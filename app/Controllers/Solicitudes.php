<?php

namespace App\Controllers;

use App\Models\SolicitudModel;
use App\Models\VehiculoModel;
use App\Models\UsuarioModel;
use App\Models\TipoProblemaModel;
use App\Models\ConductorModel;
use App\Models\CatalogoModel;
use App\Models\SolicitudDocumentoModel;
use App\Models\SolicitudHistorialModel;
use App\Models\EmpresaModel;

class Solicitudes extends BaseController
{
    protected $solicitudModel;
    protected $vehiculoModel;
    protected $usuarioModel;
    protected $tipoProblemaModel;
    protected $conductorModel;
    protected $catalogoModel;
    protected $empresaModel;
    protected $session;

    public function __construct()
    {
        $this->solicitudModel = new SolicitudModel();
        $this->vehiculoModel = new VehiculoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->tipoProblemaModel = new TipoProblemaModel();
        $this->conductorModel = new ConductorModel();
        $this->catalogoModel = new CatalogoModel();
        $this->empresaModel = new EmpresaModel();
        $this->session = session();

        helper(['form', 'url', 'date', 'text', 'vehiculo']);
    }

    // Listar todas las solicitudes
    public function index()
    {
        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');
        $rol = $this->session->get('rol_name');

        // Obtener filtros
        $filtros = [
            'id_empresa' => $empresaId,
            'estado' => $this->request->getGet('estado') ?? '',
            'fecha_desde' => $this->request->getGet('fecha_desde') ?? '',
            'fecha_hasta' => $this->request->getGet('fecha_hasta') ?? '',
            'id_vehiculo' => $this->request->getGet('id_vehiculo') ?? '',
            'busqueda' => $this->request->getGet('search') ?? '',
            'asignadas_a_mi' => $this->request->getGet('asignadas_a_mi') ?? false
        ];

        // Si es un técnico o está marcado "asignadas a mí"
        if ($rol === 'TECNICO' || $filtros['asignadas_a_mi']) {
            $filtros['id_asignado_a'] = $usuarioId;
        }

        // Paginación simple
        $page = max(1, (int)($this->request->getGet('page') ?? 1));
        $perPage = 12;

        // Total y registros
        $totalRecords = $this->solicitudModel->buscarSolicitudes($filtros, true);
        $solicitudes = $this->solicitudModel->buscarSolicitudes($filtros, false, $perPage, ($page - 1) * $perPage)
            ->get()->getResultArray();

        // Obtener datos para los filtros
        $vehiculos = $this->vehiculoModel->where('id_empresa', $empresaId)
                                       ->where('estado', 'ACTIVO')
                                       ->findAll();

        $data = [
            'title' => 'Solicitudes de Mantenimiento',
            'filtros' => $filtros,
            'solicitudes' => $solicitudes,
            'vehiculos' => $vehiculos,
            'totalRecords' => $totalRecords,
            'page' => $page,
            'perPage' => $perPage,
            'estados' => [
                'PENDIENTE' => 'Pendiente',
                'EN_REVISION' => 'En Revisión',
                'APROBADA' => 'Aprobada',
                'EN_PROCESO' => 'En Proceso',
                'EN_PAUSA' => 'En Pausa',
                'COMPLETADA' => 'Completada',
                'CANCELADA' => 'Cancelada',
                'RECHAZADA' => 'Rechazada'
            ]
        ];

        return view('solicitudes/index', $data);
    }

    // Obtener datos para DataTables
    public function getData()
    {
        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');
        $rol = $this->session->get('rol_name');

        if (!$empresaId || !$usuarioId) {
            return $this->response->setJSON(['error' => 'No autenticado'])->setStatusCode(401);
        }

        // Obtener parámetros de DataTables
        $draw = $this->request->getPost('draw') ?? 1;
        $start = (int)($this->request->getPost('start') ?? 0);
        $length = (int)($this->request->getPost('length') ?? 10);
        $search = $this->request->getPost('search')['value'] ?? '';

        $columnasOrden = [
            's.codigo_consecutivo',
            's.fecha_solicitud',
            'v.placa',
            'u1.nombre',
            'tp.nombre',
            's.descripcion',
            's.estado',
            's.id'
        ];
        $orderColumn = (int)($this->request->getPost('order')[0]['column'] ?? 0);
        $orderDir = ($this->request->getPost('order')[0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
        $orden = $columnasOrden[$orderColumn] ?? 's.fecha_solicitud';

        // Configurar filtros
        $filtros = [
            'id_empresa' => $empresaId,
            'busqueda' => $search,
            'estado' => $this->request->getPost('estado'),
            'prioridad' => $this->request->getPost('prioridad'),
            'fecha_desde' => $this->request->getPost('fecha_desde'),
            'fecha_hasta' => $this->request->getPost('fecha_hasta'),
            'id_vehiculo' => $this->request->getPost('id_vehiculo'),
            'id_asignado_a' => $this->request->getPost('id_asignado_a'),
            'orden' => $orden,
            'direccion' => $orderDir
        ];

        // Si es un técnico o está marcado "asignadas a mí", solo mostrar sus solicitudes
        if ($rol === 'TECNICO' || $this->request->getPost('asignadas_a_mi')) {
            $filtros['id_asignado_a'] = $usuarioId;
        }

        try {
            // Usar el método de búsqueda avanzada
            $totalRecords = $this->solicitudModel->buscarSolicitudes($filtros, true);

            // Obtener datos con paginación
            $solicitudes = $this->solicitudModel->buscarSolicitudes($filtros, false, $length, $start)->get()->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error en getData: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw' => (int)$draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Error al obtener datos'
            ]);
        }

        // Formatear datos para DataTables
        $data = [];
        foreach ($solicitudes as $solicitud) {
            $estado = $solicitud['estado'] ?? 'PENDIENTE';
            $prioridad = (int)($solicitud['prioridad'] ?? 2);
            $badgeClass = ['success', 'warning', 'orange', 'danger'][$prioridad - 1] ?? 'secondary';
            $prioridadLabel = ['Baja', 'Media', 'Alta', 'Crítica'][$prioridad - 1] ?? 'Media';

            $badgeEstado = match (strtoupper($estado)) {
                'PENDIENTE' => '<span class="badge bg-warning">Pendiente</span>',
                'EN_PROCESO' => '<span class="badge bg-info">En Proceso</span>',
                'APROBADA', 'APROBADAS' => '<span class="badge bg-success">Aprobada</span>',
                'COMPLETADA', 'FINALIZADA' => '<span class="badge bg-primary">Completada</span>',
                'CANCELADA' => '<span class="badge bg-secondary">Cancelada</span>',
                'RECHAZADA' => '<span class="badge bg-dark">Rechazada</span>',
                default => '<span class="badge bg-light text-dark">' . esc($estado) . '</span>'
            };

            $acciones = '<div class="btn-group btn-group-sm">';
            $acciones .= '<a href="' . base_url('solicitudes/show/' . $solicitud['id']) . '" class="btn btn-outline-primary" title="Ver"><i class="fas fa-eye"></i></a>';
            if (!in_array($estado, ['COMPLETADA', 'CANCELADA', 'RECHAZADA', 'FINALIZADA'])) {
                $acciones .= '<a href="' . base_url('ordenes-trabajo/edit/' . $solicitud['id']) . '" class="btn btn-outline-success" title="Editar"><i class="fas fa-edit"></i></a>';
            }
            $acciones .= '</div>';

            $data[] = [
                'id' => $solicitud['id'],
                'codigo_consecutivo' => $solicitud['codigo_consecutivo'],
                'fecha_solicitud' => $solicitud['fecha_solicitud'] ?? $solicitud['fechaSolicitud'] ?? '',
                'placa' => $solicitud['placa'] ?? 'N/A',
                'solicitante' => $solicitud['nombre_solicitante'] ?? 'N/A',
                'tipo_problema' => $solicitud['tipo_problema'] ?? 'N/A',
                'descripcion' => character_limiter($solicitud['descripcion'] ?? '', 80),
                'estado' => $badgeEstado,
                'acciones' => $acciones,
                'DT_RowData' => ['id' => $solicitud['id']]
            ];
        }

        return $this->response->setJSON([
            'draw' => (int)$draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    /**
     * Buscar solicitudes para autocompletado/AJAX
     */
    public function buscar()
    {
        // Verificar autenticación básica
        if (!$this->session->has('user_id')) {
            return $this->response->setJSON(['error' => 'No tiene permisos para acceder a esta función']);
        }

        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');
        $rol = $this->session->get('rol_name');

        $termino = $this->request->getGet('q');

        if (empty($termino) || strlen($termino) < 2) {
            return $this->response->setJSON([
                'success' => true,
                'data' => []
            ]);
        }

        // Configurar filtros para la búsqueda
        $filtros = [
            'id_empresa' => $empresaId,
            'search' => $termino,
            'estado' => 'EN_PROCESO' // Solo solicitudes en proceso para asignación
        ];

        // Si es un técnico, solo mostrar solicitudes asignadas a él o sin asignar
        if ($rol === 'TECNICO') {
            $filtros['id_asignado_a'] = [$usuarioId, null];
        }

        // Obtener solicitudes que coincidan con el término de búsqueda
        $solicitudes = $this->solicitudModel->buscarSolicitudes($filtros, false, 20, 0)->get()->getResultArray();

        // Formatear datos para respuesta
        $resultados = [];
        foreach ($solicitudes as $solicitud) {
            $resultados[] = [
                'id' => $solicitud['id'],
                'codigo_consecutivo' => $solicitud['codigo_consecutivo'],
                'descripcion' => $solicitud['descripcion'] ?? '',
                'estado' => $solicitud['estado'] ?? 'SIN ESTADO',
                'placa' => $solicitud['placa'] ?? 'N/A',
                'fecha_solicitud' => $solicitud['fecha_solicitud'] ?? $solicitud['fechaSolicitud'] ?? '',
                'tipo_problema' => $solicitud['tipo_problema'] ?? 'N/A',
                'prioridad' => $solicitud['prioridad'] ?? 'MEDIA'
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $resultados
        ]);
    }

    /**
     * Wizard para crear nueva solicitud de mantenimiento
     */
    public function create()
    {
        $empresaId = $this->session->get('empresa_id');
        if (!$empresaId) {
            return redirect()->to('/auth/login');
        }

        // Obtener tipos de problema de la empresa
        $tiposProblema = $this->tipoProblemaModel
            ->where('id_empresa', $empresaId)
            ->where('estado', 'ACTIVO')
            ->findAll();

        // Fallback: usar catalogo CAT-0010 si no hay tipos_problema
        if (empty($tiposProblema)) {
            $tiposProblema = $this->catalogoModel
                ->where('idempresa', $empresaId)
                ->where('codigo LIKE', 'CAT-0010%')
                ->where('estado', 1)
                ->findAll();
        }

        $data = [
            'title' => 'Nueva Solicitud de Mantenimiento',
            'tiposProblema' => $tiposProblema,
        ];

        return view('solicitudes/create', $data);
    }

    /**
     * Buscar vehiculo por placa, numero de motor o carnet de conductor
     */
    public function buscarVehiculo()
    {
        $empresaId = $this->session->get('empresa_id');
        if (!$empresaId) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autenticado']);
        }

        $termino = trim($this->request->getGet('q') ?? '');

        if (empty($termino) || strlen($termino) < 2) {
            return $this->response->setJSON(['success' => true, 'data' => []]);
        }

        // Buscar por placa o numero de motor
        $vehiculos = $this->vehiculoModel
            ->where('id_empresa', $empresaId)
            ->groupStart()
                ->like('placa', $termino)
                ->orLike('numero_motor', $termino)
            ->groupEnd()
            ->where('estado', 'ACTIVO')
            ->findAll(10);

        // Buscar por carnet de conductor asignado
        $conductores = $this->conductorModel
            ->where('id_empresa', $empresaId)
            ->where('estado', 'ACTIVO')
            ->like('carnet', $termino)
            ->findAll(10);

        $idsConductores = array_column($conductores, 'id');
        if (!empty($idsConductores)) {
            $vehiculosConductor = $this->vehiculoModel
                ->where('id_empresa', $empresaId)
                ->whereIn('id_conductor', $idsConductores)
                ->where('estado', 'ACTIVO')
                ->findAll(10);

            // Merge sin duplicados por id
            $map = [];
            foreach ($vehiculos as $v) {
                $map[$v['id']] = $v;
            }
            foreach ($vehiculosConductor as $v) {
                $map[$v['id']] = $v;
            }
            $vehiculos = array_values($map);
        }

        // Enriquecer con datos del conductor
        $resultados = [];
        foreach ($vehiculos as $vehiculo) {
            $conductor = null;
            if (!empty($vehiculo['id_conductor'])) {
                $conductor = $this->conductorModel->find($vehiculo['id_conductor']);
            }

            $resultados[] = [
                'id' => $vehiculo['id'],
                'placa' => $vehiculo['placa'],
                'marca' => $vehiculo['marca'] ?? '',
                'modelo' => $vehiculo['modelo'] ?? '',
                'anio' => $vehiculo['anio'] ?? '',
                'kilometraje' => $vehiculo['kilometraje'] ?? 0,
                'numero_motor' => $vehiculo['numero_motor'] ?? '',
                'conductor' => $conductor ? trim($conductor['nombre'] . ' ' . $conductor['apellido']) : 'Sin conductor asignado',
                'carnet' => $conductor['carnet'] ?? '',
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $resultados
        ]);
    }

    /**
     * Guardar solicitud desde wizard
     */
    public function store()
    {
        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('user_id');

        if (!$empresaId || !$usuarioId) {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'id_vehiculo' => 'required|is_natural_no_zero',
            'tipo_mantenimiento' => 'required|in_list[PREVENTIVO,CORRECTIVO,EMERGENCIA]',
            'id_tipo_problema' => 'required|is_natural_no_zero',
            'prioridad' => 'required|in_list[1,2,3,4]',
            'descripcion' => 'required|min_length[10]',
            'ubicacion' => 'permit_empty|string|max_length[255]',
            'condicion_movilidad' => 'permit_empty|in_list[OPERATIVO,INMOVILIZADO,ARRASTRE]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Verificar vehiculo pertenece a la empresa y esta activo o en mantenimiento
        $vehiculo = $this->vehiculoModel
            ->where('id', $this->request->getPost('id_vehiculo'))
            ->where('id_empresa', $empresaId)
            ->whereIn('estado', ['ACTIVO', 'EN MANTENIMIENTO'])
            ->first();

        if (!$vehiculo) {
            return redirect()->back()->withInput()->with('error', 'Vehiculo no valido o no pertenece a la empresa');
        }

        // Procesar evidencia
        $foto = $this->request->getFile('evidencia');
        $fotoPath = null;
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $uploadDir = ROOTPATH . 'public/uploads/solicitudes/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            $newName = $foto->getRandomName();
            $foto->move($uploadDir, $newName);
            $fotoPath = 'uploads/solicitudes/' . $newName;
        }

        // Generar codigo consecutivo
        $ultimo = $this->solicitudModel->selectMax('id')->first();
        $numero = $ultimo ? ((int)$ultimo['id'] + 1) : 1;
        $codigo = 'SOL-' . str_pad($numero, 5, '0', STR_PAD_LEFT);

        $data = [
            'id_empresa' => $empresaId,
            'codigo_consecutivo' => $codigo,
            'id_vehiculo' => $this->request->getPost('id_vehiculo'),
            'id_solicitante' => $usuarioId,
            'id_tipo_problema' => $this->request->getPost('id_tipo_problema'),
            'tipo_mantenimiento' => $this->request->getPost('tipo_mantenimiento'),
            'descripcion' => $this->request->getPost('descripcion'),
            'prioridad' => $this->request->getPost('prioridad'),
            'estado' => 'PENDIENTE',
            'ubicacion' => $this->request->getPost('ubicacion'),
            'condicion_movilidad' => $this->request->getPost('condicion_movilidad'),
            'url_foto' => $fotoPath,
            'solicitante' => $this->session->get('nombre') ?? 'Usuario ' . $usuarioId,
            'fecha_solicitud' => date('Y-m-d H:i:s'),
            'usuario_crea' => $usuarioId,
        ];

        try {
            $id = $this->solicitudModel->insert($data);
            if (!$id) {
                $errors = $this->solicitudModel->errors();
                return redirect()->back()->withInput()->with('error', 'Error al guardar: ' . implode(', ', $errors ?: ['desconocido']));
            }

            $idVehiculo = (int)$this->request->getPost('id_vehiculo');
            $prioridad = (int)$this->request->getPost('prioridad');
            $tipoMantenimiento = $this->request->getPost('tipo_mantenimiento');
            $condicionMovilidad = $this->request->getPost('condicion_movilidad');

            // Si la solicitud indica que el vehiculo no debe usarse, marcarlo como EN MANTENIMIENTO
            $requiereInmovilizar = ($prioridad === 4) || ($tipoMantenimiento === 'EMERGENCIA') || ($condicionMovilidad === 'INMOVILIZADO');

            if ($requiereInmovilizar && $vehiculo['estado'] !== 'EN MANTENIMIENTO') {
                $this->vehiculoModel->update($idVehiculo, [
                    'estado' => 'EN MANTENIMIENTO',
                    'motivo_inactividad' => 'Solicitud de mantenimiento ' . $codigo . ' generada con prioridad ' . $prioridad,
                    'fechaUpdate' => date('Y-m-d H:i:s'),
                    'usuarioEdita' => $usuarioId,
                ]);

                $db = \Config\Database::connect();
                $db->table('historial_estado_vehiculo')->insert([
                    'id_empresa' => $empresaId,
                    'id_vehiculo' => $idVehiculo,
                    'estado' => 'EN MANTENIMIENTO',
                    'motivo' => 'Cambio automático por solicitud ' . $codigo,
                    'fecha_inicio' => date('Y-m-d H:i:s'),
                    'fechaRegistro' => date('Y-m-d H:i:s'),
                    'usuarioCrea' => $usuarioId,
                    'usuarioEdita' => $usuarioId,
                ]);

                $mensajeAdicional = ' El vehículo fue marcado como "En Mantenimiento" y no podrá usarse hasta nueva orden.';
            } else {
                $mensajeAdicional = '';
            }

            return redirect()->to('/solicitudes/show/' . $id)->with('success', 'Solicitud de mantenimiento creada exitosamente.' . $mensajeAdicional);
        } catch (\Exception $e) {
            log_message('error', 'Error al crear solicitud: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al crear la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalle de solicitud
     */
    public function show($id = null)
    {
        $empresaId = $this->session->get('empresa_id');
        if (!$empresaId || !$id) {
            return redirect()->to('/auth/login');
        }

        $solicitud = $this->solicitudModel
            ->where('id', $id)
            ->where('id_empresa', $empresaId)
            ->first();

        if (!$solicitud) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Solicitud no encontrada');
        }

        $vehiculo = $this->vehiculoModel->find($solicitud['id_vehiculo']);
        $conductor = null;
        if ($vehiculo && !empty($vehiculo['id_conductor'])) {
            $conductor = $this->conductorModel->find($vehiculo['id_conductor']);
        }
        $tipoProblema = null;
        if (!empty($solicitud['id_tipo_problema'])) {
            $tipoProblema = $this->tipoProblemaModel->find($solicitud['id_tipo_problema']);
        }

        $tecnico = null;
        if (!empty($solicitud['id_asignado'])) {
            $tecnico = $this->usuarioModel->find($solicitud['id_asignado']);
        }

        $data = [
            'title' => 'Solicitud #' . $solicitud['codigo_consecutivo'],
            'solicitud' => $solicitud,
            'vehiculo' => $vehiculo,
            'conductor' => $conductor,
            'tipoProblema' => $tipoProblema,
            'tecnico' => $tecnico,
            'sugerencia' => $this->generarSugerenciaMantenimiento(
                $solicitud,
                $tipoProblema['nombre'] ?? null
            ),
        ];

        return view('solicitudes/show', $data);
    }

    /**
     * Formulario para supervisor asignar técnico a una solicitud
     */
    public function asignar($id = null)
    {
        $empresaId = $this->session->get('empresa_id');
        $rol = strtolower((string)$this->session->get('rol_name'));

        if (!$empresaId || !$id) {
            return redirect()->to('/auth/login');
        }

        if (!in_array($rol, ['administrador', 'supervisor'])) {
            return redirect()->to('/solicitudes')->with('error', 'No tiene permisos para asignar técnicos.');
        }

        $solicitud = $this->solicitudModel
            ->where('id', $id)
            ->where('id_empresa', $empresaId)
            ->first();

        if (!$solicitud) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Solicitud no encontrada');
        }

        // Obtener técnicos disponibles
        $tecnicos = $this->usuarioModel
            ->where('id_empresa', $empresaId)
            ->where('estado', 'ACTIVO')
            ->whereIn('id_rol', [2, 5]) // Mecanico o Tecnico
            ->findAll();

        $data = [
            'title' => 'Asignar técnico - ' . $solicitud['codigo_consecutivo'],
            'solicitud' => $solicitud,
            'tecnicos' => $tecnicos,
        ];

        return view('solicitudes/asignar', $data);
    }

    /**
     * Guardar asignación de técnico
     */
    public function guardarAsignacion($id = null)
    {
        $empresaId = $this->session->get('empresa_id');
        $rol = strtolower((string)$this->session->get('rol_name'));
        $usuarioId = $this->session->get('user_id');

        if (!$empresaId || !$id) {
            return redirect()->to('/auth/login');
        }

        if (!in_array($rol, ['administrador', 'supervisor'])) {
            return redirect()->to('/solicitudes')->with('error', 'No tiene permisos para asignar técnicos.');
        }

        $solicitud = $this->solicitudModel
            ->where('id', $id)
            ->where('id_empresa', $empresaId)
            ->first();

        if (!$solicitud) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Solicitud no encontrada');
        }

        $idTecnico = $this->request->getPost('id_tecnico');
        $fechaAsignacion = $this->request->getPost('fecha_asignacion');
        $observacionesAsignacion = trim($this->request->getPost('observaciones_asignacion') ?? '');

        if (empty($idTecnico)) {
            return redirect()->back()->withInput()->with('error', 'Debe seleccionar un técnico.');
        }

        if (empty($fechaAsignacion)) {
            return redirect()->back()->withInput()->with('error', 'Debe indicar la fecha de asignación.');
        }

        $tecnico = $this->usuarioModel->find($idTecnico);
        if (!$tecnico || $tecnico['id_empresa'] != $empresaId) {
            return redirect()->back()->withInput()->with('error', 'Técnico no válido.');
        }

        $observacionesActuales = trim($solicitud['observaciones'] ?? '');
        $nuevaObservacion = '';
        if (!empty($observacionesAsignacion)) {
            $nuevaObservacion = empty($observacionesActuales)
                ? "[Asignación: " . date('d/m/Y H:i', strtotime($fechaAsignacion)) . "] " . $observacionesAsignacion
                : $observacionesActuales . "\n[Asignación: " . date('d/m/Y H:i', strtotime($fechaAsignacion)) . "] " . $observacionesAsignacion;
        }

        $updateData = [
            'id_asignado' => $idTecnico,
            'fecha_asignacion' => $fechaAsignacion,
            'estado' => 'ASIGNADA',
            'usuario_actualiza' => $usuarioId,
        ];
        if (!empty($nuevaObservacion)) {
            $updateData['observaciones'] = $nuevaObservacion;
        }

        $this->solicitudModel->update($id, $updateData);

        return redirect()->to('/solicitudes/show/' . $id)->with('success', 'Técnico asignado correctamente.');
    }

    /**
     * Reporte / hoja de detalle de la solicitud (vista para imprimir)
     */
    public function reporte($id = null)
    {
        $empresaId = $this->session->get('empresa_id');
        if (!$empresaId || !$id) {
            return redirect()->to('/auth/login');
        }

        $solicitud = $this->solicitudModel
            ->where('id', $id)
            ->where('id_empresa', $empresaId)
            ->first();

        if (!$solicitud) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Solicitud no encontrada');
        }

        $vehiculo = $this->vehiculoModel->find($solicitud['id_vehiculo']);
        $conductor = null;
        if ($vehiculo && !empty($vehiculo['id_conductor'])) {
            $conductor = $this->conductorModel->find($vehiculo['id_conductor']);
        }
        $tipoProblema = null;
        if (!empty($solicitud['id_tipo_problema'])) {
            $tipoProblema = $this->tipoProblemaModel->find($solicitud['id_tipo_problema']);
        }
        $tecnico = null;
        if (!empty($solicitud['id_asignado'])) {
            $tecnico = $this->usuarioModel->find($solicitud['id_asignado']);
        }

        $empresa = $this->empresaModel->find($empresaId);

        $data = [
            'title' => 'Reporte de Solicitud ' . $solicitud['codigo_consecutivo'],
            'solicitud' => $solicitud,
            'vehiculo' => $vehiculo,
            'conductor' => $conductor,
            'tipoProblema' => $tipoProblema,
            'tecnico' => $tecnico,
            'empresa' => $empresa,
            'sugerencia' => $this->generarSugerenciaMantenimiento(
                $solicitud,
                $tipoProblema['nombre'] ?? null
            ),
        ];

        return view('solicitudes/reporte', $data);
    }

    /**
     * Genera una sugerencia de acción según tipo de problema, prioridad,
     * condición de movilidad y tipo de mantenimiento.
     */
    private function generarSugerenciaMantenimiento(array $solicitud, ?string $nombreProblema): string
    {
        $prioridad = (int)($solicitud['prioridad'] ?? 2);
        $tipoMantenimiento = $solicitud['tipo_mantenimiento'] ?? '';
        $condicion = $solicitud['condicion_movilidad'] ?? 'OPERATIVO';
        $problema = $nombreProblema ?? 'General';

        if ($tipoMantenimiento === 'EMERGENCIA' || $prioridad === 4 || $condicion === 'INMOVILIZADO') {
            $texto = 'Se recomienda inmovilizar el vehículo de inmediato y derivarlo a taller para diagnóstico urgente.';
        } elseif ($prioridad === 3) {
            $texto = 'Se recomienda agendar la revisión en las próximas 24 horas para evitar mayores daños.';
        } elseif ($prioridad === 2) {
            $texto = 'Se recomienda programar la revisión durante la próxima semana.';
        } else {
            $texto = 'Puede incluirse en la próxima programación de mantenimiento preventivo.';
        }

        $recomendaciones = [
            'Mecanico'    => ' Verificar nivel de aceite, correas, mangueras y estado general del motor.',
            'Electrico'   => ' Revisar batería, alternador, fusibles y sistema de carga.',
            'Neumaticos'  => ' Inspeccionar presión, desgaste y posibles daños en llantas.',
            'Carroceria'  => ' Evaluar daños estructurales y realizar ajustes si afecta la seguridad.',
            'Frenos'      => ' Revisar zapatas, discos, líquido de frenos y sistema ABS.',
            'Suspension'  => ' Verificar amortiguadores, ballestas y terminaciones de dirección.',
            'Motor'       => ' Realizar diagnóstico computarizado y pruebas de compresión.',
            'Transmision' => ' Revisar nivel y estado del aceite de transmisión, y posibles tirones.',
        ];

        if (isset($recomendaciones[$problema])) {
            $texto .= $recomendaciones[$problema];
        }

        return $texto;
    }
}
