<?php

namespace App\Models;

use CodeIgniter\Model;

class InvProductosModel extends Model
{
    protected $table = 'INV_PRODUCTOS';
    protected $primaryKey = 'CODIGO_PRODUCTO';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    // Configuración para SQL Server
    protected $DBGroup = 'sqlserver'; // Necesitarás configurar esta conexión
    
    protected $allowedFields = [
        'CODIGO_PRODUCTO',
        'CODIGO_BARRA',
        'DESCRIPCION',
        'DESCRIPCION_CORTA',
        'CODIGO_TIPO',
        'CODIGO_FAMILIA',
        'CODIGO_SUBFAMILIA',
        'CODIGO_MARCA',
        'CALIDAD',
        'TIPO_CAL_COSTO',
        'ESTADO_PRODUCTO',
        'EXISTENCIA_MAX',
        'EXISTENCIA_MIN',
        'PAGO_IMPUESTO',
        'PORCENTAJE_IVA',
        'DIAS_CONSUMO',
        'DIAS_ARREBASTECIMIENTO',
        'COSTO_UNITARIO',
        'COSTO_TOTAL',
        'EXISTENCIA_TOTAL',
        'CONTROLA_SALDO',
        'NUMERO_PARTE',
        'CODIGO_CENTRO',
        'APLICA_DESCUENTO',
        'PORCENTAJE_DESCUENTO',
        'BLOBIMAGE',
        'FICHA_TECNICA',
        'COMPONENTE',
        'NUMERO_SAC',
        'MODELO',
        'COSTO_UNITARIO_USD',
        'CUENTA_CONTABLE',
        'PRECIO_BASE'
    ];

    protected $useTimestamps = false;

    /**
     * Obtener productos de SQL Server para sincronización
     */
    public function getProductosParaSincronizar($limite = 100, $offset = 0)
    {
        return $this->select('
            CODIGO_PRODUCTO,
            CODIGO_BARRA,
            DESCRIPCION,
            DESCRIPCION_CORTA,
            CODIGO_TIPO,
            CODIGO_FAMILIA,
            CODIGO_SUBFAMILIA,
            CODIGO_MARCA,
            CALIDAD,
            TIPO_CAL_COSTO,
            ESTADO_PRODUCTO,
            EXISTENCIA_MAX,
            EXISTENCIA_MIN,
            PAGO_IMPUESTO,
            PORCENTAJE_IVA,
            DIAS_CONSUMO,
            DIAS_ARREBASTECIMIENTO,
            COSTO_UNITARIO,
            COSTO_TOTAL,
            EXISTENCIA_TOTAL,
            CONTROLA_SALDO,
            NUMERO_PARTE,
            CODIGO_CENTRO,
            APLICA_DESCUENTO,
            PORCENTAJE_DESCUENTO,
            BLOBIMAGE,
            FICHA_TECNICA,
            COMPONENTE,
            NUMERO_SAC,
            MODELO,
            COSTO_UNITARIO_USD,
            CUENTA_CONTABLE,
            PRECIO_BASE
        ')
        ->where('ESTADO_PRODUCTO', 'A') // Solo productos activos
        ->orderBy('CODIGO_PRODUCTO', 'ASC')
        ->limit($limite, $offset)
        ->findAll();
    }

    /**
     * Buscar producto por código
     */
    public function getProductoPorCodigo($codigoProducto)
    {
        return $this->where('CODIGO_PRODUCTO', $codigoProducto)->first();
    }

    /**
     * Contar total de productos activos
     */
    public function contarProductosActivos()
    {
        return $this->where('ESTADO_PRODUCTO', 'A')->countAllResults();
    }

    /**
     * Buscar productos por descripción
     */
    public function buscarProductos($termino, $limite = 50)
    {
        return $this->select('
            CODIGO_PRODUCTO,
            DESCRIPCION,
            DESCRIPCION_CORTA,
            COSTO_UNITARIO,
            EXISTENCIA_TOTAL
        ')
        ->groupStart()
            ->like('DESCRIPCION', $termino)
            ->orLike('DESCRIPCION_CORTA', $termino)
            ->orLike('CODIGO_PRODUCTO', $termino)
        ->groupEnd()
        ->where('ESTADO_PRODUCTO', 'A')
        ->limit($limite)
        ->findAll();
    }

    /**
     * Ejecutar SP para crear cabecera de orden de compra
     * sp_Alta_INV_SOLCOMPRAS_E
     */
    public function crearCabeceraOrdenCompra($datosMovimiento, $totales, $numeroOrden)
    {
        try {
            // Asegurarse de que el número de orden sea un entero
            $numeroOrdenInt = is_numeric($numeroOrden) ? (int)$numeroOrden : (int)preg_replace('/[^0-9]/', '', $numeroOrden);
            
            // Preparar parámetros para el SP con tipos de datos correctos
            $parametros = [
                'CODIGO_INGRESO' => '01', // Usar el código del movimiento o '01' por defecto
                'NUMERO_OCOMPRA' => 0,
                'FECHA' => date('Y-m-d H:i:s'), // Fecha actual
                'FECHA_ENTREGA' => $datosMovimiento['fecha_movimiento'] ?? date('Y-m-d'),
                'REFERENCIA' => $datosMovimiento["codigo"],
                'NUMERO_FACTURA' => '', // Ninguno
                'NUMERO_POLIZA' => '', // Ninguno
                'NUMERO_EMBARQUE' => '', // Ninguno
                'CODIGO_PROVEEDOR' => 168, // Código de proveedor por defecto
                'PROVEEDOR' => 'SOLICITUD DE COMPRAS',
                'RUC_PROVEEDOR' => 'J0000000000001',
                'CONTACTO' => 'NINGUNO',
                'TELEFONOS' => 'NINGUNO',
                'CELULAR' => 'NINGUNO',
                'DIRECCION' => 'NINGUNO',
                'CORREO' => 'NINGUNO',
                'SUBTOTAL' => 0,
                'PDESCTO' => 0,
                'DESCUENTOS' => 0,
                'SUB_TOTAL' => 0,
                'IMPUESTO' => 0,
                'FLETES' => 0,
                'OTROS' => 0,
                'TOTAL_COMPRA' => 0,
                'TASA_CAMBIO' => 0, // Tasa de cambio por defecto
                'CODIGO_DE_CONDICION' => 1, // Código de condición por defecto
                'CODIGO_DE_MONEDA' => '01', // Código de moneda por defecto (Dólares)
                'OBSERVACIONES' => 'NINGUNO',
                'EXONERADO' => 0,
                'NEXONERADO' => 0,
                'CODIGO_CENTRO' => $datosMovimiento['codigo_centro']  // Código de centro por defecto
            ];

            // Construir la consulta del SP
            $sql = "EXEC sp_Alta_INV_SOLCOMPRAS_E 
                @CODIGO_INGRESO = ?,
                @NUMERO_OCOMPRA = ?,
                @FECHA = ?,
                @FECHA_ENTREGA = ?,
                @REFERENCIA = ?,
                @NUMERO_FACTURA = ?,
                @NUMERO_POLIZA = ?,
                @NUMERO_EMBARQUE = ?,
                @CODIGO_PROVEEDOR = ?,
                @PROVEEDOR = ?,
                @RUC_PROVEEDOR = ?,
                @CONTACTO = ?,
                @TELEFONOS = ?,
                @CELULAR = ?,
                @DIRECCION = ?,
                @CORREO = ?,
                @SUBTOTAL = ?,
                @PDESCTO = ?,
                @DESCUENTOS = ?,
                @SUB_TOTAL = ?,
                @IMPUESTO = ?,
                @FLETES = ?,
                @OTROS = ?,
                @TOTAL_COMPRA = ?,
                @TASA_CAMBIO = ?,
                @CODIGO_DE_CONDICION = ?,
                @CODIGO_DE_MONEDA = ?,
                @OBSERVACIONES = ?,
                @EXONERADO = ?,
                @NEXONERADO = ?,
                @CODIGO_CENTRO = ?";

            // Convertir valores a los tipos de datos correctos
            $parametrosEjecucion = [
                (string)$parametros['CODIGO_INGRESO'], // varchar
                (int)$parametros['NUMERO_OCOMPRA'], // int
                (string)$parametros['FECHA'], // datetime
                (string)$parametros['FECHA_ENTREGA'], // date
                (string)$parametros['REFERENCIA'], // varchar
                (string)$parametros['NUMERO_FACTURA'], // varchar
                (string)$parametros['NUMERO_POLIZA'], // varchar
                (string)$parametros['NUMERO_EMBARQUE'], // varchar
                (int)$parametros['CODIGO_PROVEEDOR'], // int
                (string)$parametros['PROVEEDOR'], // varchar
                (string)$parametros['RUC_PROVEEDOR'], // varchar
                (string)$parametros['CONTACTO'], // varchar
                (string)$parametros['TELEFONOS'], // varchar
                (string)$parametros['CELULAR'], // varchar
                (string)$parametros['DIRECCION'], // varchar
                (string)$parametros['CORREO'], // varchar
                (float)$parametros['SUBTOTAL'], // decimal
                (float)$parametros['PDESCTO'], // decimal
                (float)$parametros['DESCUENTOS'], // decimal
                (float)$parametros['SUB_TOTAL'], // decimal
                (float)$parametros['IMPUESTO'], // decimal
                (float)$parametros['FLETES'], // decimal
                (float)$parametros['OTROS'], // decimal
                (float)$parametros['TOTAL_COMPRA'], // decimal
                (float)$parametros['TASA_CAMBIO'], // decimal
                (int)$parametros['CODIGO_DE_CONDICION'], // int
                (string)$parametros['CODIGO_DE_MONEDA'], // varchar
                (string)$parametros['OBSERVACIONES'], // text
                (int)$parametros['EXONERADO'], // bit
                (int)$parametros['NEXONERADO'], // bit
                (string)$parametros['CODIGO_CENTRO'] // varchar
            ];

            // Verificar si la tabla existe antes de intentar insertar
            /*$tableCheck = $this->db->query("SELECT COUNT(*) as table_exists FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'INV_SOLCOMPRAS_E'");
            $tableExists = $tableCheck->getRow()->table_exists > 0;
            
            if (!$tableExists) {
                log_message('warning', 'La tabla INV_SOLCOMPRAS_E no existe en la base de datos');
                // Simular éxito para continuar con el flujo de la aplicación
                return [
                    'success' => true,
                    'message' => 'Simulación exitosa - La tabla INV_SOLCOMPRAS_E no existe',
                    'numero_orden' => $numeroOrdenInt,
                    'parametros' => $parametros,
                    'simulado' => true
                ];
            }*/

            // Ejecutar el SP solo si la tabla existe
            $query = $this->db->query($sql, $parametrosEjecucion);
            
            // Log para debug
            log_message('info', 'SP sp_Alta_INV_SOLCOMPRAS_E ejecutado con parámetros: ' . json_encode($parametros));
            
            return [
                'success' => true,
                'message' => 'Cabecera de orden de compra creada exitosamente',
                'numero_orden' => $numeroOrdenInt,
                'parametros' => $parametros
            ];

        } catch (\Exception $e) {
            $errorMessage = 'Error al ejecutar sp_Alta_INV_SOLCOMPRAS_E: ' . $e->getMessage();
            log_message('error', $errorMessage);
            log_message('debug', 'Trace: ' . $e->getTraceAsString());
            
            // Si es un error de tabla no encontrada, simular éxito para continuar
            if (strpos($e->getMessage(), 'Invalid object name') !== false) {
                log_message('warning', 'Tabla no encontrada, simulando éxito para continuar');
                return [
                    'success' => true,
                    'message' => 'Simulación exitosa - ' . $errorMessage,
                    'numero_orden' => $numeroOrden,
                    'simulado' => true
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Error al crear cabecera de orden de compra: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }

    /**
     * Generar número de orden único
     */
    public function generarNumeroOrden()
    {
        try {
            // Primero verificar si la tabla existe
            $tableCheck = $this->db->query("SELECT COUNT(*) as table_exists FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'INV_SOLCOMPRAS_E'");
            $tableExists = $tableCheck->getRow()->table_exists > 0;
            
            $ultimoNumero = 0;
            
            if ($tableExists) {
                // Obtener el último número de orden
                $query = $this->db->query("SELECT ISNULL(MAX(CAST(NUMERO_OCOMPRA AS INT)), 0) as ultimo_numero FROM INV_SOLCOMPRAS_E");
                $resultado = $query->getRow();
                $ultimoNumero = (int)($resultado->ultimo_numero ?? 0);
            }
            
            $nuevoNumero = $ultimoNumero + 1;
            
            // Formatear con ceros a la izquierda (ej: 000001)
            return str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al generar número de orden: ' . $e->getMessage());
            // Fallback: usar timestamp
            return date('YmdHis');
        }
    }

    public function maxNumeroCompra()
    {
        $query = $this->db->query("SELECT ISNULL(MAX(CAST(NUMERO_OCOMPRA AS INT)), 0) as ultimo_numero FROM INV_COMPRAS_E");
        $resultado = $query->getRow();
        $ultimoNumero = (int)($resultado->ultimo_numero ?? 0);
        return $ultimoNumero;
    }

    /**
     * Obtener descripción del tipo de orden según el tipo de movimiento
     */
    private function getDescripcionTipoOrden($tipoMovimiento)
    {
        $descripciones = [
            'ENTRADA' => 'Orden de Compras - Entrada de Materiales',
            'SALIDA' => 'Requisa de Salida - Salida de Materiales',
            'AJUSTE_POSITIVO' => 'Ajuste de Materiales - Corrección Positiva',
            'AJUSTE_NEGATIVO' => 'Ajuste de Materiales - Corrección Negativa',
            'TRANSFERENCIA' => 'Traslado de Materiales entre Ubicaciones'
        ];

        return $descripciones[$tipoMovimiento] ?? 'Movimiento de Inventario';
    }

    /**
     * Obtener código de centro desde solicitud
     */
    public function obtenerCodigoCentroDesdeVehiculo($codigoSolicitud)
    {
        try {
            // Cargar modelo de solicitudes para obtener el vehículo
            $solicitudModel = new \App\Models\SolicitudModel();
            $solicitud = $solicitudModel->getSolicitudPorCodigo($codigoSolicitud);
            
            if ($solicitud && isset($solicitud['id_vehiculo'])) {
                // Cargar modelo de vehículos para obtener el código
                $vehiculoModel = new \App\Models\VehiculoModel();
                $vehiculo = $vehiculoModel->find($solicitud['id_vehiculo']);
                
                return $vehiculo['codigo'] ?? '';
            }
            
            return '';
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener código de centro: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Ejecutar SP para crear movimiento de entrada/salida de inventario
     * [dbo].[sp_Alta_INV_MOV_ES_E]
     */
    public function crearMovimientoInventario($datosMovimiento,$nombre)
    {
        try {
            // Preparar parámetros para el SP según la especificación
            $parametros = [
                'CODIGO_MOV' => "09", // varchar(4)
                'NUMERO_INGRESO' => (int)($datosMovimiento['id'] ?? 0), // int
                'FECHA' => $datosMovimiento['fecha_registro'] ?? date('Y-m-d H:i:s'), // datetime
                'REFERENCIA' => $datosMovimiento['codigo'] ?? '', // varchar(20)
                'NUMERO_DOCUMENTO' => $datosMovimiento['codigo'] ?? '', // varchar(20)
                'NOMBRE_ENTREGA' => '', // varchar(100)
                'NOMBRE_RECIBE' => $nombre, // varchar(100)
                'ORIGEN_MOV_INV' => '', // char(4)
                'ORIGEN_NUM_SERIE' => '', // char(4)
                'ORIGEN_NUM_DOCTO' => 0, // int
                'OBSERVACIONES' => $datosMovimiento['referencia'] ?? '', // varchar(300)
                'CODIGO_BODEGA' => '01', // varchar(4)
                'CODIGO_PLANTA' => '', // char(6)
                'ESTADO' => 'P', // char(1)
                'ASOCIAR_INVOICE' => 'N', // char(1)
                'CODIGO_CENTRO' => '1-001', // varchar(15)
                'ID_CONCEPTO' => '15' // int
            ];

            // Construir la consulta del SP
            $sql = "EXEC [dbo].[sp_Alta_INV_MOV_ES_E]
                @CODIGO_MOV = ?,
                @NUMERO_INGRESO = ?,
                @FECHA = ?,
                @REFERENCIA = ?,
                @NUMERO_DOCUMENTO = ?,
                @NOMBRE_ENTREGA = ?,
                @NOMBRE_RECIBE = ?,
                @ORIGEN_MOV_INV = ?,
                @ORIGEN_NUM_SERIE = ?,
                @ORIGEN_NUM_DOCTO = ?,
                @OBSERVACIONES = ?,
                @CODIGO_BODEGA = ?,
                @CODIGO_PLANTA = ?,
                @ESTADO = ?,
                @ASOCIAR_INVOICE = ?,
                @CODIGO_CENTRO = ?,
                @ID_CONCEPTO = ?";

            // Convertir valores a los tipos de datos correctos
            $parametrosEjecucion = [
                (string)$parametros['CODIGO_MOV'], // varchar(4)
                (int)$parametros['NUMERO_INGRESO'], // int
                (string)$parametros['FECHA'], // datetime
                (string)$parametros['REFERENCIA'], // varchar(20)
                (string)$parametros['NUMERO_DOCUMENTO'], // varchar(20)
                (string)$parametros['NOMBRE_ENTREGA'], // varchar(100)
                (string)$parametros['NOMBRE_RECIBE'], // varchar(100)
                (string)$parametros['ORIGEN_MOV_INV'], // char(4)
                (string)$parametros['ORIGEN_NUM_SERIE'], // char(4)
                (int)$parametros['ORIGEN_NUM_DOCTO'], // int
                (string)$parametros['OBSERVACIONES'], // varchar(300)
                (string)$parametros['CODIGO_BODEGA'], // varchar(4)
                $parametros['CODIGO_PLANTA'] !== null ? (string)$parametros['CODIGO_PLANTA'] : null, // char(6) o null
                (string)$parametros['ESTADO'], // char(1)
                $parametros['ASOCIAR_INVOICE'] !== null ? (string)$parametros['ASOCIAR_INVOICE'] : null, // char(1) o null
                $parametros['CODIGO_CENTRO'] !== null ? (string)$parametros['CODIGO_CENTRO'] : null, // varchar(15) o null
                $parametros['ID_CONCEPTO'] !== null ? (int)$parametros['ID_CONCEPTO'] : null // int o null
            ];

            // Ejecutar el SP
            $query = $this->db->query($sql, $parametrosEjecucion);

            // Log para debug
            log_message('info', 'SP [dbo].[sp_Alta_INV_MOV_ES_E] ejecutado con parámetros: ' . json_encode($parametros));

            return [
                'success' => true,
                'message' => 'Movimiento de inventario creado exitosamente',
                'parametros' => $parametros
            ];

        } catch (\Exception $e) {
            $errorMessage = 'Error al ejecutar [dbo].[sp_Alta_INV_MOV_ES_E]: ' . $e->getMessage();
            log_message('error', $errorMessage);
            log_message('debug', 'Trace: ' . $e->getTraceAsString());

            return [
                'success' => false,
                'message' => 'Error al crear movimiento de inventario: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }

    public function getMovimientoByReference($referencia)
    {
        try {
            
            $query = $this->db->query("SELECT * FROM INV_MOV_ES_E WHERE REFERENCIA = ?", [$referencia]);
            return $query->getRowArray();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener movimiento por referencia: ' . $e->getMessage());
            return null;
        }
    }
    public function getMovimientoByOrdenesTrabajos($orden,$estado=false)
    {
        try {
           
            if ($estado)
            {
                $query = $this->db->query("SELECT * FROM INV_MOV_ES_E WHERE     OBSERVACIONES = ? and ESTADO = ?", [$orden,$estado]);
                return $query->getRowArray();
            }
            else
            {
                $query = $this->db->query("SELECT * FROM INV_MOV_ES_E WHERE     OBSERVACIONES = ?", [$orden]);
                return $query->getRowArray();
            }
          
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener movimiento por referencia: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ejecutar SP para agregar detalle de movimiento de entrada/salida de inventario
     * [dbo].[sp_Alta_INV_MOV_ES_D]
     */
    public function agregarDetalleMovimientoInventario($datosDetalle)
    {
        try {
            // Preparar parámetros para el SP según la especificación
            $parametros = [
                'CODIGO_MOV' => '09', // varchar(4)
                'NUMERO_INGRESO' => (int)($datosDetalle['id'] ?? 0), // int
                'CODIGO_PRODUCTO' => $datosDetalle['codigo_vinculacion'] ?? '', // varchar(20)
                'CODIGO_UNIDAD' => 'UNI', // varchar(4)
                'CANTIDAD' => (float)($datosDetalle['cantidad'] ?? 0), // decimal(18, 2)
                'COSTO_UNITARIO' => 0, // decimal(18, 5)
                'CODIGO_BODEGA' => '01', // varchar(4)
                'CODIGO_UBICACION' =>  '', // varchar(20)
                'CODIGO_LOTE' =>  '', // varchar(20)
                'PRODUCTO_FACTURA' => $datosDetalle['referencia'] ?? '' // varchar(20) opcional
            ];

            // Construir la consulta del SP
            $sql = "EXEC [dbo].[sp_Alta_INV_MOV_ES_D]
                @CODIGO_MOV = ?,
                @NUMERO_INGRESO = ?,
                @CODIGO_PRODUCTO = ?,
                @CODIGO_UNIDAD = ?,
                @CANTIDAD = ?,
                @COSTO_UNITARIO = ?,
                @CODIGO_BODEGA = ?,
                @CODIGO_UBICACION = ?,
                @CODIGO_LOTE = ?,
                @PRODUCTO_FACTURA = ?";

            // Convertir valores a los tipos de datos correctos
            $parametrosEjecucion = [
                (string)$parametros['CODIGO_MOV'], // varchar(4)
                (int)$parametros['NUMERO_INGRESO'], // int
                (string)$parametros['CODIGO_PRODUCTO'], // varchar(20)
                (string)$parametros['CODIGO_UNIDAD'], // varchar(4)
                (float)$parametros['CANTIDAD'], // decimal(18, 2)
                (float)$parametros['COSTO_UNITARIO'], // decimal(18, 5)
                (string)$parametros['CODIGO_BODEGA'], // varchar(4)
                (string)$parametros['CODIGO_UBICACION'], // varchar(20)
                (string)$parametros['CODIGO_LOTE'], // varchar(20)
                $parametros['PRODUCTO_FACTURA'] !== null ? (string)$parametros['PRODUCTO_FACTURA'] : null // varchar(20) o null
            ];

            // Ejecutar el SP
            $query = $this->db->query($sql, $parametrosEjecucion);

            // Log para debug
            log_message('info', 'SP [dbo].[sp_Alta_INV_MOV_ES_D] ejecutado con parámetros: ' . json_encode($parametros));

            return [
                'success' => true,
                'message' => 'Detalle de movimiento de inventario agregado exitosamente',
                'parametros' => $parametros
            ];

        } catch (\Exception $e) {
            $errorMessage = 'Error al ejecutar [dbo].[sp_Alta_INV_MOV_ES_D]: ' . $e->getMessage();
            log_message('error', $errorMessage);
            log_message('debug', 'Trace: ' . $e->getTraceAsString());

            return [
                'success' => false,
                'message' => 'Error al agregar detalle de movimiento de inventario: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }


    public function ConductoresNomina($codigos)
    {
        try {
            if ($codigos == '')
            {
                $query = $this->db->query("select * from VW_CONDUCTORES");
            }
            else
            {
                $query = $this->db->query("select * from VW_CONDUCTORES where [No. Empleado] not in ($codigos)");
            }
            return $query->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener conductores nomina: ' . $e->getMessage());
            return [];
        }
    }
    public function ConductoresNominaByCodigo($codigos)
    {
        try {
          
             $query = $this->db->query("select * from VW_CONDUCTORES where [No. Empleado] = ?", [$codigos]);
             
            return $query->getRowArray();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener conductores nomina: ' . $e->getMessage());
            return [];
        }
    }

    // =========================================================================
    // COMBUSTIBLE — SP_INV_MOV_ES_E_INSERT / SP_INV_MOV_ES_D_INSERT
    // =========================================================================

    /**
     * Busca el NUMERO_INGRESO existente en INV_MOV_ES_E para una REFERENCIA (placa) y CODIGO_MOV='10'.
     * Usado cuando el SP de inserción falla con "Ya existe un encabezado".
     *
     * @param string $referencia  Placa del vehículo
     * @return int  El NUMERO_INGRESO o 0 si no se encuentra
     */
    public function buscarNumeroIngresoExistente(string $referencia): int
    {
        try {
            $query = $this->db->query(
                "SELECT TOP 1 NUMERO_INGRESO FROM INV_MOV_ES_E WHERE CODIGO_MOV = '10' AND REFERENCIA = ? ORDER BY NUMERO_INGRESO DESC",
                [$referencia]
            );
            $row = $query->getRowArray();
            return (int)($row['NUMERO_INGRESO'] ?? 0);
        } catch (\Throwable $e) {
            log_message('error', 'buscarNumeroIngresoExistente error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene el próximo NUMERO_INGRESO desde INV_ITPO_MOVIMIENTOS para un CODIGO_MOV dado.
     *
     * @param string $codigoMov  Ej. '10'
     * @return int
     */
    public function getNumeroIngresoMovimiento(string $codigoMov): int
    {
        try {
            // Diagnóstico: listar todos los registros de la tabla
            $todos = $this->db->query("SELECT CODIGO_MOV, NUMERO_DCTO FROM INV_ITPO_MOVIMIENTOS")->getResultArray();
            log_message('debug', 'INV_ITPO_MOVIMIENTOS completo: ' . json_encode($todos));

            $query = $this->db->query(
                "SELECT NUMERO_DCTO FROM INV_ITPO_MOVIMIENTOS WHERE CODIGO_MOV = ?",
                [$codigoMov]
            );
            $row = $query->getRowArray();
            log_message('debug', "getNumeroIngresoMovimiento CODIGO_MOV={$codigoMov} resultado: " . json_encode($row));
            return (int)($row['NUMERO_DCTO'] ?? 0);
        } catch (\Exception $e) {
            log_message('error', 'getNumeroIngresoMovimiento error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene el siguiente número de documento (recibo) mediante el SP SP_INV_OBTENER_NUMERO_DCTO.
     * Si el SP falla o no existe, consulta directamente INV_TIPO_MOVIMIENTOS.
     *
     * @param string $codigoMov  Ej. '10'
     * @return int
     */
    public function obtenerNumeroDcto(string $codigoMov = '10'): int
    {
        try {
            if (function_exists('sqlsrv_configure')) {
                \sqlsrv_configure('LoginTimeout', 3);
            }

            $query = $this->db->query(
                "EXEC dbo.SP_INV_OBTENER_NUMERO_DCTO ?",
                [$codigoMov]
            );
            $result = $query->getResultArray();
            $row    = $result[0] ?? [];

            if (!empty($row)) {
                $numero = reset($row);
                log_message('debug', "SP_INV_OBTENER_NUMERO_DCTO CODIGO_MOV={$codigoMov} resultado: " . json_encode($row));
                return (int)$numero;
            }
        } catch (\Throwable $e) {
            log_message('warning', 'SP_INV_OBTENER_NUMERO_DCTO falló: ' . $e->getMessage());
        }

        // Fallback: consulta directa a la tabla de tipos de movimiento
        try {
            $query = $this->db->query(
                "SELECT NUMERO_DCTO FROM dbo.INV_TIPO_MOVIMIENTOS WHERE CODIGO_MOV = ?",
                [$codigoMov]
            );
            $row = $query->getRowArray();
            return (int)($row['NUMERO_DCTO'] ?? 0);
        } catch (\Throwable $e) {
            log_message('error', 'obtenerNumeroDcto fallback error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Llama a SP_INV_MOV_ES_E_INSERT para crear el encabezado del movimiento de combustible.
     *
     * @param array $registro  Datos del registro de combustible
     *   Claves esperadas:
     *     - fecha_registro    (string Y-m-d o datetime)
     *     - placa             (string) — REFERENCIA
     *     - nombreCliente     (string) — NOMBRE_RECIBE / conductor
     *     - despachador       (string|null) — NOMBRE_ENTREGA
     *     - observaciones     (string|null)
     *     - codigo_centro     (string|null) — CODIGO_CENTRO
     *     - id_concepto       (int|null)    — 5 o 6
     * @param string $codigoMov     Código de movimiento, por defecto '10'
     * @param int    $numeroIngreso Obtenido con getNumeroIngresoMovimiento()
     * @return array ['success' => bool, 'message' => string, ...]
     */
    public function insertarEncabezadoCombustible(array $registro): array
    {
        try {
            $usuario = session()->get('usuario') ?? 'SISTEMA';

            $observaciones = trim(
                ($registro['placa'] ?? '') . ' ' . ($registro['observaciones'] ?? '')
            );

            $sql = "EXEC dbo.SP_INV_MOV_ES_E_INSERT
                @FECHA            = ?,
                @REFERENCIA       = ?,
                @NOMBRE_ENTREGA   = ?,
                @NOMBRE_RECIBE    = ?,
                @ORIGEN_MOV_INV   = ?,
                @ORIGEN_NUM_SERIE = ?,
                @ORIGEN_NUM_DOCTO = ?,
                @OBSERVACIONES    = ?,
                @CODIGO_BODEGA    = ?,
                @CODIGO_PLANTA    = ?,
                @NUMERO_DOCUMENTO = ?,
                @USUARIO_INGRESO  = ?,
                @ESTADO           = ?,
                @NUMERO_FACTURA   = ?,
                @ASOCIAR_INVOICE  = ?,
                @CODIGO_CENTRO    = ?,
                @ID_CONCEPTO      = ?";

            $params = [
                (string) ($registro['fecha_registro'] ?? date('Y-m-d H:i:s')), // @FECHA
                (string) ($registro['placa'] ?? ''),                   // @REFERENCIA          
                (string) ($registro['nombreCliente'] ?? ''),   // @NOMBRE_ENTREGA
                ($registro['despachador'] ?? null),           // @NOMBRE_RECIBE
                null,                                                  // @ORIGEN_MOV_INV
                null,                                                  // @ORIGEN_NUM_SERIE
                0,                                                  // @ORIGEN_NUM_DOCTO
                $observaciones ?: null,                                // @OBSERVACIONES
                '02',                                                  // @CODIGO_BODEGA
                null,                                                  // @CODIGO_PLANTA
                null,                                                  // @NUMERO_DOCUMENTO
                (string) $usuario,                                     // @USUARIO_INGRESO
                'P',                                                   // @ESTADO
                null,                                                  // @NUMERO_FACTURA
                null,                                                  // @ASOCIAR_INVOICE
                ($registro['codigo_centro'] ?? null),                  // @CODIGO_CENTRO
                isset($registro['id_concepto']) ? (int)$registro['id_concepto'] : null, // @ID_CONCEPTO
            ];

            $query  = $this->db->query($sql, $params);
            if ($query === false) {
                $err = $this->db->error();
                $msg = $err['message'] ?? 'Error desconocido en SP encabezado';
                log_message('error', 'SP_INV_MOV_ES_E_INSERT query returned false: ' . $msg);
                return ['success' => false, 'message' => $msg];
            }
            $result = $query->getResultArray();

            log_message('info', 'SP_INV_MOV_ES_E_INSERT ejecutado. Resultado: ' . json_encode($result));

            $row           = $result[0] ?? [];
            $numeroIngreso = (int)($row['NUMERO_INGRESO'] ?? 0);
            $codigoMov     = (string)($row['CODIGO_MOV']     ?? '');
            $resultado     = (string)($row['RESULTADO']      ?? '');

            if ($resultado !== 'OK') {
                return [
                    'success' => false,
                    'message' => 'SP encabezado no retornó OK. Resultado: ' . json_encode($row),
                ];
            }

            return [
                'success'        => true,
                'message'        => 'Encabezado creado.',
                'numero_ingreso' => $numeroIngreso,
                'codigo_mov'     => $codigoMov,
                'resultado_sp'   => $result,
            ];

        } catch (\Throwable $e) {
            log_message('error', 'SP_INV_MOV_ES_E_INSERT error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al crear encabezado: ' . $e->getMessage(),
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Llama a SP_INV_MOV_ES_D_INSERT para agregar el detalle (combustible) al movimiento.
     *
     * @param array $registro  Datos del registro de combustible
     *   Claves esperadas:
     *     - cantidad_litros      (float) — cantidad despachada
     *     - costo_unitario       (float) — precio por unidad (Giovanni)
     *     - codigo_producto      (string) — ej. 'CO-0000007'
     * @param string $codigoMov     Código de movimiento, por defecto '10'
     * @param int    $numeroIngreso Debe coincidir con el del encabezado
     * @return array ['success' => bool, 'message' => string, ...]
     */
    public function insertarDetalleCombustible(array $registro, string $codigoMov = '', int $numeroIngreso = 0): array
    {
        try {
            $cantidad      = (float)($registro['cantidad_litros'] ?? 0);
            $costoUnitario = (float)($registro['costo_unitario']  ?? 0);
            $costoTotal    = round($cantidad * $costoUnitario, 2);
            $producto      = $registro['codigo_producto'] ?? 'CO-0000007';

            $sql = "EXEC dbo.SP_INV_MOV_ES_D_INSERT
                @CODIGO_MOV        = ?,
                @NUMERO_INGRESO    = ?,
                @CODIGO_PRODUCTO   = ?,
                @CODIGO_UNIDAD     = ?,
                @CANTIDAD          = ?,
                @CODIGO_BODEGA     = ?,
                @CODIGO_UBICACION  = ?,
                @CODIGO_LOTE       = ?,
                @CODIGO_UNIDAD_TR  = ?,
                @CANTIDAD_TR       = ?,
                @PRODUCTO_FACTURA  = ?,
                @ITEM              = ?";

            $params = [
                (string) $codigoMov,   // @CODIGO_MOV
                (int)    $numeroIngreso,// @NUMERO_INGRESO
                (string) $producto,    // @CODIGO_PRODUCTO
                'STK',                 // @CODIGO_UNIDAD
                $cantidad,             // @CANTIDAD
                '02',                  // @CODIGO_BODEGA
                '01',                  // @CODIGO_UBICACION
                '01',                  // @CODIGO_LOTE
                'STK',                 // @CODIGO_UNIDAD_TR
                $cantidad,             // @CANTIDAD_TR
                null,                  // @PRODUCTO_FACTURA
                1,                     // @ITEM
            ];

            $query  = $this->db->query($sql, $params);
            if ($query === false) {
                $err = $this->db->error();
                $msg = $err['message'] ?? 'Error desconocido en SP detalle';
                log_message('error', 'SP_INV_MOV_ES_D_INSERT query returned false: ' . $msg);
                return ['success' => false, 'message' => $msg];
            }
            $result = $query->getResultArray();

            log_message('info', 'SP_INV_MOV_ES_D_INSERT ejecutado. Resultado: ' . json_encode($result));

            return [
                'success'      => true,
                'message'      => 'Detalle de movimiento combustible agregado.',
                'costo_total'  => $costoTotal,
                'resultado_sp' => $result,
            ];

        } catch (\Throwable $e) {
            log_message('error', 'SP_INV_MOV_ES_D_INSERT error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al agregar detalle: ' . $e->getMessage(),
                'error'   => $e->getMessage(),
            ];
        }
    }

}
