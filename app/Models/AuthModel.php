<?php

namespace App\Models;

use App\Models\BaseModel;

class AuthModel extends BaseModel
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'id_empresa',
        'nombre',
        'usuario',
        'clave',
        'correo',
        'telefono',
        'id_rol',
        'estado'
    ];

    protected $validationRules = [
        'nombre' => 'required|max_length[100]',
        'usuario' => 'required|max_length[50]|is_unique[usuarios.usuario,id,{id}]',
        'clave' => 'required|min_length[6]',
        'correo' => 'permit_empty|valid_email|max_length[100]',
        'id_rol' => 'required|integer',
        'id_empresa' => 'required|integer'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'usuario' => [
            'required' => 'El usuario es obligatorio',
            'is_unique' => 'Este usuario ya existe'
        ],
        'clave' => [
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres'
        ]
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['clave'])) {
            $data['data']['clave'] = password_hash($data['data']['clave'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Autenticar usuario
     */
    public function login(string $usuario, string $clave)
    {
        $user = $this->select('usuarios.*, empresas.nombre as empresa_nombre, roles.nombre as rol_nombre')
                     ->join('empresas', 'empresas.id = usuarios.id_empresa')
                     ->join('roles', 'roles.id = usuarios.id_rol')
                     ->where('usuarios.usuario', $usuario)
                     ->where('usuarios.estado', 'ACTIVO')
                     ->first();

        if ($user && password_verify($clave, $user['clave'])) {
            // No devolver la contraseña
            unset($user['clave']);
            return $user;
        }

        return false;
    }

    /**
     * Crear nuevo usuario
     */
    public function crearUsuario(array $datos)
    {
        // Validar que el rol existe
        $rolModel = new RolModel();
        if (!$rolModel->find($datos['id_rol'])) {
            return ['error' => 'El rol especificado no existe'];
        }

        // Validar que la empresa existe
        $empresaModel = new EmpresaModel();
        if (!$empresaModel->find($datos['id_empresa'])) {
            return ['error' => 'La empresa especificada no existe'];
        }

        $datos['estado'] = $datos['estado'] ?? 'ACTIVO';
        
        if ($this->insert($datos)) {
            return ['success' => true, 'id' => $this->getInsertID()];
        }

        return ['error' => 'No se pudo crear el usuario', 'validation' => $this->errors()];
    }

    /**
     * Obtener usuarios con información completa
     */
    public function getUsuarios($empresaId = null)
    {
        $builder = $this->select('usuarios.*, empresas.nombre as empresa_nombre, roles.nombre as rol_nombre')
                        ->join('empresas', 'empresas.id = usuarios.id_empresa')
                        ->join('roles', 'roles.id = usuarios.id_rol');

        if ($empresaId) {
            $builder->where('usuarios.id_empresa', $empresaId);
        }

        return $builder->findAll();
    }

    /**
     * Cambiar contraseña
     */
    public function cambiarClave(int $usuarioId, string $claveActual, string $claveNueva)
    {
        $usuario = $this->find($usuarioId);
        
        if (!$usuario) {
            return ['error' => 'Usuario no encontrado'];
        }

        if (!password_verify($claveActual, $usuario['clave'])) {
            return ['error' => 'La contraseña actual es incorrecta'];
        }

        if ($this->update($usuarioId, ['clave' => $claveNueva])) {
            return ['success' => true];
        }

        return ['error' => 'No se pudo actualizar la contraseña'];
    }

    /**
     * Activar/Desactivar usuario
     */
    public function cambiarEstado(int $usuarioId, string $estado)
    {
        if (!in_array($estado, ['ACTIVO', 'INACTIVO'])) {
            return ['error' => 'Estado no válido'];
        }

        if ($this->update($usuarioId, ['estado' => $estado])) {
            return ['success' => true];
        }

        return ['error' => 'No se pudo cambiar el estado'];
    }

    /**
     * Verificar permisos del usuario
     */
    public function tienePermiso(int $usuarioId, string $permiso)
    {
        $usuario = $this->select('usuarios.*, roles.nombre as rol_nombre')
                        ->join('roles', 'roles.id = usuarios.id_rol')
                        ->find($usuarioId);

        if (!$usuario) {
            return false;
        }

        // Permisos por rol
        $permisos = [
            'Administrador' => ['*'], // Todos los permisos
            'Mecánico' => ['ver_solicitudes', 'editar_solicitudes', 'ver_vehiculos', 'ver_materiales'],
            'Conductor' => ['ver_vehiculos_asignados', 'crear_solicitudes']
        ];

        $rolPermisos = $permisos[$usuario['rol_nombre']] ?? [];

        return in_array('*', $rolPermisos) || in_array($permiso, $rolPermisos);
    }
}
