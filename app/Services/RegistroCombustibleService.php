<?php

namespace App\Services;

use App\Models\RegistroCombustibleModel;
use App\Models\VehiculoModel;
use App\Models\ConductorModel;
use App\Models\DireccionModel;
use App\Models\CatalogoModel;
use App\Models\TipoMotivoCombustibleModel;
use App\Models\InvProductosModel;

class RegistroCombustibleService
{
    private RegistroCombustibleModel $model;
    private VehiculoModel $vehiculoModel;
    private ConductorModel $conductorModel;
    private DireccionModel $direccionModel;
    private CatalogoModel $catalogoModel;
    private TipoMotivoCombustibleModel $tipoMotivoModel;

    public function __construct()
    {
        $this->model           = new RegistroCombustibleModel();
        $this->vehiculoModel   = new VehiculoModel();
        $this->conductorModel  = new ConductorModel();
        $this->direccionModel  = new DireccionModel();
        $this->catalogoModel   = new CatalogoModel();
        $this->tipoMotivoModel = new TipoMotivoCombustibleModel();
    }

    public function getVehiculosParaSelect(int $empresaId): array
    {
        return $this->vehiculoModel
            ->where('id_empresa', $empresaId)
            ->where('estado', 'ACTIVO')
            ->orderBy('placa', 'ASC')
            ->findAll();
    }

    public function getConductoresParaSelect(int $empresaId): array
    {
        return $this->conductorModel
            ->where('id_empresa', $empresaId)
            ->orderBy('estado', 'ASC')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    public function getDirecciones(): array
    {
        return $this->direccionModel->getDireccionesCompletas();
    }

    public function getMotivosParaSelect(int $empresaId): array
    {
        return $this->tipoMotivoModel->getTiposActivosParaSelect($empresaId);
    }

    public function getCatalogosCRC(): array
    {
        return $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0014');
    }

    public function getRegistros(array $filtros = []): array
    {
        return $this->model->getRegistrosConRelaciones($filtros);
    }

    public function getRegistroPorId(int $id): ?array
    {
        return $this->model->getRegistroConRelaciones($id) ?: null;
    }

    /**
     * Estadísticas para las cards del index (solo consumo del mes actual por defecto).
     */
    public function getEstadisticasIndex(array $filtros = []): array
    {
        $fechaHoy = date('Y-m-d');
        $fechaDesde = $filtros['fecha_desde'] ?? $fechaHoy;
        $fechaHasta = $filtros['fecha_hasta'] ?? $fechaHoy;
        $idVehiculo = $filtros['vehiculo'] ?? null;
        $usuario = $filtros['usuario'] ?? null;
        
        $stats = $this->model->getEstadisticasConsumo($idVehiculo, $fechaDesde, $fechaHasta, $usuario);
        $pendientesSAG = count($this->model->getPendientesSAG());

        return [
            'total'          => (int)  ($stats['total_registros']       ?? 0),
            'total_litros'   => round((float)($stats['total_litros']    ?? 0), 2),
            'total_vehiculos'=> (int)  ($stats['total_vehiculos']  ?? 0),
            'pendientes_sag' => $pendientesSAG,
        ];
    }

    /**
     * Valida que los litros despachados no excedan la capacidad del tanque.
     */
    private function validarCapacidadTanque(array $data): ?string
    {
        $vehiculo = $this->vehiculoModel->find($data['id_vehiculo'] ?? 0);
        if (!$vehiculo) return null;

        $maxCap = (float) ($vehiculo['max_combustible'] ?? 0);
        $litros = (float) ($data['cantidad_litros'] ?? 0);

        if ($maxCap > 0 && $litros > $maxCap) {
            return sprintf(
                'Los %.2f L despachados exceden la capacidad del tanque (%.2f L). Verifique la cantidad o seleccione el vehículo correcto.',
                $litros,
                $maxCap
            );
        }
        return null;
    }

    /**
     * Crea un registro, actualiza el km del vehículo y envía al SAG.
     * Retorna ['success' => bool, 'id' => int|null, 'message' => string|null, 'errors' => array]
     */
    public function crearRegistro(array $data): array
    {
        $data['enviado'] = 0;

        // Redondear a 2 decimales para evitar error de columna DECIMAL en BD
        if (isset($data['cantidad_litros'])) {
            $data['cantidad_litros'] = round((float)$data['cantidad_litros'], 2);
        }
        if (isset($data['medicion'])) {
            $data['medicion'] = round((float)$data['medicion'], 2);
        }
        if (isset($data['combustible_tanque'])) {
            $data['combustible_tanque'] = round((float)$data['combustible_tanque'], 2);
        }

        log_message('error', '[RC Service] Intentando crearRegistro. Data: ' . json_encode($data));

        $errCap = $this->validarCapacidadTanque($data);
        if ($errCap) {
            log_message('error', '[RC Service] Validacion capacidad falló: ' . $errCap);
            return ['success' => false, 'message' => $errCap];
        }

        $vehiculo = $this->vehiculoModel->find($data['id_vehiculo']);
        // Lógica: requiere kilometraje solo si tipo_consumo === '6'
        $requiereKm = $vehiculo && (string)($vehiculo['tipo_consumo'] ?? '') === '6';

        if ($requiereKm && !$this->model->validarKilometraje($data)) {
            log_message('error', '[RC Service] Validacion kilometraje falló. Ant: ' . ($data['kilometraje_anterior'] ?? 'null') . ', Act: ' . ($data['kilometraje_actual'] ?? 'null'));
            return ['success' => false, 'message' => 'El kilometraje actual debe ser mayor al anterior'];
        }

        if (!$this->model->save($data)) {
            log_message('error', '[RC Service] Model save falló. Errores: ' . json_encode($this->model->errors()));
            return ['success' => false, 'message' => 'Error al guardar el registro', 'errors' => $this->model->errors()];
        }

        $nuevoId = $this->model->getInsertID();

        if ($requiereKm) {
            $this->vehiculoModel->update($data['id_vehiculo'], [
                'kilometraje'  => $data['kilometraje_actual'],
                'usuarioEdita' => $data['usuario_crea'] ?? 'admin',
                'fechaUpdate'  => date('Y-m-d H:i:s'),
            ]);
        }

        $registroGuardado = $this->model->getRegistroConRelaciones($nuevoId);

        // FB-03: temporalmente se envía a SAG aunque esté bloqueado, mientras no haya supervisor activo.
        // Para reactivar el control del supervisor, descomentar el bloque IF/ELSE y comentar estas 3 líneas.
        $resSAG = $this->enviarSAG($nuevoId, $registroGuardado);
        if (!$resSAG['success']) {
            log_message('warning', '[RC Service] SAG pendiente tras crearRegistro(). id=' . $nuevoId . ': ' . ($resSAG['message'] ?? 'Error desconocido'));
        }

        // --- BLOQUE A REACTIVAR CUANDO HAYA SUPERVISOR ---
        // if (($registroGuardado['estado'] ?? 'APROBADO') !== 'BLOQUEADO') {
        //     $resSAG = $this->enviarSAG($nuevoId, $registroGuardado);
        //     if (!$resSAG['success']) {
        //         log_message('warning', '[RC Service] SAG pendiente tras crearRegistro(). id=' . $nuevoId . ': ' . $resSAG['message']);
        //     }
        // } else {
        //     log_message('info', '[RC Service] Registro bloqueado, SAG aplazado hasta aprobación del supervisor. id=' . $nuevoId);
        //     // helper('email');
        //     // \notificarSupervisorRegistroBloqueado($registroGuardado);
        // }
        // --- FIN BLOQUE SUPERVISOR ---

        return [
            'success' => true,
            'id' => $nuevoId,
            'sag_success' => (bool) ($resSAG['success'] ?? false),
            'sag_message' => $resSAG['message'] ?? 'No se pudo confirmar el envío al SAG',
        ];
    }

    /**
     * Actualiza un registro y sincroniza el km del vehículo.
     */
    public function actualizarRegistro(int $id, array $data): array
    {
        // Redondear a 2 decimales para evitar error de columna DECIMAL en BD
        if (isset($data['cantidad_litros'])) {
            $data['cantidad_litros'] = round((float)$data['cantidad_litros'], 2);
        }
        if (isset($data['medicion'])) {
            $data['medicion'] = round((float)$data['medicion'], 2);
        }
        if (isset($data['combustible_tanque'])) {
            $data['combustible_tanque'] = round((float)$data['combustible_tanque'], 2);
        }

        $errCap = $this->validarCapacidadTanque($data);
        if ($errCap) {
            return ['success' => false, 'message' => $errCap];
        }

        $vehiculo = $this->vehiculoModel->find($data['id_vehiculo']);
        // Lógica: requiere kilometraje solo si tipo_consumo === '6'
        $requiereKm = $vehiculo && (string)($vehiculo['tipo_consumo'] ?? '') === '6';

        if ($requiereKm && !$this->model->validarKilometraje($data)) {
            return ['success' => false, 'message' => 'El kilometraje actual debe ser mayor al anterior'];
        }

        if (!$this->model->update($id, $data)) {
            return ['success' => false, 'message' => 'Error al actualizar el registro', 'errors' => $this->model->errors()];
        }

        if ($requiereKm) {
            $this->vehiculoModel->update($data['id_vehiculo'], [
                'kilometraje'  => $data['kilometraje_actual'],
                'usuarioEdita' => $data['usuario_edita'] ?? 'admin',
                'fechaUpdate'  => date('Y-m-d H:i:s'),
            ]);
        }

        return ['success' => true];
    }

    public function eliminarRegistro(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    /**
     * Obtiene registros bloqueados pendientes de aprobación del supervisor.
     */
    public function getRegistrosBloqueados(): array
    {
        return $this->model->getRegistrosConRelaciones(['estado' => 'BLOQUEADO']);
    }

    /**
     * Aprueba un registro bloqueado y lo envía al SAG.
     * Retorna ['success' => bool, 'message' => string]
     */
    public function aprobarRegistro(int $id): array
    {
        $registro = $this->model->getRegistroConRelaciones($id);
        if (!$registro) {
            return ['success' => false, 'message' => 'Registro no encontrado'];
        }
        if ($registro['estado'] !== 'BLOQUEADO') {
            return ['success' => false, 'message' => 'El registro no está bloqueado'];
        }

        $this->model->update($id, [
            'estado'        => 'APROBADO',
            'usuario_edita' => session()->get('usuario') ?? 'admin',
            'fecha_actualiza' => date('Y-m-d H:i:s'),
        ]);

        $resSAG = $this->enviarSAG($id, $registro);
        if (!$resSAG['success']) {
            log_message('warning', '[RC Service] SAG falló tras aprobar. id=' . $id . ': ' . $resSAG['message']);
            return ['success' => false, 'message' => 'Aprobado localmente pero falló envío a SAG: ' . $resSAG['message']];
        }

        return ['success' => true, 'message' => 'Registro aprobado y enviado a SAG correctamente'];
    }

    /**
     * Rechaza un registro bloqueado.
     * Retorna ['success' => bool, 'message' => string]
     */
    public function rechazarRegistro(int $id, string $motivo): array
    {
        $registro = $this->model->getRegistroConRelaciones($id);
        if (!$registro) {
            return ['success' => false, 'message' => 'Registro no encontrado'];
        }
        if ($registro['estado'] !== 'BLOQUEADO') {
            return ['success' => false, 'message' => 'El registro no está bloqueado'];
        }
        if (empty($motivo)) {
            return ['success' => false, 'message' => 'Debe indicar un motivo de rechazo'];
        }

        $this->model->update($id, [
            'estado'         => 'RECHAZADO',
            'motivo_rechazo' => $motivo,
            'usuario_edita'  => session()->get('usuario') ?? 'admin',
            'fecha_actualiza' => date('Y-m-d H:i:s'),
        ]);

        log_message('info', '[RC Service] Registro rechazado por supervisor. id=' . $id);
        return ['success' => true, 'message' => 'Registro rechazado. El operador debe crear una nueva orden.'];
    }

    /**
     * Reintenta el envío al SAG para un registro existente.
     */
    public function reenviarAlSAG(int $id): array
    {
        $registro = $this->model->getRegistroConRelaciones($id);
        if (!$registro) {
            return ['success' => false, 'message' => 'Registro no encontrado'];
        }
        return $this->enviarSAG($id, $registro);
    }

    /**
     * Devuelve el siguiente número de recibo (NUMERO_DCTO) desde SQL Server
     * usando el SP SP_INV_OBTENER_NUMERO_DCTO para el movimiento '10'.
     */
    public function obtenerNumeroReciboPreview(): int
    {
        try {
            $invModel = new InvProductosModel();
            return $invModel->obtenerNumeroDcto('10');
        } catch (\Throwable $e) {
            log_message('error', '[RC Service] obtenerNumeroReciboPreview error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Integración SAG (SQL Server): encabezado + detalle de combustible.
     */
    private function enviarSAG(int $id, array $registro): array
    {
        try {
            // FB-05: evitar envíos duplicados a SQL Server
            if ((int)($registro['enviado'] ?? 0) === 1 && (int)($registro['numero_ingreso_sag'] ?? 0) > 0) {
                log_message('info', "[RC Service] SAG ya enviado anteriormente. id={$id}");
                return ['success' => true, 'message' => 'Registro ya enviado a SAG previamente'];
            }

            // Validar que la cantidad sea mayor que cero antes de enviar al SAG
            $cantidadLitros = (float)($registro['cantidad_litros'] ?? 0);
            if ($cantidadLitros <= 0) {
                $msg = 'La cantidad de litros debe ser mayor que cero para enviar al SAG';
                log_message('warning', "[RC Service] SAG omitido. id={$id}: {$msg}");
                return ['success' => false, 'message' => $msg];
            }

            $invModel    = new InvProductosModel();
            $catalogoModel = new CatalogoModel();

            $catalogo   = !empty($registro['referencia2']) ? $catalogoModel->find($registro['referencia2']) : null;
           // $idConcepto = $catalogo['referencia'] ?? null;
            log_message('debug', "[RC Service] SAG catalogo id={$registro['referencia2']}: " . json_encode($catalogo));

            $vehiculo = $this->vehiculoModel->find($registro['id_vehiculo']);
            if (!$vehiculo) {
                return ['success' => false, 'message' => 'No se encontró el vehículo asociado al registro'];
            }
            $idConcepto = $vehiculo['tipo_consumo'] ?? null;
            $codigoCentro = $vehiculo['codigo_centro_costo'] ?? null;
            log_message('debug', "[RC Service] SAG vehiculo id={$registro['id_vehiculo']}: centro={$codigoCentro}");

            $cantidadGalones = round($cantidadLitros / 3.78541, 2); // SAG trabaja en galones
            $montoUsd       = (float)($registro['monto_usd'] ?? 0);
            $costoUnitario  = ($cantidadGalones > 0 && $montoUsd > 0)
                ? round($montoUsd / $cantidadGalones, 5)
                : 0;

            $numeroIngresoExistente = (int)($registro['numero_ingreso_sag'] ?? 0);
            if ($numeroIngresoExistente > 0) {
                log_message('info', "[RC Service] SAG reintento: reutilizando NUMERO_INGRESO={$numeroIngresoExistente} para id={$id}");
                $codigoMovFinal     = '10';
                $numeroIngresoFinal = $numeroIngresoExistente;
            } else {
                $resE = $invModel->insertarEncabezadoCombustible([
                    'fecha_registro' => $registro['fecha_registro'],
                    'placa'          => $registro['placa'] ?? '',
                    'nombreCliente'  => $registro['nombreCliente'] ?? '',
                    'despachador'    => session()->get('usuario') ?? null,
                    'observaciones'  => $registro['observaciones'] ?? '',
                    'codigo_centro'  => $codigoCentro,
                    'id_concepto'    => $idConcepto,
                ]);

                if (!$resE['success']) {
                    $msg = '[Encabezado] ' . ($resE['message'] ?? 'Error desconocido');
                    // Si el encabezado ya existe, intentar recuperar el NUMERO_INGRESO
                    if (stripos($msg, 'ya existe un encabezado') !== false) {
                        $placa = $registro['placa'] ?? '';
                        $numeroRecuperado = $invModel->buscarNumeroIngresoExistente($placa);
                        if ($numeroRecuperado > 0) {
                            log_message('info', "[RC Service] SAG encabezado ya existe. id={$id}: NUMERO_INGRESO recuperado={$numeroRecuperado}");
                            $codigoMovFinal     = '10';
                            $numeroIngresoFinal = $numeroRecuperado;
                            $this->model->update($id, ['numero_ingreso_sag' => $numeroRecuperado]);
                            // Continuar al detalle abajo
                        } else {
                            log_message('warning', "[RC Service] SAG encabezado ya existe pero no se pudo recuperar NUMERO_INGRESO. id={$id}: marcando como enviado");
                            $this->model->marcarEnvioSAG($id, 1);
                            return ['success' => true, 'message' => 'Registro ya existía en SAG (encabezado previamente creado)'];
                        }
                    } else {
                        log_message('error', "[RC Service] SAG encabezado fallo. id={$id}: {$msg}");
                        $this->model->marcarEnvioSAG($id, 0);
                        return ['success' => false, 'message' => $msg];
                    }
                } else {
                    $codigoMovFinal     = (string)$resE['codigo_mov'];
                    $numeroIngresoFinal = (int)$resE['numero_ingreso'];
                    $this->model->update($id, ['numero_ingreso_sag' => $numeroIngresoFinal]);
                }
            }

            $resD = $invModel->insertarDetalleCombustible([
                'cantidad_litros' => $cantidadGalones, // SAG recibe galones
                'costo_unitario'  => $costoUnitario,
                'codigo_producto' => 'CO-0000007',
            ], $codigoMovFinal, $numeroIngresoFinal);

            if (!$resD['success']) {
                $msg = '[Detalle] ' . ($resD['message'] ?? 'Error desconocido');
                // Si el detalle ya existe (producto ya registrado), el registro fue enviado antes
                if (stripos($msg, 'ya tiene un producto registrado') !== false || stripos($msg, 'La cantidad debe ser mayor que cero') !== false) {
                    log_message('warning', "[RC Service] SAG detalle ya existe o cantidad invalida. id={$id}: marcando como enviado");
                    $this->model->marcarEnvioSAG($id, 1);
                    return ['success' => true, 'message' => 'Registro ya existía en SAG (detalle previamente creado)'];
                }
                log_message('error', "[RC Service] SAG detalle fallo. id={$id}: {$msg}");
                $this->model->marcarEnvioSAG($id, 0);
                return ['success' => false, 'message' => $msg];
            }

            $this->model->marcarEnvioSAG($id, 1);
            log_message('info', "[RC Service] SAG enviado OK. id={$id}");
            return ['success' => true, 'message' => 'Éxito: se envió el registro al SAG correctamente'];

        } catch (\Throwable $e) {
            $msg = trim($e->getMessage()) !== '' ? $e->getMessage() : 'Error inesperado durante la comunicación con SAG';
            log_message('error', '[RC Service] enviarSAG excepcion id=' . $id . ': ' . $msg);
            $this->model->marcarEnvioSAG($id, 0);
            return ['success' => false, 'message' => 'No se pudo enviar a SAG: ' . $msg];
        }
    }
}
