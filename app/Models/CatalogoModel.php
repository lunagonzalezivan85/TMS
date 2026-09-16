<?php

namespace App\Models;

use CodeIgniter\Model;

class CatalogoModel extends Model
{
    protected $table = 'catalogo';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'codigo',
        'nombre', 
        'descripcion',
        'id_superior',
        'estado',
        'referencia',
        'nivel',
        'referencia2',
        'idempresa',
        'edicion',
        'usuario_crea',
        'usuario_actualiza',
        'fecha_registro',
        'fecha_actualiza'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualiza';

    // Validation
    protected $validationRules = [
        'codigo' => 'permit_empty|max_length[50]', // Cambiado a permit_empty
        'nombre' => 'required|max_length[255]',
        'descripcion' => 'permit_empty|max_length[500]',
        'id_superior' => 'permit_empty|numeric',
        'estado' => 'required|in_list[1,0]',
        'referencia' => 'permit_empty|max_length[100]',
        'nivel' => 'permit_empty|numeric',
        'referencia2' => 'permit_empty|max_length[100]',
        'idempresa' => 'required|numeric',
        'edicion' => 'permit_empty|numeric'
    ];

    protected $validationMessages = [
        'codigo' => [
            'required' => 'El código es requerido',
            'max_length' => 'El código no puede exceder 50 caracteres'
        ],
        'nombre' => [
            'required' => 'El nombre es requerido',
            'max_length' => 'El nombre no puede exceder 255 caracteres'
        ],
        'descripcion' => [
            'max_length' => 'La descripción no puede exceder 500 caracteres'
        ],
        'id_superior' => [
            'numeric' => 'El ID superior debe ser numérico'
        ],
        'estado' => [
            'required' => 'El estado es requerido',
            'in_list' => 'El estado debe ser 1 (Activo) o 0 (Inactivo)'
        ],
        'referencia' => [
            'max_length' => 'La referencia no puede exceder 100 caracteres'
        ],
        'nivel' => [
            'numeric' => 'El nivel debe ser numérico'
        ],
        'referencia2' => [
            'max_length' => 'La referencia 2 no puede exceder 100 caracteres'
        ],
        'idempresa' => [
            'required' => 'La empresa es requerida',
            'numeric' => 'El ID de empresa debe ser numérico'
        ],
        'edicion' => [
            'numeric' => 'La edición debe ser numérica'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setCreateFields'];
    protected $beforeUpdate = ['setUpdateFields'];
    /**
     * Establecer campos de creación
     */
    protected function setCreateFields(array $data)
    {
        // ... existing code ...
        
        if (!isset($data['data']['fecha_registro'])) {
            $data['data']['fecha_registro'] = date('Y-m-d H:i:s');
        }
        
        if (!isset($data['data']['idempresa'])) {
            $session = session();
            $data['data']['idempresa'] = $session->get('empresa_id');
        }
        
        // Debug: Log de datos finales
        log_message('debug', 'setCreateFields - Datos finales: ' . json_encode($data['data']));
        
        return $data;
    }

    /**
     * Establecer campos de actualización
     */
    protected function setUpdateFields(array $data)
    {
        $session = session();
        $data['data']['usuario_actualiza'] = $session->get('user_id');
        $data['data']['fecha_actualiza'] = date('Y-m-d H:i:s');
        
        return $data;
    }

    /**
     * Obtener catálogos principales (solo id_superior = 0 o NULL) con paginación y filtros
     */
    public function getCatalogos($search = '', $perPage = 12, $page = 1)
    {
        $session = session();
        $empresaId = $session->get('empresa_id');
        
        $builder = $this->select('catalogo.*, 
                                 uc.nombre as usuario_creador,
                                 ua.nombre as usuario_actualizador')
                        ->join('usuarios uc', 'uc.id = catalogo.usuario_crea', 'left')
                        ->join('usuarios ua', 'ua.id = catalogo.usuario_actualiza', 'left')
                        ->where('catalogo.idempresa', $empresaId)
                        ->groupStart()
                        ->where('catalogo.id_superior', 0)
                        ->orWhere('catalogo.id_superior IS NULL')
                        ->groupEnd();

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('catalogo.codigo', $search)
                    ->orLike('catalogo.nombre', $search)
                    ->orLike('catalogo.descripcion', $search)
                    ->orLike('catalogo.referencia', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('catalogo.codigo', 'ASC')
                      ->paginate($perPage, 'default', $page);
    }

    /**
     * Obtener catálogo por ID con información relacionada
     */
    public function getCatalogoById($id)
    {
        $session = session();
        $empresaId = $session->get('empresa_id');
        
        return $this->select('catalogo.*, 
                             superior.nombre as nombre_superior,
                             uc.nombre as usuario_creador,
                             ua.nombre as usuario_actualizador')
                    ->join('catalogo superior', 'superior.id = catalogo.id_superior', 'left')
                    ->join('usuarios uc', 'uc.id = catalogo.usuario_crea', 'left')
                    ->join('usuarios ua', 'ua.id = catalogo.usuario_actualiza', 'left')
                    ->where('catalogo.id', $id)
                    ->where('catalogo.idempresa', $empresaId)
                    ->first();
    }

    /**
     * Obtener catálogos padre (para select de id_superior)
     */
    public function getCatalogosPadre($excludeId = null)
    {
        $session = session();
        $empresaId = $session->get('empresa_id');
        
        $builder = $this->select('id, codigo, nombre, nivel')
                        ->where('idempresa', $empresaId)
                        ->where('estado', 1);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->orderBy('nivel', 'ASC')
                      ->orderBy('codigo', 'ASC')
                      ->findAll();
    }

    /**
     * Generar código automático para catálogo
     */
    public function generarCodigo($idSuperior = null)
    {
        $session = session();
        $empresaId = $session->get('empresa_id');
        
        if ($idSuperior && $idSuperior > 0) {
            // Si tiene padre, usar el código del padre
            $padre = $this->find($idSuperior);
            if ($padre) {
                return $padre['codigo'];
            }
        }
        
        // Si no tiene padre, generar código CAT-0001, CAT-0002, etc.
        $ultimoCodigo = $this->select('codigo')
                            ->where('idempresa', $empresaId)
                            ->where('codigo LIKE', 'CAT-%')
                            ->orderBy('codigo', 'DESC')
                            ->first();
        
        if ($ultimoCodigo) {
            // Extraer el número del último código (ej: CAT-0001 -> 0001)
            $numero = (int) substr($ultimoCodigo['codigo'], 4);
            $nuevoNumero = $numero + 1;
        } else {
            $nuevoNumero = 1;
        }
        
        // Formatear con ceros a la izquierda (CAT-0001)
        return 'CAT-' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener subcatálogos de un catálogo padre
     */
    public function getSubcatalogos($idPadre)
    {
        $session = session();
        $empresaId = $session->get('empresa_id');
        
        return $this->select('id, codigo, nombre, descripcion, nivel, estado')
                    ->where('id_superior', $idPadre)
                    ->where('idempresa', $empresaId)
                    ->orderBy('codigo', 'ASC')
                    ->findAll();
    }

    /**
     * Verificar si un código ya existe
     */
    public function codigoExists($codigo, $excludeId = null)
    {
        $session = session();
        $empresaId = $session->get('empresa_id');
        
        $builder = $this->where('codigo', $codigo)
                        ->where('idempresa', $empresaId);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Obtener estadísticas del catálogo (solo principales)
     */
    public function getEstadisticas()
    {
        $session = session();
        $empresaId = $session->get('empresa_id');
        
        // Solo contar catálogos principales (id_superior = 0 o NULL)
        $builder = $this->where('idempresa', $empresaId)
                        ->groupStart()
                        ->where('id_superior', 0)
                        ->orWhere('id_superior IS NULL')
                        ->groupEnd();
        
        $total = $builder->countAllResults(false);
        
        $activos = $this->where('idempresa', $empresaId)
                        ->groupStart()
                        ->where('id_superior', 0)
                        ->orWhere('id_superior IS NULL')
                        ->groupEnd()
                        ->where('estado', 1)
                        ->countAllResults();
        
        $inactivos = $this->where('idempresa', $empresaId)
                          ->groupStart()
                          ->where('id_superior', 0)
                          ->orWhere('id_superior IS NULL')
                          ->groupEnd()
                          ->where('estado', 0)
                          ->countAllResults();
        
        // Contar subcatálogos totales
        $subcatalogos = $this->where('idempresa', $empresaId)
                             ->where('id_superior >', 0)
                             ->countAllResults();
        
        return [
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos,
            'subcatalogos' => $subcatalogos
        ];
    }

    /**
     * Insertar catálogo con debugging mejorado
     */
    public function insertarCatalogo($data)
    {
        log_message('debug', 'insertarCatalogo - Datos recibidos: ' . json_encode($data));
        
        // Validar datos antes de insertar
        if (!$this->validate($data)) {
            $errors = $this->errors();
            log_message('error', 'Errores de validación en insertarCatalogo: ' . json_encode($errors));
            return false;
        }
        
        try {
            $result = $this->insert($data);
            log_message('debug', 'insertarCatalogo - Resultado: ' . ($result ? 'éxito' : 'fallo'));
            
            if (!$result) {
                $errors = $this->errors();
                log_message('error', 'Error en insert() - Errores del modelo: ' . json_encode($errors));
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Excepción en insertarCatalogo: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtiene todos los catálogos con un código específico que no tienen padre (id_superior = 0)
     * 
     * @param string $codigo Código del catálogo a buscar
     * @return array Arreglo de catálogos que coinciden con el código
     */
    public function getCatalogosPorCodigo(string $codigo, bool $exactMatch = true): array
    {
        // Normalize the code (trim and uppercase)
        $codigo = strtoupper(trim($codigo));
        log_message('debug', 'Buscando catálogos con código: ' . $codigo);
        
        $builder = $this->builder();
        
        if ($exactMatch) {
            // Try exact match first with id_superior != 0 (hijos)
            $result = $builder->where('codigo', $codigo)
                            ->where('id_superior !=', 0)  // Buscar solo registros que son hijos
                            ->where('estado', 1)
                            ->get()
                            ->getResultArray();
            
            // If no results, try without id_superior filter
            if (empty($result)) {
                $builder = $this->builder();
                $result = $builder->where('codigo', $codigo)
                                ->where('estado', 1)
                                ->get()
                                ->getResultArray();
                
                if (!empty($result)) {
                    log_message('debug', 'Se encontraron resultados sin filtrar por id_superior');
                }
            }
        } else {
            // Case-insensitive search
            $result = $builder->like('UPPER(codigo)', $codigo, 'none', false, false)
                            ->where('estado', 1)
                            ->get()
                            ->getResultArray();
        }
        
        // Log the final query (CodeIgniter 4 way)
        $db = \Config\Database::connect();
        log_message('debug', 'Consulta SQL final: ' . $db->getLastQuery());
        log_message('debug', 'Número de resultados: ' . count($result));
        
        // If still no results, check for any records with similar codes
        if (empty($result)) {
            log_message('debug', 'No se encontraron resultados para el código: ' . $codigo);
            
            // Check for any records with similar codes
            $builder = $this->builder()
                          ->select('codigo, COUNT(*) as count')
                          ->groupBy('codigo')
                          ->orderBy('count', 'DESC')
                          ->limit(10);
                          
            $similarCodes = $builder->get()->getResultArray();
            log_message('debug', 'Códigos similares encontrados: ' . print_r($similarCodes, true));
        }
        
        return $result;
    }

    /**
     * Obtener opciones (id => nombre) de los hijos activos de un catálogo padre identificado por código.
     */
    public function getOpcionesHijosActivosPorCodigo(string $codigo): array
    {
        $session = session();
        $empresaId = $session->get('empresa_id');

        $padre = $this->select('id')
                      ->where('codigo', strtoupper(trim($codigo)))
                      ->where('idempresa', $empresaId)
                      ->where('estado', 1)
                      ->orderBy('id', 'ASC')
                      ->first();

        if (!$padre) {
            return [];
        }

        $hijos = $this->select('id, nombre')
                      ->where('idempresa', $empresaId)
                      ->where('id_superior', $padre['id'])
                      ->where('estado', 1)
                      ->orderBy('nombre', 'ASC')
                      ->findAll();

        $opciones = [];
        foreach ($hijos as $hijo) {
            $opciones[$hijo['id']] = $hijo['nombre'];
        }

        return $opciones;
    }

    /**
     * Obtener opciones para tipos de vehículo basadas en el código CAT-0009.
     * Si el catálogo padre tiene hijos, se utilizan; de lo contrario se usan los registros principales.
     */
    public function getOpcionesTiposVehiculo(): array
    {
        $session = session();
        $empresaId = $session->get('empresa_id');

        $registros = $this->select('id, nombre')
                           ->where('idempresa', $empresaId)
                           ->where('codigo', 'CAT-0009')
                           ->groupStart()
                                ->where('id_superior', 0)
                                ->orWhere('id_superior IS NULL', null, false)
                           ->groupEnd()
                           ->where('estado', 1)
                           ->orderBy('nombre', 'ASC')
                           ->findAll();

        $opciones = [];
        foreach ($registros as $registro) {
            $opciones[$registro['id']] = $registro['nombre'];
        }

        return $opciones;
    }

    /**
     * Obtiene las opciones de tipo de consumo desde CAT-0014.
     * Retorna un array asociativo: referencia => nombre.
     */
    public function getOpcionesTipoConsumo(): array
    {
        $session = session();
        $empresaId = $session->get('empresa_id');

        $padre = $this->select('id')
                      ->where('codigo', 'CAT-0014')
                      ->where('idempresa', $empresaId)
                      ->where('estado', 1)
                      ->orderBy('id', 'ASC')
                      ->first();

        if (!$padre) {
            return [];
        }

        $hijos = $this->select('referencia, nombre')
                      ->where('idempresa', $empresaId)
                      ->where('id_superior', $padre['id'])
                      ->where('estado', 1)
                      ->orderBy('nombre', 'ASC')
                      ->findAll();

        $opciones = [];
        foreach ($hijos as $hijo) {
            $opciones[$hijo['referencia']] = $hijo['nombre'];
        }

        return $opciones;
    }

    /**
     * Obtener un registro de catálogo (id => nombre) garantizando que se incluya aun si está inactivo.
     */
    public function getNombreCatalogoPorId($id): ?string
    {
        if (!$id) {
            return null;
        }

        $registro = $this->select('nombre')
                         ->where('id', $id)
                         ->first();

        return $registro['nombre'] ?? null;
    }

    /**
     * Obtener los hijos activos de un catálogo padre identificado por código.
     * Retorna filas completas (id, codigo, nombre, referencia, nivel).
     */
    public function getHijosActivosPorCodigo(string $codigo): array
    {
        $session = session();
        $empresaId = $session->get('empresa_id');

        $padre = $this->select('id')
                      ->where('codigo', strtoupper(trim($codigo)))
                      ->where('idempresa', $empresaId)
                      ->where('estado', 1)
                      ->groupStart()
                            ->where('id_superior', 0)
                            ->orWhere('id_superior IS NULL', null, false)
                      ->groupEnd()
                      ->orderBy('id', 'ASC')
                      ->first();

        if (!$padre) {
            return [];
        }

        return $this->select('id, codigo, nombre, descripcion, referencia, nivel')
                    ->where('idempresa', $empresaId)
                    ->where('id_superior', $padre['id'])
                    ->where('estado', 1)
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }
}
