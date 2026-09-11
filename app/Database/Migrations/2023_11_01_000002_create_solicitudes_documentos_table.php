<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSolicitudesDocumentosTable extends Migration
{
    public function up()
    {
        // Crear tabla solicitudes_documentos
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
                'comment' => 'Usuario que subió el documento'
            ],
            'nombre_original' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Nombre original del archivo'
            ],
            'nombre_archivo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Nombre único del archivo en el sistema'
            ],
            'ruta' => [
                'type' => 'VARCHAR',
                'constraint' => 512,
                'null' => false,
                'comment' => 'Ruta relativa del archivo en el servidor'
            ],
            'tipo_archivo' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'Tipo MIME del archivo'
            ],
            'tamano' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Tamaño del archivo en bytes'
            ],
            'descripcion' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
                'comment' => 'Descripción opcional del documento'
            ],
            'fecha_subida' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
                'comment' => 'Fecha y hora de subida del archivo'
            ],
            'fecha_actualizacion' => [
                'type' => 'DATETIME',
                'null' => true,
                'on update' => 'CURRENT_TIMESTAMP',
                'comment' => 'Fecha de última actualización'
            ],
            'estado' => [
                'type' => 'ENUM',
                'constraint' => ['ACTIVO', 'INACTIVO'],
                'default' => 'ACTIVO',
                'null' => false,
                'comment' => 'Estado del documento'
            ]
        ]);

        // Clave primaria
        $this->forge->addKey('id', true);
        
        // Índices
        $this->forge->addKey('id_solicitud');
        $this->forge->addKey('id_usuario');
        $this->forge->addKey('estado');
        
        // Claves foráneas
        $this->forge->addForeignKey('id_solicitud', 'solicitudes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        
        // Crear la tabla
        $this->forge->createTable('solicitudes_documentos', true);
        
        // Agregar comentario a la tabla
        $this->db->query("ALTER TABLE `solicitudes_documentos` COMMENT 'Documentos adjuntos a solicitudes de mantenimiento'");
    }

    public function down()
    {
        // Eliminar la tabla solicitudes_documentos
        $this->forge->dropTable('solicitudes_documentos', true);
    }
}
