<?php

namespace App\Controllers;

use App\Models\TipoOperacionModel;
use CodeIgniter\HTTP\ResponseInterface;

class TipoOperacion extends BaseController
{
    protected $tipoOperacionModel;
    protected $db;

    public function __construct()
    {
        $this->tipoOperacionModel = new TipoOperacionModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Lista principal de tipos de operación
     */
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $data = [
            'title' => 'Gestión de Tipos de Operación - GMV',
            'page_title' => 'Gestión de Tipos de Operación'
        ];

        return view('tipo_operacion/index', $data);
    }

    /**
     * Obtener datos para DataTables
     */
    public function getData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        // Parámetros de DataTables
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start') ?? 0;
        $length = $this->request->getPost('length') ?? 10;
        $searchValue = $this->request->getPost('search')['value'] ?? $this->request->getPost('search') ?? '';

        // Filtros adicionales
        $filtros = [
            'estado' => $this->request->getPost('estado')
        ];
        
        // Solo agregar búsqueda si hay un valor
        if (!empty($searchValue)) {
            $filtros['search'] = $searchValue;
        }

        // Obtener datos
        $tiposOperacion = $this->tipoOperacionModel->getTiposOperacionConFiltros($filtros, $length, $start);
        $totalRecords = $this->tipoOperacionModel->contarTiposOperacionConFiltros([]);
        $filteredRecords = $this->tipoOperacionModel->contarTiposOperacionConFiltros($filtros);

        // Formatear datos para DataTables
        $data = [];
        foreach ($tiposOperacion as $tipo) {
            $data[] = [
                'id' => $tipo['id'],
                'descripcion' => $tipo['descripcion'],
                'estado' => $tipo['estado'],
                'fechaRegistra' => $tipo['fechaRegistra'],
                'fechaActualiza' => $tipo['fechaActualiza'],
                'UsuarioCrea' => $tipo['UsuarioCrea'],
                'UsuarioEdita' => $tipo['UsuarioEdita'],
                'acciones' => $this->generarAcciones($tipo)
            ];
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
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
            'title' => 'Crear Tipo de Operación - GMV',
            'page_title' => 'Crear Tipo de Operación'
        ];

        return view('tipo_operacion/create', $data);
    }

    /**
     * Procesar creación de tipo de operación
     */
    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        // Validaciones
        $rules = [
            'descripcion' => 'required|min_length[3]|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $descripcion = trim($this->request->getPost('descripcion'));

        // Verificar si la descripción ya existe
        if ($this->tipoOperacionModel->existeDescripcion($descripcion)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Ya existe un tipo de operación con esa descripción');
        }

        // Preparar datos
        $data = [
            'descripcion' => $descripcion,
            'estado' => 'ACTIVO'
        ];

        try {
            if ($this->tipoOperacionModel->save($data)) {
                return redirect()->to('tipo-operacion')
                               ->with('success', 'Tipo de operación creado exitosamente');
            } else {
                $errors = $this->tipoOperacionModel->errors();
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Error al crear el tipo de operación: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al crear tipo de operación: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error interno al crear el tipo de operación');
        }
    }

    /**
     * Mostrar detalles del tipo de operación
     */
    public function show($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $tipoOperacion = $this->tipoOperacionModel->find($id);
        
        if (!$tipoOperacion) {
            return redirect()->to('tipo-operacion')
                           ->with('error', 'Tipo de operación no encontrado');
        }

        $data = [
            'title' => 'Detalles del Tipo de Operación - GMV',
            'page_title' => 'Detalles del Tipo de Operación',
            'tipoOperacion' => $tipoOperacion
        ];

        return view('tipo_operacion/show', $data);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $tipoOperacion = $this->tipoOperacionModel->find($id);
        
        if (!$tipoOperacion) {
            return redirect()->to('tipo-operacion')
                           ->with('error', 'Tipo de operación no encontrado');
        }

        $data = [
            'title' => 'Editar Tipo de Operación - GMV',
            'page_title' => 'Editar Tipo de Operación',
            'tipoOperacion' => $tipoOperacion
        ];

        return view('tipo_operacion/edit', $data);
    }

    /**
     * Procesar actualización de tipo de operación
     */
    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $tipoOperacion = $this->tipoOperacionModel->find($id);
        
        if (!$tipoOperacion) {
            return redirect()->to('tipo-operacion')
                           ->with('error', 'Tipo de operación no encontrado');
        }

        // Validaciones
        $rules = [
            'descripcion' => 'required|min_length[3]|max_length[255]',
            'estado' => 'required|in_list[ACTIVO,INACTIVO]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $descripcion = trim($this->request->getPost('descripcion'));

        // Verificar si la descripción ya existe (excluyendo el registro actual)
        if ($this->tipoOperacionModel->existeDescripcion($descripcion, $id)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Ya existe un tipo de operación con esa descripción');
        }

        // Preparar datos
        $data = [
            'descripcion' => $descripcion,
            'estado' => $this->request->getPost('estado')
        ];

        try {
            if ($this->tipoOperacionModel->update($id, $data)) {
                return redirect()->to('tipo-operacion')
                               ->with('success', 'Tipo de operación actualizado exitosamente');
            } else {
                $errors = $this->tipoOperacionModel->errors();
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Error al actualizar el tipo de operación: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar tipo de operación: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error interno al actualizar el tipo de operación');
        }
    }

    /**
     * Cambiar estado del tipo de operación
     */
    public function cambiarEstado()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no autorizado']);
        }

        $id = $this->request->getPost('id');
        $nuevoEstado = $this->request->getPost('estado');

        if (!$id || !in_array($nuevoEstado, ['ACTIVO', 'INACTIVO'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos'
            ]);
        }

        $tipoOperacion = $this->tipoOperacionModel->find($id);
        if (!$tipoOperacion) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipo de operación no encontrado'
            ]);
        }

        try {
            if ($this->tipoOperacionModel->cambiarEstado($id, $nuevoEstado)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estado actualizado correctamente',
                    'nuevo_estado' => $nuevoEstado
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el estado'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar estado: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno al cambiar el estado'
            ]);
        }
    }

    /**
     * Eliminar tipo de operación
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no autorizado']);
        }

        $tipoOperacion = $this->tipoOperacionModel->find($id);
        if (!$tipoOperacion) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipo de operación no encontrado'
            ]);
        }

        // Verificar si está en uso
        if ($this->tipoOperacionModel->estaEnUso($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se puede eliminar porque está siendo utilizado'
            ]);
        }

        try {
            if ($this->tipoOperacionModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de operación eliminado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el tipo de operación'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar tipo de operación: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno al eliminar el tipo de operación'
            ]);
        }
    }

    /**
     * Obtener estadísticas de tipos de operación
     */
    public function getEstadisticas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        try {
            $estadisticas = $this->tipoOperacionModel->getEstadisticasTipoOperacion();
            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ]);
        }
    }

    /**
     * Verificar si descripción ya existe
     */
    public function verificarDescripcion()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $descripcion = $this->request->getPost('descripcion');
        $excludeId = $this->request->getPost('exclude_id');

        $existe = $this->tipoOperacionModel->existeDescripcion($descripcion, $excludeId);

        return $this->response->setJSON([
            'existe' => $existe,
            'message' => $existe ? 'La descripción ya está en uso' : 'Descripción disponible'
        ]);
    }

    /**
     * Generar acciones para DataTables
     */
    private function generarAcciones($tipoOperacion)
    {
        $acciones = '<div class="btn-group" role="group">';
        
        // Botón ver
        $acciones .= '<a href="' . base_url('tipo-operacion/show/' . $tipoOperacion['id']) . '" 
                         class="btn btn-sm btn-info" title="Ver detalles">
                         <i class="fas fa-eye"></i>
                      </a>';
        
        // Botón editar
        $acciones .= '<a href="' . base_url('tipo-operacion/edit/' . $tipoOperacion['id']) . '" 
                         class="btn btn-sm btn-warning" title="Editar">
                         <i class="fas fa-edit"></i>
                      </a>';
        
        // Botón cambiar estado
        $estadoClass = $tipoOperacion['estado'] === 'ACTIVO' ? 'btn-success' : 'btn-secondary';
        $estadoIcon = $tipoOperacion['estado'] === 'ACTIVO' ? 'fa-toggle-on' : 'fa-toggle-off';
        $nuevoEstado = $tipoOperacion['estado'] === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO';
        
        $acciones .= '<button type="button" 
                             class="btn btn-sm ' . $estadoClass . ' btn-cambiar-estado" 
                             data-id="' . $tipoOperacion['id'] . '" 
                             data-estado="' . $nuevoEstado . '"
                             title="Cambiar estado">
                             <i class="fas ' . $estadoIcon . '"></i>
                      </button>';
        
        // Botón eliminar
        $acciones .= '<button type="button" 
                             class="btn btn-sm btn-danger btn-eliminar" 
                             data-id="' . $tipoOperacion['id'] . '"
                             title="Eliminar">
                             <i class="fas fa-trash"></i>
                      </button>';
        
        $acciones .= '</div>';
        
        return $acciones;
    }
}
