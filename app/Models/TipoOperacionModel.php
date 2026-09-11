<?php

namespace App\Models;

use App\Models\BaseModel;

class TipoOperacionModel extends BaseModel
{
    protected $table = 'tipo_operacion';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'descripcion',
        'UsuarioCrea',
        'UsuarioEdita', 
        'fechaRegistra',
        'fechaActualiza',
        'estado'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fechaRegistra';
    protected $updatedField = 'fechaActualiza';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setAuditFields'];
    protected $beforeUpdate = ['setAuditFields'];

    // Validation
    protected $validationRules = [
        'descripcion' => 'required|min_length[3]|max_length[255]',
        'estado' => 'in_list[ACTIVO,INACTIVO]'
    ];

    protected $validationMessages = [
        'descripcion' => [
            'required' => 'La descripción es obligatoria',
            'min_length' => 'La descripción debe tener al menos 3 caracteres',
            'max_length' => 'La descripción no puede exceder 255 caracteres'
        ],
        'estado' => [
            'in_list' => 'El estado debe ser ACTIVO o INACTIVO'
        ]
    ];

    /**
     * Callback para establecer campos de auditoría
     */
    protected function setAuditFields(array $data)
    {
        $session = session();
        $userId = $session->get('user_id') ?? 1;
        $now = date('Y-m-d H:i:s');

        if (isset($data['data'])) {
            // Para inserción
            if (!isset($data['data']['UsuarioCrea'])) {
                $data['data']['UsuarioCrea'] = $userId;
            }
            if (!isset($data['data']['fechaRegistra'])) {
                $data['data']['fechaRegistra'] = $now;
            }
            if (!isset($data['data']['estado'])) {
                $data['data']['estado'] = 'ACTIVO';
            }
        } else {
            // Para actualización
            $data['UsuarioEdita'] = $userId;
            $data['fechaActualiza'] = $now;
        }

        return $data;
    }

    /**
     * Obtener tipos de operación con filtros
     */
    public function getTiposOperacionConFiltros($filtros = [], $limit = null, $offset = null)
    {
        $builder = $this->builder();

        // Aplicar filtros
        if (!empty($filtros['search'])) {
            $builder->groupStart()
                   ->like('descripcion', $filtros['search'])
                   ->groupEnd();
        }

        if (!empty($filtros['estado'])) {
            $builder->where('estado', $filtros['estado']);
        }

        // Ordenamiento
        $builder->orderBy('descripcion', 'ASC');

        // Paginación
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Contar tipos de operación con filtros
     */
    public function contarTiposOperacionConFiltros($filtros = [])
    {
        $builder = $this->builder();

        // Aplicar filtros
        if (!empty($filtros['search'])) {
            $builder->groupStart()
                   ->like('descripcion', $filtros['search'])
                   ->groupEnd();
        }

        if (!empty($filtros['estado'])) {
            $builder->where('estado', $filtros['estado']);
        }

        return $builder->countAllResults();
    }

    /**
     * Verificar si una descripción ya existe
     */
    public function existeDescripcion($descripcion, $excludeId = null)
    {
        $builder = $this->builder();
        $builder->where('descripcion', $descripcion);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Obtener estadísticas específicas de tipos de operación
     */
    public function getEstadisticasTipoOperacion()
    {
        $builder = $this->builder();
        
        $total = $builder->countAllResults(false);
        $activos = $builder->where('estado', 'ACTIVO')->countAllResults(false);
        $inactivos = $builder->where('estado', 'INACTIVO')->countAllResults();

        return [
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos
        ];
    }

    /**
     * Obtener estadísticas básicas (compatible con BaseModel)
     * 
     * @param string $campoAgrupacion Campo por el cual agrupar
     * @param array $filtros Filtros adicionales
     * @return array
     */
    public function getEstadisticas(string $campoAgrupacion, array $filtros = [])
    {
        return parent::getEstadisticas($campoAgrupacion, $filtros);
    }

    /**
     * Cambiar estado de un tipo de operación
     */
    public function cambiarEstado($id, $nuevoEstado)
    {
        $data = [
            'estado' => $nuevoEstado,
            'UsuarioEdita' => session()->get('user_id') ?? 1,
            'fechaActualiza' => date('Y-m-d H:i:s')
        ];

        return $this->update($id, $data);
    }

    /**
     * Obtener tipos de operación activos para select
     */
    public function getTiposOperacionActivos()
    {
        return $this->where('estado', 'ACTIVO')
                   ->orderBy('descripcion', 'ASC')
                   ->findAll();
    }

    /**
     * Verificar si un tipo de operación está siendo usado
     */
    public function estaEnUso($id)
    {
        // Aquí puedes agregar verificaciones en otras tablas que referencien este tipo
        // Por ejemplo, si hay una tabla 'operaciones' que usa 'id_tipo_operacion'
        
        $db = \Config\Database::connect();
        
        // Ejemplo de verificación (ajustar según las tablas que usen este tipo)
        /*
        $builder = $db->table('operaciones');
        $count = $builder->where('id_tipo_operacion', $id)->countAllResults();
        
        return $count > 0;
        */
        
        return false; // Por ahora retorna false hasta que se definan las relaciones
    }
}
