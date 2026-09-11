<?php

namespace App\Services;

use App\Models\CotizacionModel;
use App\Models\ItemModel;

class CotizacionService
{
    private CotizacionModel $model;
    private ItemModel $itemModel;

    /** Factores fijos de cálculo (extraídos del cotizador original) */
    private const EXCHANGE_RATE          = 36.6243;
    private const FUEL_PRICE_PER_GALLON = 149.3879;
    private const LABOR_INDEX           = 1.5;
    private const DEPRECIATION_USD_KM   = 0.081;
    private const MAINTENANCE_USD_KM    = 0.13;
    private const TIRES_USD_KM          = 0.05;
    private const MUNICIPALITY_PCT      = 1.0;
    private const DGI_PCT               = 3.0;

    /** Fallback si la tabla items no tiene datos */
    private const FALLBACK_PRODUCTS = [
        'diesel'           => ['name' => 'Diesel',           'costPerGallon' => 149.38],
        'maxxima_regular'  => ['name' => 'Maxxima Regular',  'costPerGallon' => 161.08],
        'maxxima_premium'  => ['name' => 'Maxxima Premium',  'costPerGallon' => 165.59],
        'jet_a1'           => ['name' => 'Jet A1',           'costPerGallon' => 126.44],
    ];

    public function __construct()
    {
        $this->model = new CotizacionModel();
        $this->itemModel = new ItemModel();
    }

    /**
     * Devuelve los productos de combustible disponibles desde la BD.
     * Si la tabla items no tiene datos, usa el fallback hardcoded.
     */
    public function getProductosCombustible(): array
    {
        $productos = $this->itemModel->getProductosActivos();
        return !empty($productos) ? $productos : self::FALLBACK_PRODUCTS;
    }

    /**
     * Devuelve los factores fijos de cálculo.
     */
    public function getFactoresFijos(): array
    {
        return [
            'exchangeRate'          => self::EXCHANGE_RATE,
            'fuelPricePerGallon'    => self::FUEL_PRICE_PER_GALLON,
            'laborIndex'            => self::LABOR_INDEX,
            'depreciationUsdPerKm'  => self::DEPRECIATION_USD_KM,
            'maintenanceUsdPerKm'   => self::MAINTENANCE_USD_KM,
            'tiresUsdPerKm'         => self::TIRES_USD_KM,
            'municipalityPercent'   => self::MUNICIPALITY_PCT,
            'dgiPercent'            => self::DGI_PCT,
        ];
    }

    /**
     * Calcula la cotización con toda la lógica de negocio.
     *
     * @param array $input Datos del formulario:
     *   - producto_id: string (key de FUEL_PRODUCTS)
     *   - volumen_galones: float
     *   - distancia_km: float
     *   - rendimiento_km_galon: float
     *   - margen_porcentaje: float
     *   - precio_propuesto: float (opcional, 0 = usar sugerido)
     *   - chofer_porcentaje: float
     *   - viatico: float
     *   - admin_porcentaje: float
     * @return array Resultado del cálculo con desglose completo
     */
    public function calcularCotizacion(array $input): array
    {
        $volumen     = (float) ($input['volumen_galones'] ?? 0);
        $distancia   = (float) ($input['distancia_km'] ?? 0);
        $rendimiento = (float) ($input['rendimiento_km_galon'] ?? 0);
        $margenPct   = (float) ($input['margen_porcentaje'] ?? 0);
        $precioProp  = (float) ($input['precio_propuesto'] ?? 0);
        $choferPct   = (float) ($input['chofer_porcentaje'] ?? 12);
        $viatico     = (float) ($input['viatico'] ?? 0);
        $adminPct    = (float) ($input['admin_porcentaje'] ?? 8);
        $productoId  = $input['producto_id'] ?? 'diesel';

        $productos = $this->getProductosCombustible();
        $producto = $productos[$productoId] ?? ($productos['diesel'] ?? self::FALLBACK_PRODUCTS['diesel']);

        // --- Subtotal del producto (volumen × precio/galón) ---
        $precioGalonProducto = (float) ($producto['pricePerGallon'] ?? $producto['costPerGallon'] ?? 0);
        $subtotalProducto    = $volumen * $precioGalonProducto;

        // --- Cálculo de costos directos del flete ---
        $galonesConsumidos = $rendimiento > 0 ? $distancia / $rendimiento : 0;
        $costoCombustible  = $galonesConsumidos * self::FUEL_PRICE_PER_GALLON;
        $depreciacion      = $distancia * self::DEPRECIATION_USD_KM * self::EXCHANGE_RATE;
        $mantenimiento     = $distancia * self::MAINTENANCE_USD_KM * self::EXCHANGE_RATE;
        $llantas           = $distancia * self::TIRES_USD_KM * self::EXCHANGE_RATE;
        $costoDirectoFijo  = $costoCombustible + $depreciacion + $mantenimiento + $llantas + $viatico;

        // --- Porcentajes retenidos ---
        $choferPctEfectivo = ($choferPct / 100) * self::LABOR_INDEX;
        $porcentajeRetenido = $choferPctEfectivo
            + ($adminPct / 100)
            + (self::MUNICIPALITY_PCT / 100)
            + (self::DGI_PCT / 100)
            + ($margenPct / 100);

        $denominador = max(1 - $porcentajeRetenido, 0.01);
        $fleteSugerido = $costoDirectoFijo / $denominador;

        // --- Precio total = subtotal producto + flete ---
        $tienePrecioManual = $precioProp > 0;
        $fleteFinal = $tienePrecioManual ? ($precioProp - $subtotalProducto) : $fleteSugerido;
        $fleteFinal = max($fleteFinal, 0);

        $precioSugerido = $subtotalProducto + $fleteSugerido;
        $precioFinal    = $subtotalProducto + $fleteFinal;

        // --- Costos derivados del flete ---
        $costoChofer       = $fleteFinal * $choferPctEfectivo;
        $costoAdmin        = $fleteFinal * ($adminPct / 100);
        $costoMunicipalidad = $fleteFinal * (self::MUNICIPALITY_PCT / 100);
        $costoDGI          = $fleteFinal * (self::DGI_PCT / 100);
        $costoViaje        = $costoDirectoFijo + $costoChofer + $costoAdmin + $costoMunicipalidad + $costoDGI;
        $margenMonto       = $fleteFinal - $costoViaje;
        $margenRealPct     = $fleteFinal > 0 ? ($margenMonto / $fleteFinal) * 100 : 0;
        $fletePorGalon     = $volumen > 0 ? $fleteFinal / $volumen : 0;

        return [
            'producto_id'           => $productoId,
            'producto_nombre'       => $producto['name'],
            'producto_costo_galon'  => $producto['costPerGallon'],
            'producto_precio_galon' => $precioGalonProducto,
            'subtotal_producto'     => round($subtotalProducto, 2),
            'flete_sugerido'        => round($fleteSugerido, 2),
            'flete_final'           => round($fleteFinal, 2),
            'galones_consumidos'    => round($galonesConsumidos, 4),
            'costo_combustible'     => round($costoCombustible, 2),
            'depreciacion'          => round($depreciacion, 2),
            'mantenimiento'         => round($mantenimiento, 2),
            'llantas'               => round($llantas, 2),
            'viatico'               => round($viatico, 2),
            'costo_directo_fijo'    => round($costoDirectoFijo, 2),
            'chofer_porcentaje'     => $choferPct,
            'chofer_pct_efectivo'   => round($choferPctEfectivo * 100, 4),
            'costo_chofer'          => round($costoChofer, 2),
            'admin_porcentaje'      => $adminPct,
            'costo_admin'           => round($costoAdmin, 2),
            'costo_municipalidad'   => round($costoMunicipalidad, 2),
            'costo_dgi'             => round($costoDGI, 2),
            'costo_viaje'           => round($costoViaje, 2),
            'margen_porcentaje'     => round($margenRealPct, 2),
            'margen_monto'          => round($margenMonto, 2),
            'margen_objetivo_pct'   => $margenPct,
            'precio_sugerido'       => round($precioSugerido, 2),
            'tiene_precio_manual'   => $tienePrecioManual,
            'precio_final'          => round($precioFinal, 2),
            'flete_por_galon'       => round($fletePorGalon, 4),
            'subtotal_producto'     => round($subtotalProducto, 2),
            'flete_sugerido'        => round($fleteSugerido, 2),
            'flete_final'           => round($fleteFinal, 2),
            'volumen_galones'       => $volumen,
            'distancia_km'          => $distancia,
            'rendimiento_km_galon'  => $rendimiento,
            'tipo_cambio'           => self::EXCHANGE_RATE,
            'precio_combustible_galon' => self::FUEL_PRICE_PER_GALLON,
        ];
    }

    /**
     * Calcula el pricing de combustible (referencia, descuento, comisión).
     *
     * @param array $input:
     *   - precio_referencia: float
     *   - descuento: float
     *   - comision_vendedor: float
     *   - flete_por_galon: float (del cálculo principal)
     * @return array
     */
    public function calcularPricingCombustible(array $input): array
    {
        $precioRef     = (float) ($input['precio_referencia'] ?? 0);
        $descuento     = (float) ($input['descuento'] ?? 0);
        $comisionVend  = (float) ($input['comision_vendedor'] ?? 0);
        $fleteGalon    = (float) ($input['flete_por_galon'] ?? 0);

        $precioCliente  = $precioRef - $descuento;
        $margenBruto    = $precioCliente - self::FUEL_PRICE_PER_GALLON;
        $margenNeto     = $margenBruto - $comisionVend - $fleteGalon;

        return [
            'precio_referencia'     => round($precioRef, 4),
            'descuento'             => round($descuento, 4),
            'precio_cliente'        => round($precioCliente, 4),
            'comision_vendedor'     => round($comisionVend, 4),
            'margen_bruto'          => round($margenBruto, 4),
            'margen_neto'           => round($margenNeto, 4),
            'precio_combustible_galon' => self::FUEL_PRICE_PER_GALLON,
        ];
    }

    /**
     * Crea una cotización en la base de datos.
     *
     * @param array $input Datos del formulario + cálculo
     * @return array ['success' => bool, 'id' => int|null, 'message' => string]
     */
    public function crearCotizacion(array $input): array
    {
        $calc = $this->calcularCotizacion($input);

        $numero = $this->model->generarNumeroCotizacion();

        $data = [
            'numero_cotizacion'         => $numero,
            'producto_id'               => $calc['producto_id'],
            'producto_nombre'           => $calc['producto_nombre'],
            'cliente_nombre'            => trim($input['cliente_nombre'] ?? ''),
            'cliente_ruc'               => trim($input['cliente_ruc'] ?? ''),
            'cliente_telefono'          => trim($input['cliente_telefono'] ?? ''),
            'origen'                    => $input['origen'] ?? null,
            'destino'                   => $input['destino'] ?? null,
            'tipo_vehiculo'             => $input['tipo_vehiculo'] ?? 'Camion rigido',
            'distancia_km'              => $calc['distancia_km'],
            'volumen_galones'           => $calc['volumen_galones'],
            'rendimiento_km_galon'      => $calc['rendimiento_km_galon'],
            'precio_combustible_galon'  => $calc['precio_combustible_galon'],
            'costo_viaje'               => $calc['costo_viaje'],
            'margen_porcentaje'         => $calc['margen_porcentaje'],
            'margen_monto'              => $calc['margen_monto'],
            'precio_sugerido'           => $calc['precio_sugerido'],
            'precio_final'              => $calc['precio_final'],
            'flete_por_galon'           => $calc['flete_por_galon'],
            'desglose_costos'           => json_encode($calc),
            'estado'                    => $input['estado'] ?? 'BORRADOR',
            'usuario_crea'              => $input['usuario_crea'] ?? session()->get('usuario') ?? 'admin',
            'fecha_creacion'            => date('Y-m-d H:i:s'),
            'fecha_actualiza'           => date('Y-m-d H:i:s'),
        ];

        if (!$this->model->save($data)) {
            return ['success' => false, 'message' => 'Error al guardar la cotización', 'errors' => $this->model->errors()];
        }

        $id = $this->model->getInsertID();
        return ['success' => true, 'id' => $id, 'numero' => $numero, 'message' => 'Cotización creada correctamente'];
    }

    /**
     * Actualiza una cotización existente.
     *
     * @param int $id
     * @param array $input
     * @return array
     */
    public function actualizarCotizacion(int $id, array $input): array
    {
        $exist = $this->model->find($id);
        if (!$exist) {
            return ['success' => false, 'message' => 'Cotización no encontrada'];
        }

        $calc = $this->calcularCotizacion($input);

        $data = [
            'producto_id'               => $calc['producto_id'],
            'producto_nombre'           => $calc['producto_nombre'],
            'cliente_nombre'            => trim($input['cliente_nombre'] ?? ''),
            'cliente_ruc'               => trim($input['cliente_ruc'] ?? ''),
            'cliente_telefono'          => trim($input['cliente_telefono'] ?? ''),
            'origen'                    => $input['origen'] ?? null,
            'destino'                   => $input['destino'] ?? null,
            'tipo_vehiculo'             => $input['tipo_vehiculo'] ?? 'Camion rigido',
            'distancia_km'              => $calc['distancia_km'],
            'volumen_galones'           => $calc['volumen_galones'],
            'rendimiento_km_galon'      => $calc['rendimiento_km_galon'],
            'precio_combustible_galon'  => $calc['precio_combustible_galon'],
            'costo_viaje'               => $calc['costo_viaje'],
            'margen_porcentaje'         => $calc['margen_porcentaje'],
            'margen_monto'              => $calc['margen_monto'],
            'precio_sugerido'           => $calc['precio_sugerido'],
            'precio_final'              => $calc['precio_final'],
            'flete_por_galon'           => $calc['flete_por_galon'],
            'desglose_costos'           => json_encode($calc),
            'estado'                    => $input['estado'] ?? $exist['estado'],
            'fecha_actualiza'           => date('Y-m-d H:i:s'),
        ];

        if (!$this->model->update($id, $data)) {
            return ['success' => false, 'message' => 'Error al actualizar la cotización', 'errors' => $this->model->errors()];
        }

        return ['success' => true, 'id' => $id, 'message' => 'Cotización actualizada correctamente'];
    }

    /**
     * Elimina una cotización.
     */
    public function eliminarCotizacion(int $id): array
    {
        $exist = $this->model->find($id);
        if (!$exist) {
            return ['success' => false, 'message' => 'Cotización no encontrada'];
        }
        $this->model->delete($id);
        return ['success' => true, 'message' => 'Cotización eliminada'];
    }

    /**
     * Obtiene una cotización con desglose.
     */
    public function getCotizacion(int $id): ?array
    {
        return $this->model->getCotizacionConDesglose($id);
    }

    /**
     * Lista cotizaciones para la vista de historial.
     */
    public function listarCotizaciones(int $limite = 100): array
    {
        return $this->model->listarCotizaciones($limite);
    }

    /**
     * Resumen para dashboard.
     */
    public function getResumen(): array
    {
        return $this->model->getResumen();
    }

    /**
     * Aprueba una cotización (cambia estado a APROBADA).
     */
    public function aprobarCotizacion(int $id): array
    {
        $exist = $this->model->find($id);
        if (!$exist) {
            return ['success' => false, 'message' => 'Cotización no encontrada'];
        }
        if (($exist['estado'] ?? '') !== 'BORRADOR') {
            return ['success' => false, 'message' => 'Solo se pueden aprobar cotizaciones en borrador'];
        }
        $this->model->update($id, [
            'estado'          => 'APROBADA',
            'fecha_actualiza' => date('Y-m-d H:i:s'),
        ]);
        return ['success' => true, 'message' => 'Cotización aprobada correctamente'];
    }

    /**
     * Rechaza una cotización (cambia estado a RECHAZADA).
     */
    public function rechazarCotizacion(int $id): array
    {
        $exist = $this->model->find($id);
        if (!$exist) {
            return ['success' => false, 'message' => 'Cotización no encontrada'];
        }
        if (($exist['estado'] ?? '') !== 'BORRADOR') {
            return ['success' => false, 'message' => 'Solo se pueden rechazar cotizaciones en borrador'];
        }
        $this->model->update($id, [
            'estado'          => 'RECHAZADA',
            'fecha_actualiza' => date('Y-m-d H:i:s'),
        ]);
        return ['success' => true, 'message' => 'Cotización rechazada'];
    }
}
