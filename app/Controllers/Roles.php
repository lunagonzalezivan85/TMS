<?php

namespace App\Controllers;

use App\Models\RolModel;

class Roles extends BaseController
{
    protected $rolModel;

    public function __construct()
    {
        $this->rolModel = new RolModel();
    }

    /**
     * Listar roles
     */
    public function index()
    {
        // Solo administradores pueden gestionar roles
        if (session()->get('rol_name') !== 'Administrador') {
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $data = [
            'title' => 'Gestión de Roles - GMV',
            'roles' => $this->rolModel->getRolesConUsuarios(),
            'permisos' => $this->rolModel->getPermisosPorRol()
        ];

        return view('roles/index', $data);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        if (session()->get('rol_name') !== 'Administrador') {
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $data = [
            'title' => 'Crear Rol - GMV',
            'validation' => session()->getFlashdata('validation')
        ];

        return view('roles/create', $data);
    }

    /**
     * Guardar nuevo rol
     */
    public function store()
    {
        if (session()->get('rol_name') !== 'Administrador') {
            return $this->response->setJSON(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $rules = [
            'nombre' => 'required|max_length[50]|is_unique[roles.nombre]',
            'descripcion' => 'permit_empty|max_length[200]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => 'Datos inválidos', 'validation' => $this->validator->getErrors()]);
        }

        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion')
        ];

        $resultado = $this->rolModel->crearRol($datos);

        if (isset($resultado['success'])) {
            return $this->response->setJSON(['success' => 'Rol creado correctamente', 'id' => $resultado['id']]);
        }

        return $this->response->setJSON(['error' => $resultado['error']]);
    }

    /**
     * Mostrar rol específico
     */
    public function show($id)
    {
        if (session()->get('rol_name') !== 'Administrador') {
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $rol = $this->rolModel->find($id);

        if (!$rol) {
            return redirect()->to(base_url('roles'))->with('error', 'Rol no encontrado');
        }

        // Obtener usuarios con este rol
        $authModel = new \App\Models\AuthModel();
        $usuarios = $authModel->where('id_rol', $id)->findAll();

        $permisos = $this->rolModel->getPermisosPorRol();

        $data = [
            'title' => 'Detalle Rol - GMV',
            'rol' => $rol,
            'usuarios' => $usuarios,
            'permisos' => $permisos[$rol['nombre']] ?? []
        ];

        return view('roles/show', $data);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        if (session()->get('rol_name') !== 'Administrador') {
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $rol = $this->rolModel->find($id);

        if (!$rol) {
            return redirect()->to(base_url('roles'))->with('error', 'Rol no encontrado');
        }

        $data = [
            'title' => 'Editar Rol - GMV',
            'rol' => $rol,
            'validation' => session()->getFlashdata('validation')
        ];

        return view('roles/edit', $data);
    }

    /**
     * Actualizar rol
     */
    public function update($id)
    {
        if (session()->get('rol_name') !== 'Administrador') {
            return $this->response->setJSON(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $rol = $this->rolModel->find($id);
        if (!$rol) {
            return $this->response->setJSON(['error' => 'Rol no encontrado']);
        }

        $rules = [
            'nombre' => "required|max_length[50]|is_unique[roles.nombre,id,{$id}]",
            'descripcion' => 'permit_empty|max_length[200]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => 'Datos inválidos', 'validation' => $this->validator->getErrors()]);
        }

        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion')
        ];

        if ($this->rolModel->update($id, $datos)) {
            return $this->response->setJSON(['success' => 'Rol actualizado correctamente']);
        }

        return $this->response->setJSON(['error' => 'No se pudo actualizar el rol']);
    }

    /**
     * Eliminar rol
     */
    public function delete($id)
    {
        if (session()->get('rol_name') !== 'Administrador') {
            return $this->response->setJSON(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $resultado = $this->rolModel->eliminarRol($id);

        if (isset($resultado['success'])) {
            return $this->response->setJSON(['success' => 'Rol eliminado correctamente']);
        }

        return $this->response->setJSON(['error' => $resultado['error']]);
    }

    /**
     * Obtener roles para DataTable (AJAX)
     */
    public function getRolesAjax()
    {
        if (session()->get('rol_name') !== 'Administrador') {
            return $this->response->setJSON(['error' => 'No tienes permisos']);
        }

        $roles = $this->rolModel->getRolesConUsuarios();

        $data = [];
        foreach ($roles as $rol) {
            $acciones = '
                <div class="btn-group" role="group">
                    <a href="' . base_url('roles/' . $rol['id']) . '" class="btn btn-sm btn-info" title="Ver">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="' . base_url('roles/' . $rol['id'] . '/edit') . '" class="btn btn-sm btn-warning" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>';

            // Solo permitir eliminar si no tiene usuarios asignados
            if ($rol['total_usuarios'] == 0) {
                $acciones .= '
                    <button class="btn btn-sm btn-danger" onclick="eliminarRol(' . $rol['id'] . ')" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>';
            }

            $acciones .= '</div>';

            $data[] = [
                'id' => $rol['id'],
                'nombre' => $rol['nombre'],
                'descripcion' => $rol['descripcion'] ?: '-',
                'total_usuarios' => $rol['total_usuarios'],
                'acciones' => $acciones
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    /**
     * Obtener permisos de un rol específico (AJAX)
     */
    public function getPermisos($rolNombre)
    {
        if (session()->get('rol_name') !== 'Administrador') {
            return $this->response->setJSON(['error' => 'No tienes permisos']);
        }

        $permisos = $this->rolModel->getPermisosPorRol();
        
        if (isset($permisos[$rolNombre])) {
            return $this->response->setJSON(['permisos' => $permisos[$rolNombre]]);
        }

        return $this->response->setJSON(['permisos' => []]);
    }
}
