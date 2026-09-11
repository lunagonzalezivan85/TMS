<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAsignacionVehiculosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_vehiculo' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_conductor' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'fecha_asignacion' => [
                'type' => 'DATETIME',
            ],
            'fecha_desasignacion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'motivo_asignacion' => [
                'type'       => 'TEXT',
            ],
            'motivo_desasignacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['ACTIVA', 'INACTIVA'],
                'default'    => 'ACTIVA',
            ],
            'usuarioCrea' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'usuarioEdita' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'fechaRegistro' => [
                'type'    => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'fechaUpdate' => [
                'type'    => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_vehiculo');
        $this->forge->addKey('id_conductor');
        $this->forge->addKey('estado');
        $this->forge->addKey('fecha_asignacion');

        // Crear la tabla
        $this->forge->createTable('asignacion_vehiculos');

        // Agregar foreign keys
        $this->forge->addForeignKey('id_vehiculo', 'vehiculos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_conductor', 'conductores', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuarioCrea', 'usuarios', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('usuarioEdita', 'usuarios', 'id', 'SET NULL', 'CASCADE');

        // Crear índices compuestos para mejorar el rendimiento
        $this->db->query('CREATE INDEX idx_vehiculo_estado ON asignacion_vehiculos (id_vehiculo, estado)');
        $this->db->query('CREATE INDEX idx_conductor_estado ON asignacion_vehiculos (id_conductor, estado)');
        $this->db->query('CREATE INDEX idx_fecha_estado ON asignacion_vehiculos (fecha_asignacion, estado)');
    }

    public function down()
    {
        $this->forge->dropTable('asignacion_vehiculos');
    }
}
