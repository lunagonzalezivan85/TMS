<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenusTable extends Migration
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
            'icono' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'comment'    => 'Clase del icono FontAwesome (ej: fas fa-car)',
            ],
            'menu' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'comment'    => 'Nombre del menú que aparecerá en la interfaz',
            ],
            'id_superior' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'comment'    => 'ID del menú padre (NULL para menús principales)',
            ],
            'nivel' => [
                'type'       => 'TINYINT',
                'constraint' => 2,
                'unsigned'   => true,
                'null'       => false,
                'default'    => 1,
                'comment'    => 'Nivel en la jerarquía (1=principal, 2=submenú, etc.)',
            ],
            'orden' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'default'    => 0,
                'comment'    => 'Orden de aparición dentro del mismo nivel',
            ],
            'url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'comment'    => 'URL del menú (opcional para menús contenedores)',
            ],
            'activo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
                'comment'    => '1=Activo, 0=Inactivo',
            ],
            'usuario_crea' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'ID del usuario que creó el menú',
            ],
            'usuario_edita' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'comment'    => 'ID del usuario que editó por última vez',
            ],
            'fecha_registra' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'comment' => 'Fecha y hora de creación del registro',
            ],
            'fecha_actualiza' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
                'comment' => 'Fecha y hora de última actualización',
            ],
        ]);

        // Definir clave primaria
        $this->forge->addKey('id', true);

        // Definir índices
        $this->forge->addKey('id_superior');
        $this->forge->addKey('nivel');
        $this->forge->addKey('orden');
        $this->forge->addKey('activo');
        $this->forge->addKey(['nivel', 'orden']); // Índice compuesto para ordenamiento

        // Crear la tabla
        $this->forge->createTable('menus');

        // Agregar restricciones de clave foránea
        $this->db->query('ALTER TABLE menus ADD CONSTRAINT fk_menus_superior 
                         FOREIGN KEY (id_superior) REFERENCES menus(id) 
                         ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        // Eliminar restricciones de clave foránea primero
        $this->db->query('ALTER TABLE menus DROP FOREIGN KEY fk_menus_superior');
        
        // Eliminar la tabla
        $this->forge->dropTable('menus');
    }
}
