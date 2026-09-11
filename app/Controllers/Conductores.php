<?php

namespace App\Controllers;

use App\Models\ConductorModel;
use App\Models\InvProductosModel;
use App\Models\ConductorErpModel;
use App\Models\DocumentacionConductorModel;
use App\Models\AsignacionVehiculoModel;
use CodeIgniter\HTTP\ResponseInterface;

class Conductores extends SecureController
{
    protected $conductorModel;
    protected $documentacionModel;
    protected $asignacionModel;
    protected $conductorErpModel;
    protected $db;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->conductorModel = new ConductorModel();
        $this->documentacionModel = new DocumentacionConductorModel();
        $this->asignacionModel = new AsignacionVehiculoModel();
        $this->invProductosModel = new InvProductosModel();
        $this->conductorErpModel = new ConductorErpModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Lista principal de conductores
     */
    public function index()
    {
        $this->requireAccess('conductores');
        
        $data = [
            'title' => 'Gestión de Conductores - GMV',
            'page_title' => 'Gestión de Conductores'
        ];

        return view('conductores/index', $data);
    }

    public function sincronizar()
    {
        $this->requireAccess('conductores');

        $data = [
            'title' => 'Sincronizar Conductores ERP',
            'page_title' => 'Sincronización con ERP'
        ];

        return view('conductores/sincronizar', $data);
    }

    public function listarConductoresErp()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no autorizado']);
        }

        try {
            $codigos=$this->conductorModel->obtenerCarnetsConcatenados();
            $conductoresNomina = $this->invProductosModel->ConductoresNomina($codigos);
            $datos = $conductoresNomina;
            return $this->response->setJSON([
                'success' => true,
                'conductores' => $datos
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Error al listar conductores ERP: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al consultar ERP'
            ]);
        }
    }

    public function sincronizarConductor()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no autorizado']);
        }

        $codigo = $this->request->getPost('codigo');

        if (empty($codigo)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Código requerido']);
        }

        try {
            $datosErp = $this->invProductosModel->ConductoresNominaByCodigo($codigo);

            if (!$datosErp) {
                return $this->response->setJSON(['success' => false, 'message' => 'Conductor no encontrado en ERP']);
            }

            $session = session();
            $empresaId = $session->get('empresa_id');

           
            $carnetErp = $datosErp['No. Empleado']
                ?? $datosErp['NoEmpleado']
                ?? $datosErp['No_Empleado']
                ?? null;

            $carnet = $carnetErp !== null ? trim((string)$carnetErp) : '';

            $data = [
                'id_empresa' => $empresaId,
                'codigo_consecutivo' => $this->conductorModel->generarCodigoConductor($empresaId),
                'nombre' => $datosErp['Nombres y Apellidos'],
                'apellido' => "",
                'dni' => $datosErp['Cedula'] ?? '',
                'fechaIngreso' => $datosErp['Fecha de Ingreso'] ?? date('Y-m-d'),
                'estado' => 'ACTIVO',
                'carnet' => $carnet,
                'usuarioCrea' => $session->get('user_id')
            ];

            $this->conductorModel->insert($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Conductor sincronizado correctamente'
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Error al sincronizar conductor: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al sincronizar conductor'
            ]);
        }
    }

    /**
     * Obtener datos para DataTables
     */
    public function getData()
    {
        $session = session();
        $empresaId = $session->get('empresa_id');

        // Depuración: Verificar empresa_id de la sesión
        log_message('debug', 'Empresa ID en sesión: ' . $empresaId);

        // Parámetros de DataTables
        $draw = $this->request->getVar('draw');
        $start = $this->request->getVar('start') ?? 0;
        $length = $this->request->getVar('length') ?? 10;
        $search = $this->request->getVar('search');
        $searchValue = is_array($search) ? ($search['value'] ?? '') : ($search ?? '');

        // Filtros adicionales
        $filtros = [
            'estado' => $this->request->getVar('estado')
        ];
        
        // Si hay empresa_id válido, agregar el filtro
        if (!empty($empresaId)) {
            $filtros['empresa_id'] = $empresaId;
        }
        
        // Solo agregar búsqueda si hay un valor
        if (!empty($searchValue)) {
            $filtros['search'] = $searchValue;
        }

        // Depuración: Mostrar los filtros que se están aplicando
        log_message('debug', 'Filtros aplicados: ' . print_r($filtros, true));
        
        // Obtener datos
        $conductores = $this->conductorModel->getConductoresConFiltros($filtros, $length, $start);
        
        $totalRecords = $this->conductorModel->contarConductoresConFiltros(['empresa_id' => $empresaId]);
        $filteredRecords = $this->conductorModel->contarConductoresConFiltros($filtros);
        
        // Depuración: Mostrar la consulta SQL generada
        $lastQuery = $this->conductorModel->getLastQuery();
        log_message('debug', 'Última consulta SQL: ' . $lastQuery);
        log_message('debug', 'Total de registros: ' . $totalRecords);
        log_message('debug', 'Registros filtrados: ' . $filteredRecords);
        log_message('debug', 'Conductores encontrados: ' . count($conductores));

        // Formatear datos para DataTables según la estructura real de la base de datos
        $data = [];
        foreach ($conductores as $conductor) {
            $data[] = [
                'id' => $conductor['id'],
                'id_empresa' => $conductor['id_empresa'] ?? null,
                'codigo_consecutivo' => $conductor['codigo_consecutivo'] ?? '-',
                'nombre' => $conductor['nombre'] ?? '',
                'apellido' => $conductor['apellido'] ?? '',
                'dni' => $conductor['dni'] ?? '-',
                'fechaIngreso' => $conductor['fechaIngreso'] ?? null,
                'estado' => $conductor['estado'] ?? 'INACTIVO',
                'fechaRegistro' => $conductor['fechaRegistro'] ?? null,
                'fechaUpdate' => $conductor['fechaUpdate'] ?? null,
                'usuarioCrea' => $conductor['usuarioCrea'] ?? null,
                'usuarioEdita' => $conductor['usuarioEdita'] ?? null,
                'licencia' => $conductor['licencia'] ?? 'NO ESPECIFICADA',
                'fechaVencimientoLicencia' => $conductor['fechaVencimientoLicencia'] ?? null,
                'carnet' => $conductor['carnet'] ?? '',
                'vehiculos_asignados' => $conductor['vehiculos_asignados'] ?? 0,
                'nombre_empresa' => $conductor['nombre_empresa'] ?? 'SIN EMPRESA'
            ];
        }
        
        // Depuración: Mostrar los datos que se enviarán
        log_message('debug', 'Datos a enviar: ' . print_r($data, true));
        
        // Verificar si hay datos
        if (empty($data)) {
            log_message('debug', 'No se encontraron conductores con los filtros aplicados');
        }

        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];
        
        log_message('debug', 'Respuesta JSON: ' . print_r($response, true));
        
        return $this->response->setJSON($response);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $data = [
            'title' => 'Registrar Conductor - GMV',
            'page_title' => 'Registrar Nuevo Conductor'
        ];

        return view('conductores/create', $data);
    }

    /**
     * Procesar creación de conductor
     */
    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $session = session();
        $empresaId = $session->get('empresa_id');

        // Validaciones
        $validationRules = [
            'nombre' => 'required|min_length[2]|max_length[100]',
            'apellido' => 'required|min_length[2]|max_length[100]',
            'dni' => 'required|min_length[6]|max_length[20]|alpha_numeric',
            'fechaIngreso' => 'required|valid_date'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Verificar DNI único
        if ($this->conductorModel->existeDni($this->request->getPost('dni'), $empresaId)) {
            return redirect()->back()->withInput()->with('error', 'Ya existe un conductor con este DNI');
        }

        // Generar código
        $codigo = $this->conductorModel->generarCodigoConductor($empresaId);

        // Preparar datos
        $data = [
            'codigo_consecutivo' => $codigo,
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'dni' => $this->request->getPost('dni'),
            'fechaIngreso' => $this->request->getPost('fechaIngreso'),
            'estado' => 'ACTIVO',
            'id_empresa' => $empresaId,
            'usuarioCrea' => $session->get('user_id')
        ];

        if ($this->conductorModel->insert($data)) {
            return redirect()->to(base_url('conductores'))->with('success', 'Conductor registrado exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al registrar el conductor');
        }
    }

    /**
     * Mostrar detalles del conductor
     */
    public function show($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $conductor = $this->conductorModel->getConductorCompleto($id);

        if (!$conductor) {
            return redirect()->to(base_url('conductores'))->with('error', 'Conductor no encontrado');
        }

        // Verificar que pertenece a la empresa del usuario
        $session = session();
        if ($conductor['id_empresa'] != $session->get('empresa_id')) {
            return redirect()->to(base_url('conductores'))->with('error', 'No tiene permisos para ver este conductor');
        }

        // Obtener vehículos asignados
        $vehiculosAsignados = $this->db->table('vehiculos')
                                     ->select('vehiculos.*')
                                     ->where('vehiculos.id_conductor', $id)
                                     ->where('vehiculos.estado', 'ACTIVO')
                                     ->get()
                                     ->getResultArray();

        // Obtener historial de asignaciones de vehículos
        $historialAsignaciones = $this->asignacionModel->getHistorialConductor($id);

        // Solicitudes activas del conductor
        $solicitudesActivas = $this->db->table('solicitudes')
            ->where('id_solicitante', $id)
            ->whereIn('estado', ['PENDIENTES', 'EN_PROCESO', 'APROBADAS'])
            ->countAllResults();

        // Mantenimientos completados
        $mantenimientosCompletados = $this->db->table('solicitudes')
            ->where('id_solicitante', $id)
            ->where('estado', 'FINALIZADA')
            ->countAllResults();

        // Documentación del conductor
        $documentos = $this->documentacionModel->getDocumentosPorConductor($id);

        $data = [
            'title' => 'Detalles del Conductor - GMV',
            'page_title' => 'Detalles del Conductor',
            'conductor' => $conductor,
            'vehiculos_asignados' => $vehiculosAsignados,
            'historial_estados' => $this->conductorModel->getHistorialEstados($id),
            'historial_asignaciones' => $historialAsignaciones,
            'solicitudes_activas' => $solicitudesActivas,
            'mantenimientos_completados' => $mantenimientosCompletados,
            'documentos' => $documentos
        ];

        return view('conductores/show', $data);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $conductor = $this->conductorModel->find($id);

        if (!$conductor) {
            return redirect()->to(base_url('conductores'))->with('error', 'Conductor no encontrado');
        }

        // Verificar que pertenece a la empresa del usuario
        $session = session();
        if ($conductor['id_empresa'] != $session->get('empresa_id')) {
            return redirect()->to(base_url('conductores'))->with('error', 'No tiene permisos para editar este conductor');
        }

        $data = [
            'title' => 'Editar Conductor - GMV',
            'page_title' => 'Editar Conductor',
            'conductor' => $conductor
        ];

        return view('conductores/edit', $data);
    }

    /**
     * Procesar actualización de conductor
     */
    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $conductor = $this->conductorModel->find($id);
        if (!$conductor) {
            return redirect()->to(base_url('conductores'))->with('error', 'Conductor no encontrado');
        }

        $session = session();
        $empresaId = $session->get('empresa_id');

        // Verificar permisos
        if ($conductor['id_empresa'] != $empresaId) {
            return redirect()->to(base_url('conductores'))->with('error', 'No tiene permisos para editar este conductor');
        }

        // Validaciones (mismas que en store)
        $validationRules = [
            'nombre' => 'required|min_length[2]|max_length[100]',
            'apellido' => 'required|min_length[2]|max_length[100]',
            'dni' => 'required|min_length[6]|max_length[20]|alpha_numeric',
            'fechaIngreso' => 'required|valid_date'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Verificar DNI único (excluyendo el actual)
        if ($this->conductorModel->existeDni($this->request->getPost('dni'), $empresaId, $id)) {
            return redirect()->back()->withInput()->with('error', 'Ya existe otro conductor con este DNI');
        }

        // Preparar datos
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'dni' => $this->request->getPost('dni'),
            'fechaIngreso' => $this->request->getPost('fechaIngreso'),
            'usuarioEdita' => $session->get('user_id')
        ];

        if ($this->conductorModel->update($id, $data)) {
            return redirect()->to(base_url('conductores/show/' . $id))->with('success', 'Conductor actualizado exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el conductor');
        }
    }

    /**
     * Cambiar estado del conductor
     */
    public function cambiarEstado()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $id = $this->request->getPost('id');
        $nuevoEstado = $this->request->getPost('estado');
        $motivo = $this->request->getPost('motivo');

        $conductor = $this->conductorModel->find($id);
        if (!$conductor) {
            return $this->response->setJSON(['success' => false, 'message' => 'Conductor no encontrado']);
        }

        $session = session();
        if ($conductor['id_empresa'] != $session->get('empresa_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sin permisos']);
        }

        // Actualizar estado
        $data = [
            'estado' => $nuevoEstado,
            'usuarioEdita' => $session->get('user_id')
        ];

        if ($this->conductorModel->update($id, $data)) {
            // Registrar en historial
            $this->registrarCambioEstado($id, $conductor['estado'], $nuevoEstado, $motivo, $session->get('user_id'));

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
    }

    /**
     * Eliminar conductor
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $conductor = $this->conductorModel->find($id);
        if (!$conductor) {
            return $this->response->setJSON(['success' => false, 'message' => 'Conductor no encontrado']);
        }

        $session = session();
        if ($conductor['id_empresa'] != $session->get('empresa_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sin permisos']);
        }

        // Verificar si tiene vehículos asignados
        $vehiculosAsignados = $this->db->table('vehiculos')
                                     ->where('id_conductor', $id)
                                     ->where('estado', 'ACTIVO')
                                     ->countAllResults();

        if ($vehiculosAsignados > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se puede eliminar el conductor porque tiene vehículos asignados'
            ]);
        }

        if ($this->conductorModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Conductor eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar el conductor'
            ]);
        }
    }

    /**
     * Obtener estadísticas de conductores
     */
    public function getEstadisticas()
    {
        $session = session();
        $empresaId = $session->get('empresa_id');

        // Obtener estadísticas
        $totalConductores = $this->conductorModel->where('id_empresa', $empresaId)->countAllResults();
        $conductoresActivos = $this->conductorModel->where(['id_empresa' => $empresaId, 'estado' => 'ACTIVO'])->countAllResults();
        
        // Contar conductores con vehículos asignados
        $conductoresConVehiculo = $this->conductorModel->select('COUNT(DISTINCT conductores.id) as total')
            ->join('vehiculos', 'vehiculos.id_conductor = conductores.id AND vehiculos.estado = "ACTIVO"', 'left')
            ->where('conductores.id_empresa', $empresaId)
            ->where('vehiculos.id_conductor IS NOT NULL')
            ->countAllResults();

        // Contar licencias por vencer (en los próximos 30 días)
        $fechaLimite = date('Y-m-d', strtotime('+30 days'));
        $licenciasPorVencer = $this->db->table('documentacion_conductor')
            ->join('conductores', 'conductores.id = documentacion_conductor.idConductor')
            ->join('catalogo', 'catalogo.id = documentacion_conductor.tipo_documento', 'left')
            ->where('conductores.id_empresa', $empresaId)
            ->like('catalogo.nombre', 'LICENCIA')
            ->where('documentacion_conductor.fecha_vencimiento >=', date('Y-m-d'))
            ->where('documentacion_conductor.fecha_vencimiento <=', $fechaLimite)
            ->countAllResults();

        return $this->response->setJSON([
            'total_conductores' => $totalConductores,
            'conductores_activos' => $conductoresActivos,
            'conductores_con_vehiculo' => $conductoresConVehiculo,
            'licencias_por_vencer' => $licenciasPorVencer
        ]);
    }

    /**
     * Verificar si DNI ya existe
     */
    public function verificarDni()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $dni = $this->request->getPost('dni');
        $excludeId = $this->request->getPost('exclude_id');
        $session = session();
        $empresaId = $session->get('empresa_id');

        $existe = $this->conductorModel->existeDni($dni, $empresaId, $excludeId);

        return $this->response->setJSON(['existe' => $existe]);
    }



    /**
     * Generar acciones para DataTables
     */
    private function generarAcciones($conductor)
    {
        $acciones = '<div class="btn-group" role="group">';
        
        $acciones .= '<a href="' . base_url('conductores/show/' . $conductor['id']) . '" class="btn btn-sm btn-outline-info" title="Ver detalles">';
        $acciones .= '<i class="fas fa-eye"></i></a>';
        
        $acciones .= '<a href="' . base_url('conductores/edit/' . $conductor['id']) . '" class="btn btn-sm btn-outline-warning" title="Editar">';
        $acciones .= '<i class="fas fa-edit"></i></a>';

        if ($conductor['estado'] == 'ACTIVO') {
            $acciones .= '<button class="btn btn-sm btn-outline-secondary cambiar-estado" data-id="' . $conductor['id'] . '" data-estado="INACTIVO" title="Desactivar">';
            $acciones .= '<i class="fas fa-pause"></i></button>';
        } else {
            $acciones .= '<button class="btn btn-sm btn-outline-success cambiar-estado" data-id="' . $conductor['id'] . '" data-estado="ACTIVO" title="Activar">';
            $acciones .= '<i class="fas fa-play"></i></button>';
        }

        $acciones .= '<button class="btn btn-sm btn-outline-danger eliminar-conductor" data-id="' . $conductor['id'] . '" title="Eliminar">';
        $acciones .= '<i class="fas fa-trash"></i></button>';

        $acciones .= '</div>';

        return $acciones;
    }

    /**
     * Registrar cambio de estado en historial
     */
    private function registrarCambioEstado($conductorId, $estadoAnterior, $estadoNuevo, $motivo, $usuarioId)
    {
        $data = [
            'conductor_id' => $conductorId,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'motivo' => $motivo,
            'usuario_id' => $usuarioId,
            'fecha_cambio' => date('Y-m-d H:i:s')
        ];

        $this->db->table('historial_estados_conductor')->insert($data);
    }

    /**
     * Muestra la documentación de un conductor
     */
    public function documentos($idConductor)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        // Verificar que el conductor existe y pertenece a la empresa del usuario
        $conductor = $this->conductorModel->find($idConductor);
        if (!$conductor || $conductor['id_empresa'] != session()->get('empresa_id')) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Conductor no encontrado o sin permisos'
            ])->setStatusCode(403);
        }

        // Obtener la documentación del conductor
        $documentos = $this->documentacionModel->getDocumentosPorConductor($idConductor);
        
        // Actualizar estados de documentos
        $this->documentacionModel->actualizarEstadosDocumentos();

        // Obtener tipos de documento del catálogo CAT-0004 (hijos del padre)
        $db = \Config\Database::connect();
        $tiposDocumento = $db->table('catalogo')
            ->where('codigo', 'CAT-0004')
            ->where('id_superior !=', 0)
            ->where('estado', 1)
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Documentación del Conductor - GMV',
            'page_title' => 'Documentación de ' . $conductor['nombre'] . ' ' . $conductor['apellido'],
            'conductor' => $conductor,
            'documentos' => $documentos,
            'tiposDocumento' => $tiposDocumento
        ];

        // Si es una petición AJAX, devolver solo el contenido del contenedor de vista
        if ($this->request->isAJAX()) {
            $vista = $this->request->getGet('vista') === 'table' ? 'table' : 'cards';
            $view = 'conductores/partials/documentos_' . $vista;
            
            return $this->response->setJSON([
                'success' => true,
                'html' => view($view, ['documentos' => $documentos, 'conductor' => $conductor])
            ]);
        }

        // Si no es AJAX, cargar la vista completa
        return view('conductores/documentos', $data);
    }

    /**
     * Guarda un documento de conductor
     */
    public function guardarDocumento()
    {
        $session = session();
        $idConductor = $this->request->getPost('idConductor');
        
        // Verificar permisos y que el conductor pertenezca a la empresa
        $conductor = $this->conductorModel->find($idConductor);
        if (!$conductor || $conductor['id_empresa'] != $session->get('empresa_id')) {
            return redirect()->back()->with('error', 'No tiene permisos para esta acción');
        }

        // Validar archivo si se está subiendo
        $archivo = null;
        $file = $this->request->getFile('archivo');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Crear directorio si no existe
            $uploadPath = ROOTPATH . 'public/uploads/documentos';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $nuevoNombre = $file->getRandomName();
            $file->move($uploadPath, $nuevoNombre);
            $archivo = 'uploads/documentos/' . $nuevoNombre;
        } else if ($file) {
            // Si hay un error en la subida
            $error = $file->getError();
            $errorMessage = 'Error al subir el archivo. Código: ' . $error;
            
            if ($error == UPLOAD_ERR_INI_SIZE || $error == UPLOAD_ERR_FORM_SIZE) {
                $errorMessage = 'El archivo excede el tamaño máximo permitido.';
            } elseif ($error == UPLOAD_ERR_PARTIAL) {
                $errorMessage = 'El archivo fue subido solo parcialmente.';
            } elseif ($error == UPLOAD_ERR_NO_FILE) {
                $errorMessage = 'No se seleccionó ningún archivo.';
            } elseif ($error == UPLOAD_ERR_NO_TMP_DIR) {
                $errorMessage = 'No se encontró el directorio temporal.';
            } elseif ($error == UPLOAD_ERR_CANT_WRITE) {
                $errorMessage = 'No se pudo escribir el archivo en el disco.';
            } elseif ($error == UPLOAD_ERR_EXTENSION) {
                $errorMessage = 'Una extensión de PHP detuvo la carga del archivo.';
            }
            
            return redirect()->back()->withInput()->with('error', $errorMessage);
        } else {
            return redirect()->back()->withInput()->with('error', 'No se ha seleccionado ningún archivo o el archivo no es válido.');
        }

        // Preparar datos
        $data = [
            'idConductor' => $idConductor,
            'tipo_documento' => $this->request->getPost('tipo_documento'),
            'numero_documento' => $this->request->getPost('numero_documento'),
            'fecha_emision' => $this->request->getPost('fecha_emision'),
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
            'observaciones' => $this->request->getPost('observaciones'),
            'usuario_crea' => $session->get('user_id'),
            'usuario_actualiza' => $session->get('user_id'),
            'ruta_documento' => $archivo,
            'estado' => 'VIGENTE' // Estado por defecto
        ];

        // Insertar en la base de datos
        try {
            if ($this->documentacionModel->save($data)) {
                // Actualizar estados después de guardar
                $this->documentacionModel->actualizarEstadosDocumentos();
                
                return redirect()->to("conductores/documentos/{$idConductor}")
                               ->with('success', 'Documento guardado correctamente');
            } else {
                // Si hay un error, eliminar el archivo subido
                if ($archivo && file_exists(ROOTPATH . 'public/' . $archivo)) {
                    unlink(ROOTPATH . 'public/' . $archivo);
                }
                
                $errors = $this->documentacionModel->errors();
                log_message('error', 'Error al guardar documento: ' . print_r($errors, true));
                
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Error al guardar el documento: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            // Si hay una excepción, eliminar el archivo subido
            if ($archivo && file_exists(ROOTPATH . 'public/' . $archivo)) {
                unlink(ROOTPATH . 'public/' . $archivo);
            }
            
            log_message('error', 'Excepción al guardar documento: ' . $e->getMessage());
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error interno al guardar el documento: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un documento de conductor
     */
    public function eliminarDocumento($idDocumento)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no autorizado']);
        }

        $session = session();
        
        // Obtener el documento
        $documento = $this->documentacionModel->find($idDocumento);
        if (!$documento) {
            return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado']);
        }

        // Verificar que el conductor pertenece a la empresa del usuario
        $conductor = $this->conductorModel->find($documento['IdConductor']);
        if (!$conductor || $conductor['id_empresa'] != $session->get('empresa_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tiene permisos para esta acción']);
        }

        // Eliminar archivo físico si existe
        // Los archivos se guardan en public/uploads/documentos/ y la ruta en BD incluye 'uploads/documentos/'
        if ($documento['ruta_documento'] && file_exists(ROOTPATH . 'public/' . $documento['ruta_documento'])) {
            unlink(ROOTPATH . 'public/' . $documento['ruta_documento']);
        }

        // Eliminar de la base de datos
        if ($this->documentacionModel->delete($idDocumento)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Documento eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar el documento'
            ]);
        }
    }

    /**
     * Servir archivo de documento de forma segura para visualización
     */
    public function verDocumento($id)
    {
        // Obtener documento con información del conductor
        $documento = $this->db->table('documentacion_conductor')
                             ->select('documentacion_conductor.*, conductores.id_empresa')
                             ->join('conductores', 'conductores.id = documentacion_conductor.IdConductor')
                             ->where('documentacion_conductor.id', $id)
                             ->get()
                             ->getRowArray();

        if (!$documento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento no encontrado');
        }

        // Verificar permisos
        $session = session();
        if ($documento['id_empresa'] != $session->get('empresa_id')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Sin permisos para ver este documento');
        }

        // Verificar que el archivo existe
        // Los archivos se guardan en public/uploads/documentos/ y la ruta en BD incluye 'uploads/documentos/'
        $rutaArchivo = ROOTPATH . 'public/' . $documento['ruta_documento'];
        
        // Debug temporal - logging para diagnosticar
        log_message('debug', 'verDocumento - ID: ' . $id . ', Ruta BD: ' . $documento['ruta_documento'] . ', Ruta física: ' . $rutaArchivo);
        log_message('debug', 'verDocumento - Archivo existe: ' . (file_exists($rutaArchivo) ? 'SI' : 'NO'));
        if (file_exists($rutaArchivo)) {
            log_message('debug', 'verDocumento - Tamaño archivo: ' . filesize($rutaArchivo) . ' bytes, Modificado: ' . date('Y-m-d H:i:s', filemtime($rutaArchivo)));
        }
        
        if (!file_exists($rutaArchivo)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }

        // Obtener información del archivo
        $extension = pathinfo($documento['ruta_documento'], PATHINFO_EXTENSION);
        $nombreArchivo = $documento['tipo_documento'] . '_' . $documento['numero_documento'] . '.' . $extension;
        $fileSize = filesize($rutaArchivo);
        
        // Determinar MIME type basado en extensión
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'txt' => 'text/plain'
        ];
        
        $mimeType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
        
        // Limpiar cualquier salida previa
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Configurar headers anti-cache para evitar mostrar archivos antiguos
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . $nombreArchivo . '"');
        header('Content-Length: ' . $fileSize);
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('ETag: "' . md5($rutaArchivo . filemtime($rutaArchivo)) . '"');
        
        // Para PDFs, agregar headers adicionales
        if ($mimeType === 'application/pdf') {
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
        }
        
        // Enviar el archivo
        readfile($rutaArchivo);
        exit;
    }

    /**
     * Descargar archivo de documento
     */
    public function descargarDocumento($id)
    {
        // Obtener documento con información del conductor
        $documento = $this->db->table('documentacion_conductor')
                             ->select('documentacion_conductor.*, conductores.id_empresa')
                             ->join('conductores', 'conductores.id = documentacion_conductor.IdConductor')
                             ->where('documentacion_conductor.id', $id)
                             ->get()
                             ->getRowArray();

        if (!$documento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento no encontrado');
        }

        // Verificar permisos
        $session = session();
        if ($documento['id_empresa'] != $session->get('empresa_id')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Sin permisos para descargar este documento');
        }

        // Verificar que el archivo existe
        // Los archivos se guardan en public/uploads/documentos/ y la ruta en BD incluye 'uploads/documentos/'
        $rutaArchivo = ROOTPATH . 'public/' . $documento['ruta_documento'];
        
        // Debug temporal - logging para diagnosticar
        log_message('debug', 'descargarDocumento - ID: ' . $id . ', Ruta BD: ' . $documento['ruta_documento'] . ', Ruta física: ' . $rutaArchivo);
        log_message('debug', 'descargarDocumento - Archivo existe: ' . (file_exists($rutaArchivo) ? 'SI' : 'NO'));
        if (file_exists($rutaArchivo)) {
            log_message('debug', 'descargarDocumento - Tamaño archivo: ' . filesize($rutaArchivo) . ' bytes, Modificado: ' . date('Y-m-d H:i:s', filemtime($rutaArchivo)));
        }
        
        if (!file_exists($rutaArchivo)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }

        // Obtener información del archivo
        $extension = pathinfo($documento['ruta_documento'], PATHINFO_EXTENSION);
        $nombreArchivo = $documento['tipo_documento'] . '_' . $documento['numero_documento'] . '.' . $extension;
        $fileSize = filesize($rutaArchivo);
        
        // Determinar MIME type basado en extensión
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'txt' => 'text/plain'
        ];
        
        $mimeType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
        
        // Limpiar cualquier salida previa
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Configurar headers para descarga sin cache
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Content-Length: ' . $fileSize);
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('ETag: "' . md5($rutaArchivo . filemtime($rutaArchivo)) . '"');
        
        // Enviar el archivo
        readfile($rutaArchivo);
        exit;
    }

    /**
     * Mostrar formulario de edición de documento
     */
    public function editarDocumento($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        // Obtener documento con información del conductor
        $documento = $this->db->table('documentacion_conductor')
                             ->select('documentacion_conductor.*, conductores.id_empresa, conductores.nombre, conductores.apellido')
                             ->join('conductores', 'conductores.id = documentacion_conductor.IdConductor')
                             ->where('documentacion_conductor.id', $id)
                             ->get()
                             ->getRowArray();

        if (!$documento) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Documento no encontrado']);
            }
            return redirect()->back()->with('error', 'Documento no encontrado');
        }

        // Verificar permisos
        $session = session();
        if ($documento['id_empresa'] != $session->get('empresa_id')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Sin permisos para editar este documento']);
            }
            return redirect()->back()->with('error', 'Sin permisos para editar este documento');
        }

        // Si es AJAX, devolver JSON para el modal
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $documento
            ]);
        }

        $data = [
            'title' => 'Editar Documento - GMV',
            'page_title' => 'Editar Documento de ' . $documento['nombre'] . ' ' . $documento['apellido'],
            'documento' => $documento
        ];

        return view('conductores/editar_documento', $data);
    }

    /**
     * Procesar actualización de documento
     */
    public function actualizarDocumento($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        // Obtener documento existente
        $documento = $this->db->table('documentacion_conductor')
                             ->select('documentacion_conductor.*, conductores.id_empresa')
                             ->join('conductores', 'conductores.id = documentacion_conductor.idConductor')
                             ->where('documentacion_conductor.id', $id)
                             ->get()
                             ->getRowArray();

        if (!$documento) {
            return redirect()->back()->with('error', 'Documento no encontrado');
        }

        // Verificar permisos
        $session = session();
        if ($documento['id_empresa'] != $session->get('empresa_id')) {
            return redirect()->back()->with('error', 'Sin permisos para editar este documento');
        }

        // Preparar datos de actualización
        $data = [
            'tipo_documento' => $this->request->getPost('tipo_documento'),
            'numero_documento' => $this->request->getPost('numero_documento'),
            'fecha_emision' => $this->request->getPost('fecha_emision'),
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
            'observaciones' => $this->request->getPost('observaciones'),
            'notificacion' => $this->request->getPost('notificacion'),
            'usuario_actualiza' => $session->get('user_id')
        ];

        // Manejar archivo nuevo si se subió
        $file = $this->request->getFile('archivo');
        log_message('debug', 'actualizarDocumento - Archivo recibido: ' . ($file ? 'SI' : 'NO'));
        if ($file) {
            log_message('debug', 'actualizarDocumento - Archivo válido: ' . ($file->isValid() ? 'SI' : 'NO') . ', Movido: ' . ($file->hasMoved() ? 'SI' : 'NO'));
        }
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Crear directorio si no existe
            $uploadPath = ROOTPATH . 'public/uploads/documentos';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Logging del archivo anterior
            log_message('debug', 'actualizarDocumento - Archivo anterior: ' . $documento['ruta_documento']);
            
            // Eliminar archivo anterior si existe
            if ($documento['ruta_documento'] && file_exists(ROOTPATH . 'public/' . $documento['ruta_documento'])) {
                log_message('debug', 'actualizarDocumento - Eliminando archivo anterior: ' . ROOTPATH . 'public/' . $documento['ruta_documento']);
                unlink(ROOTPATH . 'public/' . $documento['ruta_documento']);
            }
            
            // Subir nuevo archivo
            $nuevoNombre = $file->getRandomName();
            log_message('debug', 'actualizarDocumento - Nuevo nombre archivo: ' . $nuevoNombre);
            $file->move($uploadPath, $nuevoNombre);
            $data['ruta_documento'] = 'uploads/documentos/' . $nuevoNombre;
            log_message('debug', 'actualizarDocumento - Nueva ruta BD: ' . $data['ruta_documento']);
        } else if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            // Si hay un error en la subida del archivo
            $error = $file->getError();
            $errorMessage = 'Error al subir el archivo.';
            
            if ($error == UPLOAD_ERR_INI_SIZE || $error == UPLOAD_ERR_FORM_SIZE) {
                $errorMessage = 'El archivo excede el tamaño máximo permitido.';
            } elseif ($error == UPLOAD_ERR_PARTIAL) {
                $errorMessage = 'El archivo fue subido solo parcialmente.';
            }
            
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        // Actualizar en la base de datos
        try {
            if ($this->documentacionModel->update($id, $data)) {
                // Actualizar estados después de guardar
                $this->documentacionModel->actualizarEstadosDocumentos();
                
                return redirect()->to("conductores/documentos/{$documento['IdConductor']}")
                               ->with('success', 'Documento actualizado correctamente');
            } else {
                $errors = $this->documentacionModel->errors();
                log_message('error', 'Error al actualizar documento: ' . print_r($errors, true));
                
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Error al actualizar el documento: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción al actualizar documento: ' . $e->getMessage());
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error interno al actualizar el documento: ' . $e->getMessage());
        }
    }
}
