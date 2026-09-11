<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

/**
 * Modelo base para conexiones a SQL Server
 * 
 * Este modelo proporciona funcionalidad base para conectarse a SQL Server
 * y realizar operaciones CRUD con sintaxis específica de SQL Server.
 */
class SqlServerBaseModel extends Model
{
    /**
     * Conexión específica a SQL Server
     */
    protected $DBGroup = 'sqlserver';
    
    /**
     * Instancia de la base de datos SQL Server
     */
    protected $sqlServerDB;
    
    /**
     * Configuración por defecto
     */
    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';
    protected $dateFormat    = 'datetime';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Inicializa la conexión a SQL Server
     */
    protected function initSqlServerConnection()
    {
        if ($this->sqlServerDB !== null) {
            return;
        }
        try {
            $this->sqlServerDB = \Config\Database::connect('sqlserver');
            log_message('info', 'Conexión a SQL Server establecida exitosamente');
        } catch (\Exception $e) {
            log_message('error', 'Error al conectar con SQL Server: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Obtiene la instancia de la base de datos SQL Server
     */
    public function getSqlServerDB(): ConnectionInterface
    {
        $this->initSqlServerConnection();
        return $this->sqlServerDB;
    }
    
    /**
     * Ejecuta una consulta SQL personalizada en SQL Server
     * 
     * @param string $sql Consulta SQL
     * @param array $binds Parámetros para bind
     * @return mixed
     */
    public function executeSqlServerQuery(string $sql, array $binds = [])
    {
        $this->initSqlServerConnection();
        try {
            $query = $this->sqlServerDB->query($sql, $binds);
            return $query;
        } catch (\Exception $e) {
            log_message('error', 'Error en consulta SQL Server: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Obtiene registros con paginación específica para SQL Server
     * 
     * @param string $table Nombre de la tabla
     * @param int $offset Offset para paginación
     * @param int $limit Límite de registros
     * @param array $where Condiciones WHERE
     * @param string $orderBy Orden
     * @return array
     */
    public function getPaginatedRecords(string $table, int $offset = 0, int $limit = 10, array $where = [], string $orderBy = 'id')
    {
        $this->initSqlServerConnection();
        try {
            $builder = $this->sqlServerDB->table($table);
            
            // Aplicar condiciones WHERE si existen
            if (!empty($where)) {
                foreach ($where as $field => $value) {
                    $builder->where($field, $value);
                }
            }
            
            // Para SQL Server usamos OFFSET y FETCH
            $sql = $builder->orderBy($orderBy)
                          ->getCompiledSelect();
            
            // Agregar OFFSET y FETCH para SQL Server
            $sql .= " OFFSET {$offset} ROWS FETCH NEXT {$limit} ROWS ONLY";
            
            $query = $this->sqlServerDB->query($sql);
            return $query->getResultArray();
            
        } catch (\Exception $e) {
            log_message('error', 'Error en paginación SQL Server: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Inserta un registro usando sintaxis específica de SQL Server
     * 
     * @param string $table Nombre de la tabla
     * @param array $data Datos a insertar
     * @return int ID del registro insertado
     */
    public function insertRecord(string $table, array $data)
    {
        $this->initSqlServerConnection();
        try {
            $builder = $this->sqlServerDB->table($table);
            
            // Agregar timestamps si están habilitados
            if ($this->useTimestamps) {
                $data[$this->createdField] = date('Y-m-d H:i:s');
                $data[$this->updatedField] = date('Y-m-d H:i:s');
            }
            
            $builder->insert($data);
            
            // Obtener el ID insertado usando SCOPE_IDENTITY() de SQL Server
            $query = $this->sqlServerDB->query("SELECT SCOPE_IDENTITY() as id");
            $result = $query->getRow();
            
            return $result ? (int)$result->id : 0;
            
        } catch (\Exception $e) {
            log_message('error', 'Error al insertar en SQL Server: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Actualiza registros usando sintaxis específica de SQL Server
     * 
     * @param string $table Nombre de la tabla
     * @param array $data Datos a actualizar
     * @param array $where Condiciones WHERE
     * @return bool
     */
    public function updateRecord(string $table, array $data, array $where)
    {
        $this->initSqlServerConnection();
        try {
            $builder = $this->sqlServerDB->table($table);
            
            // Agregar timestamp de actualización
            if ($this->useTimestamps) {
                $data[$this->updatedField] = date('Y-m-d H:i:s');
            }
            
            // Aplicar condiciones WHERE
            foreach ($where as $field => $value) {
                $builder->where($field, $value);
            }
            
            return $builder->update($data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar en SQL Server: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Elimina registros de SQL Server
     * 
     * @param string $table Nombre de la tabla
     * @param array $where Condiciones WHERE
     * @return bool
     */
    public function deleteRecord(string $table, array $where)
    {
        $this->initSqlServerConnection();
        try {
            $builder = $this->sqlServerDB->table($table);
            
            // Aplicar condiciones WHERE
            foreach ($where as $field => $value) {
                $builder->where($field, $value);
            }
            
            return $builder->delete();
            
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar en SQL Server: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Ejecuta un procedimiento almacenado en SQL Server
     * 
     * @param string $procedureName Nombre del procedimiento
     * @param array $parameters Parámetros del procedimiento
     * @return mixed
     */
    public function executeStoredProcedure(string $procedureName, array $parameters = [])
    {
        $this->initSqlServerConnection();
        try {
            // Construir la llamada al procedimiento
            $paramPlaceholders = str_repeat('?,', count($parameters));
            $paramPlaceholders = rtrim($paramPlaceholders, ',');
            
            $sql = "EXEC {$procedureName}";
            if (!empty($parameters)) {
                $sql .= " {$paramPlaceholders}";
            }
            
            $query = $this->sqlServerDB->query($sql, $parameters);
            return $query->getResultArray();
            
        } catch (\Exception $e) {
            log_message('error', 'Error al ejecutar procedimiento almacenado: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Obtiene información de las tablas de la base de datos
     * 
     * @return array
     */
    public function getTableInfo()
    {
        $this->initSqlServerConnection();
        try {
            $sql = "SELECT TABLE_NAME, TABLE_SCHEMA 
                    FROM INFORMATION_SCHEMA.TABLES 
                    WHERE TABLE_TYPE = 'BASE TABLE'
                    ORDER BY TABLE_NAME";
            
            $query = $this->sqlServerDB->query($sql);
            return $query->getResultArray();
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener información de tablas: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Obtiene las columnas de una tabla específica
     * 
     * @param string $tableName Nombre de la tabla
     * @return array
     */
    public function getTableColumns(string $tableName)
    {
        $this->initSqlServerConnection();
        try {
            $sql = "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
                    FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_NAME = ?
                    ORDER BY ORDINAL_POSITION";
            
            $query = $this->sqlServerDB->query($sql, [$tableName]);
            return $query->getResultArray();
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener columnas de tabla: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Verifica la conexión a SQL Server
     * 
     * @return bool
     */
    public function testConnection(): bool
    {
        $this->initSqlServerConnection();
        try {
            $query = $this->sqlServerDB->query("SELECT 1 as test");
            $result = $query->getRow();
            
            return $result && $result->test == 1;
            
        } catch (\Exception $e) {
            log_message('error', 'Error al probar conexión SQL Server: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene la versión de SQL Server
     * 
     * @return string
     */
    public function getSqlServerVersion(): string
    {
        $this->initSqlServerConnection();
        try {
            $query = $this->sqlServerDB->query("SELECT @@VERSION as version");
            $result = $query->getRow();
            
            return $result ? $result->version : 'Desconocida';
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener versión SQL Server: ' . $e->getMessage());
            return 'Error al obtener versión';
        }
    }
}
