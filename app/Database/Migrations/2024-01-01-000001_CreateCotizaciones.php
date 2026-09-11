<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCotizaciones extends Migration
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
            'numero_cotizacion' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'unique'     => true,
            ],
            'producto_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'diesel',
            ],
            'producto_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'cliente_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'cliente_ruc' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null'       => true,
            ],
            'cliente_telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'null'       => true,
            ],
            'origen' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'destino' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'tipo_vehiculo' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Camion rigido',
            ],
            'distancia_km' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'volumen_galones' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],
            'rendimiento_km_galon' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'default'    => 0,
            ],
            'precio_combustible_galon' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'default'    => 0,
            ],
            'costo_viaje' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0,
            ],
            'margen_porcentaje' => [
                'type'       => 'DECIMAL',
                'constraint' => '6,2',
                'default'    => 0,
            ],
            'margen_monto' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0,
            ],
            'precio_sugerido' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0,
            ],
            'precio_final' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0,
            ],
            'flete_por_galon' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'default'    => 0,
            ],
            'desglose_costos' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'BORRADOR',
            ],
            'usuario_crea' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'fecha_creacion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'fecha_actualiza' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('numero_cotizacion');
        $this->forge->addKey('cliente_nombre');
        $this->forge->createTable('cotizaciones');
    }

    public function down()
    {
        $this->forge->dropTable('cotizaciones');
    }
}
