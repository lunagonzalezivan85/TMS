<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'id_empresa',
        'nombre',
        'usuario',
        'clave',
        'correo',
        'telefono',
        'id_rol',
        'estado',
        'fechaRegistro',
        'fechaUpdate',
        'usuarioCrea',
        'usuarioEdita'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fechaRegistro';
    protected $updatedField = 'fechaUpdate';

    protected $validationRules = [
        'id_empresa' => 'required|integer',
        'nombre' => 'required|min_length[2]|max_length[100]',
        'usuario' => 'required|min_length[3]|max_length[50]|is_unique[usuarios.usuario]',
        'clave' => 'required|min_length[6]',
        'correo' => 'required|valid_email|is_unique[usuarios.correo]',
        'telefono' => 'permit_empty|min_length[7]|max_length[20]',
        'id_rol' => 'required|integer',
        'estado' => 'required|in_list[ACTIVO,INACTIVO]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'usuario' => [
            'required' => 'El nombre de usuario es obligatorio',
            'min_length' => 'El usuario debe tener al menos 3 caracteres',
            'max_length' => 'El usuario no puede exceder 50 caracteres',
            'is_unique' => 'Este nombre de usuario ya está en uso'
        ],
        'clave' => [
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres'
        ],
        'correo' => [
            'required' => 'El correo electrónico es obligatorio',
            'valid_email' => 'Debe ingresar un correo electrónico válido',
            'is_unique' => 'Este correo electrónico ya está registrado'
        ],
        'telefono' => [
            'min_length' => 'El teléfono debe tener al menos 7 caracteres',
            'max_length' => 'El teléfono no puede exceder 20 caracteres'
        ],
        'id_empresa' => [
            'required' => 'La empresa es obligatoria',
            'integer' => 'ID de empresa inválido'
        ],
        'id_rol' => [
            'required' => 'El rol es obligatorio',
            'integer' => 'ID de rol inválido'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser ACTIVO o INACTIVO'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Crear usuario con campos de auditoría y hash de contraseña
     */
    public function crearUsuario(array $data)
    {
        // Hash de la contraseña
        if (isset($data['clave']) && !empty($data['clave'])) {
            $data['clave'] = password_hash($data['clave'], PASSWORD_DEFAULT);
        }

        // Campos de auditoría
        $data['fechaRegistro'] = date('Y-m-d H:i:s');
        $data['fechaUpdate'] = date('Y-m-d H:i:s');
        $data['usuarioCrea'] = session()->get('usuario_id') ?? 1;
        $data['usuarioEdita'] = session()->get('usuario_id') ?? 1;
        
        if (!isset($data['estado'])) {
            $data['estado'] = 'ACTIVO';
        }

        return $this->insert($data);
    }

    /**
     * Busca un usuario por nombre de usuario
     */
    public function findByUsername($username)
    {
        return $this->where('usuario', $username)
                    ->where('estado', 'ACTIVO')
                    ->first();
    }

    /**
     * Busca un usuario por correo electrónico
     */
    public function findByEmail($email)
    {
        return $this->where('correo', $email)
                    ->where('estado', 'ACTIVO')
                    ->first();
    }

    /**
     * Obtiene usuarios por empresa
     */
    public function getUsersByCompany($empresaId)
    {
        return $this->where('id_empresa', $empresaId)
                    ->where('estado', 'ACTIVO')
                    ->findAll();
    }

    /**
     * Verifica las credenciales del usuario
     */
    public function verifyCredentials($username, $password)
    {
        $user = $this->findByUsername($username);
        
        if ($user && password_verify($password, $user['clave'])) {
            return $user;
        }
        
        return false;
    }

    /**
     * Método de login que verifica credenciales y obtiene datos completos
     */
    public function login($username, $password)
    {
        try {
            $db = \Config\Database::connect();
            
            // Buscar usuario con datos de empresa y rol
            $query = $db->table('usuarios u')
                        ->select('u.*, e.nombre as empresa_nombre, r.nombre as rol_nombre')
                        ->join('empresas e', 'e.id = u.id_empresa', 'left')
                        ->join('roles r', 'r.id = u.id_rol', 'left')
                        ->where('u.usuario', $username)
                        ->where('u.estado', 'ACTIVO')
                        ->get();
            
            $user = $query->getRowArray();
            
            if (!$user) {
                return ['error' => 'Usuario no encontrado o inactivo'];
            }
            
            // Verificar contraseña
            if (!password_verify($password, $user['clave'])) {
                return ['error' => 'Contraseña incorrecta'];
            }
            
            // Remover la contraseña del resultado
            unset($user['clave']);
            
            return $user;
            
        } catch (\Exception $e) {
            log_message('error', 'Error en login: ' . $e->getMessage());
            return ['error' => 'Error interno del servidor'];
        }
    }
}
