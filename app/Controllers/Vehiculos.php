<?php

namespace App\Controllers;

use App\Models\VehiculoModel;
use App\Models\GenericModel;
use App\Models\CatalogoModel;
use App\Services\VehiculoService;

class Vehiculos extends SecureController
{
    protected VehiculoModel   $vehiculoModel;
    protected VehiculoService $service;
    protected $conductorModel;
    protected CatalogoModel   $catalogoModel;
    protected $db;

    public function __construct()
    {
        $this->vehiculoModel = new VehiculoModel();
        $this->service       = new VehiculoService($this->vehiculoModel);
        $this->conductorModel = GenericModel::tabla('conductores');
        $this->catalogoModel = new CatalogoModel();
        $this->db = \Config\Database::connect();
    }

    /** @deprecated — INC-008 resuelto. Ver Avances_TMS.md. */
    public function debugSqlServer()
    {
        return redirect()->to(base_url('vehiculos'));
    }

    /** @deprecated — INC-008 resuelto. Ver Avances_TMS.md. */
    public function testSqlServerSimple()
    {
        return redirect()->to(base_url('vehiculos'));
    }

    /**
     * Listar vehículos
     */
    public function index()
    {
        // Verificar sesión
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        // Cargar centros de costo desde SQL Server en sesión (cache)
        $centrosCosto = $this->service->getCentrosCostoParaSelect();
        session()->set('centros_costo_cache', $centrosCosto);

        $data = [
            'title' => 'Gestión de Vehículos - GMV',
            'page_title' => 'Gestión de Vehículos',
            'centros_costo' => $centrosCosto
        ];

        return view('vehiculos/index', $data);
    }

    /**
     * Obtener datos para DataTable (AJAX)
     */
    public function getData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $session = session();
        $empresaId = $session->get('empresa_id');
        
        // Verificar si no hay empresa ID
        if (!$empresaId) {
            return $this->response->setJSON([
                'draw' => 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'No se pudo identificar la empresa del usuario'
            ]);
        }

        // HttpClient envía JSON; getPost() solo lee form-data.
        // Leer JSON primero, fallback a getPost() para backwards compat.
        $input = $this->request->getJSON(true) ?: [];
        if (empty($input)) {
            $input = $this->request->getPost() ?: [];
        }

        // Parámetros de DataTables
        $draw   = $input['draw'] ?? 1;
        $start  = $input['start'] ?? 0;
        $length = $input['length'] ?? 100;
        $searchValue = $input['search']['value'] ?? ($input['search[value]'] ?? '');

        // Filtros adicionales
        $filtros = [];
        
        $estadoFiltro = $input['estado'] ?? '';
        if ($estadoFiltro && trim($estadoFiltro) !== '') {
            $filtros['estado'] = trim($estadoFiltro);
        }
        
        if ($searchValue && trim($searchValue) !== '') {
            $filtros['search'] = trim($searchValue);
        }

        try {
            // Utilizar el método del modelo que soporta paginación del lado del servidor
            log_message('debug', 'Vehiculos::getData - Llamando al modelo con filtros: ' . json_encode($filtros));
            $paginatedData = $this->vehiculoModel->getVehiculosConConductorPaginado($empresaId, $filtros, $start, $length);
            
            log_message('debug', 'Vehiculos::getData - Resultado modelo: Total=' . ($paginatedData['total'] ?? 'NULL') . ', Filtered=' . ($paginatedData['filtered'] ?? 'NULL') . ', Data count=' . count($paginatedData['data'] ?? []));

            $data = [];
            foreach ($paginatedData['data'] as $vehiculo) {
                $conductorInfo = $vehiculo['conductor_nombre'] 
                    ? esc($vehiculo['conductor_nombre']) 
                    : '<span class="text-muted">Sin asignar</span>';

                $data[] = [
                    'id'             => $vehiculo['id'],
                    'codigo'         => $vehiculo['codigo_unidad'] ?? '',
                    'placa'          => esc($vehiculo['placa']),
                    'vehiculo'       => esc($vehiculo['marca']) . ' ' . esc($vehiculo['modelo']),
                    'anio'           => esc($vehiculo['anio']),
                    'kilometraje'    => number_format($vehiculo['kilometraje'] ?? 0) . ' km',
                    'conductor'      => $vehiculo['conductor_nombre'] ? esc($vehiculo['conductor_nombre']) : '',
                    'estado_raw'     => $vehiculo['estado'],
                    'centro_costo'   => esc($vehiculo['codigo_centro_costo'] ?? ''),
                    'acciones'       => $this->generarAcciones($vehiculo),
                ];
            }

            return $this->response->setJSON([
                'draw' => intval($draw),
                'recordsTotal' => $paginatedData['total'],
                'recordsFiltered' => $paginatedData['filtered'],
                'data' => $data
            ]);

        } catch (\Exception $e) {
            log_message('error', '[Vehiculos::getData] ' . $e->getMessage());
            return $this->response->setJSON([
                'draw' => intval($draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Se produjo un error al procesar la solicitud.'
            ]);
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $session = session();
        $empresaId = $session->get('empresa_id');

        // Obtener tipos de unidad y operación
        $tipoUnidadModel = new \App\Models\TipoUnidadModel();
        $tipoOperacionModel = new \App\Models\TipoOperacionModel();

        $centrosCosto = $this->service->getCentrosCostoParaSelect();

        $data = [
            'title' => 'Agregar Vehículo - GMV',
            'page_title' => 'Agregar Vehículo',
            'conductores' => $this->service->getConductoresDisponibles($empresaId),
            'tiposUnidad' => $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0001'),
            'tiposOperacion' => $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0011'),
            'tipoProducto' => $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0012'),
            'centrosCosto' => $centrosCosto,
            'colores' => $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0008'),
            'tiposVehiculo' => $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0009'),
            'tiposConsumo' => $this->catalogoModel->getOpcionesTipoConsumo(),
            'validation' => session()->getFlashdata('validation')
        ];

        return view('vehiculos/create', $data);
    }

    /**
     * Procesar creación de vehículo
     */
    public function store()
    {
        if (!$this->request->is('POST')) {
            return redirect()->to(base_url('vehiculos'));
        }

        // Validar datos
        $rules = [
            'placa' => 'required|min_length[6]|max_length[20]|is_unique[vehiculos.placa]',
            'marca' => 'required|max_length[50]',
            'modelo' => 'required|max_length[50]',
            'anio' => 'required|integer|greater_than[1900]|less_than_equal_to[' . date('Y') . ']',
            'kilometraje' => 'permit_empty|integer|greater_than_equal_to[0]',
            'id_conductor' => 'permit_empty|integer',
            'idTipoUnidad' => 'permit_empty|integer',
            'idTipoOperacion' => 'permit_empty|integer',
            'codigo_centro_costo' => 'required|max_length[20]',
            'codigo_unidad' => 'required|max_length[50]|is_unique[vehiculos.codigo_unidad]',
            'numero_motor' => 'permit_empty|max_length[100]',
            'numero_chasis' => 'permit_empty|max_length[100]',
            'disponible' => 'permit_empty|in_list[0,1]',
            'id_color' => 'permit_empty|integer',
            'id_tipo_vehiculo' => 'permit_empty|integer',
            'id_tipo_producto' => 'permit_empty|integer',
            'rendimiento' => 'permit_empty|decimal',
            'max_combustible' => 'permit_empty|decimal'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->to(base_url('vehiculos/create'))->withInput()->with('validation', $this->validator);
        }

        // Preparar datos
        $data = [
            'placa' => strtoupper(trim($this->request->getPost('placa'))),
            'marca' => ucwords(trim($this->request->getPost('marca'))),
            'modelo' => ucwords(trim($this->request->getPost('modelo'))),
            'anio' => $this->request->getPost('anio'),
            'kilometraje' => $this->request->getPost('kilometraje') ?: 0,
            'id_conductor' => $this->request->getPost('id_conductor') ?: null,
            'idTipoUnidad' => $this->request->getPost('idTipoUnidad') ?: null,
            'idTipoOperacion' => $this->request->getPost('idTipoOperacion') ?: null,
            'compuesto' => $this->request->getPost('compuesto') ? 1 : 0,
            'tipo_consumo' => $this->request->getPost('tipo_consumo') ?: null,
            'codigo_unidad' => trim($this->request->getPost('codigo_unidad')) ?: null,
            'numero_motor' => trim($this->request->getPost('numero_motor')) ?: null,
            'numero_chasis' => trim($this->request->getPost('numero_chasis')) ?: null,
            'disponible' => in_array($this->request->getPost('disponible'), ['0', '1'], true) ? (int) $this->request->getPost('disponible') : 1,
            'id_color' => $this->request->getPost('id_color') ?: null,
            'id_tipo_vehiculo' => $this->request->getPost('id_tipo_vehiculo') ?: null,
            'id_tipo_producto' => $this->request->getPost('id_tipo_producto') ?: null,
            'rendimiento' => $this->request->getPost('rendimiento') !== '' ? $this->request->getPost('rendimiento') : null,
            'max_combustible' => $this->request->getPost('max_combustible') !== '' ? $this->request->getPost('max_combustible') : null,
            'codigo_centro_costo' => trim($this->request->getPost('codigo_centro_costo')),
            'estado' => 'ACTIVO'
        ];

        // Crear vehículo
        $vehiculoId = $this->service->crear($data);

        if ($vehiculoId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Vehículo creado exitosamente',
                    'vehiculo_id' => $vehiculoId
                ]);
            }
            return redirect()->to(base_url('vehiculos'))->with('success', 'Vehículo creado exitosamente');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear el vehículo'
            ]);
        }
        return redirect()->to(base_url('vehiculos/create'))->withInput()->with('error', 'Error al crear el vehículo');
    }

    /**
     * Mostrar detalles del vehículo
     */
    public function show($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $session = session();
        $vehiculo = $this->service->getParaVista((int) $id, (int) $session->get('empresa_id'));

        if (!$vehiculo) {
            return redirect()->to(base_url('vehiculos'))->with('error', 'Vehículo no encontrado o sin permisos');
        }

        $solicitudActiva = $this->verificarSolicitudMantenimientoActiva($id);
        $centroCosto     = !empty($vehiculo['codigo_centro_costo'])
            ? $this->service->getCentroCosto($vehiculo['codigo_centro_costo'])
            : null;

        // Registros de combustible del vehículo (últimos 50)
        $registroCombustibleModel = new \App\Models\RegistroCombustibleModel();
        $registrosCombustible = $registroCombustibleModel->getRegistrosConRelaciones([
            'vehiculo' => $id,
        ]);
        $registrosCombustible = array_slice($registrosCombustible, 0, 50);

        // Estadísticas de consumo del vehículo (mes actual)
        $fechaInicioMes = date('Y-m-01');
        $fechaFinMes = date('Y-m-t');
        $statsCombustible = $registroCombustibleModel->getEstadisticasConsumo($id, $fechaInicioMes, $fechaFinMes);

        // Estadísticas históricas (últimos 6 meses)
        $fechaInicio6M = date('Y-m-d', strtotime('-6 months'));
        $statsCombustible6M = $registroCombustibleModel->getEstadisticasConsumo($id, $fechaInicio6M, $fechaFinMes);

        // KPIs de rendimiento del vehículo (consulta SQL directa)
        $statsRendimiento = $this->db->query("
            SELECT 
                MIN(rc.kilometraje_anterior) AS km_inicial_global,
                MAX(rc.kilometraje_actual) AS km_final_global,
                SUM(rc.kilometraje_actual - rc.kilometraje_anterior) AS km_recorrido_tramos,
                (MAX(rc.kilometraje_actual) - MIN(rc.kilometraje_anterior)) AS km_recorrido_global,
                (
                    (MAX(rc.kilometraje_actual) - MIN(rc.kilometraje_anterior)) 
                    - SUM(rc.kilometraje_actual - rc.kilometraje_anterior)
                ) AS diferencia_descuadre,
                COUNT(rc.id) AS total_despachos_registrados,
                SUM(rc.cantidad_litros) AS total_litros_consumidos,
                ROUND(SUM(rc.cantidad_litros) / 3.78541, 2) AS total_galones_consumidos,
                ROUND(
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 
                    2
                ) AS rendimiento_km_l_tramos,
                ROUND(
                    (MAX(rc.kilometraje_actual) - MIN(rc.kilometraje_anterior)) / NULLIF(SUM(rc.cantidad_litros), 0), 
                    2
                ) AS rendimiento_km_l_global,
                COALESCE(v.rendimiento, 0) AS rendimiento_teorico,
                ROUND(AVG(rc.rendimiento), 2) AS rendimiento_promedio_registrado
            FROM vehiculos v
            INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
            WHERE v.id = ? AND rc.kilometraje_actual >= rc.kilometraje_anterior
            GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo
        ", [$id])->getRowArray();

        if (!$statsRendimiento) {
            $statsRendimiento = [
                'km_inicial_global' => 0,
                'km_final_global' => 0,
                'km_recorrido_tramos' => 0,
                'km_recorrido_global' => 0,
                'diferencia_descuadre' => 0,
                'total_despachos_registrados' => 0,
                'total_litros_consumidos' => 0,
                'total_galones_consumidos' => 0,
                'rendimiento_km_l_tramos' => 0,
                'rendimiento_km_l_global' => 0,
                'rendimiento_teorico' => $vehiculo['rendimiento'] ?? 0,
                'rendimiento_promedio_registrado' => 0,
            ];
        }

        // Documentos del vehículo
        $documentos = $this->db->table('documentos_vehiculos doc')
                              ->select('doc.*, cat.nombre as nombre_tipo_documento, cat.descripcion as descripcion_tipo_documento')
                              ->join('catalogo cat', 'cat.id = doc.tipo_documento', 'left')
                              ->where('doc.id_vehiculo', $id)
                              ->orderBy('doc.fecha_registro', 'DESC')
                              ->get()
                              ->getResultArray();

        $data = [
            'title'            => 'Detalles del Vehículo - GMV',
            'page_title'       => 'Detalles del Vehículo',
            'vehiculo'         => $vehiculo,
            'centro_costo'     => $centroCosto,
            'historial_estados' => $this->service->getHistorialEstados((int) $id),
            'solicitud_activa' => $solicitudActiva,
            'registros_combustible' => $registrosCombustible,
            'stats_combustible_mes' => $statsCombustible,
            'stats_combustible_6m'  => $statsCombustible6M,
            'stats_rendimiento'     => $statsRendimiento,
            'documentos'       => $documentos,
        ];

        return view('vehiculos/show', $data);
    }

    /**
     * Filtrar registros de combustible y estadísticas por rango de fechas (AJAX)
     */
    public function filtrarCombustible($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $input = $this->request->getJSON(true) ?: $this->request->getPost() ?: [];
        $fechaDesde = $input['fecha_desde'] ?? null;
        $fechaHasta = $input['fecha_hasta'] ?? null;

        $registroCombustibleModel = new \App\Models\RegistroCombustibleModel();

        $filtros = ['vehiculo' => $id];
        if ($fechaDesde) $filtros['fecha_desde'] = $fechaDesde;
        if ($fechaHasta) $filtros['fecha_hasta'] = $fechaHasta . ' 23:59:59';

        $registros = $registroCombustibleModel->getRegistrosConRelaciones($filtros);
        $registros = array_slice($registros, 0, 100);

        // Stats del periodo
        $stats = $registroCombustibleModel->getEstadisticasConsumo($id, $fechaDesde, $fechaHasta ? $fechaHasta . ' 23:59:59' : null);

        // Rendimiento del periodo (nueva estructura)
        $totalRecorrido = 0;
        $totalLitros = 0;
        $kmMax = 0;
        $kmMin = null;
        foreach ($registros as $reg) {
            $kmActual = (float)($reg['kilometraje_actual'] ?? 0);
            $kmAnterior = (float)($reg['kilometraje_anterior'] ?? 0);
            $diff = $kmActual - $kmAnterior;
            if ($diff > 0) $totalRecorrido += $diff;
            $totalLitros += (float)($reg['cantidad_litros'] ?? 0);
            if ($kmActual > $kmMax) $kmMax = $kmActual;
            if ($kmMin === null || $kmActual < $kmMin) $kmMin = $kmActual;
        }
        $kmInicial = $kmMin ?? 0;
        $kmFinal = $kmMax;
        $kmRecorridoTramos = $totalRecorrido;
        $kmRecorridoGlobal = $kmFinal - $kmInicial;
        $totalDespachos = count($registros);
        $rendimientoTeorico = (float)($this->vehiculoModel->find($id)['rendimiento'] ?? 0);
        $rendimientoPromedio = 0;
        $rendCount = 0;
        foreach ($registros as $reg) {
            if (!empty($reg['rendimiento'])) {
                $rendimientoPromedio += (float)$reg['rendimiento'];
                $rendCount++;
            }
        }
        $rendimientoPromedio = $rendCount > 0 ? round($rendimientoPromedio / $rendCount, 2) : 0;

        // Chart data (últimos 30 registros del periodo)
        $chartLabels = [];
        $chartLitros = [];
        $chartRendimiento = [];
        $chartRegs = array_slice(array_reverse($registros), 0, 30);
        foreach ($chartRegs as $r) {
            $chartLabels[] = date('d/m/Y', strtotime($r['fecha_registro']));
            $chartGalones[] = round((float)($r['cantidad_litros'] ?? 0) / 3.78541, 2);
            $kmA = (float)($r['kilometraje_actual'] ?? 0);
            $kmB = (float)($r['kilometraje_anterior'] ?? 0);
            $g = (float)($r['cantidad_litros'] ?? 0) / 3.78541;
            $chartRendimiento[] = ($g > 0 && $kmA > $kmB) ? round(($kmA - $kmB) / $g, 2) : 0;
        }

        // Generar HTML de la tabla
        $htmlRegistros = '';
        if (!empty($registros)) {
            foreach ($registros as $rc) {
                $tipo = strtoupper($rc['tipo'] ?? 'CONSUMO');
                $tipoClass = $tipo === 'VENTA' ? 'info' : 'success';
                $sagBadge = ($rc['enviado'] ?? 0) == 1
                    ? '<span class="badge bg-success"><i class="fas fa-check"></i></span>'
                    : '<span class="badge bg-warning text-dark"><i class="fas fa-clock"></i></span>';
                $monto = !empty($rc['monto_usd']) ? '$' . number_format((float)$rc['monto_usd'], 2) : '—';
                $kmAnt = (float)($rc['kilometraje_anterior'] ?? 0);
                $kmAct = (float)($rc['kilometraje_actual'] ?? 0);
                $recorrido = $kmAct - $kmAnt;
                $galones = (float)($rc['cantidad_litros'] ?? 0) / 3.78541;
                $rend = ($recorrido > 0 && $galones > 0) ? $recorrido / $galones : 0;
                $htmlRegistros .= '<tr>'
                    . '<td><div>' . date('d/m/Y', strtotime($rc['fecha_registro'])) . '</div>'
                    . '<small class="text-muted">' . date('H:i', strtotime($rc['fecha_registro'])) . '</small></td>'
                    . '<td><span class="badge bg-' . $tipoClass . '">' . esc($tipo) . '</span></td>'
                    . '<td class="text-end">' . number_format($kmAnt, 0) . '</td>'
                    . '<td class="text-end">' . number_format($kmAct, 0) . '</td>'
                    . '<td class="text-end fw-medium">' . ($recorrido > 0 ? number_format($recorrido, 0) : '—') . '</td>'
                    . '<td class="text-end fw-medium">' . number_format($galones, 2) . '</td>'
                    . '<td class="text-end">' . ($rend > 0 ? number_format($rend, 2) . ' KM/Gal' : '—') . '</td>'
                    . '<td class="text-end">' . $monto . '</td>'
                    . '<td><small>' . esc($rc['usuario_crea'] ?? '—') . '</small></td>'
                    . '<td>' . $sagBadge . '</td>'
                    . '</tr>';
            }
        } else {
            $htmlRegistros = '<tr><td colspan="10" class="text-center py-4 text-muted">'
                . '<i class="fas fa-gas-pump fa-2x mb-2 opacity-25 d-block"></i>'
                . 'No hay registros en el período seleccionado.</td></tr>';
        }

        return $this->response->setJSON([
            'success' => true,
            'html_registros' => $htmlRegistros,
            'count' => count($registros),
            'stats' => [
                'total_galones' => number_format((float)($stats['total_litros'] ?? 0) / 3.78541, 1),
                'total_litros' => number_format((float)($stats['total_litros'] ?? 0), 1),
                'total_registros' => (int)($stats['total_registros'] ?? 0),
                'total_monto_usd' => number_format((float)($stats['total_monto_usd'] ?? 0), 2),
                'total_kilometros' => number_format((float)($stats['total_kilometros'] ?? 0), 0),
            ],
            'rendimiento' => [
                'km_inicial_global' => number_format($kmInicial, 0),
                'km_final_global' => number_format($kmFinal, 0),
                'km_recorrido_tramos' => number_format($kmRecorridoTramos, 0),
                'km_recorrido_global' => number_format($kmRecorridoGlobal, 0),
                'total_despachos_registrados' => $totalDespachos,
                'total_galones_consumidos' => number_format($totalLitros / 3.78541, 2),
                'total_litros_consumidos' => number_format($totalLitros, 2),
                'rendimiento_teorico' => number_format($rendimientoTeorico, 2),
                'rendimiento_promedio_registrado' => number_format($rendimientoPromedio, 2),
            ],
            'chart_data' => [
                'labels' => $chartLabels,
                'galones' => $chartGalones,
                'litros' => $chartLitros,
                'rendimiento' => $chartRendimiento,
                'rend_teorico' => $rendimientoTeorico,
            ],
        ]);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $vehiculo = $this->vehiculoModel->find($id);

        if (!$vehiculo) {
            return redirect()->to(base_url('vehiculos'))->with('error', 'Vehículo no encontrado');
        }

        // Verificar que pertenece a la empresa del usuario
        $session = session();
        if ($vehiculo['id_empresa'] != $session->get('empresa_id')) {
            return redirect()->to(base_url('vehiculos'))->with('error', 'No tiene permisos para editar este vehículo');
        }

        // Obtener tipos de unidad y operación
        $tipoUnidadModel = new \App\Models\TipoUnidadModel();
        $tipoOperacionModel = new \App\Models\TipoOperacionModel();

        $centrosCosto = $this->service->getCentrosCostoParaSelect();
        
        $colores = $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0008');
        if (!empty($vehiculo['id_color']) && !array_key_exists($vehiculo['id_color'], $colores)) {
            $nombreColor = $this->catalogoModel->getNombreCatalogoPorId($vehiculo['id_color']);
            if ($nombreColor) {
                $colores[$vehiculo['id_color']] = $nombreColor . ' (Inactivo)';
            }
        }

        $tiposVehiculo = $this->catalogoModel->getOpcionesTiposVehiculo();
        if (!empty($vehiculo['id_tipo_vehiculo']) && !array_key_exists($vehiculo['id_tipo_vehiculo'], $tiposVehiculo)) {
            $nombreTipo = $this->catalogoModel->getNombreCatalogoPorId($vehiculo['id_tipo_vehiculo']);
            if ($nombreTipo) {
                $tiposVehiculo[$vehiculo['id_tipo_vehiculo']] = $nombreTipo . ' (Inactivo)';
            }
        }

        asort($colores);
        asort($tiposVehiculo);

        $data = [
            'title' => 'Editar Vehículo - GMV',
            'page_title' => 'Editar Vehículo',
            'vehiculo' => $vehiculo,
            'conductores' => $this->vehiculoModel->getConductoresDisponibles($session->get('empresa_id')),
            'tiposUnidad' => $tipoUnidadModel->where('estado', 'ACTIVO')->findAll(),
            'tiposOperacion' => $tipoOperacionModel->where('estado', 'ACTIVO')->findAll(),
            'centrosCosto' => $centrosCosto,
            'colores' => $colores,
            'tiposVehiculo' => $tiposVehiculo,
            'tiposConsumo' => $this->catalogoModel->getOpcionesTipoConsumo(),
            'validation' => session()->getFlashdata('validation')
        ];

        return view('vehiculos/edit', $data);
    }

    /**
     * Procesar actualización del vehículo
     */
    public function update($id)
    {
        if (!$this->request->is('POST')) {
            return redirect()->to(base_url('vehiculos'));
        }

        $vehiculo = $this->vehiculoModel->find($id);
        if (!$vehiculo) {
            return redirect()->to(base_url('vehiculos'))->with('error', 'Vehículo no encontrado');
        }

        // Validar datos
        $rules = [
            'placa' => "required|min_length[6]|max_length[20]|is_unique[vehiculos.placa,id,{$id}]",
            'marca' => 'required|max_length[50]',
            'modelo' => 'required|max_length[50]',
            'anio' => 'required|integer|greater_than[1900]|less_than_equal_to[' . date('Y') . ']',
            'kilometraje' => 'permit_empty|integer|greater_than_equal_to[0]',
            'id_conductor' => 'permit_empty|integer',
            'idTipoUnidad' => 'permit_empty|integer',
            'idTipoOperacion' => 'permit_empty|integer',
            'codigo_centro_costo' => 'required|max_length[20]',
            'codigo_unidad' => 'permit_empty|max_length[50]',
            'numero_motor' => 'permit_empty|max_length[100]',
            'numero_chasis' => 'permit_empty|max_length[100]',
            'disponible' => 'permit_empty|in_list[0,1]',
            'id_color' => 'permit_empty|integer',
            'id_tipo_vehiculo' => 'permit_empty|integer',
            'rendimiento' => 'permit_empty|decimal',
            'max_combustible' => 'permit_empty|decimal',
            'estado' => 'required|in_list[ACTIVO,INACTIVO,EN REPARACION]'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->to(base_url('vehiculos/edit/' . $id))->withInput()->with('validation', $this->validator);
        }

        // Preparar datos
        $data = [
            'placa' => strtoupper(trim($this->request->getPost('placa'))),
            'marca' => ucwords(trim($this->request->getPost('marca'))),
            'modelo' => ucwords(trim($this->request->getPost('modelo'))),
            'anio' => $this->request->getPost('anio'),
            'kilometraje' => $this->request->getPost('kilometraje') ?: 0,
            'id_conductor' => $this->request->getPost('id_conductor') ?: null,
            'idTipoUnidad' => $this->request->getPost('idTipoUnidad') ?: null,
            'idTipoOperacion' => $this->request->getPost('idTipoOperacion') ?: null,
            'compuesto' => $this->request->getPost('compuesto') ? 1 : 0,
            'tipo_consumo' => $this->request->getPost('tipo_consumo') ?: null,
            'codigo_unidad' => trim($this->request->getPost('codigo_unidad')) ?: null,
            'numero_motor' => trim($this->request->getPost('numero_motor')) ?: null,
            'numero_chasis' => trim($this->request->getPost('numero_chasis')) ?: null,
            'disponible' => in_array($this->request->getPost('disponible'), ['0', '1'], true) ? (int) $this->request->getPost('disponible') : $vehiculo['disponible'],
            'id_color' => $this->request->getPost('id_color') ?: null,
            'id_tipo_vehiculo' => $this->request->getPost('id_tipo_vehiculo') ?: null,
            'rendimiento' => $this->request->getPost('rendimiento') !== '' ? $this->request->getPost('rendimiento') : null,
            'max_combustible' => $this->request->getPost('max_combustible') !== '' ? $this->request->getPost('max_combustible') : null,
            'codigo_centro_costo' => trim($this->request->getPost('codigo_centro_costo')),
            'estado' => $this->request->getPost('estado'),
            'motivo_inactividad' => $this->request->getPost('motivo_inactividad'),
            'usuarioEdita' => session()->get('user_id'),
            'fechaUpdate' => date('Y-m-d H:i:s')
        ];

        // Verificar si cambió el estado para registrar en historial
        if ($vehiculo['estado'] != $data['estado']) {
            $this->vehiculoModel->cambiarEstado($id, $data['estado'], $data['motivo_inactividad']);
        }

        // Actualizar vehículo
        if ($this->vehiculoModel->update($id, $data)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Vehículo actualizado exitosamente'
                ]);
            }
            return redirect()->to(base_url('vehiculos'))->with('success', 'Vehículo actualizado exitosamente');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar el vehículo'
            ]);
        }
        return redirect()->to(base_url('vehiculos/edit/' . $id))->withInput()->with('error', 'Error al actualizar el vehículo');
    }

    /**
     * Eliminar vehículo
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $vehiculo = $this->vehiculoModel->find($id);
        if (!$vehiculo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vehículo no encontrado'
            ]);
        }

        // Verificar que pertenece a la empresa del usuario
        $session = session();
        if ($vehiculo['id_empresa'] != $session->get('empresa_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tiene permisos para eliminar este vehículo'
            ]);
        }

        // Verificar si tiene solicitudes de mantenimiento
        $solicitudes = $this->db->table('solicitudes')
                               ->where('id_vehiculo', $id)
                               ->countAllResults();

        if ($solicitudes > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se puede eliminar el vehículo porque tiene solicitudes de mantenimiento asociadas'
            ]);
        }

        if ($this->service->eliminar((int) $id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Vehículo eliminado exitosamente',
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al eliminar el vehículo',
        ]);
    }

    /**
     * Exportar vehículos de la empresa a CSV (compatible con Excel)
     */
    public function exportar()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $empresaId = (int) session()->get('empresa_id');
        $filas = $this->service->getParaExportar($empresaId);

        $headers = array_keys(\App\Services\VehiculoService::COLUMNAS_CSV);
        $csv = $this->generarCsv($headers, $filas);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="vehiculos_' . date('Ymd_His') . '.csv"')
            ->setBody($csv);
    }

    /**
     * Descargar plantilla CSV para importación masiva
     */
    public function plantillaImportacion()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $headers = array_keys(\App\Services\VehiculoService::COLUMNAS_CSV);
        $ejemplo = [[
            'placa'               => 'ABC-123',
            'marca'               => 'Toyota',
            'modelo'              => 'Hilux',
            'anio'                => date('Y'),
            'kilometraje'         => '0',
            'codigo_unidad'       => 'UNIDAD-001',
            'codigo_centro_costo' => 'CC-001',
            'numero_motor'        => 'MTR-0001',
            'numero_chasis'       => 'CHS-0001',
            'rendimiento'         => '12.5',
            'max_combustible'     => '80',
            'disponible'          => '1',
            'compuesto'           => '0',
            'estado'              => 'ACTIVO',
        ]];

        $csv = $this->generarCsv($headers, $ejemplo);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="plantilla_importacion_vehiculos.csv"')
            ->setBody($csv);
    }

    /**
     * Importación masiva de vehículos desde archivo CSV (AJAX)
     */
    public function importar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $archivo = $this->request->getFile('archivo');

        if (!$archivo || !$archivo->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe seleccionar un archivo CSV válido'
            ]);
        }

        $extension = strtolower($archivo->getClientExtension());
        if (!in_array($extension, ['csv', 'txt'], true)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Formato no soportado. Use un archivo CSV (descargue la plantilla)'
            ]);
        }

        if ($archivo->getSize() > 5 * 1024 * 1024) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El archivo supera el tamaño máximo permitido (5 MB)'
            ]);
        }

        try {
            $filas = $this->parsearCsv($archivo->getTempName());

            if (empty($filas)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El archivo está vacío o no tiene el formato de la plantilla'
                ]);
            }

            $resultado = $this->service->importar($filas);

            return $this->response->setJSON([
                'success'      => ($resultado['insertados'] + $resultado['actualizados']) > 0,
                'message'      => "Importación finalizada: {$resultado['insertados']} creado(s), {$resultado['actualizados']} actualizado(s), " . count($resultado['errores']) . " fila(s) con error",
                'insertados'   => $resultado['insertados'],
                'actualizados' => $resultado['actualizados'],
                'errores'      => $resultado['errores'],
            ]);

        } catch (\Exception $e) {
            log_message('error', '[Vehiculos::importar] ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al procesar el archivo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Genera contenido CSV con BOM UTF-8 (para que Excel respete acentos)
     */
    private function generarCsv(array $headers, array $filas): string
    {
        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, $headers, ';');
        foreach ($filas as $fila) {
            fputcsv($fh, array_values($fila), ';');
        }
        rewind($fh);
        $contenido = stream_get_contents($fh);
        fclose($fh);

        return "\xEF\xBB\xBF" . $contenido;
    }

    /**
     * Parsea un CSV (separador ; o ,) a filas asociativas usando el encabezado
     *
     * @return array<int, array<string, string>>
     */
    private function parsearCsv(string $rutaArchivo): array
    {
        $fh = fopen($rutaArchivo, 'r');
        if (!$fh) {
            throw new \RuntimeException('No se pudo leer el archivo');
        }

        // Detectar separador en la primera línea
        $primeraLinea = fgets($fh);
        if ($primeraLinea === false) {
            fclose($fh);
            return [];
        }
        // Quitar BOM si existe
        $primeraLinea = preg_replace('/^\xEF\xBB\xBF/', '', $primeraLinea);
        $separador = substr_count($primeraLinea, ';') >= substr_count($primeraLinea, ',') ? ';' : ',';

        $headers = array_map(fn($h) => strtolower(trim($h)), str_getcsv($primeraLinea, $separador));

        $filas = [];
        while (($valores = fgetcsv($fh, 0, $separador)) !== false) {
            // Ignorar filas completamente vacías
            if (count($valores) === 1 && trim((string) $valores[0]) === '') {
                continue;
            }
            $fila = [];
            foreach ($headers as $idx => $header) {
                $fila[$header] = isset($valores[$idx]) ? trim((string) $valores[$idx]) : '';
            }
            $filas[] = $fila;
        }
        fclose($fh);

        return $filas;
    }

    /**
     * Cambiar estado del vehículo (AJAX)
     */
    public function cambiarEstado()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $id = $this->request->getPost('id');
        $estado = $this->request->getPost('estado');
        $motivo = $this->request->getPost('motivo');

        $resultado = $this->service->cambiarEstado((int) $id, (string) $estado, $motivo);

        return $this->response->setJSON($resultado);
    }

    /**
     * Obtener estadísticas para dashboard
     */
    public function getEstadisticas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $session = session();
        $empresaId = $session->get('empresa_id');

        $stats = $this->vehiculoModel->getEstadisticasVehiculos($empresaId);

        return $this->response->setJSON($stats);
    }

    /**
     * Verificar si una placa está disponible (AJAX)
     */
    public function verificarPlaca()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $placa = $this->request->getPost('placa');
        $id = $this->request->getPost('id'); // Para edición
        $session = session();
        $empresaId = $session->get('empresa_id');

        if (empty($placa)) {
            return $this->response->setJSON([
                'disponible' => false,
                'mensaje' => 'La placa es requerida'
            ]);
        }

        // Verificar si la placa ya existe
        $builder = $this->db->table('vehiculos')
                           ->where('placa', $placa)
                           ->where('id_empresa', $empresaId);
        
        // Si es edición, excluir el registro actual
        if ($id) {
            $builder->where('id !=', $id);
        }
        
        $existe = $builder->countAllResults() > 0;

        return $this->response->setJSON([
            'disponible' => !$existe,
            'mensaje' => $existe ? 'Esta placa ya está registrada' : 'Placa disponible'
        ]);
    }

    /**
     * Generar badge de estado
     */
    private function getEstadoBadge($estado)
    {
        $badges = [
            'ACTIVO' => '<span class="badge bg-success">Activo</span>',
            'INACTIVO' => '<span class="badge bg-secondary">Inactivo</span>',
            'EN REPARACION' => '<span class="badge bg-warning">En Reparación</span>'
        ];

        return $badges[$estado] ?? '<span class="badge bg-secondary">Desconocido</span>';
    }

    /**
     * Generar botones de acciones con control de acceso
     */
    private function generarAcciones($vehiculo)
    {
        $id    = $vehiculo['id'];
        $items = '';

        if (hasAccess('vehiculos/show')) {
            $items .= '<li><a class="dropdown-item" href="' . base_url("vehiculos/show/$id") . '">
                           <i class="fas fa-eye text-info me-2"></i>Ver detalle
                       </a></li>';
        }

        if (hasAccess('vehiculos/edit')) {
            $items .= '<li><a class="dropdown-item" href="' . base_url("vehiculos/edit/$id") . '">
                           <i class="fas fa-edit text-primary me-2"></i>Editar
                       </a></li>';
        }

        if (hasAccess('vehiculos/documentos')) {
            $items .= '<li><a class="dropdown-item" href="' . base_url("vehiculos/documentos/$id") . '">
                           <i class="fas fa-file-alt text-secondary me-2"></i>Documentos
                       </a></li>';
        }

        if (hasAccess('vehiculos/cambiarEstado')) {
            $items .= '<li><hr class="dropdown-divider"></li>';
            if ($vehiculo['estado'] === 'ACTIVO') {
                $items .= '<li><button class="dropdown-item cambiar-estado"
                               data-id="' . $id . '" data-estado="INACTIVO">
                               <i class="fas fa-pause-circle text-warning me-2"></i>Inactivar
                           </button></li>';
            } elseif ($vehiculo['estado'] === 'EN REPARACION') {
                $items .= '<li><button class="dropdown-item cambiar-estado"
                               data-id="' . $id . '" data-estado="ACTIVO">
                               <i class="fas fa-check-circle text-success me-2"></i>Marcar activo
                           </button></li>';
            } else {
                $items .= '<li><button class="dropdown-item cambiar-estado"
                               data-id="' . $id . '" data-estado="ACTIVO">
                               <i class="fas fa-check-circle text-success me-2"></i>Activar
                           </button></li>';
            }
        }

        if (empty($items)) return '';

        return '
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle-no-caret"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    style="width:32px;padding:0;line-height:30px;border-radius:6px;">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">' . $items . '</ul>
        </div>';
    }

    /**
     * Gestión de documentos del vehículo
     */
    public function documentos($id)
    {
        $vehiculo = $this->vehiculoModel->find($id);
        if (!$vehiculo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Vehículo no encontrado');
        }

        // Verificar que pertenece a la empresa del usuario
        $session = session();
        if ($vehiculo['id_empresa'] != $session->get('empresa_id')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Vehículo no encontrado');
        }

        // Obtener documentos del vehículo con información del catálogo
        $documentos = $this->db->table('documentos_vehiculos doc')
                              ->select('doc.*, cat.nombre as nombre_tipo_documento, cat.descripcion as descripcion_tipo_documento')
                              ->join('catalogo cat', 'cat.id = doc.tipo_documento', 'left')
                              ->where('doc.id_vehiculo', $id)
                              ->orderBy('doc.fecha_registro', 'DESC')
                              ->get()
                              ->getResultArray();

        // Obtener tipos de documento del catálogo CAT-0002
        $catalogoModel = new \App\Models\CatalogoModel();
        $tiposDocumento = $catalogoModel->getCatalogosPorCodigo('CAT-0002');

        // Calcular alertas de documentos próximos a vencer
        $alertasDocumentos = [];
        $hoy = new \DateTime();
        foreach ($documentos as $doc) {
            $fechaVenc = $doc['fecha_vencimiento'] ?? null;
            if (empty($fechaVenc)) continue;
            $venc = new \DateTime($fechaVenc);
            $diasRestantes = $hoy->diff($venc)->days;
            if ($hoy > $venc) $diasRestantes = -$diasRestantes;
            $diasNotificacion = (int)($doc['notificacion'] ?? 0);
            if ($diasNotificacion > 0 && $diasRestantes >= 0 && $diasRestantes <= $diasNotificacion) {
                $alertasDocumentos[] = [
                    'nombre' => $doc['nombre_tipo_documento'] ?? 'Documento',
                    'dias_restantes' => $diasRestantes,
                    'fecha_vencimiento' => $fechaVenc,
                    'vencido' => false
                ];
            } elseif ($diasRestantes < 0) {
                $alertasDocumentos[] = [
                    'nombre' => $doc['nombre_tipo_documento'] ?? 'Documento',
                    'dias_restantes' => abs($diasRestantes),
                    'fecha_vencimiento' => $fechaVenc,
                    'vencido' => true
                ];
            }
        }

        $data = [
            'page_title' => 'Documentos del Vehículo',
            'vehiculo' => $vehiculo,
            'documentos' => $documentos,
            'tiposDocumento' => $tiposDocumento,
            'alertasDocumentos' => $alertasDocumentos
        ];

        // Si es una petición AJAX, devolver solo el contenido del contenedor de vista
        if ($this->request->isAJAX()) {
            $vista = $this->request->getGet('vista') === 'table' ? 'table' : 'cards';
            $view = 'vehiculos/partials/documentos_' . $vista;
            
            return $this->response->setJSON([
                'success' => true,
                'html' => view($view, ['documentos' => $documentos, 'vehiculo' => $vehiculo])
            ]);
        }

        // Si no es AJAX, cargar la vista completa
        return view('vehiculos/documentos', $data);
    }

    /**
     * Subir documento del vehículo (AJAX)
     */
    public function subirDocumento()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $validationRules = [
            'id_vehiculo' => 'required|numeric',
            'tipo_documento' => 'required|max_length[100]',
            'numero' => 'permit_empty|max_length[100]',
            'notificacion' => 'permit_empty|integer|greater_than_equal_to[0]',
            'archivo' => 'uploaded[archivo]|max_size[archivo,5120]|ext_in[archivo,pdf,jpg,jpeg,png,doc,docx]'
        ];

        $validationMessages = [
            'id_vehiculo' => [
                'required' => 'El ID del vehículo es requerido',
                'numeric' => 'El ID del vehículo debe ser numérico'
            ],
            'tipo_documento' => [
                'required' => 'El tipo de documento es requerido',
                'max_length' => 'El tipo de documento no puede exceder 100 caracteres'
            ],
            'numero' => [
                'max_length' => 'El número no puede exceder 100 caracteres'
            ],
            'notificacion' => [
                'integer' => 'La notificación debe ser un número entero',
                'greater_than_equal_to' => 'La notificación debe ser mayor o igual a 0'
            ],
            'archivo' => [
                'uploaded' => 'Debe seleccionar un archivo',
                'max_size' => 'El archivo no puede ser mayor a 5MB',
                'ext_in' => 'Solo se permiten archivos PDF, JPG, PNG, DOC, DOCX'
            ]
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $idVehiculo = $this->request->getPost('id_vehiculo');
        
        // Verificar que el vehículo existe y pertenece a la empresa
        $vehiculo = $this->vehiculoModel->find($idVehiculo);
        if (!$vehiculo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vehículo no encontrado'
            ]);
        }

        $session = session();
        if ($vehiculo['id_empresa'] != $session->get('empresa_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tiene permisos para subir documentos a este vehículo'
            ]);
        }

        // Procesar archivo
        $archivo = $this->request->getFile('archivo');
        if ($archivo->isValid() && !$archivo->hasMoved()) {
            // Crear directorio si no existe
            $uploadPath = WRITEPATH . 'uploads/vehiculos/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generar nombre único
            $nombreArchivo = $archivo->getRandomName();
            $rutaCompleta = $uploadPath . $nombreArchivo;
            
            // Mover archivo
            if ($archivo->move($uploadPath, $nombreArchivo)) {
                // Guardar en base de datos
                $data = [
                    'id_vehiculo' => $idVehiculo,
                    'tipo_documento' => $this->request->getPost('tipo_documento'),
                    'numero' => trim((string) $this->request->getPost('numero')) ?: null,
                    'notificacion' => $this->request->getPost('notificacion') !== '' ? (int) $this->request->getPost('notificacion') : null,
                    'nombre_archivo' => $nombreArchivo, // Usar el nombre generado
                    'ruta_archivo' => $nombreArchivo,
                    'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento') ?: null,
                    'observaciones' => $this->request->getPost('observaciones'),
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'usuario_crea' => $session->get('user_id')
                ];

                if ($this->db->table('documentos_vehiculos')->insert($data)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Documento subido exitosamente'
                    ]);
                } else {
                    // Eliminar archivo si falló la inserción
                    unlink($rutaCompleta);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al guardar la información del documento'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al subir el archivo'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo inválido o ya procesado'
            ]);
        }
    }

    /**
     * Servir archivo de documento de forma segura
     */
    public function verDocumento($id)
    {
        // Obtener documento
        $documento = $this->db->table('documentos_vehiculos')
                             ->select('documentos_vehiculos.*, vehiculos.id_empresa')
                             ->join('vehiculos', 'vehiculos.id = documentos_vehiculos.id_vehiculo')
                             ->where('documentos_vehiculos.id', $id)
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
        $rutaArchivo = WRITEPATH . 'uploads/vehiculos/' . $documento['ruta_archivo'];
        if (!file_exists($rutaArchivo)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }

        // Obtener información del archivo
        $extension = pathinfo($documento['ruta_archivo'], PATHINFO_EXTENSION);
        $nombreArchivo = $documento['nombre_archivo'];
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
        
        // Configurar headers
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . $nombreArchivo . '"');
        header('Content-Length: ' . $fileSize);
        header('Cache-Control: public, max-age=3600');
        header('Pragma: public');
        
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
        // Obtener documento
        $documento = $this->db->table('documentos_vehiculos')
                             ->select('documentos_vehiculos.*, vehiculos.id_empresa')
                             ->join('vehiculos', 'vehiculos.id = documentos_vehiculos.id_vehiculo')
                             ->where('documentos_vehiculos.id', $id)
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
        $rutaArchivo = WRITEPATH . 'uploads/vehiculos/' . $documento['ruta_archivo'];
        if (!file_exists($rutaArchivo)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado');
        }

        // Obtener información del archivo
        $extension = pathinfo($documento['ruta_archivo'], PATHINFO_EXTENSION);
        $nombreArchivo = $documento['nombre_archivo'];
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
        
        // Configurar headers para descarga
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Content-Length: ' . $fileSize);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Expires: 0');
        
        // Enviar el archivo
        readfile($rutaArchivo);
        exit;
    }

    /**
     * Eliminar documento del vehículo (AJAX)
     */
    public function eliminarDocumento()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de documento requerido'
            ]);
        }

        // Obtener documento
        $documento = $this->db->table('documentos_vehiculos')
                             ->select('documentos_vehiculos.*, vehiculos.id_empresa')
                             ->join('vehiculos', 'vehiculos.id = documentos_vehiculos.id_vehiculo')
                             ->where('documentos_vehiculos.id', $id)
                             ->get()
                             ->getRowArray();

        if (!$documento) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Documento no encontrado'
            ]);
        }

        // Verificar permisos
        $session = session();
        if ($documento['id_empresa'] != $session->get('empresa_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tiene permisos para eliminar este documento'
            ]);
        }

        // Eliminar archivo físico
        $rutaArchivo = WRITEPATH . 'uploads/vehiculos/' . $documento['ruta_archivo'];
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo);
        }

        // Eliminar registro de base de datos
        if ($this->db->table('documentos_vehiculos')->delete(['id' => $id])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Documento eliminado exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar el documento'
            ]);
        }
    }

    /**
     * Verificar si el vehículo tiene una solicitud de mantenimiento activa
     */
    private function verificarSolicitudMantenimientoActiva($idVehiculo)
    {
        $solicitud = $this->db->table('solicitudes')
                             ->where('id_vehiculo', $idVehiculo)
                             ->whereIn('estado', ['PENDIENTE', 'EN_PROCESO', 'APROBADA'])
                             ->orderBy('fecha_registro', 'DESC')
                             ->get()
                             ->getRowArray();
        
        return $solicitud;
    }

    /**
     * Crear nueva solicitud de mantenimiento
     */
    public function crearSolicitudMantenimiento()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $session = session();
        $idVehiculo = $this->request->getPost('id_vehiculo');
        $descripcion = $this->request->getPost('descripcion');
        $prioridad = $this->request->getPost('prioridad') ?? 'MEDIA';
        $tipoMantenimiento = $this->request->getPost('tipo_mantenimiento') ?? 'CORRECTIVO';

        // Validaciones
        if (!$idVehiculo || !$descripcion) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
        }

        // Verificar que el vehículo pertenece a la empresa
        $vehiculo = $this->vehiculoModel->find($idVehiculo);
        if (!$vehiculo || $vehiculo['id_empresa'] != $session->get('empresa_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vehículo no encontrado o sin permisos'
            ]);
        }

        // Verificar que no tenga solicitud activa
        $solicitudActiva = $this->verificarSolicitudMantenimientoActiva($idVehiculo);
        if ($solicitudActiva) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El vehículo ya tiene una solicitud de mantenimiento activa'
            ]);
        }

        // Crear la solicitud
        $data = [
            'idVehiculo' => $idVehiculo,
            'descripcion' => $descripcion,
            'prioridad' => $prioridad,
            'tipoMantenimiento' => $tipoMantenimiento,
            'estado' => 'PENDIENTE',
            'fechaRegistro' => date('Y-m-d H:i:s'),
            'usuarioCrea' => $session->get('user_id')
        ];

        if ($this->db->table('solicitudes_mantenimiento')->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Solicitud de mantenimiento creada exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear la solicitud de mantenimiento'
            ]);
        }
    }

    /**
     * Ver detalles de la solicitud de mantenimiento activa
     */
    public function verSolicitudMantenimiento($idVehiculo)
    {
        $session = session();
        
        // Verificar permisos del vehículo
        $vehiculo = $this->vehiculoModel->find($idVehiculo);
        if (!$vehiculo || $vehiculo['id_empresa'] != $session->get('empresa_id')) {
            return redirect()->to(base_url('vehiculos'))->with('error', 'Sin permisos');
        }

        // Obtener solicitud activa
        $solicitud = $this->verificarSolicitudMantenimientoActiva($idVehiculo);
        if (!$solicitud) {
            return redirect()->to(base_url('vehiculos/show/' . $idVehiculo))
                           ->with('error', 'No hay solicitud de mantenimiento activa');
        }

        $data = [
            'title' => 'Solicitud de Mantenimiento - GMV',
            'page_title' => 'Solicitud de Mantenimiento',
            'vehiculo' => $vehiculo,
            'solicitud' => $solicitud
        ];

        return view('vehiculos/solicitud_mantenimiento', $data);
    }

    /**
     * Obtener estadísticas específicas del vehículo
     */
    public function getEstadisticasVehiculo($idVehiculo)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $session = session();
        
        // Verificar permisos del vehículo
        $vehiculo = $this->vehiculoModel->find($idVehiculo);
        if (!$vehiculo || $vehiculo['id_empresa'] != $session->get('empresa_id')) {
            return $this->response->setJSON(['error' => 'Sin permisos']);
        }

        // Obtener estadísticas de solicitudes de mantenimiento
        $totalSolicitudes = $this->db->table('solicitudes')
                                    ->where('id_vehiculo', $idVehiculo)
                                    ->countAllResults();

        $mantenimientosCompletados = $this->db->table('solicitudes')
                                             ->where('id_vehiculo', $idVehiculo)
                                             ->where('estado', 'COMPLETADA')
                                             ->countAllResults();

        return $this->response->setJSON([
            'total_solicitudes' => $totalSolicitudes,
            'mantenimientos_completados' => $mantenimientosCompletados
        ]);
    }

    /**
     * Vista de reportes de vehículos
     */
    public function reportes()
    {
        $session = session();
        $empresaId = $session->get('empresa_id');

        // Obtener estadísticas generales para los reportes
        $estadisticas = $this->obtenerEstadisticasGenerales($empresaId);

        $data = [
            'title' => 'Reportes de Vehículos - Sistema GMV',
            'page_title' => 'Reportes de Vehículos',
            'estadisticas' => $estadisticas
        ];

        return view('vehiculos/reportes_dashboard', $data);
    }

    /**
     * Método de prueba para verificar que las rutas funcionen
     */
    public function testReporte()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Ruta funcionando correctamente',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Generar reporte de vehículos por estado
     */
    public function reporteVehiculosPorEstado()
    {
        // Permitir tanto AJAX como peticiones directas para debugging
        // if (!$this->request->isAJAX()) {
        //     return redirect()->to(base_url('vehiculos/reportes'));
        // }

        // Asegurar que siempre devuelva JSON
        $this->response->setContentType('application/json');

        $session = session();
        $empresaId = $session->get('empresa_id');

        $vehiculos = $this->db->table('vehiculos v')
                             ->select('v.*, tu.descripcion as tipo_unidad_desc, top.descripcion as tipo_operacion_desc')
                             ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left')
                             ->join('tipo_operacion top', 'v.idTipoOperacion = top.id', 'left')
                             ->where('v.id_empresa', $empresaId)
                             ->orderBy('v.estado', 'ASC')
                             ->orderBy('v.placa', 'ASC')
                             ->get()
                             ->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $vehiculos
        ]);
    }

    /**
     * Generar reporte de vehículos con mantenimiento vencido
     */
    public function reporteMantenimientoVencido()
    {
        // Permitir tanto AJAX como peticiones directas para debugging
        // if (!$this->request->isAJAX()) {
        //     return redirect()->to(base_url('vehiculos/reportes'));
        // }

        // Asegurar que siempre devuelva JSON
        $this->response->setContentType('application/json');

        $session = session();
        $empresaId = $session->get('empresa_id');

        // Obtener vehículos con mantenimiento vencido o próximo a vencer
        $vehiculos = $this->db->query("
            SELECT v.*, 
                   COUNT(s.id) as total_solicitudes,
                   MAX(s.fecha_solicitud) as ultimo_mantenimiento,
                   DATEDIFF(CURDATE(), MAX(s.fecha_solicitud)) as dias_sin_mantenimiento
            FROM vehiculos v
            LEFT JOIN solicitudes s ON v.id = s.id_vehiculo AND s.estado = 'COMPLETADA'
            WHERE v.id_empresa = ?
            GROUP BY v.id
            HAVING dias_sin_mantenimiento > 90 OR dias_sin_mantenimiento IS NULL
            ORDER BY dias_sin_mantenimiento DESC
        ", [$empresaId])->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $vehiculos
        ]);
    }

    /**
     * Generar reporte de vehículos por kilometraje
     */
    public function reporteKilometraje()
    {
        // Permitir tanto AJAX como peticiones directas para debugging
        // if (!$this->request->isAJAX()) {
        //     return redirect()->to(base_url('vehiculos/reportes'));
        // }

        // Asegurar que siempre devuelva JSON
        $this->response->setContentType('application/json');

        $session = session();
        $empresaId = $session->get('empresa_id');

        $vehiculos = $this->db->table('vehiculos v')
                             ->select('v.*, tu.descripcion as tipo_unidad_desc')
                             ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left')
                             ->where('v.id_empresa', $empresaId)
                             ->orderBy('v.kilometraje', 'DESC')
                             ->get()
                             ->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $vehiculos
        ]);
    }

    /**
     * Generar reporte de documentos por vencer
     */
    public function reporteDocumentosPorVencer()
    {
        // Permitir tanto AJAX como peticiones directas para debugging
        // if (!$this->request->isAJAX()) {
        //     return redirect()->to(base_url('vehiculos/reportes'));
        // }

        // Asegurar que siempre devuelva JSON
        $this->response->setContentType('application/json');

        $session = session();
        $empresaId = $session->get('empresa_id');

        // Obtener documentos que vencen en los próximos 30 días
        $documentos = $this->db->query("
            SELECT v.placa, v.marca, v.modelo, v.anio,
                   td.nombre as tipo_documento,
                   dv.fecha_vencimiento,
                   DATEDIFF(dv.fecha_vencimiento, CURDATE()) as dias_restantes,
                   CONCAT(v.marca, ' ', v.modelo, ' (', v.anio, ')') as vehiculo_info
            FROM vehiculos v
            INNER JOIN documentos_vehiculos dv ON v.id = dv.id_vehiculo
            INNER JOIN tipo_documento td ON dv.id_tipo_documento = td.id
            WHERE v.id_empresa = ?
            AND dv.fecha_vencimiento IS NOT NULL
            AND dv.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY dv.fecha_vencimiento ASC
        ", [$empresaId])->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $documentos
        ]);
    }

    /**
     * Generar reporte de asignaciones de conductores
     */
    public function reporteAsignaciones()
    {
        // Permitir tanto AJAX como peticiones directas para debugging
        // if (!$this->request->isAJAX()) {
        //     return redirect()->to(base_url('vehiculos/reportes'));
        // }

        // Asegurar que siempre devuelva JSON
        $this->response->setContentType('application/json');

        $session = session();
        $empresaId = $session->get('empresa_id');

        $asignaciones = $this->db->query("
            SELECT v.placa, v.marca, v.modelo, v.anio, v.estado as estado_vehiculo,
                   c.nombre, c.apellido, c.dni,
                   av.fecha_asignacion, av.estado as estado_asignacion,
                   CASE 
                       WHEN av.estado = 'ACTIVA' THEN 'Asignado'
                       WHEN av.estado = 'INACTIVA' THEN 'No Asignado'
                       ELSE 'Sin Asignación'
                   END as estado_desc,
                   CONCAT(v.marca, ' ', v.modelo, ' (', v.anio, ')') as vehiculo_info,
                   CONCAT(c.nombre, ' ', c.apellido) as conductor
            FROM vehiculos v
            LEFT JOIN asignacion_vehiculos av ON v.id = av.id_vehiculo AND av.estado = 'ACTIVA'
            LEFT JOIN conductores c ON av.id_conductor = c.id
            WHERE v.id_empresa = ?
            ORDER BY v.placa ASC
        ", [$empresaId])->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $asignaciones
        ]);
    }

    /**
     * Generar reporte de vehículos sin documentación
     */
    public function reporteVehiculosSinDocumentacion()
    {
        // Permitir tanto AJAX como peticiones directas para debugging
        // if (!$this->request->isAJAX()) {
        //     return redirect()->to(base_url('vehiculos/reportes'));
        // }

        // Asegurar que siempre devuelva JSON
        $this->response->setContentType('application/json');

        $session = session();
        $empresaId = $session->get('empresa_id');

        // Obtener filtros
        $estado = $this->request->getGet('estado');
        $busqueda = $this->request->getGet('busqueda');

        // Construir consulta base
        $sql = "
            SELECT v.*, tu.descripcion as tipo_unidad_desc, top.descripcion as tipo_operacion_desc
            FROM vehiculos v 
            LEFT JOIN tipo_unidad tu ON v.idTipoUnidad = tu.id
            LEFT JOIN tipo_operacion top ON v.idTipoOperacion = top.id
            WHERE v.id_empresa = ?
            AND v.id NOT IN (
                SELECT DISTINCT dv.id_vehiculo 
                FROM documentos_vehiculos dv 
                WHERE dv.id_vehiculo IS NOT NULL
            )
        ";

        $params = [$empresaId];

        // Aplicar filtro por estado si se proporciona
        if (!empty($estado) && $estado !== 'TODOS') {
            $sql .= " AND v.estado = ?";
            $params[] = $estado;
        }

        // Aplicar filtro de búsqueda general si se proporciona
        if (!empty($busqueda)) {
            $sql .= " AND (
                v.placa LIKE ? OR 
                v.marca LIKE ? OR 
                v.modelo LIKE ? OR 
                v.anio LIKE ? OR
                v.numero_motor LIKE ? OR
                v.numero_chasis LIKE ? OR
                tu.descripcion LIKE ? OR
                top.descripcion LIKE ?
            )";
            $searchTerm = "%{$busqueda}%";
            for ($i = 0; $i < 8; $i++) {
                $params[] = $searchTerm;
            }
        }

        $sql .= " ORDER BY v.placa ASC";

        $vehiculos = $this->db->query($sql, $params)->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $vehiculos,
            'total' => count($vehiculos),
            'filtros' => [
                'estado' => $estado,
                'busqueda' => $busqueda
            ]
        ]);
    }

    /**
     * Exportar reporte a Excel
     */
    public function exportarReporte()
    {
        $tipoReporte = $this->request->getPost('tipo_reporte');
        $formato = $this->request->getPost('formato') ?? 'excel';

        $session = session();
        $empresaId = $session->get('empresa_id');

        // Obtener datos según el tipo de reporte
        $datos = [];
        $nombreArchivo = '';
        $headers = [];

        switch ($tipoReporte) {
            case 'estado':
                $datos = $this->obtenerDatosReporteEstado($empresaId);
                $nombreArchivo = 'vehiculos_por_estado_' . date('Y-m-d');
                $headers = ['Placa', 'Marca', 'Modelo', 'Año', 'Estado', 'Kilometraje', 'Tipo Unidad'];
                break;
            
            case 'mantenimiento':
                $datos = $this->obtenerDatosReporteMantenimiento($empresaId);
                $nombreArchivo = 'mantenimiento_vencido_' . date('Y-m-d');
                $headers = ['Placa', 'Marca', 'Modelo', 'Último Mantenimiento', 'Días sin Mantenimiento'];
                break;
            
            case 'kilometraje':
                $datos = $this->obtenerDatosReporteKilometraje($empresaId);
                $nombreArchivo = 'reporte_kilometraje_' . date('Y-m-d');
                $headers = ['Placa', 'Marca', 'Modelo', 'Año', 'Kilometraje', 'Tipo Unidad'];
                break;
            
            case 'documentos':
                $datos = $this->obtenerDatosReporteDocumentos($empresaId);
                $nombreArchivo = 'documentos_por_vencer_' . date('Y-m-d');
                $headers = ['Placa', 'Marca', 'Modelo', 'Tipo Documento', 'Fecha Vencimiento', 'Días Restantes'];
                break;
            
            case 'asignaciones':
                $datos = $this->obtenerDatosReporteAsignaciones($empresaId);
                $nombreArchivo = 'asignaciones_vehiculos_' . date('Y-m-d');
                $headers = ['Placa', 'Marca', 'Modelo', 'Conductor', 'DNI', 'Estado Asignación', 'Fecha Asignación'];
                break;
            
            case 'sin_documentacion':
                $datos = $this->obtenerDatosReporteSinDocumentacion($empresaId);
                $nombreArchivo = 'vehiculos_sin_documentacion_' . date('Y-m-d');
                $headers = ['Placa', 'Marca', 'Modelo', 'Año', 'Estado', 'Kilometraje', 'Tipo Unidad', 'Tipo Operación'];
                break;
            
            default:
                return redirect()->to(base_url('vehiculos/reportes'))
                               ->with('error', 'Tipo de reporte no válido');
        }

        if ($formato === 'excel') {
            return $this->exportarExcel($datos, $headers, $nombreArchivo);
        } else {
            return $this->exportarPDF($datos, $headers, $nombreArchivo);
        }
    }

    /**
     * Obtener estadísticas generales para los reportes
     */
    private function obtenerEstadisticasGenerales($empresaId)
    {
        $estadisticas = [];

        // Total de vehículos
        $estadisticas['total_vehiculos'] = $this->db->table('vehiculos')
                                                   ->where('id_empresa', $empresaId)
                                                   ->countAllResults();

        // Vehículos por estado
        $estadisticas['por_estado'] = $this->db->table('vehiculos')
                                              ->select('estado, COUNT(*) as cantidad')
                                              ->where('id_empresa', $empresaId)
                                              ->groupBy('estado')
                                              ->get()
                                              ->getResultArray();

        // Vehículos asignados
        $estadisticas['asignados'] = $this->db->query("
            SELECT COUNT(DISTINCT v.id) as cantidad
            FROM vehiculos v
            INNER JOIN asignacion_vehiculos av ON v.id = av.id_vehiculo
            WHERE v.id_empresa = ? AND av.estado = 'ACTIVA'
        ", [$empresaId])->getRow()->cantidad ?? 0;

        // Documentos por vencer (próximos 30 días)
        $estadisticas['documentos_vencer'] = $this->db->query("
            SELECT COUNT(*) as cantidad
            FROM vehiculos v
            INNER JOIN documentos_vehiculos dv ON v.id = dv.id_vehiculo
            WHERE v.id_empresa = ?
            AND dv.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        ", [$empresaId])->getRow()->cantidad ?? 0;

        // Mantenimientos vencidos (más de 90 días)
        $estadisticas['mantenimiento_vencido'] = $this->db->query("
            SELECT COUNT(DISTINCT v.id) as cantidad
            FROM vehiculos v
            LEFT JOIN solicitudes s ON v.id = s.id_vehiculo AND s.estado = 'COMPLETADA'
            WHERE v.id_empresa = ?
            GROUP BY v.id
            HAVING MAX(s.fecha_solicitud) IS NULL OR DATEDIFF(CURDATE(), MAX(s.fecha_solicitud)) > 90
        ", [$empresaId])->getNumRows();

        // Vehículos sin documentación
        $estadisticas['sin_documentacion'] = $this->db->query("
            SELECT COUNT(*) as cantidad
            FROM vehiculos v
            WHERE v.id_empresa = ?
            AND v.id NOT IN (
                SELECT DISTINCT dv.id_vehiculo 
                FROM documentos_vehiculos dv 
                WHERE dv.id_vehiculo IS NOT NULL
            )
        ", [$empresaId])->getRow()->cantidad ?? 0;

        return $estadisticas;
    }

    /**
     * Métodos auxiliares para obtener datos de reportes específicos
     */
    private function obtenerDatosReporteEstado($empresaId)
    {
        return $this->db->table('vehiculos v')
                       ->select('v.placa, v.marca, v.modelo, v.anio, v.estado, v.kilometraje, tu.descripcion as tipo_unidad')
                       ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left')
                       ->where('v.id_empresa', $empresaId)
                       ->orderBy('v.estado', 'ASC')
                       ->get()
                       ->getResultArray();
    }

    private function obtenerDatosReporteMantenimiento($empresaId)
    {
        return $this->db->query("
            SELECT v.placa, v.marca, v.modelo,
                   MAX(s.fecha_solicitud) as ultimo_mantenimiento,
                   COALESCE(DATEDIFF(CURDATE(), MAX(s.fecha_solicitud)), 999) as dias_sin_mantenimiento
            FROM vehiculos v
            LEFT JOIN solicitudes s ON v.id = s.id_vehiculo AND s.estado = 'COMPLETADA'
            WHERE v.id_empresa = ?
            GROUP BY v.id
            HAVING dias_sin_mantenimiento > 90
            ORDER BY dias_sin_mantenimiento DESC
        ", [$empresaId])->getResultArray();
    }

    private function obtenerDatosReporteKilometraje($empresaId)
    {
        return $this->db->table('vehiculos v')
                       ->select('v.placa, v.marca, v.modelo, v.anio, v.kilometraje, tu.descripcion as tipo_unidad')
                       ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left')
                       ->where('v.id_empresa', $empresaId)
                       ->orderBy('v.kilometraje', 'DESC')
                       ->get()
                       ->getResultArray();
    }

    private function obtenerDatosReporteDocumentos($empresaId)
    {
        return $this->db->query("
            SELECT v.placa, v.marca, v.modelo,
                   td.nombre as tipo_documento,
                   dv.fecha_vencimiento,
                   DATEDIFF(dv.fecha_vencimiento, CURDATE()) as dias_restantes
            FROM vehiculos v
            INNER JOIN documentos_vehiculos dv ON v.id = dv.id_vehiculo
            INNER JOIN tipo_documento td ON dv.id_tipo_documento = td.id
            WHERE v.id_empresa = ?
            AND dv.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY dv.fecha_vencimiento ASC
        ", [$empresaId])->getResultArray();
    }

    private function obtenerDatosReporteAsignaciones($empresaId)
    {
        return $this->db->query("
            SELECT v.placa, v.marca, v.modelo,
                   CONCAT(c.nombre, ' ', c.apellido) as conductor,
                   c.dni,
                   CASE 
                       WHEN av.estado = 'ACTIVA' THEN 'Asignado'
                       ELSE 'No Asignado'
                   END as estado_asignacion,
                   av.fecha_asignacion
            FROM vehiculos v
            LEFT JOIN asignacion_vehiculos av ON v.id = av.id_vehiculo AND av.estado = 'ACTIVA'
            LEFT JOIN conductores c ON av.id_conductor = c.id
            WHERE v.id_empresa = ?
            ORDER BY v.placa ASC
        ", [$empresaId])->getResultArray();
    }

    private function obtenerDatosReporteSinDocumentacion($empresaId)
    {
        return $this->db->query("
            SELECT v.placa, v.marca, v.modelo, v.anio, v.estado, v.kilometraje,
                   tu.descripcion as tipo_unidad, top.descripcion as tipo_operacion
            FROM vehiculos v 
            LEFT JOIN tipo_unidad tu ON v.idTipoUnidad = tu.id
            LEFT JOIN tipo_operacion top ON v.idTipoOperacion = top.id
            WHERE v.id_empresa = ?
            AND v.id NOT IN (
                SELECT DISTINCT dv.id_vehiculo 
                FROM documentos_vehiculos dv 
                WHERE dv.id_vehiculo IS NOT NULL
            )
            ORDER BY v.placa ASC
        ", [$empresaId])->getResultArray();
    }

    /**
     * Exportar datos a Excel (simplificado)
     */
    private function exportarExcel($datos, $headers, $nombreArchivo)
    {
        // Crear contenido CSV
        $output = implode(',', $headers) . "\n";
        
        foreach ($datos as $fila) {
            $output .= implode(',', array_values($fila)) . "\n";
        }

        // Configurar headers para descarga
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '.csv"');
        
        return $this->response->setBody($output);
    }

    /**
     * Exportar datos a PDF (simplificado)
     */
    private function exportarPDF($datos, $headers, $nombreArchivo)
    {
        // Por ahora, exportar como CSV hasta implementar librería PDF
        return $this->exportarExcel($datos, $headers, $nombreArchivo);
    }

    /**
     * Obtener alertas de documentos próximos a vencer (AJAX)
     */
    public function getAlertasDocumentos()
    {
        $empresaId = session()->get('empresa_id');
        if (!$empresaId) {
            return $this->response->setJSON(['success' => false, 'count' => 0, 'items' => []]);
        }

        $documentos = $this->db->table('documentos_vehiculos dv')
            ->select('dv.id, dv.fecha_vencimiento, dv.notificacion, dv.id_vehiculo,
                     v.placa, v.marca, v.modelo,
                     cat.nombre as nombre_tipo_documento')
            ->join('vehiculos v', 'v.id = dv.id_vehiculo', 'inner')
            ->join('catalogo cat', 'cat.id = dv.tipo_documento', 'left')
            ->where('v.id_empresa', $empresaId)
            ->where('dv.fecha_vencimiento IS NOT NULL')
            ->get()
            ->getResultArray();

        $alertas = [];
        $hoy = new \DateTime();
        foreach ($documentos as $doc) {
            $venc = new \DateTime($doc['fecha_vencimiento']);
            $diasRestantes = $hoy->diff($venc)->days;
            if ($hoy > $venc) $diasRestantes = -$diasRestantes;

            $diasNotificacion = (int)($doc['notificacion'] ?? 0);
            $esVencido = $diasRestantes < 0;
            $esPorVencer = $diasNotificacion > 0 && $diasRestantes >= 0 && $diasRestantes <= $diasNotificacion;

            if ($esVencido || $esPorVencer) {
                $vehiculoDesc = trim(($doc['marca'] ?? '') . ' ' . ($doc['modelo'] ?? '') . ' (' . ($doc['placa'] ?? '') . ')');
                $alertas[] = [
                    'id' => $doc['id'],
                    'id_vehiculo' => $doc['id_vehiculo'],
                    'title' => esc($doc['nombre_tipo_documento'] ?? 'Documento'),
                    'desc' => $vehiculoDesc,
                    'type' => $esVencido ? 'danger' : 'warning',
                    'icon' => $esVencido ? 'fas fa-times-circle' : 'fas fa-exclamation-triangle',
                    'dias' => abs($diasRestantes),
                    'vencido' => $esVencido,
                    'time' => $esVencido
                        ? 'Vencido hace ' . abs($diasRestantes) . 'd'
                        : 'Vence en ' . $diasRestantes . 'd'
                ];
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'count' => count($alertas),
            'items' => $alertas
        ]);
    }
}
