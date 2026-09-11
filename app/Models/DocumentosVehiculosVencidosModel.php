<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentosVehiculosVencidosModel extends Model
{
    protected $table = 'vw_documentacion_vehiculo';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id',
        'id_documento',
        'placa',
        'marca',
        'modelo',
        'anio',
        'kilometraje',
        'numero_motor',
        'modelo_motor',
        'idTipoDocumento',
        'nombre_documento',
        'rutaDocumento',
        'fecha_vencimiento',
        'notificacion'
    ];

    protected $useTimestamps = false;

    /**
     * Obtiene documentos de vehículos próximos a vencer según el campo notificacion
     * @param string $filtro Filtro opcional para buscar por placa o modelo
     * @param int $dias Número de días por defecto si no hay campo notificacion (por defecto 30)
     */
    public function getDocumentosVehiculosPorVencer($filtro = null, $dias = 30)
    {
        $sql = "
            SELECT *, 
                   DATEDIFF(fecha_vencimiento, CURDATE()) as dias_restantes,
                   COALESCE(notificacion, ?) as dias_notificacion
            FROM {$this->table}
            WHERE fecha_vencimiento >= CURDATE()
            AND DATEDIFF(fecha_vencimiento, CURDATE()) <= COALESCE(notificacion, ?)
        ";
        
        $params = [$dias, $dias];
        
        // Agregar filtro si se proporciona
        if (!empty($filtro)) {
            $sql .= " AND (placa LIKE ? OR CONCAT(marca, ' ', modelo) LIKE ?)";
            $filtroLike = '%' . $filtro . '%';
            $params[] = $filtroLike;
            $params[] = $filtroLike;
        }
        
        $sql .= " ORDER BY fecha_vencimiento ASC";
        
        $query = $this->db->query($sql, $params);
        return $query->getResultArray();
    }

    /**
     * Obtiene documentos de vehículos que requieren notificación
     * (ya vencidos o próximos a vencer según el campo notificacion)
     * @param string $filtro Filtro opcional para buscar por placa o modelo
     */
    public function getDocumentosVehiculosVencidos($filtro = null)
    {
        $sql = "
            SELECT *, 
                   DATEDIFF(fecha_vencimiento, CURDATE()) as dias_restantes,
                   CASE 
                       WHEN fecha_vencimiento < CURDATE() THEN 'vencido'
                       WHEN DATEDIFF(fecha_vencimiento, CURDATE()) <= COALESCE(notificacion, 30) THEN 'por_vencer'
                       ELSE 'vigente'
                   END as estado_documento
            FROM {$this->table}
            WHERE fecha_vencimiento < CURDATE() 
               OR DATEDIFF(fecha_vencimiento, CURDATE()) <= COALESCE(notificacion, 30)
        ";
        
        $params = [];
        
        // Agregar filtro si se proporciona
        if (!empty($filtro)) {
            $sql .= " AND (placa LIKE ? OR CONCAT(marca, ' ', modelo) LIKE ?)";
            $filtroLike = '%' . $filtro . '%';
            $params[] = $filtroLike;
            $params[] = $filtroLike;
        }
        
        $sql .= " ORDER BY 
                    CASE 
                        WHEN fecha_vencimiento < CURDATE() THEN 1
                        ELSE 2
                    END,
                    fecha_vencimiento ASC";
        
        $query = $this->db->query($sql, $params);
        return $query->getResultArray();
    }

    /**
     * Obtiene documentos de vehículos realmente vencidos (sin considerar notificación)
     * @param string $filtro Filtro opcional para buscar por placa o modelo
     */
    public function getDocumentosRealmenteVencidos($filtro = null)
    {
        $sql = "
            SELECT *, 
                   DATEDIFF(fecha_vencimiento, CURDATE()) as dias_restantes
            FROM {$this->table}
            WHERE fecha_vencimiento < CURDATE()
        ";
        
        $params = [];
        
        // Agregar filtro si se proporciona
        if (!empty($filtro)) {
            $sql .= " AND (placa LIKE ? OR CONCAT(marca, ' ', modelo) LIKE ?)";
            $filtroLike = '%' . $filtro . '%';
            $params[] = $filtroLike;
            $params[] = $filtroLike;
        }
        
        $sql .= " ORDER BY fecha_vencimiento DESC";
        
        $query = $this->db->query($sql, $params);
        return $query->getResultArray();
    }

    /**
     * Obtiene documentos críticos (próximos 7 días)
     * @param string $filtro Filtro opcional para buscar por placa o modelo
     */
    public function getDocumentosVehiculosCriticos($filtro = null)
    {
        $sql = "
            SELECT * 
            FROM {$this->table}
            WHERE fecha_vencimiento <= CURDATE() + INTERVAL 7 DAY
            AND fecha_vencimiento >= CURDATE()
        ";
        
        $params = [];
        
        // Agregar filtro si se proporciona
        if (!empty($filtro)) {
            $sql .= " AND (placa LIKE ? OR CONCAT(marca, ' ', modelo) LIKE ?)";
            $filtroLike = '%' . $filtro . '%';
            $params[] = $filtroLike;
            $params[] = $filtroLike;
        }
        
        $sql .= " ORDER BY fecha_vencimiento ASC";
        
        $query = $this->db->query($sql, $params);
        return $query->getResultArray();
    }

    /**
     * Obtiene todos los documentos con filtros opcionales
     * @param string $filtro Filtro opcional para buscar por placa o modelo
     * @param string $estado Estado del documento: 'vencido', 'critico', 'por_vencer', 'todos'
     * @param int $dias Número de días para filtros de vencimiento
     */
    public function getDocumentosConFiltro($filtro = null, $estado = 'todos', $dias = 30)
    {
        switch ($estado) {
            case 'vencido':
                return $this->getDocumentosVehiculosVencidos($filtro);
            case 'critico':
                return $this->getDocumentosVehiculosCriticos($filtro);
            case 'por_vencer':
                return $this->getDocumentosVehiculosPorVencer($filtro, $dias);
            default:
                $sql = "
                    SELECT * 
                    FROM {$this->table}
                ";
                
                $params = [];
                
                // Agregar filtro si se proporciona
                if (!empty($filtro)) {
                    $sql .= " WHERE (placa LIKE ? OR CONCAT(marca, ' ', modelo) LIKE ?)";
                    $filtroLike = '%' . $filtro . '%';
                    $params[] = $filtroLike;
                    $params[] = $filtroLike;
                }
                
                $sql .= " ORDER BY fecha_vencimiento ASC";
                
                $query = $this->db->query($sql, $params);
                return $query->getResultArray();
        }
    }

    /**
     * Obtiene estadísticas de documentos considerando el campo notificacion
     * @return array Estadísticas de documentos por estado
     */
    public function getEstadisticasConNotificacion()
    {
        $sql = "
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN fecha_vencimiento < CURDATE() THEN 1 ELSE 0 END) as vencidos,
                SUM(CASE 
                    WHEN fecha_vencimiento >= CURDATE() 
                    AND DATEDIFF(fecha_vencimiento, CURDATE()) <= COALESCE(notificacion, 30) 
                    THEN 1 ELSE 0 
                END) as por_vencer,
                SUM(CASE 
                    WHEN fecha_vencimiento >= CURDATE() 
                    AND DATEDIFF(fecha_vencimiento, CURDATE()) > COALESCE(notificacion, 30) 
                    THEN 1 ELSE 0 
                END) as vigentes,
                SUM(CASE 
                    WHEN fecha_vencimiento >= CURDATE() 
                    AND DATEDIFF(fecha_vencimiento, CURDATE()) <= 7 
                    THEN 1 ELSE 0 
                END) as criticos
            FROM {$this->table}
        ";
        
        $query = $this->db->query($sql);
        return $query->getRowArray();
    }
}
