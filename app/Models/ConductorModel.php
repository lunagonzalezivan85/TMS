<?php

namespace App\Models;

use CodeIgniter\Model;

class ConductorModel extends Model
{
    protected $table = 'conductores';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_empresa',
        'codigo_consecutivo',
        'nombre',
        'apellido',
        'dni',
        'fechaIngreso',
        'estado',
        'fechaRegistro',
        'fechaUpdate',
        'usuarioCrea',
        'usuarioEdita',
        'carnet'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fechaRegistro';
    protected $updatedField = 'fechaUpdate';
    protected $deletedField = 'deleted_at';

    // Validation (se moverá al controlador para evitar errores de lint)
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
    
    /**
     * Obtener la última consulta SQL ejecutada
     */
    public function getLastQuery()
    {
        return $this->db->getLastQuery();
    }

    /**
     * Obtener conductor completo con información adicional
     */
    public function getConductorCompleto($id)
    {
        return $this->select('conductores.*, 
                             COUNT(vehiculos.id) as total_vehiculos_asignados,
                             empresas.nombre as empresa_nombre')
                    ->join('empresas', 'empresas.id = conductores.id_empresa', 'left')
                    ->join('vehiculos', 'vehiculos.id_conductor = conductores.id AND vehiculos.estado = "ACTIVO"', 'left')
                    ->where('conductores.id', $id)
                    ->groupBy('conductores.id')
                    ->first();
    }

    /**
     * Obtener conductores con filtros y paginación
     */
    public function getConductoresConFiltros($filtros = [], $limit = 10, $offset = 0)
    {
        // Asegurarse de que los parámetros sean enteros
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        // Inicializar el constructor de consultas
        $builder = $this->db->table('conductores');
        
        // Seleccionar solo los campos existentes en la tabla conductores
        $builder->select('conductores.*, 
                         COALESCE(COUNT(DISTINCT vehiculos.id), 0) as vehiculos_asignados,
                         empresas.nombre as nombre_empresa,
                         MAX(CASE WHEN catalogo_doc.nombre LIKE "%LICENCIA%" THEN documentacion_conductor.numero_documento END) as licencia,
                         MAX(CASE WHEN catalogo_doc.nombre LIKE "%LICENCIA%" THEN catalogo_doc.nombre END) as tipo_licencia,
                         MAX(CASE WHEN catalogo_doc.nombre LIKE "%LICENCIA%" THEN documentacion_conductor.fecha_vencimiento END) as fechaVencimientoLicencia')
                ->join('vehiculos', 'vehiculos.id_conductor = conductores.id AND vehiculos.estado = "ACTIVO"', 'left')
                ->join('empresas', 'empresas.id = conductores.id_empresa', 'left')
                ->join('documentacion_conductor', 'documentacion_conductor.idConductor = conductores.id', 'left')
                ->join('catalogo catalogo_doc', 'catalogo_doc.id = documentacion_conductor.tipo_documento', 'left')
                ->groupBy('conductores.id, empresas.nombre, conductores.codigo_consecutivo, conductores.nombre, conductores.apellido, 
                         conductores.dni, conductores.fechaIngreso, conductores.estado, conductores.fechaRegistro, 
                         conductores.fechaUpdate, conductores.usuarioCrea, conductores.usuarioEdita, conductores.carnet');

        // Aplicar filtros
        if (!empty($filtros['empresa_id'])) {
            $builder->where('conductores.id_empresa', $filtros['empresa_id']);
        } else {
            // Si no hay empresa_id, mostrar todos los conductores (para debugging)
            log_message('debug', 'Mostrando todos los conductores sin filtro de empresa');
        }

        if (!empty($filtros['estado'])) {
            $builder->where('conductores.estado', $filtros['estado']);
        }

        if (!empty($filtros['search'])) {
            $builder->groupStart()
                    ->like('conductores.nombre', $filtros['search'])
                    ->orLike('conductores.apellido', $filtros['search'])
                    ->orLike('conductores.dni', $filtros['search'])
                    ->orLike('conductores.codigo_consecutivo', $filtros['search'])
                    ->orLike('conductores.carnet', $filtros['search'])
                    ->orLike('documentacion_conductor.numero_documento', $filtros['search'])
                    ->groupEnd();
        }

        $builder->orderBy('conductores.fechaRegistro', 'DESC');
        
        // Aplicar límite y offset solo si se especifican
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        // Ejecutar la consulta y devolver los resultados como array
        return $builder->get()->getResultArray();
    }

    /**
     * Contar conductores con filtros
     */
    public function contarConductoresConFiltros($filtros = [])
    {
        // Inicializar el constructor de consultas
        $builder = $this->db->table('conductores');
        
        // Seleccionar solo el ID para contar
        $builder->select('conductores.id')
                ->join('empresas', 'empresas.id = conductores.id_empresa', 'left')
                ->join('documentacion_conductor', 'documentacion_conductor.idConductor = conductores.id', 'left')
                ->groupBy('conductores.id'); // Agrupar por ID para evitar duplicados

        // Aplicar filtros
        if (!empty($filtros['empresa_id'])) {
            $builder->where('conductores.id_empresa', $filtros['empresa_id']);
        } else {
            // Si no hay empresa_id, contar todos los conductores (para debugging)
            log_message('debug', 'Contando todos los conductores sin filtro de empresa');
        }

        if (!empty($filtros['estado'])) {
            $builder->where('conductores.estado', $filtros['estado']);
        }

        if (!empty($filtros['search'])) {
            $searchTerm = $filtros['search'];
            $builder->groupStart()
                    ->like('conductores.nombre', $searchTerm)
                    ->orLike('conductores.apellido', $searchTerm)
                    ->orLike('conductores.dni', $searchTerm)
                    ->orLike('conductores.codigo_consecutivo', $searchTerm)
                    ->orLike('conductores.carnet', $searchTerm)
                    ->orLike('documentacion_conductor.numero_documento', $searchTerm)
                    ->orLike('empresas.nombre', $searchTerm)
                    ->groupEnd();
        }

        // Contar resultados
        return $builder->countAllResults();
    }

    /**
     * Generar código consecutivo para conductor
     */
    public function generarCodigoConductor($empresaId)
    {
        $ultimoCodigo = $this->select('codigo_consecutivo')
                           ->where('id_empresa', $empresaId)
                           ->orderBy('codigo_consecutivo', 'DESC')
                           ->first();

        if ($ultimoCodigo && !empty($ultimoCodigo['codigo_consecutivo'])) {
            $numero = intval(str_replace('COND-', '', $ultimoCodigo['codigo_consecutivo'])) + 1;
        } else {
            $numero = 1;
        }

        return 'COND-' . str_pad($numero, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Verificar si DNI ya existe
     */
    public function existeDni($dni, $empresaId, $excludeId = null)
    {
        $builder = $this->where('dni', $dni)
                        ->where('id_empresa', $empresaId);

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->first() !== null;
    }

    /**
     * Obtener conductores disponibles (sin vehículo asignado o con estado ACTIVO)
     */
    public function getConductoresDisponibles($empresaId)
    {
        return $this->select('conductores.id, conductores.codigo_consecutivo, conductores.nombre, conductores.apellido, conductores.dni')
                    ->where('conductores.id_empresa', $empresaId)
                    ->where('conductores.estado', 'ACTIVO')
                    ->whereNotIn('conductores.id', function($builder) {
                        return $builder->select('id_conductor')
                                      ->from('vehiculos')
                                      ->where('id_conductor IS NOT NULL')
                                      ->where('estado', 'ACTIVO');
                    })
                    ->orderBy('conductores.nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener estadísticas de conductores por empresa
     */
    public function getEstadisticasConductores($empresaId)
    {
        $db = \Config\Database::connect();
        
        $stats = [
            'total_conductores' => 0,
            'conductores_activos' => 0,
            'conductores_inactivos' => 0,
            'conductores_con_vehiculo' => 0,
            'conductores_sin_vehiculo' => 0,
            'nuevos_este_mes' => 0
        ];

        // Total de conductores
        $stats['total_conductores'] = $this->where('id_empresa', $empresaId)->countAllResults();

        // Conductores por estado
        $stats['conductores_activos'] = $this->where('id_empresa', $empresaId)
                                           ->where('estado', 'ACTIVO')
                                           ->countAllResults();

        $stats['conductores_inactivos'] = $this->where('id_empresa', $empresaId)
                                            ->where('estado', 'INACTIVO')
                                            ->countAllResults();

        // Conductores con y sin vehículo
        $query = $db->query("
            SELECT 
                COUNT(CASE WHEN v.id IS NOT NULL THEN 1 END) as con_vehiculo,
                COUNT(CASE WHEN v.id IS NULL THEN 1 END) as sin_vehiculo
            FROM conductores c
            LEFT JOIN vehiculos v ON c.id = v.id_conductor AND v.estado = 'ACTIVO'
            WHERE c.id_empresa = ? AND c.estado = 'ACTIVO'
        ", [$empresaId]);

        $resultado = $query->getRow();
        if ($resultado) {
            $stats['conductores_con_vehiculo'] = $resultado->con_vehiculo;
            $stats['conductores_sin_vehiculo'] = $resultado->sin_vehiculo;
        }

        // Nuevos conductores este mes
        $inicioMes = date('Y-m-01');
        $stats['nuevos_este_mes'] = $this->where('id_empresa', $empresaId)
                                       ->where('fechaRegistro >=', $inicioMes)
                                       ->countAllResults();

        return $stats;
    }

    /**
     * Obtener todos los carnets concatenados en formato 'carnet1','carnet2',...
     */
    public function obtenerCarnetsConcatenados(): string
    {
        try {
            $result = $this->db->table($this->table)
                ->select("GROUP_CONCAT(CONCAT('\'', carnet, '\'')) AS Codigos", false)
                ->get()
                ->getRowArray();

            return $result['Codigos'] ?? '';
        } catch (\Throwable $e) {
            log_message('error', 'Error al obtener carnets concatenados: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Obtener historial de cambios de estado del conductor
     * Nota: Esta tabla puede no existir aún, se puede implementar después
     */
    public function getHistorialEstados($conductorId)
    {
        $db = \Config\Database::connect();
        
        // Verificar si la tabla existe
        if (!$db->tableExists('historial_estados_conductor')) {
            return [];
        }
        
        return $db->table('historial_estados_conductor')
                  ->select('historial_estados_conductor.*, usuarios.nombres as usuario_nombre')
                  ->join('usuarios', 'usuarios.id = historial_estados_conductor.usuario_id', 'left')
                  ->where('conductor_id', $conductorId)
                  ->orderBy('fecha_cambio', 'DESC')
                  ->get()
                  ->getResultArray();
    }
}
