<?php

namespace App\Controllers;

use App\Models\HistorialOrdenTrabajoModel;
use App\Models\SolicitudModel;
use CodeIgniter\API\ResponseTrait;

class HistorialOrdenTrabajo extends BaseController
{
    use ResponseTrait;

    protected $historialModel;
    protected $solicitudModel;

    public function __construct()
    {
        $this->historialModel = new HistorialOrdenTrabajoModel();
        $this->solicitudModel = new SolicitudModel();
        helper(['form', 'url']);
    }

    // List all history records
    public function index()
    {
        $data = [
            'title' => 'Historial de Órdenes de Trabajo',
            'historial' => $this->historialModel->orderBy('fecha_registro', 'DESC')->findAll()
        ];
        return view('historial_orden_trabajo/index', $data);
    }

    // Show create form with solicitud search
    public function create($idSolicitud = null)
    {
        $data = [
            'title' => 'Nuevo Registro de Historial'
        ];

        // If solicitud ID is provided, load that specific solicitud
        if ($idSolicitud) {
            $solicitud = $this->solicitudModel->find($idSolicitud);
            if ($solicitud) {
                $data['solicitud'] = $solicitud;
                $data['historial'] = $this->historialModel->where('id_solicitud', $idSolicitud)
                    ->orderBy('fecha_registro', 'DESC')
                    ->findAll();
            } else {
                return redirect()->back()->with('error', 'La solicitud especificada no existe');
            }
        } else {
            // If no ID provided, get all non-closed solicitudes for search
            $data['solicitudes'] = $this->solicitudModel
                ->where('estado !=', 'CERRADO')
                ->orderBy('fecha_solicitud', 'DESC')
                ->findAll();
        }

        return view('historial_orden_trabajo/create', $data);
    }

    // Search solicitudes for the create form
    public function searchSolicitudes()
    {
        $search = $this->request->getGet('search');
        
        $builder = $this->solicitudModel
            ->where('estado !=', 'CERRADO');
            
        if (!empty($search)) {
            $builder->groupStart()
                ->like('codigo_consecutivo', $search)
                ->orLike('descripcion', $search)
                ->orLike('placa', $search, 'after')
                ->groupEnd();
        }
        
        $solicitudes = $builder->orderBy('fecha_solicitud', 'DESC')
            ->findAll(10); // Limit to 10 results

        return $this->response->setJSON($solicitudes);
    }

    // Store a new history record
    public function store()
    {
        $rules = [
            'id_solicitud' => 'required|is_not_unique[solicitudes.id]',
            'estado' => 'required',
            'comentario' => 'permit_empty|string',
            'referencia' => 'permit_empty|string|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idSolicitud = $this->request->getPost('id_solicitud');
        $nuevoEstado = $this->request->getPost('estado');

        // Get current solicitud state
        $solicitud = $this->solicitudModel->find($idSolicitud);
        if (!$solicitud) {
            return redirect()->back()->withInput()->with('error', 'Solicitud no encontrada');
        }

        $estadoActual = $solicitud['estado'];

        // Validate state transition logic
        $estadoValido = $this->validarTransicionEstado($estadoActual, $nuevoEstado);

        if (!$estadoValido) {
            return redirect()->back()->withInput()->with('error',
                "No se puede cambiar de estado '{$estadoActual}' a '{$nuevoEstado}'. Verifique la lógica de transiciones.");
        }

        $resultado = $this->solicitudModel->cambiarEstado($solicitudId,  $nuevoEstado);

        $data = [
            'id_solicitud' => $idSolicitud,
            'estado' => $nuevoEstado,
            'comentario' => $this->request->getPost('comentario'),
            'referencia' => $this->request->getPost('referencia'),
            'usuario_registra' => session()->get('user_id') ?? 1 // Default to 1 if not logged in
        ];

        if ($this->historialModel->save($data)) {
            // Update solicitud status if needed
            if ($solicitud['estado'] !== $nuevoEstado) {
                $this->solicitudModel->update($idSolicitud, ['estado' => $nuevoEstado]);
            }

            return redirect()->to("/historial-orden-trabajo/create/{$idSolicitud}")
                ->with('message', 'Registro de historial guardado correctamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al guardar el registro de historial');
    }

    /**
     * Validate state transition according to business logic
     */
    private function validarTransicionEstado($estadoActual, $nuevoEstado)
    {
        // Define valid transitions
        $transicionesValidas = [
            'PENDIENTE' => ['EN_PROCESO', 'APROBADO'],
            'EN_PROCESO' => ['FINALIZADO', 'EN_PAUSA'],
            'EN_PAUSA' => ['EN_PROCESO'],
            'APROBADO' => ['EN_PROCESO', 'FINALIZADO'],
            'FINALIZADO' => [] // Estado final, no se puede cambiar
        ];

        // Check if transition is valid
        return in_array($nuevoEstado, $transicionesValidas[$estadoActual] ?? []);
    }

    // Show a single history record
    public function show($id = null)
    {
        $historial = $this->historialModel->find($id);
        if (!$historial) {
            return redirect()->to('/historial-orden-trabajo')->with('error', 'Registro no encontrado');
        }

        $data = [
            'title' => 'Detalles del Historial',
            'historial' => $historial,
            'solicitud' => $this->solicitudModel->find($historial['id_solicitud'])
        ];

        return view('historial_orden_trabajo/show', $data);
    }

    // Delete a history record
    public function delete($id)
    {
        $historial = $this->historialModel->find($id);
        if (!$historial) {
            return redirect()->back()->with('error', 'Registro no encontrado');
        }

        if ($this->historialModel->delete($id)) {
            return redirect()->back()->with('message', 'Registro eliminado correctamente');
        }

        return redirect()->back()->with('error', 'Error al eliminar el registro');
    }

    // Get history for a specific solicitud (AJAX)
    public function getHistorialSolicitud($idSolicitud)
    {
        $historial = $this->historialModel
            ->where('id_solicitud', $idSolicitud)
            ->orderBy('fecha_registro', 'DESC')
            ->findAll();
            
        return $this->response->setJSON($historial);
    }
}
