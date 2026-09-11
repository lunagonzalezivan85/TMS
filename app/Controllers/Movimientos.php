<?php

namespace App\Controllers;

use App\Models\MovimientosModel;
use App\Models\DetalleMovimientoModel;
use App\Models\MaterialesModel;
use CodeIgniter\HTTP\ResponseInterface;

class Movimientos extends BaseController
{
    protected $movimientosModel;
    protected $detalleMovimientoModel;
    protected $materialesModel;
    protected $session;

    public function __construct()
    {
        $this->movimientosModel = new MovimientosModel();
        $this->detalleMovimientoModel = new DetalleMovimientoModel();
        $this->materialesModel = new MaterialesModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Vista principal - Lista de movimientos
     */
    public function index()
    {
        $data = [
            'title' => 'Gestión de Movimientos de Inventario',
            'tipos_movimiento' => $this->movimientosModel->getTiposMovimiento(),
            'estados' => $this->movimientosModel->getEstados()
        ];

        return view('movimientos/index', $data);
    }

    /**
     * Obtener datos para DataTables (AJAX)
     */
    public function getData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $idEmpresa = $this->session->get('empresa_id');
        
        // Parámetros de DataTables
        $start = (int)($this->request->getPost('start') ?? 0);
        $length = (int)($this->request->getPost('length') ?? 10);
        $searchValue = $this->request->getPost('search')['value'] ?? '';
        
        // Filtros adicionales
        $filtros = [
            'tipo_movimiento' => $this->request->getPost('tipo_movimiento'),
            'estado' => $this->request->getPost('estado'),
            'fecha_desde' => $this->request->getPost('fecha_desde'),
            'fecha_hasta' => $this->request->getPost('fecha_hasta'),
            'busqueda' => $searchValue
        ];

        try {
            $movimientos = $this->movimientosModel->getMovimientosPorEmpresa($idEmpresa, $length, $start, $filtros);
            $totalRegistros = $this->movimientosModel->contarMovimientosPorEmpresa($idEmpresa, $filtros);
            $totalSinFiltro = $this->movimientosModel->contarMovimientosPorEmpresa($idEmpresa);

            $data = [];
            $estados = $this->movimientosModel->getEstados();

            foreach ($movimientos as $movimiento) {
                $estadoBadge = $this->generarBadgeEstado($movimiento['estado'], $estados);
                $acciones = $this->generarAcciones($movimiento);

                $data[] = [
                    'codigo' => esc($movimiento['codigo']),
                    'tipo_movimiento' => esc($movimiento['tipo_movimiento']),
                    'fecha_movimiento' => date('d/m/Y', strtotime($movimiento['fecha_movimiento'])),
                    'referencia' => esc($movimiento['referencia'] ?? '-'),
                    'total_items' => $movimiento['total_items'],
                    'monto' => number_format($movimiento['monto'] ?? 0, 2),
                    'estado' => $estadoBadge,
                    'acciones' => $acciones
                ];
            }

            return $this->response->setJSON([
                'draw' => (int)$this->request->getPost('draw'),
                'recordsTotal' => $totalSinFiltro,
                'recordsFiltered' => $totalRegistros,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en getData movimientos: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error al cargar los datos']);
        }
    }

    /**
     * Vista para crear nuevo movimiento
     */
    public function create()
    {
        $idEmpresa = $this->session->get('empresa_id');
        
        $data = [
            'title' => 'Crear Movimiento de Inventario',
            'tipos_movimiento' => $this->movimientosModel->getTiposMovimiento(),
            'materiales' => $this->materialesModel->getMaterialesParaSelect($idEmpresa),
            'tipo_preseleccionado' => $this->request->getGet('tipo')
        ];

        return view('movimientos/create', $data);
    }

    /**
     * Procesar creación de movimiento
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Método no permitido');
        }

        try {
            $idEmpresa = $this->session->get('empresa_id');
            $usuarioCrea = $this->session->get('usuario');

            // Validar datos del movimiento
            $validationRules = [
                'tipo_movimiento' => 'required',
                'fecha_movimiento' => 'required|valid_date',
                'referencia' => 'permit_empty|max_length[100]',
                'codigo_origen' => 'permit_empty|max_length[100]',
                'codigo_destino' => 'permit_empty|max_length[100]'
            ];

            if (!$this->validate($validationRules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            // Datos del movimiento
            $datosMovimiento = [
                'tipo_movimiento' => $this->request->getPost('tipo_movimiento'),
                'fecha_movimiento' => $this->request->getPost('fecha_movimiento'),
                'referencia' => $this->request->getPost('referencia'),
                'codigo_origen' => $this->request->getPost('codigo_origen'),
                'codigo_destino' => $this->request->getPost('codigo_destino'),
                'estado' => MovimientosModel::ESTADO_BORRADOR,
                'usuario_crea' => $usuarioCrea,
                'id_empresa' => $idEmpresa
            ];

            // Obtener detalles
            $detalles = $this->procesarDetalles();
            
            if (empty($detalles)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Debe agregar al menos un material al movimiento'
                ]);
            }

            // Crear movimiento con detalles
            $resultado = $this->movimientosModel->crearMovimientoConDetalles($datosMovimiento, $detalles);

            if ($resultado['success']) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $resultado['message'],
                    'id_movimiento' => $resultado['id_movimiento'],
                    'redirect' => base_url('movimientos/show/' . $resultado['id_movimiento'])
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $resultado['message']
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al crear movimiento: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Vista detallada del movimiento
     */
    public function show($id)
    {
        $idEmpresa = $this->session->get('empresa_id');
        
        $movimiento = $this->movimientosModel->getMovimientoConDetalles($id, $idEmpresa);
        
        if (!$movimiento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Movimiento no encontrado');
        }

        $data = [
            'title' => 'Detalle del Movimiento',
            'movimiento' => $movimiento,
            'estados' => $this->movimientosModel->getEstados(),
            'tipos_movimiento' => $this->movimientosModel->getTiposMovimiento()
        ];

        return view('movimientos/show', $data);
    }

    /**
     * Vista para editar movimiento
     */
    public function edit($id)
    {
        $idEmpresa = $this->session->get('empresa_id');
        
        $movimiento = $this->movimientosModel->getMovimientoConDetalles($id, $idEmpresa);
        
        if (!$movimiento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Movimiento no encontrado');
        }

        // Solo se pueden editar movimientos en borrador o pendiente
        if (!in_array($movimiento['estado'], [MovimientosModel::ESTADO_BORRADOR, MovimientosModel::ESTADO_PENDIENTE])) {
            return redirect()->to('movimientos/show/' . $id)->with('error', 'No se puede editar un movimiento que ya ha sido procesado');
        }

        $data = [
            'title' => 'Editar Movimiento',
            'movimiento' => $movimiento,
            'tipos_movimiento' => $this->movimientosModel->getTiposMovimiento(),
            'materiales' => $this->materialesModel->getMaterialesParaSelect($idEmpresa)
        ];

        return view('movimientos/edit', $data);
    }

    /**
     * Procesar actualización de movimiento
     */
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Método no permitido');
        }

        try {
            $idEmpresa = $this->session->get('empresa_id');
            $usuarioEdita = $this->session->get('usuario');

            // Verificar que el movimiento existe y pertenece a la empresa
            $movimiento = $this->movimientosModel->find($id);
            if (!$movimiento || $movimiento['id_empresa'] != $idEmpresa) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Movimiento no encontrado'
                ]);
            }

            // Verificar que se puede editar
            if (!in_array($movimiento['estado'], [MovimientosModel::ESTADO_BORRADOR, MovimientosModel::ESTADO_PENDIENTE])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede editar un movimiento que ya ha sido procesado'
                ]);
            }

            // Validar datos
            $validationRules = [
                'tipo_movimiento' => 'required',
                'fecha_movimiento' => 'required|valid_date',
                'referencia' => 'permit_empty|max_length[100]',
                'codigo_origen' => 'permit_empty|max_length[100]',
                'codigo_destino' => 'permit_empty|max_length[100]'
            ];

            if (!$this->validate($validationRules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            // Datos del movimiento
            $datosMovimiento = [
                'tipo_movimiento' => $this->request->getPost('tipo_movimiento'),
                'fecha_movimiento' => $this->request->getPost('fecha_movimiento'),
                'referencia' => $this->request->getPost('referencia'),
                'codigo_origen' => $this->request->getPost('codigo_origen'),
                'codigo_destino' => $this->request->getPost('codigo_destino'),
                'usuario_edita' => $usuarioEdita,
                'id_empresa' => $idEmpresa
            ];

            // Obtener detalles
            $detalles = $this->procesarDetalles();
            
            if (empty($detalles)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Debe agregar al menos un material al movimiento'
                ]);
            }

            // Actualizar movimiento con detalles
            $resultado = $this->movimientosModel->actualizarMovimientoConDetalles($id, $datosMovimiento, $detalles);

            if ($resultado['success']) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $resultado['message'],
                    'redirect' => base_url('movimientos/show/' . $id)
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $resultado['message']
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar movimiento: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Eliminar movimiento
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Método no permitido');
        }

        try {
            $idEmpresa = $this->session->get('empresa_id');

            // Verificar que el movimiento existe y pertenece a la empresa
            $movimiento = $this->movimientosModel->find($id);
            if (!$movimiento || $movimiento['id_empresa'] != $idEmpresa) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Movimiento no encontrado'
                ]);
            }

            $resultado = $this->movimientosModel->eliminarMovimientoConDetalles($id);

            return $this->response->setJSON($resultado);

        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar movimiento: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Cambiar estado del movimiento
     */
    public function cambiarEstado()
    {
        $estados = $this->movimientosModel->getEstados();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $db = \Config\Database::connect();
       
        try {

            $idMovimiento = $this->request->getPost('id_movimiento');
            $nuevoEstado = $this->request->getPost('nuevo_estado');
            $idEmpresa = $this->session->get('empresa_id');
            $usuarioAprueba = $this->session->get('nombre');

            // Verificar que el movimiento existe y pertenece a la empresa
            $movimiento = $this->movimientosModel->find($idMovimiento);
            if (!$movimiento || $movimiento['id_empresa'] != $idEmpresa) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Movimiento no encontrado'
                ]);
            }

            // Obtener los detalles del movimiento
            $detallesMovimiento = $this->movimientosModel->getMovimientoConDetalles($idMovimiento, $idEmpresa);
            if (empty($detallesMovimiento['detalles'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El movimiento no tiene detalles para procesar'
                ]);
            }

            if ($nuevoEstado == 1) {
                // Crear instancia de los modelos necesarios
                $productosModel = new \App\Models\InvProductosModel();
                $comprasEModel = new \App\Models\InvComprasEModel();

                    
                
                
                $validateMovimiento = $productosModel->getMovimientoByReference($movimiento['codigo']);
                if ($validateMovimiento) {

                   $this->movimientosModel->update($idMovimiento, [
                        'estado' => 2,
                        'usuario_aprueba' => $usuarioAprueba
                    ]);
                        

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'El movimiento ya existe en el ERP'
                    ]);
                }else{
                    // 3. Crear cabecera de orden de compra
                    $resultadoCabecera = $productosModel->crearMovimientoInventario(
                        $movimiento,
                        $usuarioAprueba
                    );
                    $validateMovimiento = $productosModel->getMovimientoByReference($movimiento['codigo']);
                    if ($validateMovimiento) {
                        foreach ($detallesMovimiento['detalles'] as $detalle) {
                            try {
                                $detalleData = $detalle;
                                $detalleData["referencia"]=$movimiento['codigo'];
                                log_message('debug', 'Intentando guardar detalle: ' . print_r($detalleData, true));
                                
                                $resultadoDetalle = $productosModel->agregarDetalleMovimientoInventario(
                                    $detalleData
                                );
                                
                                
                                
                                log_message('debug', 'Detalle guardado exitosamente: ' . $detalle['codigo_vinculacion']);
                                
                            } catch (\Exception $e) {
                                log_message('error', 'Excepción al guardar detalle: ' . $e->getMessage());
                                log_message('error', 'Traza del error: ' . $e->getTraceAsString());
                                throw new \Exception('Error procesando detalle ' . ($detalle['codigo_vinculacion'] ?? '') . ': ' . $e->getMessage());
                            }
                        }
        
                        // 5. Actualizar el movimiento con el número de orden generado
                        $this->movimientosModel->update($idMovimiento, [
                            'numero_orden_compra' => $numeroOrden,
                            'fecha_actualizacion' => date('Y-m-d H:i:s')
                        ]);
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Estado actualizado exitosamente' . ($nuevoEstado == 1 ? ' y orden de compra generada' : ''),
                            'nuevo_estado_texto' => $estados[$nuevoEstado],
                            'numero_orden' => $movimiento["codigo"]
                        ]);
                    }
                }
                

                

                
                
            }else{
                $this->movimientosModel->update($idMovimiento, [
                    'estado' => $nuevoEstado,
                    'fecha_actualizacion' => date('Y-m-d H:i:s')
                ]);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estado actualizado exitosamente' . ($nuevoEstado == 1 ? ' y orden de compra generada' : ''),
                    'nuevo_estado_texto' => $estados[$nuevoEstado],
                    'numero_orden' => $movimiento["codigo"]
                ]);
            }

           

           

          
            

        } catch (\Exception $e) {
          
            log_message('error', 'Error en cambiarEstado: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener estadísticas para el dashboard
     */
    public function getEstadisticas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        try {
            $idEmpresa = $this->session->get('empresa_id');
            $fechaDesde = $this->request->getPost('fecha_desde');
            $fechaHasta = $this->request->getPost('fecha_hasta');

            $estadisticas = $this->movimientosModel->getEstadisticasMovimientos($idEmpresa, $fechaDesde, $fechaHasta);

            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ]);
        }
    }

    /**
     * Buscar materiales para autocompletado
     */
    public function buscarMateriales()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $idEmpresa = $this->session->get('empresa_id');
        $termino = $this->request->getGet('q');

        $materiales = $this->materialesModel->buscarPorNombre($termino, $idEmpresa, 20);

        $resultado = [];
        foreach ($materiales as $material) {
            $resultado[] = [
                'id' => $material['id'],
                'text' => $material['codigo_consecutivo'] . ' - ' . $material['nombre'],
                'codigo' => $material['codigo_consecutivo'],
                'nombre' => $material['nombre'],
                'unidad_medida' => $material['unidad_medida'],
                'costo_unitario' => $material['costo_unitario']
            ];
        }

        return $this->response->setJSON(['results' => $resultado]);
    }

    /**
     * Buscar órdenes de trabajo en proceso para referencia
     */
    public function buscarOrdenesEnProceso()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        try {
            $idEmpresa = $this->session->get('empresa_id');
            $termino = $this->request->getGet('q');
            $estado = $this->request->getGet('estado');
            $esModal = $this->request->getGet('modal') === 'true';
            $busquedaExacta = $this->request->getGet('exact') === 'true';

            // Cargar el modelo de solicitudes
            $solicitudModel = new \App\Models\SolicitudModel();
            
            // Preparar filtros
            $filtros = [];
            if (!empty($termino)) {
                $filtros['search'] = $termino;
            }
            if (!empty($estado)) {
                $filtros['estado'] = $estado;
            } else {
                // Por defecto, solo solicitudes en proceso
                $filtros['estado'] = 'EN_PROCESO';
            }
            
            $solicitudes = $solicitudModel->getSolicitudesEnProceso($idEmpresa, $filtros);
            
            // Si es búsqueda exacta, filtrar por código exacto
            if ($busquedaExacta && !empty($termino)) {
                $solicitudes = array_filter($solicitudes, function($solicitud) use ($termino) {
                    return strtoupper($solicitud['codigo_consecutivo']) === strtoupper($termino);
                });
            }
            
            $resultado = [];
            foreach ($solicitudes as $solicitud) {
                $vehiculo = trim(($solicitud['placa'] ?? '') . ' ' . ($solicitud['marca'] ?? '') . ' ' . ($solicitud['modelo'] ?? ''));
                
                $resultado[] = [
                    'id' => $solicitud['id'],
                    'text' => $solicitud['codigo_consecutivo'] . ' - ' . $solicitud['descripcion'],
                    'codigo' => $solicitud['codigo_consecutivo'],
                    'descripcion' => $solicitud['descripcion'],
                    'vehiculo' => $vehiculo ?: 'Sin vehículo',
                    'estado' => $solicitud['estado'] ?? 'EN_PROCESO',
                    'fecha' => $solicitud['fecha_solicitud'] ?? ''
                ];
            }

            return $this->response->setJSON(['results' => $resultado,"filtro"=>$filtros]);

        } catch (\Exception $e) {
            log_message('error', 'Error al buscar órdenes en proceso: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error al buscar órdenes de trabajo']);
        }
    }

    /**
     * Buscar material por código (consecutivo o vinculación)
     */
    public function buscarMaterialPorCodigo()
    {
        // Permitir tanto AJAX como requests normales para debug
        try {
            $idEmpresa = $this->session->get('empresa_id');
            $codigo = trim($this->request->getPost('codigo'));

            // Log para debug
            log_message('info', 'Buscando material - Empresa: ' . $idEmpresa . ', Código: ' . $codigo);

            if (empty($codigo)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Código requerido'
                ]);
            }

            if (empty($idEmpresa)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sesión de empresa no válida'
                ]);
            }

            // Buscar por código consecutivo o código de vinculación
            $material = $this->materialesModel
                ->where('id_empresa', $idEmpresa)
                ->groupStart()
                    ->where('codigo_consecutivo', $codigo)
                    ->orWhere('codigo_vinculacion', $codigo)
                ->groupEnd()
                ->first();

            // Log de la consulta SQL para debug
            log_message('info', 'SQL Query: ' . $this->materialesModel->getLastQuery());

            if (!$material) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Material no encontrado con el código: ' . $codigo
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'material' => [
                    'id' => $material['id'],
                    'codigo_consecutivo' => $material['codigo_consecutivo'],
                    'codigo_vinculacion' => $material['codigo_vinculacion'] ?? '',
                    'nombre' => $material['nombre'],
                    'unidad_medida' => $material['unidad_medida'],
                    'costo_unitario' => $material['costo_unitario']
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al buscar material por código: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Debug method to test material search
     */
    public function debugMaterialSearch()
    {
        try {
            // Obtener todos los datos de sesión para debug
            $sessionData = $this->session->get();
            
            $idEmpresa = $this->session->get('empresa_id');
            $empresaId = $this->session->get('empresa_id');
            
            $response = [
                'all_session_data' => $sessionData,
                'id_empresa_key' => $idEmpresa,
                'empresa_id_key' => $empresaId,
                'session_keys' => array_keys($sessionData)
            ];
            
            // Si tenemos empresa_id, usémoslo para obtener materiales
            $empresaParaBuscar = $empresaId ?? $idEmpresa;
            
            if ($empresaParaBuscar) {
                $materiales = $this->materialesModel
                    ->where('id_empresa', $empresaParaBuscar)
                    ->limit(5)
                    ->findAll();
                
                $response['empresa_usada'] = $empresaParaBuscar;
                $response['total_materials'] = $this->materialesModel->where('id_empresa', $empresaParaBuscar)->countAllResults();
                $response['sample_materials'] = $materiales;
                $response['last_query'] = $this->materialesModel->getLastQuery();
            }
            
            return $this->response->setJSON($response);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Procesar detalles del formulario
     */
    private function procesarDetalles()
    {
        $materiales = $this->request->getPost('materiales') ?? [];
        $cantidades = $this->request->getPost('cantidades') ?? [];
        $precios = $this->request->getPost('precios') ?? [];
        $impuestos = $this->request->getPost('impuestos') ?? [];

        $detalles = [];
        
        for ($i = 0; $i < count($materiales); $i++) {
            if (!empty($materiales[$i]) && !empty($cantidades[$i]) && isset($precios[$i])) {
                $detalles[] = [
                    'id_material' => (int)$materiales[$i],
                    'cantidad' => (float)$cantidades[$i],
                    'precio' => (float)$precios[$i],
                    'impuesto' => (float)($impuestos[$i] ?? 0)
                ];
            }
        }

        return $detalles;
    }

    /**
     * Generar badge de estado
     */
    private function generarBadgeEstado($estado, $estados)
    {
        $clases = [
            MovimientosModel::ESTADO_BORRADOR => 'bg-secondary',
            MovimientosModel::ESTADO_PENDIENTE => 'bg-warning',
            MovimientosModel::ESTADO_APROBADO => 'bg-info',
            MovimientosModel::ESTADO_PROCESADO => 'bg-success',
            MovimientosModel::ESTADO_CANCELADO => 'bg-danger'
        ];

        $clase = $clases[$estado] ?? 'bg-secondary';
        $texto = $estados[$estado] ?? 'Desconocido';

        return '<span class="badge ' . $clase . '">' . $texto . '</span>';
    }

    /**
     * Generar botones de acción
     */
    private function generarAcciones($movimiento)
    {
        $acciones = '<div class="btn-group" role="group">';
        
        // Ver
        $acciones .= '<a href="' . base_url('movimientos/show/' . $movimiento['id']) . '" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                      </a>';
        
        // Editar (solo si está en borrador o pendiente)
        if (in_array($movimiento['estado'], [MovimientosModel::ESTADO_BORRADOR, MovimientosModel::ESTADO_PENDIENTE])) {
            $acciones .= '<a href="' . base_url('movimientos/edit/' . $movimiento['id']) . '" class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                          </a>';
        }
        
        // Eliminar (solo si está en borrador o pendiente)
        if (in_array($movimiento['estado'], [MovimientosModel::ESTADO_BORRADOR, MovimientosModel::ESTADO_PENDIENTE])) {
            $acciones .= '<button class="btn btn-sm btn-outline-danger eliminar-movimiento" 
                            data-id="' . $movimiento['id'] . '" 
                            data-codigo="' . esc($movimiento['codigo']) . '" 
                            title="Eliminar">
                            <i class="fas fa-trash"></i>
                          </button>';
        }
        
        $acciones .= '</div>';
        
        return $acciones;
    }
}
