<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistroCombustibleModel extends Model
{
    protected $table = 'registro_combustible';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_vehiculo',
        'id_direccion',
        'fecha_registro',
        'kilometraje_anterior',
        'kilometraje_actual',
        'cantidad_litros',
        'id_tipo_motivo',
        'observaciones',
        'usuario_crea',
        'usuario_edita',
        'fecha_actualiza',
        'medicion',
        'nombreCliente',
        'dni',
        'tipo',
        'monto',
        'monto_nio',
        'monto_usd',
        'combustible_tanque',
        'rendimiento',
        'rendimiento_promedio',
        'estado',
        'referencia1',
        'referencia2',
        'enviado',
        'numero_ingreso_sag',
        'motivo_rechazo',
        'id_lectura'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_actualiza';
    protected $updatedField = 'fecha_actualiza';

    /**
     * Obtiene registros de combustible por ID de lectura (apertura de bomba)
     */
    public function getRegistrosPorLectura($lecturaId)
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT r.id, r.fecha_registro, r.cantidad_litros, r.id_lectura, r.kilometraje_actual, r.kilometraje_anterior,
                   v.codigo_centro_costo, v.codigo_unidad, v.placa, v.marca, v.modelo
            FROM registro_combustible r
            INNER JOIN vehiculos v ON r.id_vehiculo = v.id
            WHERE r.id_lectura = ?
            ORDER BY r.fecha_registro DESC
        ", [$lecturaId])->getResultArray();
    }

    protected $validationRules = [
        'id_vehiculo' => 'required|integer',
        'id_direccion' => 'permit_empty|integer',
        'fecha_registro' => 'required|valid_date',
        'kilometraje_anterior' => 'required|numeric',
        'kilometraje_actual' => 'permit_empty|numeric',
        'cantidad_litros' => 'required|numeric',
        'medicion' => 'permit_empty|numeric',
        'nombreCliente' => 'permit_empty|string|max_length[150]',
        'dni' => 'permit_empty|string|max_length[50]',
        'tipo' => 'permit_empty|in_list[CONSUMO,VENTA]',
        'monto' => 'permit_empty|numeric',
        'combustible_tanque' => 'permit_empty|numeric',
        'id_tipo_motivo' => 'permit_empty|integer',
        'observaciones' => 'permit_empty|string|max_length[500]',
        'motivo_rechazo' => 'permit_empty|string|max_length[500]',
        'usuario_crea' => 'permit_empty|string|max_length[100]',
        'usuario_edita' => 'permit_empty|string|max_length[100]',
        'rendimiento' => 'permit_empty|numeric',
        'rendimiento_promedio' => 'permit_empty|numeric',
        'estado' => 'permit_empty|in_list[APROBADO,BLOQUEADO,RECHAZADO]',
        'id_lectura' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'id_vehiculo' => [
            'required' => 'El vehículo es obligatorio',
            'integer' => 'El vehículo debe ser un número válido'
        ],
        'id_direccion' => [
            'integer' => 'La dirección debe ser un número válido'
        ],
        'fecha_registro' => [
            'required' => 'La fecha de registro es obligatoria',
            'valid_date' => 'La fecha de registro debe ser válida'
        ],
        'kilometraje_anterior' => [
            'required' => 'El kilometraje anterior es obligatorio',
            'decimal' => 'El kilometraje anterior debe ser un número válido'
        ],
        'kilometraje_actual' => [
            'required' => 'El kilometraje actual es obligatorio',
            'decimal' => 'El kilometraje actual debe ser un número válido'
        ],
        'cantidad_litros' => [
            'required' => 'La cantidad de litros es obligatoria',
            'decimal' => 'La cantidad de litros debe ser un número válido'
        ],
        'id_motivo' => [
            'required' => 'El motivo es obligatorio',
            'integer' => 'El motivo debe ser un número válido'
        ]
    ];

    /**
     * Obtiene todos los registros de combustible con información relacionada
     */
    public function getRegistrosConRelaciones($filtros = [])
    {
        $builder = $this->db->table($this->table . ' rc');
        $builder->select('
            rc.*,
            v.placa,
            v.marca,
            v.modelo,
            v.anio,
            v.codigo_unidad,
            CASE WHEN COALESCE(v.tipo_consumo, "") = "6" THEN 0 ELSE 1 END as externo,
            v.tipo_consumo,
            mac.nombre  as motivo,
            d.direccion,
            d.latitud,
            d.longitud
        ');
        $builder->join('vehiculos v', 'v.id = rc.id_vehiculo', 'left');
        $builder->join('catalogo mac', 'mac.id = rc.id_tipo_motivo', 'left');
        $builder->join('direcciones d', 'd.id = rc.id_direccion', 'left');

        // Aplicar filtros
        if (!empty($filtros['vehiculo'])) {
            $builder->where('rc.id_vehiculo', $filtros['vehiculo']);
        }

        if (!empty($filtros['fecha_desde'])) {
            $builder->where('rc.fecha_registro >=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('rc.fecha_registro <=', $filtros['fecha_hasta']);
        }

        if (!empty($filtros['tipo'])) {
            $builder->where('rc.tipo', $filtros['tipo']);
        }

        if (!empty($filtros['motivo'])) {
            $builder->where('rc.id_motivo', $filtros['motivo']);
        }

        if (!empty($filtros['direccion'])) {
            $builder->where('rc.idDireccion', $filtros['direccion']);
        }

        if (!empty($filtros['usuario'])) {
            $builder->where('rc.usuario_crea', $filtros['usuario']);
        }

        if (!empty($filtros['estado'])) {
            $builder->where('rc.estado', $filtros['estado']);
        }

        if (!empty($filtros['lectura_id'])) {
            $builder->where('rc.id_lectura', $filtros['lectura_id']);
        }

        $builder->orderBy('rc.fecha_registro', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene un registro específico con sus relaciones
     */
    public function getRegistroConRelaciones($id)
    {
        $builder = $this->db->table($this->table . ' rc');
        $builder->select('
            rc.*,
            v.placa,
            v.marca,
            v.modelo,
            v.anio,
            mac.nombre as motivo,
            d.direccion,
            d.latitud,
            d.longitud
        ');
        $builder->join('vehiculos v', 'v.id = rc.id_vehiculo', 'left');
        $builder->join('catalogo mac', 'mac.id = rc.id_tipo_motivo', 'left');
        $builder->join('direcciones d', 'd.id = rc.id_direccion', 'left');
        $builder->where('rc.id', $id);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Obtiene el último registro de combustible de un vehículo
     */
    public function getUltimoRegistroVehiculo($idVehiculo)
    {
        $builder = $this->db->table($this->table);
        $builder->where('id_vehiculo', $idVehiculo);
        $builder->orderBy('fecha_registro', 'DESC');
        $builder->limit(1);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Calcula estadísticas de consumo
     */
    public function getEstadisticasConsumo($idVehiculo = null, $fechaDesde = null, $fechaHasta = null, $usuario = null)
    {
        $builder = $this->db->table($this->table . ' rc');
        $builder->join('vehiculos v', 'v.id = rc.id_vehiculo', 'left');

        if ($idVehiculo) {
            $builder->where('rc.id_vehiculo', $idVehiculo);
        }

        if ($fechaDesde) {
            $builder->where('rc.fecha_registro >=', $fechaDesde);
        }

        if ($fechaHasta) {
            $builder->where('rc.fecha_registro <=', $fechaHasta);
        }

        if ($usuario) {
            $builder->where('rc.usuario_crea', $usuario);
        }

        $builder->select('
            COUNT(*) as total_registros,
            SUM(rc.cantidad_litros) as total_litros,
            SUM(rc.cantidad_litros / 3.78541) as total_galones,
            SUM(COALESCE(rc.kilometraje_actual, 0) - COALESCE(rc.kilometraje_anterior, 0)) as total_kilometros,
            AVG(rc.cantidad_litros) as promedio_litros,
            COUNT(DISTINCT rc.id_vehiculo) as total_vehiculos,
            SUM(CASE WHEN rc.tipo = "VENTA" THEN 1 ELSE 0 END) as total_registros_venta,
            SUM(CASE WHEN rc.tipo = "VENTA" THEN rc.monto_nio ELSE 0 END) as total_monto_nio,
            SUM(CASE WHEN rc.tipo = "VENTA" THEN rc.monto_usd ELSE 0 END) as total_monto_usd,
            SUM(CASE WHEN COALESCE(v.tipo_consumo, "") = "6" THEN 1 ELSE 0 END) as total_registros_interno,
            SUM(CASE WHEN COALESCE(v.tipo_consumo, "") <> "6" THEN 1 ELSE 0 END) as total_registros_externo,
            SUM(CASE WHEN COALESCE(v.tipo_consumo, "") = "6" THEN rc.cantidad_litros ELSE 0 END) as total_litros_interno,
            SUM(CASE WHEN COALESCE(v.tipo_consumo, "") <> "6" THEN rc.cantidad_litros ELSE 0 END) as total_litros_externo
        ');

        $estadisticas = $builder->get()->getRowArray() ?? [];

        $estadisticas['total_registros'] = (int) ($estadisticas['total_registros'] ?? 0);
        $estadisticas['total_litros'] = (float) ($estadisticas['total_litros'] ?? 0);
        $estadisticas['total_galones'] = (float) ($estadisticas['total_galones'] ?? 0);
        $estadisticas['total_kilometros'] = (float) ($estadisticas['total_kilometros'] ?? 0);
        $estadisticas['promedio_litros'] = (float) ($estadisticas['promedio_litros'] ?? 0);
        $estadisticas['total_vehiculos'] = (int) ($estadisticas['total_vehiculos'] ?? 0);
        $estadisticas['total_registros_venta'] = (int) ($estadisticas['total_registros_venta'] ?? 0);
        $estadisticas['total_monto_nio'] = (float) ($estadisticas['total_monto_nio'] ?? 0);
        $estadisticas['total_monto_usd'] = (float) ($estadisticas['total_monto_usd'] ?? 0);
        $estadisticas['total_registros_interno'] = (int) ($estadisticas['total_registros_interno'] ?? 0);
        $estadisticas['total_registros_externo'] = (int) ($estadisticas['total_registros_externo'] ?? 0);
        $estadisticas['total_litros_interno'] = (float) ($estadisticas['total_litros_interno'] ?? 0);
        $estadisticas['total_litros_externo'] = (float) ($estadisticas['total_litros_externo'] ?? 0);

        // Promedio diario de litros despachados
        $dias = 1;
        if ($fechaDesde && $fechaHasta) {
            $diff = (strtotime($fechaHasta) - strtotime($fechaDesde)) / 86400;
            $dias = max(1, (int)$diff + 1);
        }
        $estadisticas['promedio_diario_litros'] = round($estadisticas['total_litros'] / $dias, 2);

        // --- Top vehículos con mayor consumo ---
        $builderTop = $this->db->table($this->table . ' rc');
        $builderTop->select('rc.id_vehiculo, v.placa, v.marca, v.modelo, SUM(rc.cantidad_litros) as total_litros, COUNT(*) as veces');
        $builderTop->join('vehiculos v', 'v.id = rc.id_vehiculo', 'left');
        if ($idVehiculo) {
            $builderTop->where('rc.id_vehiculo', $idVehiculo);
        }
        if ($fechaDesde) {
            $builderTop->where('rc.fecha_registro >=', $fechaDesde);
        }
        if ($fechaHasta) {
            $builderTop->where('rc.fecha_registro <=', $fechaHasta);
        }
        if ($usuario) {
            $builderTop->where('rc.usuario_crea', $usuario);
        }
        $builderTop->groupBy('rc.id_vehiculo, v.placa, v.marca, v.modelo');
        $builderTop->orderBy('total_litros', 'DESC');
        $estadisticas['top_vehiculos'] = $builderTop->get()->getResultArray();

        // --- Últimos registros de combustible ---
        $builderRecientes = $this->db->table($this->table . ' rc');
        $builderRecientes->select('rc.*, v.placa, v.marca, v.modelo');
        $builderRecientes->join('vehiculos v', 'v.id = rc.id_vehiculo', 'left');
        if ($idVehiculo) {
            $builderRecientes->where('rc.id_vehiculo', $idVehiculo);
        }
        if ($fechaDesde) {
            $builderRecientes->where('rc.fecha_registro >=', $fechaDesde);
        }
        if ($fechaHasta) {
            $builderRecientes->where('rc.fecha_registro <=', $fechaHasta);
        }
        if ($usuario) {
            $builderRecientes->where('rc.usuario_crea', $usuario);
        }
        $builderRecientes->orderBy('rc.fecha_registro', 'DESC');
        $builderRecientes->limit(10);
        $estadisticas['ultimos_registros'] = $builderRecientes->get()->getResultArray();

        // --- Conteo de registros bloqueados ---
        $builderBloq = $this->db->table($this->table);
        if ($idVehiculo) {
            $builderBloq->where('id_vehiculo', $idVehiculo);
        }
        if ($fechaDesde) {
            $builderBloq->where('fecha_registro >=', $fechaDesde);
        }
        if ($fechaHasta) {
            $builderBloq->where('fecha_registro <=', $fechaHasta);
        }
        if ($usuario) {
            $builderBloq->where('usuario_crea', $usuario);
        }
        $builderBloq->where('estado', 'BLOQUEADO');
        $estadisticas['total_bloqueados'] = (int) $builderBloq->countAllResults();

        return $estadisticas;
    }

    /**
     * Devuelve registros que NO se enviaron al SAG (enviado_sag = 0)
     */
    public function getPendientesSAG()
    {
        $builder = $this->db->table($this->table . ' rc');
        $builder->select('rc.*, v.placa, v.marca, v.modelo');
        $builder->join('vehiculos v', 'v.id = rc.id_vehiculo', 'left');
        $builder->where('rc.enviado', 0);
        $builder->orderBy('rc.fecha_registro', 'DESC');
        return $builder->get()->getResultArray();
    }

    /**
     * Marca un registro como enviado (1) o pendiente (0) en SAG
     */
    public function marcarEnvioSAG(int $id, int $estado): bool
    {
        return $this->db->table($this->table)
            ->where('id', $id)
            ->update(['enviado' => $estado]);
    }

    /**
     * Valida que el kilometraje actual sea mayor al anterior
     */
    public function validarKilometraje($data)
    {
        if (isset($data['kilometraje_actual']) && isset($data['kilometraje_anterior'])) {
            if ($data['kilometraje_actual'] == 0 && $data['kilometraje_anterior'] == 0) {
                return true;
            }
            if ($data['kilometraje_actual'] <= $data['kilometraje_anterior']) {
                return false;
            }
        }
        return true;
    }

    /**
     * Obtiene las direcciones disponibles para selección
     */
    public function getDireccionesDisponibles()
    {
        $builder = $this->db->table('direcciones');
        $builder->select('id, direccion, latitud, longitud');
        $builder->where('activo', 1);
        $builder->orderBy('direccion', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene registros con filtro por dirección
     */
    public function getRegistrosPorDireccion($idDireccion, $fechaDesde = null, $fechaHasta = null)
    {
        $builder = $this->db->table($this->table . ' rc');
        $builder->select('
            rc.*,
            v.placa,
            v.marca,
            v.modelo,
            d.direccion
        ');
        $builder->join('vehiculos v', 'v.id = rc.id_vehiculo', 'left');
        $builder->join('direcciones d', 'd.id = rc.idDireccion', 'left');
        $builder->where('rc.idDireccion', $idDireccion);
        
        if ($fechaDesde) {
            $builder->where('rc.fecha_registro >=', $fechaDesde);
        }
        
        if ($fechaHasta) {
            $builder->where('rc.fecha_registro <=', $fechaHasta);
        }
        
        $builder->orderBy('rc.fecha_registro', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene lista de usuarios únicos que han registrado combustible
     */
    public function getUsuariosRegistros()
    {
        $builder = $this->db->table($this->table);
        $builder->select('usuario_crea as usuario');
        $builder->where('usuario_crea IS NOT NULL');
        $builder->where('usuario_crea !=', '');
        $builder->groupBy('usuario_crea');
        $builder->orderBy('usuario_crea', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}
