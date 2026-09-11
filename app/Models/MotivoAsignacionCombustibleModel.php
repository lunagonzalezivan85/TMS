<?php

namespace App\Models;

use CodeIgniter\Model;

class MotivoAsignacionCombustibleModel extends Model
{
    protected $table = 'motivos_asignacion_combustible';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'motivo',
        'id_tipo_motivo'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'motivo' => 'required|string|max_length[255]|is_unique[motivos_asignacion_combustible.motivo,id,{id}]',
        'id_tipo_motivo' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'motivo' => [
            'required' => 'El motivo es obligatorio',
            'string' => 'El motivo debe ser texto',
            'max_length' => 'El motivo no puede exceder 255 caracteres',
            'is_unique' => 'Este motivo ya existe'
        ]
    ];

    /**
     * Obtiene todos los motivos activos ordenados alfabéticamente
     */
    public function getMotivosActivos()
    {
        return $this->orderBy('motivo', 'ASC')->findAll();
    }

    /**
     * Obtiene motivos para dropdown/select con información de tipo de motivo
     */
    public function getMotivosParaSelect()
    {
        $builder = $this->db->table($this->table . ' mac');
        $builder->select('mac.id, mac.motivo, tmc.descripcion as tipo_descripcion');
        $builder->join('tipo_motivo_combustible tmc', 'mac.id_tipo_motivo = tmc.id', 'left');
        $builder->orderBy('mac.motivo', 'ASC');
        
        $motivos = $builder->get()->getResultArray();
        $options = [];
        
        foreach ($motivos as $motivo) {
            $label = $motivo['motivo'];
            if ($motivo['tipo_descripcion']) {
                $label .= ' (' . $motivo['tipo_descripcion'] . ')';
            }
            $options[$motivo['id']] = $label;
        }
        
        return $options;
    }

    /**
     * Obtiene motivos con información completa para Select2
     */
    public function getMotivosParaSelect2($empresaId = null)
    {
        $builder = $this->db->table($this->table . ' mac');
        $builder->select('mac.id, mac.motivo, tmc.descripcion as tipo_descripcion, tmc.id as tipo_id');
        $builder->join('tipo_motivo_combustible tmc', 'mac.id_tipo_motivo = tmc.id', 'left');
        
        if ($empresaId) {
            $builder->where('tmc.id_empresa', $empresaId);
        }
        
        $builder->orderBy('mac.motivo', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}
