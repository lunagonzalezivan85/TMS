<?php

namespace App\Controllers;

use App\Models\TipoProblemaModel;

class TiposProblema extends BaseController
{
    protected $tipoProblemaModel;
    protected $session;

    public function __construct()
    {
        $this->tipoProblemaModel = new TipoProblemaModel();
        $this->session = session();
        helper(['form', 'url']);
    }

    public function index()
    {
       

        $empresaId = $this->session->get('empresa_id');
        
        $data = [
            'title' => 'Gestión de Tipos de Problema',
            'tipos' => $this->tipoProblemaModel->where('id_empresa', $empresaId)->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('tipos_problema/index', $data);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso no autorizado.'
            ]);
        }

        $empresaId = $this->session->get('empresa_id');
        $usuarioId = $this->session->get('usuario_id');

        $data = [
            'id_empresa' => $empresaId,
            'nombre' => $this->request->getPost('nombre'),
            'estado' => 'ACTIVO',
            'usuarioCrea' => $usuarioId
        ];

        if ($this->tipoProblemaModel->save($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tipo de problema guardado correctamente.',
                'data' => $data
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al guardar el tipo de problema.',
            'errors' => $this->tipoProblemaModel->errors()
        ]);
    }

    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso no autorizado.'
            ]);
        }

        $tipo = $this->tipoProblemaModel->find($id);
        
        if (!$tipo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipo de problema no encontrado.'
            ]);
        }

        $data = [
            'id' => $id,
            'nombre' => $this->request->getPost('nombre'),
            'estado' => $this->request->getPost('estado'),
            'usuarioEdita' => $this->session->get('usuario_id')
        ];

        if ($this->tipoProblemaModel->save($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tipo de problema actualizado correctamente.',
                'data' => $data
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al actualizar el tipo de problema.',
            'errors' => $this->tipoProblemaModel->errors()
        ]);
    }

    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso no autorizado.'
            ]);
        }

        $tipo = $this->tipoProblemaModel->find($id);
        
        if (!$tipo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipo de problema no encontrado.'
            ]);
        }

        if ($this->tipoProblemaModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tipo de problema eliminado correctamente.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al eliminar el tipo de problema.'
        ]);
    }

    public function get($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso no autorizado.'
            ]);
        }

        $tipo = $this->tipoProblemaModel->find($id);
        
        if (!$tipo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipo de problema no encontrado.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $tipo
        ]);
    }
}
