<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SqlServerExampleModel;
use App\Models\SqlServerBaseModel;

/**
 * Controlador de ejemplo para SQL Server
 * 
 * Demuestra cómo usar los modelos de SQL Server en el sistema GMV
 */
class SqlServerExample extends BaseController
{
    protected $empleadoModel;
    protected $sqlServerBase;
    
    public function __construct()
    {
        $this->empleadoModel = new SqlServerExampleModel();
        $this->sqlServerBase = new SqlServerBaseModel();
    }
    
    /**
     * Página principal - Lista de empleados
     */
    public function index()
    {
        try {
            // Verificar conexión
            if (!$this->sqlServerBase->testConnection()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error de conexión a SQL Server'
                ]);
            }
            
            $data = [
                'title' => 'Gestión de Empleados - SQL Server',
                'empleados' => [],
                'estadisticas' => []
            ];
            
            // Obtener empleados y estadísticas
            try {
                $data['empleados'] = $this->empleadoModel->getEmpleadosConDepartamento();
                $data['estadisticas'] = $this->empleadoModel->getEstadisticasEmpleados();
            } catch (\Exception $e) {
                $data['error'] = 'Error al cargar datos: ' . $e->getMessage();
            }
            
            return view('sqlserver/empleados/index', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en SqlServerExample::index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error del sistema: ' . $e->getMessage());
        }
    }
    
    /**
     * Datos para DataTables (AJAX)
     */
    public function getData()
    {
        try {
            $request = $this->request;
            
            $start = (int)($request->getPost('start') ?? 0);
            $length = (int)($request->getPost('length') ?? 10);
            $search = $request->getPost('search')['value'] ?? '';
            $order = $request->getPost('order') ?? [];
            
            $result = $this->empleadoModel->getEmpleadosDataTable($start, $length, $search, $order);
            
            // Formatear datos para DataTables
            $data = [];
            foreach ($result['data'] as $empleado) {
                $estado = $empleado['activo'] ? 
                    '<span class="badge bg-success">Activo</span>' : 
                    '<span class="badge bg-danger">Inactivo</span>';
                
                $acciones = '
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary ver-empleado" 
                                data-id="' . $empleado['id'] . '" 
                                title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning editar-empleado" 
                                data-id="' . $empleado['id'] . '" 
                                title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-' . ($empleado['activo'] ? 'danger' : 'success') . ' cambiar-estado" 
                                data-id="' . $empleado['id'] . '" 
                                data-estado="' . ($empleado['activo'] ? '0' : '1') . '"
                                title="' . ($empleado['activo'] ? 'Desactivar' : 'Activar') . '">
                            <i class="fas fa-' . ($empleado['activo'] ? 'times' : 'check') . '"></i>
                        </button>
                    </div>
                ';
                
                $data[] = [
                    $empleado['id'],
                    $empleado['nombre'] . ' ' . $empleado['apellido'],
                    $empleado['email'],
                    $empleado['telefono'] ?? 'N/A',
                    $empleado['departamento_nombre'],
                    $estado,
                    date('d/m/Y', strtotime($empleado['fecha_creacion'])),
                    $acciones
                ];
            }
            
            return $this->response->setJSON([
                'draw' => (int)$request->getPost('draw'),
                'recordsTotal' => $result['recordsTotal'],
                'recordsFiltered' => $result['recordsFiltered'],
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getData: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al cargar datos: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Formulario para crear empleado
     */
    public function create()
    {
        $data = [
            'title' => 'Crear Empleado',
            'departamentos' => $this->getDepartamentos()
        ];
        
        return view('sqlserver/empleados/form', $data);
    }
    
    /**
     * Guardar nuevo empleado
     */
    public function store()
    {
        try {
            $datos = [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'email' => $this->request->getPost('email'),
                'telefono' => $this->request->getPost('telefono'),
                'departamento_id' => $this->request->getPost('departamento_id'),
                'activo' => 1
            ];
            
            $empleadoId = $this->empleadoModel->crearEmpleado($datos);
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Empleado creado exitosamente',
                    'id' => $empleadoId
                ]);
            }
            
            return redirect()->to('/sqlserver-example')->with('success', 'Empleado creado exitosamente');
            
        } catch (\Exception $e) {
            log_message('error', 'Error al crear empleado: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear empleado: ' . $e->getMessage()
                ]);
            }
            
            return redirect()->back()->withInput()->with('error', 'Error al crear empleado: ' . $e->getMessage());
        }
    }
    
    /**
     * Ver detalles de un empleado
     */
    public function show($id)
    {
        try {
            $empleado = $this->empleadoModel->find($id);
            
            if (!$empleado) {
                throw new \Exception('Empleado no encontrado');
            }
            
            $data = [
                'title' => 'Detalles del Empleado',
                'empleado' => $empleado
            ];
            
            return view('sqlserver/empleados/show', $data);
            
        } catch (\Exception $e) {
            return redirect()->to('/sqlserver-example')->with('error', $e->getMessage());
        }
    }
    
    /**
     * Formulario para editar empleado
     */
    public function edit($id)
    {
        try {
            $empleado = $this->empleadoModel->find($id);
            
            if (!$empleado) {
                throw new \Exception('Empleado no encontrado');
            }
            
            $data = [
                'title' => 'Editar Empleado',
                'empleado' => $empleado,
                'departamentos' => $this->getDepartamentos()
            ];
            
            return view('sqlserver/empleados/form', $data);
            
        } catch (\Exception $e) {
            return redirect()->to('/sqlserver-example')->with('error', $e->getMessage());
        }
    }
    
    /**
     * Actualizar empleado
     */
    public function update($id)
    {
        try {
            $datos = [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'email' => $this->request->getPost('email'),
                'telefono' => $this->request->getPost('telefono'),
                'departamento_id' => $this->request->getPost('departamento_id')
            ];
            
            $this->empleadoModel->actualizarEmpleado($id, $datos);
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Empleado actualizado exitosamente'
                ]);
            }
            
            return redirect()->to('/sqlserver-example')->with('success', 'Empleado actualizado exitosamente');
            
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar empleado: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar empleado: ' . $e->getMessage()
                ]);
            }
            
            return redirect()->back()->withInput()->with('error', 'Error al actualizar empleado: ' . $e->getMessage());
        }
    }
    
    /**
     * Cambiar estado del empleado (AJAX)
     */
    public function cambiarEstado($id)
    {
        try {
            if (!$this->request->isAJAX()) {
                throw new \Exception('Acceso no autorizado');
            }
            
            $estado = $this->request->getPost('estado');
            
            $this->empleadoModel->cambiarEstadoEmpleado($id, $estado);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado cambiado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar estado: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cambiar estado: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Buscar empleados (AJAX)
     */
    public function buscar()
    {
        try {
            $criterios = [
                'nombre' => $this->request->getPost('nombre'),
                'email' => $this->request->getPost('email'),
                'activo' => $this->request->getPost('activo'),
                'departamento_id' => $this->request->getPost('departamento_id')
            ];
            
            $empleados = $this->empleadoModel->buscarEmpleados($criterios);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $empleados
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error en búsqueda: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Obtener estadísticas (AJAX)
     */
    public function estadisticas()
    {
        try {
            $estadisticas = $this->empleadoModel->getEstadisticasEmpleados();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Probar conexión SQL Server
     */
    public function testConnection()
    {
        try {
            $conexionOk = $this->sqlServerBase->testConnection();
            $version = $this->sqlServerBase->getSqlServerVersion();
            $tablas = $this->sqlServerBase->getTableInfo();
            
            $data = [
                'conexion' => $conexionOk,
                'version' => $version,
                'tablas' => $tablas,
                'mensaje' => $conexionOk ? 'Conexión exitosa' : 'Error de conexión'
            ];
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON($data);
            }
            
            return view('sqlserver/test_connection', $data);
            
        } catch (\Exception $e) {
            $error = 'Error al probar conexión: ' . $e->getMessage();
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $error
                ]);
            }
            
            return view('sqlserver/test_connection', ['error' => $error]);
        }
    }
    
    /**
     * Ejecutar consulta personalizada
     */
    public function executeQuery()
    {
        try {
            $sql = $this->request->getPost('sql');
            
            if (empty($sql)) {
                throw new \Exception('Consulta SQL requerida');
            }
            
            // Validar que sea una consulta SELECT (seguridad básica)
            if (!preg_match('/^\s*SELECT/i', trim($sql))) {
                throw new \Exception('Solo se permiten consultas SELECT');
            }
            
            $query = $this->sqlServerBase->executeSqlServerQuery($sql);
            $results = $query->getResultArray();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $results,
                'count' => count($results)
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error en consulta: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Obtener departamentos (método auxiliar)
     */
    private function getDepartamentos()
    {
        try {
            $sql = "SELECT id, nombre FROM departamentos WHERE activo = 1 ORDER BY nombre";
            $query = $this->sqlServerBase->executeSqlServerQuery($sql);
            return $query->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener departamentos: ' . $e->getMessage());
            return [];
        }
    }
}
