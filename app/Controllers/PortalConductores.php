<?php

namespace App\Controllers;

use App\Models\SolicitudModel;
use App\Models\VehiculoModel;
use App\Models\CatalogoModel;

class PortalConductores extends BaseController
{
    protected $solicitudModel;
    protected $vehiculoModel;
    protected $catalogoModel;
    protected $conductorModel;
    protected $session;
    protected $empresaId;
    protected $usuarioId;
    protected $conductorId;

    public function __construct()
    {
        $this->solicitudModel = new SolicitudModel();
        $this->vehiculoModel  = new VehiculoModel();
        $this->catalogoModel  = new CatalogoModel();
        $this->conductorModel = new \App\Models\ConductorModel();
        $this->session        = session();

        helper(['form', 'url']);

        $this->empresaId   = $this->session->get('empresa_id');
        $this->usuarioId   = $this->session->get('user_id') ?? 0;
        $this->conductorId = $this->session->get('conductor_id') ?? 0;

        if (!$this->empresaId) {
            $db = \Config\Database::connect();
            $empresa = $db->table('empresas')->select('id')->orderBy('id', 'ASC')->limit(1)->get()->getRowArray();
            $this->empresaId = $empresa['id'] ?? 1;
        }
    }

    /**
     * Verificar si el conductor está autenticado por PIN
     */
    protected function requireConductor()
    {
        if (!$this->conductorId) {
            return redirect()->to('portal/login')->with('error', 'Ingresa tu PIN para acceder');
        }
        return null;
    }

    /**
     * Pantalla de login por PIN
     */
    public function login()
    {
        if ($this->conductorId) {
            return redirect()->to('portal');
        }
        return view('portal_conductores/login', ['title' => 'Acceso Conductores']);
    }

    /**
     * Procesar login por PIN
     */
    public function doLogin()
    {
        $pin = trim($this->request->getPost('pin') ?? '');

        if (strlen($pin) !== 6 || !ctype_digit($pin)) {
            return redirect()->back()->with('error', 'El PIN debe tener 6 dígitos');
        }

        $conductor = $this->conductorModel
            ->where('pin_acceso', $pin)
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$conductor) {
            return redirect()->back()->with('error', 'PIN incorrecto o conductor inactivo');
        }

        // Guardar sesión del conductor
        $this->session->set([
            'conductor_id'     => $conductor['id'],
            'conductor_nombre' => $conductor['nombre'] . ' ' . $conductor['apellido'],
            'conductor_dni'    => $conductor['dni'],
            'conductor_carnet' => $conductor['carnet'] ?? '',
            'empresa_id'       => $conductor['id_empresa'],
        ]);

        return redirect()->to('portal')->with('success', 'Bienvenido, ' . $conductor['nombre']);
    }

    /**
     * Cerrar sesión del conductor
     */
    public function logout()
    {
        $this->session->remove(['conductor_id', 'conductor_nombre', 'conductor_dni', 'conductor_carnet']);
        return redirect()->to('portal/login');
    }

    /**
     * Página principal del portal de conductores
     */
    public function index()
    {
        if ($redirect = $this->requireConductor()) return $redirect;

        $solicitudes = $this->solicitudModel
            ->select('solicitudes.*, vehiculos.placa, vehiculos.marca, vehiculos.modelo')
            ->join('vehiculos', 'vehiculos.id = solicitudes.id_vehiculo', 'left')
            ->where('solicitudes.id_empresa', $this->empresaId)
            ->where('solicitudes.id_solicitante', $this->conductorId)
            ->orderBy('solicitudes.fecha_solicitud', 'DESC')
            ->findAll();

        $conteo = [
            'total'      => count($solicitudes),
            'pendientes' => 0,
            'en_proceso' => 0,
            'finalizadas'=> 0,
        ];
        foreach ($solicitudes as $s) {
            $estado = $s['estado'] ?? '';
            if ($estado === 'PENDIENTES') $conteo['pendientes']++;
            elseif ($estado === 'EN_PROCESO' || $estado === 'APROBADAS') $conteo['en_proceso']++;
            elseif ($estado === 'FINALIZADA') $conteo['finalizadas']++;
        }

        $recientes = array_slice($solicitudes, 0, 3);

        $data = [
            'title'      => 'Portal de Conductores',
            'conteo'     => $conteo,
            'recientes'  => $recientes,
        ];

        return view('portal_conductores/index', $data);
    }

    /**
     * Wizard para crear solicitud de orden de trabajo
     */
    public function crear()
    {
        if ($redirect = $this->requireConductor()) return $redirect;

        // Vehículos asignados al conductor o todos los activos de la empresa
        $vehiculos = $this->vehiculoModel
            ->where('id_empresa', $this->empresaId)
            ->where('estado', 'ACTIVO')
            ->findAll();

        // Tipos de problema desde catálogo CAT-0016
        $tiposProblemaCatalogo = $this->catalogoModel->getHijosActivosPorCodigo('CAT-0016');
        $tiposProblema = [];
        foreach ($tiposProblemaCatalogo as $tipo) {
            $categoria = $tipo['referencia'] ?? 'General';
            if (!isset($tiposProblema[$categoria])) {
                $tiposProblema[$categoria] = [];
            }
            $tiposProblema[$categoria][] = $tipo;
        }

        $data = [
            'title'        => 'Nueva Solicitud',
            'vehiculos'    => $vehiculos,
            'tiposProblema' => $tiposProblema,
        ];

        return view('portal_conductores/crear', $data);
    }

    /**
     * Guardar la solicitud enviada desde el wizard
     */
    public function store()
    {
        if ($redirect = $this->requireConductor()) return $redirect;

        $rules = [
            'carnet_solicitante' => 'required|min_length[3]',
            'id_vehiculo'        => 'required|is_natural_no_zero',
            'id_tipo_problema'   => 'required|is_natural_no_zero',
            'descripcion'        => 'required|min_length[10]',
            'foto'               => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,5120]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Manejo del archivo adjunto
        $fotoPath = null;
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $uploadDir = ROOTPATH . 'public/uploads/solicitudes/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            $newName  = $foto->getRandomName();
            $foto->move($uploadDir, $newName);
            $fotoPath = 'uploads/solicitudes/' . $newName;
        }

        $carnet = trim($this->request->getPost('carnet_solicitante'));
        $conductorNombre = $this->session->get('conductor_nombre') ?? $carnet;
        $solicitanteNombre = "{$conductorNombre} [{$carnet}]";

        $tipoProblemaId = (int) $this->request->getPost('id_tipo_problema');
        $tipoProblema   = $this->catalogoModel->find($tipoProblemaId);

        $descripcion = $this->request->getPost('descripcion');
        if ($tipoProblema) {
            $descripcion .= "\nTipo de problema: {$tipoProblema['nombre']}";
        }

        $solicitudData = [
            'id_empresa'       => $this->empresaId,
            'id_vehiculo'      => (int) $this->request->getPost('id_vehiculo'),
            'id_solicitante'   => $this->conductorId,
            'id_tipo_problema' => $tipoProblemaId,
            'descripcion'      => $descripcion,
            'prioridad'        => '1',
            'estado'           => 'PENDIENTES',
            'fecha_solicitud'  => date('Y-m-d H:i:s'),
            'usuario_crea'     => $this->conductorId,
            'url_foto'         => $fotoPath,
            'solicitante'      => $solicitanteNombre,
        ];

        try {
            $result = $this->solicitudModel->save($solicitudData);

            if ($result) {
                $nuevaId = $this->solicitudModel->getInsertID();
                return redirect()->to('portal/solicitud/confirmacion/' . $nuevaId)
                                 ->with('success', 'Solicitud enviada correctamente.');
            }

            $errors = $this->solicitudModel->errors();
            return redirect()->back()->withInput()->with('error', 'Error al guardar: ' . implode(', ', $errors));

        } catch (\Exception $e) {
            log_message('error', 'PortalConductores::store - ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error inesperado: ' . $e->getMessage());
        }
    }

    /**
     * Pantalla de confirmación tras enviar la solicitud
     */
    public function confirmacion(int $id)
    {
        if ($redirect = $this->requireConductor()) return $redirect;

        $solicitud = $this->solicitudModel
            ->select('solicitudes.*, vehiculos.placa, vehiculos.marca, vehiculos.modelo')
            ->join('vehiculos', 'vehiculos.id = solicitudes.id_vehiculo', 'left')
            ->where('solicitudes.id', $id)
            ->first();

        if (!$solicitud) {
            return redirect()->to('portal/solicitud/crear')->with('error', 'Solicitud no encontrada.');
        }

        return view('portal_conductores/confirmacion', [
            'title'     => 'Solicitud Enviada',
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * AJAX: buscar vehículo por placa o código de unidad
     */
    public function buscarVehiculo()
    {
        $q = trim($this->request->getGet('q') ?? '');
        if (strlen($q) < 2) {
            return $this->response->setJSON(['ok' => false, 'mensaje' => 'Ingresa al menos 2 caracteres']);
        }

        $db = \Config\Database::connect();
        $qEsc = '%' . $db->escapeLikeString($q) . '%';

        $vehiculo = $db->query(
            "SELECT id, placa, marca, modelo, anio, codigo_consecutivo, codigo_unidad, numero_motor, numero_chasis
             FROM vehiculos
             WHERE id_empresa = ?
               AND estado = 'ACTIVO'
               AND (placa LIKE ?
                    OR codigo_consecutivo LIKE ?
                    OR codigo_unidad LIKE ?
                    OR numero_motor LIKE ?
                    OR numero_chasis LIKE ?
                    OR marca LIKE ?
                    OR modelo LIKE ?
                    OR CONCAT(marca, ' ', modelo) LIKE ?)
             LIMIT 1",
            [$this->empresaId, $qEsc, $qEsc, $qEsc, $qEsc, $qEsc, $qEsc, $qEsc, $qEsc]
        )->getRowArray();

        if (!$vehiculo) {
            return $this->response->setJSON(['ok' => false, 'mensaje' => 'No se encontró ningún vehículo activo con ese código o placa.']);
        }

        return $this->response->setJSON([
            'ok'           => true,
            'id'           => $vehiculo['id'],
            'placa'        => $vehiculo['placa'],
            'marca'        => $vehiculo['marca'] ?? '',
            'modelo'       => $vehiculo['modelo'] ?? '',
            'anio'         => $vehiculo['anio'] ?? '',
            'codigo'       => $vehiculo['codigo_consecutivo'] ?? '',
            'codigo_unidad'=> $vehiculo['codigo_unidad'] ?? '',
            'motor'        => $vehiculo['numero_motor'] ?? '',
            'chasis'       => $vehiculo['numero_chasis'] ?? '',
        ]);
    }

    /**
     * Historial de solicitudes del conductor
     */
    public function historial()
    {
        if ($redirect = $this->requireConductor()) return $redirect;

        $solicitudes = $this->solicitudModel
            ->select('solicitudes.*, vehiculos.placa, vehiculos.marca, vehiculos.modelo')
            ->join('vehiculos', 'vehiculos.id = solicitudes.id_vehiculo', 'left')
            ->where('solicitudes.id_empresa', $this->empresaId)
            ->where('solicitudes.id_solicitante', $this->conductorId)
            ->orderBy('solicitudes.fecha_solicitud', 'DESC')
            ->findAll();

        return view('portal_conductores/historial', [
            'title'       => 'Mis Solicitudes',
            'solicitudes' => $solicitudes,
        ]);
    }

    /**
     * Seguimiento detallado de una solicitud
     */
    public function seguimiento(int $id)
    {
        if ($redirect = $this->requireConductor()) return $redirect;

        $solicitud = $this->solicitudModel
            ->select('solicitudes.*, vehiculos.placa, vehiculos.marca, vehiculos.modelo, vehiculos.anio, vehiculos.codigo_consecutivo as vehiculo_codigo')
            ->join('vehiculos', 'vehiculos.id = solicitudes.id_vehiculo', 'left')
            ->where('solicitudes.id', $id)
            ->where('solicitudes.id_solicitante', $this->conductorId)
            ->first();

        if (!$solicitud) {
            return redirect()->to('portal/solicitud/historial')->with('error', 'Solicitud no encontrada');
        }

        // Obtener registro de trabajo si existe
        $registroTrabajo = $this->db->table('registro_trabajo_realizado')
            ->where('id_solicitud', $id)
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        // Obtener materiales si hay registro de trabajo
        $materiales = [];
        if ($registroTrabajo) {
            $materiales = $this->db->table('materiales_trabajo mt')
                ->select('mt.*, m.nombre as material_nombre, m.unidad_medida')
                ->join('materiales m', 'm.id = mt.id_material', 'left')
                ->where('mt.id_registro_trabajo', $registroTrabajo['id'])
                ->get()
                ->getResultArray();
        }

        // Tipo de problema
        $tipoProblema = null;
        if (!empty($solicitud['id_tipo_problema'])) {
            $tipoProblema = $this->catalogoModel->find($solicitud['id_tipo_problema']);
        }

        return view('portal_conductores/seguimiento', [
            'title'           => 'Seguimiento #' . $id,
            'solicitud'       => $solicitud,
            'registroTrabajo' => $registroTrabajo,
            'materiales'      => $materiales,
            'tipoProblema'    => $tipoProblema,
        ]);
    }
}
