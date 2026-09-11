<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AccesoSeeder extends Seeder
{
    public function run()
    {
        // Datos de ejemplo para accesos (rol-menú)
        $data = [
            // Administrador (id_rol = 1) - Acceso a todos los menús principales
            [
                'id_rol' => 1,
                'id_menu' => 1, // Dashboard
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 1,
                'id_menu' => 2, // Usuarios
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 1,
                'id_menu' => 3, // Roles
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 1,
                'id_menu' => 4, // Menús
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 1,
                'id_menu' => 5, // Accesos
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 1,
                'id_menu' => 6, // Vehículos
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 1,
                'id_menu' => 7, // Conductores
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 1,
                'id_menu' => 8, // Mantenimiento
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 1,
                'id_menu' => 9, // Combustible
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 1,
                'id_menu' => 10, // Reportes
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],

            // Supervisor (id_rol = 2) - Acceso limitado
            [
                'id_rol' => 2,
                'id_menu' => 1, // Dashboard
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 2,
                'id_menu' => 6, // Vehículos
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 2,
                'id_menu' => 7, // Conductores
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 2,
                'id_menu' => 8, // Mantenimiento
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 2,
                'id_menu' => 9, // Combustible
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 2,
                'id_menu' => 10, // Reportes
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],

            // Operador (id_rol = 3) - Acceso muy limitado
            [
                'id_rol' => 3,
                'id_menu' => 1, // Dashboard
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 3,
                'id_menu' => 6, // Vehículos (solo consulta)
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 3,
                'id_menu' => 9, // Combustible
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],

            // Mecánico (id_rol = 4) - Acceso especializado
            [
                'id_rol' => 4,
                'id_menu' => 1, // Dashboard
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 4,
                'id_menu' => 6, // Vehículos
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 4,
                'id_menu' => 8, // Mantenimiento
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],

            // Conductor (id_rol = 5) - Acceso mínimo
            [
                'id_rol' => 5,
                'id_menu' => 1, // Dashboard
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 5,
                'id_menu' => 9, // Combustible (registro de consumo)
                'estado' => 1,
                'usuario_crea' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insertar los datos
        $this->db->table('accesos')->insertBatch($data);

        echo "Seeder de Accesos ejecutado correctamente. Se insertaron " . count($data) . " registros.\n";
    }
}
