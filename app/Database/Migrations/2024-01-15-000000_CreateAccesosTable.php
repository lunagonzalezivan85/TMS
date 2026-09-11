<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccesosTable extends Migration
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
            'id_rol' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_menu' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Activo, 0=Inactivo'
            ],
            'usuario_crea' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'usuario_actualiza' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'fecha_registro' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'fecha_actualizacion' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        // Definir clave primaria
        $this->forge->addPrimaryKey('id');

        // Definir claves foráneas
        $this->forge->addForeignKey('id_rol', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_menu', 'menu', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_crea', 'usuarios', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('usuario_actualiza', 'usuarios', 'id', 'RESTRICT', 'CASCADE');

        // Crear índices
        $this->forge->addKey(['id_rol', 'id_menu'], false, true); // Índice único compuesto
        $this->forge->addKey('id_rol');
        $this->forge->addKey('id_menu');
        $this->forge->addKey('estado');

        // Crear la tabla
        $this->forge->createTable('accesos');
    }

    public function down()
    {
        $this->forge->dropTable('accesos');
    }
}
