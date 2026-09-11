<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSolicitudesHistorialTable extends Migration
{
    public function up()
    {
        // Crear tabla solicitudes_historial
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'id_solicitud' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'ID de la solicitud asociada'
            ],
            'id_usuario' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Usuario que realizó el cambio'
            ],
            'estado_anterior' => [
                'type' => 'ENUM',
                'constraint' => ['PENDIENTE', 'EN_PROCESO', 'CERRADA', 'CANCELADA'],
                'null' => true,
                'comment' => 'Estado anterior de la solicitud'
            ],
            'estado_nuevo' => [
                'type' => 'ENUM',
                'constraint' => ['PENDIENTE', 'EN_PROCESO', 'CERRADA', 'CANCELADA'],
                'null' => false,
                'comment' => 'Nuevo estado de la solicitud'
            ],
            'comentario' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Comentario u observación del cambio de estado'
            ],
            'fecha_cambio' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
                'comment' => 'Fecha y hora del cambio de estado'
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
                'comment' => 'Dirección IP desde donde se realizó el cambio'
            ],
            'user_agent' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Agente de usuario (navegador) utilizado'
            ]
        ]);

        // Clave primaria
        $this->forge->addKey('id', true);
        
        // Índices
        $this->forge->addKey('id_solicitud');
        $this->forge->addKey('id_usuario');
        $this->forge->addKey('estado_nuevo');
        $this->forge->addKey('fecha_cambio');
        
        // Claves foráneas
        $this->forge->addForeignKey('id_solicitud', 'solicitudes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        
        // Crear la tabla
        $this->forge->createTable('solicitudes_historial', true);
        
        // Agregar comentario a la tabla
        $this->db->query("ALTER TABLE `solicitudes_historial` COMMENT 'Historial de cambios de estado de las solicitudes de mantenimiento'");
    }

    public function down()
    {
        // Eliminar la tabla solicitudes_historial
        $this->forge->dropTable('solicitudes_historial', true);
    }
}
