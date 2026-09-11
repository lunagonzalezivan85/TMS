<?php

namespace App\Models;

use CodeIgniter\Model;

class CotizacionModel extends Model
{
    protected $table = 'cotizaciones';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'numero_cotizacion',
        'producto_id',
        'producto_nombre',
        'cliente_nombre',
        'cliente_ruc',
        'cliente_telefono',
        'origen',
        'destino',
        'tipo_vehiculo',
        'distancia_km',
        'volumen_galones',
        'rendimiento_km_galon',
        'precio_combustible_galon',
        'costo_viaje',
        'margen_porcentaje',
        'margen_monto',
        'precio_sugerido',
        'precio_final',
        'flete_por_galon',
        'desglose_costos',
        'estado',
        'usuario_crea',
        'fecha_creacion',
        'fecha_actualiza',
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_creacion';
    protected $updatedField = 'fecha_actualiza';

    protected $validationRules = [
        'cliente_nombre'   => 'required|min_length[2]|max_length[200]',
        'distancia_km'     => 'required|numeric|greater_than[0]',
        'volumen_galones'  => 'required|numeric|greater_than[0]',
        'rendimiento_km_galon' => 'required|numeric|greater_than[0]',
        'estado'           => 'permit_empty|in_list[BORRADOR,ENVIADA,APROBADA,RECHAZADA]',
    ];

    protected $validationMessages = [
        'cliente_nombre' => [
            'required'   => 'El nombre del cliente es obligatorio',
            'min_length' => 'El nombre del cliente debe tener al menos 2 caracteres',
        ],
        'distancia_km' => [
            'required'     => 'La distancia es obligatoria',
            'greater_than' => 'La distancia debe ser mayor a 0',
        ],
        'volumen_galones' => [
            'required'     => 'El volumen en galones es obligatorio',
            'greater_than' => 'El volumen debe ser mayor a 0',
        ],
    ];

    /**
     * Genera el siguiente número de cotización secuencial.
     */
    public function generarNumeroCotizacion(): string
    {
        $ultima = $this->orderBy('id', 'DESC')->first();
        $consecutivo = $ultima ? (int) $ultima['id'] + 1 : 1;
        return 'COT-' . date('Y') . '-' . str_pad($consecutivo, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Obtiene una cotización por ID con el desglose decodificado.
     */
    public function getCotizacionConDesglose(int $id): ?array
    {
        $cot = $this->find($id);
        if (!$cot) return null;
        if (!empty($cot['desglose_costos'])) {
            $cot['desglose'] = json_decode($cot['desglose_costos'], true);
        } else {
            $cot['desglose'] = [];
        }
        return $cot;
    }

    /**
     * Lista cotizaciones ordenadas por fecha descendente.
     */
    public function listarCotizaciones(int $limite = 100): array
    {
        return $this->orderBy('fecha_creacion', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Resumen para dashboard: totales por estado, monto solo aprobadas, margen prom.
     */
    public function getResumen(): array
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            COUNT(*) as total_cotizaciones,
            COALESCE(SUM(CASE WHEN estado = \'APROBADA\' THEN precio_final ELSE 0 END), 0) as monto_total,
            COALESCE(AVG(CASE WHEN estado = \'APROBADA\' THEN margen_porcentaje ELSE NULL END), 0) as margen_promedio,
            COALESCE(SUM(CASE WHEN estado = \'BORRADOR\' THEN 1 ELSE 0 END), 0) as total_borradores,
            COALESCE(SUM(CASE WHEN estado = \'APROBADA\' THEN 1 ELSE 0 END), 0) as total_aprobadas,
            COALESCE(SUM(CASE WHEN estado = \'RECHAZADA\' THEN 1 ELSE 0 END), 0) as total_rechazadas
        ');
        $row = $builder->get()->getRowArray() ?? [];
        return [
            'total_cotizaciones' => (int) ($row['total_cotizaciones'] ?? 0),
            'monto_total'        => (float) ($row['monto_total'] ?? 0),
            'margen_promedio'    => round((float) ($row['margen_promedio'] ?? 0), 2),
            'total_borradores'   => (int) ($row['total_borradores'] ?? 0),
            'total_aprobadas'    => (int) ($row['total_aprobadas'] ?? 0),
            'total_rechazadas'   => (int) ($row['total_rechazadas'] ?? 0),
        ];
    }
}
