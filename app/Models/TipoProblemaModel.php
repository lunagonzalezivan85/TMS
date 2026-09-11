<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoProblemaModel extends Model
{
    protected $table = 'tipos_problema';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'nombre',
        'descripcion',
        'categoria',
        'prioridad_predeterminada',
        'tiempo_estimado',
        'estado',
        'fecha_registro',
        'fecha_actualizacion',
        'usuarioCrea',
        'usuarioEdita'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualizacion';
    protected $deletedField = 'fecha_eliminacion';
    protected $skipValidation = false;

    // Validaciones
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'descripcion' => 'permit_empty|max_length[500]',
        'categoria' => 'permit_empty|max_length[50]',
        'prioridad_predeterminada' => 'permit_empty|in_list[BAJA,MEDIA,ALTA,CRITICA]',
        'tiempo_estimado' => 'permit_empty|integer',
        'estado' => 'required|in_list[ACTIVO,INACTIVO]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del tipo de problema es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder los 100 caracteres'
        ],
        'estado' => [
            'in_list' => 'El estado debe ser ACTIVO o INACTIVO'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];

    /**
     * Establece el usuario que crea el registro
     */
    protected function setCreatedBy(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['usuarioCrea'] = session()->get('user_id');
        }
        return $data;
    }

    /**
     * Establece el usuario que actualiza el registro
     */
    protected function setUpdatedBy(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['usuarioEdita'] = session()->get('user_id');
        }
        return $data;
    }

    /**
     * Obtiene los tipos de problema activos
     */
    public function getTiposActivos()
    {
        return $this->where('estado', 'ACTIVO')
                   ->orderBy('categoria, nombre', 'ASC')
                   ->findAll();
    }

    /**
     * Obtiene los tipos de problema agrupados por categoría
     */
    public function getTiposPorCategoria()
    {
        $tipos = $this->where('estado', 'ACTIVO')
                     ->orderBy('categoria, nombre', 'ASC')
                     ->findAll();
        
        $resultado = [];
        
        foreach ($tipos as $tipo) {
            $categoria = $tipo['categoria'] ?: 'General';
            if (!isset($resultado[$categoria])) {
                $resultado[$categoria] = [];
            }
            $resultado[$categoria][] = $tipo;
        }
        
        return $resultado;
    }
}
