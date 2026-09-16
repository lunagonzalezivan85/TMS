<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudModel extends Model
{
    protected $table = 'solicitudes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id_empresa',
        'codigo_consecutivo',
        'id_vehiculo',
        'id_solicitante',
        'id_tipo_problema',
        'id_tipo_mantenimiento',
        'tipo_mantenimiento',
        'descripcion',
        'prioridad',
        'estado',
        'fecha_solicitud',
        'fecha_cierre',
        'fecha_registro',
        'fecha_actualizacion',
        'usuario_crea',
        'usuario_actualiza',
        'fecha_planificacion',
        'id_asignado',
        'fecha_asignacion',
        'fecha_aprobacion',
        'observaciones',
        'url_foto',
        'solicitante',
        'ubicacion',
        'condicion_movilidad'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualizacion';
    protected $skipValidation = false;
    
    // Validaciones
    protected $validationRules = [
        'id_empresa' => 'required',
        'id_vehiculo' => 'required',
        'id_solicitante' => 'required',
        'descripcion' => 'required|min_length[10]',
        'prioridad' => 'permit_empty|in_list[1,2,3,4]',
        'estado' => 'permit_empty|in_list[PENDIENTE,PENDIENTES,PLANIFICADA,APROBADA,APROBADAS,ASIGNADA,EN_PROCESO,EN_PAUSA,COMPLETADA,CANCELADA,RECHAZADA,FINALIZADA]',
        'fecha_solicitud' => 'permit_empty|valid_date',
        'costo_estimado' => 'permit_empty|decimal',
        'costo_real' => 'permit_empty|decimal',
        'kilometraje' => 'permit_empty|is_natural',
        'id_tipo_problema' => 'permit_empty|integer',
        'id_tipo_mantenimiento' => 'permit_empty|integer',
        'tipo_mantenimiento' => 'permit_empty|max_length[50]',
        'observaciones' => 'permit_empty|string',
        'url_foto' => 'permit_empty|string|max_length[255]',
        'solicitante' => 'permit_empty|string|max_length[150]',
        'ubicacion' => 'permit_empty|string|max_length[255]',
        'condicion_movilidad' => 'permit_empty|in_list[OPERATIVO,INMOVILIZADO,ARRASTRE]'
    ];

    protected $validationMessages = [
        'id_vehiculo' => [
            'required' => 'El vehículo es obligatorio',
            'is_natural_no_zero' => 'Seleccione un vehículo válido'
        ],
        'titulo' => [
            'required' => 'El título de la solicitud es obligatorio',
            'min_length' => 'El título debe tener al menos 5 caracteres',
            'max_length' => 'El título no puede exceder los 255 caracteres'
        ],
        'descripcion' => [
            'required' => 'La descripción es obligatoria',
            'min_length' => 'La descripción debe tener al menos 10 caracteres'
        ],
        'prioridad' => [
            'required' => 'La prioridad es obligatoria',
            'in_list' => 'La prioridad seleccionada no es válida'
        ]
    ];
    
    // Callbacks
    protected $beforeInsert = ['setUsuarioCrea', 'generarCodigoConsecutivo'];
    protected $beforeUpdate = ['setUsuarioActualiza'];

    /**
     * Establece el usuario que crea el registro
     */
    protected function setUsuarioCrea(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['usuario_crea'] = session()->get('user_id');
            
            // Si no se especifica un solicitante, usar el usuario de la sesión
            if (empty($data['data']['id_solicitante'])) {
                $data['data']['id_solicitante'] = session()->get('user_id');
            }
        }
        
        // Establecer fechas por defecto si no están definidas
        if (empty($data['data']['fecha_solicitud'])) {
            $data['data']['fecha_solicitud'] = date('Y-m-d H:i:s');
        }
        
        return $data;
    }
    
    /**
     * Establece el usuario que actualiza el registro
     */
    protected function setUsuarioActualiza(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['usuario_actualiza'] = session()->get('user_id');
        }
        return $data;
    }
    
    /**
     * Genera un código consecutivo para la solicitud
     */
    protected function generarCodigoConsecutivo(array $data)
    {
        if (empty($data['data']['codigo_consecutivo'])) {
            $ultimo = $this->selectMax('id')->first();
            $numero = $ultimo ? ((int)$ultimo['id'] + 1) : 1;
            $data['data']['codigo_consecutivo'] = 'SOL-' . str_pad($numero, 5, '0', STR_PAD_LEFT);
        }
        return $data;
    }

    /**
     * Obtiene las solicitudes de mantenimiento por empresa con filtros opcionales
     * 
     * @param int $idEmpresa ID de la empresa
     * @param array $filtros Filtros opcionales (estado, fecha_desde, fecha_hasta, id_vehiculo, search, etc.)
     * @return \CodeIgniter\Database\BaseBuilder
     */
    public function getSolicitudesPorEmpresa($idEmpresa, $filtros = [])
    {
        $builder = $this->db->table('solicitudes s')
            ->select('s.*, v.placa, v.modelo, v.marca, v.anio, v.kilometraje, v.estado as estado_vehiculo, 
                    v.numero_motor,  v.disponible, v.compuesto,
                    CONCAT(u1.nombre, " ", u1.apellido) as nombre_solicitante,
                    u1.usuario as usuario_solicitante,
                    CONCAT(u2.nombre, " ", u2.apellido) as nombre_asignado,
                    u2.usuario as usuario_asignado,
                    tp.nombre as tipo_problema')
            ->join('vehiculos v', 'v.id = s.id_vehiculo', 'left')
            ->join('usuarios u1', 'u1.id = s.id_solicitante', 'left')
            ->join('usuarios u2', 'u2.id = s.id_asignado', 'left')
            ->join('catalogo tp', 'tp.id = s.id_tipo_problema', 'left')
            ->where('s.id_empresa', $idEmpresa);
            

        // Aplicar filtros
        if (!empty($filtros['estado'])) {
            if (is_array($filtros['estado'])) {
              
                $builder->whereIn('s.estado', $filtros['estado']);
            } else {
                $builder->where('s.estado', $filtros['estado']);
            }
        }

        // Filtro por rango de fechas
        if (!empty($filtros['fecha_desde'])) {
            $builder->where('s.fecha_solicitud >=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('s.fecha_solicitud <=', $filtros['fecha_hasta'] . ' 23:59:59');
        }

        // Filtro por vehículo
        if (!empty($filtros['id_vehiculo'])) {
            $builder->where('s.id_vehiculo', $filtros['id_vehiculo']);
        }

        // Filtro por solicitante
        if (!empty($filtros['id_solicitante'])) {
            $builder->where('s.id_solicitante', $filtros['id_solicitante']);
        }

        // Filtro por asignado a
        if (!empty($filtros['id_asignado'])) {
            $builder->where('s.id_asignado', $filtros['id_asignado']);
        }

        // Filtro por tipo de problema
        if (!empty($filtros['id_tipo_problema'])) {
            $builder->where('s.id_tipo_problema', $filtros['id_tipo_problema']);
        }

        // Filtro por prioridad
        if (!empty($filtros['prioridad'])) {
            $builder->where('s.prioridad', $filtros['prioridad']);
        }

        // Búsqueda general
        if (!empty($filtros['search'])) {
            $search = $filtros['search'];
            $builder->groupStart()
                   ->like('s.codigo_consecutivo', $search)
                   ->orLike('s.titulo', $search)
                   ->orLike('v.placa', $search)
                   ->orLike('v.modelo', $search)
                   ->orLike('v.marca', $search)
                   ->orLike('u1.nombre', $search)
                   ->orLike('u1.apellido', $search)
                   ->orLike('u2.nombre', $search)
                   ->orLike('u2.apellido', $search)
                   ->orLike('tp.nombre', $search)
                   ->orLike('s.descripcion', $search)
                   ->orLike('s.observaciones', $search)
                   ->groupEnd();
        }

        // Ordenamiento
        $orden = !empty($filtros['orden']) ? $filtros['orden'] : 's.fecha_solicitud';
        $direccion = !empty($filtros['direccion']) ? $filtros['direccion'] : 'DESC';
        $builder->orderBy($orden, $direccion);

        // Límite y paginación
        if (!empty($filtros['limit'])) {
            $offset = !empty($filtros['offset']) ? $filtros['offset'] : 0;
            $builder->limit($filtros['limit'], $offset);
        }

        return $builder;
    }

    /**
     * Obtiene una solicitud por su ID con información relacionada
     * 
     * @param int $idSolicitud ID de la solicitud
     * @param int $idEmpresa ID de la empresa (opcional, para validación de seguridad)
     * @return array|null Datos de la solicitud o null si no se encuentra
     */
    public function getSolicitudConRelaciones($idSolicitud, $idEmpresa = null)
    {
        $builder = $this->db->table('solicitudes s')
            ->select('s.*, s.estado as estado_orden, s.codigo_consecutivo,
                    v.placa, v.modelo, v.marca, v.anio, v.numero_motor, v.kilometraje, v.estado as estado_vehiculo, 
                    v.disponible, v.compuesto,
                    CONCAT(u1.nombre, " ", u1.apellido) as nombre_solicitante, 
                    u1.correo as email_solicitante, u1.telefono as telefono_solicitante,
                    CONCAT(u2.nombre, " ", u2.apellido) as nombre_asignado, 
                    u2.correo as email_asignado, u2.telefono as telefono_asignado,
                    tp.nombre as tipo_problema_nombre')
            ->join('vehiculos v', 'v.id = s.id_vehiculo', 'left')
            ->join('usuarios u1', 'u1.id = s.id_solicitante', 'left')
            ->join('usuarios u2', 'u2.id = s.id_asignado', 'left')
            ->join('catalogo tp', 'tp.id = s.id_tipo_problema', 'left')
            ->where('s.id', $idSolicitud);
        
        if ($idEmpresa) {
            $builder->where('s.id_empresa', $idEmpresa);
        }
        
        $solicitud = $builder->get()->getRowArray();
        
        if (!$solicitud) {
            return null;
        }
        
        // Cargar documentos adjuntos si existen (comentado hasta crear la tabla)
        // $documentosModel = new \App\Models\SolicitudDocumentoModel();
        // $solicitud['documentos'] = $documentosModel->getDocumentosPorSolicitud($idSolicitud);
        $solicitud['documentos'] = [];
        
        // Cargar historial de estados (comentado hasta crear la tabla)
        // $historialModel = new \App\Models\SolicitudHistorialModel();
        // $solicitud['historial'] = $historialModel->getHistorialPorSolicitud($idSolicitud);
        $solicitud['historial'] = [];
        
        return $solicitud;
    }
    
    /**
     * Actualiza el estado de una solicitud y registra el cambio en el historial
     * 
     * @param int $idSolicitud ID de la solicitud
     * @param string $nuevoEstado Nuevo estado (PENDIENTE, EN_PROCESO, CERRADA, CANCELADA)
     * @param int $idUsuario ID del usuario que realiza el cambio
     * @param string|null $comentario Comentario opcional para el historial
     * @param array $datosAdicionales Datos adicionales para actualizar
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function actualizarEstado($idSolicitud, $nuevoEstado, $idUsuario, $comentario = null, $datosAdicionales = [])
    {
        // Obtener el estado actual
        $solicitud = $this->find($idSolicitud);
        if (!$solicitud) {
            return false;
        }
        
        $estadoAnterior = $solicitud['estado'];
        
        // Si el estado no ha cambiado, no hacer nada
        if ($estadoAnterior === $nuevoEstado) {
            return true;
        }
        
        // Iniciar transacción para asegurar la integridad de los datos
        $this->db->transStart();
        
        try {
            // Preparar datos para actualizar
            $data = [
                'estado' => $nuevoEstado,
                'usuario_actualiza' => $idUsuario,
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ];
            
            // Actualizar fechas según el nuevo estado
            if ($nuevoEstado === 'EN_PROCESO' && empty($solicitud['fecha_asignacion'])) {
                $data['fecha_asignacion'] = date('Y-m-d H:i:s');
                
                // Si no hay nadie asignado, asignar al usuario que cambia el estado
                if (empty($solicitud['id_asignado'])) {
                    $data['id_asignado_a'] = $idUsuario;
                }
            } 
            
            if (in_array($nuevoEstado, ['CERRADA', 'CANCELADA']) && empty($solicitud['fecha_cierre'])) {
                $data['fecha_cierre'] = date('Y-m-d H:i:s');
            }
            
            // Agregar datos adicionales si se proporcionan
            if (!empty($datosAdicionales)) {
                $data = array_merge($data, $datosAdicionales);
            }
            
            // Actualizar la solicitud
            $this->update($idSolicitud, $data);
            
            // Registrar el cambio en el historial
            $historialModel = new \App\Models\SolicitudHistorialModel();
            $historialModel->registrarCambioEstado(
                $idSolicitud, 
                $nuevoEstado, 
                $estadoAnterior, 
                $comentario
            );
            
            // Confirmar la transacción
            $this->db->transComplete();
            
            return $this->db->transStatus();
            
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            $this->db->transRollback();
            log_message('error', 'Error al actualizar estado de solicitud: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un vehículo ya tiene una solicitud activa
     * 
     * @param int $idVehiculo ID del vehículo
     * @param array $estadosConsideradosActivos Estados que se consideran activos (opcional)
     * @return array|null Datos de la solicitud activa o null si no hay ninguna
     */
    public function verificarSolicitudActiva($idVehiculo, $estadosConsideradosActivos = null)
    {
        if ($estadosConsideradosActivos === null) {
            $estadosConsideradosActivos = ['PENDIENTE', 'EN_REVISION', 'APROBADA', 'EN_PROCESO', 'EN_PAUSA'];
        }
        
        return $this->where('id_vehiculo', $idVehiculo)
                   ->whereIn('estado', $estadosConsideradosActivos)
                   ->orderBy('fecha_solicitud', 'DESC')
                   ->first();
    }
    
    /**
     * Obtiene las solicitudes asignadas a un usuario específico
     * 
     * @param int $idUsuario ID del usuario
     * @param array $filtros Opciones de filtrado (estado, fecha_desde, fecha_hasta, etc.)
     * @param bool $soloActivas Si es true, solo devuelve solicitudes en estados activos
     * @return \CodeIgniter\Database\BaseBuilder Constructor de consulta para las solicitudes
     */
    public function getSolicitudesAsignadasAUsuario($idUsuario, $filtros = [], $soloActivas = true)
    {
        $builder = $this->db->table('solicitudes s')
            ->select('s.*, v.placa, v.modelo, v.marca, 
                    CONCAT(u1.nombre, " ", u1.apellido) as nombre_solicitante, 
                    tp.nombre as tipo_problema, tp.referencia as categoria_problema,
                    DATEDIFF(s.fecha_limite, CURDATE()) as dias_restantes')
            ->join('vehiculos v', 'v.id = s.id_vehiculo', 'left')
            ->join('usuarios u1', 'u1.id = s.id_solicitante', 'left')
            ->join('catalogo tp', 'tp.id = s.id_tipo_problema', 'left')
            ->where('s.id_asignado', $idUsuario);
      
        
        // Filtrar por estados activos si es necesario
        if ($soloActivas) {
            $builder->whereIn('s.estado', ['PENDIENTE', 'EN_REVISION', 'APROBADA', 'EN_PROCESO', 'EN_PAUSA']);
        }
        
        // Aplicar filtros adicionales
        if (!empty($filtros['estado'])) {
            if (is_array($filtros['estado'])) {
                $builder->whereIn('s.estado', $filtros['estado']);
            } else {
                $builder->where('s.estado', $filtros['estado']);
            }
        }
        
        if (!empty($filtros['prioridad'])) {
            $builder->where('s.prioridad', $filtros['prioridad']);
        }
        
        if (!empty($filtros['fecha_desde'])) {
            $builder->where('s.fecha_solicitud >=', $filtros['fecha_desde'] . ' 00:00:00');
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('s.fecha_solicitud <=', $filtros['fecha_hasta'] . ' 23:59:59');
        }
        
        // Ordenamiento por defecto: prioridad alta primero, luego por fecha límite más cercana
        $orden = !empty($filtros['orden']) ? $filtros['orden'] : 's.prioridad DESC, s.fecha_limite ASC';
        $builder->orderBy($orden);
        
        // Límite y paginación
        if (!empty($filtros['limit'])) {
            $offset = !empty($filtros['offset']) ? $filtros['offset'] : 0;
            $builder->limit($filtros['limit'], $offset);
        }
        
        return $builder;
    }
    
    /**
     * Busca solicitudes con múltiples criterios de filtrado
     * 
     * @param array $filtros Array con los criterios de búsqueda
     * @param bool $contarTotal Si es true, devuelve solo el conteo total
     * @param int $limite Límite de resultados por página
     * @param int $offset Desplazamiento para paginación
     * @return \CodeIgniter\Database\BaseBuilder|int Constructor de consulta o conteo total
     */
    public function buscarSolicitudes($filtros = [], $contarTotal = false, $limite = null, $offset = 0)
    {
        $builder = $this->db->table('solicitudes s')
            ->select('s.*, v.placa, v.modelo, v.marca, 
                    TRIM(CONCAT(COALESCE(u1.nombre, ""), " ", COALESCE(u1.apellido, ""))) as nombre_solicitante,
                    TRIM(CONCAT(COALESCE(u2.nombre, ""), " ", COALESCE(u2.apellido, ""))) as nombre_asignado,
                    tp.nombre as tipo_problema 
                  ')
            ->join('vehiculos v', 'v.id = s.id_vehiculo', 'left')
            ->join('usuarios u1', 'u1.id = s.id_solicitante', 'left')
            ->join('usuarios u2', 'u2.id = s.id_asignado', 'left')
            ->join('catalogo tp', 'tp.id = s.id_tipo_problema', 'left')
           ;
        
        // Aplicar filtros
        if (!empty($filtros['id_empresa'])) {
            $builder->where('s.id_empresa', $filtros['id_empresa']);
        }
        
        if (!empty($filtros['id_vehiculo'])) {
            $builder->where('s.id_vehiculo', $filtros['id_vehiculo']);
        }
        
        if (!empty($filtros['id_solicitante'])) {
            $builder->where('s.id_solicitante', $filtros['id_solicitante']);
        }
        
        if (!empty($filtros['id_asignado_a'])) {
            $builder->where('s.id_asignado', $filtros['id_asignado_a']);
        }
        
        if (!empty($filtros['id_tipo_problema'])) {
            $builder->where('s.id_tipo_problema', $filtros['id_tipo_problema']);
        }
        
        if (!empty($filtros['estado'])) {
            if (is_array($filtros['estado'])) {
                $builder->whereIn('s.estado', $filtros['estado']);
            } else {
                $builder->where('s.estado', $filtros['estado']);
            }
        }
        
        if (!empty($filtros['prioridad'])) {
            $builder->where('s.prioridad', $filtros['prioridad']);
        }
        
        // Filtro por fechas
        if (!empty($filtros['fecha_desde'])) {
            $builder->where('s.fecha_solicitud >=', $filtros['fecha_desde'] . ' 00:00:00');
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('s.fecha_solicitud <=', $filtros['fecha_hasta'] . ' 23:59:59');
        }
        
        // Filtro por vencimiento
        if (isset($filtros['vencidas'])) {
            $hoy = date('Y-m-d');
            if ($filtros['vencidas'] === true) {
                $builder->where('s.fecha_limite <', $hoy . ' 23:59:59')
                       ->whereIn('s.estado', ['PENDIENTE', 'EN_REVISION', 'APROBADA', 'EN_PROCESO', 'EN_PAUSA']);
            } elseif ($filtros['vencidas'] === false) {
                $builder->groupStart()
                       ->where('s.fecha_limite >=', $hoy . ' 00:00:00')
                       ->orWhere('s.estado', 'COMPLETADA')
                       ->orWhere('s.estado', 'CANCELADA')
                       ->orWhere('s.estado', 'RECHAZADA')
                       ->groupEnd();
            }
        }
        
        // Búsqueda por texto
        if (!empty($filtros['busqueda'])) {
            $busqueda = $filtros['busqueda'];
            $builder->groupStart()
                   ->like('s.codigo_consecutivo', $busqueda)
                   ->orLike('s.descripcion', $busqueda)
                   ->orLike('s.observaciones', $busqueda)
                   ->orLike('v.placa', $busqueda)
                   ->orLike('v.modelo', $busqueda)
                   ->orLike('v.marca', $busqueda)
                   ->orLike('u1.nombre', $busqueda)
                   ->orLike('u1.apellido', $busqueda)
                   ->orLike('u2.nombre', $busqueda)
                   ->orLike('u2.apellido', $busqueda)
                   ->orLike('tp.nombre', $busqueda)
                   ->groupEnd();
        }
        
        // Si solo necesitamos el conteo total
        if ($contarTotal) {
            return $builder->countAllResults();
        }
        
        // Ordenamiento
        $orden = !empty($filtros['orden']) ? $filtros['orden'] : 's.fecha_solicitud';
        $direccion = !empty($filtros['direccion']) ? $filtros['direccion'] : 'DESC';
        $builder->orderBy($orden, $direccion);
        
        // Aplicar límite y offset para paginación
        if ($limite !== null) {
            $builder->limit($limite, $offset);
        }
        
        return $builder;
    }
    
    /**
     * Obtiene estadísticas de solicitudes por vehículo
     * 
     * @param int $idVehiculo ID del vehículo
     * @param int $ultimosMeses Número de meses a considerar (opcional, por defecto 12)
     * @return array Estadísticas del vehículo
     */
    public function getEstadisticasPorVehiculo($idVehiculo, $ultimosMeses = 12)
    {
        $fechaInicio = date('Y-m-d', strtotime("-$ultimosMeses months"));
        
        // Obtener conteo total de solicitudes
        $totalSolicitudes = $this->where('id_vehiculo', $idVehiculo)
                               ->where('fecha_solicitud >=', $fechaInicio)
                               
                               ->countAllResults();
        
        // Obtener conteo por estado
        $porEstado = $this->db->table('solicitudes')
            ->select('estado, COUNT(*) as total')
            ->where('id_vehiculo', $idVehiculo)
            ->where('fecha_solicitud >=', $fechaInicio)
            ->groupBy('estado')
            ->get()
            ->getResultArray();
        
        $estados = [];
        foreach ($porEstado as $item) {
            $estados[$item['estado']] = (int)$item['total'];
        }
        
        // Obtener conteo por tipo de problema
        $porTipoProblema = $this->db->table('solicitudes s')
            ->select('tp.nombre, COUNT(*) as total')
            ->join('catalogo tp', 'tp.id = s.id_tipo_problema', 'left')
            ->where('s.id_vehiculo', $idVehiculo)
            ->where('s.fecha_solicitud >=', $fechaInicio)
           
            ->groupBy('s.id_tipo_problema, tp.nombre')
            ->orderBy('total', 'DESC')
            ->get(5) // Solo los 5 tipos más comunes
            ->getResultArray();
        
        // Obtener solicitudes por mes (últimos 12 meses)
        $solicitudesPorMes = $this->db->query("
            SELECT 
                DATE_FORMAT(fecha_solicitud, '%Y-%m') as mes,
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'COMPLETADA' THEN 1 ELSE 0 END) as completadas,
                SUM(CASE WHEN estado = 'CANCELADA' OR estado = 'RECHAZADA' THEN 1 ELSE 0 END) as canceladas
            FROM solicitudes
            WHERE id_vehiculo = ?
            AND fecha_solicitud >= ?
            GROUP BY DATE_FORMAT(fecha_solicitud, '%Y-%m')
            ORDER BY mes ASC
        ", [$idVehiculo, $fechaInicio])->getResultArray();
        
        $datosMensuales = [
            'labels' => [],
            'totales' => [],
            'completadas' => [],
            'canceladas' => []
        ];
        
        foreach ($solicitudesPorMes as $mes) {
            $datosMensuales['labels'][] = date('M Y', strtotime($mes['mes'] . '-01'));
            $datosMensuales['totales'][] = (int)$mes['total'];
            $datosMensuales['completadas'][] = (int)$mes['completadas'];
            $datosMensuales['canceladas'][] = (int)$mes['canceladas'];
        }
        
        // Obtener la última solicitud
        $ultimaSolicitud = $this->where('id_vehiculo', $idVehiculo)
                             
                              ->orderBy('fecha_solicitud', 'DESC')
                              ->first();
        
        // Calcular tiempo promedio de resolución (solo para solicitudes completadas)
        $tiempos = $this->db->query("
            SELECT 
                AVG(TIMESTAMPDIFF(HOUR, fecha_solicitud, fecha_cierre)) as horas_promedio,
                AVG(TIMESTAMPDIFF(DAY, fecha_solicitud, fecha_cierre)) as dias_promedio
            FROM solicitudes
            WHERE id_vehiculo = ?
            AND estado = 'COMPLETADA'
            AND fecha_cierre IS NOT NULL
        
        ", [$idVehiculo])->getRowArray();
        
        // Obtener el tipo de problema más común
        $problemaMasComun = $this->db->table('solicitudes s')
            ->select('tp.nombre, COUNT(*) as total')
            ->join('catalogo tp', 'tp.id = s.id_tipo_problema', 'left')
            ->where('s.id_vehiculo', $idVehiculo)
            ->where('s.fecha_solicitud >=', $fechaInicio)
           
            ->groupBy('s.id_tipo_problema, tp.nombre')
            ->orderBy('total', 'DESC')
            ->get(1)
            ->getRowArray();
        
        return [
            'total_solicitudes' => $totalSolicitudes,
            'por_estado' => $estados,
            'por_tipo_problema' => $porTipoProblema,
            'datos_mensuales' => $datosMensuales,
            'ultima_solicitud' => $ultimaSolicitud,
            'tiempo_promedio_resolucion' => [
                'horas' => round((float)$tiempos['horas_promedio'], 2),
                'dias' => round((float)$tiempos['dias_promedio'], 2)
            ],
            'problema_mas_comun' => $problemaMasComun ?: null,
            'periodo_analizado' => [
                'desde' => $fechaInicio,
                'hasta' => date('Y-m-d'),
                'meses' => $ultimosMeses
            ]
        ];
    }
    
    /**
     * Obtiene estadísticas detalladas de las solicitudes de mantenimiento
     * 
     * @param int $idEmpresa ID de la empresa
     * @param array $filtros Filtros opcionales (fecha_desde, fecha_hasta, id_vehiculo, etc.)
     * @return array Estadísticas detalladas
     */
    public function getEstadisticasSolicitudes($idEmpresa, $filtros = [])
    {
        $estadisticas = [
            'totales' => [
                'total' => 0,
                'abiertas' => 0,
                'en_proceso' => 0,
                'cerradas' => 0,
                'canceladas' => 0,
                'vencidas' => 0,
                'por_vencer' => 0,
                'sin_asignar' => 0,
                'asignadas' => 0
            ],
            'por_estado' => [],
            'por_prioridad' => [],
            'por_tipo_problema' => [],
            'por_mes' => [],
            'por_asignado' => [],
            'por_solicitante' => [],
            'tiempos_promedio' => [
                'asignacion' => 0,
                'resolucion' => 0,
                'atencion' => 0
            ]
        ];
        
        // Configurar fechas para filtros
        $fechaActual = date('Y-m-d');
        $fechaHace30Dias = date('Y-m-d', strtotime('-30 days'));
        $fechaHace90Dias = date('Y-m-d', strtotime('-90 days'));
        
        $fechaDesde = !empty($filtros['fecha_desde']) ? $filtros['fecha_desde'] : $fechaHace90Dias;
        $fechaHasta = !empty($filtros['fecha_hasta']) ? $filtros['fecha_hasta'] : $fechaActual;
        
        // Construir consulta base con filtros
        $builder = $this->db->table('solicitudes s')
            ->where('s.id_empresa', $idEmpresa)
            ->where('s.fecha_solicitud >=', $fechaDesde . ' 00:00:00')
            ->where('s.fecha_solicitud <=', $fechaHasta . ' 23:59:59')
           ;
        
        // Aplicar filtros adicionales
        if (!empty($filtros['id_vehiculo'])) {
            $builder->where('s.id_vehiculo', $filtros['id_vehiculo']);
        }
        
        if (!empty($filtros['id_solicitante'])) {
            $builder->where('s.id_solicitante', $filtros['id_solicitante']);
        }
        
        if (!empty($filtros['id_asignado_a'])) {
            $builder->where('s.id_asignado', $filtros['id_asignado_a']);
        }
        
        if (!empty($filtros['id_tipo_problema'])) {
            $builder->where('s.id_tipo_problema', $filtros['id_tipo_problema']);
        }
        
        // 1. Totales generales
        $estados = $builder->select('s.estado, COUNT(*) as total')
                          ->groupBy('s.estado')
                          ->get()
                          ->getResultArray();
        
        $estadisticas['totales']['total'] = array_sum(array_column($estados, 'total'));
        
        // Inicializar contadores por estado
        $estadosPosibles = ['PENDIENTE', 'EN_REVISION', 'APROBADA', 'EN_PROCESO', 'EN_PAUSA', 'COMPLETADA', 'CANCELADA', 'RECHAZADA'];
        foreach ($estadosPosibles as $estado) {
            $estadisticas['por_estado'][$estado] = 0;
        }
        
        // Procesar conteos por estado
        foreach ($estados as $estado) {
            $estadisticas['por_estado'][$estado['estado']] = (int)$estado['total'];
            
            // Actualizar totales resumidos
            if (in_array($estado['estado'], ['PENDIENTE', 'EN_REVISION', 'APROBADA'])) {
                $estadisticas['totales']['abiertas'] += (int)$estado['total'];
            } elseif ($estado['estado'] === 'EN_PROCESO' || $estado['estado'] === 'EN_PAUSA') {
                $estadisticas['totales']['en_proceso'] += (int)$estado['total'];
            } elseif ($estado['estado'] === 'COMPLETADA') {
                $estadisticas['totales']['cerradas'] += (int)$estado['total'];
            } elseif ($estado['estado'] === 'CANCELADA' || $estado['estado'] === 'RECHAZADA') {
                $estadisticas['totales']['canceladas'] += (int)$estado['total'];
            }
        }
        
        // 2. Por prioridad
        $prioridades = $this->db->table('solicitudes s')
            ->select('s.prioridad, COUNT(*) as total')
            ->where('s.id_empresa', $idEmpresa)
            ->where('s.fecha_solicitud >=', $fechaDesde . ' 00:00:00')
            ->where('s.fecha_solicitud <=', $fechaHasta . ' 23:59:59')
          
            ->groupBy('s.prioridad')
            ->get()
            ->getResultArray();
        
        $estadisticas['por_prioridad'] = array_column($prioridades, 'total', 'prioridad');
        
        // 3. Por tipo de problema
        $tiposProblema = $this->db->table('solicitudes s')
            ->select('tp.nombre, COUNT(*) as total')
            ->join('catalogo tp', 'tp.id = s.id_tipo_problema', 'left')
            ->where('s.id_empresa', $idEmpresa)
            ->where('s.fecha_solicitud >=', $fechaDesde . ' 00:00:00')
            ->where('s.fecha_solicitud <=', $fechaHasta . ' 23:59:59')
          
            ->groupBy('s.id_tipo_problema, tp.nombre')
            ->orderBy('total', 'DESC')
            ->get(10) // Limitar a los 10 tipos más comunes
            ->getResultArray();
        
        $estadisticas['por_tipo_problema'] = array_column($tiposProblema, 'total', 'nombre');
        
        // 4. Por mes (últimos 12 meses)
        $doceMesesAtras = date('Y-m-d', strtotime('-12 months'));
        $solicitudesPorMes = $this->db->query("
            SELECT 
                DATE_FORMAT(fecha_solicitud, '%Y-%m') as mes,
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'COMPLETADA' THEN 1 ELSE 0 END) as completadas,
                SUM(CASE WHEN estado = 'CANCELADA' OR estado = 'RECHAZADA' THEN 1 ELSE 0 END) as canceladas
            FROM solicitudes
            WHERE id_empresa = ?
            AND fecha_solicitud >= ?
            AND fecha_solicitud <= ?
            GROUP BY DATE_FORMAT(fecha_solicitud, '%Y-%m')
            ORDER BY mes ASC
        ", [$idEmpresa, $doceMesesAtras, $fechaActual])->getResultArray();
        
        $estadisticas['por_mes'] = [
            'labels' => [],
            'totales' => [],
            'completadas' => [],
            'canceladas' => []
        ];
        
        foreach ($solicitudesPorMes as $mes) {
            $estadisticas['por_mes']['labels'][] = date('M Y', strtotime($mes['mes'] . '-01'));
            $estadisticas['por_mes']['totales'][] = (int)$mes['total'];
            $estadisticas['por_mes']['completadas'][] = (int)$mes['completadas'];
            $estadisticas['por_mes']['canceladas'][] = (int)$mes['canceladas'];
        }
        
        // 5. Por persona asignada (top 10)
        $porAsignado = $this->db->table('solicitudes s')
            ->select('u.nombre as nombre, COUNT(*) as total')
            ->join('usuarios u', 'u.id = s.id_asignado', 'left')
            ->where('s.id_empresa', $idEmpresa)
            ->where('s.fecha_solicitud >=', $fechaDesde . ' 00:00:00')
            ->where('s.fecha_solicitud <=', $fechaHasta . ' 23:59:59')
        
            ->where('s.id_asignado IS NOT NULL')
            ->groupBy('s.id_asignado, u.nombre')
            ->orderBy('total', 'DESC')
            ->get(10)
            ->getResultArray();
        
        foreach ($porAsignado as $asignado) {
            $estadisticas['por_asignado'][$asignado['nombre']] = (int)$asignado['total'];
        }
        
        // 6. Por solicitante (top 10)
        $porSolicitante = $this->db->table('solicitudes s')
            ->select('u.nombre as nombre, COUNT(*) as total')
            ->join('usuarios u', 'u.id = s.id_solicitante', 'left')
            ->where('s.id_empresa', $idEmpresa)
            ->where('s.fecha_solicitud >=', $fechaDesde . ' 00:00:00')
            ->where('s.fecha_solicitud <=', $fechaHasta . ' 23:59:59')
            ->groupBy('s.id_solicitante, u.nombre')
            ->orderBy('total', 'DESC')
            ->get(10)
            ->getResultArray();
        
        foreach ($porSolicitante as $solicitante) {
            $estadisticas['por_solicitante'][$solicitante['nombre']] = (int)$solicitante['total'];
        }
        
        // 7. Tiempos promedio (solo para solicitudes completadas)
        $tiempos = $this->db->query("
            SELECT 
                AVG(TIMESTAMPDIFF(HOUR, fecha_solicitud, fecha_asignacion)) as tiempo_asignacion,
                AVG(TIMESTAMPDIFF(HOUR, fecha_asignacion, fecha_cierre)) as tiempo_resolucion,
                AVG(TIMESTAMPDIFF(HOUR, fecha_solicitud, fecha_cierre)) as tiempo_total
            FROM solicitudes
            WHERE id_empresa = ?
            AND estado = 'COMPLETADA'
            AND fecha_solicitud >= ?
            AND fecha_cierre IS NULL
        ", [$idEmpresa, $fechaHace90Dias])->getRowArray();
        
        $estadisticas['tiempos_promedio'] = [
            'asignacion' => round((float)$tiempos['tiempo_asignacion'], 2),
            'resolucion' => round((float)$tiempos['tiempo_resolucion'], 2),
            'total' => round((float)$tiempos['tiempo_total'], 2)
        ];
        
        // 8. Solicitudes vencidas y por vencer
        $hoy = date('Y-m-d');
        
        // Solicitudes vencidas
        $vencidas = $this->where('id_empresa', $idEmpresa)
                         
                         ->whereIn('estado', ['PENDIENTE', 'EN_REVISION', 'APROBADA', 'EN_PROCESO', 'EN_PAUSA'])
                        
                         ->countAllResults();
        
        // Solicitudes por vencer (vencen en los próximos 3 días)
        $proximoVencimiento = date('Y-m-d', strtotime('+3 days'));
        $porVencer = $this->where('id_empresa', $idEmpresa)
                        
                         ->whereIn('estado', ['PENDIENTE', 'EN_REVISION', 'APROBADA', 'EN_PROCESO', 'EN_PAUSA'])
                        
                         ->countAllResults();
        
        $estadisticas['totales']['vencidas'] = $vencidas;
        $estadisticas['totales']['por_vencer'] = $porVencer;
        
        // 9. Solicitudes sin asignar
        $sinAsignar = $this->where('id_empresa', $idEmpresa)
                           ->where('id_asignado IS NULL')
                           ->whereIn('estado', ['PENDIENTE', 'EN_REVISION', 'APROBADA', 'EN_PROCESO', 'EN_PAUSA'])
                           
                           ->countAllResults();
        
        $estadisticas['totales']['sin_asignar'] = $sinAsignar;
        $estadisticas['totales']['asignadas'] = $estadisticas['totales']['total'] - $sinAsignar;
        
        return $estadisticas;
    }

    /**
     * Obtiene solicitudes en estado PLANIFICADA
     * 
     * @param int $idEmpresa ID de la empresa
     * @param array $filtros Filtros adicionales opcionales
     * @return array Solicitudes planificadas
     */
    public function getSolicitudesPlanificadas($idEmpresa, $filtros = [])
    {
        $builder = $this->getSolicitudesPorEmpresa($idEmpresa, $filtros);
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene solicitudes en estado PENDIENTES
     * 
     * @param int $idEmpresa ID de la empresa
     * @param array $filtros Filtros adicionales opcionales
     * @return array Solicitudes pendientes
     */
    public function getSolicitudesPendientes($idEmpresa, $filtros = [])
    {
        $builder = $this->getSolicitudesPorEmpresa($idEmpresa, array_merge($filtros, ['estado' => 'PENDIENTE']));
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene solicitudes en estado APROBADAS
     * 
     * @param int $idEmpresa ID de la empresa
     * @param array $filtros Filtros adicionales opcionales
     * @return array Solicitudes aprobadas
     */
    public function getSolicitudesAprobadas($idEmpresa, $filtros = [])
    {
        $builder = $this->getSolicitudesPorEmpresa($idEmpresa, array_merge($filtros, ['estado' => 'APROBADA']));
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene solicitudes en estado EN_PROCESO
     * 
     * @param int $idEmpresa ID de la empresa
     * @param array $filtros Filtros adicionales opcionales
     * @return array Solicitudes en proceso
     */
    public function getSolicitudesEnProceso($idEmpresa, $filtros = [])
    {
        $builder = $this->getSolicitudesPorEmpresa($idEmpresa, $filtros);
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene solicitudes en estado FINALIZADA
     * 
     * @param int $idEmpresa ID de la empresa
     * @param array $filtros Filtros adicionales opcionales
     * @return array Solicitudes finalizadas
     */
    public function getSolicitudesFinalizadas($idEmpresa, $filtros = [])
    {
        $builder = $this->getSolicitudesPorEmpresa($idEmpresa, array_merge($filtros, ['estado' => 'FINALIZADA']));
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene conteo de solicitudes por estado
     * 
     * @param int $idEmpresa ID de la empresa
     * @param array $filtros Filtros adicionales opcionales
     * @return array Conteo por cada estado
     */
    public function getConteoSolicitudesPorEstado($idEmpresa, $filtros = [])
    {
        $builder = $this->db->table('solicitudes s')
            ->select('s.estado, COUNT(*) as total')
            ->where('s.id_empresa', $idEmpresa);

        // Aplicar filtros adicionales si existen
        if (!empty($filtros['fecha_desde'])) {
            $builder->where('s.fecha_solicitud >=', $filtros['fecha_desde']);
        }
        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('s.fecha_solicitud <=', $filtros['fecha_hasta'] . ' 23:59:59');
        }
        if (!empty($filtros['id_vehiculo'])) {
            $builder->where('s.id_vehiculo', $filtros['id_vehiculo']);
        }
        if (!empty($filtros['prioridad'])) {
            $builder->where('s.prioridad', $filtros['prioridad']);
        }

        $resultados = $builder->groupBy('s.estado')
                             ->get()
                             ->getResultArray();

        // Inicializar todos los estados con 0
        $conteo = [
            'PLANIFICADA' => 0,
            'PENDIENTE' => 0,
            'APROBADA' => 0,
            'EN_PROCESO' => 0,
            'FINALIZADA' => 0
        ];

        // Llenar con los datos reales
        foreach ($resultados as $resultado) {
            $conteo[$resultado['estado']] = (int)$resultado['total'];
        }

        return $conteo;
    }

    /**
     * Obtiene solicitudes activas (no finalizadas)
     * 
     * @param int $idEmpresa ID de la empresa
     * @param array $filtros Filtros adicionales opcionales
     * @return array Solicitudes activas
     */
    public function getSolicitudesActivas($idEmpresa, $filtros = [])
    {
        $estadosActivos = ['PLANIFICADA', 'PENDIENTE', 'APROBADA', 'EN_PROCESO'];
        $builder = $this->getSolicitudesPorEmpresa($idEmpresa, array_merge($filtros, ['estado' => $estadosActivos]));
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene solicitudes para el calendario
     */
    public function getSolicitudesParaCalendario($idEmpresa)
    {
        $builder = $this->db->table('solicitudes s');
        return $builder->select('s.id, s.codigo_consecutivo, s.descripcion, s.estado, s.fecha_solicitud, s.fecha_programada, s.prioridad, s.id_vehiculo, s.id_asignado, s.id_tipo_problema, v.placa, u.nombre as mecanico_asignado, tp.nombre as tipo_problema')
                    ->join('vehiculos v', 'v.id = s.id_vehiculo', 'left')
                    ->join('usuarios u', 'u.id = s.id_asignado', 'left')
                    ->join('catalogo tp', 'tp.id = s.id_tipo_problema', 'left')
                    ->where('s.id_empresa', $idEmpresa)
                    ->whereIn('s.estado', ['PENDIENTE', 'EN_PROCESO', 'FINALIZADA', 'RECHAZADA'])
                    ->orderBy('s.fecha_solicitud', 'DESC')
                    ->get()
                    ->getResultArray();
    }

    /**
     * Obtiene solicitudes por estado específico
     */
    public function getSolicitudesPorEstado($idEmpresa, $estado)
    {
        return $this->select('s.id, s.codigo_consecutivo, s.descripcion, s.estado, s.fecha_solicitud, s.prioridad, s.id_vehiculo, s.id_asignado, v.placa, u.nombre as mecanico_asignado, tp.nombre as tipo_problema')
                    ->from('solicitudes s')
                    ->join('vehiculos v', 'v.id = s.id_vehiculo', 'left')
                    ->join('usuarios u', 'u.id = s.id_asignado', 'left')
                    ->join('catalogo tp', 'tp.id = s.id_tipo_problema', 'left')
                    ->where('s.id_empresa', $idEmpresa)
                    ->where('s.estado', $estado)
                    ->groupBy('s.id')
                    ->orderBy('s.fecha_solicitud', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene solicitudes en un rango de fechas (para calendario)
     */
    public function getSolicitudesEnRango($idEmpresa, $fechaInicio, $fechaFin)
    {
        return $this->select('DISTINCT s.id, s.*, v.placa, u.nombre as mecanico_asignado')
                    ->from('solicitudes s')
                    ->join('vehiculos v', 'v.id = s.id_vehiculo', 'left')
                    ->join('usuarios u', 'u.id = s.id_asignado', 'left')
                    ->where('s.id_empresa', $idEmpresa)
                    ->where('s.fecha_solicitud >=', $fechaInicio)
                    ->where('s.fecha_solicitud <=', $fechaFin)
                    ->whereIn('s.estado', ['PENDIENTE', 'EN_PROCESO', 'FINALIZADA', 'RECHAZADA'])
                    ->findAll();
    }

    /**
     * Cambia el estado de una solicitud
     */
    public function cambiarEstado($solicitudId, $nuevoEstado)
    {
        $estadosValidos = ['PENDIENTE', 'EN_PROCESO', 'FINALIZADA', 'RECHAZADA'];
        
        if (!in_array($nuevoEstado, $estadosValidos)) {
            return false;
        }

        $data = ['estado' => $nuevoEstado];
        
        // Si se finaliza, agregar fecha de cierre
        if ($nuevoEstado === 'FINALIZADA') {
            $data['fecha_cierre'] = date('Y-m-d H:i:s');
        }

        return $this->update($solicitudId, $data);
    }
}
