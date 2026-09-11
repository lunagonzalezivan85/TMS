<?php

namespace App\Models;

use App\Models\BaseModel;

class AccesoModel extends BaseModel
{
    protected $table = 'accesos';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'id_rol',
        'id_menu',
        'estado',
        'usuario_crea',
        'usuario_actualiza',
        'fecha_registro',
        'fecha_actualizacion'
    ];
    
    // Deshabilitar callbacks del BaseModel para este modelo
    protected $allowCallbacks = false;

    protected $validationRules = [
        'id_rol' => 'required|integer|is_not_unique[roles.id]',
        'id_menu' => 'required|integer|is_not_unique[menu.id]',
        'estado' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'id_rol' => [
            'required' => 'El rol es obligatorio',
            'integer' => 'El rol debe ser un número válido',
            'is_not_unique' => 'El rol seleccionado no existe'
        ],
        'id_menu' => [
            'required' => 'El menú es obligatorio',
            'integer' => 'El menú debe ser un número válido',
            'is_not_unique' => 'El menú seleccionado no existe'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser activo (1) o inactivo (0)'
        ]
    ];

    /**
     * Obtener accesos con información de rol y menú
     */
    public function getAccesosConDetalles()
    {
        return $this->select('accesos.*, roles.nombre as rol_nombre, menu.nombre as menu_nombre, menu.icono as menu_icono')
                    ->join('roles', 'roles.id = accesos.id_rol')
                    ->join('menu', 'menu.id = accesos.id_menu')
                    ->orderBy('roles.nombre', 'ASC')
                    ->orderBy('menu.nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener accesos por rol
     */
    public function getAccesosPorRol($idRol)
    {
        return $this->select('accesos.*, menu.menu as menu_nombre, menu.icono as menu_icono, menu.ruta as menu_url')
                    ->join('menu', 'menu.id = accesos.id_menu')
                    ->where('accesos.id_rol', $idRol)
                    ->where('accesos.estado', 1)
                    ->orderBy('menu.id', 'ASC')
                    ->orderBy('menu.nivel', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener menús disponibles para un rol (que no tienen acceso asignado)
     */
    public function getMenusDisponiblesPorRol($idRol)
    {
        $db = \Config\Database::connect();
        
        return $db->table('menu')
                  ->select('menu.*')
                  ->where('menu.estado', 1)
                  ->where("menu.id NOT IN (SELECT id_menu FROM accesos WHERE id_rol = $idRol AND estado = 1)")
                  ->orderBy('menu.nombre', 'ASC')
                  ->get()
                  ->getResultArray();
    }

    /**
     * Verificar si ya existe un acceso para el rol y menú específico
     */
    public function existeAcceso($idRol, $idMenu, $excludeId = null)
    {
        $builder = $this->where('id_rol', $idRol)
                        ->where('id_menu', $idMenu);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Activar/Desactivar acceso
     */
    public function cambiarEstado($id, $estado)
    {
        return $this->update($id, [
            'estado' => $estado,
            'usuario_actualiza' => session('user_id') ?? 1,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Obtener estadísticas de accesos
     */
    public function getEstadisticasAccesos()
    {
        $total = $this->countAll();
        $activos = $this->where('estado', 1)->countAllResults();
        $inactivos = $this->where('estado', 0)->countAllResults();
        
        // Obtener cantidad de roles con acceso
        $rolesConAcceso = $this->select('id_rol')
                              ->distinct()
                              ->where('estado', 1)
                              ->countAllResults();
        
        return [
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos,
            'roles_con_acceso' => $rolesConAcceso
        ];
    }

    /**
     * Eliminar todos los accesos de un rol
     */
    public function eliminarAccesosPorRol($idRol)
    {
        return $this->where('id_rol', $idRol)->delete();
    }

    /**
     * Asignar múltiples accesos a un rol
     */
    public function asignarAccesosMultiples($idRol, $idsMenus)
    {
        $data = [];
        $userId = session('user_id') ?? 1;
        $fecha = date('Y-m-d H:i:s');
        
        foreach ($idsMenus as $idMenu) {
            $data[] = [
                'id_rol' => $idRol,
                'id_menu' => $idMenu,
                'estado' => 1,
                'usuario_crea' => $userId,
                'fecha_registro' => $fecha
            ];
        }
        
        return $this->insertBatch($data);
    }
}
