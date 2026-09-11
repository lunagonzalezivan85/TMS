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
    protected $session;
    protected $empresaId;
    protected $usuarioId;

    public function __construct()
    {
        $this->solicitudModel = new SolicitudModel();
        $this->vehiculoModel  = new VehiculoModel();
        $this->catalogoModel  = new CatalogoModel();
        $this->session        = session();

        helper(['form', 'url']);

        // Si hay sesión, usar esos datos; si no, usar defaults (portal sin login)
        $this->empresaId = $this->session->get('empresa_id');
        $this->usuarioId = $this->session->get('user_id') ?? 0;

        if (!$this->empresaId) {
            // Sin sesión: usar la primera empresa disponible
            $db = \Config\Database::connect();
            $empresa = $db->table('empresas')->select('id')->orderBy('id', 'ASC')->limit(1)->get()->getRowArray();
            $this->empresaId = $empresa['id'] ?? 1;
        }
    }

    /**
     * Página principal del portal de conductores
     */
    public function index()
    {
        $solicitudes = $this->solicitudModel
            ->select('solicitudes.*, vehiculos.placa, vehiculos.marca, vehiculos.modelo')
            ->join('vehiculos', 'vehiculos.id = solicitudes.id_vehiculo', 'left')
            ->where('solicitudes.id_empresa', $this->empresaId)
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
        // Vehículos de la empresa activos
        $vehiculos = $this->vehiculoModel
            ->where('id_empresa', $this->empresaId)
            ->where('estado', 'ACTIVO')
            ->findAll();

        // Tipos de problema desde catálogo CAT-0010
        $tiposProblemaCatalogo = $this->catalogoModel->getCatalogosPorCodigo('CAT-0010');
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

        // Nombre del solicitante: nombre de sesión + carnet, o solo carnet si no hay sesión
        $nombreSesion      = $this->session->get('nombre') ?? $this->session->get('usuario') ?? '';
        $solicitanteNombre = $nombreSesion ? "{$nombreSesion} [{$carnet}]" : $carnet;

        $tipoProblemaId = (int) $this->request->getPost('id_tipo_problema');
        $tipoProblema   = $this->catalogoModel->find($tipoProblemaId);

        $descripcion = $this->request->getPost('descripcion');
        if ($tipoProblema) {
            $descripcion .= "\nTipo de problema: {$tipoProblema['nombre']}";
        }

        $solicitudData = [
            'id_empresa'       => $this->empresaId,
            'id_vehiculo'      => (int) $this->request->getPost('id_vehiculo'),
            'id_solicitante'   => $this->usuarioId,
            'id_tipo_problema' => $tipoProblemaId,
            'descripcion'      => $descripcion,
            'prioridad'        => '1',
            'estado'           => 'PENDIENTES',
            'fecha_solicitud'  => date('Y-m-d H:i:s'),
            'usuario_crea'     => $this->usuarioId,
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
            "SELECT id, placa, marca, modelo, anio, codigo_consecutivo, numero_motor
             FROM vehiculos
             WHERE id_empresa = ?
               AND estado = 'ACTIVO'
               AND (placa LIKE ? OR codigo_consecutivo LIKE ? OR numero_motor LIKE ?)
             LIMIT 1",
            [$this->empresaId, $qEsc, $qEsc, $qEsc]
        )->getRowArray();

        if (!$vehiculo) {
            return $this->response->setJSON(['ok' => false, 'mensaje' => 'No se encontró ningún vehículo activo con ese código o placa.']);
        }

        return $this->response->setJSON([
            'ok'      => true,
            'id'      => $vehiculo['id'],
            'placa'   => $vehiculo['placa'],
            'marca'   => $vehiculo['marca'] ?? '',
            'modelo'  => $vehiculo['modelo'] ?? '',
            'anio'    => $vehiculo['anio'] ?? '',
            'codigo'  => $vehiculo['codigo_consecutivo'] ?? '',
        ]);
    }

    /**
     * Historial de solicitudes
     */
    public function historial()
    {
        $builder = $this->solicitudModel
            ->select('solicitudes.*, vehiculos.placa, vehiculos.marca, vehiculos.modelo')
            ->join('vehiculos', 'vehiculos.id = solicitudes.id_vehiculo', 'left')
            ->where('solicitudes.id_empresa', $this->empresaId);

        // Si hay sesión, filtrar por usuario; si no, mostrar todas
        if ($this->usuarioId > 0) {
            $builder->where('solicitudes.id_solicitante', $this->usuarioId);
        }

        $solicitudes = $builder->orderBy('solicitudes.fecha_solicitud', 'DESC')->findAll();

        return view('portal_conductores/historial', [
            'title'       => 'Mis Solicitudes',
            'solicitudes' => $solicitudes,
        ]);
    }
}
