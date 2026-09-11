<?php

namespace App\Services;

use App\Models\VehiculoModel;

/**
 * VehiculoService
 *
 * Encapsula la lógica de negocio del módulo de vehículos,
 * desacoplando el controller del Model y de las consultas externas.
 */
class VehiculoService
{
    public function __construct(
        protected VehiculoModel $model
    ) {}

    /**
     * Obtiene un vehículo completo para mostrar en vista,
     * validando que pertenezca a la empresa indicada.
     *
     * @return array|null  null si no existe o no pertenece a la empresa
     */
    public function getParaVista(int $id, int $empresaId): ?array
    {
        $vehiculo = $this->model->getVehiculoCompleto($id);

        if (!$vehiculo || (int) $vehiculo['id_empresa'] !== $empresaId) {
            return null;
        }

        return $vehiculo;
    }

    /**
     * Crea un vehículo y devuelve el ID generado, o false en fallo.
     */
    public function crear(array $data): int|false
    {
        return $this->model->crearVehiculo($data);
    }

    /**
     * Actualiza un vehículo. Devuelve true en éxito.
     */
    public function actualizar(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    /**
     * Cambia el estado de un vehículo, registrando el motivo.
     * Estados válidos: ACTIVO, INACTIVO, EN REPARACION
     *
     * @return array  ['success' => '...'] o ['error' => '...']
     */
    public function cambiarEstado(int $id, string $estado, ?string $motivo): array
    {
        return $this->model->cambiarEstado($id, $estado, $motivo);
    }

    /**
     * Devuelve los centros de costo formateados para un <select>.
     * Delega en el Model que tiene la lógica de SQL Server + timeout.
     */
    public function getCentrosCostoParaSelect(): array
    {
        return $this->model->getCentrosCostoParaSelect();
    }

    /**
     * Devuelve la info de un centro de costo por su código.
     */
    public function getCentroCosto(string $codigo): ?array
    {
        return $this->model->getCentroCostoPorCodigo($codigo) ?: null;
    }

    /**
     * Devuelve los conductores disponibles para el select del formulario.
     */
    public function getConductoresDisponibles(int $empresaId): array
    {
        return $this->model->getConductoresDisponibles($empresaId);
    }

    /**
     * Verifica si el vehículo tiene una solicitud de mantenimiento activa.
     */
    public function tieneSolicitudActiva(int $vehiculoId): bool
    {
        // Delegamos al controller por ahora; mover aquí cuando se refactorice Solicitudes
        return false;
    }

    /**
     * Devuelve el historial de estados del vehículo.
     */
    public function getHistorialEstados(int $vehiculoId): array
    {
        return $this->model->getHistorialEstados($vehiculoId);
    }

    /**
     * Elimina un vehículo por ID. Devuelve true en éxito.
     */
    public function eliminar(int $id): bool
    {
        return $this->model->delete($id);
    }

    /**
     * Columnas usadas en exportación e importación masiva (CSV).
     * Clave = encabezado del CSV, valor = campo en BD.
     */
    public const COLUMNAS_CSV = [
        'placa'               => 'placa',
        'marca'               => 'marca',
        'modelo'              => 'modelo',
        'anio'                => 'anio',
        'kilometraje'         => 'kilometraje',
        'codigo_unidad'       => 'codigo_unidad',
        'codigo_centro_costo' => 'codigo_centro_costo',
        'numero_motor'        => 'numero_motor',
        'numero_chasis'       => 'numero_chasis',
        'rendimiento'         => 'rendimiento',
        'max_combustible'     => 'max_combustible',
        'disponible'          => 'disponible',
        'compuesto'           => 'compuesto',
        'estado'              => 'estado',
    ];

    /**
     * Devuelve los vehículos de la empresa listos para exportar a CSV.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getParaExportar(int $empresaId): array
    {
        $vehiculos = $this->model
            ->where('id_empresa', $empresaId)
            ->orderBy('placa', 'ASC')
            ->findAll();

        $filas = [];
        foreach ($vehiculos as $v) {
            $fila = [];
            foreach (self::COLUMNAS_CSV as $header => $campo) {
                $fila[$header] = $v[$campo] ?? '';
            }
            $filas[] = $fila;
        }

        return $filas;
    }

    /**
     * Importa vehículos desde filas de CSV ya parseadas (upsert por placa).
     *
     * - Si la placa ya existe en la empresa → se ACTUALIZA el vehículo.
     * - Si la placa no existe → se REGISTRA un vehículo nuevo.
     * - En actualizaciones, los campos opcionales vacíos conservan su valor actual.
     *
     * Cada fila se valida individualmente; las filas con error no detienen
     * la importación del resto.
     *
     * @param array<int, array<string, string>> $filas  Filas asociativas (header => valor)
     * @return array{insertados: int, actualizados: int, errores: array<int, string>}
     */
    public function importar(array $filas): array
    {
        $insertados   = 0;
        $actualizados = 0;
        $errores      = [];
        $anioActual   = (int) date('Y');
        $empresaId    = (int) (session()->get('empresa_id') ?? 0);
        $userId       = session()->get('user_id') ?? 1;

        // Mapas de existentes para resolver upsert y duplicados sin N consultas
        $existentes = $this->model->select('id, id_empresa, placa, codigo_unidad')->findAll();
        $idPorPlaca        = []; // placa => ['id' => x, 'id_empresa' => y]
        $idPorCodigoUnidad = []; // codigo_unidad => id
        foreach ($existentes as $e) {
            $idPorPlaca[strtoupper($e['placa'])] = ['id' => (int) $e['id'], 'id_empresa' => (int) $e['id_empresa']];
            if (!empty($e['codigo_unidad'])) {
                $idPorCodigoUnidad[$e['codigo_unidad']] = (int) $e['id'];
            }
        }

        foreach ($filas as $i => $fila) {
            $numFila = $i + 2; // +2: fila 1 es el encabezado

            $placa  = strtoupper(trim($fila['placa'] ?? ''));
            $marca  = trim($fila['marca'] ?? '');
            $modelo = trim($fila['modelo'] ?? '');
            $anio   = trim($fila['anio'] ?? '');
            $codigoUnidad = trim($fila['codigo_unidad'] ?? '');
            $centroCosto  = trim($fila['codigo_centro_costo'] ?? '');
            $estado       = strtoupper(trim($fila['estado'] ?? ''));

            // ¿Existe la placa? → actualizar en vez de insertar
            $existente  = $idPorPlaca[$placa] ?? null;
            $esUpdate   = $existente !== null;
            $idVehiculo = $existente['id'] ?? null;

            // ── Validaciones por fila ──
            $err = [];
            if ($placa === '' || strlen($placa) < 6 || strlen($placa) > 20) {
                $err[] = 'placa requerida (6-20 caracteres)';
            } elseif ($esUpdate && $existente['id_empresa'] !== $empresaId) {
                $err[] = "placa '{$placa}' pertenece a otra empresa";
            }

            if ($esUpdate) {
                // NOTA: validaciones comentadas a pedido del negocio.
                // El update se resuelve SOLO por placa: si la placa existe,
                // los datos se actualizan directo sin validar formato.
                // if ($marca !== '' && strlen($marca) > 50)   $err[] = 'marca inválida (máx 50)';
                // if ($modelo !== '' && strlen($modelo) > 50) $err[] = 'modelo inválido (máx 50)';
                // if ($anio !== '' && (!ctype_digit($anio) || (int) $anio <= 1900 || (int) $anio > $anioActual)) {
                //     $err[] = "anio inválido (1901-{$anioActual})";
                // }
            } else {
                // En registro nuevo siguen siendo obligatorios
                if ($marca === '' || strlen($marca) > 50)   $err[] = 'marca requerida (máx 50)';
                if ($modelo === '' || strlen($modelo) > 50) $err[] = 'modelo requerido (máx 50)';
                if (!ctype_digit($anio) || (int) $anio <= 1900 || (int) $anio > $anioActual) {
                    $err[] = "anio inválido (1901-{$anioActual})";
                }
                if ($codigoUnidad === '') $err[] = 'codigo_unidad requerido (máx 50)';
            }

            // Validaciones de formato: solo aplican al REGISTRAR.
            // En updates por placa están comentadas (pedido del negocio).
            if (!$esUpdate) {
                if ($codigoUnidad !== '') {
                    if (strlen($codigoUnidad) > 50) {
                        $err[] = 'codigo_unidad inválido (máx 50)';
                    } elseif (isset($idPorCodigoUnidad[$codigoUnidad]) && $idPorCodigoUnidad[$codigoUnidad] !== $idVehiculo) {
                        $err[] = "codigo_unidad '{$codigoUnidad}' ya existe en otro vehículo";
                    }
                }
                if ($centroCosto !== '' && strlen($centroCosto) > 20) {
                    $err[] = 'codigo_centro_costo inválido (máx 20)';
                }
                if ($estado !== '' && !in_array($estado, ['ACTIVO', 'INACTIVO', 'EN REPARACION'], true)) {
                    $err[] = "estado inválido '{$estado}' (ACTIVO, INACTIVO, EN REPARACION)";
                }
            }

            $kilometraje = trim($fila['kilometraje'] ?? '');
            if (!$esUpdate && $kilometraje !== '' && (!is_numeric($kilometraje) || (float) $kilometraje < 0)) {
                $err[] = 'kilometraje inválido';
            }
            $rendimiento = trim($fila['rendimiento'] ?? '');
            if (!$esUpdate && $rendimiento !== '' && !is_numeric($rendimiento)) $err[] = 'rendimiento inválido';
            $maxCombustible = trim($fila['max_combustible'] ?? '');
            if (!$esUpdate && $maxCombustible !== '' && !is_numeric($maxCombustible)) $err[] = 'max_combustible inválido';

            $disponible = trim($fila['disponible'] ?? '');
            $compuesto  = trim($fila['compuesto'] ?? '');

            if ($err) {
                $errores[$numFila] = implode('; ', $err);
                continue;
            }

            if ($esUpdate) {
                // ── Actualizar: solo los campos que vienen con valor ──
                $data = ['usuarioEdita' => $userId, 'fechaUpdate' => date('Y-m-d H:i:s')];
                if ($marca !== '')          $data['marca'] = ucwords($marca);
                if ($modelo !== '')         $data['modelo'] = ucwords($modelo);
                if ($anio !== '')           $data['anio'] = (int) $anio;
                if ($kilometraje !== '')    $data['kilometraje'] = (int) $kilometraje;
                if ($codigoUnidad !== '')   $data['codigo_unidad'] = $codigoUnidad;
                if ($centroCosto !== '')    $data['codigo_centro_costo'] = $centroCosto;
                if (trim($fila['numero_motor'] ?? '') !== '')  $data['numero_motor'] = trim($fila['numero_motor']);
                if (trim($fila['numero_chasis'] ?? '') !== '') $data['numero_chasis'] = trim($fila['numero_chasis']);
                if ($rendimiento !== '')    $data['rendimiento'] = $rendimiento;
                if ($maxCombustible !== '') $data['max_combustible'] = $maxCombustible;
                if (in_array($disponible, ['0', '1'], true)) $data['disponible'] = (int) $disponible;
                if (in_array($compuesto, ['0', '1'], true))  $data['compuesto'] = (int) $compuesto;
                if ($estado !== '')         $data['estado'] = $estado;

                if ($this->model->update($idVehiculo, $data)) {
                    $actualizados++;
                    if ($codigoUnidad !== '') $idPorCodigoUnidad[$codigoUnidad] = $idVehiculo;
                } else {
                    $errores[$numFila] = 'Error al actualizar en base de datos';
                }
                continue;
            }

            // ── Registrar nuevo ──
            $data = [
                'placa'               => $placa,
                'marca'               => ucwords($marca),
                'modelo'              => ucwords($modelo),
                'anio'                => (int) $anio,
                'kilometraje'         => $kilometraje !== '' ? (int) $kilometraje : 0,
                'codigo_unidad'       => $codigoUnidad,
                'codigo_centro_costo' => $centroCosto !== '' ? $centroCosto : null,
                'numero_motor'        => trim($fila['numero_motor'] ?? '') ?: null,
                'numero_chasis'       => trim($fila['numero_chasis'] ?? '') ?: null,
                'rendimiento'         => $rendimiento !== '' ? $rendimiento : null,
                'max_combustible'     => $maxCombustible !== '' ? $maxCombustible : null,
                'disponible'          => in_array($disponible, ['0', '1'], true) ? (int) $disponible : 1,
                'compuesto'           => $compuesto === '1' ? 1 : 0,
                'estado'              => $estado !== '' ? $estado : 'ACTIVO',
            ];

            $id = $this->model->crearVehiculo($data);

            if ($id) {
                $insertados++;
                // Registrar para resolver duplicados/upserts dentro del mismo archivo
                $idPorPlaca[$placa] = ['id' => (int) $id, 'id_empresa' => $empresaId];
                if ($codigoUnidad !== '') $idPorCodigoUnidad[$codigoUnidad] = (int) $id;
            } else {
                $errores[$numFila] = 'Error al insertar en base de datos';
            }
        }

        return ['insertados' => $insertados, 'actualizados' => $actualizados, 'errores' => $errores];
    }
}
