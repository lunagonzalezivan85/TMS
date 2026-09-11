<?php

namespace App\Models;

use CodeIgniter\Model;

class InvComprasEModel extends Model
{
    protected $DBGroup = 'sqlserver';
    protected $table = 'INV_COMPRAS_E';
    protected $primaryKey = 'NUMERO_OCOMPRA';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'CODIGO_INGRESO',
        'NUMERO_OCOMPRA',
        'FECHA',
        'FECHA_ENTREGA',
        'REFERENCIA',
        'NUMERO_FACTURA',
        'NUMERO_POLIZA',
        'NUMERO_EMBARQUE',
        'CODIGO_PROVEEDOR',
        'PROVEEDOR',
        'RUC_PROVEEDOR',
        'CONTACTO',
        'TELEFONOS',
        'CELULAR',
        'DIRECCION',
        'CORREO',
        'SUBTOTAL',
        'PDESCTO',
        'DESCUENTOS',
        'SUB_TOTAL',
        'IMPUESTO',
        'FLETES',
        'OTROS',
        'TOTAL_COMPRA',
        'TASA_CAMBIO',
        'CODIGO_DE_CONDICION',
        'CODIGO_DE_MONEDA',
        'OBSERVACIONES',
        'EXONERADO',
        'NEXONERADO',
        'USUARIO_INGRESO',
        'FECHA_INGRESO',
        'ESTADO'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'FECHA_INGRESO';
    protected $updatedField = '';

    /**
     * Guarda el detalle de una compra ejecutando el procedimiento almacenado sp_Alta_INV_COMPRAS_D
     *
     * @param array $datos Arreglo con los datos del detalle de compra
     * @return bool|array Retorna el resultado de la ejecución o false en caso de error
     */
    public function guardarDetalleCompra($codigoIngreso, array $datos, $numeroOrden = null)
    {
        $db = \Config\Database::connect('sqlserver');
        
        $sql = "EXEC sp_Alta_INV_COMPRAS_D 
            @CODIGO_INGRESO = ?,
            @NUMERO_OCOMPRA = ?,
            @CODIGO_PRODUCTO = ?,
            @CODIGO_UNIDAD = ?,
            @CANTIDAD_SOLICITADA = ?,
            @COSTO_UNITARIO = ?,
            @PORCENTAJE_DESCUENTO = ?,
            @DESCUENTO = ?,
            @COSTO_TOTAL = ?,
            @PORCENTAJE_IVA = ?,
            @CORRELATIVO_DETALLE = ?,
            @DESCRIPCION = ?";
            
        try {
            // Log the SQL and parameters for debugging
            log_message('debug', 'Ejecutando SP sp_Alta_INV_COMPRAS_D con parámetros: ' . json_encode([
                'CODIGO_INGRESO' => $codigoIngreso,
                'NUMERO_OCOMPRA' => $numeroOrden,
                'CODIGO_PRODUCTO' => $datos['codigo_vinculacion'],
                'CANTIDAD' => $datos['cantidad'],
                'PRECIO' => $datos['precio']
            ]));
            
            $query = $db->query($sql, [
                '01',
                $numeroOrden ?? 0, // Default to 0 if not provided
                $datos['codigo_vinculacion'],
                "UNI",
                $datos['cantidad'],
                $datos['precio'],
                0, // PORCENTAJE_DESCUENTO
                0, // DESCUENTO
                $datos['subtotal'],
                $datos['impuesto'],
                $datos['id'],
                $datos['nombre']
            ]);
            
            $result = $query->getResultArray();
            log_message('debug', 'Resultado de sp_Alta_INV_COMPRAS_D: ' . print_r($result, true));
            
            return $result;
            
        } catch (\Exception $e) {
            $error = $db->error();
            log_message('error', 'Error en guardarDetalleCompra: ' . $e->getMessage());
            log_message('error', 'Detalles del error: ' . print_r([
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'db_error' => $error
            ], true));
            
            return false;
        }
    }
    
    // Validation
    protected $validationRules = [
        'CODIGO_INGRESO' => 'required|max_length[10]',
        'NUMERO_OCOMPRA' => 'required|max_length[50]',
        'FECHA' => 'required|valid_date',
        'FECHA_ENTREGA' => 'permit_empty|valid_date',
        'REFERENCIA' => 'permit_empty|max_length[100]',
        'NUMERO_FACTURA' => 'permit_empty|max_length[50]',
        'NUMERO_POLIZA' => 'permit_empty|max_length[50]',
        'NUMERO_EMBARQUE' => 'permit_empty|max_length[50]',
        'CODIGO_PROVEEDOR' => 'required|numeric',
        'PROVEEDOR' => 'required|max_length[255]',
        'RUC_PROVEEDOR' => 'permit_empty|max_length[20]',
        'CONTACTO' => 'permit_empty|max_length[100]',
        'TELEFONOS' => 'permit_empty|max_length[50]',
        'CELULAR' => 'permit_empty|max_length[50]',
        'DIRECCION' => 'permit_empty|max_length[255]',
        'CORREO' => 'permit_empty|valid_email|max_length[100]',
        'SUBTOTAL' => 'permit_empty|decimal',
        'PDESCTO' => 'permit_empty|decimal',
        'DESCUENTOS' => 'permit_empty|decimal',
        'SUB_TOTAL' => 'permit_empty|decimal',
        'IMPUESTO' => 'permit_empty|decimal',
        'FLETES' => 'permit_empty|decimal',
        'OTROS' => 'permit_empty|max_length[255]',
        'TOTAL_COMPRA' => 'permit_empty|decimal',
        'TASA_CAMBIO' => 'permit_empty|decimal',
        'CODIGO_DE_CONDICION' => 'permit_empty|max_length[50]',
        'CODIGO_DE_MONEDA' => 'permit_empty|max_length[10]',
        'OBSERVACIONES' => 'permit_empty|max_length[500]',
        'EXONERADO' => 'permit_empty|decimal',
        'NEXONERADO' => 'permit_empty|max_length[50]',
        'USUARIO_INGRESO' => 'permit_empty|max_length[50]',
        'FECHA_INGRESO' => 'permit_empty|valid_date',
        'ESTADO' => 'permit_empty|max_length[20]'
    ];

    protected $validationMessages = [
        'CODIGO_INGRESO' => [
            'required' => 'El código de ingreso es requerido',
            'max_length' => 'El código de ingreso no puede exceder 10 caracteres'
        ],
        'NUMERO_OCOMPRA' => [
            'required' => 'El número de orden de compra es requerido',
            'max_length' => 'El número de orden no puede exceder 50 caracteres'
        ],
        'FECHA' => [
            'required' => 'La fecha es requerida',
            'valid_date' => 'La fecha debe tener un formato válido'
        ],
        'FECHA_ENTREGA' => [
            'valid_date' => 'La fecha de entrega debe tener un formato válido'
        ],
        'CODIGO_PROVEEDOR' => [
            'required' => 'El código de proveedor es requerido',
            'numeric' => 'El código de proveedor debe ser numérico'
        ],
        'PROVEEDOR' => [
            'required' => 'El nombre del proveedor es requerido',
            'max_length' => 'El nombre del proveedor no puede exceder 255 caracteres'
        ],
        'CORREO' => [
            'valid_email' => 'El correo electrónico debe tener un formato válido',
            'max_length' => 'El correo no puede exceder 100 caracteres'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setCreateFields'];
    protected $beforeUpdate = ['setUpdateFields'];

    /**
     * Establecer campos de creación
     */
    protected function setCreateFields(array $data)
    {
        if (!isset($data['data']['FECHA_INGRESO'])) {
            $data['data']['FECHA_INGRESO'] = date('Y-m-d H:i:s');
        }
        
        if (!isset($data['data']['USUARIO_INGRESO'])) {
            $session = session();
            $data['data']['USUARIO_INGRESO'] = $session->get('usuario') ?? 'SISTEMA';
        }
        
        if (!isset($data['data']['ESTADO'])) {
            $data['data']['ESTADO'] = 'ACTIVO';
        }
        
        return $data;
    }

    /**
     * Establecer campos de actualización
     */
    protected function setUpdateFields(array $data)
    {
        // No hay campos de actualización específicos para esta tabla
        return $data;
    }

    /**
     * Obtener compras con filtros y paginación
     */
    public function getCompras($search = '', $perPage = 10, $page = 1, $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->select('NUMERO_OCOMPRA, FECHA, FECHA_ENTREGA, PROVEEDOR, REFERENCIA, TOTAL_COMPRA, ESTADO')
                        ->orderBy('FECHA', 'DESC');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('NUMERO_OCOMPRA', $search)
                    ->orLike('PROVEEDOR', $search)
                    ->orLike('REFERENCIA', $search)
                    ->orLike('RUC_PROVEEDOR', $search)
                    ->groupEnd();
        }

        if ($fechaInicio && $fechaFin) {
            $builder->where('FECHA >=', $fechaInicio)
                    ->where('FECHA <=', $fechaFin);
        }

        return $builder->paginate($perPage, 'default', $page);
    }

    /**
     * Obtener compra por número de orden
     */
    public function getCompraPorNumero($numeroOrden)
    {
        return $this->where('NUMERO_OCOMPRA', $numeroOrden)->first();
    }

    /**
     * Obtener compras por proveedor
     */
    public function getComprasPorProveedor($codigoProveedor, $limit = 10)
    {
        return $this->select('NUMERO_OCOMPRA, FECHA, REFERENCIA, TOTAL_COMPRA, ESTADO')
                    ->where('CODIGO_PROVEEDOR', $codigoProveedor)
                    ->orderBy('FECHA', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Obtener estadísticas de compras
     */
    public function getEstadisticas($fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->selectSum('TOTAL_COMPRA', 'total_compras')
                        ->selectCount('NUMERO_OCOMPRA', 'cantidad_ordenes');

        if ($fechaInicio && $fechaFin) {
            $builder->where('FECHA >=', $fechaInicio)
                    ->where('FECHA <=', $fechaFin);
        }

        $resultado = $builder->first();

        // Obtener compras por estado
        $builderEstados = $this->select('ESTADO, COUNT(*) as cantidad')
                               ->groupBy('ESTADO');

        if ($fechaInicio && $fechaFin) {
            $builderEstados->where('FECHA >=', $fechaInicio)
                          ->where('FECHA <=', $fechaFin);
        }

        $estados = $builderEstados->findAll();

        return [
            'total_compras' => $resultado['total_compras'] ?? 0,
            'cantidad_ordenes' => $resultado['cantidad_ordenes'] ?? 0,
            'promedio_orden' => $resultado['cantidad_ordenes'] > 0 ? 
                               ($resultado['total_compras'] / $resultado['cantidad_ordenes']) : 0,
            'estados' => $estados
        ];
    }

    /**
     * Generar número de orden único
     */
    public function generarNumeroOrden($prefijo = 'OC')
    {
        $ultimaOrden = $this->select('NUMERO_OCOMPRA')
                           ->like('NUMERO_OCOMPRA', $prefijo . '%')
                           ->orderBy('NUMERO_OCOMPRA', 'DESC')
                           ->first();

        if ($ultimaOrden) {
            // Extraer el número de la última orden
            $numero = (int) substr($ultimaOrden['NUMERO_OCOMPRA'], strlen($prefijo));
            $nuevoNumero = $numero + 1;
        } else {
            $nuevoNumero = 1;
        }

        // Formatear con ceros a la izquierda
        return $prefijo . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verificar si existe una orden de compra
     */
    public function existeOrdenCompra($numeroOrden)
    {
        return $this->where('NUMERO_OCOMPRA', $numeroOrden)->countAllResults() > 0;
    }

    /**
     * Actualizar estado de la orden
     */
    public function actualizarEstado($numeroOrden, $nuevoEstado)
    {
        return $this->where('NUMERO_OCOMPRA', $numeroOrden)
                    ->set('ESTADO', $nuevoEstado)
                    ->update();
    }

    /**
     * Obtener compras pendientes
     */
    public function getComprasPendientes($limit = 20)
    {
        return $this->select('NUMERO_OCOMPRA, FECHA, FECHA_ENTREGA, PROVEEDOR, TOTAL_COMPRA')
                    ->where('ESTADO', 'PENDIENTE')
                    ->orderBy('FECHA_ENTREGA', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Buscar compras por rango de fechas
     */
    public function buscarPorFechas($fechaInicio, $fechaFin, $campo = 'FECHA')
    {
        return $this->where($campo . ' >=', $fechaInicio)
                    ->where($campo . ' <=', $fechaFin)
                    ->orderBy($campo, 'DESC')
                    ->findAll();
    }
}
