<?php

namespace App\Controllers;

use App\Models\TipoMotivoCombustibleModel;
use CodeIgniter\HTTP\ResponseInterface;

class TipoMotivoCombustible extends BaseController
{
    protected $tipoMotivoCombustibleModel;

    public function __construct()
    {
        $this->tipoMotivoCombustibleModel = new TipoMotivoCombustibleModel();
    }

    /**
     * Mostrar lista de tipos de motivo de combustible
     */
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $empresaId = session()->get('empresa_id');
        $tipos = $this->tipoMotivoCombustibleModel->getTiposMotivoCombustibleCompletos($empresaId);
        $estadisticas = $this->tipoMotivoCombustibleModel->getEstadisticas($empresaId);

        $data = [
            'title' => 'Tipos de Motivo de Combustible - GMV',
            'page_title' => 'Tipos de Motivo de Combustible',
            'tipos' => $tipos,
            'estadisticas' => $estadisticas
        ];

        return view('tipo_motivo_combustible/index', $data);
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
            'title' => 'Nuevo Tipo de Motivo de Combustible - GMV',
            'page_title' => 'Nuevo Tipo de Motivo de Combustible'
        ];

        return view('tipo_motivo_combustible/create', $data);
    }

    /**
     * Procesar creación
     */
    public function store()
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        $empresaId = session()->get('empresa_id');
        
        // Validar datos
        $rules = [
            'descripcion' => 'required|min_length[3]|max_length[255]',
            'estado' => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $descripcion = trim($this->request->getPost('descripcion'));
        
        // Verificar si ya existe
        if ($this->tipoMotivoCombustibleModel->existeDescripcion($descripcion, $empresaId)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['descripcion' => 'Ya existe un tipo con esta descripción']
            ]);
        }

        $data = [
            'descripcion' => $descripcion,
            'estado' => $this->request->getPost('estado') === 'ACTIVO' ? 1 : 0,
            'id_empresa' => $empresaId
        ];

        try {
            $id = $this->tipoMotivoCombustibleModel->insert($data);
            
            if ($id) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de motivo de combustible creado exitosamente',
                    'id' => $id
                ]);
            } else {
                // Obtener errores específicos del modelo
                $errors = $this->tipoMotivoCombustibleModel->errors();
                $dbError = $this->tipoMotivoCombustibleModel->db->error();
                
                log_message('error', 'Error al crear tipo de motivo de combustible - Errores modelo: ' . json_encode($errors));
                log_message('error', 'Error al crear tipo de motivo de combustible - Error DB: ' . json_encode($dbError));
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear el tipo de motivo de combustible',
                    'debug' => [
                        'model_errors' => $errors,
                        'db_error' => $dbError
                    ]
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al crear tipo de motivo de combustible: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mostrar detalles
     */
    public function show($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $empresaId = session()->get('empresa_id');
        $tipo = $this->tipoMotivoCombustibleModel->getTipoMotivoCombustibleCompleto($id, $empresaId);

        if (!$tipo) {
            return redirect()->to(base_url('tipo-motivo-combustible'))->with('error', 'Tipo no encontrado');
        }

        $data = [
            'title' => 'Detalles del Tipo - GMV',
            'page_title' => 'Detalles del Tipo de Motivo de Combustible',
            'tipo' => $tipo
        ];

        return view('tipo_motivo_combustible/show', $data);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Debe iniciar sesión');
        }

        $empresaId = session()->get('empresa_id');
        $tipo = $this->tipoMotivoCombustibleModel->getTipoMotivoCombustibleCompleto($id, $empresaId);

        if (!$tipo) {
            return redirect()->to(base_url('tipo-motivo-combustible'))->with('error', 'Tipo no encontrado');
        }

        $data = [
            'title' => 'Editar Tipo - GMV',
            'page_title' => 'Editar Tipo de Motivo de Combustible',
            'tipo' => $tipo
        ];

        return view('tipo_motivo_combustible/edit', $data);
    }

    /**
     * Procesar actualización
     */
    public function update($id)
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        $empresaId = session()->get('empresa_id');
        $tipo = $this->tipoMotivoCombustibleModel->find($id);

        if (!$tipo || $tipo['id_empresa'] != $empresaId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipo no encontrado'
            ]);
        }

        // Validar datos
        $rules = [
            'descripcion' => 'required|min_length[3]|max_length[255]',
            'estado' => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $descripcion = trim($this->request->getPost('descripcion'));
        
        // Verificar si ya existe (excluyendo el actual)
        if ($this->tipoMotivoCombustibleModel->existeDescripcion($descripcion, $empresaId, $id)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['descripcion' => 'Ya existe un tipo con esta descripción']
            ]);
        }

        $data = [
            'descripcion' => $descripcion,
            'estado' => $this->request->getPost('estado') === 'ACTIVO' ? 1 : 0
        ];

        try {
            $updated = $this->tipoMotivoCombustibleModel->update($id, $data);
            
            if ($updated) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de motivo de combustible actualizado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el tipo de motivo de combustible'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar tipo de motivo de combustible: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Eliminar tipo
     */
    public function delete($id)
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        $empresaId = session()->get('empresa_id');
        $tipo = $this->tipoMotivoCombustibleModel->find($id);

        if (!$tipo || $tipo['id_empresa'] != $empresaId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipo no encontrado'
            ]);
        }

        try {
            $deleted = $this->tipoMotivoCombustibleModel->delete($id);
            
            if ($deleted) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tipo de motivo de combustible eliminado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el tipo de motivo de combustible'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar tipo de motivo de combustible: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se puede eliminar este tipo porque está siendo utilizado'
            ]);
        }
    }

    /**
     * Cambiar estado
     */
    public function cambiarEstado()
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debe iniciar sesión'
            ]);
        }

        $id = $this->request->getPost('id');
        $estado = $this->request->getPost('estado');
        $empresaId = session()->get('empresa_id');

        if (!in_array($estado, ['ACTIVO', 'INACTIVO'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Estado inválido'
            ]);
        }

        try {
            $updated = $this->tipoMotivoCombustibleModel->cambiarEstado($id, $estado, $empresaId);
            
            if ($updated) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estado actualizado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al cambiar el estado'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar estado: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Verificar si descripción existe (AJAX)
     */
    public function verificarDescripcion()
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['disponible' => false]);
        }

        $descripcion = $this->request->getPost('descripcion');
        $id = $this->request->getPost('id');
        $empresaId = session()->get('empresa_id');

        $existe = $this->tipoMotivoCombustibleModel->existeDescripcion($descripcion, $empresaId, $id);

        return $this->response->setJSON(['disponible' => !$existe]);
    }
}
