<?php

namespace App\Controllers;

use App\Models\DocumentacionConductorModel;
use App\Models\RegistroCombustibleModel;
use App\Models\VehiculoModel;
use App\Models\SolicitudModel;
use App\Models\ConductorModel;
use App\Config\Widget;

class Dashboard extends SecureController
{
    public function index(): string
    {
        
        // Cargar modelos y configuración
        $docConductorModel = new DocumentacionConductorModel();
        $combustibleModel = new RegistroCombustibleModel();
        $vehiculoModel = new VehiculoModel();
        $solicitudModel = new SolicitudModel();
        $conductorModel = new ConductorModel();
        $widgetConfig = new Widget();
        
        $empresaId = session()->get('empresa_id');
        
        // Obtener documentos próximos a vencer
        $documentosConductorPorVencer = $docConductorModel->getDocumentosPorVencer();
        $documentosVehiculosPorVencer = []; // Vista vw_documentacion_vehiculo pendiente de crear
        
        // Obtener estadísticas de combustible
        $estadisticasCombustible = $combustibleModel->getEstadisticasConsumo();
        
        // Convertir litros a galones (1 galón = 3.78541 litros) - manejar valores nulos
        $totalGalones = ($estadisticasCombustible['total_litros'] ?? 0) / 3.78541;
        $promedioGalones = ($estadisticasCombustible['promedio_litros'] ?? 0) / 3.78541;
        
        // Obtener estadísticas generales
        $statsVehiculos = $vehiculoModel->getEstadisticasVehiculos($empresaId);
        $statsSolicitudes = $solicitudModel->getEstadisticasSolicitudes($empresaId);
        $totalConductores = $conductorModel->where('id_empresa', $empresaId)->countAllResults();

        $data = [
            'title' => 'Dashboard - Sistema GMV',
            'page_title' => 'Dashboard',
            'stats' => [
                'total_vehicles' => $statsVehiculos['total'] ?? 0,
                'total_maintenance' => $statsSolicitudes['totales']['total'] ?? 0,
                'total_conductores' => $totalConductores,
                'alerts' => count($documentosConductorPorVencer) + count($documentosVehiculosPorVencer)
            ],
            'documentos_por_vencer' => $documentosConductorPorVencer,
            'documentos_vehiculos_por_vencer' => $documentosVehiculosPorVencer,
            'combustible_stats' => [
                'total_litros' => $estadisticasCombustible['total_litros'] ?? 0,
                'total_galones' => $totalGalones,
                'promedio_litros' => $estadisticasCombustible['promedio_litros'] ?? 0,
                'promedio_galones' => $promedioGalones,
                'total_registros' => $estadisticasCombustible['total_registros'] ?? 0,
                'total_kilometros' => $estadisticasCombustible['total_kilometros'] ?? 0
            ],
            'widget_config' => $widgetConfig->getWidgetConfig(),
            'widgets_disponibles' => $widgetConfig->widgets,
            'widgets_categorias' => $widgetConfig->getWidgetsByCategory()
        ];

        return view('dashboard/index', $data);
    }

    public function getStats()
    {
        $vehiculoModel = new VehiculoModel();
        $solicitudModel = new SolicitudModel();
        $combustibleModel = new RegistroCombustibleModel();
        $conductorModel = new ConductorModel();
        $docConductorModel = new DocumentacionConductorModel();
        
        $empresaId = session()->get('empresa_id');
        
        $statsVehiculos = $vehiculoModel->getEstadisticasVehiculos($empresaId);
        $statsSolicitudes = $solicitudModel->getEstadisticasSolicitudes($empresaId);
        $estadisticasCombustible = $combustibleModel->getEstadisticasConsumo();
        $totalConductores = $conductorModel->where('id_empresa', $empresaId)->countAllResults();
        $documentosPorVencer = $docConductorModel->getDocumentosPorVencer();
        
        $stats = [
            'vehicles' => [
                'total' => $statsVehiculos['total'] ?? 0,
                'por_estado' => $statsVehiculos['por_estado'] ?? []
            ],
            'maintenance' => [
                'total' => $statsSolicitudes['totales']['total'] ?? 0,
                'abiertas' => $statsSolicitudes['totales']['abiertas'] ?? 0,
                'cerradas' => $statsSolicitudes['totales']['cerradas'] ?? 0
            ],
            'fuel' => [
                'total_litros' => $estadisticasCombustible['total_litros'] ?? 0,
                'total_galones' => $estadisticasCombustible['total_galones'] ?? 0,
                'total_registros' => $estadisticasCombustible['total_registros'] ?? 0
            ],
            'conductores' => $totalConductores,
            'alerts' => count($documentosPorVencer)
        ];

        return $this->response->setJSON($stats);
    }
}
