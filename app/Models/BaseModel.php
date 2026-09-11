<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class BaseModel extends Model
{
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fechaRegistro';
    protected $updatedField = 'fechaUpdate';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setUserData'];
    protected $beforeUpdate = ['setUserData'];

    /**
     * Constructor que permite definir la tabla dinámicamente
     */
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null, string $tabla = null)
    {
        parent::__construct($db, $validation);
        
        if ($tabla) {
            $this->table = $tabla;
        }
    }

    /**
     * Establecer la tabla de trabajo
     */
    public function setTabla(string $tabla): self
    {
        $this->table = $tabla;
        return $this;
    }

    /**
     * Establecer la clave primaria
     */
    public function setPrimaryKey(string $key): self
    {
        $this->primaryKey = $key;
        return $this;
    }

    /**
     * Establecer campos permitidos
     */
    public function setAllowedFields(array $fields): self
    {
        $this->allowedFields = $fields;
        $this->protectFields = true;
        return $this;
    }

    /**
     * Callback para establecer datos de auditoría
     */
    protected function setUserData(array $data)
    {
        $session = session();
        $userId = $session->get('user_id') ?? 1; // Default para pruebas

        if (isset($data['data'])) {
            if (!isset($data['data']['usuarioCrea'])) {
                $data['data']['usuarioCrea'] = $userId;
            }
            $data['data']['usuarioEdita'] = $userId;
        }

        return $data;
    }

    /**
     * Ejecutar procedimiento almacenado
     * 
     * @param string $spName Nombre del procedimiento almacenado
     * @param array $parametros Parámetros del SP
     * @param string $tipo Tipo de operación: 'insert', 'select', 'update', 'delete'
     * @param bool $returnData Si debe retornar datos (para SELECT)
     * @return mixed
     */
    public function ejecutarSP(string $spName, array $parametros = [], string $tipo = 'select', bool $returnData = true)
    {
        $db = $this->db;
        
        try {
            // Construir la llamada al SP
            $placeholders = str_repeat('?,', count($parametros));
            $placeholders = rtrim($placeholders, ',');
            
            $sql = "CALL {$spName}({$placeholders})";
            
            // Ejecutar el procedimiento
            if ($tipo === 'select' && $returnData) {
                // Para consultas que retornan datos
                $query = $db->query($sql, $parametros);
                
                if ($query) {
                    $result = $query->getResultArray();
                    $query->freeResult();
                    return $result;
                }
                return [];
                
            } else {
                // Para operaciones de inserción, actualización o eliminación
                $result = $db->query($sql, $parametros);
                
                if ($tipo === 'insert') {
                    // Retornar el ID insertado si es posible
                    return $db->insertID() ?: true;
                }
                
                return $result ? true : false;
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error ejecutando SP: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ejecutar SP para inserción con código consecutivo
     */
    public function insertarConCodigo(array $data, string $tipoTabla)
    {
        if (!isset($data['id_empresa'])) {
            throw new \InvalidArgumentException('id_empresa es requerido para generar código');
        }

        try {
            $db = $this->db;
            
            // Generar código consecutivo
            $db->query("CALL generar_codigo(?, ?, @codigo, @numero)", [
                $data['id_empresa'],
                $tipoTabla
            ]);
            
            // Obtener el código generado
            $result = $db->query("SELECT @codigo as codigo, @numero as numero")->getRow();
            
            if ($result) {
                $data['codigo_consecutivo'] = $result->codigo;
            }
            
            // Insertar el registro
            return $this->insert($data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error insertando con código: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener registros con paginación
     */
    public function getPaginado(int $page = 1, int $perPage = 10, array $filtros = [], array $joins = [])
    {
        $builder = $this->builder();
        
        // Aplicar joins si se proporcionan
        foreach ($joins as $join) {
            $builder->join($join['table'], $join['condition'], $join['type'] ?? 'inner');
        }
        
        // Aplicar filtros
        foreach ($filtros as $campo => $valor) {
            if (is_array($valor)) {
                $builder->whereIn($campo, $valor);
            } else {
                $builder->where($campo, $valor);
            }
        }
        
        // Calcular offset
        $offset = ($page - 1) * $perPage;
        
        // Obtener total de registros
        $total = $builder->countAllResults(false);
        
        // Obtener registros paginados
        $registros = $builder->limit($perPage, $offset)->get()->getResultArray();
        
        return [
            'data' => $registros,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }

    /**
     * Buscar registros por texto
     */
    public function buscar(string $texto, array $campos, array $filtros = [])
    {
        $builder = $this->builder();
        
        // Aplicar filtros base
        foreach ($filtros as $campo => $valor) {
            $builder->where($campo, $valor);
        }
        
        // Buscar en los campos especificados
        $builder->groupStart();
        foreach ($campos as $campo) {
            $builder->orLike($campo, $texto);
        }
        $builder->groupEnd();
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtener estadísticas básicas de una tabla
     */
    public function getEstadisticas(string $campoAgrupacion, array $filtros = [])
    {
        $builder = $this->select("{$campoAgrupacion}, COUNT(*) as cantidad")
                       ->groupBy($campoAgrupacion);
        
        // Aplicar filtros
        foreach ($filtros as $campo => $valor) {
            $builder->where($campo, $valor);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Ejecutar consulta SQL personalizada
     */
    public function ejecutarSQL(string $sql, array $parametros = [], bool $returnData = true)
    {
        try {
            $query = $this->db->query($sql, $parametros);
            
            if ($returnData && $query) {
                $result = $query->getResultArray();
                $query->freeResult();
                return $result;
            }
            
            return $query ? true : false;
            
        } catch (\Exception $e) {
            log_message('error', 'Error ejecutando SQL: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener el último error de la base de datos
     */
    public function getUltimoError()
    {
        return $this->db->error();
    }

    /**
     * Iniciar transacción
     */
    public function iniciarTransaccion()
    {
        $this->db->transStart();
    }

    /**
     * Confirmar transacción
     */
    public function confirmarTransaccion()
    {
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    /**
     * Cancelar transacción
     */
    public function cancelarTransaccion()
    {
        $this->db->transRollback();
    }

    /**
     * Validar si existe un registro
     */
    public function existe(array $condiciones)
    {
        $builder = $this->builder();
        
        foreach ($condiciones as $campo => $valor) {
            $builder->where($campo, $valor);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Obtener el siguiente número consecutivo para una empresa
     */
    public function getSiguienteConsecutivo(int $empresaId, string $tipoTabla)
    {
        try {
            $db = $this->db;
            
            $db->query("CALL generar_codigo(?, ?, @codigo, @numero)", [
                $empresaId,
                $tipoTabla
            ]);
            
            $result = $db->query("SELECT @codigo as codigo, @numero as numero")->getRow();
            
            return $result ? [
                'codigo' => $result->codigo,
                'numero' => $result->numero
            ] : null;
            
        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo consecutivo: ' . $e->getMessage());
            return null;
        }
    }
}
