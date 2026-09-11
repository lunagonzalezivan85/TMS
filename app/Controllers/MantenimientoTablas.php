<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\Config;

class MantenimientoTablas extends Controller
{
    protected $db;
    
    public function __construct()
    {
        // Conectar a SQL Server
        $this->db = \Config\Database::connect('sqlserver');
    }

    /**
     * Página principal - Lista de tablas
     */
    public function index()
    {
        try {
            // Obtener lista de tablas de SQL Server
            $query = "SELECT TABLE_NAME, TABLE_TYPE 
                     FROM INFORMATION_SCHEMA.TABLES 
                     WHERE TABLE_TYPE = 'BASE TABLE' 
                     ORDER BY TABLE_NAME";
            
            $tablas = $this->db->query($query)->getResultArray();
            
            $data = [
                'title' => 'Mantenimiento de Tablas - SQL Server',
                'tablas' => $tablas,
                'total_tablas' => count($tablas)
            ];

            return view('mantenimiento_tablas/index', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener tablas: ' . $e->getMessage());
            
            $data = [
                'title' => 'Mantenimiento de Tablas - Error',
                'error' => 'Error al conectar con la base de datos: ' . $e->getMessage(),
                'tablas' => [],
                'total_tablas' => 0
            ];

            return view('mantenimiento_tablas/index', $data);
        }
    }

    /**
     * Ver datos de una tabla específica (versión simple sin paginación avanzada)
     */
    /**
     * Obtener lista de tablas disponibles
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function obtenerTablas()
    {
        return $this->obtenerObjetos('BASE TABLE');
    }

    /**
     * Obtener lista de vistas disponibles
     */
    public function obtenerVistas()
    {
        return $this->obtenerObjetos('VIEW');
    }

    /**
     * Método interno: obtiene tablas o vistas con filtros por campo y operador
     */
    private function obtenerObjetos(string $tipo)
    {
        $pagina    = max(1, (int)($this->request->getGet('pagina')    ?? 1));
        $porPagina = max(1, (int)($this->request->getGet('por_pagina') ?? 15));
        $offset    = ($pagina - 1) * $porPagina;

        $buscar = trim($this->request->getGet('buscar') ?? '');

        $whereClauses = ["t.TABLE_TYPE = '{$tipo}'"];
        if ($buscar !== '') {
            $like = $this->db->escapeLikeString($buscar);
            $whereClauses[] = "t.TABLE_NAME LIKE '%{$like}%' ESCAPE '\\'";
        }

        $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);

        try {
            $total = (int)$this->db->query(
                "SELECT COUNT(*) as total
                 FROM INFORMATION_SCHEMA.TABLES t
                 {$whereSQL}"
            )->getRow()->total;

            $rows = $this->db->query(
                "SELECT
                    t.TABLE_NAME,
                    t.TABLE_SCHEMA,
                    NULL AS row_count
                 FROM INFORMATION_SCHEMA.TABLES t
                 {$whereSQL}
                 ORDER BY t.TABLE_NAME
                 OFFSET {$offset} ROWS FETCH NEXT {$porPagina} ROWS ONLY"
            )->getResultArray();

            return $this->response->setJSON([
                'success'    => true,
                'data'       => $rows,
                'pagination' => [
                    'total'        => $total,
                    'per_page'     => $porPagina,
                    'current_page' => $pagina,
                    'last_page'    => (int)ceil($total / $porPagina),
                ],
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en obtenerObjetos: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Buscar datos en una tabla específica
     */
    public function buscarDatos($nombreTabla = null)
    {
        // Verificar si es una petición AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Solicitud no válida'
            ]);
        }

        if (!$nombreTabla) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Debe especificar el nombre de la tabla'
            ]);
        }

        try {
            // Verificar que la tabla existe
            if (!$this->existeTabla($nombreTabla)) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'La tabla especificada no existe'
                ]);
            }

            // Obtener parámetros de búsqueda y paginación
            $buscar = $this->request->getGet('buscar') ?? '';
            $pagina = (int)($this->request->getGet('pagina') ?? 1);
            $porPagina = 10;
            $offset = ($pagina - 1) * $porPagina;

            // Obtener columnas de la tabla
            $columnas = $this->obtenerColumnasTabla($nombreTabla);
            $columnasBusqueda = [];
            $columnasMostrar = [];
            
            // Identificar columnas de búsqueda y visualización
            foreach ($columnas as $col) {
                $columnasBusqueda[] = $col['COLUMN_NAME'];
                if (in_array($col['DATA_TYPE'], ['varchar', 'nvarchar', 'char', 'nchar', 'text', 'ntext', 'int', 'bigint', 'decimal', 'numeric', 'date', 'datetime'])) {
                    $columnasMostrar[] = $col['COLUMN_NAME'];
                }
            }
            
            // Construir la consulta
            $selectColumns = implode(', ', array_map(function($col) {
                return "[" . $col . "]";
            }, $columnasBusqueda));
            
            $query = "SELECT $selectColumns FROM [{$nombreTabla}] ";
            
            // Aplicar filtro de búsqueda si existe
            if (!empty($buscar)) {
                $conditions = [];
                foreach ($columnasBusqueda as $col) {
                    $conditions[] = "[$col] LIKE '%" . $this->db->escapeLikeString($buscar) . "%' ESCAPE '\\' ";
                }
                $query .= " WHERE " . implode(' OR ', $conditions);
            }
            
            // Contar total de registros
            $countQuery = "SELECT COUNT(*) as total FROM [{$nombreTabla}]";
            if (!empty($buscar)) {
                $countQuery .= " WHERE " . implode(' OR ', $conditions);
            }
            $total = $this->db->query($countQuery)->getRow()->total;
            
            // Aplicar paginación
            $query .= " ORDER BY [" . $columnasBusqueda[0] . "] ";
            $query .= " OFFSET $offset ROWS FETCH NEXT $porPagina ROWS ONLY";
            
            $resultados = $this->db->query($query)->getResultArray();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $resultados,
                'columnas' => $columnasMostrar,
                'pagination' => [
                    'total' => (int)$total,
                    'per_page' => $porPagina,
                    'current_page' => $pagina,
                    'last_page' => ceil($total / $porPagina)
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en buscarDatos: ' . $e->getMessage());
            
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error al buscar datos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Ver datos de una tabla específica (versión simple sin paginación avanzada)
     */
    public function verTablaSimple($nombreTabla = null)
    {
        if (!$nombreTabla) {
            return redirect()->to('/mantenimiento-tablas')
                           ->with('error', 'Debe especificar el nombre de la tabla');
        }

        try {
            // Validar que la tabla existe
            if (!$this->existeTabla($nombreTabla)) {
                return redirect()->to('/mantenimiento-tablas')
                               ->with('error', 'La tabla especificada no existe');
            }

            // Obtener información de las columnas
            $columnas = $this->obtenerColumnasTabla($nombreTabla);
            
            // Consulta simple para contar registros
            $countQuery = "SELECT COUNT(*) as total FROM [{$nombreTabla}]";
            $totalRegistros = $this->db->query($countQuery)->getRow()->total;
            
            // Consulta simple para obtener datos (primeros 100 registros)
            $dataQuery = "SELECT TOP 100 * FROM [{$nombreTabla}]";
            $datos = $this->db->query($dataQuery)->getResultArray();
            
            $data = [
                'title' => "Tabla: {$nombreTabla} (Vista Simple)",
                'nombre_tabla' => $nombreTabla,
                'columnas' => $columnas,
                'datos' => $datos,
                'total_registros' => $totalRegistros,
                'es_vista_simple' => true
            ];

            return view('mantenimiento_tablas/ver_tabla_simple', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al consultar tabla simple ' . $nombreTabla . ': ' . $e->getMessage());
            
            return redirect()->to('/mantenimiento-tablas')
                           ->with('error', 'Error al consultar la tabla: ' . $e->getMessage());
        }
    }

    /**
     * Ver datos de una tabla específica
     */
    public function verTabla($nombreTabla = null)
    {
        if (!$nombreTabla) {
            return redirect()->to('/mantenimiento-tablas')
                           ->with('error', 'Debe especificar el nombre de la tabla');
        }

        try {
            if (!$this->existeTabla($nombreTabla)) {
                return redirect()->to('/mantenimiento-tablas')
                                 ->with('error', 'La tabla especificada no existe');
            }

            $columnas  = $this->obtenerColumnasTabla($nombreTabla);
            $colNames  = array_column($columnas, 'COLUMN_NAME');

            // Parámetros
            $page     = max(1, (int)($this->request->getGet('page')      ?? 1));
            $perPage  = max(1, (int)($this->request->getGet('per_page')  ?? 25));
            $orderBy  = $this->request->getGet('order_by')  ?? '';
            $orderDir = strtoupper($this->request->getGet('order_dir') ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';

            // Filtros por columna
            $colFiltros = $this->request->getGet('col') ?? [];
            $opFiltros  = $this->request->getGet('op')  ?? [];
            $valFiltros = $this->request->getGet('val') ?? [];

            // Construir cláusulas WHERE
            $whereClauses = [];
            $opMap = [
                'like'    => fn($c, $v) => "[{$c}] LIKE '%" . $this->db->escapeLikeString($v) . "%' ESCAPE '\\'",
                'eq'      => fn($c, $v) => "[{$c}] = '" . $this->db->escapeString($v) . "'",
                'neq'     => fn($c, $v) => "[{$c}] <> '" . $this->db->escapeString($v) . "'",
                'gt'      => fn($c, $v) => "[{$c}] > '" . $this->db->escapeString($v) . "'",
                'gte'     => fn($c, $v) => "[{$c}] >= '" . $this->db->escapeString($v) . "'",
                'lt'      => fn($c, $v) => "[{$c}] < '" . $this->db->escapeString($v) . "'",
                'lte'     => fn($c, $v) => "[{$c}] <= '" . $this->db->escapeString($v) . "'",
                'null'    => fn($c, $v) => "[{$c}] IS NULL",
                'notnull' => fn($c, $v) => "[{$c}] IS NOT NULL",
            ];

            foreach ($colFiltros as $i => $col) {
                $col = trim($col);
                if (empty($col) || !in_array($col, $colNames)) continue;
                $op  = $opFiltros[$i]  ?? 'like';
                $val = $valFiltros[$i] ?? '';
                if (!in_array($op, ['null', 'notnull']) && $val === '') continue;
                if (isset($opMap[$op])) {
                    $whereClauses[] = $opMap[$op]($col, $val);
                }
            }

            $whereSQL = !empty($whereClauses) ? ' WHERE ' . implode(' AND ', $whereClauses) : '';

            // ORDER BY
            $orderSQL = '';
            if (!empty($orderBy) && in_array($orderBy, $colNames)) {
                $orderSQL = " ORDER BY [{$orderBy}] {$orderDir}";
            } elseif (!empty($columnas)) {
                $orderSQL = " ORDER BY [{$colNames[0]}] ASC";
            } else {
                $orderSQL = " ORDER BY (SELECT NULL)";
            }

            $offset = ($page - 1) * $perPage;

            $totalRegistros = (int)$this->db->query(
                "SELECT COUNT(*) as total FROM [{$nombreTabla}]{$whereSQL}"
            )->getRow()->total;

            $datos = $this->db->query(
                "SELECT * FROM [{$nombreTabla}]{$whereSQL}{$orderSQL}
                 OFFSET {$offset} ROWS FETCH NEXT {$perPage} ROWS ONLY"
            )->getResultArray();

            $totalPaginas = max(1, (int)ceil($totalRegistros / $perPage));

            // ── Resumen: sumar columnas que contengan Monto o Saldo ──────────
            $tiposNumericos = ['int','bigint','smallint','tinyint','decimal','numeric','float','real','money','smallmoney'];
            $sumasMontos = [];
            foreach ($columnas as $col) {
                $nombre = $col['COLUMN_NAME'];
                $esNumerico = in_array(strtolower($col['DATA_TYPE']), $tiposNumericos);
                $esMonto = $esNumerico && preg_match('/monto|saldo/i', $nombre);
                if ($esMonto) {
                    $row = $this->db->query(
                        "SELECT SUM([{$nombre}]) as total FROM [{$nombreTabla}]{$whereSQL}"
                    )->getRow();
                    $sumasMontos[$nombre] = $row ? (float)$row->total : 0.0;
                }
            }

            // ── Resumen: contar por Estado si existe la columna ──────────────
            $conteoEstados = [];
            $colEstado = null;
            foreach ($columnas as $col) {
                if (preg_match('/^(estado|status)$/i', $col['COLUMN_NAME'])) {
                    $colEstado = $col['COLUMN_NAME'];
                    break;
                }
            }
            $montosPorEstado = [];
            $colSaldoEstado  = null;
            if ($colEstado) {
                $rows = $this->db->query(
                    "SELECT [{$colEstado}] as estado, COUNT(*) as total
                     FROM [{$nombreTabla}]{$whereSQL}
                     GROUP BY [{$colEstado}]
                     ORDER BY total DESC"
                )->getResultArray();
                foreach ($rows as $r) {
                    $conteoEstados[$r['estado'] ?? 'NULL'] = (int)$r['total'];
                }

                // Suma de saldo/monto por cada estado — preferir columna con 'Saldo' sobre 'Monto'
                if (!empty($sumasMontos)) {
                    $colSaldoEstado = null;
                    foreach (array_keys($sumasMontos) as $k) {
                        if (preg_match('/saldo/i', $k)) { $colSaldoEstado = $k; break; }
                    }
                    if ($colSaldoEstado === null) {
                        $colSaldoEstado = array_key_first($sumasMontos);
                    }
                    $rowsMontos = $this->db->query(
                        "SELECT [{$colEstado}] as estado, SUM([{$colSaldoEstado}]) as monto
                         FROM [{$nombreTabla}]{$whereSQL}
                         GROUP BY [{$colEstado}]
                         ORDER BY monto DESC"
                    )->getResultArray();
                    foreach ($rowsMontos as $r) {
                        $montosPorEstado[$r['estado'] ?? 'NULL'] = (float)$r['monto'];
                    }
                }
            }

            // ── Porcentaje de vencimiento: SaldoVencido / SaldoTotal ─────────
            // Usa la MISMA columna que montosPorEstado para el total
            $porcentajeVencimiento = null;
            if (!empty($montosPorEstado) && $colSaldoEstado !== null) {
                // Total = SUM de esa columna sin filtro de estado
                $rowTotal = $this->db->query(
                    "SELECT SUM([{$colSaldoEstado}]) as total FROM [{$nombreTabla}]{$whereSQL}"
                )->getRow();
                $saldoTotal = $rowTotal ? (float)$rowTotal->total : 0.0;

                $saldoVencido = 0.0;
                foreach ($montosPorEstado as $est => $mnt) {
                    if (strtoupper(trim((string)$est)) === 'VENCIDO') {
                        $saldoVencido = $mnt;
                        break;
                    }
                }

                if ($saldoTotal != 0) {
                    $porcentajeVencimiento = ($saldoVencido / $saldoTotal) * 100;
                }
            }

            $data = [
                'title'        => "Tabla: {$nombreTabla}",
                'nombre_tabla' => $nombreTabla,
                'columnas'     => $columnas,
                'datos'        => $datos,
                'sumas_montos'           => $sumasMontos,
                'conteo_estados'         => $conteoEstados,
                'montos_por_estado'      => $montosPorEstado,
                'porcentaje_vencimiento' => $porcentajeVencimiento,
                'saldo_vencido'          => $saldoVencido ?? 0.0,
                'saldo_total_pct'        => $saldoTotal   ?? 0.0,
                'paginacion'   => [
                    'page'            => $page,
                    'per_page'        => $perPage,
                    'total_registros' => $totalRegistros,
                    'total_paginas'   => $totalPaginas,
                    'tiene_anterior'  => $page > 1,
                    'tiene_siguiente' => $page < $totalPaginas,
                ],
                'filtros' => [
                    'col'       => $colFiltros,
                    'op'        => $opFiltros,
                    'val'       => $valFiltros,
                    'order_by'  => $orderBy,
                    'order_dir' => $orderDir,
                ],
            ];

            return view('mantenimiento_tablas/ver_tabla', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error al consultar tabla ' . $nombreTabla . ': ' . $e->getMessage());
            return redirect()->to('/mantenimiento-tablas')
                             ->with('error', 'Error al consultar la tabla: ' . $e->getMessage());
        }
    }

    /**
     * API para obtener datos de tabla en formato JSON
     */
    public function apiDatosTabla($nombreTabla = null)
    {
        if (!$nombreTabla || !$this->existeTabla($nombreTabla)) {
            return $this->response->setJSON([
                'error' => 'Tabla no válida',
                'data' => []
            ]);
        }

        try {
            $page = $this->request->getGet('page') ?? 1;
            $perPage = min($this->request->getGet('per_page') ?? 25, 100); // Máximo 100 registros
            $search = $this->request->getGet('search') ?? '';
            
            $offset = ($page - 1) * $perPage;
            
            // Consulta con filtros
            $query = "SELECT * FROM [{$nombreTabla}]";
            
            if (!empty($search)) {
                $columnas = $this->obtenerColumnasTabla($nombreTabla);
                $whereConditions = [];
                foreach ($columnas as $columna) {
                    if (in_array($columna['DATA_TYPE'], ['varchar', 'nvarchar', 'char', 'nchar', 'text', 'ntext'])) {
                        $whereConditions[] = "[{$columna['COLUMN_NAME']}] LIKE '%{$search}%'";
                    }
                }
                if (!empty($whereConditions)) {
                    $query .= " WHERE " . implode(' OR ', $whereConditions);
                }
            }
            
            // Contar total
            $countQuery = str_replace('SELECT *', 'SELECT COUNT(*) as total', $query);
            $total = $this->db->query($countQuery)->getRow()->total;
            
            // Agregar paginación
            $query .= " ORDER BY (SELECT NULL) OFFSET {$offset} ROWS FETCH NEXT {$perPage} ROWS ONLY";
            
            $datos = $this->db->query($query)->getResultArray();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $datos,
                'pagination' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => ceil($total / $perPage)
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Error al consultar datos: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    /**
     * Obtener estructura de una tabla
     */
    public function estructuraTabla($nombreTabla = null)
    {
        if (!$nombreTabla || !$this->existeTabla($nombreTabla)) {
            return redirect()->to('/mantenimiento-tablas')
                           ->with('error', 'Tabla no válida');
        }

        try {
            $columnas = $this->obtenerColumnasTabla($nombreTabla);
            $indices = $this->obtenerIndicesTabla($nombreTabla);
            
            $data = [
                'title' => "Estructura: {$nombreTabla}",
                'nombre_tabla' => $nombreTabla,
                'columnas' => $columnas,
                'indices' => $indices
            ];

            return view('mantenimiento_tablas/estructura', $data);
            
        } catch (\Exception $e) {
            return redirect()->to('/mantenimiento-tablas')
                           ->with('error', 'Error al obtener estructura: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si una tabla existe
     */
    private function existeTabla($nombreTabla)
    {
        $query = "SELECT COUNT(*) as existe 
                 FROM INFORMATION_SCHEMA.TABLES 
                 WHERE TABLE_NAME = ? AND TABLE_TYPE IN ('BASE TABLE', 'VIEW')";
        
        $result = $this->db->query($query, [$nombreTabla])->getRow();
        return $result->existe > 0;
    }

    /**
     * Obtener información de las columnas de una tabla
     */
    private function obtenerColumnasTabla($nombreTabla)
    {
        $query = "SELECT 
                    COLUMN_NAME,
                    DATA_TYPE,
                    CHARACTER_MAXIMUM_LENGTH,
                    IS_NULLABLE,
                    COLUMN_DEFAULT,
                    ORDINAL_POSITION
                  FROM INFORMATION_SCHEMA.COLUMNS 
                  WHERE TABLE_NAME = ? 
                  ORDER BY ORDINAL_POSITION";
        
        return $this->db->query($query, [$nombreTabla])->getResultArray();
    }

    /**
     * Obtener índices de una tabla
     */
    private function obtenerIndicesTabla($nombreTabla)
    {
        $query = "SELECT 
                    i.name as INDEX_NAME,
                    i.type_desc as INDEX_TYPE,
                    i.is_unique as IS_UNIQUE,
                    c.name as COLUMN_NAME
                  FROM sys.indexes i
                  INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
                  INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
                  INNER JOIN sys.tables t ON i.object_id = t.object_id
                  WHERE t.name = ?
                  ORDER BY i.name, ic.key_ordinal";
        
        return $this->db->query($query, [$nombreTabla])->getResultArray();
    }

    /**
     * Verificar si una columna es válida para ordenamiento
     */
    private function esColumnaValida($nombreTabla, $nombreColumna)
    {
        $query = "SELECT COUNT(*) as existe 
                 FROM INFORMATION_SCHEMA.COLUMNS 
                 WHERE TABLE_NAME = ? AND COLUMN_NAME = ?";
        
        $result = $this->db->query($query, [$nombreTabla, $nombreColumna])->getRow();
        return $result->existe > 0;
    }

    /**
     * Método de debug para probar consultas simples
     */
    public function debugTabla($nombreTabla = null)
    {
        if (!$nombreTabla) {
            return $this->response->setJSON(['error' => 'Nombre de tabla requerido']);
        }

        try {
            // Prueba 1: Verificar conexión
            $testConnection = $this->db->query("SELECT 1 as test")->getRow();
            
            // Prueba 2: Verificar si la tabla existe
            $existeTabla = $this->existeTabla($nombreTabla);
            
            // Prueba 3: Contar registros con consulta simple
            $countQuery = "SELECT COUNT(*) as total FROM [{$nombreTabla}]";
            $countResult = $this->db->query($countQuery)->getRow();
            $totalRegistros = $countResult ? $countResult->total : 0;
            
            // Prueba 4: Obtener primeros 5 registros
            $dataQuery = "SELECT TOP 5 * FROM [{$nombreTabla}]";
            $datos = $this->db->query($dataQuery)->getResultArray();
            
            // Prueba 5: Obtener información de columnas
            $columnas = $this->obtenerColumnasTabla($nombreTabla);
            
            return $this->response->setJSON([
                'conexion' => $testConnection ? 'OK' : 'ERROR',
                'tabla_existe' => $existeTabla,
                'total_registros' => $totalRegistros,
                'primeros_5_registros' => $datos,
                'total_columnas' => count($columnas),
                'columnas' => array_column($columnas, 'COLUMN_NAME'),
                'query_count' => $countQuery,
                'query_data' => $dataQuery
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Exportar datos de tabla a CSV
     */
    public function exportarCSV($nombreTabla = null)
    {
        if (!$nombreTabla || !$this->existeTabla($nombreTabla)) {
            return redirect()->to('/mantenimiento-tablas')
                           ->with('error', 'Tabla no válida para exportar');
        }

        try {
            // Obtener todos los datos (limitado a 10000 registros por seguridad)
            $query = "SELECT TOP 10000 * FROM [{$nombreTabla}]";
            $datos = $this->db->query($query)->getResultArray();
            
            if (empty($datos)) {
                return redirect()->back()->with('error', 'No hay datos para exportar');
            }

            // Configurar headers para descarga
            $filename = $nombreTabla . '_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');

            $output = fopen('php://output', 'w');
            
            // Escribir encabezados
            fputcsv($output, array_keys($datos[0]));
            
            // Escribir datos
            foreach ($datos as $row) {
                fputcsv($output, $row);
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al exportar: ' . $e->getMessage());
        }
    }
    
    /**
     * Buscar en una tabla de referencia con paginación
     */
    public function buscarReferencia()
    {
        $tabla = $this->request->getGet('tabla');
        $busqueda = $this->request->getGet('buscar') ?? '';
        $pagina = $this->request->getGet('pagina') ?? 1;
        $porPagina = $this->request->getGet('por_pagina') ?? 10;
        
        if (empty($tabla)) {
            return $this->response->setJSON([
                'error' => 'El parámetro "tabla" es requerido',
                'data' => []
            ]);
        }
        
        try {
            $offset = ($pagina - 1) * $porPagina;
            
            // Consulta para contar total de registros
            $countQuery = "SELECT COUNT(*) as total FROM [{$tabla}]";
            $whereConditions = [];
            
            if (!empty($busqueda)) {
                $columnas = $this->obtenerColumnasTabla($tabla);
                foreach ($columnas as $columna) {
                    if (in_array(strtolower($columna['DATA_TYPE']), ['varchar', 'nvarchar', 'char', 'nchar', 'text', 'ntext'])) {
                        $whereConditions[] = "[{$columna['COLUMN_NAME']}] LIKE '%{$busqueda}%'";
                    }
                }
                if (!empty($whereConditions)) {
                    $countQuery .= " WHERE " . implode(' OR ', $whereConditions);
                }
            }
            
            $total = $this->db->query($countQuery)->getRow()->total;
            
            // Consulta para obtener datos
            $dataQuery = "SELECT * FROM [{$tabla}]";
            
            if (!empty($whereConditions)) {
                $dataQuery .= " WHERE " . implode(' OR ', $whereConditions);
            }
            
            // Agregar ordenamiento por la primera columna
            $columnas = $this->obtenerColumnasTabla($tabla);
            if (!empty($columnas)) {
                $dataQuery .= " ORDER BY [{$columnas[0]['COLUMN_NAME']}] ASC";
            }
            
            // Agregar paginación
            $dataQuery .= " OFFSET {$offset} ROWS FETCH NEXT {$porPagina} ROWS ONLY";
            
            $datos = $this->db->query($dataQuery)->getResultArray();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $datos,
                'paginacion' => [
                    'pagina_actual' => (int)$pagina,
                    'por_pagina' => (int)$porPagina,
                    'total' => (int)$total,
                    'total_paginas' => ceil($total / $porPagina)
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en buscarReferencia: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al realizar la búsqueda: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }
    
    /**
     * Obtener datos de referencia por ID
     */
    public function obtenerReferencia()
    {
        $tabla = $this->request->getGet('tabla');
        $id = $this->request->getGet('id');
        $campoId = $this->request->getGet('campo_id') ?? 'id';
        
        if (empty($tabla) || empty($id)) {
            return $this->response->setJSON([
                'error' => 'Los parámetros "tabla" e "id" son requeridos',
                'data' => null
            ]);
        }
        
        try {
            $query = "SELECT * FROM [{$tabla}] WHERE [{$campoId}] = ?";
            $dato = $this->db->query($query, [$id])->getRowArray();
            
            if (!$dato) {
                return $this->response->setJSON([
                    'error' => 'No se encontró el registro',
                    'data' => null
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $dato
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en obtenerReferencia: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al obtener el registro: ' . $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Obtener columnas de una tabla
     */
    public function obtenerColumnas()
    {
        $tabla = $this->request->getGet('tabla');
        
        if (empty($tabla)) {
            return $this->response->setJSON([
                'error' => 'El parámetro "tabla" es requerido',
                'data' => []
            ]);
        }
        
        try {
            $columnas = $this->obtenerColumnasTabla($tabla);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $columnas
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en obtenerColumnas: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al obtener las columnas: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
