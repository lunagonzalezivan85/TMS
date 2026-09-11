<?php

namespace App\Controllers;

use App\Models\MaterialesModel;
use CodeIgniter\Controller;

class Materiales extends BaseController
{
    protected $materialesModel;
    protected $empresaId;

    public function __construct()
    {
        $this->materialesModel = new MaterialesModel();
        $this->empresaId = session()->get('empresa_id') ?? 1;
    }

    /**
     * Mostrar lista de materiales
     */
    public function index()
    {
        $busqueda = $this->request->getGet('busqueda');
        $pagina = (int)($this->request->getGet('page') ?? 1);
        $limite = 20;
        $offset = ($pagina - 1) * $limite;

        $materiales = $this->materialesModel->getMaterialesPorEmpresa(
            $this->empresaId, 
            $limite, 
            $offset, 
            $busqueda
        );

        $totalMateriales = $this->materialesModel->contarMaterialesPorEmpresa($this->empresaId, $busqueda);
        $totalPaginas = ceil($totalMateriales / $limite);

        // Obtener estadísticas
        $estadisticas = $this->materialesModel->getEstadisticasPorEmpresa($this->empresaId);

        $data = [
            'title' => 'Gestión de Materiales',
            'materiales' => $materiales,
            'busqueda' => $busqueda,
            'paginaActual' => $pagina,
            'totalPaginas' => $totalPaginas,
            'totalMateriales' => $totalMateriales,
            'estadisticas' => $estadisticas
        ];

        return view('materiales/index', $data);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $unidadesMedida = $this->materialesModel->getUnidadesMedida($this->empresaId);
        
        $data = [
            'title' => 'Nuevo Material',
            'unidadesMedida' => $unidadesMedida
        ];

        return view('materiales/create', $data);
    }

    /**
     * Guardar nuevo material
     */
    public function store()
    {
        $usuarioId = session()->get('usuario_id');
        
        // Si no hay usuario_id, intentar con otras claves comunes o usar fallback
        if (!$usuarioId) {
            $usuarioId = session()->get('user_id') ?? session()->get('id') ?? session()->get('usuario') ?? 1;
        }

        // Validar datos
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombre' => 'required|min_length[3]|max_length[255]',
            'unidad_medida' => 'required|max_length[50]',
            'costo_unitario' => 'required|decimal|greater_than_equal_to[0]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $validation->getErrors());
        }

        try {
            // Generar código consecutivo
            $codigoConsecutivo = $this->materialesModel->generarCodigoConsecutivo($this->empresaId);
            
            $data = [
                'id_empresa' => $this->empresaId,
                'nombre' => trim($this->request->getPost('nombre')),
                'unidad_medida' => strtoupper(trim($this->request->getPost('unidad_medida'))),
                'costo_unitario' => (float)$this->request->getPost('costo_unitario'),
                'usuarioCrea' => $usuarioId,
                'codigo_consecutivo' => $codigoConsecutivo,
                'fechaRegistro' => date('Y-m-d H:i:s')
            ];

            $id = $this->materialesModel->insert($data);
            
            // Verificar errores del modelo
            $errors = $this->materialesModel->errors();
            if (!empty($errors)) {
                return redirect()->back()
                               ->withInput()
                               ->with('errors', $errors);
            }

            if ($id) {
                return redirect()->to('materiales')
                               ->with('success', 'Material creado exitosamente');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Error al crear el material');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear el material: ' . $e->getMessage());
        }
    }

    /**
     * Vista de sincronización con SQL Server
     */
    public function sincronizacion()
    {
        $data = [
            'title' => 'Sincronización de Materiales',
        ];

        return view('materiales/sincronizacion', $data);
    }

    /**
     * Obtener productos de SQL Server para sincronizar
     */
    public function obtenerProductosSqlServer()
    {
        try {
            log_message('debug', 'Materiales::obtenerProductosSqlServer - Iniciando');
            
            // Obtener parámetros de paginación
            $limite = (int)($this->request->getGet('limite') ?? 50);
            $offset = (int)($this->request->getGet('offset') ?? 0);
            $busqueda = $this->request->getGet('busqueda') ?? '';
            
            // Usar conexión SQLSRV nativa (como en direcciones)
            $connectionInfo = [
                "Database" => "GCM_PRUEBAS",
                "UID" => "sag",
                "PWD" => "sag"
            ];
            
            $conn = sqlsrv_connect("15.235.109.18,1433", $connectionInfo);
            
            if (!$conn) {
                $errors = sqlsrv_errors();
                $errorMsg = 'Error de conexión SQLSRV: ';
                foreach ($errors as $error) {
                    $errorMsg .= $error['message'] . ' ';
                }
                log_message('error', $errorMsg);
                throw new \Exception($errorMsg);
            }
            
            log_message('debug', 'Materiales::obtenerProductosSqlServer - Conexión SQLSRV exitosa');

            // Construir query con búsqueda y paginación
            $whereClause = "WHERE ESTADO_PRODUCTO = 'A'";
            $params = [];
            
            if (!empty($busqueda)) {
                $whereClause .= " AND (CODIGO_PRODUCTO LIKE ? OR DESCRIPCION LIKE ? OR DESCRIPCION_CORTA LIKE ?)";
                $searchTerm = "%{$busqueda}%";
                $params = [$searchTerm, $searchTerm, $searchTerm];
            }
            
            // Query para contar total
            $countQuery = "SELECT COUNT(*) as total FROM INV_PRODUCTOS {$whereClause}";
            $countStmt = sqlsrv_query($conn, $countQuery, $params);
            
            if (!$countStmt) {
                $errors = sqlsrv_errors();
                $errorMsg = 'Error en consulta count: ';
                foreach ($errors as $error) {
                    $errorMsg .= $error['message'] . ' ';
                }
                log_message('error', $errorMsg);
                sqlsrv_close($conn);
                throw new \Exception($errorMsg);
            }
            
            $countRow = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);
            $total = $countRow['total'];
            
            // Query principal con paginación
            $query = "SELECT 
                        CODIGO_PRODUCTO,
                        CODIGO_BARRA,
                        DESCRIPCION,
                        DESCRIPCION_CORTA,
                        COSTO_UNITARIO,
                        EXISTENCIA_TOTAL,
                        ESTADO_PRODUCTO
                      FROM INV_PRODUCTOS 
                      {$whereClause}
                      ORDER BY CODIGO_PRODUCTO
                      OFFSET ? ROWS
                      FETCH NEXT ? ROWS ONLY";

            // Asegurar que offset y limite sean enteros
            $queryParams = array_merge($params, [(int)$offset, (int)$limite]);
            
            log_message('debug', 'Materiales::obtenerProductosSqlServer - Ejecutando query: ' . $query);
            log_message('debug', 'Materiales::obtenerProductosSqlServer - Parámetros: offset=' . $offset . ', limite=' . $limite . ', busqueda=' . $busqueda);
            
            $stmt = sqlsrv_query($conn, $query, $queryParams);
            
            if (!$stmt) {
                $errors = sqlsrv_errors();
                $errorMsg = 'Error en consulta: ';
                foreach ($errors as $error) {
                    $errorMsg .= $error['message'] . ' ';
                }
                log_message('error', $errorMsg);
                sqlsrv_close($conn);
                throw new \Exception($errorMsg);
            }
            
            // Convertir resultados a array con limpieza UTF-8
            $productosSqlServer = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                // Limpiar caracteres UTF-8 malformados
                $cleanRow = [];
                foreach ($row as $key => $value) {
                    if (is_string($value)) {
                        // Convertir a UTF-8 válido
                        $cleanRow[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    } else {
                        $cleanRow[$key] = $value;
                    }
                }
                $productosSqlServer[] = $cleanRow;
            }
            
            log_message('debug', 'Materiales::obtenerProductosSqlServer - Registros obtenidos: ' . count($productosSqlServer));
            
            // Cerrar conexión SQLSRV
            sqlsrv_close($conn);

            // Obtener códigos de vinculación existentes en MySQL
            $codigosExistentes = $this->materialesModel
                ->select('codigo_vinculacion')
                ->where('codigo_vinculacion IS NOT NULL')
                ->where('codigo_vinculacion !=', '')
                ->where('id_empresa', $this->empresaId)
                ->findAll();

            $codigosArray = array_column($codigosExistentes, 'codigo_vinculacion');
            
            // Verificar cuáles ya están sincronizados
            $productosConEstado = [];
            foreach ($productosSqlServer as $producto) {
                $sincronizado = in_array($producto['CODIGO_PRODUCTO'], $codigosArray);
                
                $producto['sincronizado'] = $sincronizado;
                $producto['material_id'] = null;
                
                if ($sincronizado) {
                    $materialExistente = $this->materialesModel
                        ->where('codigo_vinculacion', $producto['CODIGO_PRODUCTO'])
                        ->where('id_empresa', $this->empresaId)
                        ->first();
                    $producto['material_id'] = $materialExistente['id'] ?? null;
                }
                
                $productosConEstado[] = $producto;
            }
            
            return $this->response->setJSON([
                'success' => true,
                'productos' => $productosConEstado,
                'total' => $total,
                'limite' => $limite,
                'offset' => $offset
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en obtenerProductosSqlServer: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al conectar con SQL Server: ' . $e->getMessage(),
                'debug_info' => [
                    'error_message' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'file' => basename($e->getFile()),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    /**
     * Sincronizar múltiples productos
     */
    public function sincronizarProductosMasivo()
    {
        $usuarioId = session()->get('usuario_id') ?? 1;
        $codigosProductos = $this->request->getPost('codigos_productos');
        
        if (empty($codigosProductos) || !is_array($codigosProductos)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Códigos de productos requeridos'
            ]);
        }
        
        try {
            // Conectar a SQL Server
            $connectionInfo = [
                "Database" => "GCM_PRUEBAS",
                "UID" => "sag",
                "PWD" => "sag"
            ];
            
            $conn = sqlsrv_connect("15.235.109.18,1433", $connectionInfo);
            
            if (!$conn) {
                $errors = sqlsrv_errors();
                $errorMsg = 'Error de conexión SQLSRV: ';
                foreach ($errors as $error) {
                    $errorMsg .= $error['message'] . ' ';
                }
                throw new \Exception($errorMsg);
            }
            
            $resultados = [
                'exitosos' => [],
                'errores' => [],
                'ya_sincronizados' => []
            ];
            
            foreach ($codigosProductos as $codigoProducto) {
                try {
                    // Verificar si ya existe
                    $materialExistente = $this->materialesModel
                        ->where('codigo_vinculacion', $codigoProducto)
                        ->where('id_empresa', $this->empresaId)
                        ->first();
                    
                    if ($materialExistente) {
                        $resultados['ya_sincronizados'][] = [
                            'codigo' => $codigoProducto,
                            'mensaje' => 'Ya existe'
                        ];
                        continue;
                    }
                    
                    // Obtener producto de SQL Server
                    $query = "SELECT 
                                CODIGO_PRODUCTO,
                                DESCRIPCION,
                                DESCRIPCION_CORTA,
                                COSTO_UNITARIO
                              FROM INV_PRODUCTOS 
                              WHERE CODIGO_PRODUCTO = ? AND ESTADO_PRODUCTO = 'A'";
                    
                    $stmt = sqlsrv_query($conn, $query, [$codigoProducto]);
                    
                    if (!$stmt) {
                        $resultados['errores'][] = [
                            'codigo' => $codigoProducto,
                            'mensaje' => 'Error en consulta SQL Server'
                        ];
                        continue;
                    }
                    
                    $producto = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                    
                    if (!$producto) {
                        $resultados['errores'][] = [
                            'codigo' => $codigoProducto,
                            'mensaje' => 'Producto no encontrado'
                        ];
                        continue;
                    }
                    
                    // Limpiar datos UTF-8
                    foreach ($producto as $key => $value) {
                        if (is_string($value)) {
                            $producto[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    // Generar código consecutivo
                    $codigoConsecutivo = $this->materialesModel->generarCodigoConsecutivo($this->empresaId);
                    
                    // Preparar datos para insertar
                    $data = [
                        'id_empresa' => $this->empresaId,
                        'codigo_consecutivo' => $codigoConsecutivo,
                        'codigo_vinculacion' => $producto['CODIGO_PRODUCTO'],
                        'nombre' => $producto['DESCRIPCION'] ?? $producto['DESCRIPCION_CORTA'],
                        'unidad_medida' => 'UNIDAD', // Valor por defecto
                        'costo_unitario' => $producto['COSTO_UNITARIO'] ?? 0,
                        'usuarioCrea' => $usuarioId,
                        'fechaRegistro' => date('Y-m-d H:i:s')
                    ];
                    
                    $id = $this->materialesModel->insert($data);
                    
                    if ($id) {
                        $resultados['exitosos'][] = [
                            'codigo' => $codigoProducto,
                            'id' => $id,
                            'codigo_consecutivo' => $codigoConsecutivo,
                            'nombre' => $data['nombre']
                        ];
                    } else {
                        $resultados['errores'][] = [
                            'codigo' => $codigoProducto,
                            'mensaje' => 'Error al insertar en base de datos'
                        ];
                    }
                    
                } catch (\Exception $e) {
                    $resultados['errores'][] = [
                        'codigo' => $codigoProducto,
                        'mensaje' => $e->getMessage()
                    ];
                }
            }
            
            // Cerrar conexión
            sqlsrv_close($conn);
            
            $totalExitosos = count($resultados['exitosos']);
            $totalErrores = count($resultados['errores']);
            $totalYaSincronizados = count($resultados['ya_sincronizados']);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "Sincronización completada: {$totalExitosos} exitosos, {$totalErrores} errores, {$totalYaSincronizados} ya sincronizados",
                'resultados' => $resultados,
                'estadisticas' => [
                    'total_procesados' => count($codigosProductos),
                    'exitosos' => $totalExitosos,
                    'errores' => $totalErrores,
                    'ya_sincronizados' => $totalYaSincronizados
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en sincronizarProductosMasivo: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error en sincronización masiva: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Sincronizar producto específico
     */
    public function sincronizarProducto()
    {
        $usuarioId = session()->get('usuario_id') ?? 1;
        $codigoProducto = $this->request->getPost('codigo_producto');
        
        if (!$codigoProducto) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Código de producto requerido'
            ]);
        }
        
        try {
            // Obtener producto de SQL Server
            $invProductosModel = new \App\Models\InvProductosModel();
            $producto = $invProductosModel->getProductoPorCodigo($codigoProducto);
            
            if (!$producto) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Producto no encontrado en SQL Server'
                ]);
            }
            
            // Verificar si ya existe
            $materialExistente = $this->materialesModel
                ->where('codigo_vinculacion', $codigoProducto)
                ->where('id_empresa', $this->empresaId)
                ->first();
            
            if ($materialExistente) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El producto ya está sincronizado'
                ]);
            }
            
            // Generar código consecutivo
            $codigoConsecutivo = $this->materialesModel->generarCodigoConsecutivo($this->empresaId);
            
            // Preparar datos para insertar
            $data = [
                'id_empresa' => $this->empresaId,
                'codigo_consecutivo' => $codigoConsecutivo,
                'codigo_vinculacion' => $producto['CODIGO_PRODUCTO'],
                'nombre' => $producto['DESCRIPCION'] ?? $producto['DESCRIPCION_CORTA'],
                'unidad_medida' => 'UNIDAD', // Valor por defecto, se puede ajustar
                'costo_unitario' => $producto['COSTO_UNITARIO'] ?? 0,
                'usuarioCrea' => $usuarioId,
                'fechaRegistro' => date('Y-m-d H:i:s')
            ];
            
            $id = $this->materialesModel->insert($data);
            
            if ($id) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Producto sincronizado exitosamente',
                    'material_id' => $id,
                    'codigo_consecutivo' => $codigoConsecutivo
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al sincronizar producto'
                ]);
            }
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al sincronizar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mostrar material específico
     */
    public function show($id = null)
    {
        if (!$id) {
            return redirect()->to('materiales')->with('error', 'Material no encontrado');
        }

        $material = $this->materialesModel->getMaterialConUsuarios($id, $this->empresaId);

        if (!$material) {
            return redirect()->to('materiales')->with('error', 'Material no encontrado');
        }

        $data = [
            'title' => 'Detalles del Material',
            'material' => $material
        ];

        return view('materiales/show', $data);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->to('materiales')->with('error', 'Material no encontrado');
        }

        $material = $this->materialesModel->getMaterialConUsuarios($id, $this->empresaId);

        if (!$material) {
            return redirect()->to('materiales')->with('error', 'Material no encontrado');
        }

        $unidadesMedida = $this->materialesModel->getUnidadesMedida($this->empresaId);

        $data = [
            'title' => 'Editar Material',
            'material' => $material,
            'unidadesMedida' => $unidadesMedida
        ];

        return view('materiales/edit', $data);
    }

    /**
     * Actualizar material
     */
    public function update($id = null)
    {
        if (!$id) {
            return redirect()->to('materiales')->with('error', 'Material no encontrado');
        }

        $material = $this->materialesModel->find($id);
        if (!$material || $material['id_empresa'] != $this->empresaId) {
            return redirect()->to('materiales')->with('error', 'Material no encontrado');
        }

        $usuarioId = session()->get('usuario_id');

        // Validar datos
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombre' => 'required|min_length[3]|max_length[255]',
            'unidad_medida' => 'required|max_length[50]',
            'costo_unitario' => 'required|decimal|greater_than_equal_to[0]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $validation->getErrors());
        }

        try {
            $data = [
                'nombre' => trim($this->request->getPost('nombre')),
                'unidad_medida' => strtoupper(trim($this->request->getPost('unidad_medida'))),
                'costo_unitario' => $this->request->getPost('costo_unitario')
            ];

            $actualizado = $this->materialesModel->actualizarMaterial($id, $data, $usuarioId);

            if ($actualizado) {
                return redirect()->to('materiales')
                               ->with('success', 'Material actualizado exitosamente');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Error al actualizar el material');
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar material: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar el material: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar material
     */
    public function delete($id = null)
    {
        if (!$id) {
            return redirect()->to('materiales')->with('error', 'Material no encontrado');
        }

        $material = $this->materialesModel->find($id);
        if (!$material || $material['id_empresa'] != $this->empresaId) {
            return redirect()->to('materiales')->with('error', 'Material no encontrado');
        }

        try {
            $resultado = $this->materialesModel->eliminarMaterial($id);

            if ($resultado['success']) {
                return redirect()->to('materiales')
                               ->with('success', $resultado['message']);
            } else {
                return redirect()->to('materiales')
                               ->with('error', $resultado['message']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar material: ' . $e->getMessage());
            return redirect()->to('materiales')
                           ->with('error', 'Error al eliminar el material: ' . $e->getMessage());
        }
    }

    /**
     * API para buscar materiales (AJAX)
     */
    public function buscar()
    {
        $termino = $this->request->getGet('q');
        $limite = (int)($this->request->getGet('limit') ?? 10);

        if (empty($termino)) {
            return $this->response->setJSON([]);
        }

        $materiales = $this->materialesModel->buscarPorNombre($termino, $this->empresaId, $limite);

        return $this->response->setJSON($materiales);
    }

    /**
     * API para obtener material por ID (AJAX)
     */
    public function obtener($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['error' => 'ID requerido']);
        }

        $material = $this->materialesModel->find($id);

        if (!$material || $material['id_empresa'] != $this->empresaId) {
            return $this->response->setJSON(['error' => 'Material no encontrado']);
        }

        return $this->response->setJSON($material);
    }

    /**
     * Exportar materiales a CSV
     */
    public function exportar()
    {
        $materiales = $this->materialesModel->getMaterialesPorEmpresa($this->empresaId, 1000);

        $filename = 'materiales_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Encabezados
        fputcsv($output, [
            'Código',
            'Nombre',
            'Unidad de Medida',
            'Costo Unitario',
            'Fecha Registro',
            'Creado Por'
        ]);
        
        // Datos
        foreach ($materiales as $material) {
            fputcsv($output, [
                $material['codigo_consecutivo'],
                $material['nombre'],
                $material['unidad_medida'],
                $material['costo_unitario'],
                $material['fechaRegistro'],
                $material['nombre_creador'] ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }
}
