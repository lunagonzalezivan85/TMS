<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialesTrabajoModel extends Model
{
    protected $table = 'materiales_trabajo';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id_registro_trabajo',
        'id_material',
        'cantidad',
        'costo_unitario',
        'costo_total'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_registro';
    protected $updatedField = null;
    protected $skipValidation = false;
    
    // Validaciones
    protected $validationRules = [
        'id_registro_trabajo' => 'required|integer',
        'id_material' => 'required|integer',
        'cantidad' => 'required|integer|greater_than[0]',
        'costo_unitario' => 'required|decimal|greater_than_equal_to[0]'
    ];

    protected $validationMessages = [
        'id_registro_trabajo' => [
            'required' => 'El registro de trabajo es requerido',
            'integer' => 'El registro de trabajo debe ser un número válido'
        ],
        'id_material' => [
            'required' => 'El material es requerido',
            'integer' => 'El material debe ser un número válido'
        ],
        'cantidad' => [
            'required' => 'La cantidad es requerida',
            'integer' => 'La cantidad debe ser un número entero',
            'greater_than' => 'La cantidad debe ser mayor a 0'
        ],
        'costo_unitario' => [
            'required' => 'El costo unitario es requerido',
            'decimal' => 'El costo unitario debe ser un número válido',
            'greater_than_equal_to' => 'El costo unitario debe ser mayor o igual a 0'
        ]
    ];

    /**
     * Obtener materiales por registro de trabajo con información del material
     */
    public function getMaterialesPorRegistro($idRegistroTrabajo)
    {
        return $this->db->table('materiales_trabajo mt')
            ->select('mt.*, 
                    m.nombre as nombre_material, 
                    m.descripcion as descripcion_material,
                    m.unidad_medida,
                    m.precio_referencia,
                    m.categoria')
            ->join('materiales m', 'm.id = mt.id_material', 'left')
            ->where('mt.id_registro_trabajo', $idRegistroTrabajo)
            ->orderBy('mt.fecha_registro', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Insertar múltiples materiales para un registro de trabajo
     */
    public function insertarMateriales($idRegistroTrabajo, $materiales)
    {
        $this->db->transStart();
        
        foreach ($materiales as $material) {
            $data = [
                'id_registro_trabajo' => $idRegistroTrabajo,
                'id_material' => $material['id_material'],
                'cantidad' => $material['cantidad'],
                'costo_unitario' => $material['costo_unitario']
            ];
            
            $this->insert($data);
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

    /**
     * Actualizar material específico
     */
    public function actualizarMaterial($idMaterialTrabajo, $cantidad, $costoUnitario)
    {
        return $this->update($idMaterialTrabajo, [
            'cantidad' => $cantidad,
            'costo_unitario' => $costoUnitario
        ]);
    }

    /**
     * Eliminar material de un trabajo
     */
    public function eliminarMaterial($idMaterialTrabajo)
    {
        return $this->delete($idMaterialTrabajo);
    }

    /**
     * Obtener costo total de materiales por registro
     */
    public function getCostoTotalPorRegistro($idRegistroTrabajo)
    {
        $result = $this->db->table('materiales_trabajo')
            ->select('SUM(costo_total) as total')
            ->where('id_registro_trabajo', $idRegistroTrabajo)
            ->get()
            ->getRowArray();
        
        return $result['total'] ?? 0;
    }

    /**
     * Obtener estadísticas de uso de materiales
     */
    public function getEstadisticasMateriales($fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->db->table('materiales_trabajo mt')
            ->select('m.nombre as material,
                    m.categoria,
                    COUNT(*) as veces_usado,
                    SUM(mt.cantidad) as cantidad_total,
                    AVG(mt.costo_unitario) as costo_promedio,
                    SUM(mt.costo_total) as costo_total_acumulado')
            ->join('materiales m', 'm.id = mt.id_material', 'left')
            ->groupBy('mt.id_material, m.nombre, m.categoria');
        
        if ($fechaInicio) {
            $builder->where('mt.fecha_registro >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $builder->where('mt.fecha_registro <=', $fechaFin);
        }
        
        return $builder->orderBy('cantidad_total', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Obtener materiales más utilizados
     */
    public function getMaterialesMasUtilizados($limite = 10)
    {
        return $this->db->table('materiales_trabajo mt')
            ->select('m.nombre as material,
                    m.categoria,
                    COUNT(*) as veces_usado,
                    SUM(mt.cantidad) as cantidad_total')
            ->join('materiales m', 'm.id = mt.id_material', 'left')
            ->groupBy('mt.id_material, m.nombre, m.categoria')
            ->orderBy('veces_usado', 'DESC')
            ->limit($limite)
            ->get()
            ->getResultArray();
    }

    /**
     * Verificar si un material está siendo utilizado
     */
    public function materialEnUso($idMaterial)
    {
        $count = $this->where('id_material', $idMaterial)->countAllResults();
        return $count > 0;
    }

    /**
     * Obtener historial de uso de un material específico
     */
    public function getHistorialMaterial($idMaterial, $limite = 20)
    {
        return $this->db->table('materiales_trabajo mt')
            ->select('mt.*, 
                    rt.codigo_consecutivo,
                    rt.fecha_inicio,
                    rt.trabajo_realizado,
                    v.placa,
                    CONCAT(u.nombre, " ", u.apellido) as tecnico')
            ->join('registro_trabajo_realizado rt', 'rt.id = mt.id_registro_trabajo', 'left')
            ->join('vehiculos v', 'v.id = rt.id_vehiculo', 'left')
            ->join('usuarios u', 'u.id = rt.id_tecnico', 'left')
            ->where('mt.id_material', $idMaterial)
            ->orderBy('mt.fecha_registro', 'DESC')
            ->limit($limite)
            ->get()
            ->getResultArray();
    }

    /**
     * Reemplazar todos los materiales de un registro de trabajo
     */
    public function reemplazarMateriales($idRegistroTrabajo, $nuevosMateriales)
    {
        $this->db->transStart();
        
        // Eliminar materiales existentes
        $this->where('id_registro_trabajo', $idRegistroTrabajo)->delete();
        
        // Insertar nuevos materiales
        if (!empty($nuevosMateriales)) {
            $this->insertarMateriales($idRegistroTrabajo, $nuevosMateriales);
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }
}
