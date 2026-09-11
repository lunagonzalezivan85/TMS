<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeederSimple extends Seeder
{
    public function run()
    {
        // Datos de ejemplo para menús del sistema GMV
        $menus = [
            // MENÚS PRINCIPALES (Nivel 1)
            [
                'icono' => 'fas fa-tachometer-alt',
                'menu' => 'Dashboard',
                'id_superior' => null,
                'nivel' => 1,
                'ruta' => 'dashboard',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-car',
                'menu' => 'Gestión de Vehículos',
                'id_superior' => null,
                'nivel' => 1,
                'ruta' => null, // Menú contenedor
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-users',
                'menu' => 'Gestión de Conductores',
                'id_superior' => null,
                'nivel' => 1,
                'ruta' => null, // Menú contenedor
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-gas-pump',
                'menu' => 'Control de Combustible',
                'id_superior' => null,
                'nivel' => 1,
                'ruta' => null, // Menú contenedor
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-tools',
                'menu' => 'Mantenimiento',
                'id_superior' => null,
                'nivel' => 1,
                'ruta' => null, // Menú contenedor
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-chart-bar',
                'menu' => 'Reportes y Análisis',
                'id_superior' => null,
                'nivel' => 1,
                'ruta' => null, // Menú contenedor
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-cog',
                'menu' => 'Configuración',
                'id_superior' => null,
                'nivel' => 1,
                'ruta' => null, // Menú contenedor
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insertar los menús principales primero
        foreach ($menus as $menu) {
            $this->db->table('menu')->insert($menu);
        }

        // Obtener los IDs de los menús principales para crear submenús
        $dashboardId = $this->db->table('menu')->where('menu', 'Dashboard')->get()->getRow()->id;
        $vehiculosId = $this->db->table('menu')->where('menu', 'Gestión de Vehículos')->get()->getRow()->id;
        $conductoresId = $this->db->table('menu')->where('menu', 'Gestión de Conductores')->get()->getRow()->id;
        $combustibleId = $this->db->table('menu')->where('menu', 'Control de Combustible')->get()->getRow()->id;
        $mantenimientoId = $this->db->table('menu')->where('menu', 'Mantenimiento')->get()->getRow()->id;
        $reportesId = $this->db->table('menu')->where('menu', 'Reportes y Análisis')->get()->getRow()->id;
        $configId = $this->db->table('menu')->where('menu', 'Configuración')->get()->getRow()->id;

        // SUBMENÚS DE GESTIÓN DE VEHÍCULOS (Nivel 2)
        $submenus = [
            [
                'icono' => 'fas fa-list',
                'menu' => 'Lista de Vehículos',
                'id_superior' => $vehiculosId,
                'nivel' => 2,
                'ruta' => 'vehiculos',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-plus',
                'menu' => 'Registrar Vehículo',
                'id_superior' => $vehiculosId,
                'nivel' => 2,
                'ruta' => 'vehiculos/create',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],

            // SUBMENÚS DE GESTIÓN DE CONDUCTORES (Nivel 2)
            [
                'icono' => 'fas fa-list',
                'menu' => 'Lista de Conductores',
                'id_superior' => $conductoresId,
                'nivel' => 2,
                'ruta' => 'conductores',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-user-plus',
                'menu' => 'Registrar Conductor',
                'id_superior' => $conductoresId,
                'nivel' => 2,
                'ruta' => 'conductores/create',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],

            // SUBMENÚS DE CONTROL DE COMBUSTIBLE (Nivel 2)
            [
                'icono' => 'fas fa-clipboard-list',
                'menu' => 'Registros de Combustible',
                'id_superior' => $combustibleId,
                'nivel' => 2,
                'ruta' => 'registro-combustible',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-plus-circle',
                'menu' => 'Nuevo Registro',
                'id_superior' => $combustibleId,
                'nivel' => 2,
                'ruta' => 'registro-combustible/create',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],

            // SUBMENÚS DE MANTENIMIENTO (Nivel 2)
            [
                'icono' => 'fas fa-wrench',
                'menu' => 'Solicitudes de Mantenimiento',
                'id_superior' => $mantenimientoId,
                'nivel' => 2,
                'ruta' => 'solicitudes',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],

            // SUBMENÚS DE REPORTES (Nivel 2)
            [
                'icono' => 'fas fa-file-pdf',
                'menu' => 'Reportes de Vehículos',
                'id_superior' => $reportesId,
                'nivel' => 2,
                'ruta' => 'reportes/vehiculos',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-chart-pie',
                'menu' => 'Análisis de Combustible',
                'id_superior' => $reportesId,
                'nivel' => 2,
                'ruta' => 'reportes/combustible',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],

            // SUBMENÚS DE CONFIGURACIÓN (Nivel 2)
            [
                'icono' => 'fas fa-users-cog',
                'menu' => 'Gestión de Usuarios',
                'id_superior' => $configId,
                'nivel' => 2,
                'ruta' => 'admin/usuarios',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-shield-alt',
                'menu' => 'Roles y Permisos',
                'id_superior' => $configId,
                'nivel' => 2,
                'ruta' => 'admin/roles',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
            [
                'icono' => 'fas fa-sitemap',
                'menu' => 'Gestión de Menús',
                'id_superior' => $configId,
                'nivel' => 2,
                'ruta' => 'menu',
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insertar los submenús
        $this->db->table('menu')->insertBatch($submenus);

        echo "Seeder de menús ejecutado exitosamente. Se insertaron " . (count($menus) + count($submenus)) . " menús.\n";
    }
}
