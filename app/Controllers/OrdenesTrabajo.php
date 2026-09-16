<?php

namespace App\Controllers;

use App\Models\SolicitudModel;
use App\Models\VehiculoModel;
use App\Models\UsuarioModel;
use App\Models\TipoProblemaModel;
use CodeIgniter\Controller;
use App\Models\InvProductosModel;
use App\Models\CatalogoModel;

class OrdenesTrabajo extends BaseController
{
    protected $solicitudModel;
    protected $vehiculoModel;
    protected $usuarioModel;
    protected $tipoProblemaModel;
    protected $session;
    protected $catalogoModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->solicitudModel = new SolicitudModel();
        $this->vehiculoModel = new VehiculoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->tipoProblemaModel = new TipoProblemaModel();
        $this->catalogoModel = new CatalogoModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Formulario de clasificación de orden
     */
    public function clasificacion($id)
    {
        $empresaId = $this->session->get('empresa_id');

        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        $orden = $this->solicitudModel->getSolicitudConRelaciones($id, $empresaId);

        if (!$orden) {
            return redirect()->to('/ordenes-trabajo')->with('error', 'Orden no encontrada.');
        }

        $tiposProblemaCatalogo = $this->catalogoModel->getCatalogosPorCodigo("CAT-0010");
        $tiposProblema = [];
        foreach ($tiposProblemaCatalogo as $tipo) {
            $categoria = $tipo['referencia'] ?? 'General';
            if (!isset($tiposProblema[$categoria])) {
                $tiposProblema[$categoria] = [];
            }
            $tiposProblema[$categoria][] = $tipo;
        }

        $usuarios = $this->usuarioModel->where('estado', 'ACTIVO')->findAll();

        $data = [
            'title' => 'Clasificación de Orden',
            'orden' => $orden,
            'tiposProblema' => $tiposProblema,
            'usuarios' => $usuarios
        ];

        return view('ordenes_trabajo/clasificacion', $data);
    }

    /**
     * Actualizar clasificación de orden
     */
    public function actualizarClasificacion($id)
    {
        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');

        if (!$empresaId || !$usuarioId) {
            return redirect()->to('/auth/login');
        }

        $orden = $this->solicitudModel->getSolicitudConRelaciones($id, $empresaId);
        if (!$orden) {
            return redirect()->to('/ordenes-trabajo')->with('error', 'Orden no encontrada.');
        }

        $rules = [
            'id_tipo_problema' => 'required|is_natural_no_zero',
            'prioridad' => 'required|in_list[1,2,3,4]',
            'id_asignado' => 'permit_empty|is_natural_no_zero'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $datosActualizacion = [
            'id_tipo_problema' => $this->request->getPost('id_tipo_problema'),
            'prioridad' => $this->request->getPost('prioridad'),
            'id_asignado' => $this->request->getPost('id_asignado') ?: null,
            'usuario_actualiza' => $usuarioId,
        ];

        if (!empty($datosActualizacion['id_asignado'])) {
            $datosActualizacion['fecha_asignacion'] = date('Y-m-d H:i:s');
        }

        try {
            if (!$this->solicitudModel->update($id, $datosActualizacion)) {
                $modelErrors = $this->solicitudModel->errors();
                log_message('error', 'Actualizar clasificación - errores de validación: ' . json_encode($modelErrors));

                $mensajeError = 'Error al actualizar la orden.';
                if (!empty($modelErrors)) {
                    $mensajeError .= ' ' . implode(' ', $modelErrors);
                }

                return redirect()->back()->withInput()->with('errors', $modelErrors)->with('error', $mensajeError);
            }

            return redirect()->to('/ordenes-trabajo')->with('success', 'Orden actualizada correctamente.');
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar clasificación: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al actualizar la orden: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard principal de órdenes de trabajo
     */
    public function index()
    {
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        // Obtener conteo por estado para el dashboard
        $conteoEstados = $this->solicitudModel->getConteoSolicitudesPorEstado($empresaId);
        
        // Obtener solicitudes recientes con límite mayor para cards
        $filtros = [];
        if (isset($_GET['estado']) && $_GET['estado'] !== 'todas') {
            $filtros['estado'] = $_GET['estado'];
        }

        $solicitudesRecientes = $this->solicitudModel->getSolicitudesPorEmpresa($empresaId, array_merge($filtros, [
            'limit' => 20, // Más elementos para diseño con cards
            'order_by' => 'fecha_solicitud',
            'order_direction' => 'DESC'
        ]))->get()->getResultArray();

        $data = [
            'title' => 'Órdenes de Trabajo - Dashboard',
            'conteoEstados' => $conteoEstados,
            'solicitudesRecientes' => $solicitudesRecientes,
            'ordenes' => $solicitudesRecientes,
            'filtros_aplicados' => $filtros
        ];

        return view('ordenes_trabajo/dashboard', $data);
    }

    /**
     * Solicitudes Pendientes - Administradores ven todas, usuarios ven solo las asignadas
     */
    public function pendientes()
    {
        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');
        $rolId = $this->session->get('rol_id');
        
        if (!$empresaId || !$usuarioId) {
            return redirect()->to('/auth/login');
        }

        // Filtros para la consulta
        $filtros = ['estado' => 'PENDIENTE'];
        
        // Si no es administrador, solo ver las asignadas al usuario
        if ($rolId != 1) { // Asumiendo que rol_id 1 es ADMINISTRADOR
            $filtros['id_asignado'] = $usuarioId;
        }

        // Obtener solicitudes pendientes
        $solicitudes = $this->solicitudModel->getSolicitudesPorEmpresa($empresaId, $filtros)
            ->orderBy('s.fecha_solicitud', 'DESC')
            ->get()
            ->getResultArray();

        // Obtener conteo por estado para el sidebar
        $conteoEstados = $this->solicitudModel->getConteoSolicitudesPorEstado($empresaId);

        $data = [
            'title' => 'Solicitudes Pendientes',
            'solicitudes' => $solicitudes,
            'conteoEstados' => $conteoEstados,
            'esAdministrador' => ($rolId == 1),
            'usuarioActual' => $usuarioId
        ];

        return view('ordenes_trabajo/pendientes', $data);
    }

    /**
     * Mis Órdenes - Órdenes asignadas al usuario actual
     */
    public function misOrdenes()
    {
        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');
        
        if (!$empresaId || !$usuarioId) {
            return redirect()->to('/auth/login');
        }

        $filtros = [
            'id_asignado' => $usuarioId
        ];

        // Aplicar filtros adicionales si vienen por GET
        if ($this->request->getGet('estado')) {
            $filtros['estado'] = $this->request->getGet('estado');
        }
        if ($this->request->getGet('prioridad')) {
            $filtros['prioridad'] = $this->request->getGet('prioridad');
        }

        $misOrdenes = $this->solicitudModel->getSolicitudesPorEmpresa($empresaId, $filtros)
                                          ->get()
                                          ->getResultArray();

        $data = [
            'title' => 'Mis Órdenes de Trabajo',
            'ordenes' => $misOrdenes,
            'filtros' => $filtros
        ];

        return view('ordenes_trabajo/mis_ordenes', $data);
    }

    /**
     * Consulta general de órdenes
     */
    public function consulta()
    {
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        $filtros = [];
        
        // Aplicar filtros de búsqueda
        if ($this->request->getGet('estado')) {
            $filtros['estado'] = $this->request->getGet('estado');
        }
        if ($this->request->getGet('id_vehiculo')) {
            $filtros['id_vehiculo'] = $this->request->getGet('id_vehiculo');
        }
        if ($this->request->getGet('fecha_desde')) {
            $filtros['fecha_desde'] = $this->request->getGet('fecha_desde');
        }
        if ($this->request->getGet('fecha_hasta')) {
            $filtros['fecha_hasta'] = $this->request->getGet('fecha_hasta');
        }
        if ($this->request->getGet('prioridad')) {
            $filtros['prioridad'] = $this->request->getGet('prioridad');
        }

        $ordenes = $this->solicitudModel->getSolicitudesPorEmpresa($empresaId, $filtros)
                                       ->get()
                                       ->getResultArray();

        // Obtener datos para los filtros
        $vehiculos = $this->vehiculoModel->where('id_empresa', $empresaId)->findAll();

        $data = [
            'title' => 'Consulta de Órdenes de Trabajo',
            'ordenes' => $ordenes,
            'vehiculos' => $vehiculos,
            'filtros' => $filtros
        ];

        return view('ordenes_trabajo/consulta', $data);
    }

    /**
     * Bandeja de Pendientes
     */
    public function bandejaPendientes()
    {
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        $ordenesPendientes = $this->solicitudModel->getSolicitudesPendientes($empresaId);

        $data = [
            'title' => 'Bandeja de Pendientes',
            'ordenes' => $ordenesPendientes
        ];

        return view('ordenes_trabajo/bandeja_pendientes', $data);
    }

    /**
     * Bandeja de Aprobadas
     */
    public function bandejaAprobadas()
    {
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        $ordenesAprobadas = $this->solicitudModel->getSolicitudesAprobadas($empresaId);

        $data = [
            'title' => 'Bandeja de Aprobadas',
            'ordenes' => $ordenesAprobadas
        ];

        return view('ordenes_trabajo/bandeja_aprobadas', $data);
    }

    /**
     * Bandeja de En Proceso
     */
    public function bandejaEnProceso()
    {
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        $ordenesEnProceso = $this->solicitudModel->getSolicitudesEnProceso($empresaId);

        $data = [
            'title' => 'Bandeja En Proceso',
            'ordenes' => $ordenesEnProceso
        ];

        return view('ordenes_trabajo/bandeja_en_proceso', $data);
    }

    /**
     * Bandeja de Finalizadas
     */
    public function bandejaFinalizadas()
    {
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        $ordenesFinalizadas = $this->solicitudModel->getSolicitudesFinalizadas($empresaId);

        $data = [
            'title' => 'Bandeja de Finalizadas',
            'ordenes' => $ordenesFinalizadas
        ];

        return view('ordenes_trabajo/bandeja_finalizadas', $data);
    }

    /**
     * Crear nueva orden de trabajo
     */
    public function create()
    {
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        // Obtener datos para el formulario simplificado
        $vehiculos = $this->vehiculoModel->where('id_empresa', $empresaId)->findAll();
        $tiposProblemaCatalogo = $this->catalogoModel->getCatalogosPorCodigo("CAT-0010");

        $tiposProblema = [];
        foreach ($tiposProblemaCatalogo as $tipo) {
            $categoria = $tipo['referencia'] ?? 'General';
            if (!isset($tiposProblema[$categoria])) {
                $tiposProblema[$categoria] = [];
            }
            $tiposProblema[$categoria][] = $tipo;
        }

        $data = [
            'title' => 'Nueva Orden de Trabajo',
            'vehiculos' => $vehiculos,
            'tiposProblema' => $tiposProblema
        ];

        return view('ordenes_trabajo/create', $data);
    }

    /**
     * Guardar nueva orden de trabajo
     */
    public function store()
    {
        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');
        
        // Debug: Log de datos recibidos
        log_message('debug', 'Store OT - POST data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'Store OT - EmpresaId: ' . ($empresaId ?? 'NULL') . ', UsuarioId: ' . ($usuarioId ?? 'NULL'));
        
        // Validar que tengamos los datos de sesión necesarios
        if (!$empresaId || !$usuarioId) {
            log_message('error', 'Store OT - Faltan datos de sesión');
            return redirect()->back()->withInput()->with('error', 'Error: No se encontraron datos de sesión válidos. Por favor, inicie sesión nuevamente.');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'id_vehiculo' => 'required|is_natural_no_zero',
            'id_tipo_problema' => 'required|is_natural_no_zero',
            'solicitante' => 'required|string|min_length[3]|max_length[150]',
            'descripcion' => 'required|min_length[10]',
            'foto' => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,5120]'
        ];

        if (!$this->validate($rules)) {
            $errors = $validation->getErrors();
            log_message('error', 'Store OT - Errores de validación: ' . json_encode($errors));
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $solicitanteNombre = trim($this->request->getPost('solicitante'));
        $tipoProblemaId = (int) $this->request->getPost('id_tipo_problema');
        $tipoProblema = $this->catalogoModel->find($tipoProblemaId);

        $descripcion = $this->request->getPost('descripcion');
        $descripcionDetallada = $descripcion;
        $descripcionDetallada .= "\n\nSolicitante: {$solicitanteNombre}";
        if ($tipoProblema) {
            $descripcionDetallada .= "\nTipo de problema: {$tipoProblema['nombre']}";
        }

        $foto = $this->request->getFile('foto');
        $fotoPath = null;

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $uploadDir = ROOTPATH . 'public/uploads/ordenes/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $newName = $foto->getRandomName();
            $foto->move($uploadDir, $newName);
            $fotoPath = 'uploads/ordenes/' . $newName;
            $descripcionDetallada .= "\nFoto adjunta: {$fotoPath}";
        }

        $data = [
            'id_empresa' => $empresaId,
            'id_vehiculo' => $this->request->getPost('id_vehiculo'),
            'id_solicitante' => $usuarioId,
            'id_tipo_problema' => $tipoProblemaId,
            'descripcion' => $descripcionDetallada,
            'prioridad' => '1',
            'estado' => 'PENDIENTE',
            'fecha_solicitud' => date('Y-m-d H:i:s'),
            'usuario_crea' => $usuarioId,
            'url_foto' => $fotoPath,
            'solicitante' => $solicitanteNombre
        ];

        try {
            // Debug: Log de datos a insertar
            log_message('debug', 'Store OT - Datos a insertar: ' . json_encode($data));
            
            $result = $this->solicitudModel->save($data);
            
            if ($result) {
                log_message('info', 'Store OT - Orden creada exitosamente con ID: ' . $this->solicitudModel->getInsertID());
                return redirect()->to('/ordenes-trabajo')->with('success', 'Orden de trabajo creada exitosamente');
            } else {
                $errors = $this->solicitudModel->errors();
                log_message('error', 'Store OT - Error del modelo: ' . json_encode($errors));
                
                if (empty($errors)) {
                    $dbError = $this->solicitudModel->db->error();
                    log_message('error', 'Store OT - Error de BD: ' . json_encode($dbError));
                    return redirect()->back()->withInput()->with('error', 'Error de base de datos: ' . $dbError['message']);
                }
                
                return redirect()->back()->withInput()->with('error', 'Error al crear la orden: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Store OT - Excepción: ' . $e->getMessage() . ' - Línea: ' . $e->getLine());
            return redirect()->back()->withInput()->with('error', 'Error inesperado: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalle de orden de trabajo
     */
    public function show($id)
    {


        $productosModel = new InvProductosModel();
       
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        $orden = $this->solicitudModel->getSolicitudesPorEmpresa($empresaId, ['id' => $id])
                                     ->get()
                                     ->getRowArray();

        if (!$orden) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Orden de trabajo no encontrada');
        }

        $data = [
            'title' => 'Detalle Orden de Trabajo #' . $orden['id'],
            'orden' => $orden,
    
        ];

        return view('ordenes_trabajo/show', $data);
    }

    /**
     * Editar orden de trabajo
     */
    public function edit($id)
    {
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        $orden = $this->solicitudModel->where('id', $id)
                                     ->where('id_empresa', $empresaId)
                                     ->first();

        if (!$orden) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Orden de trabajo no encontrada');
        }

        // Obtener datos para el formulario
        $vehiculos = $this->vehiculoModel->where('id_empresa', $empresaId)->findAll();
        $tiposProblema = $this->catalogoModel->getHijosActivosPorCodigo('CAT-0016');
        $usuarios = $this->usuarioModel->where('id_empresa', $empresaId)->findAll();

        $data = [
            'title' => 'Editar Orden de Trabajo #' . $orden['id'],
            'orden' => $orden,
            'vehiculos' => $vehiculos,
            'tiposProblema' => $tiposProblema,
            'usuarios' => $usuarios
        ];

        return view('ordenes_trabajo/edit', $data);
    }

    /**
     * Actualizar orden de trabajo
     */
    public function update($id)
    {
        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');
        
        if (!$empresaId || !$usuarioId) {
            return redirect()->to('/auth/login');
        }

        $orden = $this->solicitudModel->where('id', $id)
                                     ->where('id_empresa', $empresaId)
                                     ->first();

        if (!$orden) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Orden de trabajo no encontrada');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'id_vehiculo' => 'required|is_natural_no_zero',
            'id_tipo_problema' => 'required|is_natural_no_zero',
            'descripcion' => 'required|min_length[10]',
            'prioridad' => 'required|in_list[BAJA,MEDIA,ALTA,CRITICA]',
            'estado' => 'required|in_list[PLANIFICADA,PENDIENTE,APROBADA,EN_PROCESO,FINALIZADA]',
            'fecha_planificacion' => 'permit_empty|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'id_vehiculo' => $this->request->getPost('id_vehiculo'),
            'id_tipo_problema' => $this->request->getPost('id_tipo_problema'),
            'descripcion' => $this->request->getPost('descripcion'),
            'prioridad' => $this->request->getPost('prioridad'),
            'estado' => $this->request->getPost('estado'),
            'fecha_planificacion' => $this->request->getPost('fecha_planificacion') ?: null,
            'id_asignado' => $this->request->getPost('id_asignado') ?: null,
            'usuario_actualiza' => $usuarioId
        ];

        // Si se está finalizando la orden
        if ($this->request->getPost('estado') === 'FINALIZADA' && $orden['estado'] !== 'FINALIZADA') {
            $data['fecha_cierre'] = date('Y-m-d H:i:s');
        }

        if ($this->solicitudModel->update($id, $data)) {
            return redirect()->to('/ordenes-trabajo/show/' . $id)->with('success', 'Orden de trabajo actualizada exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar la orden de trabajo');
        }
    }

    /**
     * Cambiar estado de orden de trabajo (AJAX)
     */
    public function cambiarEstado()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');
        
        $id = $this->request->getPost('id');
        $nuevoEstado = $this->request->getPost('estado');

        $orden = $this->solicitudModel->where('id', $id)
                                     ->where('id_empresa', $empresaId)
                                     ->first();

        if (!$orden) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Orden de trabajo no encontrada'
            ]);
        }

        $data = [
            'estado' => $nuevoEstado,
            'usuario_actualiza' => $usuarioId
        ];

        // Si se está finalizando
        if ($nuevoEstado === 'FINALIZADA') {
            $data['fecha_cierre'] = date('Y-m-d H:i:s');
        }

        if ($this->solicitudModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado actualizado exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar el estado'
            ]);
        }
    }

    /**
     * Asignar orden de trabajo (AJAX)
     */
    public function asignarOrden()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');
        
        $id = $this->request->getPost('id');
        $idAsignado = $this->request->getPost('id_asignado');

        $orden = $this->solicitudModel->where('id', $id)
                                     ->where('id_empresa', $empresaId)
                                     ->first();

        if (!$orden) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Orden de trabajo no encontrada'
            ]);
        }

        $data = [
            'id_asignado' => $idAsignado,
            'fecha_asignacion' => date('Y-m-d H:i:s'),
            'usuario_actualiza' => $usuarioId
        ];

        if ($this->solicitudModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Orden asignada exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al asignar la orden'
            ]);
        }
    }

    /**
     * Mostrar vista para realizar orden de trabajo
     */
    public function realizar($id)
    {
        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');
        
        $productosModel = new InvProductosModel();
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        // Obtener la orden con detalles completos
        $orden = $this->solicitudModel->getSolicitudConRelaciones($id);

        $movimientos = [];
        
        if (!$orden || $orden['id_empresa'] != $empresaId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Orden no encontrada');
        }

        // Verificar que la orden esté en un estado que permita realizar el trabajo
        $estadoOrden = strtoupper($orden['estado'] ?? '');
        if (!in_array($estadoOrden, ['EN_PROCESO', 'ASIGNADA', 'APROBADA'])) {
            return redirect()->to('ordenes-trabajo/show/' . $id)
                           ->with('error', 'La orden debe estar ASIGNADA, APROBADA o EN_PROCESO para poder realizarla');
        }

        // Auto-transicionar a EN_PROCESO si está ASIGNADA o APROBADA
        if (in_array($estadoOrden, ['ASIGNADA', 'APROBADA'])) {
            $this->solicitudModel->update($id, [
                'estado' => 'EN_PROCESO',
                'usuario_actualiza' => $usuarioId
            ]);
            $orden['estado'] = 'EN_PROCESO';
        }

        // Últimos 3 registros de combustible del vehículo
        $registroCombustibleModel = new \App\Models\RegistroCombustibleModel();
        $ultimosCombustible = $registroCombustibleModel->getRegistrosConRelaciones([
            'vehiculo' => $orden['id_vehiculo'],
        ]);
        $ultimosCombustible = array_slice($ultimosCombustible, 0, 3);

        // Catálogo de materiales para el selector
        $materialesModel = new \App\Models\MaterialesModel();
        $materiales = $materialesModel->where('id_empresa', $empresaId)->findAll();

        $data = [
            'title' => 'Realizar Orden de Trabajo #' . $id,
            'orden' => $orden,
            'movimientos' => $movimientos,
            'ultimos_combustible' => $ultimosCombustible,
            'materiales' => $materiales,
        ];

        return view('ordenes_trabajo/realizar', $data);
    }

    /**
     * Guardar trabajo realizado
     */
    public function guardarTrabajo($id = null)
    {
        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');

        if (!$id) {
            return redirect()->to('ordenes-trabajo')->with('error', 'ID de orden no válido');
        }

        // Verificar que la orden existe y está en estado EN_PROCESO
        $orden = $this->solicitudModel->getSolicitudConRelaciones($id, $empresaId);
        if (!$orden) {
            return redirect()->to('ordenes-trabajo')->with('error', 'Orden no encontrada');
        }

        $estadoOrden = strtoupper($orden['estado_orden'] ?? $orden['estado'] ?? '');
        if (!in_array($estadoOrden, ['EN_PROCESO', 'ASIGNADA', 'APROBADA'])) {
            return redirect()->to('ordenes-trabajo/show/' . $id)
                           ->with('error', 'Solo se pueden guardar trabajos en órdenes ASIGNADAS, APROBADAS o EN PROCESO');
        }

       

        // Validar datos del formulario
        $validation = \Config\Services::validation();
        $validation->setRules([
            'trabajo_realizado' => 'required|min_length[10]',
            'fecha_inicio' => 'required|valid_date',
            'id_tecnico' => 'required|integer',
            'observaciones' => 'permit_empty|string',
            'estado_vehiculo_post' => 'permit_empty|in_list[OPERATIVO,REQUIERE_REVISION,FUERA_DE_SERVICIO,PENDIENTE_REPUESTOS]',
            'kilometraje_actual' => 'permit_empty|numeric',
            'horas_trabajo' => 'permit_empty|numeric',
            'kilometraje_ingreso' => 'permit_empty|numeric',
            'nivel_combustible' => 'permit_empty|max_length[20]',
            'resultado_final' => 'permit_empty|max_length[50]',
            'observaciones_finales' => 'permit_empty|string'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $validation->getErrors());
        }

        try {
            // Cargar modelos necesarios
            $registroTrabajoModel = new \App\Models\RegistroTrabajoModel();
            $materialesTrabajoModel = new \App\Models\MaterialesTrabajoModel();

            // Iniciar transacción
            $this->db->transStart();

            // Checkboxes de diagnóstico y trabajos (guardar como JSON)
            $diagnostico = $this->request->getPost('diagnostico');
            $trabajosCheck = $this->request->getPost('trabajos_check');
            $diagnosticoOtro = trim((string)$this->request->getPost('diagnostico_otro'));
            $trabajosOtro = trim((string)$this->request->getPost('trabajos_otro'));

            if (is_array($diagnostico) && $diagnosticoOtro !== '') {
                $diagnostico[] = 'Otro: ' . $diagnosticoOtro;
            } elseif ($diagnosticoOtro !== '') {
                $diagnostico = ['Otro: ' . $diagnosticoOtro];
            }
            if (is_array($trabajosCheck) && $trabajosOtro !== '') {
                $trabajosCheck[] = 'Otro: ' . $trabajosOtro;
            } elseif ($trabajosOtro !== '') {
                $trabajosCheck = ['Otro: ' . $trabajosOtro];
            }

            // Preparar datos para el registro de trabajo
            $registroData = [
                'id_solicitud' => $id,
                'id_vehiculo' => $orden['id_vehiculo'],
                'id_tecnico' => $this->request->getPost('id_tecnico'),
                'fecha_inicio' => $this->request->getPost('fecha_inicio'),
                'trabajo_realizado' => $this->request->getPost('trabajo_realizado'),
                'kilometraje_actual' => $this->request->getPost('kilometraje_actual'),
                'horas_trabajo' => $this->request->getPost('horas_trabajo'),
                'observaciones' => $this->request->getPost('observaciones'),
                'estado_vehiculo_post' => $this->request->getPost('estado_vehiculo_post'),
                'trabajo_completado' => $this->request->getPost('trabajo_completado') ? 1 : 0,
                'diagnostico_sistemas' => !empty($diagnostico) ? json_encode($diagnostico, JSON_UNESCAPED_UNICODE) : null,
                'trabajos_checklist' => !empty($trabajosCheck) ? json_encode($trabajosCheck, JSON_UNESCAPED_UNICODE) : null,
                'nivel_combustible' => $this->request->getPost('nivel_combustible') ?: null,
                'kilometraje_ingreso' => $this->request->getPost('kilometraje_ingreso') ?: null,
                'resultado_final' => $this->request->getPost('resultado_final') ?: null,
                'observaciones_finales' => $this->request->getPost('observaciones_finales') ?: null,
                'usuario_crea' => $usuarioId
            ];

            // Si se marca como completado, agregar fecha de fin
            if ($this->request->getPost('trabajo_completado')) {
                $registroData['fecha_fin'] = $this->request->getPost('fecha_fin') ?: date('Y-m-d H:i:s');
            }

            // Insertar registro de trabajo
            $idRegistroTrabajo = $registroTrabajoModel->insert($registroData);

            if (!$idRegistroTrabajo) {
                throw new \Exception('Error al crear el registro de trabajo');
            }

            // Procesar materiales si se enviaron
            $materiales = $this->request->getPost('materiales');
            if (!empty($materiales) && is_array($materiales)) {
                $materialesData = [];
                foreach ($materiales as $material) {
                    if (!empty($material['id_material']) && !empty($material['cantidad']) && isset($material['costo_unitario'])) {
                        $materialesData[] = [
                            'id_material' => $material['id_material'],
                            'cantidad' => $material['cantidad'],
                            'costo_unitario' => $material['costo_unitario']
                        ];
                    }
                }

                if (!empty($materialesData)) {
                    $materialesTrabajoModel->insertarMateriales($idRegistroTrabajo, $materialesData);
                }
            }

            // Si se completó el trabajo, actualizar estado de la orden y vehículo
            if ($this->request->getPost('trabajo_completado')) {
                // Actualizar estado de la orden a FINALIZADA
                $this->solicitudModel->update($id, [
                    'estado' => 'FINALIZADA',
                    'fecha_cierre' => $registroData['fecha_fin'],
                    'usuario_actualiza' => $usuarioId
                ]);

                // Actualizar kilometraje del vehículo si se proporcionó
                if ($this->request->getPost('kilometraje_actual')) {
                    $vehiculoUpdateData = [
                        'kilometraje' => $this->request->getPost('kilometraje_actual'),
                        'usuario_actualiza' => $usuarioId
                    ];

                    // Si se especificó un nuevo estado para el vehículo, actualizarlo
                    if ($this->request->getPost('estado_vehiculo_post')) {
                        $vehiculoUpdateData['estado'] = $this->request->getPost('estado_vehiculo_post');
                    }

                    $this->vehiculoModel->update($orden['id_vehiculo'], $vehiculoUpdateData);
                }
            }

            // Completar transacción
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Error en la transacción de base de datos');
            }

            $mensaje = $this->request->getPost('trabajo_completado') 
                     ? 'Trabajo completado y orden finalizada exitosamente'
                     : 'Trabajo guardado exitosamente';

            return redirect()->to('ordenes-trabajo/show/' . $id)
                           ->with('success', $mensaje);

        } catch (\Exception $e) {
            log_message('error', 'Error al guardar trabajo: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al guardar el trabajo: ' . $e->getMessage());
        }
    }

    /**
     * Vista de calendario de órdenes de trabajo
     */
    public function calendario()
    {
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        // Obtener solicitudes para el calendario
        $solicitudes = $this->solicitudModel->getSolicitudesParaCalendario($empresaId);
        
        // Formatear datos para el calendario
        $eventos = [];
        foreach ($solicitudes as $solicitud) {
            $color = $this->getColorPorEstado($solicitud['estado']);
            
            $eventos[] = [
                'id' => $solicitud['id'],
                'title' => ($solicitud['codigo_consecutivo'] ?? 'S-' . $solicitud['id']) . ' - ' . $solicitud['descripcion'],
                'start' => $solicitud['fecha_solicitud'],
                'end' => $solicitud['fecha_programada'] ?? $solicitud['fecha_solicitud'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'estado' => $solicitud['estado'],
                    'vehiculo' => $solicitud['placa'] ?? 'Sin asignar',
                    'mecanico' => $solicitud['mecanico_asignado'] ?? 'Sin asignar',
                    'prioridad' => $solicitud['prioridad'] ?? 'NORMAL'
                ]
            ];
        }

        $data = [
            'title' => 'Calendario de Órdenes de Trabajo',
            'eventos' => json_encode($eventos),
            'solicitudes_raw' => $solicitudes
        ];

        return view('ordenes_trabajo/calendario', $data);
    }

    /**
     * Vista estilo órdenes de cocina (Kanban)
     */
    public function kanban()
    {
        $empresaId = $this->session->get('empresa_id');
        
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        // Obtener solicitudes agrupadas por estado
        $solicitudesPorEstado = [
            'PENDIENTE' => $this->solicitudModel->getSolicitudesPorEstado($empresaId, 'PENDIENTE'),
            'EN_PROCESO' => $this->solicitudModel->getSolicitudesPorEstado($empresaId, 'EN_PROCESO'),
            'FINALIZADA' => $this->solicitudModel->getSolicitudesPorEstado($empresaId, 'FINALIZADA'),
            'RECHAZADA' => $this->solicitudModel->getSolicitudesPorEstado($empresaId, 'RECHAZADA')
        ];

        // Obtener conteos
        $conteos = [];
        foreach ($solicitudesPorEstado as $estado => $solicitudes) {
            $conteos[$estado] = count($solicitudes);
        }

        $data = [
            'title' => 'Órdenes de Trabajo - Vista Kanban',
            'solicitudes_por_estado' => $solicitudesPorEstado,
            'conteos' => $conteos
        ];

        return view('ordenes_trabajo/kanban', $data);
    }

    /**
     * API para mover solicitud entre estados (para Kanban)
     */
    public function moverSolicitud()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        $solicitudId = $this->request->getPost('solicitud_id');
        $nuevoEstado = $this->request->getPost('nuevo_estado');

        try {
            $resultado = $this->solicitudModel->cambiarEstado($solicitudId, $nuevoEstado);
            
            if ($resultado) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Estado actualizado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Error al actualizar el estado'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API para obtener eventos del calendario
     */
    public function apiEventosCalendario()
    {
        $empresaId = $this->session->get('empresa_id');
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');

        if (!$empresaId) {
            return $this->response->setJSON([]);
        }

        $solicitudes = $this->solicitudModel->getSolicitudesEnRango($empresaId, $start, $end);
        
        $eventos = [];
        foreach ($solicitudes as $solicitud) {
            $color = $this->getColorPorEstado($solicitud['estado']);
            
            $eventos[] = [
                'id' => $solicitud['id'],
                'title' => ($solicitud['codigo_consecutivo'] ?? 'S-' . $solicitud['id']) . ' - ' . substr($solicitud['descripcion'], 0, 30) . '...',
                'start' => $solicitud['fecha_solicitud'],
                'end' => $solicitud['fecha_programada'] ?? $solicitud['fecha_solicitud'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'url' => base_url('ordenes-trabajo/show/' . $solicitud['id'])
            ];
        }

        return $this->response->setJSON($eventos);
    }

    /**
     * Obtener color según el estado
     */
    private function getColorPorEstado($estado)
    {
        $colores = [
            'PENDIENTE' => '#ffc107',    // Amarillo
            'EN_PROCESO' => '#fd7e14',   // Naranja
            'FINALIZADA' => '#28a745',   // Verde
            'RECHAZADA' => '#dc3545'     // Rojo
        ];

        return $colores[$estado] ?? '#6c757d'; // Gris por defecto
    }
}
