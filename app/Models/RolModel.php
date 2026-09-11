<?php

namespace App\Models;

use App\Models\BaseModel;

class RolModel extends BaseModel
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'nombre',
        'descripcion',
        'usuario_crea',
        'usuario_actualiza',
        'fecha_registro',
        'fecha_actualizacion'
    ];
    
    // Deshabilitar callbacks del BaseModel para este modelo
    protected $allowCallbacks = false;

    protected $validationRules = [
        'nombre' => 'required|max_length[50]|is_unique[roles.nombre,id,{id}]',
        'descripcion' => 'permit_empty|max_length[200]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del rol es obligatorio',
            'is_unique' => 'Este rol ya existe'
        ]
    ];

    /**
     * Obtener todos los roles activos
     */
    public function getRolesActivos()
    {
        return $this->findAll();
    }

    /**
     * Obtener rol con cantidad de usuarios
     */
    public function getRolesConUsuarios()
    {
        return $this->select('roles.*, COUNT(usuarios.id) as total_usuarios')
                    ->join('usuarios', 'usuarios.id_rol = roles.id', 'left')
                    ->groupBy('roles.id')
                    ->findAll();
    }

    /**
     * Crear nuevo rol
     */
    public function crearRol(array $datos)
    {
        if ($this->insert($datos)) {
            return ['success' => true, 'id' => $this->getInsertID()];
        }

        return ['error' => 'No se pudo crear el rol', 'validation' => $this->errors()];
    }

    /**
     * Verificar si el rol se puede eliminar
     */
    public function puedeEliminar(int $rolId)
    {
        // No permitir eliminar si hay usuarios asignados
        $usuarioModel = new AuthModel();
        $usuarios = $usuarioModel->where('id_rol', $rolId)->countAllResults();
        
        return $usuarios === 0;
    }

    /**
     * Eliminar rol si es posible
     */
    public function eliminarRol(int $rolId)
    {
        if (!$this->puedeEliminar($rolId)) {
            return ['error' => 'No se puede eliminar el rol porque tiene usuarios asignados'];
        }

        if ($this->delete($rolId)) {
            return ['success' => true];
        }

        return ['error' => 'No se pudo eliminar el rol'];
    }

    /**
     * Obtener permisos por rol
     */
    public function getPermisosPorRol()
    {
        return [
            'Administrador' => [
                'descripcion' => 'Acceso completo al sistema',
                'permisos' => [
                    'Gestión de usuarios',
                    'Gestión de roles',
                    'Gestión de empresas',
                    'Gestión de vehículos',
                    'Gestión de conductores',
                    'Gestión de mantenimientos',
                    'Gestión de materiales',
                    'Reportes completos',
                    'Configuración del sistema'
                ]
            ],
            'Mecánico' => [
                'descripcion' => 'Gestión de mantenimientos y reparaciones',
                'permisos' => [
                    'Ver solicitudes de mantenimiento',
                    'Editar solicitudes de mantenimiento',
                    'Ver vehículos',
                    'Ver y consumir materiales',
                    'Generar reportes de mantenimiento'
                ]
            ],
            'Conductor' => [
                'descripcion' => 'Acceso limitado para conductores',
                'permisos' => [
                    'Ver vehículos asignados',
                    'Crear solicitudes de mantenimiento',
                    'Ver historial de mantenimientos',
                    'Actualizar kilometraje'
                ]
            ]
        ];
    }
}
