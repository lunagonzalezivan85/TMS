<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleMovimientoModel extends Model
{
    protected $table = 'detalle_movimiento';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id_movimiento',
        'id_material',
        'cantidad',
        'precio',
        'impuesto',
        'linea',
        'subtotal',
        'total_linea',
        'usuario_crea',
        'usuario_edita',
        'id_empresa',
        'fecha_registro',
        'fecha_actualiza'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualiza';
    protected $skipValidation = false;
    
    // Validaciones
    protected $validationRules = [
        'id_movimiento' => 'required|integer',
        'id_material' => 'required|integer',
        'cantidad' => 'required|decimal|greater_than[0]',
        'precio' => 'required|decimal|greater_than_equal_to[0]',
        'impuesto' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'linea' => 'required|integer|greater_than[0]',
        'usuario_crea' => 'required|max_length[100]',
        'id_empresa' => 'required|integer'
    ];

    protected $validationMessages = [
        'id_movimiento' => [
            'required' => 'El ID del movimiento es requerido',
            'integer' => 'El ID del movimiento debe ser un número válido'
        ],
        'id_material' => [
            'required' => 'El material es requerido',
            'integer' => 'El ID del material debe ser un número válido'
        ],
        'cantidad' => [
            'required' => 'La cantidad es requerida',
            'decimal' => 'La cantidad debe ser un número válido',
            'greater_than' => 'La cantidad debe ser mayor a 0'
        ],
        'precio' => [
            'required' => 'El precio es requerido',
            'decimal' => 'El precio debe ser un número válido',
            'greater_than_equal_to' => 'El precio debe ser mayor o igual a 0'
        ],
        'impuesto' => [
            'decimal' => 'El impuesto debe ser un número válido',
            'greater_than_equal_to' => 'El impuesto debe ser mayor o igual a 0'
        ],
        'linea' => [
            'required' => 'El número de línea es requerido',
            'integer' => 'El número de línea debe ser un número válido',
            'greater_than' => 'El número de línea debe ser mayor a 0'
        ],
        'usuario_crea' => [
            'required' => 'El usuario creador es requerido'
        ],
        'id_empresa' => [
            'required' => 'La empresa es requerida',
            'integer' => 'La empresa debe ser un número válido'
        ]
    ];

    /**
     * Obtener detalles de un movimiento con información de materiales
     */
    public function getDetallesPorMovimiento($idMovimiento)
    {
        return $this->db->table('detalle_movimiento dm')
            ->select('dm.*, 
                    mat.codigo_consecutivo,
                    mat.nombre as material_nombre,
                    mat.unidad_medida,
                    mat.costo_unitario as costo_material')
            ->join('materiales mat', 'mat.id = dm.id_material')
            ->where('dm.id_movimiento', $idMovimiento)
            ->orderBy('dm.linea', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtener resumen de un detalle específico
     */
    public function getDetalleConMaterial($idDetalle)
    {
        return $this->db->table('detalle_movimiento dm')
            ->select('dm.*, 
                    mat.codigo_consecutivo,
                    mat.nombre as material_nombre,
                    mat.unidad_medida,
                    mat.costo_unitario as costo_material,
                    mov.codigo as codigo_movimiento,
                    mov.tipo_movimiento')
            ->join('materiales mat', 'mat.id = dm.id_material')
            ->join('movimientos mov', 'mov.id = dm.id_movimiento')
            ->where('dm.id', $idDetalle)
            ->get()
            ->getRowArray();
    }

    /**
     * Validar que el material pertenezca a la empresa
     */
    public function validarMaterialEmpresa($idMaterial, $idEmpresa)
    {
        $material = $this->db->table('materiales')
            ->select('id')
            ->where('id', $idMaterial)
            ->where('id_empresa', $idEmpresa)
            ->get()
            ->getRowArray();
        
        return !empty($material);
    }

    /**
     * Calcular totales de un detalle
     */
    public function calcularTotales($cantidad, $precio, $impuesto = 0)
    {
        $subtotal = $cantidad * $precio;
        $montoImpuesto = $subtotal * ($impuesto / 100);
        $totalLinea = $subtotal + $montoImpuesto;
        
        return [
            'subtotal' => round($subtotal, 2),
            'monto_impuesto' => round($montoImpuesto, 2),
            'total_linea' => round($totalLinea, 2)
        ];
    }

    /**
     * Insertar detalle con cálculos automáticos
     */
    public function insertarConCalculos($datos)
    {
        // Validar que el material pertenezca a la empresa
        if (!$this->validarMaterialEmpresa($datos['id_material'], $datos['id_empresa'])) {
            return [
                'success' => false,
                'message' => 'El material no pertenece a la empresa especificada'
            ];
        }
        
        // Calcular totales
        $totales = $this->calcularTotales(
            $datos['cantidad'], 
            $datos['precio'], 
            $datos['impuesto'] ?? 0
        );
        
        $datos['subtotal'] = $totales['subtotal'];
        $datos['total_linea'] = $totales['total_linea'];
        $datos['fecha_registro'] = date('Y-m-d H:i:s');
        
        $resultado = $this->insert($datos);
        
        if ($resultado) {
            return [
                'success' => true,
                'id_detalle' => $resultado,
                'totales' => $totales,
                'message' => 'Detalle insertado exitosamente'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al insertar el detalle'
            ];
        }
    }

    /**
     * Actualizar detalle con cálculos automáticos
     */
    public function actualizarConCalculos($idDetalle, $datos)
    {
        // Validar que el material pertenezca a la empresa si se está cambiando
        if (isset($datos['id_material']) && isset($datos['id_empresa'])) {
            if (!$this->validarMaterialEmpresa($datos['id_material'], $datos['id_empresa'])) {
                return [
                    'success' => false,
                    'message' => 'El material no pertenece a la empresa especificada'
                ];
            }
        }
        
        // Calcular totales si se proporcionan los datos necesarios
        if (isset($datos['cantidad']) && isset($datos['precio'])) {
            $totales = $this->calcularTotales(
                $datos['cantidad'], 
                $datos['precio'], 
                $datos['impuesto'] ?? 0
            );
            
            $datos['subtotal'] = $totales['subtotal'];
            $datos['total_linea'] = $totales['total_linea'];
        }
        
        $datos['fecha_actualiza'] = date('Y-m-d H:i:s');
        
        $resultado = $this->update($idDetalle, $datos);
        
        if ($resultado) {
            return [
                'success' => true,
                'totales' => $totales ?? null,
                'message' => 'Detalle actualizado exitosamente'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al actualizar el detalle'
            ];
        }
    }

    /**
     * Obtener estadísticas de materiales más utilizados
     */
    public function getMaterialesMasUtilizados($idEmpresa, $fechaDesde = null, $fechaHasta = null, $limite = 10)
    {
        $builder = $this->db->table('detalle_movimiento dm')
            ->select('mat.codigo_consecutivo,
                    mat.nombre as material_nombre,
                    mat.unidad_medida,
                    SUM(dm.cantidad) as cantidad_total,
                    COUNT(dm.id) as veces_utilizado,
                    AVG(dm.precio) as precio_promedio,
                    SUM(dm.total_linea) as valor_total')
            ->join('materiales mat', 'mat.id = dm.id_material')
            ->join('movimientos mov', 'mov.id = dm.id_movimiento')
            ->where('dm.id_empresa', $idEmpresa);
        
        if ($fechaDesde) {
            $builder->where('mov.fecha_movimiento >=', $fechaDesde);
        }
        
        if ($fechaHasta) {
            $builder->where('mov.fecha_movimiento <=', $fechaHasta);
        }
        
        return $builder->groupBy('dm.id_material')
                      ->orderBy('cantidad_total', 'DESC')
                      ->limit($limite)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Obtener resumen de movimientos por material
     */
    public function getResumenPorMaterial($idMaterial, $idEmpresa, $fechaDesde = null, $fechaHasta = null)
    {
        $builder = $this->db->table('detalle_movimiento dm')
            ->select('mov.tipo_movimiento,
                    mov.codigo as codigo_movimiento,
                    mov.fecha_movimiento,
                    dm.cantidad,
                    dm.precio,
                    dm.total_linea')
            ->join('movimientos mov', 'mov.id = dm.id_movimiento')
            ->where('dm.id_material', $idMaterial)
            ->where('dm.id_empresa', $idEmpresa);
        
        if ($fechaDesde) {
            $builder->where('mov.fecha_movimiento >=', $fechaDesde);
        }
        
        if ($fechaHasta) {
            $builder->where('mov.fecha_movimiento <=', $fechaHasta);
        }
        
        return $builder->orderBy('mov.fecha_movimiento', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Eliminar detalles de un movimiento
     */
    public function eliminarDetallesMovimiento($idMovimiento)
    {
        return $this->where('id_movimiento', $idMovimiento)->delete();
    }

    /**
     * Obtener total de líneas de un movimiento
     */
    public function getTotalLineasMovimiento($idMovimiento)
    {
        $resultado = $this->db->table('detalle_movimiento')
            ->select('COUNT(*) as total_lineas, SUM(total_linea) as monto_total')
            ->where('id_movimiento', $idMovimiento)
            ->get()
            ->getRowArray();
        
        return $resultado;
    }

    /**
     * Validar que no exista duplicado de material en el mismo movimiento
     */
    public function validarMaterialUnico($idMovimiento, $idMaterial, $idDetalleExcluir = null)
    {
        $builder = $this->where('id_movimiento', $idMovimiento)
                       ->where('id_material', $idMaterial);
        
        if ($idDetalleExcluir) {
            $builder->where('id !=', $idDetalleExcluir);
        }
        
        return $builder->countAllResults() == 0;
    }

    /**
     * Obtener siguiente número de línea para un movimiento
     */
    public function getSiguienteLinea($idMovimiento)
    {
        $ultimaLinea = $this->select('MAX(linea) as ultima_linea')
                           ->where('id_movimiento', $idMovimiento)
                           ->get()
                           ->getRowArray();
        
        return ($ultimaLinea['ultima_linea'] ?? 0) + 1;
    }

    /**
     * Reordenar líneas de un movimiento
     */
    public function reordenarLineas($idMovimiento)
    {
        $detalles = $this->select('id')
                        ->where('id_movimiento', $idMovimiento)
                        ->orderBy('linea', 'ASC')
                        ->findAll();
        
        foreach ($detalles as $index => $detalle) {
            $this->update($detalle['id'], ['linea' => $index + 1]);
        }
        
        return true;
    }
}
