<?php

namespace App\Models;

use CodeIgniter\Model;

class VehiculoModel extends BaseModel
{
    protected $table = 'vehiculos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_empresa',
        'codigo_consecutivo',
        'placa',
        'marca',
        'modelo',
        'anio',
        'kilometraje',
        'id_conductor',
        'estado',
        'motivo_inactividad',
        'fechaRegistro',
        'fechaUpdate',
        'usuarioCrea',
        'usuarioEdita',
        'idTipoUnidad',
        'idTipoOperacion',
        'codigo_unidad',
        'numero_motor',
        'numero_chasis',
        'disponible',
        'compuesto',
        'codigo_centro_costo',
        'id_color',
        'id_tipo_vehiculo',
        'rendimiento',
        'max_combustible',
        'tipo_consumo'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fechaRegistro';
    protected $updatedField = 'fechaUpdate';

    // Callbacks
    protected $allowCallbacks = false;

    // Validation - Se maneja en el controlador para evitar errores de lint
    protected $validationRules = [];
    protected $validationMessages = [];

    /**
     * Crear vehículo con código consecutivo automático
     */
    public function crearVehiculo(array $data)
    {
        try {
            // Agregar campos de auditoría
            $session = session();
            $userId = $session->get('user_id') ?? 1;
            $empresaId = $session->get('empresa_id') ?? 1;

            $data['id_empresa'] = $empresaId;
            $data['usuarioCrea'] = $userId;
            $data['usuarioEdita'] = $userId;
            $data['fechaRegistro'] = date('Y-m-d H:i:s');
            $data['fechaUpdate'] = date('Y-m-d H:i:s');
            $data['estado'] = $data['estado'] ?? 'ACTIVO';

            return $this->insert($data);

        } catch (\Exception $e) {
            log_message('error', 'Error creando vehículo: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener vehículos con información de conductor
     */
    public function getVehiculosConConductor($empresaId = null, $filtros = [])
    {
        $builder = $this->db->table('vehiculos v');
        $builder->select('v.*, 
                         CONCAT(c.nombre, " ", c.apellido) as conductor_nombre,
                         c.dni as conductor_dni,
                         tu.descripcion as tipo_unidad_descripcion,
                         top.descripcion as tipo_operacion_descripcion,
                         col.nombre as color_nombre,
                         tv.descripcion as tipo_vehiculo_descripcion')
                ->join('conductores c', 'v.id_conductor = c.id', 'left')
                ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left')
                ->join('tipo_operacion top', 'v.idTipoOperacion = top.id', 'left')
                ->join('colores col', 'v.id_color = col.id', 'left')
                ->join('tipo_vehiculo tv', 'v.id_tipo_vehiculo = tv.id', 'left');

        if ($empresaId) {
            $builder->where('v.id_empresa', $empresaId);
        }

        // Aplicar filtros
        if (!empty($filtros['estado'])) {
            $builder->where('v.estado', $filtros['estado']);
        }

        if (!empty($filtros['marca'])) {
            $builder->like('v.marca', $filtros['marca']);
        }

        if (!empty($filtros['placa'])) {
            $builder->like('v.placa', $filtros['placa']);
        }

        if (!empty($filtros['search'])) {
            $builder->groupStart()
                    ->like('v.placa', $filtros['search'])
                    ->orLike('v.marca', $filtros['search'])
                    ->orLike('v.modelo', $filtros['search'])
                    ->orLike('CONCAT(c.nombre, " ", c.apellido)', $filtros['search'])
                    ->groupEnd();
        }

        $builder->orderBy('v.fechaRegistro', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener vehículos con información de conductor con paginación del servidor
     */
    public function getVehiculosConConductorPaginado($empresaId = null, $filtros = [], $start = 0, $length = 10)
    {
        log_message('debug', 'getVehiculosConConductorPaginado - EmpresaId: ' . $empresaId);
        log_message('debug', 'getVehiculosConConductorPaginado - Filtros: ' . json_encode($filtros));
        
        // Verificar que tenemos empresa ID
        if (!$empresaId) {
            log_message('error', 'getVehiculosConConductorPaginado - No hay empresaId');
            return [
                'data' => [],
                'total' => 0,
                'filtered' => 0
            ];
        }
        
        // Consulta simple para verificar datos
        $totalRecords = $this->db->table('vehiculos')
                                ->where('id_empresa', $empresaId)
                                ->countAllResults();
        
        log_message('debug', 'Total vehiculos en empresa ' . $empresaId . ': ' . $totalRecords);
        
        if ($totalRecords == 0) {
            log_message('debug', 'No hay vehículos para la empresa ' . $empresaId);
            return [
                'data' => [],
                'total' => 0,
                'filtered' => 0
            ];
        }
        
        // Builder principal - consulta simplificada
        $builder = $this->db->table('vehiculos v');
        $builder->select('v.id, v.codigo_consecutivo, v.codigo_unidad, v.codigo_centro_costo, v.placa, v.marca, v.modelo, v.anio, v.kilometraje, v.estado, v.id_conductor,
                         COALESCE(CONCAT(c.nombre, " ", c.apellido), "") as conductor_nombre')
                ->join('conductores c', 'v.id_conductor = c.id', 'left')
                ->where('v.id_empresa', $empresaId);

        // Aplicar filtros solo si existen
        if (isset($filtros['estado']) && $filtros['estado'] !== '') {
            $builder->where('v.estado', $filtros['estado']);
            log_message('debug', 'Aplicando filtro estado: ' . $filtros['estado']);
        }


        if (isset($filtros['search']) && $filtros['search'] !== '') {
            $builder->groupStart()
                    ->like('v.placa', $filtros['search'])
                    ->orLike('v.marca', $filtros['search'])
                    ->orLike('v.modelo', $filtros['search'])
                    ->orLike('v.codigo_consecutivo', $filtros['search'])
                    ->orLike('v.codigo_unidad', $filtros['search'])
                    ->orLike('v.codigo_centro_costo', $filtros['search'])
                    ->orLike('v.anio', $filtros['search'])
                    ->orLike('v.kilometraje', $filtros['search'])
                    ->orLike('CONCAT(c.nombre, " ", c.apellido)', $filtros['search'])
                    ->groupEnd();
            log_message('debug', 'Aplicando filtro search: ' . $filtros['search']);
        }

        // Contar registros filtrados
        $builderClone = clone $builder;
        $filteredRecords = $builderClone->countAllResults();
        
        log_message('debug', 'Registros filtrados: ' . $filteredRecords);

        // Obtener datos con paginación
        $builder->orderBy('v.id', 'DESC')
                ->limit($length, $start);
        
        $data = $builder->get()->getResultArray();
        
        log_message('debug', 'Registros obtenidos: ' . count($data));
        
        return [
            'data' => $data,
            'total' => $totalRecords,
            'filtered' => $filteredRecords
        ];
    }

    /**
     * Obtener vehículo con información completa
     */
    public function getVehiculoCompleto($id)
    {
        return $this->db->table('vehiculos v')
                       ->select('v.*, 
                                CONCAT(c.nombre, " ", c.apellido) as conductor_nombre,
                                c.dni as conductor_dni,
                                c.fechaIngreso as conductor_fecha_ingreso,
                                e.nombre as empresa_nombre,
                                tu.descripcion as tipo_unidad_descripcion,
                                top.descripcion as tipo_operacion_descripcion,
                                col.nombre as color_nombre,
                                tv.nombre as tipo_vehiculo_descripcion,
                                uc.nombre as usuario_crea_nombre,
                                ue.nombre as usuario_edita_nombre')
                       ->join('conductores c', 'v.id_conductor = c.id', 'left')
                       ->join('empresas e', 'v.id_empresa = e.id', 'left')
                       ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left')
                       ->join('tipo_operacion top', 'v.idTipoOperacion = top.id', 'left')
                       ->join('catalogo col', 'v.id_color = col.id', 'left')
                       ->join('catalogo tv', 'v.id_tipo_vehiculo = tv.id', 'left')
                       ->join('usuarios uc', 'v.usuarioCrea = uc.id', 'left')
                       ->join('usuarios ue', 'v.usuarioEdita = ue.id', 'left')
                       ->where('v.id', $id)
                       ->get()
                       ->getRowArray();
    }

    /**
     * Cambiar estado del vehículo
     */
    public function cambiarEstado($id, $estado, $motivo = null)
    {
        if (!in_array($estado, ['ACTIVO', 'INACTIVO', 'EN REPARACION'])) {
            return ['error' => 'Estado inválido'];
        }

        $session = session();
        $userId = $session->get('user_id') ?? 1;

        $data = [
            'estado' => $estado,
            'motivo_inactividad' => $motivo,
            'usuarioEdita' => $userId,
            'fechaUpdate' => date('Y-m-d H:i:s')
        ];

        $resultado = $this->update($id, $data);

        if ($resultado) {
            // Registrar en historial de estados
            //$this->registrarCambioEstado($id, $estado, $motivo);
            return ['success' => 'Estado actualizado correctamente'];
        }

        return ['error' => 'Error al actualizar el estado'];
    }

    /**
     * Registrar cambio de estado en historial
     */
    private function registrarCambioEstado($vehiculoId, $estado, $motivo)
    {
        $session = session();
        $userId = $session->get('user_id') ?? 1;
        $empresaId = $session->get('empresa_id') ?? 1;

        $historialData = [
            'id_empresa' => $empresaId,
            'id_vehiculo' => $vehiculoId,
            'estado' => $estado,
            'motivo' => $motivo,
            'fecha_inicio' => date('Y-m-d H:i:s'),
            'usuarioCrea' => $userId,
            'usuarioEdita' => $userId,
            'fechaRegistro' => date('Y-m-d H:i:s'),
            'fechaUpdate' => date('Y-m-d H:i:s')
        ];

        $this->db->table('historial_estado_vehiculo')->insert($historialData);
    }

    /**
     * Obtener estadísticas de vehículos por empresa
     */
    public function getEstadisticasVehiculos($empresaId)
    {
        $stats = [];

        // Total de vehículos
        $stats['total'] = $this->where('id_empresa', $empresaId)->countAllResults();

        // Por estado
        $estados = $this->select('estado, COUNT(*) as cantidad')
                       ->where('id_empresa', $empresaId)
                       ->groupBy('estado')
                       ->findAll();

        $stats['por_estado'] = [];
        foreach ($estados as $estado) {
            $stats['por_estado'][$estado['estado']] = $estado['cantidad'];
        }

        // Vehículos sin conductor
        $stats['sin_conductor'] = $this->where('id_empresa', $empresaId)
                                       ->where('id_conductor IS NULL')
                                       ->countAllResults();

        return $stats;
    }

    /**
     * Obtener conductores disponibles para asignar
     */
    public function getConductoresDisponibles($empresaId)
    {
        return $this->db->table('conductores c')
                       ->select('c.id, CONCAT(c.nombre, " ", c.apellido) as nombre_completo, c.dni')
                       ->where('c.id_empresa', $empresaId)
                       ->where('c.estado', 'ACTIVO')
                       ->where('c.id NOT IN (SELECT DISTINCT id_conductor FROM vehiculos WHERE id_conductor IS NOT NULL AND estado = "ACTIVO")', null, false)
                       ->orderBy('c.nombre')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Asignar conductor a vehículo
     */
    public function asignarConductor($vehiculoId, $conductorId)
    {
        $session = session();
        $userId = $session->get('user_id') ?? 1;

        $data = [
            'id_conductor' => $conductorId,
            'usuarioEdita' => $userId,
            'fechaUpdate' => date('Y-m-d H:i:s')
        ];

        return $this->update($vehiculoId, $data);
    }

    /**
     * Obtener historial de estados del vehículo
     */
    public function getHistorialEstados($vehiculoId)
    {
        return $this->db->table('historial_estado_vehiculo h')
                       ->select('h.*, u.nombre as usuario_nombre')
                       ->join('usuarios u', 'h.usuarioCrea = u.id', 'left')
                       ->where('h.id_vehiculo', $vehiculoId)
                       ->orderBy('h.fecha_inicio', 'DESC')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Obtener centros de costo desde SQL Server
     * Consulta la tabla CNT_CENTROS en SQL Server
     */
    public function getCentrosCosto()
    {
        try {
            // Limitar timeout a 3 s para no bloquear la página si SQL Server no responde
            if (function_exists('sqlsrv_configure')) {
                \sqlsrv_configure('LoginTimeout', 3);
            }

            $db = \Config\Database::connect('sqlserver');
            log_message('debug', 'VehiculoModel::getCentrosCosto - Conexión directa establecida');
            
            // Consulta a la tabla CNT_CENTROS
            $sql = "
                SELECT 
                    TIPO_CATALOGO,
                    CODIGO_CENTRO,
                    DESCRIPCION,
                    NOMBRE_ENCARGADO,
                    CONSOLIDA_CENTRO
                FROM CNT_CENTROS 
                WHERE TIPO_CATALOGO IS NOT NULL
                ORDER BY CODIGO_CENTRO
            ";
            
            log_message('debug', 'VehiculoModel::getCentrosCosto - Ejecutando SQL: ' . $sql);
            
            $query = $db->query($sql);
            $centros = $query->getResultArray();
            
            log_message('info', 'Centros de costo obtenidos: ' . count($centros));
            log_message('debug', 'VehiculoModel::getCentrosCosto - Primeros 3 registros: ' . json_encode(array_slice($centros, 0, 3)));
            
            return $centros;
            
        } catch (\Throwable $e) {
            log_message('error', 'Error al obtener centros de costo desde SQL Server: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener centros de costo para select (formato simplificado)
     */
    public function getCentrosCostoParaSelect()
    {
        try {
            log_message('debug', 'VehiculoModel::getCentrosCostoParaSelect - Iniciando');
            
            $centros = $this->getCentrosCosto();
            log_message('debug', 'VehiculoModel::getCentrosCostoParaSelect - Centros obtenidos: ' . count($centros));
            
            if (empty($centros)) {
                return [];
            }
            
            $opciones = [];
            
            foreach ($centros as $centro) {
                $opciones[$centro['CODIGO_CENTRO']] = $centro['CODIGO_CENTRO'] . ' - ' . $centro['DESCRIPCION'];
            }
            
            log_message('debug', 'VehiculoModel::getCentrosCostoParaSelect - Opciones formateadas: ' . count($opciones));
            
            return $opciones;
            
        } catch (\Throwable $e) {
            log_message('error', 'Error al formatear centros de costo para select: ' . $e->getMessage());
            return [];
        }
    }


    /**
     * Buscar centro de costo por código
     */
    public function getCentroCostoPorCodigo($codigo)
    {
        try {
            // Usar conexión directa de CodeIgniter
            $db = \Config\Database::connect('sqlserver');
            log_message('debug', 'VehiculoModel::getCentroCostoPorCodigo - Conexión directa establecida');
            
            $sql = "
                SELECT 
                    TIPO_CATALOGO,
                    CODIGO_CENTRO,
                    DESCRIPCION,
                    NOMBRE_ENCARGADO,
                    CONSOLIDA_CENTRO
                FROM CNT_CENTROS 
                WHERE CODIGO_CENTRO = ?
            ";
            
            $query = $db->query($sql, [$codigo]);
            $centro = $query->getRowArray();
            
            return $centro;
            
        } catch (\Exception $e) {
            log_message('error', 'Error al buscar centro de costo por código: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener colores para select
     */
    public function getColoresParaSelect()
    {
        try {
            $colores = $this->db->table('colores')
                              ->select('id, nombre')
                              ->where('activo', 1)
                              ->orderBy('nombre')
                              ->get()
                              ->getResultArray();
            
            $opciones = [];
            foreach ($colores as $color) {
                $opciones[$color['id']] = $color['nombre'];
            }
            
            return $opciones;
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener colores: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener tipos de vehículo para select
     */
    public function getTiposVehiculoParaSelect()
    {
        try {
            $tipos = $this->db->table('tipo_vehiculo')
                             ->select('id, descripcion')
                             ->where('activo', 1)
                             ->orderBy('descripcion')
                             ->get()
                             ->getResultArray();
            
            $opciones = [];
            foreach ($tipos as $tipo) {
                $opciones[$tipo['id']] = $tipo['descripcion'];
            }
            
            return $opciones;
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener tipos de vehículo: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener vehículos disponibles para asignación (con nuevos campos)
     */
    public function getVehiculosDisponiblesParaAsignacion($empresaId, $tipoUnidadId = null)
    {
        $builder = $this->db->table('vehiculos v');
        $builder->select('v.id, v.placa, v.marca, v.modelo, v.anio, v.kilometraje, 
                         v.disponible, v.compuesto, v.rendimiento, v.max_combustible,
                         tu.descripcion as tipo_unidad_descripcion,
                         top.descripcion as tipo_operacion_descripcion,
                         col.nombre as color_nombre,
                         tv.descripcion as tipo_vehiculo_descripcion')
                ->join('tipo_unidad tu', 'v.idTipoUnidad = tu.id', 'left')
                ->join('tipo_operacion top', 'v.idTipoOperacion = top.id', 'left')
                ->join('colores col', 'v.id_color = col.id', 'left')
                ->join('tipo_vehiculo tv', 'v.id_tipo_vehiculo = tv.id', 'left')
                ->where('v.id_empresa', $empresaId)
                ->where('v.estado', 'ACTIVO')
                ->where('v.disponible', 1)
                ->whereNull('v.id_conductor');

        if ($tipoUnidadId) {
            $builder->where('v.idTipoUnidad', $tipoUnidadId);
        }

        return $builder->orderBy('v.placa')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Validar disponibilidad de vehículo para asignación
     */
    public function validarDisponibilidadVehiculo($vehiculoId)
    {
        $vehiculo = $this->find($vehiculoId);
        
        if (!$vehiculo) {
            return ['valido' => false, 'mensaje' => 'Vehículo no encontrado'];
        }

        if ($vehiculo['estado'] !== 'ACTIVO') {
            return ['valido' => false, 'mensaje' => 'El vehículo no está activo'];
        }

        if ($vehiculo['disponible'] != 1) {
            return ['valido' => false, 'mensaje' => 'El vehículo no está disponible'];
        }

        if (!empty($vehiculo['id_conductor'])) {
            return ['valido' => false, 'mensaje' => 'El vehículo ya tiene un conductor asignado'];
        }

        return ['valido' => true, 'mensaje' => 'Vehículo disponible para asignación'];
    }
}
