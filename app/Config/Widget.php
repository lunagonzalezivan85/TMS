<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class Widget extends BaseConfig
{
    /**
     * Configuración de widgets del dashboard
     * Cada widget puede ser habilitado/deshabilitado por el usuario
     */
    public $widgets = [
        [
            'id_widget' => 'stats_general',
            'titulo' => 'Estadísticas Generales',
            'descripcion' => 'Muestra estadísticas generales del sistema (vehículos, mantenimientos, alertas)',
            'icono' => 'fas fa-tachometer-alt',
            'estado' => 1,
            'orden' => 1,
            'categoria' => 'estadisticas'
        ],
        [
            'id_widget' => 'combustible_stats',
            'titulo' => 'Estadísticas de Combustible',
            'descripcion' => 'Muestra información detallada sobre el consumo de combustible',
            'icono' => 'fas fa-gas-pump',
            'estado' => 1,
            'orden' => 2,
            'categoria' => 'combustible'
        ],
        [
            'id_widget' => 'charts_analysis',
            'titulo' => 'Gráficos y Análisis',
            'descripcion' => 'Gráficos de mantenimiento y estado de vehículos',
            'icono' => 'fas fa-chart-bar',
            'estado' => 1,
            'orden' => 3,
            'categoria' => 'graficos'
        ],
        [
            'id_widget' => 'urgent_info',
            'titulo' => 'Información Urgente',
            'descripcion' => 'Documentos de conductores próximos a vencer',
            'icono' => 'fas fa-exclamation-triangle',
            'estado' => 1,
            'orden' => 4,
            'categoria' => 'alertas'
        ],
        [
            'id_widget' => 'vehicle_docs',
            'titulo' => 'Documentos de Vehículos',
            'descripcion' => 'Documentos de vehículos próximos a vencer',
            'icono' => 'fas fa-file-alt',
            'estado' => 1,
            'orden' => 5,
            'categoria' => 'documentos'
        ],
        [
            'id_widget' => 'recent_activities',
            'titulo' => 'Actividades Recientes',
            'descripcion' => 'Mantenimientos recientes y próximos, acciones rápidas',
            'icono' => 'fas fa-history',
            'estado' => 1,
            'orden' => 6,
            'categoria' => 'actividades'
        ]
    ];

    /**
     * Configuración por defecto de widgets
     * Se usa cuando no hay configuración guardada en sesión
     */
    public $defaultConfig = [
        'stats_general' => true,
        'combustible_stats' => true,
        'charts_analysis' => true,
        'urgent_info' => true,
        'vehicle_docs' => true,
        'recent_activities' => true
    ];

    /**
     * Categorías de widgets para organización en el modal
     */
    public $categorias = [
        'estadisticas' => [
            'nombre' => 'Estadísticas',
            'icono' => 'fas fa-chart-line',
            'color' => 'primary'
        ],
        'combustible' => [
            'nombre' => 'Combustible',
            'icono' => 'fas fa-gas-pump',
            'color' => 'warning'
        ],
        'graficos' => [
            'nombre' => 'Gráficos',
            'icono' => 'fas fa-chart-bar',
            'color' => 'info'
        ],
        'alertas' => [
            'nombre' => 'Alertas',
            'icono' => 'fas fa-bell',
            'color' => 'danger'
        ],
        'documentos' => [
            'nombre' => 'Documentos',
            'icono' => 'fas fa-folder',
            'color' => 'secondary'
        ],
        'actividades' => [
            'nombre' => 'Actividades',
            'icono' => 'fas fa-tasks',
            'color' => 'success'
        ]
    ];

    /**
     * Obtiene la configuración de widgets desde la sesión o usa la por defecto
     */
    public function getWidgetConfig()
    {
        $session = session();
        $config = $session->get('dashboard_widgets');
        
        if (!$config) {
            return $this->defaultConfig;
        }
        
        return $config;
    }

    /**
     * Guarda la configuración de widgets en la sesión
     */
    public function saveWidgetConfig($config)
    {
        $session = session();
        $session->set('dashboard_widgets', $config);
        return true;
    }

    /**
     * Obtiene widgets habilitados ordenados
     */
    public function getEnabledWidgets()
    {
        $config = $this->getWidgetConfig();
        $enabledWidgets = [];
        
        foreach ($this->widgets as $widget) {
            if (isset($config[$widget['id_widget']]) && $config[$widget['id_widget']]) {
                $enabledWidgets[] = $widget;
            }
        }
        
        // Ordenar por orden
        usort($enabledWidgets, function($a, $b) {
            return $a['orden'] - $b['orden'];
        });
        
        return $enabledWidgets;
    }

    /**
     * Verifica si un widget está habilitado
     */
    public function isWidgetEnabled($widgetId)
    {
        $config = $this->getWidgetConfig();
        return isset($config[$widgetId]) && $config[$widgetId];
    }

    /**
     * Obtiene widgets agrupados por categoría
     */
    public function getWidgetsByCategory()
    {
        $grouped = [];
        
        foreach ($this->widgets as $widget) {
            $categoria = $widget['categoria'];
            if (!isset($grouped[$categoria])) {
                $grouped[$categoria] = [
                    'info' => $this->categorias[$categoria],
                    'widgets' => []
                ];
            }
            $grouped[$categoria]['widgets'][] = $widget;
        }
        
        return $grouped;
    }
}
