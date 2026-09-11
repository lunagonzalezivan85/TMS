<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudHistorialModel extends Model
{
    protected $table = 'solicitudes_historial';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'id_solicitud',
        'id_usuario',
        'estado_anterior',
        'estado_nuevo',
        'comentario',
        'ip_address',
        'user_agent'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'fecha_cambio';
    protected $updatedField = '';
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Validaciones
    protected $validationRules = [
        'id_solicitud' => 'required|is_natural_no_zero',
        'id_usuario' => 'required|is_natural_no_zero',
        'estado_nuevo' => 'required|in_list[PENDIENTE,EN_PROCESO,CERRADA,CANCELADA]',
        'estado_anterior' => 'permit_empty|in_list[PENDIENTE,EN_PROCESO,CERRADA,CANCELADA]',
        'comentario' => 'permit_empty|max_length[1000]',
        'ip_address' => 'permit_empty|valid_ip',
        'user_agent' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'id_solicitud' => [
            'required' => 'El ID de la solicitud es obligatorio',
            'is_natural_no_zero' => 'El ID de la solicitud debe ser un número válido'
        ],
        'estado_nuevo' => [
            'required' => 'El nuevo estado es obligatorio',
            'in_list' => 'El estado proporcionado no es válido'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setDatosUsuario'];

    /**
     * Establece los datos del usuario que realiza el cambio
     */
    protected function setDatosUsuario(array $data)
    {
        $request = service('request');
        
        // Si no se proporciona IP, usar la del cliente
        if (empty($data['data']['ip_address'])) {
            $data['data']['ip_address'] = $request->getIPAddress();
        }
        
        // Si no se proporciona user agent, usar el del navegador
        if (empty($data['data']['user_agent'])) {
            $data['data']['user_agent'] = $request->getUserAgent()->getAgentString();
        }
        
        // Si no se proporciona usuario, usar el de la sesión
        if (empty($data['data']['id_usuario']) && session()->has('user_id')) {
            $data['data']['id_usuario'] = session()->get('user_id');
        }
        
        return $data;
    }

    /**
     * Registra un cambio de estado en el historial
     * 
     * @param int $idSolicitud ID de la solicitud
     * @param string $estadoNuevo Nuevo estado
     * @param string|null $estadoAnterior Estado anterior (opcional)
     * @param string|null $comentario Comentario opcional
     * @return int|false ID del registro creado o false en caso de error
     */
    public function registrarCambioEstado($idSolicitud, $estadoNuevo, $estadoAnterior = null, $comentario = null)
    {
        $data = [
            'id_solicitud' => $idSolicitud,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'comentario' => $comentario
        ];
        
        if ($this->save($data)) {
            return $this->getInsertID();
        }
        
        return false;
    }
    
    /**
     * Obtiene el historial de cambios de una solicitud
     * 
     * @param int $idSolicitud ID de la solicitud
     * @param int $limit Límite de registros a devolver (0 para todos)
     * @return array Historial de cambios
     */
    public function getHistorialPorSolicitud($idSolicitud, $limit = 10)
    {
        $builder = $this->select('sh.*, u.nombre, u.apellido, u.email')
                       ->from('solicitudes_historial sh')
                       ->join('usuarios u', 'u.id = sh.id_usuario', 'left')
                       ->where('sh.id_solicitud', $idSolicitud)
                       ->orderBy('sh.fecha_cambio', 'DESC');
        
        if ($limit > 0) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Obtiene el último cambio de estado de una solicitud
     * 
     * @param int $idSolicitud ID de la solicitud
     * @return array|null Datos del último cambio o null si no existe
     */
    public function getUltimoCambioEstado($idSolicitud)
    {
        return $this->where('id_solicitud', $idSolicitud)
                   ->orderBy('fecha_cambio', 'DESC')
                   ->first();
    }
    
    /**
     * Obtiene el historial de cambios de estado por usuario
     * 
     * @param int $idUsuario ID del usuario
     * @param string $fechaInicio Fecha de inicio (formato YYYY-MM-DD)
     * @param string $fechaFin Fecha de fin (formato YYYY-MM-DD)
     * @return array Historial de cambios
     */
    public function getHistorialPorUsuario($idUsuario, $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->select('sh.*, s.codigo_consecutivo, s.titulo')
                       ->from('solicitudes_historial sh')
                       ->join('solicitudes s', 's.id = sh.id_solicitud')
                       ->where('sh.id_usuario', $idUsuario)
                       ->orderBy('sh.fecha_cambio', 'DESC');
        
        // Filtrar por rango de fechas si se proporciona
        if ($fechaInicio) {
            $builder->where('DATE(sh.fecha_cambio) >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $builder->where('DATE(sh.fecha_cambio) <=', $fechaFin);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Obtiene estadísticas de cambios de estado por usuario
     * 
     * @param int $idUsuario ID del usuario (opcional, si no se proporciona se toman todos)
     * @param string $fechaInicio Fecha de inicio (formato YYYY-MM-DD)
     * @param string $fechaFin Fecha de fin (formato YYYY-MM-DD)
     * @return array Estadísticas de cambios
     */
    public function getEstadisticasCambios($idUsuario = null, $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->select('COUNT(*) as total, sh.estado_nuevo, u.nombre, u.apellido')
                       ->from('solicitudes_historial sh')
                       ->join('usuarios u', 'u.id = sh.id_usuario')
                       ->groupBy('sh.estado_nuevo, sh.id_usuario')
                       ->orderBy('u.nombre, u.apellido');
        
        // Filtrar por usuario si se proporciona
        if ($idUsuario) {
            $builder->where('sh.id_usuario', $idUsuario);
        }
        
        // Filtrar por rango de fechas si se proporciona
        if ($fechaInicio) {
            $builder->where('DATE(sh.fecha_cambio) >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $builder->where('DATE(sh.fecha_cambio) <=', $fechaFin);
        }
        
        $resultados = $builder->get()->getResultArray();
        
        // Procesar resultados para un formato más amigable
        $estadisticas = [];
        foreach ($resultados as $fila) {
            $usuarioId = $fila['id_usuario'];
            $estado = $fila['estado_nuevo'];
            
            if (!isset($estadisticas[$usuarioId])) {
                $estadisticas[$usuarioId] = [
                    'usuario_id' => $usuarioId,
                    'nombre' => $fila['nombre'] . ' ' . $fila['apellido'],
                    'total' => 0,
                    'estados' => [
                        'PENDIENTE' => 0,
                        'EN_PROCESO' => 0,
                        'CERRADA' => 0,
                        'CANCELADA' => 0
                    ]
                ];
            }
            
            $estadisticas[$usuarioId]['total'] += $fila['total'];
            $estadisticas[$usuarioId]['estados'][$estado] = (int)$fila['total'];
        }
        
        return array_values($estadisticas);
    }
    
    /**
     * Obtiene el tiempo promedio que las solicitudes permanecen en cada estado
     * 
     * @param int $idEmpresa ID de la empresa (opcional)
     * @param string $fechaInicio Fecha de inicio (formato YYYY-MM-DD)
     * @param string $fechaFin Fecha de fin (formato YYYY-MM-DD)
     * @return array Tiempos promedios por estado
     */
    public function getTiempoPromedioPorEstado($idEmpresa = null, $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->db->table('solicitudes_historial sh')
                           ->select([
                               'sh.estado_nuevo',
                               'AVG(TIMESTAMPDIFF(MINUTE, sh.fecha_cambio, sh_next.fecha_cambio)) as tiempo_promedio_minutos'
                           ])
                           ->join('solicitudes_historial sh_next', 
                               'sh.id_solicitud = sh_next.id_solicitud AND sh.fecha_cambio < sh_next.fecha_cambio', 
                               'left')
                           ->join('solicitudes s', 's.id = sh.id_solicitud')
                           ->where('sh.estado_nuevo !=', 'CERRADA')
                           ->where('sh.estado_nuevo !=', 'CANCELADA')
                           ->groupBy('sh.estado_nuevo');
        
        // Filtrar por empresa si se proporciona
        if ($idEmpresa) {
            $builder->where('s.id_empresa', $idEmpresa);
        }
        
        // Filtrar por rango de fechas si se proporciona
        if ($fechaInicio) {
            $builder->where('DATE(sh.fecha_cambio) >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $builder->where('DATE(sh.fecha_cambio) <=', $fechaFin);
        }
        
        $resultados = $builder->get()->getResultArray();
        
        // Formatear resultados
        $tiempos = [
            'PENDIENTE' => 0,
            'EN_PROCESO' => 0,
            'CERRADA' => 0,
            'CANCELADA' => 0
        ];
        
        foreach ($resultados as $fila) {
            $estado = $fila['estado_nuevo'];
            $tiempos[$estado] = (float)$fila['tiempo_promedio_minutos'];
        }
        
        return $tiempos;
    }
}
