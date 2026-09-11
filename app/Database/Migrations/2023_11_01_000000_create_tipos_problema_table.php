<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTiposProblemaTable extends Migration
{
    public function up()
    {
        // Crear tabla tipos_problema
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'categoria' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Categoría para agrupar tipos de problemas'
            ],
            'prioridad_predeterminada' => [
                'type' => 'ENUM',
                'constraint' => ['BAJA', 'MEDIA', 'ALTA', 'CRITICA'],
                'default' => 'MEDIA',
                'null' => true
            ],
            'tiempo_estimado' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Tiempo estimado en minutos para resolver el problema'
            ],
            'estado' => [
                'type' => 'ENUM',
                'constraint' => ['ACTIVO', 'INACTIVO'],
                'default' => 'ACTIVO'
            ],
            'fecha_registro' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP'
            ],
            'fecha_actualizacion' => [
                'type' => 'DATETIME',
                'null' => true,
                'on update' => 'CURRENT_TIMESTAMP'
            ],
            'fecha_eliminacion' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'usuario_crea' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'usuario_actualiza' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ]
        ]);

        // Clave primaria
        $this->forge->addKey('id', true);
        
        // Índices
        $this->forge->addKey('categoria');
        $this->forge->addKey('estado');
        
        // Crear la tabla
        $this->forge->createTable('tipos_problema', true);
        
        // Insertar datos iniciales
        $this->seedTiposProblema();
    }

    public function down()
    {
        // Eliminar la tabla tipos_problema
        $this->forge->dropTable('tipos_problema', true);
    }
    
    /**
     * Inserta datos iniciales en la tabla tipos_problema
     */
    private function seedTiposProblema()
    {
        $tipos = [
            // Categoría: Motor
            [
                'nombre' => 'Fuga de aceite',
                'descripcion' => 'Pérdida de aceite del motor',
                'categoria' => 'MOTOR',
                'prioridad_predeterminada' => 'ALTA',
                'tiempo_estimado' => 120,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ],
            [
                'nombre' => 'Sobrecalentamiento',
                'descripcion' => 'El motor se sobrecalienta',
                'categoria' => 'MOTOR',
                'prioridad_predeterminada' => 'CRITICA',
                'tiempo_estimado' => 180,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ],
            
            // Categoría: Frenos
            [
                'nombre' => 'Pastillas de freno desgastadas',
                'descripcion' => 'Las pastillas de freno necesitan reemplazo',
                'categoria' => 'FRENOS',
                'prioridad_predeterminada' => 'ALTA',
                'tiempo_estimado' => 90,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ],
            [
                'nombre' => 'Líquido de frenos bajo',
                'descripcion' => 'Nivel bajo de líquido de frenos',
                'categoria' => 'FRENOS',
                'prioridad_predeterminada' => 'ALTA',
                'tiempo_estimado' => 30,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ],
            
            // Categoría: Neumáticos
            [
                'nombre' => 'Neumático desinflado',
                'descripcion' => 'Neumático con presión baja o pinchado',
                'categoria' => 'NEUMATICOS',
                'prioridad_predeterminada' => 'ALTA',
                'tiempo_estimado' => 30,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ],
            [
                'nombre' => 'Desgaste irregular de neumáticos',
                'descripcion' => 'Desgaste desigual en los neumáticos',
                'categoria' => 'NEUMATICOS',
                'prioridad_predeterminada' => 'MEDIA',
                'tiempo_estimado' => 60,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ],
            
            // Categoría: Eléctrico
            [
                'nombre' => 'Batería descargada',
                'descripcion' => 'La batería no tiene carga suficiente',
                'categoria' => 'ELECTRICO',
                'prioridad_predeterminada' => 'ALTA',
                'tiempo_estimado' => 45,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ],
            [
                'nombre' => 'Fallo en luces',
                'descripcion' => 'Luces delanteras, traseras o intermitentes no funcionan',
                'categoria' => 'ELECTRICO',
                'prioridad_predeterminada' => 'MEDIA',
                'tiempo_estimado' => 60,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ],
            
            // Categoría: Mantenimiento Programado
            [
                'nombre' => 'Cambio de aceite',
                'descripcion' => 'Cambio de aceite y filtro programado',
                'categoria' => 'MANTENIMIENTO',
                'prioridad_predeterminada' => 'MEDIA',
                'tiempo_estimado' => 60,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ],
            [
                'nombre' => 'Rotación de neumáticos',
                'descripcion' => 'Rotación de neumáticos según kilometraje',
                'categoria' => 'MANTENIMIENTO',
                'prioridad_predeterminada' => 'BAJA',
                'tiempo_estimado' => 45,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ],
            
            // Categoría: Otros
            [
                'nombre' => 'Otro problema',
                'descripcion' => 'Problema no listado',
                'categoria' => 'OTROS',
                'prioridad_predeterminada' => 'MEDIA',
                'tiempo_estimado' => 60,
                'estado' => 'ACTIVO',
                'usuario_crea' => 1
            ]
        ];
        
        // Insertar los tipos de problema
        $this->db->table('tipos_problema')->insertBatch($tipos);
    }
}
