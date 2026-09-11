<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\RolModel;
use App\Models\EmpresaModel;

class Configuracion extends BaseController
{
    protected $usuarioModel;
    protected $rolModel;
    protected $empresaModel;
    protected $validation;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolModel = new RolModel();
        $this->empresaModel = new EmpresaModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Página principal de configuración
     */
    public function index()
    {
        $data = [
            'title' => 'Configuración del Sistema - GMV',
            'breadcrumb' => [
                ['name' => 'Inicio', 'url' => base_url('dashboard')],
                ['name' => 'Configuración', 'url' => '']
            ]
        ];

        return view('configuracion/index', $data);
    }

    /**
     * Mostrar formulario de registro de usuario
     */
    public function registroUsuario()
    {
        // Verificar que el usuario tenga permisos de administrador
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $empresaId = session()->get('empresa_id');
        
        // Obtener roles activos
        $roles = $this->rolModel->where('estado', 1)->findAll();

        $data = [
            'title' => 'Registro de Usuario - GMV',
            'breadcrumb' => [
                ['name' => 'Inicio', 'url' => base_url('dashboard')],
                ['name' => 'Configuración', 'url' => base_url('configuracion')],
                ['name' => 'Registro de Usuario', 'url' => '']
            ],
            'roles' => $roles,
            'empresa_id' => $empresaId
        ];

        return view('configuracion/registro_usuario', $data);
    }

    /**
     * Procesar registro de usuario
     */
    public function crearUsuario()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // Verificar permisos
        if (!$this->esAdministrador()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ]);
        }

        try {
            // Obtener empresa_id de la sesión
            $empresaId = session()->get('empresa_id');
            
            if (!$empresaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo obtener la información de la empresa'
                ]);
            }

            // Validar datos de entrada
            $rules = [
                'nombre' => 'required|min_length[2]|max_length[100]',
                'email' => 'required|valid_email|is_unique[usuarios.email]',
                'password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[password]',
                'id_rol' => 'required|integer|greater_than[0]'
            ];

            $messages = [
                'nombre' => [
                    'required' => 'El nombre es obligatorio',
                    'min_length' => 'El nombre debe tener al menos 2 caracteres',
                    'max_length' => 'El nombre no puede exceder 100 caracteres'
                ],
                'email' => [
                    'required' => 'El email es obligatorio',
                    'valid_email' => 'Debe proporcionar un email válido',
                    'is_unique' => 'Este email ya está registrado'
                ],
                'password' => [
                    'required' => 'La contraseña es obligatoria',
                    'min_length' => 'La contraseña debe tener al menos 6 caracteres'
                ],
                'confirm_password' => [
                    'required' => 'Debe confirmar la contraseña',
                    'matches' => 'Las contraseñas no coinciden'
                ],
                'id_rol' => [
                    'required' => 'Debe seleccionar un rol',
                    'integer' => 'El rol debe ser un número válido',
                    'greater_than' => 'Debe seleccionar un rol válido'
                ]
            ];

            if (!$this->validate($rules, $messages)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            // Verificar que el rol existe y está activo
            $rol = $this->rolModel->where('id', $this->request->getPost('id_rol'))
                                  ->where('estado', 1)
                                  ->first();
            
            if (!$rol) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El rol seleccionado no es válido'
                ]);
            }

            // Preparar datos del usuario
            $userData = [
                'nombre' => trim($this->request->getPost('nombre')),
                'email' => trim(strtolower($this->request->getPost('email'))),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'id_rol' => $this->request->getPost('id_rol'),
                'id_empresa' => $empresaId, // Tomado de la sesión
                'estado' => 1,
                'usuario_crea' => session()->get('usuario_id'),
                'fecha_registro' => date('Y-m-d H:i:s')
            ];

            // Crear usuario
            $usuarioId = $this->usuarioModel->insert($userData);

            if (!$usuarioId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear el usuario'
                ]);
            }

            // Log de auditoría
            log_message('info', "Usuario creado: ID {$usuarioId}, Email: {$userData['email']}, Empresa: {$empresaId}, Creado por: " . session()->get('usuario_id'));

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => [
                    'id' => $usuarioId,
                    'nombre' => $userData['nombre'],
                    'email' => $userData['email'],
                    'rol' => $rol['nombre']
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al crear usuario: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor. Intente nuevamente.'
            ]);
        }
    }

    /**
     * Obtener estadísticas de usuarios de la empresa
     */
    public function getEstadisticasUsuarios()
    {
        // Verificar que sea una petición AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        // Verificar permisos de administrador
        if (!$this->esAdministrador()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tiene permisos para acceder a esta información'
            ]);
        }

        try {
            $empresaId = session('empresa_id');
            
            if (!$empresaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo identificar la empresa'
                ]);
            }

            // Usuarios activos
            $usuariosActivos = $this->usuarioModel
                ->where('id_empresa', $empresaId)
                ->where('estado', 1)
                ->countAllResults();

            // Usuarios creados en los últimos 30 días
            $fechaLimite = date('Y-m-d H:i:s', strtotime('-30 days'));
            $usuariosRecientes = $this->usuarioModel
                ->where('id_empresa', $empresaId)
                ->where('fecha_registro >=', $fechaLimite)
                ->countAllResults();

            // Usuarios por rol
            $usuariosPorRol = $this->usuarioModel
                ->select('roles.nombre as rol, COUNT(usuarios.id) as total')
                ->join('roles', 'roles.id = usuarios.id_rol')
                ->where('usuarios.id_empresa', $empresaId)
                ->where('usuarios.estado', 1)
                ->groupBy('usuarios.id_rol, roles.nombre')
                ->orderBy('total', 'DESC')
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'usuarios_activos' => $usuariosActivos,
                    'usuarios_recientes' => $usuariosRecientes,
                    'usuarios_por_rol' => $usuariosPorRol
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas de usuarios: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener las estadísticas'
            ]);
        }
    }

    /**
     * Obtener usuarios de la empresa actual
     */
    public function getUsuariosEmpresa()
    {
        // Verificar que sea una petición AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        // Verificar permisos de administrador
        if (!$this->esAdministrador()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tiene permisos para acceder a esta información'
            ]);
        }

        try {
            $empresaId = session('empresa_id');
            
            if (!$empresaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo identificar la empresa'
                ]);
            }

            $usuarios = $this->usuarioModel
                ->select('usuarios.*, roles.nombre as rol_nombre')
                ->join('roles', 'roles.id = usuarios.id_rol')
                ->where('usuarios.id_empresa', $empresaId)
                ->orderBy('usuarios.nombre', 'ASC')
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => $usuarios
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener usuarios de la empresa: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener los usuarios'
            ]);
        }
    }

    /**
     * Verificar si el usuario tiene permisos de administrador
     */
    private function esAdministrador()
    {
        $rolName = session()->get('rol_name');
        $usuarioId = session()->get('usuario_id');
        
        // Verificar si es administrador o superadmin
        return in_array(strtolower($rolName), ['administrador', 'admin', 'superadmin']) && $usuarioId;
    }
}
