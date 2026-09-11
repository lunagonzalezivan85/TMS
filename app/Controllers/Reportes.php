<?php

namespace App\Controllers;

class Reportes extends SecureController
{
    private function buildWhere()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio');
        $fechaFin    = $this->request->getGet('fecha_fin');

        $whereRC = '';
        $whereLB = '';
        $params  = [];

        if ($fechaInicio && $fechaFin) {
            $whereRC = "WHERE rc.fecha_registro >= ? AND rc.fecha_registro <= ?";
            $whereLB = "WHERE lb.fecha_apertura >= ? AND lb.fecha_apertura <= ?";
            $params  = [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'];
        } elseif ($fechaInicio) {
            $whereRC = "WHERE rc.fecha_registro >= ?";
            $whereLB = "WHERE lb.fecha_apertura >= ?";
            $params  = [$fechaInicio . ' 00:00:00'];
        } elseif ($fechaFin) {
            $whereRC = "WHERE rc.fecha_registro <= ?";
            $whereLB = "WHERE lb.fecha_apertura <= ?";
            $params  = [$fechaFin . ' 23:59:59'];
        }

        return [$whereRC, $whereLB, $params, $fechaInicio, $fechaFin];
    }

    public function combustible()
    {
        $db = \Config\Database::connect();
        [$whereRC, $whereLB, $params, $fechaInicio, $fechaFin] = $this->buildWhere();

        // Solo KPIs Globales en carga inicial
        $kpis = $db->query("
            SELECT 
                SUM(rc.cantidad_litros) AS total_litros_consumidos,
                ROUND(SUM(rc.cantidad_litros) / 3.78541, 2) AS total_galones_consumidos,
                ROUND(AVG(rc.cantidad_litros), 2) AS promedio_litros_por_despacho,
                COUNT(DISTINCT rc.id_vehiculo) AS total_vehiculos_unicos
            FROM registro_combustible rc
            INNER JOIN lectura_bomba lb ON rc.id_lectura = lb.id
            $whereRC
        ", $params)->getRowArray();

        $data = [
            'title'        => 'Reporte de Combustible - GMV',
            'kpis'         => $kpis,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin
        ];

        return view('reportes/combustible', $data);
    }

    public function ajaxTop10()
    {
        $db = \Config\Database::connect();
        [$whereRC, , $params] = $this->buildWhere();

        $top10 = $db->query("
            SELECT 
                v.id AS id_vehiculo,
                v.codigo_unidad,
                v.placa,
                v.marca,
                v.modelo,
                SUM(rc.cantidad_litros) AS total_litros_consumidos,
                ROUND(SUM(rc.cantidad_litros) / 3.78541, 2) AS total_galones_consumidos,
                COUNT(rc.id) AS total_despachos
            FROM registro_combustible rc
            INNER JOIN vehiculos v ON rc.id_vehiculo = v.id
            INNER JOIN lectura_bomba lb ON rc.id_lectura = lb.id
            $whereRC
            GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo
            ORDER BY total_litros_consumidos DESC
            LIMIT 10
        ", $params)->getResultArray();

        return $this->response->setJSON(['data' => $top10]);
    }

    public function ajaxRendimiento()
    {
        $db = \Config\Database::connect();
        [$whereRC, , $params] = $this->buildWhere();
        $whereRend = $whereRC ? $whereRC . " AND rc.kilometraje_actual >= rc.kilometraje_anterior"
                              : "WHERE rc.kilometraje_actual >= rc.kilometraje_anterior";

        $rendimiento = $db->query("
            SELECT 
                v.id AS id_vehiculo,
                v.codigo_unidad,
                v.placa,
                SUM(rc.kilometraje_actual - rc.kilometraje_anterior) AS km_totales_recorridos,
                SUM(rc.cantidad_litros) AS total_litros_consumidos,
                ROUND(SUM(rc.cantidad_litros) / 3.78541, 2) AS total_galones_consumidos,
                ROUND(
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 
                    2
                ) AS rendimiento_km_l,
                ROUND(
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0) * 3.78541, 
                    2
                ) AS rendimiento_km_gln
            FROM registro_combustible rc
            INNER JOIN vehiculos v ON rc.id_vehiculo = v.id
            $whereRend
            GROUP BY v.id, v.codigo_unidad, v.placa
            ORDER BY km_totales_recorridos DESC
        ", $params)->getResultArray();

        return $this->response->setJSON(['data' => $rendimiento]);
    }

    public function ajaxRendimientoDetalle($idVehiculo = null)
    {
        if (!$idVehiculo) {
            return $this->response->setJSON(['data' => []]);
        }

        $db = \Config\Database::connect();
        [$whereRC, , $params] = $this->buildWhere();

        $whereDetalle = $whereRC
            ? $whereRC . " AND rc.id_vehiculo = ? AND rc.kilometraje_actual >= rc.kilometraje_anterior AND rc.cantidad_litros > 0"
            : "WHERE rc.id_vehiculo = ? AND rc.kilometraje_actual >= rc.kilometraje_anterior AND rc.cantidad_litros > 0";

        $params[] = $idVehiculo;

        $detalle = $db->query("
            SELECT
                rc.id,
                rc.fecha_registro,
                rc.kilometraje_anterior,
                rc.kilometraje_actual,
                (rc.kilometraje_actual - rc.kilometraje_anterior) AS km_recorridos,
                rc.cantidad_litros,
                ROUND(rc.cantidad_litros / 3.78541, 2) AS cantidad_galones,
                ROUND(
                    (rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(rc.cantidad_litros, 0),
                    2
                ) AS rendimiento_km_l,
                ROUND(
                    (rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(rc.cantidad_litros, 0) * 3.78541,
                    2
                ) AS rendimiento_km_gln
            FROM registro_combustible rc
            WHERE rc.id_vehiculo = ?
              AND rc.kilometraje_actual >= rc.kilometraje_anterior
              AND rc.cantidad_litros > 0
            ORDER BY rc.fecha_registro ASC
        ", [$idVehiculo])->getResultArray();

        return $this->response->setJSON(['data' => $detalle]);
    }

    public function ajaxComparativo()
    {
        $db = \Config\Database::connect();
        [$whereRC, , $params] = $this->buildWhere();
        $whereComp = $whereRC ? $whereRC . " AND rc.kilometraje_actual >= rc.kilometraje_anterior AND rc.cantidad_litros > 0"
                              : "WHERE rc.kilometraje_actual >= rc.kilometraje_anterior AND rc.cantidad_litros > 0";

        $comparativo = $db->query("
            SELECT 
                v.id AS id_vehiculo,
                v.codigo_unidad,
                v.placa,
                v.marca,
                v.modelo,
                COALESCE(v.rendimiento, 0) AS rendimiento_teorico,
                ROUND(
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0),
                    2
                ) AS rendimiento_promedio_kml,
                ROUND(
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0) * 3.78541,
                    2
                ) AS rendimiento_promedio_kmgln,
                ROUND(
                    (SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0) * 3.78541)
                    - COALESCE(v.rendimiento, 0),
                    2
                ) AS diferencia_promedio_vs_teorico,
                CASE 
                    WHEN COALESCE(v.rendimiento, 0) > 0 THEN 
                        ROUND(
                            (SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0) * 3.78541)
                            / v.rendimiento * 100, 2
                        )
                    ELSE 0 
                END AS porcentaje_eficiencia,
                COUNT(rc.id) AS total_despachos,
                SUM(rc.cantidad_litros) AS total_litros_consumidos,
                SUM(rc.kilometraje_actual - rc.kilometraje_anterior) AS total_km_recorridos
            FROM vehiculos v
            INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
            INNER JOIN lectura_bomba lb ON rc.id_lectura = lb.id
            $whereComp
            GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo, v.rendimiento
            ORDER BY porcentaje_eficiencia ASC
        ", $params)->getResultArray();

        return $this->response->setJSON(['data' => $comparativo]);
    }

    public function ajaxTurnos()
    {
        $db = \Config\Database::connect();
        [, $whereLB, $params] = $this->buildWhere();

        $turnos = $db->query("
            SELECT 
                lb.id AS id_lectura_bomba,
                lb.fecha_apertura,
                lb.fecha_cierre,
                lb.lectura_inicial_litros,
                lb.lectura_final_litros,
                lb.ingreso_tanque,
                lb.usuario_apertura,
                lb.usuario_cierre,
                cat.nombre AS nombre_bomba,
                (lb.lectura_final_litros - lb.lectura_inicial_litros) AS teorico,
                COALESCE(SUM(rc.cantidad_litros), 0) AS despachado,
                ((lb.lectura_final_litros - lb.lectura_inicial_litros) - COALESCE(SUM(rc.cantidad_litros), 0)) AS diferencia
            FROM lectura_bomba lb
            LEFT JOIN registro_combustible rc ON lb.id = rc.id_lectura
            LEFT JOIN catalogo cat ON cat.id = lb.id_centro_costo
            $whereLB
            GROUP BY lb.id, lb.fecha_apertura, lb.fecha_cierre, 
                     lb.lectura_inicial_litros, lb.lectura_final_litros, lb.ingreso_tanque,
                     lb.usuario_apertura, lb.usuario_cierre, cat.nombre
            ORDER BY lb.fecha_apertura DESC
        ", $params)->getResultArray();

        return $this->response->setJSON(['data' => $turnos]);
    }

    public function exportarExcel()
    {
        $db = \Config\Database::connect();
        [$whereRC, $whereLB, $params] = $this->buildWhere();

        $seccion = $this->request->getGet('seccion');

        $data = [];
        $headers = [];
        $filename = 'reporte_combustible';

        if ($seccion === 'top10') {
            $data = $db->query("
                SELECT v.codigo_unidad, v.placa, v.marca, v.modelo,
                    SUM(rc.cantidad_litros) AS total_litros,
                    ROUND(SUM(rc.cantidad_litros) / 3.78541, 2) AS total_galones,
                    COUNT(rc.id) AS despachos
                FROM registro_combustible rc
                INNER JOIN vehiculos v ON rc.id_vehiculo = v.id
                INNER JOIN lectura_bomba lb ON rc.id_lectura = lb.id
                $whereRC
                GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo
                ORDER BY total_litros DESC
            ", $params)->getResultArray();
            $headers = ['Codigo Unidad', 'Placa', 'Marca', 'Modelo', 'Total Litros', 'Total Galones', 'Despachos'];
            $filename = 'top10_vehiculos';

        } elseif ($seccion === 'rendimiento') {
            $whereRend = $whereRC ? $whereRC . " AND rc.kilometraje_actual >= rc.kilometraje_anterior"
                                  : "WHERE rc.kilometraje_actual >= rc.kilometraje_anterior";
            $data = $db->query("
                SELECT v.codigo_unidad, v.placa,
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) AS km_recorridos,
                    SUM(rc.cantidad_litros) AS litros,
                    ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 2) AS rendimiento_kml
                FROM registro_combustible rc
                INNER JOIN vehiculos v ON rc.id_vehiculo = v.id
                $whereRend
                GROUP BY v.id, v.codigo_unidad, v.placa
                ORDER BY km_recorridos DESC
            ", $params)->getResultArray();
            $headers = ['Codigo Unidad', 'Placa', 'KM Recorridos', 'Litros', 'Rendimiento KM/L'];
            $filename = 'rendimiento_vehiculos';

        } elseif ($seccion === 'comparativo') {
            $whereComp = $whereRC ? $whereRC . " AND rc.kilometraje_actual >= rc.kilometraje_anterior AND rc.cantidad_litros > 0"
                                  : "WHERE rc.kilometraje_actual >= rc.kilometraje_anterior AND rc.cantidad_litros > 0";
            $data = $db->query("
                SELECT v.codigo_unidad, v.placa, v.marca, v.modelo,
                    COALESCE(v.rendimiento, 0) AS rend_teorico,
                    ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0) * 3.78541, 2) AS rend_promedio_kmgln,
                    ROUND((SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0) * 3.78541) - COALESCE(v.rendimiento, 0), 2) AS diferencia,
                    CASE WHEN COALESCE(v.rendimiento, 0) > 0 THEN ROUND((SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0) * 3.78541) / v.rendimiento * 100, 2) ELSE 0 END AS eficiencia,
                    COUNT(rc.id) AS despachos, SUM(rc.cantidad_litros) AS litros,
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) AS km
                FROM vehiculos v
                INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
                INNER JOIN lectura_bomba lb ON rc.id_lectura = lb.id
                $whereComp
                GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo, v.rendimiento
                ORDER BY eficiencia ASC
            ", $params)->getResultArray();
            $headers = ['Codigo Unidad', 'Placa', 'Marca', 'Modelo', 'Teorica KM/Gln', 'Promedio KM/Gln', 'Diferencia', '% Eficiencia', 'Despachos', 'Litros', 'KM'];
            $filename = 'comparativo_rendimiento';

        } elseif ($seccion === 'turnos') {
            $data = $db->query("
                SELECT lb.id, lb.fecha_apertura, lb.fecha_cierre,
                    lb.lectura_inicial_litros, lb.lectura_final_litros,
                    lb.usuario_apertura, lb.usuario_cierre,
                    cat.nombre AS nombre_bomba,
                    (lb.lectura_final_litros - lb.lectura_inicial_litros) AS teorico,
                    COALESCE(SUM(rc.cantidad_litros), 0) AS despachado,
                    ((lb.lectura_final_litros - lb.lectura_inicial_litros) - COALESCE(SUM(rc.cantidad_litros), 0)) AS diferencia
                FROM lectura_bomba lb
                LEFT JOIN registro_combustible rc ON lb.id = rc.id_lectura
                LEFT JOIN catalogo cat ON cat.id = lb.id_centro_costo
                $whereLB
                GROUP BY lb.id, lb.fecha_apertura, lb.fecha_cierre, lb.lectura_inicial_litros, lb.lectura_final_litros,
                         lb.usuario_apertura, lb.usuario_cierre, cat.nombre
                ORDER BY lb.fecha_apertura DESC
            ", $params)->getResultArray();
            $headers = ['Turno', 'Bomba', 'Usuario Apertura', 'Usuario Cierre', 'Apertura', 'Cierre', 'Lectura Inicial', 'Lectura Final', 'Teorico', 'Despachado', 'Diferencia'];
            $filename = 'consumo_turnos';
        }

        // Generar CSV (Excel compatible)
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

        fputcsv($output, $headers);
        foreach ($data as $row) {
            fputcsv($output, array_map(function($v) {
                return $v ?? '';
            }, array_values($row)));
        }
        fclose($output);
        exit;
    }

    // ════════════════════════════════════════════════════════════
    //  REPORTE DE VEHÍCULOS
    // ═════════════════════════════════════════════════════════════

    public function vehiculos()
    {
        $db = \Config\Database::connect();

        // KPIs globales de flota
        $kpis = $db->query("
            SELECT
                COUNT(DISTINCT v.id) AS total_vehiculos,
                SUM(CASE WHEN v.estado = 'ACTIVO' THEN 1 ELSE 0 END) AS total_activos,
                SUM(CASE WHEN v.estado = 'INACTIVO' THEN 1 ELSE 0 END) AS total_inactivos,
                SUM(CASE WHEN v.estado = 'EN REPARACION' THEN 1 ELSE 0 END) AS total_reparacion,
                SUM(CASE WHEN v.id IS NOT NULL THEN 1 ELSE 0 END) AS total_flota
            FROM vehiculos v
        ")->getRowArray();

        // KPIs de combustible global
        $kpisCombustible = $db->query("
            SELECT
                COUNT(rc.id) AS total_despachos,
                SUM(rc.cantidad_litros) AS total_litros,
                SUM(rc.kilometraje_actual - rc.kilometraje_anterior) AS total_km_recorridos,
                ROUND(
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(COUNT(DISTINCT rc.id_vehiculo), 0),
                    0
                ) AS promedio_km_por_vehiculo
            FROM registro_combustible rc
            WHERE rc.kilometraje_actual >= rc.kilometraje_anterior
        ")->getRowArray();

        $data = [
            'title'            => 'Reporte de Vehículos - GMV',
            'kpis'             => $kpis,
            'kpis_combustible' => $kpisCombustible,
        ];

        return view('reportes/vehiculos', $data);
    }

    public function ajaxResumenFlota()
    {
        $db = \Config\Database::connect();

        // Distribución por estado
        $estados = $db->query("
            SELECT
                v.estado,
                COUNT(*) AS cantidad
            FROM vehiculos v
            GROUP BY v.estado
        ")->getResultArray();

        // Resumen por vehículo (solo con registros de combustible)
        $vehiculos = $db->query("
            SELECT
                v.id,
                v.codigo_unidad,
                v.placa,
                v.marca,
                v.modelo,
                v.estado,
                COALESCE(v.rendimiento, 0) AS rendimiento_teorico,
                MAX(rc.kilometraje_actual) AS km_actual,
                MIN(rc.kilometraje_anterior) AS km_inicial,
                SUM(rc.cantidad_litros) AS total_litros,
                COUNT(rc.id) AS total_cargas,
                ROUND(
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0),
                    2
                ) AS rendimiento_promedio
            FROM vehiculos v
            INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
            WHERE rc.kilometraje_actual >= rc.kilometraje_anterior AND rc.cantidad_litros > 0
            GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo, v.estado, v.rendimiento
            ORDER BY v.codigo_unidad
        ")->getResultArray();

        // Cumplimiento de rendimiento teórico (agregado, con tolerancia ±5)
        $cumplimiento = $db->query("
            SELECT
                SUM(CASE WHEN rend_calc >= (v.rendimiento - 5) AND rend_calc <= (v.rendimiento + 5) THEN 1 ELSE 0 END) AS cumplen,
                SUM(CASE WHEN rend_calc < (v.rendimiento - 5) OR rend_calc > (v.rendimiento + 5) THEN 1 ELSE 0 END) AS no_cumplen,
                COUNT(*) AS total_evaluados
            FROM (
                SELECT
                    v.id, v.rendimiento,
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0) AS rend_calc
                FROM vehiculos v
                INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
                WHERE v.rendimiento > 0
                  AND rc.kilometraje_actual >= rc.kilometraje_anterior
                  AND rc.cantidad_litros > 0
                GROUP BY v.id, v.rendimiento
            ) AS calc
            INNER JOIN vehiculos v ON calc.id = v.id
        ")->getRowArray();

        // Vehículos que SÍ cumplen (dentro de ±5 del teórico)
        $cumplen = $db->query("
            SELECT
                v.id, v.codigo_unidad, v.placa, v.marca, v.modelo,
                COALESCE(v.rendimiento, 0) AS rendimiento_teorico,
                ROUND(
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0),
                    2
                ) AS rendimiento_promedio,
                COUNT(rc.id) AS total_evaluaciones
            FROM vehiculos v
            INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
            WHERE v.rendimiento > 0
              AND rc.kilometraje_actual >= rc.kilometraje_anterior
              AND rc.cantidad_litros > 0
            GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo, v.rendimiento
            HAVING
                ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 2) >= (v.rendimiento - 5)
                AND ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 2) <= (v.rendimiento + 5)
            ORDER BY rendimiento_promedio DESC
        ")->getResultArray();

        // Vehículos que NO cumplen (fuera de ±5 del teórico)
        $noCumplen = $db->query("
            SELECT
                v.id, v.codigo_unidad, v.placa, v.marca, v.modelo,
                COALESCE(v.rendimiento, 0) AS rendimiento_teorico,
                ROUND(
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0),
                    2
                ) AS rendimiento_promedio,
                COUNT(rc.id) AS total_evaluaciones
            FROM vehiculos v
            INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
            WHERE v.rendimiento > 0
              AND rc.kilometraje_actual >= rc.kilometraje_anterior
              AND rc.cantidad_litros > 0
            GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo, v.rendimiento
            HAVING
                ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 2) < (v.rendimiento - 5)
                OR ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 2) > (v.rendimiento + 5)
            ORDER BY rendimiento_promedio ASC
        ")->getResultArray();

        return $this->response->setJSON([
            'estados'       => $estados,
            'vehiculos'     => $vehiculos,
            'cumplimiento'  => $cumplimiento,
            'cumplen'       => $cumplen,
            'no_cumplen'    => $noCumplen,
        ]);
    }

    public function ajaxRankingRendimiento()
    {
        $db = \Config\Database::connect();

        $ranking = $db->query("
            SELECT
                v.id,
                v.codigo_unidad,
                v.placa,
                v.marca,
                v.modelo,
                COALESCE(v.rendimiento, 0) AS rendimiento_teorico,
                ROUND(
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0),
                    2
                ) AS rendimiento_promedio,
                MIN(rc.kilometraje_anterior) AS km_inicial,
                MAX(rc.kilometraje_actual) AS km_final,
                SUM(rc.kilometraje_actual - rc.kilometraje_anterior) AS km_recorridos,
                ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(COUNT(rc.id), 0), 0) AS km_promedio_recorrido,
                SUM(rc.cantidad_litros) AS total_litros,
                COUNT(rc.id) AS total_despachos,
                CASE
                    WHEN COALESCE(v.rendimiento, 0) > 0 THEN
                        ROUND(
                            (SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0))
                            / v.rendimiento * 100, 2
                        )
                    ELSE 0
                END AS porcentaje_eficiencia,
                CASE
                    WHEN COALESCE(v.rendimiento, 0) > 0 AND
                         ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 2) < (v.rendimiento - 5)
                         OR ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 2) > (v.rendimiento + 5)
                    THEN 1 ELSE 0
                END AS fuera_rango
            FROM vehiculos v
            INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
            WHERE rc.kilometraje_actual >= rc.kilometraje_anterior
              AND rc.cantidad_litros > 0
            GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo, v.rendimiento
            ORDER BY rendimiento_promedio DESC
        ")->getResultArray();

        return $this->response->setJSON(['data' => $ranking]);
    }

    public function ajaxKilometraje()
    {
        $db = \Config\Database::connect();

        $kilometraje = $db->query("
            SELECT
                v.id,
                v.codigo_unidad,
                v.placa,
                v.marca,
                v.modelo,
                MIN(rc.kilometraje_anterior) AS km_inicial,
                MAX(rc.kilometraje_actual) AS km_final,
                (MAX(rc.kilometraje_actual) - MIN(rc.kilometraje_anterior)) AS km_lineal,
                SUM(rc.kilometraje_actual - rc.kilometraje_anterior) AS km_tramos,
                (
                    (MAX(rc.kilometraje_actual) - MIN(rc.kilometraje_anterior))
                    - SUM(rc.kilometraje_actual - rc.kilometraje_anterior)
                ) AS diferencia_descuadre,
                COUNT(rc.id) AS total_registros
            FROM vehiculos v
            INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
            WHERE rc.kilometraje_actual >= rc.kilometraje_anterior
            GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo
            ORDER BY diferencia_descuadre DESC
        ")->getResultArray();

        return $this->response->setJSON(['data' => $kilometraje]);
    }

    public function exportarExcelVehiculos()
    {
        $db = \Config\Database::connect();
        $seccion = $this->request->getGet('seccion');

        $data = [];
        $headers = [];
        $filename = 'reporte_vehiculos';

        if ($seccion === 'resumen') {
            $data = $db->query("
                SELECT v.codigo_unidad, v.placa, v.marca, v.modelo, v.estado,
                    COALESCE(v.rendimiento, 0) AS rendimiento_teorico,
                    (SELECT MAX(rc2.kilometraje_actual) FROM registro_combustible rc2 WHERE rc2.id_vehiculo = v.id) AS km_actual,
                    (SELECT SUM(rc2.cantidad_litros) FROM registro_combustible rc2 WHERE rc2.id_vehiculo = v.id) AS total_litros,
                    (SELECT COUNT(rc2.id) FROM registro_combustible rc2 WHERE rc2.id_vehiculo = v.id) AS total_cargas,
                    (SELECT ROUND(AVG(rc2.rendimiento), 2) FROM registro_combustible rc2 WHERE rc2.id_vehiculo = v.id AND rc2.rendimiento IS NOT NULL) AS rendimiento_promedio
                FROM vehiculos v
                ORDER BY v.codigo_unidad
            ")->getResultArray();
            $headers = ['Codigo Unidad', 'Placa', 'Marca', 'Modelo', 'Estado', 'Rend Teorico', 'KM Actual', 'Total Litros', 'Total Cargas', 'Rend Promedio'];
            $filename = 'resumen_flota';

        } elseif ($seccion === 'ranking') {
            $data = $db->query("
                SELECT v.codigo_unidad, v.placa, v.marca, v.modelo,
                    COALESCE(v.rendimiento, 0) AS rend_teorico,
                    ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 2) AS rend_promedio,
                    MIN(rc.kilometraje_anterior) AS km_inicial,
                    MAX(rc.kilometraje_actual) AS km_final,
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) AS km_recorridos,
                    SUM(rc.cantidad_litros) AS litros,
                    COUNT(rc.id) AS despachos,
                    CASE WHEN COALESCE(v.rendimiento, 0) > 0 THEN ROUND(((SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0)) - v.rendimiento) / v.rendimiento * 100, 2) ELSE 0 END AS desviacion,
                    CASE WHEN COALESCE(v.rendimiento, 0) > 0 AND ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 2) < (v.rendimiento - 5) OR ROUND(SUM(rc.kilometraje_actual - rc.kilometraje_anterior) / NULLIF(SUM(rc.cantidad_litros), 0), 2) > (v.rendimiento + 5) THEN 'SI' ELSE 'NO' END AS fuera_rango
                FROM vehiculos v
                INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
                WHERE rc.kilometraje_actual >= rc.kilometraje_anterior AND rc.cantidad_litros > 0
                GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo, v.rendimiento
                ORDER BY rend_promedio DESC
            ")->getResultArray();
            $headers = ['Codigo Unidad', 'Placa', 'Marca', 'Modelo', 'Rend Teorico', 'Rend Promedio', 'KM Inicial', 'KM Recorrido', 'KM Actual', 'Litros', 'Despachos', 'Desviacion %', 'Fuera de Rango'];
            $filename = 'ranking_rendimiento';

        } elseif ($seccion === 'kilometraje') {
            $data = $db->query("
                SELECT v.codigo_unidad, v.placa, v.marca, v.modelo,
                    MIN(rc.kilometraje_anterior) AS km_inicial,
                    MAX(rc.kilometraje_actual) AS km_final,
                    (MAX(rc.kilometraje_actual) - MIN(rc.kilometraje_anterior)) AS km_lineal,
                    SUM(rc.kilometraje_actual - rc.kilometraje_anterior) AS km_tramos,
                    ((MAX(rc.kilometraje_actual) - MIN(rc.kilometraje_anterior)) - SUM(rc.kilometraje_actual - rc.kilometraje_anterior)) AS descuadre,
                    COUNT(rc.id) AS registros
                FROM vehiculos v
                INNER JOIN registro_combustible rc ON v.id = rc.id_vehiculo
                WHERE rc.kilometraje_actual >= rc.kilometraje_anterior
                GROUP BY v.id, v.codigo_unidad, v.placa, v.marca, v.modelo
                ORDER BY descuadre DESC
            ")->getResultArray();
            $headers = ['Codigo Unidad', 'Placa', 'Marca', 'Modelo', 'KM Inicial', 'KM Final', 'KM Lineal', 'KM Tramos', 'Descuadre', 'Registros'];
            $filename = 'kilometraje_vehiculos';
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, $headers);
        foreach ($data as $row) {
            fputcsv($output, array_map(function($v) {
                return $v ?? '';
            }, array_values($row)));
        }
        fclose($output);
        exit;
    }
}
