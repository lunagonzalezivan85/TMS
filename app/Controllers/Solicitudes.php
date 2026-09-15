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

class Solicitudes extends BaseController
{
    protected $solicitudModel;
    protected $vehiculoModel;
    protected $usuarioModel;
    protected $tipoProblemaModel;
    protected $conductorModel;
    protected $catalogoModel;
    protected $session;

    public function __construct()
    {
        $this->solicitudModel = new SolicitudModel();
        $this->vehiculoModel = new VehiculoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->tipoProblemaModel = new TipoProblemaModel();
        $this->conductorModel = new ConductorModel();
        $this->catalogoModel = new CatalogoModel();
        $this->session = session();

        helper(['form', 'url', 'date']);
    }

    // Listar todas las solicitudes
    public function index()
    {

        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('usuario_id');
        $rol = $this->session->get('rol_nombre');

        // Obtener filtros
        $filtros = [
            'estado' => $this->request->getGet('estado') ?? '',
            'fecha_desde' => $this->request->getGet('fecha_desde') ?? '',
            'fecha_hasta' => $this->request->getGet('fecha_hasta') ?? '',
            'id_vehiculo' => $this->request->getGet('id_vehiculo') ?? '',
            'search' => $this->request->getGet('search') ?? '',
            'asignadas_a_mi' => $this->request->getGet('asignadas_a_mi') ?? false
        ];

        // Obtener datos para los filtros
        $vehiculos = $this->vehiculoModel->where('id_empresa', $empresaId)
                                       ->where('estado', 'ACTIVO')
                                       ->findAll();

        // Obtener técnicos para filtro (solo para administradores/jefes)
        $tecnicos = [];
        if (in_array($rol, ['ADMINISTRADOR', 'JEFE_TALLER'])) {
            $tecnicos = $this->usuarioModel->where('id_empresa', $empresaId)
                                         ->whereIn('rol_nombre', ['TECNICO', 'JEFE_TALLER'])
                                         ->findAll();
        }

        $data = [
            'title' => 'Solicitudes de Mantenimiento',
            'filtros' => $filtros,
            'vehiculos' => $vehiculos,
            'tecnicos' => $tecnicos,
            'estados' => [
                'PENDIENTE' => 'Pendiente',
                'EN_REVISION' => 'En Revisión',
                'APROBADA' => 'Aprobada',
                'EN_PROCESO' => 'En Proceso',
                'EN_PAUSA' => 'En Pausa',
                'COMPLETADA' => 'Completada',
                'CANCELADA' => 'Cancelada',
                'RECHAZADA' => 'Rechazada'
            ],
            'prioridades' => [
                'BAJA' => 'Baja',
                'MEDIA' => 'Media',
                'ALTA' => 'Alta',
                'CRITICA' => 'Crítica'
            ]
        ];

        return view('solicitudes/index', $data);
    }

    // Obtener datos para DataTables
    public function getData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('usuario_id');
        $rol = $this->session->get('rol_nombre');

        // Obtener parámetros de DataTables
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'] ?? '';

        // Configurar filtros
        $filtros = [
            'id_empresa' => $empresaId,
            'search' => $search,
            'estado' => $this->request->getPost('estado'),
            'prioridad' => $this->request->getPost('prioridad'),
            'fecha_desde' => $this->request->getPost('fecha_desde'),
            'fecha_hasta' => $this->request->getPost('fecha_hasta'),
            'id_vehiculo' => $this->request->getPost('id_vehiculo'),
            'id_asignado_a' => $this->request->getPost('id_asignado_a'),
            'orden' => $this->request->getPost('order[0][column]'),
            'direccion' => $this->request->getPost('order[0][dir]')
        ];

        // Si es un técnico o está marcado "asignadas a mí", solo mostrar sus solicitudes
        if ($rol === 'TECNICO' || $this->request->getPost('asignadas_a_mi')) {
            $filtros['id_asignado_a'] = $usuarioId;
        }

        // Usar el método de búsqueda avanzada
        $totalRecords = $this->solicitudModel->buscarSolicitudes($filtros, true);

        // Obtener datos con paginación
        $solicitudes = $this->solicitudModel->buscarSolicitudes($filtros, false, $length, $start)->get()->getResultArray();

        // Formatear datos para DataTables
        $data = [];
        foreach ($solicitudes as $solicitud) {
            $data[] = [
                'id' => $solicitud['id'],
                'codigo_consecutivo' => $solicitud['codigo_consecutivo'],
                'fecha_solicitud' => $solicitud['fecha_solicitud'] ?? $solicitud['fechaSolicitud'],
                'fecha_limite' => $solicitud['fecha_limite'] ?? null,
                'placa' => $solicitud['placa'] ?? 'N/A',
                'solicitante' => $solicitud['nombre_solicitante'] ?? 'N/A',
                'asignado_a' => $solicitud['nombre_asignado'] ?? 'Sin asignar',
                'tipo_problema' => $solicitud['tipo_problema'] ?? 'N/A',
                'prioridad' => $this->getBadgePrioridad($solicitud['prioridad'] ?? 'MEDIA'),
                'descripcion' => character_limiter($solicitud['descripcion'] ?? '', 100),
                'estado' => $this->getBadgeEstado($solicitud['estado']),
                'dias_restantes' => $solicitud['dias_restantes'] ?? null,
                'acciones' => $this->getAcciones($solicitud)
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
        $rol = $this->session->get('rol_nombre');

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

        // Verificar vehiculo pertenece a la empresa y esta activo
        $vehiculo = $this->vehiculoModel
            ->where('id', $this->request->getPost('id_vehiculo'))
            ->where('id_empresa', $empresaId)
            ->where('estado', 'ACTIVO')
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

            return redirect()->to('/solicitudes/show/' . $id)->with('success', 'Solicitud de mantenimiento creada exitosamente');
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

        $data = [
            'title' => 'Solicitud #' . $solicitud['codigo_consecutivo'],
            'solicitud' => $solicitud,
            'vehiculo' => $vehiculo,
            'conductor' => $conductor,
            'tipoProblema' => $tipoProblema,
        ];

        return view('solicitudes/show', $data);
    }
}
