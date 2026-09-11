<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentacionVehiculoModel extends Model
{
    protected $table = 'documentacion_vehiculo';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'idVehiculo',
        'tipo_documento',
        'numero',
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
        'idVehiculo' => 'required|numeric',
        'tipo_documento' => 'required|max_length[100]',
        'numero' => 'permit_empty|max_length[100]',
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
     * Obtiene la documentación de un vehículo específico
     */
    public function getDocumentosPorVehiculo($idVehiculo)
    {
        $builder = $this->db->table('documentos_vehiculos doc')
            ->select('doc.*, cat.nombre as nombre_tipo_documento, cat.descripcion as descripcion_tipo_documento')
            ->join('catalogo cat', 'doc.tipo_documento = cat.id', 'left')
            ->where('doc.idVehiculo', $idVehiculo)
            ->orderBy('doc.fecha_vencimiento', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Actualiza el estado de los documentos según su fecha de vencimiento
     */
    public function actualizarEstadosDocumentos()
    {
        $hoy = date('Y-m-d');
        $futuro = date('Y-m-d', strtotime('+30 days'));

        // Actualizar documentos vencidos
        $this->db->table($this->table)
            ->where('fecha_vencimiento <', $hoy)
            ->update(['estado' => 'VENCIDO']);

        // Actualizar documentos por vencer (próximos 30 días)
        $this->db->table($this->table)
            ->where('fecha_vencimiento >=', $hoy)
            ->where('fecha_vencimiento <=', $futuro)
            ->update(['estado' => 'POR_VENCER']);

        // Actualizar documentos vigentes (más de 30 días)
        $this->db->table($this->table)
            ->where('fecha_vencimiento >', $futuro)
            ->update(['estado' => 'VIGENTE']);
    }

    /**
     * Obtiene un documento por su ID
     */
    public function getDocumento($idDocumento)
    {
        return $this->find($idDocumento);
    }

    /**
     * Obtiene el conteo de documentos por estado para un vehículo
     */
    public function getConteoDocumentos($idVehiculo)
    {
        $builder = $this->db->table($this->table)
            ->select('estado, COUNT(*) as total')
            ->where('idVehiculo', $idVehiculo)
            ->groupBy('estado');

        $result = $builder->get()->getResultArray();
        
        $conteo = [
            'TOTAL' => 0,
            'VIGENTE' => 0,
            'POR_VENCER' => 0,
            'VENCIDO' => 0
        ];

        foreach ($result as $row) {
            $conteo[$row['estado']] = (int)$row['total'];
            $conteo['TOTAL'] += (int)$row['total'];
        }

        return $conteo;
    }
}
