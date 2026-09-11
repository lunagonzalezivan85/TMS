<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoMotivoCombustibleModel extends Model
{
    protected $table = 'tipo_motivo_combustible';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'descripcion',
        'estado',
        'usuario_crea',
        'usuario_edita',
        'fecha_registro',
        'fecha_actualiza',
        'id_empresa'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualiza';

    // Validation
    protected $validationRules = [
        'descripcion' => 'required|max_length[255]',
        'estado' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'descripcion' => [
            'required' => 'La descripción es requerida',
            'max_length' => 'La descripción no puede exceder 255 caracteres'
        ],
        'estado' => [
            'required' => 'El estado es requerido',
            'in_list' => 'El estado debe ser ACTIVO o INACTIVO'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setCreationData'];
    protected $beforeUpdate = ['setUpdateData'];

    /**
     * Establecer datos de creación
     */
    protected function setCreationData(array $data)
    {
        $session = session();
        if ($session && $session->get('user_id')) {
            $data['data']['usuario_crea'] = $session->get('user_id');
            $data['data']['fecha_registro'] = date('Y-m-d H:i:s');
            $data['data']['id_empresa'] = $session->get('empresa_id');
        } else {
            // Valores por defecto si no hay sesión
            $data['data']['usuario_crea'] = 1;
            $data['data']['fecha_registro'] = date('Y-m-d H:i:s');
            $data['data']['id_empresa'] = 1;
        }
        return $data;
    }

    /**
     * Establecer datos de actualización
     */
    protected function setUpdateData(array $data)
    {
        $session = session();
        if ($session && $session->get('user_id')) {
            $data['data']['usuario_edita'] = $session->get('user_id');
            $data['data']['fecha_actualiza'] = date('Y-m-d H:i:s');
        } else {
            // Valores por defecto si no hay sesión
            $data['data']['usuario_edita'] = 1;
            $data['data']['fecha_actualiza'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Obtener todos los tipos de motivo de combustible con información de usuarios
     */
    public function getTiposMotivoCombustibleCompletos($empresaId = null)
    {
        $builder = $this->db->table($this->table . ' tmc');
        
        $builder->select('
            tmc.*,
            uc.nombre as usuario_crea_nombre,
            ue.nombre as usuario_edita_nombre
        ');
        
        $builder->join('usuarios uc', 'uc.id = tmc.usuario_crea', 'left');
        $builder->join('usuarios ue', 'ue.id = tmc.usuario_edita', 'left');
        
        if ($empresaId) {
            $builder->where('tmc.id_empresa', $empresaId);
        }
        
        $builder->orderBy('tmc.descripcion', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener tipo de motivo de combustible por ID con información completa
     */
    public function getTipoMotivoCombustibleCompleto($id, $empresaId = null)
    {
        $builder = $this->db->table($this->table . ' tmc');
        
        $builder->select('
            tmc.*,
            uc.nombre as usuario_crea_nombre,
            ue.nombre as usuario_edita_nombre
        ');
        
        $builder->join('usuarios uc', 'uc.id = tmc.usuario_crea', 'left');
        $builder->join('usuarios ue', 'ue.id = tmc.usuario_edita', 'left');
        
        $builder->where('tmc.id', $id);
        
        if ($empresaId) {
            $builder->where('tmc.id_empresa', $empresaId);
        }
        
        return $builder->get()->getRowArray();
    }

    /**
     * Verificar si una descripción ya existe
     */
    public function existeDescripcion($descripcion, $empresaId, $excludeId = null)
    {
        $builder = $this->where('descripcion', $descripcion)
                        ->where('id_empresa', $empresaId);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Obtener tipos de motivo de combustible por empresa
     */
    public function getTiposPorEmpresa($empresaId)
    {
        return $this->where('id_empresa', $empresaId)
                   ->where('estado', 1)
                   ->orderBy('descripcion', 'ASC')
                   ->findAll();
    }

    /**
     * Obtener tipos activos para select
     */
    public function getTiposActivosParaSelect($empresaId)
    {
        $tipos = $this->getTiposPorEmpresa($empresaId);
        
        $opciones = [];
        foreach ($tipos as $tipo) {
            $opciones[$tipo['id']] = $tipo['descripcion'];
        }
        
        return $opciones;
    }

    /**
     * Cambiar estado de un tipo de motivo de combustible
     */
    public function cambiarEstado($id, $nuevoEstado)
    {
        $estado = ($nuevoEstado === 'ACTIVO') ? 1 : 0;
        return $this->update($id, ['estado' => $estado]);
    }

    /**
     * Obtener estadísticas
     */
    public function getEstadisticas($empresaId)
    {
        $total = $this->where('id_empresa', $empresaId)->countAllResults();
        $activos = $this->where('id_empresa', $empresaId)
                       ->where('estado', 1)
                       ->countAllResults();
        $inactivos = $this->where('id_empresa', $empresaId)
                         ->where('estado', 0)
                         ->countAllResults();
        
        return [
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos
        ];
    }
}
