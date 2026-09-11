<?php

namespace App\Services;

use App\Models\LecturaBombaModel;
use App\Models\VehiculoModel;

class LecturaBombaService
{
    private LecturaBombaModel $model;
    private VehiculoModel $vehiculoModel;

    public function __construct()
    {
        $this->model = new LecturaBombaModel();
        $this->vehiculoModel = new VehiculoModel();
    }

    /**
     * Obtiene los centros de costo disponibles para selección
     */
    public function getCentrosCostoParaSelect(): array
    {
        return $this->vehiculoModel->getCentrosCostoParaSelect();
    }

    /**
     * Verifica si existe una apertura pendiente de cierre para un centro de costo
     */
    public function verificarAperturaPendiente(int $idCentroCosto): ?array
    {
        return $this->model->getAperturaPendiente($idCentroCosto);
    }

    /**
     * Obtiene todas las aperturas pendientes de cierre
     */
    public function getAperturasPendientes($usuario = null): array
    {
        return $this->model->getAperturasPendientes($usuario);
    }

    /**
     * Obtiene todas las lecturas (abiertas y cerradas)
     */
    public function getTodasLasLecturas(): array
    {
        return $this->model->getTodasLasLecturas();
    }

    /**
     * Obtiene la apertura activa más reciente para el monitor
     */
    public function getAperturaActiva(): ?array
    {
        $aperturas = $this->model->getAperturasPendientes();
        return !empty($aperturas) ? $aperturas[0] : null;
    }

    /**
     * Obtiene el consumo real de combustible para una apertura
     * Sumando los litros de los registros de combustible asociados
     */
    public function getConsumoReal(int $aperturaId): float
    {
        $db = \Config\Database::connect();
        $result = $db->query("
            SELECT COALESCE(SUM(cantidad_litros), 0) as total
            FROM registro_combustible
            WHERE id_lectura = ?
        ", [$aperturaId])->getRow();

        $consumo = $result ? (float)$result->total : 0;
        log_message('info', "Consumo real para apertura {$aperturaId}: {$consumo} litros");

        return $consumo;
    }

    /**
     * Obtiene el historial de aperturas/cierres de un centro de costo
     */
    public function getHistorialCentroCosto(int $idCentroCosto, int $limit = 50): array
    {
        return $this->model->getHistorialCentroCosto($idCentroCosto, $limit);
    }

    /**
     * Crea una nueva apertura de bomba
     * Convierte litros a galones automáticamente
     */
    public function crearApertura(array $data): array
    {
        log_message('info', 'LecturaBombaService::crearApertura - Datos recibidos: ' . json_encode($data));

        // Constante de conversión (1 galón = 3.785 litros)
        $L_TO_GAL = 3.785;

        // Calcular galones si no se proporcionaron
        if (!isset($data['lectura_inicial_galones']) && isset($data['lectura_inicial_litros'])) {
            $data['lectura_inicial_galones'] = (float)$data['lectura_inicial_litros'] / $L_TO_GAL;
        }

        // Guardar litraje inicial si se proporciona
        if (isset($data['litraje_inicial_ltr'])) {
            $data['litraje_inicial_ltr'] = (float)$data['litraje_inicial_ltr'];
        }
        if (isset($data['litraje_inicial_gal'])) {
            $data['litraje_inicial_gal'] = (float)$data['litraje_inicial_gal'];
        }

        // Validar que exista foto
        if (empty($data['foto'])) {
            log_message('error', 'LecturaBombaService::crearApertura - Foto vacía');
            return ['success' => false, 'message' => 'La foto del contador es obligatoria'];
        }

        // Validar observación si hay anomalía
        if (isset($data['estado']) && $data['estado'] === 'anomalia' && empty($data['observaciones'])) {
            return ['success' => false, 'message' => 'Las observaciones son obligatorias cuando hay anomalía'];
        }

        // Verificar si ya existe apertura pendiente
        $aperturaPendiente = $this->verificarAperturaPendiente($data['id_centro_costo']);
        if ($aperturaPendiente) {
            return [
                'success' => false,
                'message' => 'Ya existe una apertura pendiente de cierre',
                'apertura_pendiente' => $aperturaPendiente
            ];
        }

        // Establecer fecha de apertura
        $data['fecha_apertura'] = date('Y-m-d H:i:s');

        log_message('info', 'LecturaBombaService::crearApertura - Intentando insertar: ' . json_encode($data));

        if (!$this->model->insert($data)) {
            log_message('error', 'LecturaBombaService::crearApertura - Error al insertar: ' . json_encode($this->model->errors()));
            return ['success' => false, 'message' => 'Error al guardar la apertura', 'errors' => $this->model->errors()];
        }

        $nuevoId = $this->model->getInsertID();
        log_message('info', 'LecturaBombaService::crearApertura - Insertado exitosamente ID: ' . $nuevoId);

        // Si hay anomalía, enviar correo al supervisor
        if (isset($data['estado']) && $data['estado'] === 'anomalia') {
            $this->enviarCorreoAnomalia($nuevoId, $data);
        }

        return ['success' => true, 'id' => $nuevoId];
    }

    /**
     * Cierra una apertura existente
     * Calcula consumo del turno
     */
    public function cerrarApertura(int $id, array $data): array
    {
        $apertura = $this->model->getAperturaById($id);
        if (!$apertura) {
            return ['success' => false, 'message' => 'Apertura no encontrada'];
        }

        if (!empty($apertura['fecha_cierre'])) {
            return ['success' => false, 'message' => 'Esta apertura ya está cerrada'];
        }

        // Constante de conversión (1 galón = 3.785 litros)
        $L_TO_GAL = 3.785;

        // Calcular galones si no se proporcionaron
        if (!isset($data['lectura_final_galones']) && isset($data['lectura_final_litros'])) {
            $data['lectura_final_galones'] = (float)$data['lectura_final_litros'] / $L_TO_GAL;
        }

        // Guardar litraje final si se proporciona
        if (isset($data['litraje_final_ltr'])) {
            $data['litraje_final_ltr'] = (float)$data['litraje_final_ltr'];
        }
        if (isset($data['litraje_final_gal'])) {
            $data['litraje_final_gal'] = (float)$data['litraje_final_gal'];
        }

        // Validar que lectura final sea mayor a inicial
        if (!$this->model->validarLecturaFinal($data['lectura_final_litros'], $apertura['lectura_inicial_litros'])) {
            return ['success' => false, 'message' => 'La lectura final debe ser mayor a la lectura inicial'];
        }

        // Calcular consumo del turno
        $data['consumo_turno_litros'] = $data['lectura_final_litros'] - $apertura['lectura_inicial_litros'];
        $data['consumo_turno_galones'] = $data['lectura_final_galones'] - $apertura['lectura_inicial_galones'];

        // Establecer fecha de cierre
        $data['fecha_cierre'] = date('Y-m-d H:i:s');

        // Asegurar que ingreso_tanque y salida_despachada tengan valores por defecto
        $data['ingreso_tanque'] = $data['ingreso_tanque'] ?? 0;
        $data['salida_despachada'] = $data['salida_despachada'] ?? 0;

        if (!$this->model->update($id, $data)) {
            return ['success' => false, 'message' => 'Error al cerrar la apertura', 'errors' => $this->model->errors()];
        }

        return [
            'success' => true,
            'consumo_litros' => $data['consumo_turno_litros'],
            'consumo_galones' => $data['consumo_turno_galones']
        ];
    }

    /**
     * Obtiene una apertura por ID
     */
    public function getAperturaById(int $id): ?array
    {
        return $this->model->getAperturaById($id);
    }

    /**
     * Reporte de conciliación de combustible.
     * Reutilizable: con ID específico o con rango de fechas.
     *
     * @param int|null    $id        ID de lectura_bomba (opcional)
     * @param string|null $fechaIni  Fecha inicial YYYY-MM-DD (opcional)
     * @param string|null $fechaFin  Fecha final YYYY-MM-DD (opcional)
     * @param int|null    $idCentro  Centro de costo / bomba (opcional)
     * @return array
     */
    public function getReporteConciliacion(?int $id = null, ?string $fechaIni = null, ?string $fechaFin = null, ?int $idCentro = null): array
    {
        return $this->model->getReporteConciliacion($id, $fechaIni, $fechaFin, $idCentro);
    }

    /**
     * Obtiene estadísticas para el dashboard
     */
    public function getEstadisticas(): array
    {
        $aperturasPendientes = $this->getAperturasPendientes();
        $todasLecturas = $this->model->findAll();
        
        $totalAperturas = count($todasLecturas);
        $totalCerradas = 0;
        $totalAnomalias = 0;
        $consumoTotalLitros = 0;
        $consumoTotalGalones = 0;
        
        foreach ($todasLecturas as $lectura) {
            if (!empty($lectura['fecha_cierre'])) {
                $totalCerradas++;
                if (!empty($lectura['consumo_turno_litros'])) {
                    $consumoTotalLitros += $lectura['consumo_turno_litros'];
                }
                if (!empty($lectura['consumo_turno_galones'])) {
                    $consumoTotalGalones += $lectura['consumo_turno_galones'];
                }
            }
            if ($lectura['estado'] === 'anomalia') {
                $totalAnomalias++;
            }
        }
        
        // Consumo promedio por turno
        $consumoPromedioLitros = $totalCerradas > 0 ? $consumoTotalLitros / $totalCerradas : 0;
        $consumoPromedioGalones = $totalCerradas > 0 ? $consumoTotalGalones / $totalCerradas : 0;
        
        // Lecturas por bomba (centro de costo)
        $lecturasPorBomba = [];
        foreach ($todasLecturas as $lectura) {
            $idCentro = $lectura['id_centro_costo'];
            if (!isset($lecturasPorBomba[$idCentro])) {
                $lecturasPorBomba[$idCentro] = [
                    'id' => $idCentro,
                    'total' => 0,
                    'cerradas' => 0,
                    'anomalias' => 0
                ];
            }
            $lecturasPorBomba[$idCentro]['total']++;
            if (!empty($lectura['fecha_cierre'])) {
                $lecturasPorBomba[$idCentro]['cerradas']++;
            }
            if ($lectura['estado'] === 'anomalia') {
                $lecturasPorBomba[$idCentro]['anomalias']++;
            }
        }
        
        return [
            'total_aperturas' => $totalAperturas,
            'aperturas_pendientes' => count($aperturasPendientes),
            'total_cerradas' => $totalCerradas,
            'total_anomalias' => $totalAnomalias,
            'consumo_total_litros' => $consumoTotalLitros,
            'consumo_total_galones' => $consumoTotalGalones,
            'consumo_promedio_litros' => $consumoPromedioLitros,
            'consumo_promedio_galones' => $consumoPromedioGalones,
            'lecturas_por_bomba' => array_values($lecturasPorBomba),
            'aperturas_pendientes_detalle' => $aperturasPendientes
        ];
    }

    /**
     * Envía correo al supervisor cuando hay anomalía
     * TODO: Implementar servicio de correo
     */
    private function enviarCorreoAnomalia(int $aperturaId, array $data): void
    {
        // Implementación pendiente - requiere configuración de SMTP
        log_message('info', "[LecturaBombaService] Anomalía detectada en apertura ID {$aperturaId}. Correo pendiente de implementación.");
        
        // Ejemplo de implementación futura:
        // $emailService = new EmailService();
        // $emailService->enviarAnomaliaBomba($aperturaId, $data);
    }
}
