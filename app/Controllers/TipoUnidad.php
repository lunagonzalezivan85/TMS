<?php

namespace App\Controllers;

use App\Models\TipoUnidadModel;

class TipoUnidad extends BaseController
{
    protected $tipoUnidadModel;
    
    public function __construct()
    {
        $this->tipoUnidadModel = new TipoUnidadModel();
    }

    /**
     * Mostrar lista de tipos de unidad
     */
    public function index()
    {
        $data = [
            'page_title' => 'Gestión de Tipos de Unidad',
            'breadcrumb' => [
                ['title' => 'Inicio', 'url' => base_url('dashboard')],
                ['title' => 'Tipos de Unidad', 'url' => '']
            ]
        ];

        return view('tipo_unidad/index', $data);
    }

    /**
     * Obtener datos para DataTables
     */
    public function getData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $request = $this->request->getPost();
        
        // Parámetros de DataTables
        $draw = $request['draw'] ?? 1;
        $start = $request['start'] ?? 0;
        $length = $request['length'] ?? 10;
        $searchValue = $request['search']['value'] ?? '';
        
        // Filtros adicionales
        $filtros = [
            'estado' => $request['estado'] ?? '',
            'busqueda' => $request['busqueda'] ?? $searchValue
        ];

        // Obtener datos
        $builder = $this->tipoUnidadModel->getTiposUnidad($filtros);
        
        // Total de registros sin filtrar
        $totalRecords = $this->tipoUnidadModel->countAll();
        
        // Total de registros filtrados
        $totalFiltered = $builder->countAllResults(false);
        
        // Obtener datos con paginación
        $datos = $builder->limit($length, $start)->get()->getResultArray();
        
        // Formatear datos para DataTables
        $data = [];
        foreach ($datos as $row) {
            $acciones = $this->generarBotonesAccion($row);
            
            $data[] = [
                'id' => $row['id'],
                'descripcion' => esc($row['descripcion']),
                'estado' => $row['estado'],
                'fechaRegistra' => $row['fechaRegistra'],
                'fechaActualiza' => $row['fechaActualiza'],
                'acciones' => $acciones
            ];
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $data = [
            'page_title' => 'Crear Tipo de Unidad',
            'breadcrumb' => [
                ['title' => 'Inicio', 'url' => base_url('dashboard')],
                ['title' => 'Tipos de Unidad', 'url' => base_url('tipo-unidad')],
                ['title' => 'Crear', 'url' => '']
            ]
        ];

        return view('tipo_unidad/create', $data);
    }

    /**
     * Guardar nuevo tipo de unidad
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Acceso no autorizado');
        }

        // Validaciones
        $rules = [
            'descripcion' => 'required|min_length[3]|max_length[255]',
            'estado' => 'required|in_list[ACTIVO,INACTIVO]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'descripcion' => trim($this->request->getPost('descripcion')),
            'estado' => $this->request->getPost('estado')
        ];

        // Verificar si ya existe la descripción
        if ($this->tipoUnidadModel->existeDescripcion($data['descripcion'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ya existe un tipo de unidad con esta descripción'
            ]);
        }

        try {
            $usuarioId = session('user_id') ?? 1;
            $id = $this->tipoUnidadModel->crearTipoUnidad($data, $usuarioId);

            if ($id) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de unidad creado exitosamente',
                    'redirect' => base_url('tipo-unidad')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear el tipo de unidad'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mostrar detalles de un tipo de unidad
     */
    public function show($id)
    {
        $tipoUnidad = $this->tipoUnidadModel->find($id);
        
        if (!$tipoUnidad) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Tipo de unidad no encontrado');
        }

        $data = [
            'page_title' => 'Detalles del Tipo de Unidad',
            'tipoUnidad' => $tipoUnidad,
            'breadcrumb' => [
                ['title' => 'Inicio', 'url' => base_url('dashboard')],
                ['title' => 'Tipos de Unidad', 'url' => base_url('tipo-unidad')],
                ['title' => 'Detalles', 'url' => '']
            ]
        ];

        return view('tipo_unidad/show', $data);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $tipoUnidad = $this->tipoUnidadModel->find($id);
        
        if (!$tipoUnidad) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Tipo de unidad no encontrado');
        }

        $data = [
            'page_title' => 'Editar Tipo de Unidad',
            'tipoUnidad' => $tipoUnidad,
            'breadcrumb' => [
                ['title' => 'Inicio', 'url' => base_url('dashboard')],
                ['title' => 'Tipos de Unidad', 'url' => base_url('tipo-unidad')],
                ['title' => 'Editar', 'url' => '']
            ]
        ];

        return view('tipo_unidad/edit', $data);
    }

    /**
     * Actualizar tipo de unidad
     */
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Acceso no autorizado');
        }

        $tipoUnidad = $this->tipoUnidadModel->find($id);
        if (!$tipoUnidad) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipo de unidad no encontrado'
            ]);
        }

        // Validaciones
        $rules = [
            'descripcion' => 'required|min_length[3]|max_length[255]',
            'estado' => 'required|in_list[ACTIVO,INACTIVO]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'descripcion' => trim($this->request->getPost('descripcion')),
            'estado' => $this->request->getPost('estado')
        ];

        // Verificar si ya existe la descripción (excluyendo el actual)
        if ($this->tipoUnidadModel->existeDescripcion($data['descripcion'], $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ya existe un tipo de unidad con esta descripción'
            ]);
        }

        try {
            $usuarioId = session('user_id') ?? 1;
            $actualizado = $this->tipoUnidadModel->actualizarTipoUnidad($id, $data, $usuarioId);

            if ($actualizado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de unidad actualizado exitosamente',
                    'redirect' => base_url('tipo-unidad')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el tipo de unidad'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar tipo de unidad
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $tipoUnidad = $this->tipoUnidadModel->find($id);
        if (!$tipoUnidad) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipo de unidad no encontrado'
            ]);
        }

        try {
            if ($this->tipoUnidadModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de unidad eliminado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el tipo de unidad'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se puede eliminar: puede estar en uso'
            ]);
        }
    }

    /**
     * Cambiar estado de tipo de unidad
     */
    public function cambiarEstado()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $id = $this->request->getPost('id');
        $nuevoEstado = $this->request->getPost('estado');

        if (!$id || !in_array($nuevoEstado, ['ACTIVO', 'INACTIVO'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos'
            ]);
        }

        try {
            $usuarioId = session('user_id') ?? 1;
            $actualizado = $this->tipoUnidadModel->cambiarEstado($id, $nuevoEstado, $usuarioId);

            if ($actualizado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Estado cambiado a {$nuevoEstado} exitosamente"
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al cambiar el estado'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener estadísticas
     */
    public function getEstadisticas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        try {
            $estadisticas = $this->tipoUnidadModel->getEstadisticasTiposUnidad();
            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ]);
        }
    }

    /**
     * Verificar si existe descripción (AJAX)
     */
    public function verificarDescripcion()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $descripcion = $this->request->getPost('descripcion');
        $excludeId = $this->request->getPost('exclude_id');

        $existe = $this->tipoUnidadModel->existeDescripcion($descripcion, $excludeId);

        return $this->response->setJSON([
            'exists' => $existe
        ]);
    }

    /**
     * Generar botones de acción para DataTables
     */
    private function generarBotonesAccion($row)
    {
        $botones = [];
        
        // Botón ver
        $botones[] = '<a href="' . base_url('tipo-unidad/show/' . $row['id']) . '" class="btn btn-info btn-sm" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                      </a>';
        
        // Botón editar
        $botones[] = '<a href="' . base_url('tipo-unidad/edit/' . $row['id']) . '" class="btn btn-warning btn-sm" title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>';
        
        // Botón cambiar estado
        $nuevoEstado = $row['estado'] === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO';
        $colorBoton = $row['estado'] === 'ACTIVO' ? 'btn-secondary' : 'btn-success';
        $iconoBoton = $row['estado'] === 'ACTIVO' ? 'fa-times' : 'fa-check';
        
        $botones[] = '<button class="btn ' . $colorBoton . ' btn-sm btn-cambiar-estado" 
                        data-id="' . $row['id'] . '" 
                        data-estado="' . $nuevoEstado . '" 
                        title="Cambiar a ' . $nuevoEstado . '">
                        <i class="fas ' . $iconoBoton . '"></i>
                      </button>';
        
        // Botón eliminar
        $botones[] = '<button class="btn btn-danger btn-sm btn-eliminar" 
                        data-id="' . $row['id'] . '" 
                        data-descripcion="' . esc($row['descripcion']) . '" 
                        title="Eliminar">
                        <i class="fas fa-trash"></i>
                      </button>';
        
        return implode(' ', $botones);
    }
}
