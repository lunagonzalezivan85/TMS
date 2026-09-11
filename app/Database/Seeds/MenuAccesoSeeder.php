<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuAccesoSeeder extends Seeder
{
    public function run()
    {
        // Verificar si ya existe el menú de Accesos
        $existingMenu = $this->db->table('menu')
            ->where('ruta', 'acceso')
            ->get()
            ->getRowArray();

        if ($existingMenu) {
            echo "El menú de Accesos ya existe.\n";
            return;
        }

        // Obtener el ID del menú de Administración (si existe)
        $adminMenu = $this->db->table('menu')
            ->where('menu', 'Administración')
            ->orWhere('menu', 'Admin')
            ->orWhere('ruta', 'admin')
            ->get()
            ->getRowArray();

        // Datos del menú de Accesos
        $menuData = [
            'icono' => 'fas fa-key',
            'menu' => 'Accesos',
            'id_superior' => $adminMenu ? $adminMenu['id'] : null,
            'nivel' => $adminMenu ? 2 : 1,
            'ruta' => 'acceso',
            'estado' => 1,
            'usuario_crea' => 1,
            'fecha_registra' => date('Y-m-d H:i:s'),
        ];

        // Insertar el menú
        $this->db->table('menu')->insert($menuData);
        $menuId = $this->db->insertID();

        echo "Menú de Accesos creado con ID: {$menuId}\n";

        // Si no existe un menú de administración, crear uno
        if (!$adminMenu) {
            $adminMenuData = [
                'icono' => 'fas fa-cogs',
                'menu' => 'Administración',
                'id_superior' => null,
                'nivel' => 1,
                'ruta' => '#',
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registra' => date('Y-m-d H:i:s'),
            ];

            $this->db->table('menu')->insert($adminMenuData);
            $adminMenuId = $this->db->insertID();

            // Actualizar el menú de Accesos para que sea hijo del menú de Administración
            $this->db->table('menu')
                ->where('id', $menuId)
                ->update([
                    'id_superior' => $adminMenuId,
                    'nivel' => 2
                ]);

            echo "Menú de Administración creado con ID: {$adminMenuId}\n";
            echo "Menú de Accesos actualizado como submenú de Administración\n";
        }

        // Verificar si existen otros menús de administración y agregarlos como hermanos
        $this->createAdminMenusIfNotExist();
    }

    private function createAdminMenusIfNotExist()
    {
        // Obtener el menú de Administración
        $adminMenu = $this->db->table('menu')
            ->where('menu', 'Administración')
            ->get()
            ->getRowArray();

        if (!$adminMenu) {
            return;
        }

        $adminMenus = [
            [
                'icono' => 'fas fa-users',
                'menu' => 'Usuarios',
                'ruta' => 'admin/usuarios',
                'check_ruta' => 'admin/usuarios'
            ],
            [
                'icono' => 'fas fa-user-shield',
                'menu' => 'Roles',
                'ruta' => 'rol',
                'check_ruta' => 'rol'
            ],
            [
                'icono' => 'fas fa-bars',
                'menu' => 'Menús',
                'ruta' => 'menu',
                'check_ruta' => 'menu'
            ]
        ];

        foreach ($adminMenus as $menuInfo) {
            // Verificar si ya existe
            $existing = $this->db->table('menu')
                ->where('ruta', $menuInfo['check_ruta'])
                ->get()
                ->getRowArray();

            if (!$existing) {
                $menuData = [
                    'icono' => $menuInfo['icono'],
                    'menu' => $menuInfo['menu'],
                    'id_superior' => $adminMenu['id'],
                    'nivel' => 2,
                    'ruta' => $menuInfo['ruta'],
                    'estado' => 1,
                    'usuario_crea' => 1,
                    'fecha_registra' => date('Y-m-d H:i:s'),
                ];

                $this->db->table('menu')->insert($menuData);
                echo "Menú {$menuInfo['menu']} creado\n";
            }
        }
    }
}
