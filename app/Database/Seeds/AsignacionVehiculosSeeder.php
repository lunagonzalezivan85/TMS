<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AsignacionVehiculosSeeder extends Seeder
{
    public function run()
    {
        // Datos de ejemplo para asignaciones de vehículos
        $data = [
            [
                'id_vehiculo' => 1,
                'id_conductor' => 1,
                'fecha_asignacion' => '2024-01-01 08:00:00',
                'fecha_desasignacion' => null,
                'motivo_asignacion' => 'Asignación inicial para ruta urbana matutina. El conductor tiene experiencia en transporte urbano y conoce bien las rutas de la zona norte de la ciudad.',
                'motivo_desasignacion' => null,
                'estado' => 'ACTIVA',
                'usuarioCrea' => 1,
                'usuarioEdita' => null,
                'fechaRegistro' => '2024-01-01 08:00:00',
                'fechaUpdate' => '2024-01-01 08:00:00',
            ],
            [
                'id_vehiculo' => 2,
                'id_conductor' => 2,
                'fecha_asignacion' => '2024-01-02 09:00:00',
                'fecha_desasignacion' => null,
                'motivo_asignacion' => 'Asignación para servicio de transporte escolar. El conductor cuenta con certificación para transporte de menores y experiencia de 5 años en el sector.',
                'motivo_desasignacion' => null,
                'estado' => 'ACTIVA',
                'usuarioCrea' => 1,
                'usuarioEdita' => null,
                'fechaRegistro' => '2024-01-02 09:00:00',
                'fechaUpdate' => '2024-01-02 09:00:00',
            ],
            [
                'id_vehiculo' => 3,
                'id_conductor' => 3,
                'fecha_asignacion' => '2024-01-03 07:30:00',
                'fecha_desasignacion' => '2024-01-10 18:00:00',
                'motivo_asignacion' => 'Asignación temporal para cubrir ruta de emergencia durante mantenimiento de otros vehículos.',
                'motivo_desasignacion' => 'Finalización del período de cobertura temporal. El vehículo regresa a su ruta original.',
                'estado' => 'INACTIVA',
                'usuarioCrea' => 1,
                'usuarioEdita' => 1,
                'fechaRegistro' => '2024-01-03 07:30:00',
                'fechaUpdate' => '2024-01-10 18:00:00',
            ],
            [
                'id_vehiculo' => 4,
                'id_conductor' => 4,
                'fecha_asignacion' => '2024-01-05 10:00:00',
                'fecha_desasignacion' => null,
                'motivo_asignacion' => 'Asignación para servicio de transporte ejecutivo. El conductor tiene licencia profesional y experiencia en atención a clientes corporativos.',
                'motivo_desasignacion' => null,
                'estado' => 'ACTIVA',
                'usuarioCrea' => 1,
                'usuarioEdita' => null,
                'fechaRegistro' => '2024-01-05 10:00:00',
                'fechaUpdate' => '2024-01-05 10:00:00',
            ],
            [
                'id_vehiculo' => 5,
                'id_conductor' => 5,
                'fecha_asignacion' => '2024-01-08 06:00:00',
                'fecha_desasignacion' => null,
                'motivo_asignacion' => 'Asignación para ruta intercity. El conductor tiene experiencia en viajes largos y conocimiento de rutas interurbanas.',
                'motivo_desasignacion' => null,
                'estado' => 'ACTIVA',
                'usuarioCrea' => 1,
                'usuarioEdita' => null,
                'fechaRegistro' => '2024-01-08 06:00:00',
                'fechaUpdate' => '2024-01-08 06:00:00',
            ],
            [
                'id_vehiculo' => 6,
                'id_conductor' => 6,
                'fecha_asignacion' => '2024-01-12 14:00:00',
                'fecha_desasignacion' => '2024-01-15 16:30:00',
                'motivo_asignacion' => 'Asignación de emergencia para cubrir ausencia por enfermedad de conductor titular.',
                'motivo_desasignacion' => 'Reintegro del conductor titular después de recuperación médica.',
                'estado' => 'INACTIVA',
                'usuarioCrea' => 1,
                'usuarioEdita' => 1,
                'fechaRegistro' => '2024-01-12 14:00:00',
                'fechaUpdate' => '2024-01-15 16:30:00',
            ],
        ];

        // Insertar los datos
        $this->db->table('asignacion_vehiculos')->insertBatch($data);

        echo "Seeder de asignaciones de vehículos ejecutado correctamente.\n";
        echo "Se han insertado " . count($data) . " registros de ejemplo.\n";
    }
}
