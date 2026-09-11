<?php

namespace App\Models;

use CodeIgniter\Model;

class DireccionModel extends Model
{
    protected $table = 'direcciones';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nombre',
        'direccion',
        'codigo_integracion',
        'longitud',
        'latitud',
        'ciudad',
        'usuario_crea',
        'usuario_actualizacion',
        'fecha_registro',
        'fecha_actualizacion'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualizacion';

    // Validation
    protected $validationRules = [
        'nombre' => 'required|max_length[255]',
        'direccion' => 'required|max_length[500]',
        'codigo_integracion' => 'permit_empty|max_length[100]',
        'longitud' => 'permit_empty|decimal',
        'latitud' => 'permit_empty|decimal',
        'ciudad' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es requerido',
            'max_length' => 'El nombre no puede exceder 255 caracteres'
        ],
        'direccion' => [
            'required' => 'La dirección es requerida',
            'max_length' => 'La dirección no puede exceder 500 caracteres'
        ],
        'codigo_integracion' => [
            'max_length' => 'El código de integración no puede exceder 100 caracteres'
        ],
        'longitud' => [
            'decimal' => 'La longitud debe ser un número decimal válido'
        ],
        'latitud' => [
            'decimal' => 'La latitud debe ser un número decimal válido'
        ],
        'ciudad' => [
            'max_length' => 'La ciudad no puede exceder 255 caracteres'
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
        } else {
            $data['data']['usuario_crea'] = 1;
            $data['data']['fecha_registro'] = date('Y-m-d H:i:s');
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
            $data['data']['usuario_actualizacion'] = $session->get('user_id');
            $data['data']['fecha_actualizacion'] = date('Y-m-d H:i:s');
        } else {
            $data['data']['usuario_actualizacion'] = 1;
            $data['data']['fecha_actualizacion'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Obtener todas las direcciones con información de usuarios
     */
    public function getDireccionesCompletas()
    {
        $builder = $this->db->table($this->table . ' d');
        
        $builder->select('
            d.*,
            uc.nombre as usuario_crea_nombre,
            ua.nombre as usuario_actualizacion_nombre
        ');
        
        $builder->join('usuarios uc', 'uc.id = d.usuario_crea', 'left');
        $builder->join('usuarios ua', 'ua.id = d.usuario_actualizacion', 'left');
        
        $builder->orderBy('d.direccion', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener dirección por ID con información completa
     */
    public function getDireccionCompleta($id)
    {
        $builder = $this->db->table($this->table . ' d');
        
        $builder->select('
            d.*,
            uc.nombre as usuario_crea_nombre,
            ua.nombre as usuario_actualizacion_nombre
        ');
        
        $builder->join('usuarios uc', 'uc.id = d.usuario_crea', 'left');
        $builder->join('usuarios ua', 'ua.id = d.usuario_actualizacion', 'left');
        
        $builder->where('d.id', $id);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Buscar direcciones por texto
     */
    public function buscarDirecciones($texto)
    {
        return $this->like('direccion', $texto)
                   ->orLike('ciudad', $texto)
                   ->orLike('codigo_integracion', $texto)
                   ->orderBy('direccion', 'ASC')
                   ->findAll();
    }

    /**
     * Obtener direcciones cercanas a una coordenada
     */
    public function getDireccionesCercanas($latitud, $longitud, $radio = 5)
    {
        // Usar fórmula de Haversine para calcular distancia
        $sql = "
            SELECT *, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitud)) * 
            cos(radians(longitud) - radians(?)) + sin(radians(?)) * 
            sin(radians(latitud)))) AS distancia
            FROM {$this->table}
            WHERE latitud IS NOT NULL AND longitud IS NOT NULL
            HAVING distancia < ?
            ORDER BY distancia ASC
        ";
        
        return $this->db->query($sql, [$latitud, $longitud, $latitud, $radio])->getResultArray();
    }

    /**
     * Obtener direcciones cercanas con exclusión de ID y filtros
     */
    public function getNearbyAddresses($lat, $lng, $radiusMeters = 1000, $excludeId = null)
    {
        $radiusKm = $radiusMeters / 1000; // Convertir metros a kilómetros
        
        // Fórmula de Haversine para calcular distancia
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitud)) * 
                cos(radians(longitud) - radians(?)) + sin(radians(?)) * 
                sin(radians(latitud)))) AS distancia
                FROM {$this->table} 
                WHERE latitud IS NOT NULL 
                AND longitud IS NOT NULL 
                AND estado = 1
                AND id_empresa = ?";
        
        $params = [$lat, $lng, $lat, session('empresa_id')];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $sql .= " HAVING distancia <= ? ORDER BY distancia LIMIT 5";
        $params[] = $radiusKm;
        
        return $this->db->query($sql, $params)->getResultArray();
    }

    /**
     * Verificar si una dirección ya existe
     */
    public function existeDireccion($direccion, $excludeId = null)
    {
        $builder = $this->where('direccion', $direccion);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Obtener direcciones con coordenadas válidas
     */
    public function getDireccionesConCoordenadas()
    {
        return $this->where('latitud IS NOT NULL')
                   ->where('longitud IS NOT NULL')
                   ->where('latitud !=', 0)
                   ->where('longitud !=', 0)
                   ->where('estado', 1)
                   ->where('id_empresa', session('empresa_id'))
                   ->orderBy('direccion', 'ASC')
                   ->findAll();
    }

    /**
     * Obtener estadísticas
     */
    public function getEstadisticas()
    {
        $total = $this->countAllResults();
        $conCoordenadas = $this->where('latitud IS NOT NULL')
                              ->where('longitud IS NOT NULL')
                              ->countAllResults();
        $sinCoordenadas = $total - $conCoordenadas;
        
        return [
            'total' => $total,
            'con_coordenadas' => $conCoordenadas,
            'sin_coordenadas' => $sinCoordenadas
        ];
    }
}
