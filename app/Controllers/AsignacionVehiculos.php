<?php

namespace App\Controllers;

use App\Models\AsignacionVehiculoModel;
use App\Models\TipoUnidadModel;
use App\Models\VehiculoModel;
use App\Models\ConductorModel;

class AsignacionVehiculos extends SecureController
{
    protected $asignacionModel;
    protected $tipoUnidadModel;
    protected $vehiculoModel;
    protected $conductorModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->asignacionModel = new AsignacionVehiculoModel();
        $this->tipoUnidadModel = new TipoUnidadModel();
        $this->vehiculoModel = new VehiculoModel();
        $this->conductorModel = new ConductorModel();
    }

    /**
     * Lista principal de asignaciones
     */
    public function index()
    {
        $this->requireAccess('asignacion-vehiculos');
        
        $data = [
            'title' => 'Asignación de Vehículos',
            'estadisticas' => $this->asignacionModel->getEstadisticasAsignaciones(),
            'tipos_unidad' => $this->tipoUnidadModel->where('estado', 'ACTIVO')->findAll()
        ];

        return view('asignacion_vehiculos/index', $data);
    }

    /**
     * Página de prueba para verificar el estado del módulo
     */
    public function test()
    {
        return view('asignacion_vehiculos/test');
    }



    /**
     * Método de prueba para verificar conectividad
     */
    public function testConnection()
    {
        try {
            // Verificar conexión a base de datos
            $db = \Config\Database::connect();
            
            // Contar registros en tabla asignacion_vehiculos
            $count = $db->table('asignacion_vehiculos')->countAllResults();
            
            // Verificar tablas relacionadas
            $vehiculosCount = $db->table('vehiculos')->countAllResults();
            $conductoresCount = $db->table('conductores')->countAllResults();
            $tipoUnidadCount = $db->table('tipo_unidad')->countAllResults();
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Conexión exitosa',
                'data' => [
                    'asignaciones' => $count,
                    'vehiculos' => $vehiculosCount,
                    'conductores' => $conductoresCount,
                    'tipos_unidad' => $tipoUnidadCount
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Error de conexión'
            ]);
        }
    }

    /**
     * Datos AJAX para DataTable
     */
    public function getData()
    {
        try {
            // Verificar si es una petición AJAX
            if (!$this->request->isAJAX()) {
                log_message('error', 'getData: Acceso no AJAX detectado');
                return $this->response->setJSON(['error' => 'Acceso no autorizado']);
            }

            // Obtener filtros
            $filtros = [
                'estado' => $this->request->getGet('estado'),
                'tipo_unidad' => $this->request->getGet('tipo_unidad'),
                'conductor' => $this->request->getGet('conductor')
            ];

            log_message('info', 'getData: Filtros aplicados: ' . json_encode($filtros));

            // Obtener asignaciones
            $asignaciones = $this->asignacionModel->getAsignacionesCompletas(null, $filtros);
            
            log_message('info', 'getData: Número de asignaciones encontradas: ' . count($asignaciones));

            $data = [];
            foreach ($asignaciones as $asignacion) {
                try {
                    $estadoBadge = $asignacion['estado'] == 'ACTIVA' 
                        ? '<span class="badge bg-success">ACTIVA</span>'
                        : '<span class="badge bg-secondary">INACTIVA</span>';

                    $fechaDesasignacion = !empty($asignacion['fecha_desasignacion']) 
                        ? date('d/m/Y', strtotime($asignacion['fecha_desasignacion']))
                        : '-';

                    $acciones = $this->generarAcciones($asignacion);

                    $data[] = [
                        'id' => $asignacion['id'] ?? 0,
                        'vehiculo' => ($asignacion['placa'] ?? 'Sin placa') . ' - ' . ($asignacion['marca'] ?? 'Sin marca') . ' ' . ($asignacion['modelo'] ?? 'Sin modelo'),
                        'conductor' => $asignacion['conductor_nombre'] ?? 'Sin conductor',
                        'tipo_unidad' => $asignacion['tipo_unidad_descripcion'] ?? 'No asignado',
                        'fecha_asignacion' => !empty($asignacion['fecha_asignacion']) ? date('d/m/Y', strtotime($asignacion['fecha_asignacion'])) : '-',
                        'fecha_desasignacion' => $fechaDesasignacion,
                        'estado' => $estadoBadge,
                        'acciones' => $acciones
                    ];
                } catch (\Exception $e) {
                    log_message('error', 'Error procesando asignación ID ' . ($asignacion['id'] ?? 'desconocido') . ': ' . $e->getMessage());
                    continue;
                }
            }

            log_message('info', 'getData: Datos procesados exitosamente. Total registros: ' . count($data));
            
            return $this->response->setJSON([
                'data' => $data,
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data)
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getData: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    /**
     * Wizard principal - redirige al step1
     */
    public function wizard()
    {
        return redirect()->to(base_url('asignacion-vehiculos/wizard/step1'));
    }

    /**
     * Wizard - Paso 1: Seleccionar tipo de unidad
     */
    public function wizardStep1()
    {
        $data = [
            'title' => 'Nueva Asignación - Seleccionar Tipo de Unidad',
            'tipos_unidad' => $this->tipoUnidadModel->where('estado', 'ACTIVO')->findAll()
        ];

        return view('asignacion_vehiculos/wizard_step1', $data);
    }

    /**
     * Wizard - Paso 2: Seleccionar vehículo
     */
    public function wizardStep2($tipoUnidadId = null)
    {
        // Si no se proporciona tipoUnidadId, redirigir al paso 1
        if (!$tipoUnidadId) {
            return redirect()->to(base_url('asignacion-vehiculos/wizard/step1'))
                           ->with('error', 'Debe seleccionar un tipo de unidad primero');
        }

        $tipoUnidad = $this->tipoUnidadModel->find($tipoUnidadId);
        if (!$tipoUnidad) {
            return redirect()->to(base_url('asignacion-vehiculos/wizard/step1'))
                           ->with('error', 'Tipo de unidad no encontrado');
        }

        $vehiculos = $this->asignacionModel->getVehiculosDisponiblesPorTipo($tipoUnidadId);

        $data = [
            'title' => 'Nueva Asignación - Seleccionar Vehículo',
            'tipo_unidad' => $tipoUnidad,
            'vehiculos' => $vehiculos,
            'tipo_unidad_id' => $tipoUnidadId
        ];

        return view('asignacion_vehiculos/wizard_step2', $data);
    }

    /**
     * Wizard - Paso 3: Seleccionar conductor y confirmar
     */
    public function wizardStep3($tipoUnidadId = null, $vehiculoId = null)
    {
        // Si no se proporcionan los parámetros, redirigir al paso 1
        if (!$tipoUnidadId || !$vehiculoId) {
            return redirect()->to(base_url('asignacion-vehiculos/wizard/step1'))
                           ->with('error', 'Debe completar los pasos anteriores');
        }

        $tipoUnidad = $this->tipoUnidadModel->find($tipoUnidadId);
        $vehiculo = $this->vehiculoModel->find($vehiculoId);

        if (!$tipoUnidad || !$vehiculo) {
            return redirect()->to(base_url('asignacion-vehiculos/wizard/step1'))
                           ->with('error', 'Datos no válidos');
        }

        // Verificar que el vehículo esté disponible
        if ($vehiculo['disponible'] !== 'DISPONIBLE') {
            return redirect()->to(base_url('asignacion-vehiculos/wizard/step2/' . $tipoUnidadId))
                           ->with('error', 'El vehículo seleccionado ya no está disponible');
        }

        $conductores = $this->asignacionModel->getConductoresDisponibles(null, $vehiculoId);

        $data = [
            'title' => 'Nueva Asignación - Seleccionar Conductor',
            'tipo_unidad' => $tipoUnidad,
            'vehiculo' => $vehiculo,
            'conductores' => $conductores,
            'tipo_unidad_id' => $tipoUnidadId,
            'vehiculo_id' => $vehiculoId
        ];

        return view('asignacion_vehiculos/wizard_step3', $data);
    }

    /**
     * Procesar la asignación
     */
    public function store()
    {
        $rules = [
            'id_vehiculo' => 'required|integer',
            'id_conductor' => 'required|integer',
            'fecha_asignacion' => 'required|valid_date',
            'motivo_asignacion' => 'required|min_length[10]|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_vehiculo' => $this->request->getPost('id_vehiculo'),
            'id_conductor' => $this->request->getPost('id_conductor'),
            'fecha_asignacion' => $this->request->getPost('fecha_asignacion'),
            'observaciones' => $this->request->getPost('motivo_asignacion')
        ];

        // Verificar que el vehículo esté disponible
        $vehiculo = $this->vehiculoModel->find($data['id_vehiculo']);
        if (!$vehiculo || $vehiculo['disponible'] !== 'DISPONIBLE') {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'El vehículo seleccionado no está disponible');
        }

        // Validación basada en el campo compuesto del vehículo
        $esCompuesto = (int)$vehiculo['compuesto'] === 1;
        $asignacionesActuales = $this->asignacionModel->contarAsignacionesActivasConductor($data['id_conductor']);
        
        if (!$esCompuesto) {
            // Si el vehículo NO es compuesto, el conductor no puede tener ninguna asignación activa
            if ($asignacionesActuales > 0) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'No se puede asignar un vehículo no compuesto a un conductor que ya tiene vehículos asignados');
            }
        } else {
            // Si el vehículo ES compuesto, verificar que:
            // 1. El conductor no tenga más de 2 vehículos asignados
            if ($asignacionesActuales >= 2) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', "El conductor ya tiene el máximo de 2 vehículos asignados (actualmente: {$asignacionesActuales})");
            }
            
            // 2. El conductor no tenga vehículos no compuestos asignados
            $tieneVehiculosNoCompuestos = $this->asignacionModel->conductorTieneVehiculosNoCompuestos($data['id_conductor']);
            if ($tieneVehiculosNoCompuestos) {
                // Obtener información de debugging
                $debugInfo = session()->getFlashdata('debug_vehiculos');
                $errorMessage = 'No se puede asignar un vehículo compuesto a un conductor que tiene vehículos no compuestos asignados.';
                
                if ($debugInfo) {
                    $errorMessage .= ' DEBUG: Conductor ID: ' . $debugInfo['conductor_id'] . '. ';
                    $errorMessage .= 'Vehículos asignados: ' . count($debugInfo['vehiculos_asignados']) . '. ';
                    foreach ($debugInfo['vehiculos_asignados'] as $v) {
                        $errorMessage .= 'Placa: ' . $v['placa'] . ' (compuesto: ' . $v['compuesto'] . '), ';
                    }
                }
                
                return redirect()->back()
                               ->withInput()
                               ->with('error', $errorMessage);
            }
        }

        // Intentar crear la asignación (las validaciones adicionales están en el modelo)
        try {
            $asignacionId = $this->asignacionModel->crearAsignacion($data);

            if ($asignacionId) {
                return redirect()->to(base_url('asignacion-vehiculos'))
                               ->with('success', 'Asignación creada exitosamente');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Error al crear la asignación');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error en store asignación: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', $e->getMessage());
        }
    }

/**
 * Ver detalles de asignación
 */
public function show($id)
{
    $asignacion = $this->asignacionModel->getAsignacionCompleta($id);
    if (!$asignacion) {
        return redirect()->to(base_url('asignacion-vehiculos'))
                       ->with('error', 'Asignación no encontrada');
    }

    $data = [
        'title' => 'Detalles de Asignación',
        'asignacion' => $asignacion
    ];

    return view('asignacion_vehiculos/show', $data);
}

    /**
     * Desasignar vehículo
     */
    public function desasignar($id)
    {
        $asignacion = $this->asignacionModel->find($id);
        if (!$asignacion) {
            return redirect()->to(base_url('asignacion-vehiculos'))
                           ->with('error', 'Asignación no encontrada');
        }

        if ($asignacion['estado'] !== 'ACTIVA') {
            return redirect()->to(base_url('asignacion-vehiculos'))
                           ->with('error', 'La asignación ya está inactiva');
        }

        $data = [
            'title' => 'Desasignar Vehículo',
            'asignacion' => $this->asignacionModel->getAsignacionCompleta($id)
        ];

        return view('asignacion_vehiculos/desasignar', $data);
    }



    /**
     * Obtener vehículos por tipo (AJAX)
     */
    public function getVehiculosPorTipo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $tipoUnidadId = $this->request->getPost('tipo_unidad_id');
        $vehiculos = $this->asignacionModel->getVehiculosDisponiblesPorTipo($tipoUnidadId);

        return $this->response->setJSON(['vehiculos' => $vehiculos]);
    }

    /**
     * Método AJAX para obtener estadísticas
     */
    public function getEstadisticas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no permitido']);
        }

        try {
            $estadisticas = $this->asignacionModel->getEstadisticas();
            return $this->response->setJSON($estadisticas);
        } catch (\Exception $e) {
            log_message('error', 'Error obteniendo estadísticas: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error interno del servidor']);
        }
    }



    /**
     * Procesar desasignación
     */
    public function processDesasignar($id = null)
    {
        if (!$id) {
            return redirect()->to('asignacion-vehiculos')->with('error', 'ID de asignación no válido');
        }

        // Validar datos
        $rules = [
            'motivo_desasignacion' => [
                'rules' => 'required|min_length[10]|max_length[500]',
                'errors' => [
                    'required' => 'El motivo de desasignación es obligatorio',
                    'min_length' => 'El motivo debe tener al menos 10 caracteres',
                    'max_length' => 'El motivo no puede exceder los 500 caracteres'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        try {
            $motivo = $this->request->getPost('motivo_desasignacion');
            $usuarioId = session()->get('usuario_id');

            // Debug: verificar datos antes de desasignar
            $this->asignacionModel->debugDesasignacion($id);
            
            log_message('info', "Iniciando desasignación desde controlador - ID: {$id}, Usuario: {$usuarioId}");

            $resultado = $this->asignacionModel->desasignarVehiculo($id, $motivo, $usuarioId);

            if ($resultado) {
                return redirect()->to('asignacion-vehiculos')
                               ->with('success', 'Vehículo desasignado correctamente');
            } else {
                return redirect()->back()
                               ->with('error', 'No se pudo completar la desasignación');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error en desasignación: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Error interno: ' . $e->getMessage());
        }
    }

    /**
     * Generar botones de acciones para cada fila
     */
    private function generarAcciones($asignacion)
    {
        $acciones = '<div class="btn-group" role="group">';
        
        // Ver detalles
        $acciones .= '<a href="' . base_url('asignacion-vehiculos/show/' . $asignacion['id']) . '" 
                        class="btn btn-sm btn-outline-info" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                      </a>';

        // Desasignar (solo si está activa)
        if ($asignacion['estado'] === 'ACTIVA') {
            $acciones .= '<a href="' . base_url('asignacion-vehiculos/desasignar/' . $asignacion['id']) . '" 
                            class="btn btn-sm btn-outline-warning" title="Desasignar">
                            <i class="fas fa-unlink"></i>
                          </a>';
        }

        $acciones .= '</div>';
        
        return $acciones;
    }
}
