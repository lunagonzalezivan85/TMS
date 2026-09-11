<?php

namespace App\Models;

use App\Models\SqlServerBaseModel;

/**
 * Modelo de ejemplo para SQL Server
 * 
 * Este modelo demuestra cómo usar la clase base SqlServerBaseModel
 * para interactuar con tablas específicas en SQL Server.
 */
class SqlServerExampleModel extends SqlServerBaseModel
{
    /**
     * Configuración del modelo
     */
    protected $table = 'empleados'; // Cambia por tu tabla
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'departamento_id',
        'activo'
    ];
    
    /**
     * Validaciones
     */
    protected $validationRules = [
        'nombre'    => 'required|min_length[2]|max_length[100]',
        'apellido'  => 'required|min_length[2]|max_length[100]',
        'email'     => 'required|valid_email|is_unique[empleados.email,id,{id}]',
        'telefono'  => 'permit_empty|min_length[10]|max_length[15]',
        'departamento_id' => 'required|integer',
        'activo'    => 'required|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'Debe proporcionar un email válido',
            'is_unique' => 'Este email ya está registrado'
        ]
    ];
    
    /**
     * Obtiene empleados con información del departamento
     * 
     * @param array $filtros Filtros opcionales
     * @return array
     */
    public function getEmpleadosConDepartamento(array $filtros = [])
    {
        try {
            $sql = "
                SELECT 
                    e.id,
                    e.nombre,
                    e.apellido,
                    e.email,
                    e.telefono,
                    e.activo,
                    e.fecha_creacion,
                    d.nombre as departamento_nombre
                FROM empleados e
                INNER JOIN departamentos d ON e.departamento_id = d.id
                WHERE 1=1
            ";
            
            $binds = [];
            
            // Aplicar filtros
            if (!empty($filtros['activo'])) {
                $sql .= " AND e.activo = ?";
                $binds[] = $filtros['activo'];
            }
            
            if (!empty($filtros['departamento_id'])) {
                $sql .= " AND e.departamento_id = ?";
                $binds[] = $filtros['departamento_id'];
            }
            
            if (!empty($filtros['buscar'])) {
                $sql .= " AND (e.nombre LIKE ? OR e.apellido LIKE ? OR e.email LIKE ?)";
                $buscar = '%' . $filtros['buscar'] . '%';
                $binds[] = $buscar;
                $binds[] = $buscar;
                $binds[] = $buscar;
            }
            
            $sql .= " ORDER BY e.apellido, e.nombre";
            
            $query = $this->executeSqlServerQuery($sql, $binds);
            return $query->getResultArray();
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener empleados con departamento: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Obtiene empleados paginados para DataTables
     * 
     * @param int $start Inicio de paginación
     * @param int $length Cantidad de registros
     * @param string $search Término de búsqueda
     * @param array $order Ordenamiento
     * @return array
     */
    public function getEmpleadosDataTable(int $start = 0, int $length = 10, string $search = '', array $order = [])
    {
        try {
            // Consulta base
            $sqlBase = "
                FROM empleados e
                INNER JOIN departamentos d ON e.departamento_id = d.id
                WHERE 1=1
            ";
            
            $binds = [];
            
            // Aplicar búsqueda
            if (!empty($search)) {
                $sqlBase .= " AND (e.nombre LIKE ? OR e.apellido LIKE ? OR e.email LIKE ? OR d.nombre LIKE ?)";
                $searchTerm = '%' . $search . '%';
                $binds = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
            }
            
            // Contar total de registros
            $sqlCount = "SELECT COUNT(*) as total " . $sqlBase;
            $queryCount = $this->executeSqlServerQuery($sqlCount, $binds);
            $totalRecords = $queryCount->getRow()->total;
            
            // Consulta principal con paginación
            $sqlData = "
                SELECT 
                    e.id,
                    e.nombre,
                    e.apellido,
                    e.email,
                    e.telefono,
                    e.activo,
                    e.fecha_creacion,
                    d.nombre as departamento_nombre
                " . $sqlBase;
            
            // Aplicar ordenamiento
            if (!empty($order)) {
                $columns = ['e.id', 'e.nombre', 'e.apellido', 'e.email', 'd.nombre', 'e.activo'];
                $orderColumn = $columns[$order[0]['column']] ?? 'e.id';
                $orderDir = $order[0]['dir'] ?? 'asc';
                $sqlData .= " ORDER BY {$orderColumn} {$orderDir}";
            } else {
                $sqlData .= " ORDER BY e.apellido, e.nombre";
            }
            
            // Aplicar paginación SQL Server
            $sqlData .= " OFFSET {$start} ROWS FETCH NEXT {$length} ROWS ONLY";
            
            $queryData = $this->executeSqlServerQuery($sqlData, $binds);
            $data = $queryData->getResultArray();
            
            return [
                'data' => $data,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Error en DataTable empleados: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Crea un nuevo empleado
     * 
     * @param array $data Datos del empleado
     * @return int ID del empleado creado
     */
    public function crearEmpleado(array $data)
    {
        try {
            // Validar datos
            if (!$this->validate($data)) {
                throw new \Exception('Datos de empleado inválidos');
            }
            
            // Insertar empleado
            $empleadoId = $this->insertRecord($this->table, $data);
            
            if (!$empleadoId) {
                throw new \Exception('Error al crear empleado');
            }
            
            log_message('info', "Empleado creado con ID: {$empleadoId}");
            return $empleadoId;
            
        } catch (\Exception $e) {
            log_message('error', 'Error al crear empleado: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Actualiza un empleado existente
     * 
     * @param int $id ID del empleado
     * @param array $data Datos a actualizar
     * @return bool
     */
    public function actualizarEmpleado(int $id, array $data)
    {
        try {
            // Validar que el empleado existe
            $empleado = $this->find($id);
            if (!$empleado) {
                throw new \Exception('Empleado no encontrado');
            }
            
            // Validar datos
            $data['id'] = $id; // Para validación is_unique
            if (!$this->validate($data)) {
                throw new \Exception('Datos de empleado inválidos');
            }
            unset($data['id']);
            
            // Actualizar empleado
            $resultado = $this->updateRecord($this->table, $data, ['id' => $id]);
            
            if (!$resultado) {
                throw new \Exception('Error al actualizar empleado');
            }
            
            log_message('info', "Empleado actualizado ID: {$id}");
            return true;
            
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar empleado: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Cambia el estado activo/inactivo de un empleado
     * 
     * @param int $id ID del empleado
     * @param int $estado Nuevo estado (0 o 1)
     * @return bool
     */
    public function cambiarEstadoEmpleado(int $id, int $estado)
    {
        try {
            $resultado = $this->updateRecord(
                $this->table, 
                ['activo' => $estado], 
                ['id' => $id]
            );
            
            if (!$resultado) {
                throw new \Exception('Error al cambiar estado del empleado');
            }
            
            $estadoTexto = $estado ? 'activado' : 'desactivado';
            log_message('info', "Empleado {$estadoTexto} ID: {$id}");
            
            return true;
            
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar estado empleado: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Obtiene estadísticas de empleados
     * 
     * @return array
     */
    public function getEstadisticasEmpleados()
    {
        try {
            $sql = "
                SELECT 
                    COUNT(*) as total_empleados,
                    SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as empleados_activos,
                    SUM(CASE WHEN activo = 0 THEN 1 ELSE 0 END) as empleados_inactivos,
                    COUNT(DISTINCT departamento_id) as departamentos_con_empleados
                FROM empleados
            ";
            
            $query = $this->executeSqlServerQuery($sql);
            $estadisticas = $query->getRow();
            
            // Obtener empleados por departamento
            $sqlDepartamentos = "
                SELECT 
                    d.nombre as departamento,
                    COUNT(e.id) as cantidad_empleados
                FROM departamentos d
                LEFT JOIN empleados e ON d.id = e.departamento_id AND e.activo = 1
                GROUP BY d.id, d.nombre
                ORDER BY cantidad_empleados DESC
            ";
            
            $queryDepartamentos = $this->executeSqlServerQuery($sqlDepartamentos);
            $empleadosPorDepartamento = $queryDepartamentos->getResultArray();
            
            return [
                'resumen' => $estadisticas,
                'por_departamento' => $empleadosPorDepartamento
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Busca empleados por criterios específicos
     * 
     * @param array $criterios Criterios de búsqueda
     * @return array
     */
    public function buscarEmpleados(array $criterios)
    {
        try {
            $sql = "
                SELECT 
                    e.id,
                    e.nombre,
                    e.apellido,
                    e.email,
                    e.telefono,
                    e.activo,
                    d.nombre as departamento_nombre
                FROM empleados e
                INNER JOIN departamentos d ON e.departamento_id = d.id
                WHERE 1=1
            ";
            
            $binds = [];
            
            if (!empty($criterios['nombre'])) {
                $sql .= " AND e.nombre LIKE ?";
                $binds[] = '%' . $criterios['nombre'] . '%';
            }
            
            if (!empty($criterios['email'])) {
                $sql .= " AND e.email LIKE ?";
                $binds[] = '%' . $criterios['email'] . '%';
            }
            
            if (isset($criterios['activo'])) {
                $sql .= " AND e.activo = ?";
                $binds[] = $criterios['activo'];
            }
            
            if (!empty($criterios['departamento_id'])) {
                $sql .= " AND e.departamento_id = ?";
                $binds[] = $criterios['departamento_id'];
            }
            
            $sql .= " ORDER BY e.apellido, e.nombre";
            
            $query = $this->executeSqlServerQuery($sql, $binds);
            return $query->getResultArray();
            
        } catch (\Exception $e) {
            log_message('error', 'Error al buscar empleados: ' . $e->getMessage());
            throw $e;
        }
    }
}
