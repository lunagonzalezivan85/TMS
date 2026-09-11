<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoUnidadModel extends BaseModel
{
    protected $table = 'tipo_unidad';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'descripcion', 
        'estado', 
        'fechaRegistra', 
        'fechaActualiza', 
        'UsuarioCrea', 
        'UsuarioEdita'
    ];

    protected $useTimestamps = false;
    protected $allowCallbacks = false;

    // Validaciones básicas
    protected $validationRules = [
        'descripcion' => 'required|min_length[3]|max_length[255]'
    ];

    protected $validationMessages = [
        'descripcion' => [
            'required' => 'La descripción es obligatoria',
            'min_length' => 'La descripción debe tener al menos 3 caracteres',
            'max_length' => 'La descripción no puede exceder 255 caracteres'
        ]
    ];

    /**
     * Obtener tipos de unidad con filtros
     */
    public function getTiposUnidad($filtros = [])
    {
        $builder = $this->builder();

        // Filtro por estado
        if (!empty($filtros['estado'])) {
            $builder->where('estado', $filtros['estado']);
        }

        // Filtro por búsqueda
        if (!empty($filtros['busqueda'])) {
            $builder->like('descripcion', $filtros['busqueda']);
        }

        return $builder;
    }

    /**
     * Obtener estadísticas de tipos de unidad
     */
    public function getEstadisticasTiposUnidad()
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
     * Verificar si existe una descripción
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
     * Cambiar estado de un tipo de unidad
     */
    public function cambiarEstado($id, $nuevoEstado, $usuarioId)
    {
        return $this->update($id, [
            'estado' => $nuevoEstado,
            'fechaActualiza' => date('Y-m-d H:i:s'),
            'UsuarioEdita' => $usuarioId
        ]);
    }

    /**
     * Crear tipo de unidad con auditoría
     */
    public function crearTipoUnidad($data, $usuarioId)
    {
        $data['fechaRegistra'] = date('Y-m-d H:i:s');
        $data['fechaActualiza'] = date('Y-m-d H:i:s');
        $data['UsuarioCrea'] = $usuarioId;
        $data['UsuarioEdita'] = $usuarioId;
        $data['estado'] = $data['estado'] ?? 'ACTIVO';

        return $this->insert($data);
    }

    /**
     * Actualizar tipo de unidad con auditoría
     */
    public function actualizarTipoUnidad($id, $data, $usuarioId)
    {
        $data['fechaActualiza'] = date('Y-m-d H:i:s');
        $data['UsuarioEdita'] = $usuarioId;

        return $this->update($id, $data);
    }
}
