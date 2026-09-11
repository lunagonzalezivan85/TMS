<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimientosModel extends Model
{
    protected $table = 'movimientos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'tipo_movimiento',
        'codigo',
        'referencia',
        'monto',
        'fecha_registro',
        'fecha_actualizacion',
        'fecha_movimiento',
        'estado',
        'usuario_crea',
        'usuario_edita',
        'usuario_aprueba',
        'id_empresa',
        'codigo_origen',
        'codigo_destino'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualizacion';
    protected $skipValidation = false;
    
    // Validaciones
    protected $validationRules = [
        'tipo_movimiento' => 'required|max_length[100]',
        'codigo' => 'required|max_length[100]',
        'fecha_movimiento' => 'required|valid_date',
        'estado' => 'required|integer',
        'usuario_crea' => 'required|max_length[100]',
        'id_empresa' => 'required|integer'
    ];

    protected $validationMessages = [
        'tipo_movimiento' => [
            'required' => 'El tipo de movimiento es requerido',
            'max_length' => 'El tipo de movimiento no puede exceder 100 caracteres'
        ],
        'codigo' => [
            'required' => 'El código es requerido',
            'max_length' => 'El código no puede exceder 100 caracteres'
        ],
        'fecha_movimiento' => [
            'required' => 'La fecha del movimiento es requerida',
            'valid_date' => 'La fecha del movimiento debe ser válida'
        ],
        'estado' => [
            'required' => 'El estado es requerido',
            'integer' => 'El estado debe ser un número válido'
        ],
        'usuario_crea' => [
            'required' => 'El usuario creador es requerido'
        ],
        'id_empresa' => [
            'required' => 'La empresa es requerida',
            'integer' => 'La empresa debe ser un número válido'
        ]
    ];

    // Estados de movimientos
    const ESTADO_BORRADOR = 0;
    const ESTADO_PENDIENTE = 1;
    const ESTADO_APROBADO = 2;
    const ESTADO_PROCESADO = 3;
    const ESTADO_CANCELADO = 4;

    // Tipos de movimientos
    const TIPO_ENTRADA = 'ENTRADA';
    const TIPO_SALIDA = 'SALIDA';
    const TIPO_TRANSFERENCIA = 'TRANSFERENCIA';
    const TIPO_AJUSTE_POSITIVO = 'AJUSTE_POSITIVO';
    const TIPO_AJUSTE_NEGATIVO = 'AJUSTE_NEGATIVO';

    /**
     * Obtener movimientos por empresa con paginación
     */
    public function getMovimientosPorEmpresa($idEmpresa, $limite = 20, $offset = 0, $filtros = [])
    {
        $builder = $this->db->table('movimientos m')
            ->select('m.*, 
                    COUNT(dm.id) as total_items,
                    SUM(dm.total_linea) as monto_calculado')
            ->join('detalle_movimiento dm', 'dm.id_movimiento = m.id', 'left')
            ->where('m.id_empresa', $idEmpresa)
            ->groupBy('m.id');
        
        // Aplicar filtros
        if (!empty($filtros['tipo_movimiento'])) {
            $builder->where('m.tipo_movimiento', $filtros['tipo_movimiento']);
        }
        
        if (!empty($filtros['estado'])) {
            $builder->where('m.estado', $filtros['estado']);
        }
        
        if (!empty($filtros['fecha_desde'])) {
            $builder->where('m.fecha_movimiento >=', $filtros['fecha_desde']);
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('m.fecha_movimiento <=', $filtros['fecha_hasta']);
        }
        
        if (!empty($filtros['busqueda'])) {
            $builder->groupStart()
                   ->like('m.codigo', $filtros['busqueda'])
                   ->orLike('m.referencia', $filtros['busqueda'])
                   ->orLike('m.codigo_origen', $filtros['busqueda'])
                   ->orLike('m.codigo_destino', $filtros['busqueda'])
                   ->groupEnd();
        }
        
        return $builder->orderBy('m.fecha_registro', 'DESC')
                      ->limit($limite, $offset)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Obtener movimiento por orden de trabajo
     */
    public function getMovimientosByOrdenesTrabajos($orden)
    {
        try {
            $query = $this->db->query("SELECT * FROM movimientos WHERE referencia = ? and estado=2", [$orden]);
            return $query->getRowArray();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener movimiento por orden de trabajo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Contar movimientos por empresa
     */
    public function contarMovimientosPorEmpresa($idEmpresa, $filtros = [])
    {
        $builder = $this->where('id_empresa', $idEmpresa);
        
        // Aplicar los mismos filtros que en getMovimientosPorEmpresa
        if (!empty($filtros['tipo_movimiento'])) {
            $builder->where('tipo_movimiento', $filtros['tipo_movimiento']);
        }
        
        if (!empty($filtros['estado'])) {
            $builder->where('estado', $filtros['estado']);
        }
        
        if (!empty($filtros['fecha_desde'])) {
            $builder->where('fecha_movimiento >=', $filtros['fecha_desde']);
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('fecha_movimiento <=', $filtros['fecha_hasta']);
        }
        
        if (!empty($filtros['busqueda'])) {
            $builder->groupStart()
                   ->like('codigo', $filtros['busqueda'])
                   ->orLike('referencia', $filtros['busqueda'])
                   ->orLike('codigo_origen', $filtros['busqueda'])
                   ->orLike('codigo_destino', $filtros['busqueda'])
                   ->groupEnd();
        }
        
        return $builder->countAllResults();
    }

    /**
     * Obtener movimiento con detalles
     */
    public function getMovimientoConDetalles($idMovimiento, $idEmpresa = null)
    {
        $builder = $this->db->table('movimientos m')
            ->select('m.*')
            ->where('m.id', $idMovimiento);
        
        if ($idEmpresa) {
            $builder->where('m.id_empresa', $idEmpresa);
        }
        
        $movimiento = $builder->get()->getRowArray();
        
        if ($movimiento) {
            // Obtener detalles del movimiento
            $detalles = $this->db->table('detalle_movimiento dm')
                ->select('dm.*, mat.nombre as material_nombre, mat.unidad_medida, mat.codigo_consecutivo, mat.codigo_vinculacion')
                ->join('materiales mat', 'mat.id = dm.id_material')
                ->where('dm.id_movimiento', $idMovimiento)
                ->orderBy('dm.linea', 'ASC')
                ->get()
                ->getResultArray();
            
            $movimiento['detalles'] = $detalles;
        }
        
        return $movimiento;
    }


    public function getDetalleMaterialesPorSolicitud ($codigo,$idEmpresa=false)
    {
        $builder = $this->db->table('movimientos m')
            ->select('m.*')
            ->where('m.referencia', $codigo);
           
        
        if ($idEmpresa) {
            $builder->where('m.id_empresa', $idEmpresa);
        }
        
        $movimiento = $builder->get()->getRowArray();
        
        if ($movimiento) {
            // Obtener detalles del movimiento
            $detalles = $this->db->table('detalle_movimiento dm')
                ->select('dm.*, mat.nombre as material_nombre, mat.unidad_medida, mat.codigo_consecutivo, mat.codigo_vinculacion')
                ->join('materiales mat', 'mat.id = dm.id_material')
                ->where('dm.id_movimiento', $movimiento['id'])
                ->orderBy('dm.linea', 'ASC')
                ->get()
                ->getResultArray();
            
            $movimiento['detalles'] = $detalles;
        }
        
        return $movimiento;
    }

    /**
     * Crear movimiento con detalles (transacción)
     */
    public function crearMovimientoConDetalles($datosMovimiento, $detalles)
    {
        $this->db->transStart();
        
        try {
            // Generar código si no se proporciona
            if (empty($datosMovimiento['codigo'])) {
                $datosMovimiento['codigo'] = $this->generarCodigoMovimiento($datosMovimiento['tipo_movimiento'], $datosMovimiento['id_empresa']);
            }
            
            // Establecer fecha de registro
            $datosMovimiento['fecha_registro'] = date('Y-m-d H:i:s');
            
            // Insertar movimiento
            $idMovimiento = $this->insert($datosMovimiento);
            
            if (!$idMovimiento) {
                throw new \Exception('Error al crear el movimiento');
            }
            
            // Insertar detalles
            $detalleModel = new DetalleMovimientoModel();
            $montoTotal = 0;
            
            foreach ($detalles as $index => $detalle) {
                $detalle['id_movimiento'] = $idMovimiento;
                $detalle['linea'] = $index + 1;
                $detalle['id_empresa'] = $datosMovimiento['id_empresa'];
                $detalle['usuario_crea'] = $datosMovimiento['usuario_crea'];
                $detalle['fecha_registro'] = date('Y-m-d H:i:s');
                
                // Calcular totales de línea
                $subtotal = $detalle['cantidad'] * $detalle['precio'];
                $impuestoLinea = $subtotal * ($detalle['impuesto'] / 100);
                $totalLinea = $subtotal + $impuestoLinea;
                
                $detalle['subtotal'] = $subtotal;
                $detalle['total_linea'] = $totalLinea;
                
                $montoTotal += $totalLinea;
                
                if (!$detalleModel->insert($detalle)) {
                    throw new \Exception('Error al insertar detalle en línea ' . ($index + 1));
                }
            }
            
            // Actualizar monto total del movimiento
            $this->update($idMovimiento, ['monto' => $montoTotal]);
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }
            
            return [
                'success' => true,
                'id_movimiento' => $idMovimiento,
                'monto_total' => $montoTotal,
                'message' => 'Movimiento creado exitosamente'
            ];
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return [
                'success' => false,
                'message' => 'Error al crear movimiento: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Actualizar movimiento con detalles
     */
    public function actualizarMovimientoConDetalles($idMovimiento, $datosMovimiento, $detalles)
    {
        $this->db->transStart();
        
        try {
            // Actualizar movimiento
            $datosMovimiento['fecha_actualizacion'] = date('Y-m-d H:i:s');
            
            if (!$this->update($idMovimiento, $datosMovimiento)) {
                throw new \Exception('Error al actualizar el movimiento');
            }
            
            // Eliminar detalles existentes
            $this->db->table('detalle_movimiento')->where('id_movimiento', $idMovimiento)->delete();
            
            // Insertar nuevos detalles
            $detalleModel = new DetalleMovimientoModel();
            $montoTotal = 0;
            
            foreach ($detalles as $index => $detalle) {
                $detalle['id_movimiento'] = $idMovimiento;
                $detalle['linea'] = $index + 1;
                $detalle['id_empresa'] = $datosMovimiento['id_empresa'];
                $detalle['usuario_edita'] = $datosMovimiento['usuario_edita'];
                $detalle['fecha_actualiza'] = date('Y-m-d H:i:s');
                
                // Calcular totales de línea
                $subtotal = $detalle['cantidad'] * $detalle['precio'];
                $impuestoLinea = $subtotal * ($detalle['impuesto'] / 100);
                $totalLinea = $subtotal + $impuestoLinea;
                
                $detalle['subtotal'] = $subtotal;
                $detalle['total_linea'] = $totalLinea;
                
                $montoTotal += $totalLinea;
                
                if (!$detalleModel->insert($detalle)) {
                    throw new \Exception('Error al insertar detalle en línea ' . ($index + 1));
                }
            }
            
            // Actualizar monto total
            $this->update($idMovimiento, ['monto' => $montoTotal]);
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }
            
            return [
                'success' => true,
                'monto_total' => $montoTotal,
                'message' => 'Movimiento actualizado exitosamente'
            ];
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return [
                'success' => false,
                'message' => 'Error al actualizar movimiento: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cambiar estado del movimiento
     */
    public function cambiarEstado($idMovimiento, $nuevoEstado, $usuarioAprueba = null)
    {
        $datos = [
            'estado' => $nuevoEstado,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ];
        
        if ($nuevoEstado == self::ESTADO_APROBADO && $usuarioAprueba) {
            $datos['usuario_aprueba'] = $usuarioAprueba;
        }
        
        return $this->update($idMovimiento, $datos);
    }

    /**
     * Generar código de movimiento
     */
    public function generarCodigoMovimiento($tipoMovimiento, $idEmpresa)
    {
        $prefijo = $this->getPrefijoPorTipo($tipoMovimiento);
        $anioActual = date('Y');
        
        // Buscar el último número consecutivo para el tipo y año actual
        $ultimoCodigo = $this->select('codigo')
                           ->where('id_empresa', $idEmpresa)
                           ->where('tipo_movimiento', $tipoMovimiento)
                           ->where('codigo LIKE', "{$prefijo}-{$anioActual}%")
                           ->orderBy('codigo', 'DESC')
                           ->first();
        
        $siguienteNumero = 1;
        
        if ($ultimoCodigo) {
            // Extraer el número del último código
            $numeroActual = (int) substr($ultimoCodigo['codigo'], -6);
            $siguienteNumero = $numeroActual + 1;
        }
        
        // Generar código con formato PREF-YYYY000001
        return sprintf('%s-%s%06d', $prefijo, $anioActual, $siguienteNumero);
    }

    /**
     * Obtener prefijo por tipo de movimiento
     */
    private function getPrefijoPorTipo($tipoMovimiento)
    {
        $prefijos = [
            self::TIPO_ENTRADA => 'ENT',
            self::TIPO_SALIDA => 'SAL',
            self::TIPO_TRANSFERENCIA => 'TRF',
            self::TIPO_AJUSTE_POSITIVO => 'AJP',
            self::TIPO_AJUSTE_NEGATIVO => 'AJN'
        ];
        
        return $prefijos[$tipoMovimiento] ?? 'MOV';
    }

    /**
     * Obtener estadísticas de movimientos
     */
    public function getEstadisticasMovimientos($idEmpresa, $fechaDesde = null, $fechaHasta = null)
    {
        $builder = $this->db->table('movimientos')
            ->where('id_empresa', $idEmpresa);
        
        if ($fechaDesde) {
            $builder->where('fecha_movimiento >=', $fechaDesde);
        }
        
        if ($fechaHasta) {
            $builder->where('fecha_movimiento <=', $fechaHasta);
        }
        
        $estadisticas = $builder->select('
            COUNT(*) as total_movimientos,
            SUM(CASE WHEN tipo_movimiento = "ENTRADA" THEN monto ELSE 0 END) as total_entradas,
            SUM(CASE WHEN tipo_movimiento = "SALIDA" THEN monto ELSE 0 END) as total_salidas,
            COUNT(CASE WHEN estado = ' . self::ESTADO_PENDIENTE . ' THEN 1 END) as pendientes,
            COUNT(CASE WHEN estado = ' . self::ESTADO_APROBADO . ' THEN 1 END) as aprobados,
            COUNT(CASE WHEN estado = ' . self::ESTADO_PROCESADO . ' THEN 1 END) as procesados
        ')->get()->getRowArray();
        
        return $estadisticas;
    }

    /**
     * Verificar si se puede eliminar un movimiento
     */
    public function puedeEliminar($idMovimiento)
    {
        $movimiento = $this->find($idMovimiento);
        
        if (!$movimiento) {
            return false;
        }
        
        // Solo se pueden eliminar movimientos en estado borrador o pendiente
        return in_array($movimiento['estado'], [self::ESTADO_BORRADOR, self::ESTADO_PENDIENTE]);
    }

    /**
     * Eliminar movimiento con detalles
     */
    public function eliminarMovimientoConDetalles($idMovimiento)
    {
        if (!$this->puedeEliminar($idMovimiento)) {
            return [
                'success' => false,
                'message' => 'No se puede eliminar un movimiento que ya ha sido procesado o aprobado'
            ];
        }
        
        $this->db->transStart();
        
        try {
            // Eliminar detalles
            $this->db->table('detalle_movimiento')->where('id_movimiento', $idMovimiento)->delete();
            
            // Eliminar movimiento
            $this->delete($idMovimiento);
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }
            
            return [
                'success' => true,
                'message' => 'Movimiento eliminado exitosamente'
            ];
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return [
                'success' => false,
                'message' => 'Error al eliminar movimiento: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtener tipos de movimiento disponibles
     */
    public function getTiposMovimiento()
    {
        return [
            self::TIPO_ENTRADA => 'Entrada de Inventario',
            self::TIPO_SALIDA => 'Salida de Inventario',
            self::TIPO_TRANSFERENCIA => 'Transferencia entre Ubicaciones',
            self::TIPO_AJUSTE_POSITIVO => 'Ajuste Positivo',
            self::TIPO_AJUSTE_NEGATIVO => 'Ajuste Negativo'
        ];
    }

    /**
     * Obtener estados disponibles
     */
    public function getEstados()
    {
        return [
            self::ESTADO_BORRADOR => 'Borrador',
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_APROBADO => 'Aprobado',
            self::ESTADO_PROCESADO => 'Procesado',
            self::ESTADO_CANCELADO => 'Cancelado'
        ];
    }
}
