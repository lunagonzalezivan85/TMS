<?php

namespace App\Models;

use CodeIgniter\Model;

class AsignacionVehiculoModel extends BaseModel
{
    protected $table = 'asignacion_vehiculos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_vehiculo',
        'id_conductor',
        'fecha_asignacion',
        'observaciones',
        'estado',
        'usuario_crea',
        'usuario_edita',
        'fecha_registro',
        'fecha_actualizacion',
        'motivo_desasignacion',
        'fecha_desasignacion',
        'id_empresa'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualizacion';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setAuditFields'];
    protected $beforeUpdate = ['setUpdateFields'];

    /**
     * Establecer campos de auditoría antes de insertar
     */
    protected function setAuditFields(array $data)
    {
        $session = session();
        $userId = $session->get('user_id') ?? 1;
        $empresaId = $session->get('empresa_id') ?? 1;

        $data['data']['id_empresa'] = $empresaId;
        $data['data']['usuario_crea'] = $userId;
        $data['data']['usuario_edita'] = $userId;
        $data['data']['fecha_registro'] = date('Y-m-d H:i:s');
        $data['data']['fecha_actualizacion'] = date('Y-m-d H:i:s');
        $data['data']['estado'] = $data['data']['estado'] ?? 'ACTIVA';

        return $data;
    }

    /**
     * Establecer campos de auditoría antes de actualizar
     */
    protected function setUpdateFields(array $data)
    {
        $session = session();
        $userId = $session->get('user_id') ?? 1;

        $data['data']['usuario_edita'] = $userId;
        $data['data']['fecha_actualizacion'] = date('Y-m-d H:i:s');

        return $data;
    }

    /**
     * Obtener vehículos disponibles por tipo de unidad
     */
    public function getVehiculosDisponiblesPorTipo($tipoUnidadId, $empresaId = null)
    {
        $session = session();
        $empresaId = $empresaId ?? $session->get('empresa_id');

        return $this->db->table('vehiculos v')
                       ->select('v.id, v.placa, v.marca, v.modelo, v.anio, v.kilometraje,
                                tu.descripcion as tipo_unidad_descripcion,
                                top.descripcion as tipo_operacion_descripcion,
                                CASE WHEN COUNT(dv.id) > 0 THEN 1 ELSE 0 END as tiene_documentacion')
                       ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left')
                       ->join('tipo_operacion top', 'v.idTipoOperacion = top.id', 'left')
                       ->join('documentos_vehiculos dv', 'v.id = dv.id_vehiculo', 'left')
                       ->where('v.idTipoUnidad', $tipoUnidadId)
                       ->where('v.disponible', 'DISPONIBLE')
                       ->where('v.estado', 'ACTIVO')
                       ->where('v.id_empresa', $empresaId)
                       ->whereNotIn('v.id', function($builder) {
                           return $builder->select('id_vehiculo')
                                         ->from('asignacion_vehiculos')
                                         ->where('estado', 'ACTIVA');
                       })
                       ->groupBy('v.id, v.placa, v.marca, v.modelo, v.anio, v.kilometraje, tu.descripcion, top.descripcion')
                       ->orderBy('v.placa')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Obtener conductores disponibles según el tipo de vehículo (compuesto)
     * @param int|null $empresaId ID de la empresa
     * @param int|null $vehiculoId ID del vehículo para verificar si es compuesto
     */
    public function getConductoresDisponibles($empresaId = null, $vehiculoId = null)
    {
        $session = session();
        $empresaId = $empresaId ?? $session->get('empresa_id');
        
        // Obtener información del vehículo para saber si es compuesto
        $vehiculoCompuesto = null;
        if ($vehiculoId) {
            $vehiculo = $this->db->table('vehiculos')
                                ->select('compuesto')
                                ->where('id', $vehiculoId)
                                ->get()
                                ->getRowArray();
            $vehiculoCompuesto = $vehiculo['compuesto'] ?? null;
        }

        $query = $this->db->table('conductores c')
                         ->select('c.id, CONCAT(c.nombre, " ", c.apellido) as nombre_completo, 
                                   c.dni, c.telefono, c.fechaIngreso,
                                   COALESCE(asignaciones_activas.total_asignaciones, 0) as vehiculos_asignados,
                                   COALESCE(tiene_vehiculo_no_compuesto.tiene_no_compuesto, 0) as tiene_vehiculo_no_compuesto')
                         ->join('(
                             SELECT id_conductor, COUNT(*) as total_asignaciones
                             FROM asignacion_vehiculos 
                             WHERE estado = "ACTIVA"
                             GROUP BY id_conductor
                         ) asignaciones_activas', 'c.id = asignaciones_activas.id_conductor', 'left')
                         ->join('(
                             SELECT av.id_conductor, COUNT(*) as tiene_no_compuesto
                             FROM asignacion_vehiculos av
                             INNER JOIN vehiculos v ON av.id_vehiculo = v.id
                             WHERE av.estado = "ACTIVA" AND v.compuesto = 0
                             GROUP BY av.id_conductor
                         ) tiene_vehiculo_no_compuesto', 'c.id = tiene_vehiculo_no_compuesto.id_conductor', 'left')
                         ->where('c.estado', 'ACTIVO');

        // Si el vehículo NO es compuesto (compuesto = 0), solo mostrar conductores sin asignaciones
        if ($vehiculoCompuesto === 0) {
            $query->where('COALESCE(asignaciones_activas.total_asignaciones, 0)', 0);
        }
        // Si el vehículo ES compuesto (compuesto = 1), mostrar conductores que:
        // 1. No tengan vehículos no compuestos asignados
        // 2. Tengan menos de 2 vehículos asignados en total
        else if ($vehiculoCompuesto === 1) {
            $query->where('COALESCE(tiene_vehiculo_no_compuesto.tiene_no_compuesto, 0)', 0)
                  ->where('COALESCE(asignaciones_activas.total_asignaciones, 0) <', 2);
        }
        // Si no se especifica vehículo, mostrar todos los conductores disponibles
        else {
            $query->where('COALESCE(asignaciones_activas.total_asignaciones, 0) <', 2);
        }

        return $query->orderBy('c.nombre')
                     ->get()
                     ->getResultArray();
    }

    /**
     * Obtener historial de asignaciones de un conductor
     */
    public function getHistorialConductor($conductorId)
    {
        try {
            $builder = $this->db->table('asignacion_vehiculos a');
            $builder->select('a.*, 
                             COALESCE(v.placa, "Sin placa") as placa, 
                             COALESCE(v.marca, "Sin marca") as marca, 
                             COALESCE(v.modelo, "Sin modelo") as modelo, 
                             COALESCE(v.anio, 0) as anio,
                             COALESCE(tu.descripcion, "Sin tipo") as tipo_unidad_descripcion,
                             a.fecha_asignacion')
                    ->join('vehiculos v', 'a.id_vehiculo = v.id')
                    ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id')
                    ->where('a.id_conductor', $conductorId)
                    ->orderBy('a.fecha_asignacion', 'DESC');

            $result = $builder->get()->getResultArray();
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getHistorialConductor: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener asignaciones con información completa
     */
    public function getAsignacionesCompletas($empresaId = null, $filtros = [])
    {
        try {
            $session = session();
            $empresaId = $empresaId ?? $session->get('empresa_id');

            $builder = $this->db->table('asignacion_vehiculos a');
            $builder->select('a.*, 
                             COALESCE(v.placa, "Sin placa") as placa, 
                             COALESCE(v.marca, "Sin marca") as marca, 
                             COALESCE(v.modelo, "Sin modelo") as modelo, 
                             COALESCE(v.anio, 0) as anio,
                             COALESCE(CONCAT(c.nombre, " ", c.apellido), "Sin conductor") as conductor_nombre,
                             COALESCE(c.dni, "Sin DNI") as conductor_dni,
                             COALESCE(tu.descripcion, "Sin tipo") as tipo_unidad_descripcion')
                    ->join('vehiculos v', 'a.id_vehiculo = v.id', 'left')
                    ->join('conductores c', 'a.id_conductor = c.id', 'left')
                    ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left');

            // Aplicar filtros
            if (!empty($filtros['estado'])) {
                $builder->where('a.estado', $filtros['estado']);
            }

            if (!empty($filtros['tipo_unidad'])) {
                $builder->where('v.idTipoUnidad', $filtros['tipo_unidad']);
            }

            if (!empty($filtros['conductor'])) {
                $builder->where('a.id_conductor', $filtros['conductor']);
            }

            $result = $builder->orderBy('a.fecha_asignacion', 'DESC')
                            ->get()
                            ->getResultArray();

            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getAsignacionesCompletas: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener asignación completa por ID
     */
    public function getAsignacionCompleta($id)
    {
        return $this->db->table('asignacion_vehiculos a')
                       ->select('a.*, 
                                v.placa, v.marca, v.modelo, v.anio, v.kilometraje,
                                CONCAT(c.nombre, " ", c.apellido) as conductor_nombre,
                                c.dni as conductor_dni, c.telefono as conductor_telefono,
                                tu.descripcion as tipo_unidad_descripcion,
                                top.descripcion as tipo_operacion_descripcion,
                                uc.nombre as usuario_crea_nombre,
                                ue.nombre as usuario_edita_nombre')
                       ->join('vehiculos v', 'a.id_vehiculo = v.id', 'left')
                       ->join('conductores c', 'a.id_conductor = c.id', 'left')
                       ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left')
                       ->join('tipo_operacion top', 'v.idTipoOperacion = top.id', 'left')
                       ->join('usuarios uc', 'a.usuario_crea = uc.id', 'left')
                       ->join('usuarios ue', 'a.usuario_edita = ue.id', 'left')
                       ->where('a.id', $id)
                       ->get()
                       ->getRowArray();
    }

    /**
     * Crear asignación de vehículo con validación basada en campo compuesto
     */
    public function crearAsignacion($data)
    {
        $this->db->transStart();

        try {
            // Obtener información del vehículo para verificar si es compuesto
            $vehiculo = $this->db->table('vehiculos')
                                ->select('compuesto, disponible')
                                ->where('id', $data['id_vehiculo'])
                                ->get()
                                ->getRowArray();
        
            if (!$vehiculo) {
                throw new \Exception('El vehículo seleccionado no existe');
            }
        
            // Validar que el vehículo no esté ya asignado
            $vehiculoAsignado = $this->verificarVehiculoAsignado($data['id_vehiculo']);
            if ($vehiculoAsignado) {
                throw new \Exception('El vehículo ya está asignado a otro conductor');
            }
        
            // Obtener asignaciones actuales del conductor
            $asignacionesActuales = $this->contarAsignacionesActivasConductor($data['id_conductor']);
        
            // Verificar si el conductor tiene vehículos no compuestos asignados
            $tieneVehiculoNoCompuesto = $this->db->table('asignacion_vehiculos av')
                                               ->join('vehiculos v', 'av.id_vehiculo = v.id')
                                               ->where('av.id_conductor', $data['id_conductor'])
                                               ->where('av.estado', 'ACTIVA')
                                               ->where('v.compuesto', 0)
                                               ->countAllResults() > 0;
        
            // Aplicar reglas de validación según el tipo de vehículo
            if ($vehiculo['compuesto'] == 0) {
                // Vehículo NO compuesto: el conductor no puede tener ninguna asignación activa
                if ($asignacionesActuales > 0) {
                    throw new \Exception('No se puede asignar un vehículo no compuesto a un conductor que ya tiene vehículos asignados');
                }
            } else {
                // Vehículo compuesto: el conductor no puede tener vehículos no compuestos
                if ($tieneVehiculoNoCompuesto) {
                    throw new \Exception('No se puede asignar un vehículo compuesto a un conductor que tiene un vehículo no compuesto asignado');
                }
            
                // Además, no puede tener más de 2 vehículos en total
                if ($asignacionesActuales >= 2) {
                    throw new \Exception('El conductor ya tiene el máximo de 2 vehículos asignados');
                }
            }

            // Insertar asignación
            $asignacionId = $this->insert($data);

            if ($asignacionId) {
                // Actualizar estado del vehículo a NO DISPONIBLE
                $this->db->table('vehiculos')
                        ->where('id', $data['id_vehiculo'])
                        ->update(['disponible' => 'NO DISPONIBLE', 'id_conductor' => $data['id_conductor']]);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return false;
            }

            return $asignacionId;
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error creando asignación: ' . $e->getMessage());
            throw $e; // Re-lanzar la excepción para que el controlador pueda capturarla
        }
    }

    /**
     * Desasignar vehículo
     */
    public function desasignarVehiculo($id, $motivo, $usuarioId = null)
    {
        log_message('info', "Iniciando desasignación - ID: {$id}, Motivo: {$motivo}, Usuario: {$usuarioId}");
        
        $this->db->transStart();

        try {
            // Verificar que la asignación existe
            $asignacion = $this->find($id);
            if (!$asignacion) {
                log_message('error', "Asignación no encontrada - ID: {$id}");
                $this->db->transRollback();
                return false;
            }

            log_message('info', "Asignación encontrada: " . json_encode($asignacion));

            // Verificar que la asignación está activa
            if ($asignacion['estado'] !== 'ACTIVA') {
                log_message('error', "La asignación no está activa - Estado actual: {$asignacion['estado']}");
                $this->db->transRollback();
                return false;
            }

            // Actualizar asignación
            $updateData = [
                'fecha_desasignacion' => date('Y-m-d H:i:s'),
                'motivo_desasignacion' => $motivo,
                'estado' => 'INACTIVA'
            ];
            
            if ($usuarioId) {
                $updateData['usuario_edita'] = $usuarioId;
            }

            log_message('info', "Actualizando asignación con datos: " . json_encode($updateData));
            
            $updateResult = $this->update($id, $updateData);
            if (!$updateResult) {
                log_message('error', "Error actualizando asignación - ID: {$id}");
                $this->db->transRollback();
                return false;
            }

            // Actualizar estado del vehículo a DISPONIBLE
            $vehiculoUpdateData = [
                'disponible' => 'DISPONIBLE',
                'id_conductor' => 0
            ];
            
            log_message('info', "Actualizando vehículo ID {$asignacion['id_vehiculo']} con datos: " . json_encode($vehiculoUpdateData));
            
            $vehiculoResult = $this->db->table('vehiculos')
                    ->where('id', $asignacion['id_vehiculo'])
                    ->update($vehiculoUpdateData);
                    
            if (!$vehiculoResult) {
                log_message('error', "Error actualizando vehículo - ID: {$asignacion['id_vehiculo']}");
                $this->db->transRollback();
                return false;
            }

            $this->db->transComplete();
            
            $transStatus = $this->db->transStatus();
            log_message('info', "Estado de transacción: " . ($transStatus ? 'SUCCESS' : 'FAILED'));
            
            if ($transStatus === false) {
                log_message('error', "Transacción fallida para desasignación ID: {$id}");
                return false;
            }
            
            log_message('info', "Desasignación completada exitosamente - ID: {$id}");
            return true;
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', "Excepción en desasignación - ID: {$id}, Error: " . $e->getMessage());
            log_message('error', "Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Método de debugging para verificar estructura de BD
     */
    public function debugDesasignacion($id)
    {
        log_message('info', "=== DEBUG DESASIGNACIÓN ===");
        
        // Verificar asignación
        $asignacion = $this->find($id);
        log_message('info', "Asignación ID {$id}: " . json_encode($asignacion));
        
        if ($asignacion) {
            // Verificar vehículo
            $vehiculo = $this->db->table('vehiculos')
                                ->where('id', $asignacion['id_vehiculo'])
                                ->get()
                                ->getRowArray();
            log_message('info', "Vehículo ID {$asignacion['id_vehiculo']}: " . json_encode($vehiculo));
            
            // Verificar conductor
            $conductor = $this->db->table('conductores')
                                 ->where('id', $asignacion['id_conductor'])
                                 ->get()
                                 ->getRowArray();
            log_message('info', "Conductor ID {$asignacion['id_conductor']}: " . json_encode($conductor));
            
            // Verificar estructura de tabla asignacion_vehiculos
            $fields = $this->db->getFieldNames('asignacion_vehiculos');
            log_message('info', "Campos tabla asignacion_vehiculos: " . json_encode($fields));
            
            // Verificar estructura de tabla vehiculos
            $vehiculosFields = $this->db->getFieldNames('vehiculos');
            log_message('info', "Campos tabla vehiculos: " . json_encode($vehiculosFields));
        }
        
        log_message('info', "=== FIN DEBUG DESASIGNACIÓN ===");
        
        return $asignacion;
    }

    /**
     * Obtener estadísticas de asignaciones
     */
    public function getEstadisticasAsignaciones($empresaId = null)
    {
        $session = session();
        $empresaId = $empresaId ?? $session->get('empresa_id');

        $stats = [];

        // Total de asignaciones activas
        $stats['activas'] = $this->where('estado', 'ACTIVA')
                                ->countAllResults();

        // Total de asignaciones históricas
        $stats['total'] = $this->countAllResults();

        // Vehículos disponibles
        $stats['vehiculos_disponibles'] = $this->db->table('vehiculos')
                                                  ->where('disponible', 'DISPONIBLE')
                                                  ->where('estado', 'ACTIVO')
                                                  ->countAllResults();

        // Conductores disponibles
        $stats['conductores_disponibles'] = $this->db->table('conductores')
                                                    ->where('estado', 'ACTIVO')
                                                    ->countAllResults();

        return $stats;
    }

    /**
     * Verificar si un vehículo está asignado
     */
    public function verificarVehiculoAsignado($vehiculoId)
    {
        return $this->where('id_vehiculo', $vehiculoId)
                   ->where('estado', 'ACTIVA')
                   ->first();
    }

    /**
     * Verificar si un conductor tiene asignación activa (mantiene compatibilidad)
     */
    public function verificarConductorAsignado($conductorId)
    {
        return $this->where('id_conductor', $conductorId)
                   ->where('estado', 'ACTIVA')
                   ->first();
    }

    /**
     * Contar asignaciones activas de un conductor
     */
    public function contarAsignacionesActivasConductor($conductorId)
    {
        return $this->where('id_conductor', $conductorId)
                   ->where('estado', 'ACTIVA')
                   ->countAllResults();
    }

    /**
     * Verificar si un conductor puede recibir una nueva asignación (máximo 2)
     */
    public function puedeAsignarVehiculo($conductorId)
    {
        $asignacionesActuales = $this->contarAsignacionesActivasConductor($conductorId);
        return $asignacionesActuales < 2;
    }

    /**
     * Verificar si un conductor tiene vehículos no compuestos asignados
     */
    public function conductorTieneVehiculosNoCompuestos($conductorId)
    {
        // Una consulta más directa y eficiente para verificar la condición.
        $count = $this->db->table('asignacion_vehiculos av')
                         ->join('vehiculos v', 'av.id_vehiculo = v.id')
                         ->where('av.id_conductor', $conductorId)
                         ->where('av.estado', 'ACTIVA')
                         ->where('v.compuesto', 0)
                         ->countAllResults();

        return $count > 0;
    }

    /**
     * Obtener asignaciones con detalles completos
     */
    public function getAsignacionesConDetalles($filtros = [])
    {
        $builder = $this->db->table('asignacion_vehiculos av')
                          ->select('av.*, 
                                   v.placa, v.marca, v.modelo, v.anio, v.kilometraje,
                                   CONCAT(c.nombre, " ", c.apellido) as conductor_nombre,
                                   c.dni as conductor_dni,
                                   c.telefono as conductor_telefono,
                                   tu.descripcion as tipo_unidad_descripcion,
                                   top.descripcion as tipo_operacion_descripcion,
                                   uc.nombre as usuario_crea_nombre,
                                   ue.nombre as usuario_edita_nombre')
                          ->join('vehiculos v', 'av.id_vehiculo = v.id', 'left')
                          ->join('conductores c', 'av.id_conductor = c.id', 'left')
                          ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left')
                          ->join('tipo_operacion top', 'v.idTipoOperacion = top.id', 'left')
                          ->join('usuarios uc', 'av.usuarioCrea = uc.id', 'left')
                          ->join('usuarios ue', 'av.usuarioEdita = ue.id', 'left');

        // Aplicar filtros
        if (!empty($filtros['estado'])) {
            $builder->where('av.estado', $filtros['estado']);
        }

        if (!empty($filtros['tipo_unidad'])) {
            $builder->where('v.idTipoUnidad', $filtros['tipo_unidad']);
        }

        if (!empty($filtros['conductor'])) {
            $builder->groupStart()
                   ->like('c.nombre', $filtros['conductor'])
                   ->orLike('c.apellido', $filtros['conductor'])
                   ->orLike('c.dni', $filtros['conductor'])
                   ->groupEnd();
        }

        return $builder->orderBy('av.id', 'DESC')->get()->getResultArray();
    }
}
