<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\RolModel;
use App\Models\EmpresaModel;

class Usuarios extends SecureController
{
    protected $authModel;
    protected $rolModel;
    protected $empresaModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->authModel = new AuthModel();
        $this->rolModel = new RolModel();
        $this->empresaModel = new EmpresaModel();
    }

    /**
     * Listar usuarios
     */
    public function index()
    {
        $this->requireAccess('usuarios');
        
        $empresaId = session()->get('empresa_id');
        $rolName = session()->get('rol_name');

        // Si es administrador, puede ver todos los usuarios de su empresa
        $usuarios = $this->authModel->getUsuarios($empresaId);

        $data = [
            'title' => 'Gestión de Usuarios - GMV',
            'usuarios' => $usuarios
        ];

        return view('usuarios/index', $data);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        /*if (!$this->tienePermiso('gestionar_usuarios')) {
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para acceder a esta sección');
        }*/

        $data = [
            'title' => 'Crear Usuario - GMV',
            'roles' => $this->rolModel->getRolesActivos(),
            'empresas' => $this->empresaModel->findAll(),
            'validation' => session()->getFlashdata('validation')
        ];

        return view('usuarios/create', $data);
    }

    /**
     * Guardar nuevo usuario
     */
    public function store()
    {
        if (!$this->tienePermiso('gestionar_usuarios')) {
            return $this->response->setJSON(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $rules = [
            'nombre' => 'required|max_length[100]',
            'usuario' => 'required|max_length[50]|is_unique[usuarios.usuario]',
            'clave' => 'required|min_length[6]',
            'id_rol' => 'required|integer',
            'id_empresa' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => 'Datos inválidos', 'validation' => $this->validator->getErrors()]);
        }

        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'usuario' => $this->request->getPost('usuario'),
            'clave' => $this->request->getPost('clave'),
            'correo' => $this->request->getPost('correo'),
            'telefono' => $this->request->getPost('telefono'),
            'id_rol' => $this->request->getPost('id_rol'),
            'id_empresa' => $this->request->getPost('id_empresa'),
            'estado' => 'ACTIVO'
        ];

        $resultado = $this->authModel->crearUsuario($datos);

        if (isset($resultado['success'])) {
            return $this->response->setJSON(['success' => 'Usuario creado correctamente', 'id' => $resultado['id']]);
        }

        return $this->response->setJSON(['error' => $resultado['error']]);
    }

    /**
     * Mostrar usuario específico
     */
    public function show($id)
    {
        if (!$this->tienePermiso('gestionar_usuarios')) {
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $usuario = $this->authModel->select('usuarios.*, empresas.nombre as empresa_nombre, roles.nombre as rol_nombre')
                                  ->join('empresas', 'empresas.id = usuarios.id_empresa')
                                  ->join('roles', 'roles.id = usuarios.id_rol')
                                  ->find($id);

        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))->with('error', 'Usuario no encontrado');
        }

        $data = [
            'title' => 'Detalle Usuario - GMV',
            'usuario' => $usuario
        ];

        return view('usuarios/show', $data);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        if (!$this->tienePermiso('gestionar_usuarios')) {
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $usuario = $this->authModel->find($id);

        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))->with('error', 'Usuario no encontrado');
        }

        $data = [
            'title' => 'Editar Usuario - GMV',
            'usuario' => $usuario,
            'roles' => $this->rolModel->getRolesActivos(),
            'empresas' => $this->empresaModel->findAll(),
            'validation' => session()->getFlashdata('validation')
        ];

        return view('usuarios/edit', $data);
    }

    /**
     * Actualizar usuario
     */
    public function update($id)
    {
        if (!$this->tienePermiso('gestionar_usuarios')) {
            return $this->response->setJSON(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $usuario = $this->authModel->find($id);
        if (!$usuario) {
            return $this->response->setJSON(['error' => 'Usuario no encontrado']);
        }

        $rules = [
            'nombre' => 'required|max_length[100]',
            'usuario' => "required|max_length[50]|is_unique[usuarios.usuario,id,{$id}]",
            'id_rol' => 'required|integer',
            'id_empresa' => 'required|integer'
        ];

        // Si se proporciona nueva contraseña, validarla
        if ($this->request->getPost('clave')) {
            $rules['clave'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => 'Datos inválidos', 'validation' => $this->validator->getErrors()]);
        }

        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'usuario' => $this->request->getPost('usuario'),
            'correo' => $this->request->getPost('correo'),
            'telefono' => $this->request->getPost('telefono'),
            'id_rol' => $this->request->getPost('id_rol'),
            'id_empresa' => $this->request->getPost('id_empresa')
        ];

        // Solo actualizar contraseña si se proporciona
        if ($this->request->getPost('clave')) {
            $datos['clave'] = $this->request->getPost('clave');
        }

        if ($this->authModel->update($id, $datos)) {
            return $this->response->setJSON(['success' => 'Usuario actualizado correctamente']);
        }

        return $this->response->setJSON(['error' => 'No se pudo actualizar el usuario']);
    }

    /**
     * Cambiar estado del usuario
     */
    public function cambiarEstado($id)
    {
        if (!$this->tienePermiso('gestionar_usuarios')) {
            return $this->response->setJSON(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $estado = $this->request->getPost('estado');
        $resultado = $this->authModel->cambiarEstado($id, $estado);

        if (isset($resultado['success'])) {
            return $this->response->setJSON(['success' => 'Estado cambiado correctamente']);
        }

        return $this->response->setJSON(['error' => $resultado['error']]);
    }

    /**
     * Eliminar usuario
     */
    public function delete($id)
    {
        if (!$this->tienePermiso('gestionar_usuarios')) {
            return $this->response->setJSON(['error' => 'No tienes permisos para realizar esta acción']);
        }

        // No permitir eliminar el propio usuario
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON(['error' => 'No puedes eliminar tu propio usuario']);
        }

        if ($this->authModel->delete($id)) {
            return $this->response->setJSON(['success' => 'Usuario eliminado correctamente']);
        }

        return $this->response->setJSON(['error' => 'No se pudo eliminar el usuario']);
    }

    /**
     * Mostrar formulario para resetear contraseña
     */
    public function resetPassword($id)
    {
        if (!$this->tienePermiso('gestionar_usuarios')) {
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $usuario = $this->authModel->select('usuarios.*, empresas.nombre as empresa_nombre, roles.nombre as rol_nombre')
                                  ->join('empresas', 'empresas.id = usuarios.id_empresa')
                                  ->join('roles', 'roles.id = usuarios.id_rol')
                                  ->find($id);

        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))->with('error', 'Usuario no encontrado');
        }

        // No permitir resetear la contraseña del propio usuario desde aquí
        if ($id == session()->get('user_id')) {
            return redirect()->to(base_url('usuarios'))->with('error', 'No puedes resetear tu propia contraseña desde aquí. Usa el perfil de usuario.');
        }

        $data = [
            'title' => 'Resetear Contraseña - GMV',
            'usuario' => $usuario
        ];

        return view('usuarios/reset_password', $data);
    }

    /**
     * Procesar el reseteo de contraseña
     */
    public function processResetPassword($id)
    {
        if (!$this->tienePermiso('gestionar_usuarios')) {
            return $this->response->setJSON(['error' => 'No tienes permisos para realizar esta acción']);
        }

        // No permitir resetear la contraseña del propio usuario
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON(['error' => 'No puedes resetear tu propia contraseña desde aquí']);
        }

        $rules = [
            'nueva_clave' => 'required|min_length[6]',
            'confirmar_clave' => 'required|matches[nueva_clave]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'error' => 'Datos inválidos', 
                'validation' => $this->validator->getErrors()
            ]);
        }

        $usuario = $this->authModel->find($id);
        if (!$usuario) {
            return $this->response->setJSON(['error' => 'Usuario no encontrado']);
        }

        $nuevaClave = $this->request->getPost('nueva_clave');
        $datos = [
            'clave' => $nuevaClave,
            'fechaUpdate' => date('Y-m-d H:i:s'),
            'usuarioEdita' => session()->get('user_id')
        ];

        if ($this->authModel->update($id, $datos)) {
            // Log de la acción
            log_message('info', 'Contraseña reseteada para usuario ID: ' . $id . ' por usuario ID: ' . session()->get('user_id'));
            
            return $this->response->setJSON([
                'success' => 'Contraseña reseteada correctamente',
                'redirect' => base_url('usuarios/' . $id)
            ]);
        }

        return $this->response->setJSON(['error' => 'No se pudo resetear la contraseña']);
    }

    /**
     * Generar contraseña temporal aleatoria
     */
    public function generateTempPassword($id)
    {
        if (!$this->tienePermiso('gestionar_usuarios')) {
            return $this->response->setJSON(['error' => 'No tienes permisos para realizar esta acción']);
        }

        // No permitir generar contraseña temporal para el propio usuario
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON(['error' => 'No puedes generar contraseña temporal para tu propio usuario']);
        }

        $usuario = $this->authModel->find($id);
        if (!$usuario) {
            return $this->response->setJSON(['error' => 'Usuario no encontrado']);
        }

        // Generar contraseña temporal de 8 caracteres
        $tempPassword = $this->generateRandomPassword(8);
        
        $datos = [
            'clave' => $tempPassword,
            'fechaUpdate' => date('Y-m-d H:i:s'),
            'usuarioEdita' => session()->get('user_id')
        ];

        if ($this->authModel->update($id, $datos)) {
            // Log de la acción
            log_message('info', 'Contraseña temporal generada para usuario ID: ' . $id . ' por usuario ID: ' . session()->get('user_id'));
            
            return $this->response->setJSON([
                'success' => 'Contraseña temporal generada correctamente',
                'temp_password' => $tempPassword,
                'usuario_nombre' => $usuario['nombre'],
                'usuario_usuario' => $usuario['usuario']
            ]);
        }

        return $this->response->setJSON(['error' => 'No se pudo generar la contraseña temporal']);
    }

    /**
     * Generar contraseña aleatoria
     */
    private function generateRandomPassword($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        $charactersLength = strlen($characters);
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $password;
    }

    /**
     * Obtener usuarios para DataTable (AJAX)
     */
    public function getUsuariosAjax()
    {
        /*if (!$this->tienePermiso('gestionar_usuarios')) {
            return $this->response->setJSON(['error' => 'No tienes permisos']);
        }*/

        $empresaId = session()->get('empresa_id');
        $usuarios = $this->authModel->getUsuarios($empresaId);

        $data = [];
        foreach ($usuarios as $usuario) {
            $estadoBadge = $usuario['estado'] === 'ACTIVO' 
                ? '<span class="badge bg-success">Activo</span>' 
                : '<span class="badge bg-danger">Inactivo</span>';

            $acciones = '
            <div class="btn-group" role="group">
                <a href="' . base_url('usuarios/' . $usuario['id']) . '" class="btn btn-sm btn-info" title="Ver">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="' . base_url('usuarios/' . $usuario['id'] . '/edit') . '" class="btn btn-sm btn-warning" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="' . base_url('usuarios/' . $usuario['id'] . '/reset-password') . '" class="btn btn-sm btn-secondary" title="Resetear Contraseña">
                    <i class="fas fa-key"></i>
                </a>
                <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(' . $usuario['id'] . ')" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </div>';

            $data[] = [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'usuario' => $usuario['usuario'],
                'correo' => $usuario['correo'] ?: '-',
                'rol' => $usuario['rol_nombre'],
                'empresa' => $usuario['empresa_nombre'],
                'estado' => $estadoBadge,
                'acciones' => $acciones
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    /**
     * Verificar permisos
     */
    private function tienePermiso(string $permiso)
    {
        return true;
        $userId = session()->get('user_id');
        $rolName = session()->get('rol_name');

        if (!$userId) {
            return false;
        }

        // Los administradores tienen todos los permisos
        if (strtolower($rolName) === 'administrador') {
            return true;
        }

        return true;
       // return $this->authModel->tienePermiso($userId, $permiso);
    }
}
