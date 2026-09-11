<?php

namespace App\Models;

use CodeIgniter\Model;

class HistorialOrdenTrabajoModel extends Model
{
    protected $table            = 'historial_orden_trabajo';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Campos permitidos para asignación masiva
    protected $allowedFields = [
        'id_solicitud',
        'estado',
        'fecha_registro',
        'usuario_registra',
        'comentario',
        'referencia'
    ];

    // Validación de campos
    protected $validationRules = [
        'id_solicitud'     => 'required|integer',
        'estado'           => 'required|max_length[50]',
        'fecha_registro'   => 'required|valid_date',
        'usuario_registra' => 'required|integer',
        'comentario'       => 'permit_empty|string',
        'referencia'       => 'permit_empty|string|max_length[255]'
    ];

    protected $validationMessages = [
        'id_solicitud' => [
            'required' => 'El ID de la solicitud es obligatorio',
            'integer'  => 'El ID de la solicitud debe ser un número entero'
        ],
        'estado' => [
            'required'   => 'El estado es obligatorio',
            'max_length' => 'El estado no puede exceder los 50 caracteres'
        ],
        'fecha_registro' => [
            'required'    => 'La fecha de registro es obligatoria',
            'valid_date'  => 'La fecha de registro no es válida'
        ],
        'usuario_registra' => [
            'required' => 'El ID del usuario es obligatorio',
            'integer'  => 'El ID del usuario debe ser un número entero'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setCreatedAt'];
    protected $beforeUpdate = ['setUpdatedAt'];

    protected function setCreatedAt(array $data)
    {
        if (!isset($data['data']['fecha_registro'])) {
            $data['data']['fecha_registro'] = date('Y-m-d H:i:s');
        }
        
        if (!isset($data['data']['usuario_registra'])) {
            $session = session();
            if ($session->has('user_id')) {
                $data['data']['usuario_registra'] = $session->get('user_id');
            } else {
                $data['data']['usuario_registra'] = 0; // o null, dependiendo de tu lógica
            }
        }
        
        return $data;
    }

    /**
     * Obtiene el historial de una orden de trabajo específica
     */
    public function getHistorialPorSolicitud($idSolicitud, $limit = null, $offset = 0)
    {
        $builder = $this->where('id_solicitud', $idSolicitud)
                       ->orderBy('fecha_registro', 'DESC');
        
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->findAll();
    }

    /**
     * Registra un nuevo evento en el historial
     */
    public function registrarEvento($idSolicitud, $estado, $comentario = '', $referencia = null)
    {
        $data = [
            'id_solicitud' => $idSolicitud,
            'estado'       => $estado,
            'comentario'   => $comentario,
            'referencia'   => $referencia,
            // fecha_registro y usuario_registra se asignarán en el beforeInsert
        ];
        
        return $this->insert($data);
    }
}
