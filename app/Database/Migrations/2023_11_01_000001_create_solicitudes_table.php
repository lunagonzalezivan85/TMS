<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSolicitudesTable extends Migration
{
    public function up()
    {
        // Crear tabla solicitudes
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'id_empresa' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Empresa a la que pertenece la solicitud'
            ],
            'codigo_consecutivo' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'comment' => 'Código único de la solicitud (ej: SOL-0001)'
            ],
            'id_vehiculo' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Vehículo asociado a la solicitud'
            ],
            'id_solicitante' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Usuario que crea la solicitud'
            ],
            'id_asignado_a' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Usuario asignado para atender la solicitud'
            ],
            'id_tipo_problema' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Tipo de problema reportado'
            ],
            'titulo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Título descriptivo de la solicitud'
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Descripción detallada del problema o solicitud'
            ],
            'prioridad' => [
                'type' => 'ENUM',
                'constraint' => ['BAJA', 'MEDIA', 'ALTA', 'CRITICA'],
                'default' => 'MEDIA',
                'null' => false,
                'comment' => 'Nivel de prioridad de la solicitud'
            ],
            'estado' => [
                'type' => 'ENUM',
                'constraint' => ['PENDIENTE', 'EN_PROCESO', 'CERRADA', 'CANCELADA'],
                'default' => 'PENDIENTE',
                'null' => false,
                'comment' => 'Estado actual de la solicitud'
            ],
            'fecha_solicitud' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => 'Fecha y hora en que se realizó la solicitud'
            ],
            'fecha_asignacion' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Fecha y hora en que se asignó la solicitud'
            ],
            'fecha_cierre' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Fecha y hora en que se cerró la solicitud'
            ],
            'fecha_vencimiento' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Fecha límite para atender la solicitud'
            ],
            'costo_estimado' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'default' => 0.00,
                'comment' => 'Costo estimado de la reparación o mantenimiento'
            ],
            'costo_real' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'default' => 0.00,
                'comment' => 'Costo real de la reparación o mantenimiento'
            ],
            'kilometraje' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Kilometraje del vehículo al momento de la solicitud'
            ],
            'observaciones' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Observaciones adicionales o notas del técnico'
            ],
            'fecha_registro' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
                'comment' => 'Fecha de registro en el sistema'
            ],
            'fecha_actualizacion' => [
                'type' => 'DATETIME',
                'null' => true,
                'on update' => 'CURRENT_TIMESTAMP',
                'comment' => 'Fecha de última actualización'
            ],
            'fecha_eliminacion' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Fecha de eliminación lógica'
            ],
            'usuario_crea' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'ID del usuario que creó el registro'
            ],
            'usuario_actualiza' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID del usuario que actualizó el registro por última vez'
            ]
        ]);

        // Clave primaria
        $this->forge->addKey('id', true);
        
        // Índices
        $this->forge->addKey('id_empresa');
        $this->forge->addKey('id_vehiculo');
        $this->forge->addKey('id_solicitante');
        $this->forge->addKey('id_asignado_a');
        $this->forge->addKey('id_tipo_problema');
        $this->forge->addKey('estado');
        $this->forge->addKey('fecha_solicitud');
        $this->forge->addKey('fecha_vencimiento');
        
        // Claves foráneas
        $this->forge->addForeignKey('id_empresa', 'empresas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_vehiculo', 'vehiculos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_solicitante', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_asignado_a', 'usuarios', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('id_tipo_problema', 'tipos_problema', 'id', 'SET NULL', 'SET NULL');
        
        // Crear la tabla
        $this->forge->createTable('solicitudes', true);
        
        // Agregar comentario a la tabla
        $this->db->query("ALTER TABLE `solicitudes` COMMENT 'Solicitudes de mantenimiento de vehículos'");
    }

    public function down()
    {
        // Eliminar la tabla solicitudes
        $this->forge->dropTable('solicitudes', true);
    }
}
