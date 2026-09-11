<?php

namespace App\Controllers;

use App\Models\RegistroCombustibleModel;
use App\Models\TipoMotivoCombustibleModel;
use App\Models\CatalogoModel;
use App\Models\VehiculoModel;
use App\Models\DireccionModel;
use App\Models\ConductorModel;
use App\Services\RegistroCombustibleService;
use App\Services\LecturaBombaService;

class RegistroCombustible extends SecureController
{
    protected $registroCombustibleModel;
    protected $tipoMotivoModel;
    protected $vehiculoModel;
    protected $direccionModel;
    protected $catalogoModel;
    protected $conductorModel;
    protected RegistroCombustibleService $service;
    protected LecturaBombaService $lecturaBombaService;

    public function __construct()
    {
        helper('access');

        $this->service                  = new RegistroCombustibleService();
        $this->lecturaBombaService      = new LecturaBombaService();
        $this->registroCombustibleModel = new RegistroCombustibleModel();
        $this->tipoMotivoModel          = new TipoMotivoCombustibleModel();
        $this->vehiculoModel            = new VehiculoModel();
        $this->direccionModel           = new DireccionModel();
        $this->catalogoModel            = new CatalogoModel();
        $this->conductorModel           = new ConductorModel();
    }

    /**
     * Muestra formulario para actualizar montos de una venta
     */
    public function editMontosVenta($id)
    {
        requireAccess('registro-combustible/edit');

        $registro = $this->registroCombustibleModel->find($id);

        if (!$registro || strtoupper($registro['tipo'] ?? '') !== 'VENTA') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Venta de combustible no encontrada');
        }

        $data = [
            'title' => 'Actualizar Montos de Venta - Sistema GMV',
            'page_title' => 'Actualizar Montos de Venta',
            'registro' => $registro
        ];

        return view('registro_combustible/ventas_montos', $data);
    }

    /**
     * Procesa la actualización de montos para una venta
     */
    public function updateMontosVenta($id)
    {
        requireAccess('registro-combustible/edit');

        $registro = $this->registroCombustibleModel->find($id);

        if (!$registro || strtoupper($registro['tipo'] ?? '') !== 'VENTA') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Venta de combustible no encontrada');
        }

        $data = [
            'monto_nio' => $this->request->getPost('monto_nio'),
            'monto_usd' => $this->request->getPost('monto_usd'),
            'usuario_edita' => session()->get('usuario') ?? 'admin',
            'fecha_actualiza' => date('Y-m-d H:i:s')
        ];

        if ($this->registroCombustibleModel->update($id, $data)) {
            session()->setFlashdata('success', 'Montos de la venta actualizados correctamente');
            return redirect()->to('/registro-combustible/ventas');
        }

        session()->setFlashdata('error', 'No se pudo actualizar los montos de la venta');
        session()->setFlashdata('errors', $this->registroCombustibleModel->errors());
        return redirect()->back()->withInput();
    }

    /**
     * Muestra la lista de registros de combustible
     */
    public function index()
    {
        requireAccess('registro-combustible');
        
        // Filtro por defecto: fecha del día actual si no se especifica
        $fechaHoy = date('Y-m-d');
        
        // Obtener filtros
        $filtros = [
            'vehiculo' => $this->request->getGet('vehiculo'),
            'fecha_desde' => $this->request->getGet('fecha_desde') ?: $fechaHoy,
            'fecha_hasta' => $this->request->getGet('fecha_hasta') ?: $fechaHoy,
            'motivo' => $this->request->getGet('motivo'),
            'direccion' => $this->request->getGet('direccion'),
            'usuario' => $this->request->getGet('usuario'),
            'lectura_id' => $this->request->getGet('lectura_id')
        ];

        // Si se filtra por lectura_id, ignorar fechas
        if (!empty($filtros['lectura_id'])) {
            unset($filtros['fecha_desde']);
            unset($filtros['fecha_hasta']);
        }

        $empresaId = session()->get('empresa_id');
        $registros = $this->registroCombustibleModel->getRegistrosConRelaciones($filtros);
        $vehiculos = $this->vehiculoModel->where('id_empresa', $empresaId)->orderBy('placa','ASC')->findAll();
        $direcciones = $this->direccionModel->getDireccionesCompletas();
        $catalogos = $this->catalogoModel->getCatalogosPorCodigo("CAT-0003");
        $catalogosCRC = $this->catalogoModel->getOpcionesTipoConsumo();

        // Calcular total de litros si se filtra por lectura_id
        $totalLitros = 0;
        if (!empty($filtros['lectura_id']) && is_array($registros)) {
            foreach ($registros as $reg) {
                $totalLitros += (float)($reg['cantidad_litros'] ?? 0);
            }
        }
        
        // Obtener lista de usuarios únicos que han registrado de forma ultra-robusta
        $usuarios = [];
        if (method_exists($this->registroCombustibleModel, 'getUsuariosRegistros')) {
            $usuarios = $this->registroCombustibleModel->getUsuariosRegistros();
        } else {
            $db = \Config\Database::connect();
            $usuarios = $db->query("SELECT DISTINCT usuario_crea as usuario 
                                   FROM registro_combustible 
                                   WHERE usuario_crea IS NOT NULL AND usuario_crea != '' 
                                   ORDER BY usuario_crea ASC")->getResultArray();
        }
        
        // Para compatibilidad con la vista index
        $motivos = [];
        foreach ($catalogos as $id => $descripcion) {
            $motivos[] = ['id' => $id, 'motivo' => $descripcion];
        }

        $data = [
            'title' => 'Registro de Combustible - Sistema GMV',
            'page_title' => 'Registro de Combustible',
            'registros' => $registros,
            'vehiculos' => $vehiculos,
            'direcciones' => $direcciones,
            'motivos' => $motivos,
            'filtros' => $filtros,
            'catalogos' => $catalogos,
            'catalogosCRC' => $catalogosCRC,
            'usuarios' => $usuarios,
            'total_litros' => $totalLitros
        ];

        return view('registro_combustible/index', $data);
    }

    /**
     * Muestra únicamente los registros de ventas de combustible
     */
    public function ventas()
    {
        requireAccess('registro-combustible');

        $filtros = [
            'fecha_desde' => $this->request->getGet('fecha_desde'),
            'fecha_hasta' => $this->request->getGet('fecha_hasta'),
            'tipo' => 'VENTA'
        ];

        $registros = $this->registroCombustibleModel->getRegistrosConRelaciones($filtros);

        $data = [
            'title' => 'Ventas de Combustible - Sistema GMV',
            'page_title' => 'Ventas de Combustible',
            'registros' => $registros,
            'filtros' => $filtros
        ];

        return view('registro_combustible/ventas', $data);
    }

    /**
     * Voucher imprimible para registros de consumo
     */
    public function voucherConsumo($id)
    {
        requireAccess('registro-combustible/show');

        $registro = $this->registroCombustibleModel->getRegistroConRelaciones($id);

        if (!$registro || strtoupper($registro['tipo'] ?? 'CONSUMO') === 'VENTA') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Voucher de consumo no disponible');
        }

        $data = [
            'registro'       => $registro,
            'empresa_nombre' => session()->get('empresa_nombre') ?? 'Sistema GMV',
        ];

        return view('registro_combustible/voucher_consumo', $data);
    }

    /**
     * Voucher imprimible para registros de venta
     */
    public function voucherVenta($id)
    {
        requireAccess('registro-combustible/show');

        $registro = $this->registroCombustibleModel->getRegistroConRelaciones($id);

        if (!$registro || strtoupper($registro['tipo'] ?? '') !== 'VENTA') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Voucher de venta no disponible');
        }

        $data = [
            'registro' => $registro
        ];

        return view('registro_combustible/voucher_venta', $data);
    }

    /**
     * Muestra el formulario para crear un nuevo registro
     */
    public function create()
    {
        requireAccess('registro-combustible/create');

        // Verificar si existe al menos una apertura de bomba pendiente (solo las del usuario actual)
        $usuarioActual = session()->get('usuario');
        $aperturasPendientes = $this->lecturaBombaService->getAperturasPendientes($usuarioActual);

        if (empty($aperturasPendientes)) {
            session()->setFlashdata('warning', 'Debe abrir la bomba antes de registrar combustible');
            return redirect()->to('/lectura-bomba/apertura');
        }

        $empresaId   = session()->get('empresa_id');
        $vehiculos   = $this->vehiculoModel->where('id_empresa', $empresaId)->where('estado','ACTIVO')->orderBy('placa','ASC')->findAll();
        $direcciones = $this->direccionModel->getDireccionesCompletas();
        $tiposMotivo = $this->tipoMotivoModel->getTiposActivosParaSelect($empresaId);
        $catalogos   = $this->catalogoModel->getCatalogosPorCodigo("CAT-0003");
        $conductores = $this->conductorModel
            ->where('id_empresa', $empresaId)
            ->orderBy('estado', 'ASC')
            ->orderBy('nombre',  'ASC')
            ->findAll();
        $catalogosCRC = $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0014');
        $tiposConsumo = $this->catalogoModel->getOpcionesTipoConsumo();

        $data = [
            'title' => 'Nuevo Registro de Combustible - Sistema GMV',
            'page_title' => 'Nuevo Registro de Combustible',
            'vehiculos' => $vehiculos,
            'direcciones' => $direcciones,
            'tipos_motivo' => $tiposMotivo,
            'catalogos' => $catalogos,
            'conductores' => $conductores,
            'catalogosCRC' => $catalogosCRC,
            'tiposConsumo' => $tiposConsumo,
            'registro' => [],
            'aperturas_pendientes' => $aperturasPendientes
        ];

        return view('registro_combustible/form', $data);
    }

    /**
     * Muestra el formulario para registrar una venta de combustible
     */
    public function createVenta()
    {
        requireAccess('registro-combustible/create');
        $vehiculos = $this->vehiculoModel->findAll();
        $direcciones = $this->direccionModel->getDireccionesCompletas();
        $tiposMotivo = $this->tipoMotivoModel->getTiposActivosParaSelect(session()->get('empresa_id'));
        $catalogos = $this->catalogoModel->getCatalogosPorCodigo("CAT-0003");

        $data = [
            'title' => 'Registro de Venta de Combustible - Sistema GMV',
            'page_title' => 'Registro de Venta de Combustible',
            'vehiculos' => $vehiculos,
            'direcciones' => $direcciones,
            'tipos_motivo' => $tiposMotivo,
            'catalogos' => $catalogos,
            'registro' => [
                'id_vehiculo' => 1,
                'tipo' => 'VENTA'
            ]
        ];

        return view('registro_combustible/form_venta', $data);
    }

    /**
     * Procesa la creación de un nuevo registro
     */
    public function store()
    {
        $data = [
            'id_vehiculo'         => $this->request->getPost('id_vehiculo'),
            'id_direccion'        => $this->request->getPost('idDireccion') ?: null,
            'fecha_registro'      => date('Y-m-d H:i:s'),
            'kilometraje_anterior'=> $this->request->getPost('kilometraje_anterior'),
            'kilometraje_actual'  => $this->request->getPost('kilometraje_actual'),
            'cantidad_litros'     => $this->request->getPost('cantidad_litros'),
            'medicion'            => $this->request->getPost('medicion'),
            'combustible_tanque'  => $this->request->getPost('combustible_tanque'),
            'id_tipo_motivo'      => $this->request->getPost('id_tipo_motivo') ?: null,
            'id_lectura'          => $this->request->getPost('id_lectura') ?: null,
            'nombreCliente'       => $this->request->getPost('nombreCliente'),
            'dni'                 => $this->request->getPost('dni'),
            'tipo'                => $this->request->getPost('tipo') ?: 'CONSUMO',
            'monto_nio'           => $this->request->getPost('monto_nio'),
            'monto_usd'           => $this->request->getPost('monto_usd'),
            'observaciones'       => $this->request->getPost('observaciones'),
            'rendimiento'         => $this->request->getPost('rendimiento'),
            'rendimiento_promedio'=> $this->request->getPost('rendimiento_promedio'),
            'estado'              => $this->request->getPost('estado') ?: 'APROBADO',
            'referencia1'         => $this->request->getPost('referencia1') ?: null,
            'usuario_crea'        => session()->get('usuario') ?? 'admin',
            'fecha_actualiza'     => date('Y-m-d H:i:s'),
        ];

        // Obtener tipo_consumo del vehículo y guardarlo en referencia1.
        // Lógica: si tipo_consumo == '7' no se pide kilometraje.
        $vehiculo = $this->vehiculoModel->find($data['id_vehiculo']);
        if ($vehiculo) {
            $data['referencia1'] = $vehiculo['tipo_consumo'] ?? null;
            $tc = $vehiculo['tipo_consumo'] ?? null;
            if ((string)$tc === '7') {
                $data['kilometraje_actual'] = 0;
                $data['kilometraje_anterior'] = 0;
            }
        }

        $result = $this->service->crearRegistro($data);

        if ($result['success']) {
            session()->setFlashdata('success', 'Registro de combustible creado exitosamente');
            session()->setFlashdata('voucher_id', $result['id']);
            if (isset($result['sag_success']) && !$result['sag_success']) {
                session()->setFlashdata('warning', 'El registro se guardó, pero no se pudo enviar a SAG. Puede usar “Reenviar SAG” desde la lista de registros. Detalle: ' . ($result['sag_message'] ?? 'Error desconocido'));
            }
            return redirect()->to('/registro-combustible');
        }

        session()->setFlashdata('error', $result['message'] ?? 'Error al crear el registro');
        if (!empty($result['errors'])) {
            session()->setFlashdata('errors', $result['errors']);
        }
        return redirect()->back()->withInput();
    }

    /**
     * Reenvía un registro específico al SAG (AJAX o POST).
     */
    public function reenviarSAG($id)
    {
        requireAccess('registro-combustible/edit');

        $res = $this->service->reenviarAlSAG((int)$id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($res);
        }

        session()->setFlashdata($res['success'] ? 'success' : 'error', $res['message']);
        return redirect()->to('/registro-combustible');
    }

    /**
     * Bandeja de supervisor — registros bloqueados pendientes de aprobación
     */
    public function supervisor()
    {
        requireAccess('registro-combustible/supervisor');

        $registros = $this->service->getRegistrosBloqueados();

        $data = [
            'title'      => 'Supervisor - Registros Bloqueados - Sistema GMV',
            'page_title' => 'Bandeja de Supervisor',
            'registros'  => $registros,
        ];

        return view('registro_combustible/supervisor', $data);
    }

    /**
     * Retorna JSON con el conteo de registros bloqueados (para polling/notificación)
     */
    public function getBloqueadosCount()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Solo AJAX']);
        }
        requireAccess('registro-combustible/supervisor');

        $registros = $this->service->getRegistrosBloqueados();
        return $this->response->setJSON([
            'count'     => count($registros),
            'registros' => array_map(fn($r) => [
                'id'     => $r['id'],
                'placa'  => $r['placa'] ?? '—',
                'nombreCliente' => $r['nombreCliente'] ?? '—',
            ], $registros),
        ]);
    }

    /**
     * Aprueba un registro bloqueado y lo envía al SAG
     */
    public function aprobar($id)
    {
        requireAccess('registro-combustible/supervisor');

        $res = $this->service->aprobarRegistro((int)$id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($res);
        }

        session()->setFlashdata($res['success'] ? 'success' : 'error', $res['message']);
        return redirect()->to('/registro-combustible/supervisor');
    }

    /**
     * Rechaza un registro bloqueado
     */
    public function rechazar($id)
    {
        requireAccess('registro-combustible/supervisor');

        $motivo = $this->request->getPost('motivo_rechazo') ?? '';
        $res = $this->service->rechazarRegistro((int)$id, trim($motivo));

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($res);
        }

        session()->setFlashdata($res['success'] ? 'success' : 'error', $res['message']);
        return redirect()->to('/registro-combustible/supervisor');
    }

    /**
     * Muestra los detalles de un registro específico
     */
    public function show($id)
    {
        requireAccess('registro-combustible/show');
        $registro = $this->registroCombustibleModel->getRegistroConRelaciones($id);

        if (!$registro) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Registro no encontrado');
        }

        $data = [
            'title' => 'Detalle Registro de Combustible - Sistema GMV',
            'page_title' => 'Detalle Registro de Combustible',
            'registro' => $registro
        ];

        return view('registro_combustible/show', $data);
    }

    /**
     * Muestra el formulario para editar un registro
     */
    public function edit($id)
    {
        requireAccess('registro-combustible/edit');
        $registro = $this->registroCombustibleModel->find($id);

        if (!$registro) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Registro no encontrado');
        }

        $empresaId   = session()->get('empresa_id');
        $vehiculos   = $this->vehiculoModel->where('id_empresa', $empresaId)->where('estado','ACTIVO')->orderBy('placa','ASC')->findAll();
        $direcciones = $this->direccionModel->getDireccionesCompletas();
        $tiposMotivo = $this->tipoMotivoModel->getTiposActivosParaSelect($empresaId);
        $conductores = $this->conductorModel
            ->where('id_empresa', $empresaId)
            ->orderBy('estado', 'ASC')
            ->orderBy('nombre',  'ASC')
            ->findAll();
        $catalogosCRC = $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0014');
        $tiposConsumo = $this->catalogoModel->getOpcionesTipoConsumo();
        $usuarioActual = session()->get('usuario');
        $aperturasPendientes = $this->lecturaBombaService->getAperturasPendientes($usuarioActual);

        $data = [
            'title' => 'Editar Registro de Combustible - Sistema GMV',
            'page_title' => 'Editar Registro de Combustible',
            'vehiculos' => $vehiculos,
            'direcciones' => $direcciones,
            'tipos_motivo' => $tiposMotivo,
            'conductores' => $conductores,
            'catalogosCRC' => $catalogosCRC,
            'tiposConsumo' => $tiposConsumo,
            'registro' => $registro,
            'aperturas_pendientes' => $aperturasPendientes
        ];

        return view('registro_combustible/form', $data);
    }

    /**
     * Procesa la actualización de un registro
     */
    public function update($id)
    {
        $registro = $this->registroCombustibleModel->find($id);
        if (!$registro) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Registro no encontrado');
        }

        $data = [
            'id_vehiculo'         => $this->request->getPost('id_vehiculo'),
            'id_direccion'        => $this->request->getPost('idDireccion') ?: null,
            'fecha_registro'      => $this->request->getPost('fecha_registro') ?: date('Y-m-d H:i:s'),
            'kilometraje_anterior'=> $this->request->getPost('kilometraje_anterior'),
            'kilometraje_actual'  => $this->request->getPost('kilometraje_actual'),
            'cantidad_litros'     => $this->request->getPost('cantidad_litros'),
            'medicion'            => $this->request->getPost('medicion'),
            'combustible_tanque'  => $this->request->getPost('combustible_tanque'),
            'id_tipo_motivo'      => $this->request->getPost('id_tipo_motivo') ?: null,
            'id_lectura'          => $this->request->getPost('id_lectura') ?: null,
            'nombreCliente'       => $this->request->getPost('nombreCliente'),
            'dni'                 => $this->request->getPost('dni'),
            'tipo'                => $this->request->getPost('tipo') ?: ($registro['tipo'] ?? 'CONSUMO'),
            'monto_nio'           => $this->request->getPost('monto_nio'),
            'monto_usd'           => $this->request->getPost('monto_usd'),
            'observaciones'       => $this->request->getPost('observaciones'),
            'rendimiento'         => $this->request->getPost('rendimiento'),
            'rendimiento_promedio'=> $this->request->getPost('rendimiento_promedio'),
            'estado'              => $this->request->getPost('estado') ?: ($registro['estado'] ?? 'APROBADO'),
            'referencia1'         => $this->request->getPost('referencia1') ?: null,
            'usuario_edita'       => session()->get('usuario') ?? 'admin',
            'fecha_actualiza'     => date('Y-m-d H:i:s'),
        ];

        // Obtener tipo_consumo del vehículo y guardarlo en referencia1.
        // Lógica: si tipo_consumo == '7' no se pide kilometraje.
        $vehiculo = $this->vehiculoModel->find($data['id_vehiculo']);
        if ($vehiculo) {
            $data['referencia1'] = $vehiculo['tipo_consumo'] ?? null;
            $tc = $vehiculo['tipo_consumo'] ?? null;
            if ((string)$tc === '7') {
                $data['kilometraje_actual'] = 0;
                $data['kilometraje_anterior'] = 0;
            }
        }

        $result = $this->service->actualizarRegistro((int)$id, $data);

        if ($result['success']) {
            session()->setFlashdata('success', 'Registro de combustible actualizado exitosamente');
            return redirect()->to('/registro-combustible');
        }

        session()->setFlashdata('error', $result['message'] ?? 'Error al actualizar el registro');
        if (!empty($result['errors'])) {
            session()->setFlashdata('errors', $result['errors']);
        }
        return redirect()->back()->withInput();
    }

    /**
     * Obtiene el kilometraje actual de un vehículo (AJAX)
     */
    public function getKilometrajeVehiculo($idVehiculo = null)
    {
        if ($this->request->isAJAX() && $idVehiculo) {
            $vehiculo = $this->vehiculoModel->find($idVehiculo);
            
            if ($vehiculo) {
                return $this->response->setJSON([
                    'success' => true,
                    'kilometraje' => $vehiculo['kilometraje'] ?? 0,
                    'rendimiento' => $vehiculo['rendimiento'] ?? 0,
                    'externo' => $vehiculo['externo'] ?? 0,
                    'tipo_consumo' => $vehiculo['tipo_consumo'] ?? null
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Vehículo no encontrado'
        ]);
    }

    /**
     * Devuelve el siguiente número de recibo (NUMERO_DCTO) desde SQL Server (AJAX).
     */
    public function siguienteNumeroRecibo()
    {
        if ($this->request->isAJAX()) {
            $numero = $this->service->obtenerNumeroReciboPreview();
            return $this->response->setJSON([
                'success' => true,
                'numero_recibo' => $numero,
                'numero_recibo_formateado' => ($numero > 0) ? str_pad((string)$numero, 6, '0', STR_PAD_LEFT) : '—'
            ]);
        }

        return redirect()->to('/registro-combustible');
    }

    /**
     * Elimina un registro de combustible
     */
    public function delete($id = null)
    {
        requireAccess('registro-combustible');

        if ($id === null) {
            return redirect()->to(base_url('registro-combustible'))->with('error', 'ID de registro no válido');
        }

        if ($this->service->eliminarRegistro((int)$id)) {
            return redirect()->to(base_url('registro-combustible'))->with('success', 'Registro eliminado exitosamente');
        }

        return redirect()->to(base_url('registro-combustible'))->with('error', 'Error al eliminar el registro');
    }

    /**
     * Muestra estadísticas de consumo
     */
    public function estadisticas()
    {
        $filtros = [
            'vehiculo' => $this->request->getGet('vehiculo'),
            'fecha_desde' => $this->request->getGet('fecha_desde'),
            'fecha_hasta' => $this->request->getGet('fecha_hasta')
        ];

        $estadisticas = $this->registroCombustibleModel->getEstadisticasConsumo(
            $filtros['vehiculo'],
            $filtros['fecha_desde'],
            $filtros['fecha_hasta']
        );

        $vehiculos = $this->vehiculoModel->findAll();

        $data = [
            'title' => 'Estadísticas de Combustible - Sistema GMV',
            'page_title' => 'Estadísticas de Combustible',
            'estadisticas' => $estadisticas,
            'vehiculos' => $vehiculos,
            'filtros' => $filtros
        ];

        return view('registro_combustible/estadisticas', $data);
    }

    /**
     * Estadísticas para las cards del index (AJAX)
     */
    public function getEstadisticas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        // Obtener filtros de la solicitud
        $filtros = [
            'vehiculo' => $this->request->getGet('vehiculo'),
            'fecha_desde' => $this->request->getGet('fecha_desde'),
            'fecha_hasta' => $this->request->getGet('fecha_hasta'),
            'usuario' => $this->request->getGet('usuario')
        ];

        // Si no hay filtros de fecha, usar hoy por defecto
        $fechaHoy = date('Y-m-d');
        if (empty($filtros['fecha_desde'])) {
            $filtros['fecha_desde'] = $fechaHoy;
        }
        if (empty($filtros['fecha_hasta'])) {
            $filtros['fecha_hasta'] = $fechaHoy;
        }

        return $this->response->setJSON($this->service->getEstadisticasIndex($filtros));
    }

    /**
     * Datos paginados para la tabla del index (AJAX)
     */
    public function getData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $filtros = [
            'vehiculo'    => $this->request->getPost('vehiculo')    ?? '',
            'fecha_desde' => $this->request->getPost('fecha_desde') ?? '',
            'fecha_hasta' => $this->request->getPost('fecha_hasta') ?? '',
            'motivo'      => $this->request->getPost('motivo')      ?? '',
        ];

        $registros = $this->service->getRegistros(array_filter($filtros));

        return $this->response->setJSON([
            'success'      => true,
            'data'         => $registros,
            'recordsTotal' => count($registros),
        ]);
    }
}
