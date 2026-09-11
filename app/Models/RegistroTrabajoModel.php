<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistroTrabajoModel extends Model
{
    protected $table = 'registro_trabajo_realizado';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'codigo_consecutivo',
        'id_solicitud',
        'id_vehiculo',
        'id_tecnico',
        'fecha_inicio',
        'fecha_fin',
        'trabajo_realizado',
        'kilometraje_actual',
        'horas_trabajo',
        'observaciones',
        'estado_vehiculo_post',
        'trabajo_completado',
        'costo_total_materiales',
        'usuario_crea',
        'usuario_actualiza'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualizacion';
    protected $skipValidation = false;
    
    // Validaciones
    protected $validationRules = [
        'id_solicitud' => 'required|integer',
        'id_vehiculo' => 'required|integer',
        'id_tecnico' => 'required|integer',
        'fecha_inicio' => 'required|valid_date',
        'trabajo_realizado' => 'required|min_length[10]',
        'kilometraje_actual' => 'permit_empty|integer',
        'horas_trabajo' => 'permit_empty|decimal',
        'estado_vehiculo_post' => 'permit_empty|in_list[OPERATIVO,REQUIERE_REVISION,FUERA_DE_SERVICIO,PENDIENTE_REPUESTOS]',
        'trabajo_completado' => 'required|in_list[0,1]',
        'costo_total_materiales' => 'permit_empty|decimal',
        'usuario_crea' => 'required|integer'
    ];

    protected $validationMessages = [
        'id_solicitud' => [
            'required' => 'La solicitud es requerida',
            'integer' => 'La solicitud debe ser un número válido'
        ],
        'id_vehiculo' => [
            'required' => 'El vehículo es requerido',
            'integer' => 'El vehículo debe ser un número válido'
        ],
        'id_tecnico' => [
            'required' => 'El técnico es requerido',
            'integer' => 'El técnico debe ser un número válido'
        ],
        'fecha_inicio' => [
            'required' => 'La fecha de inicio es requerida',
            'valid_date' => 'La fecha de inicio debe ser válida'
        ],
        'trabajo_realizado' => [
            'required' => 'La descripción del trabajo es requerida',
            'min_length' => 'La descripción debe tener al menos 10 caracteres'
        ],
        'trabajo_completado' => [
            'required' => 'El estado de completado es requerido',
            'in_list' => 'El estado de completado debe ser 0 o 1'
        ],
        'usuario_crea' => [
            'required' => 'El usuario creador es requerido',
            'integer' => 'El usuario creador debe ser un número válido'
        ]
    ];

    /**
     * Obtener registro de trabajo con relaciones
     */
    public function getRegistroConRelaciones($idRegistro, $idEmpresa = null)
    {
        $builder = $this->db->table('registro_trabajo_realizado r')
            ->select('r.*, 
                    v.placa, v.modelo, v.marca, v.anio, v.numero_motor, v.kilometraje as km_vehiculo, 
                    v.estado as estado_vehiculo, v.modelo_motor, v.disponible, v.compuesto,
                    CONCAT(u1.nombre, " ", u1.apellido) as nombre_tecnico, 
                    u1.correo as email_tecnico, u1.telefono as telefono_tecnico,
                    CONCAT(u2.nombre, " ", u2.apellido) as nombre_creador, 
                    u2.correo as email_creador,
                    s.codigo_consecutivo as codigo_solicitud, s.descripcion as descripcion_solicitud,
                    s.estado as estado_solicitud, s.prioridad')
            ->join('vehiculos v', 'v.id = r.id_vehiculo', 'left')
            ->join('usuarios u1', 'u1.id = r.id_tecnico', 'left')
            ->join('usuarios u2', 'u2.id = r.usuario_crea', 'left')
            ->join('solicitudes s', 's.id = r.id_solicitud', 'left')
            ->where('r.id', $idRegistro);
        
        if ($idEmpresa) {
            $builder->join('solicitudes s2', 's2.id = r.id_solicitud', 'inner')
                   ->where('s2.id_empresa', $idEmpresa);
        }
        
        return $builder->get()->getRowArray();
    }

    /**
     * Obtener registros de trabajo por solicitud
     */
    public function getRegistrosPorSolicitud($idSolicitud)
    {
        return $this->db->table('registro_trabajo_realizado r')
            ->select('r.*, 
                    CONCAT(u.nombre, " ", u.apellido) as nombre_tecnico')
            ->join('usuarios u', 'u.id = r.id_tecnico', 'left')
            ->where('r.id_solicitud', $idSolicitud)
            ->orderBy('r.fecha_inicio', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtener registros de trabajo por vehículo
     */
    public function getRegistrosPorVehiculo($idVehiculo, $limite = 10)
    {
        return $this->db->table('registro_trabajo_realizado r')
            ->select('r.*, 
                    CONCAT(u.nombre, " ", u.apellido) as nombre_tecnico,
                    s.codigo_consecutivo as codigo_solicitud')
            ->join('usuarios u', 'u.id = r.id_tecnico', 'left')
            ->join('solicitudes s', 's.id = r.id_solicitud', 'left')
            ->where('r.id_vehiculo', $idVehiculo)
            ->orderBy('r.fecha_inicio', 'DESC')
            ->limit($limite)
            ->get()
            ->getResultArray();
    }

    /**
     * Obtener estadísticas de trabajos por técnico
     */
    public function getEstadisticasPorTecnico($idTecnico, $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->db->table('registro_trabajo_realizado r')
            ->select('COUNT(*) as total_trabajos,
                    SUM(CASE WHEN trabajo_completado = 1 THEN 1 ELSE 0 END) as trabajos_completados,
                    SUM(horas_trabajo) as total_horas,
                    AVG(horas_trabajo) as promedio_horas,
                    SUM(costo_total_materiales) as total_costo_materiales')
            ->where('r.id_tecnico', $idTecnico);
        
        if ($fechaInicio) {
            $builder->where('r.fecha_inicio >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $builder->where('r.fecha_inicio <=', $fechaFin);
        }
        
        return $builder->get()->getRowArray();
    }

    /**
     * Marcar trabajo como completado
     */
    public function marcarComoCompletado($idRegistro, $fechaFin = null, $usuarioActualiza = null)
    {
        $data = [
            'trabajo_completado' => 1,
            'fecha_fin' => $fechaFin ?: date('Y-m-d H:i:s')
        ];
        
        if ($usuarioActualiza) {
            $data['usuario_actualiza'] = $usuarioActualiza;
        }
        
        return $this->update($idRegistro, $data);
    }

    /**
     * Obtener trabajos pendientes
     */
    public function getTrabajosPendientes($idEmpresa = null)
    {
        $builder = $this->db->table('registro_trabajo_realizado r')
            ->select('r.*, 
                    v.placa, v.modelo, v.marca,
                    CONCAT(u.nombre, " ", u.apellido) as nombre_tecnico,
                    s.codigo_consecutivo as codigo_solicitud, s.prioridad')
            ->join('vehiculos v', 'v.id = r.id_vehiculo', 'left')
            ->join('usuarios u', 'u.id = r.id_tecnico', 'left')
            ->join('solicitudes s', 's.id = r.id_solicitud', 'left')
            ->where('r.trabajo_completado', 0);
        
        if ($idEmpresa) {
            $builder->where('s.id_empresa', $idEmpresa);
        }
        
        return $builder->orderBy('s.prioridad', 'DESC')
                      ->orderBy('r.fecha_inicio', 'ASC')
                      ->get()
                      ->getResultArray();
    }
}
