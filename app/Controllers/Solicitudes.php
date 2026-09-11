<?php

namespace App\Controllers;

use App\Models\SolicitudModel;
use App\Models\VehiculoModel;
use App\Models\UsuarioModel;
use App\Models\TipoProblemaModel;
use App\Models\SolicitudDocumentoModel;
use App\Models\SolicitudHistorialModel;

class Solicitudes extends BaseController
{
    protected $solicitudModel;
    protected $vehiculoModel;
    protected $usuarioModel;
    protected $tipoProblemaModel;
    protected $session;

    public function __construct()
    {
        $this->solicitudModel = new SolicitudModel();
        $this->vehiculoModel = new VehiculoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->tipoProblemaModel = new TipoProblemaModel();
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
}
