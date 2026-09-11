<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\EmpresaModel;
use App\Models\RolModel;

class Profile extends BaseController
{
    protected $usuarioModel;
    protected $empresaModel;
    protected $rolModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->empresaModel = new EmpresaModel();
        $this->rolModel = new RolModel();
    }

    /**
     * Mostrar perfil del usuario
     * Si no se proporciona ID, muestra el perfil del usuario logueado
     * Si se proporciona ID, muestra el perfil de ese usuario específico
     */
    public function index($userId = null)
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Debes iniciar sesión para acceder a esta página');
        }

        // Si no se proporciona ID, usar el del usuario logueado
        if ($userId === null) {
            $userId = session()->get('user_id');
            $isOwnProfile = true;
        } else {
            $isOwnProfile = (session()->get('user_id') == $userId);
        }

        // Obtener datos completos del usuario
        $userData = $this->getUserCompleteData($userId);

        if (!$userData) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Usuario no encontrado');
        }

        // Verificar permisos: solo puede ver perfiles de su misma empresa (excepto su propio perfil)
        if (!$isOwnProfile && $userData['id_empresa'] != session()->get('empresa_id')) {
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para ver este perfil');
        }

        $data = [
            'title' => $isOwnProfile ? 'Mi Perfil' : 'Perfil de ' . $userData['nombre'],
            'user' => $userData,
            'isOwnProfile' => $isOwnProfile,
            'canEdit' => $isOwnProfile || $this->canEditUser($userData['id'])
        ];

        return view('profile/index', $data);
    }

    /**
     * Mostrar formulario de edición de perfil
     */
    public function edit($userId = null)
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        // Si no se proporciona ID, usar el del usuario logueado
        if ($userId === null) {
            $userId = session()->get('user_id');
            $isOwnProfile = true;
        } else {
            $isOwnProfile = (session()->get('user_id') == $userId);
        }

        // Obtener datos del usuario
        $userData = $this->getUserCompleteData($userId);

        if (!$userData) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Usuario no encontrado');
        }

        // Verificar permisos de edición
        if (!$this->canEditUser($userId)) {
            return redirect()->to(base_url('profile/' . $userId))->with('error', 'No tienes permisos para editar este perfil');
        }

        // Obtener roles disponibles (solo para administradores)
        $roles = [];
        if (session()->get('rol_name') === 'Administrador') {
            $roles = $this->rolModel->where('estado', 'ACTIVO')->findAll();
        }

        $data = [
            'title' => $isOwnProfile ? 'Editar Mi Perfil' : 'Editar Perfil de ' . $userData['nombre'],
            'user' => $userData,
            'roles' => $roles,
            'isOwnProfile' => $isOwnProfile,
            'canChangeRole' => session()->get('rol_name') === 'Administrador' && !$isOwnProfile
        ];

        return view('profile/edit', $data);
    }

    /**
     * Procesar actualización de perfil
     */
    public function update($userId = null)
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        // Si no se proporciona ID, usar el del usuario logueado
        if ($userId === null) {
            $userId = session()->get('user_id');
        }

        // Verificar permisos de edición
        if (!$this->canEditUser($userId)) {
            return redirect()->to(base_url('profile/' . $userId))->with('error', 'No tienes permisos para editar este perfil');
        }

        $isOwnProfile = (session()->get('user_id') == $userId);

        // Reglas de validación
        $rules = [
            'nombre' => 'required|min_length[2]|max_length[100]',
            'correo' => "required|valid_email|is_unique[usuarios.correo,id,{$userId}]",
            'telefono' => 'permit_empty|min_length[7]|max_length[20]'
        ];

        // Solo administradores pueden cambiar el rol de otros usuarios
        if (session()->get('rol_name') === 'Administrador' && !$isOwnProfile) {
            $rules['id_rol'] = 'required|integer';
        }

        // Si se proporciona nueva contraseña, validarla
        if ($this->request->getPost('nueva_clave')) {
            $rules['nueva_clave'] = 'min_length[6]';
            $rules['confirmar_clave'] = 'matches[nueva_clave]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Preparar datos para actualizar
        $updateData = [
            'nombre' => $this->request->getPost('nombre'),
            'correo' => $this->request->getPost('correo'),
            'telefono' => $this->request->getPost('telefono'),
            'fechaUpdate' => date('Y-m-d H:i:s'),
            'usuarioEdita' => session()->get('user_id')
        ];

        // Agregar rol si el usuario puede cambiarlo
        if (session()->get('rol_name') === 'Administrador' && !$isOwnProfile) {
            $updateData['id_rol'] = $this->request->getPost('id_rol');
        }

        // Agregar nueva contraseña si se proporcionó
        if ($this->request->getPost('nueva_clave')) {
            $updateData['clave'] = password_hash($this->request->getPost('nueva_clave'), PASSWORD_DEFAULT);
        }

        // Actualizar usuario
        if ($this->usuarioModel->update($userId, $updateData)) {
            // Si es el propio perfil y cambió datos de sesión, actualizarla
            if ($isOwnProfile) {
                session()->set('nombre', $updateData['nombre']);
                if (isset($updateData['id_rol'])) {
                    $rol = $this->rolModel->find($updateData['id_rol']);
                    session()->set([
                        'rol_id' => $updateData['id_rol'],
                        'rol_name' => $rol['nombre']
                    ]);
                }
            }

            return redirect()->to(base_url('profile/' . ($userId == session()->get('user_id') ? '' : $userId)))
                           ->with('success', 'Perfil actualizado correctamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el perfil');
        }
    }

    /**
     * Obtener datos completos del usuario con información de empresa y rol
     */
    private function getUserCompleteData($userId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('usuarios u')
                    ->select('u.*, e.nombre as empresa_nombre, e.ruc as empresa_ruc, 
                             r.nombre as rol_nombre, r.descripcion as rol_descripcion')
                    ->join('empresas e', 'e.id = u.id_empresa', 'left')
                    ->join('roles r', 'r.id = u.id_rol', 'left')
                    ->where('u.id', $userId)
                    ->get();

        return $query->getRowArray();
    }

    /**
     * Verificar si el usuario actual puede editar el perfil especificado
     */
    private function canEditUser($userId)
    {
        $currentUserId = session()->get('user_id');
        $currentUserRole = session()->get('rol_name');

        // Puede editar su propio perfil
        if ($currentUserId == $userId) {
            return true;
        }

        // Los administradores pueden editar perfiles de su empresa
        if ($currentUserRole === 'Administrador') {
            $targetUser = $this->usuarioModel->find($userId);
            return $targetUser && $targetUser['id_empresa'] == session()->get('empresa_id');
        }

        return false;
    }
}
