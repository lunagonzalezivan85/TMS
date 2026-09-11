<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\RolModel;
use App\Models\EmpresaModel;

class Auth extends BaseController
{
    protected $usuarioModel;
    protected $rolModel;
    protected $empresaModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolModel = new RolModel();
        $this->empresaModel = new EmpresaModel();
    }

    /**
     * Mostrar formulario de login
     */
    public function login()
    {
        // Si ya está logueado, redirigir al dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Iniciar Sesión - GMV'
        ];

        return view('auth/login', $data);
    }

    /**
     * Procesar login
     */
    public function authenticate()
    {
        $rules = [
            'usuario' => 'required',
            'clave' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $usuario = $this->request->getPost('usuario');
        $clave = $this->request->getPost('clave');

        $resultado = $this->usuarioModel->login($usuario, $clave);

        if (isset($resultado['error'])) {
            return redirect()->back()->withInput()->with('error', $resultado['error']);
        }

        // Establecer datos de sesión
        $sessionData = [
            'user_id' => $resultado['id'],
            'usuario' => $resultado['usuario'],
            'nombre' => $resultado['nombre'],
            'rol_id' => $resultado['id_rol'],
            'rol_name' => $resultado['rol_nombre'],
            'empresa_id' => $resultado['id_empresa'],
            'empresa_nombre' => $resultado['empresa_nombre'],
            'isLoggedIn' => true
        ];

        session()->set($sessionData);

        // Redirigir según el rol
        $redirectUrl = $this->getRedirectByRole($resultado['rol_nombre'] ?? '');
        return redirect()->to($redirectUrl)->with('success', 'Bienvenido, ' . $resultado['nombre']);
    }

    /**
     * Cerrar sesión
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Sesión cerrada correctamente');
    }

    /**
     * Wizard de registro - Página inicial
     */
    public function registerWizard()
    {
        // Si ya está logueado, redirigir al dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        // Limpiar datos del wizard si existen
        session()->remove('wizard_empresa');
        session()->remove('wizard_usuario');

        return redirect()->to(base_url('register/step1'));
    }

    /**
     * Paso 1: Registro de empresa
     */
    public function registerStep1()
    {
        // Si ya está logueado, redirigir al dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Registro - Datos de la Empresa',
            'validation' => session()->getFlashdata('validation'),
            'empresa_data' => session()->get('wizard_empresa') ?? []
        ];

        return view('auth/register_step1', $data);
    }

    /**
     * Procesar paso 1: Guardar datos de empresa en sesión
     */
    public function processStep1()
    {
        $rules = [
            'nombre_empresa' => 'required|max_length[100]',
            'nit' => 'required|max_length[20]|is_unique[empresas.ruc]', // Campo nit pero valida contra ruc en BD
            'direccion' => 'required|max_length[200]',
            'telefono_empresa' => 'required|max_length[20]',
            'correo_empresa' => 'required|valid_email|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Guardar datos de empresa en sesión
        $empresaData = [
            'nombre' => $this->request->getPost('nombre_empresa'),
            'ruc' => $this->request->getPost('nit'), // Mapear nit a ruc
            'direccion' => $this->request->getPost('direccion'),
            'telefono' => $this->request->getPost('telefono_empresa'),
            'correo' => $this->request->getPost('correo_empresa'),
            'codigo_inicial' => 'GMV', // Código por defecto
            'serie_documento' => '001', // Serie por defecto
            'fechaRegistro' => date('Y-m-d H:i:s'),
            'fechaUpdate' => date('Y-m-d H:i:s'),
            'usuarioCrea' => 'GMV', // Usuario sistema para registro inicial
            'usuarioEdita' => 'GMV',
            'estado' => 'ACTIVO'
        ];

        session()->set('wizard_empresa', $empresaData);

        return redirect()->to(base_url('register/step2'));
    }

    /**
     * Paso 2: Registro de usuario administrador
     */
    public function registerStep2()
    {
        // Si ya está logueado, redirigir al dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        // Verificar que existan los datos de empresa
        if (!session()->get('wizard_empresa')) {
            return redirect()->to(base_url('register/step1'))->with('error', 'Debes completar primero los datos de la empresa');
        }

        $data = [
            'title' => 'Registro - Usuario Administrador',
            'validation' => session()->getFlashdata('validation'),
            'empresa_data' => session()->get('wizard_empresa'),
            'usuario_data' => session()->get('wizard_usuario') ?? []
        ];

        return view('auth/register_step2', $data);
    }

    /**
     * Procesar paso 2: Crear empresa y usuario administrador
     */
    public function processStep2()
    {
        // Verificar que existan los datos de empresa
        if (!session()->get('wizard_empresa')) {
            return redirect()->to(base_url('register/step1'))->with('error', 'Debes completar primero los datos de la empresa');
        }

        $rules = [
            'nombre_usuario' => 'required|max_length[100]',
            'usuario' => 'required|max_length[50]|is_unique[usuarios.usuario]',
            'clave' => 'required|min_length[6]',
            'confirmar_clave' => 'required|matches[clave]',
            'correo_usuario' => 'required|valid_email|max_length[100]',
            'telefono_usuario' => 'permit_empty|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Iniciar transacción
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Crear empresa
            $empresaData = session()->get('wizard_empresa');
            $empresaId = $this->empresaModel->crearEmpresaRegistro($empresaData);

            if (!$empresaId) {
                $errors = $this->empresaModel->errors();
                throw new \Exception('Error al crear la empresa: ' . implode(', ', $errors));
            }

            // 2. Crear usuario administrador (asignar rol ID = 1 por defecto)
            $usuarioData = [
                'nombre' => $this->request->getPost('nombre_usuario'),
                'usuario' => $this->request->getPost('usuario'),
                'clave' => $this->request->getPost('clave'),
                'correo' => $this->request->getPost('correo_usuario'),
                'telefono' => $this->request->getPost('telefono_usuario'),
                'id_rol' => 1, // Rol por defecto
                'id_empresa' => $empresaId,
                'estado' => 'ACTIVO'
            ];

            $usuarioId = $this->usuarioModel->crearUsuario($usuarioData);

            if (!$usuarioId) {
                $errors = $this->usuarioModel->errors();
                $detail = !empty($errors) ? implode(', ', $errors) : 'Error desconocido';
                throw new \Exception('Error al crear el usuario administrador: ' . $detail);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }

            // Limpiar datos del wizard
            session()->remove('wizard_empresa');
            session()->remove('wizard_usuario');

            return redirect()->to(base_url('login'))->with('success', 'Empresa y usuario administrador registrados correctamente. Ahora puedes iniciar sesión.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar perfil del usuario
     */
    public function profile()
    {
        $data = [
            'title' => 'Mi Perfil - GMV',
            'usuario' => $this->authModel->find(session()->get('user_id')),
            'validation' => session()->getFlashdata('validation')
        ];

        return view('auth/profile', $data);
    }

    /**
     * Actualizar perfil
     */
    public function updateProfile()
    {
        $userId = session()->get('user_id');
        
        $rules = [
            'nombre' => 'required|max_length[100]',
            'correo' => 'permit_empty|valid_email',
            'telefono' => 'permit_empty|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'correo' => $this->request->getPost('correo'),
            'telefono' => $this->request->getPost('telefono')
        ];

        if ($this->authModel->update($userId, $datos)) {
            // Actualizar datos de sesión
            session()->set('user_name', $datos['nombre']);
            session()->set('user_email', $datos['correo']);
            
            return redirect()->back()->with('success', 'Perfil actualizado correctamente');
        }

        return redirect()->back()->with('error', 'No se pudo actualizar el perfil');
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword()
    {
        $rules = [
            'clave_actual' => 'required',
            'clave_nueva' => 'required|min_length[6]',
            'confirmar_clave' => 'required|matches[clave_nueva]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('validation', $this->validator);
        }

        $userId = session()->get('user_id');
        $claveActual = $this->request->getPost('clave_actual');
        $claveNueva = $this->request->getPost('clave_nueva');

        $resultado = $this->authModel->cambiarClave($userId, $claveActual, $claveNueva);

        if (isset($resultado['success'])) {
            return redirect()->back()->with('success', 'Contraseña cambiada correctamente');
        }

        return redirect()->back()->with('error', $resultado['error']);
    }

    /**
     * Obtener URL de redirección según el rol
     */
    private function getRedirectByRole(?string $rolName)
    {
        switch (strtolower((string) $rolName)) {
            case 'administrador':
                return base_url('dashboard');
            case 'mecánico':
            case 'mecanico':
                return base_url('maintenance');
            case 'conductor':
                return base_url('vehicles');
            default:
                return base_url('dashboard');
        }
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    private function tienePermiso(string $permiso)
    {
        $userId = session()->get('user_id');
        $rolName = session()->get('rol_name');

        if (!$userId) {
            return false;
        }

        // Los administradores tienen todos los permisos
        if ($rolName === 'Administrador') {
            return true;
        }

        return $this->authModel->tienePermiso($userId, $permiso);
    }
}
