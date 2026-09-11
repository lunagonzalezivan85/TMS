<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentacionConductorModel extends Model
{
    protected $table = 'documentacion_conductor';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'idConductor',
        'tipo_documento',
        'numero_documento',
        'fecha_emision',
        'fecha_vencimiento',
        'ruta_documento',
        'observaciones',
        'estado',
        'usuario_crea',
        'usuario_actualiza',
        'notificacion'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fechaRegistro';
    protected $updatedField = 'fechaUpdate';
    protected $deletedField = '';

    // Validaciones
    protected $validationRules = [
        'idConductor' => 'required|numeric',
        'tipo_documento' => 'required|max_length[100]',
        'numero_documento' => 'permit_empty|max_length[100]',
        'fecha_emision' => 'permit_empty|valid_date',
        'fecha_vencimiento' => 'required|valid_date',
        'ruta_documento' => 'permit_empty|max_length[255]',
        'estado' => 'required|in_list[VIGENTE,VENCIDO,POR_VENCER]',
        'notificacion' => 'permit_empty|integer|greater_than_equal_to[0]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtiene la documentación de un conductor específico
     */
    public function getDocumentosPorConductor($idConductor)
    {
        $builder = $this->db->table($this->table . ' dc');
        
        $builder->select('dc.*, c.nombre as nombre_tipo_documento, c.descripcion as descripcion_tipo_documento');
        $builder->join('catalogo c', 'c.id = dc.tipo_documento', 'left');
        $builder->where('dc.idConductor', $idConductor);
        $builder->orderBy('dc.fecha_vencimiento', 'DESC');
        
        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * Verifica si un conductor tiene documentos vencidos o por vencer
     */
    public function tieneDocumentosVencidos($idConductor)
    {
        $hoy = date('Y-m-d');
        $proximoMes = date('Y-m-d', strtotime('+30 days'));

        $documentos = $this->where('id_conductor', $idConductor)
                          ->where('fecha_vencimiento <=', $proximoMes)
                          ->where('fecha_vencimiento >=', $hoy)
                          ->countAllResults();

        return $documentos > 0;
    }

    /**
     * Obtiene documentos próximos a vencer con información del conductor
     */
    public function getDocumentosPorVencer()
    {
        $builder = $this->db->table($this->table . ' dc');
        $builder->select('c.nombre, c.apellido, c.carnet, dc.*');
        $builder->join('conductores c', 'c.id = dc.idConductor', 'inner');
        $builder->where('dc.fecha_vencimiento <=', 'CURDATE() + INTERVAL 30 DAY', false);
        $builder->where('dc.fecha_vencimiento >=', 'CURDATE()', false);
        $builder->orderBy('dc.fecha_vencimiento', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Actualiza el estado de los documentos basado en su fecha de vencimiento
     */
    public function actualizarEstadosDocumentos()
    {
        $hoy = date('Y-m-d');
        
        // Marcar documentos como VENCIDO
        $this->where('fecha_vencimiento <', $hoy)
             ->set('estado', 'VENCIDO')
             ->update();
        
        // Marcar documentos que están por vencer (próximos 30 días)
        $proximoMes = date('Y-m-d', strtotime('+30 days'));
        $this->where('fecha_vencimiento >=', $hoy)
             ->where('fecha_vencimiento <=', $proximoMes)
             ->set('estado', 'POR_VENCER')
             ->update();
             
        // Marcar documentos vigentes
        $this->where('fecha_vencimiento >', $proximoMes)
             ->set('estado', 'VIGENTE')
             ->update();
    }
}
