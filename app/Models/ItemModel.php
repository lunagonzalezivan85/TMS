<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria',
        'unidad_medida',
        'costo_galon',
        'precio_galon',
        'estado',
        'fecha_creacion',
        'fecha_actualiza',
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_creacion';
    protected $updatedField = 'fecha_actualiza';

    protected $validationRules = [
        'codigo'        => 'required|max_length[50]|is_unique[items.codigo,id,{id}]',
        'nombre'        => 'required|max_length[150]',
        'categoria'     => 'permit_empty|max_length[80]',
        'unidad_medida' => 'permit_empty|max_length[20]',
        'costo_galon'   => 'permit_empty|numeric',
        'precio_galon'  => 'permit_empty|numeric',
        'estado'        => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'codigo' => [
            'required'   => 'El código es obligatorio',
            'is_unique'  => 'Ya existe un item con ese código',
        ],
        'nombre' => [
            'required' => 'El nombre es obligatorio',
        ],
    ];

    protected $beforeInsert = ['setCreateFields'];
    protected $beforeUpdate = ['setUpdateFields'];

    protected function setCreateFields(array $data)
    {
        if (!isset($data['data']['fecha_creacion'])) {
            $data['data']['fecha_creacion'] = date('Y-m-d H:i:s');
        }
        $data['data']['fecha_actualiza'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function setUpdateFields(array $data)
    {
        $data['data']['fecha_actualiza'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * Obtiene los items activos para usar en el cotizador.
     * Devuelve un array asociativo: codigo => [name, costPerGallon, ...]
     */
    public function getProductosActivos(): array
    {
        $items = $this->select('codigo, nombre, costo_galon, precio_galon, unidad_medida')
                      ->where('estado', 1)
                      ->where('categoria', 'combustible')
                      ->orderBy('nombre', 'ASC')
                      ->findAll();

        $productos = [];
        foreach ($items as $item) {
            $productos[$item['codigo']] = [
                'name'           => $item['nombre'],
                'costPerGallon'  => (float) $item['costo_galon'],
                'pricePerGallon' => (float) $item['precio_galon'],
                'unit'           => $item['unidad_medida'],
            ];
        }
        return $productos;
    }

    /**
     * Lista todos los items para administración.
     */
    public function listarItems(): array
    {
        return $this->orderBy('categoria', 'ASC')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }
}
