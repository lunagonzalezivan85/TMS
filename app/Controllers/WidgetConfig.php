<?php

namespace App\Controllers;

use App\Config\Widget;

class WidgetConfig extends BaseController
{
    protected $widgetConfig;

    public function __construct()
    {
        $this->widgetConfig = new Widget();
    }

    /**
     * Obtiene la configuración actual de widgets
     */
    public function getConfig()
    {
        $config = $this->widgetConfig->getWidgetConfig();
        $widgets = $this->widgetConfig->widgets;
        $categorias = $this->widgetConfig->getWidgetsByCategory();

        return $this->response->setJSON([
            'success' => true,
            'config' => $config,
            'widgets' => $widgets,
            'categorias' => $categorias
        ]);
    }

    /**
     * Guarda la configuración de widgets
     */
    public function saveConfig()
    {
        $request = $this->request->getJSON(true);
        
        if (!$request || !isset($request['widgets'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos de configuración inválidos'
            ]);
        }

        $widgetConfig = [];
        foreach ($request['widgets'] as $widgetId => $enabled) {
            $widgetConfig[$widgetId] = (bool)$enabled;
        }

        $saved = $this->widgetConfig->saveWidgetConfig($widgetConfig);

        if ($saved) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Configuración guardada exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al guardar la configuración'
            ]);
        }
    }

    /**
     * Resetea la configuración a los valores por defecto
     */
    public function resetConfig()
    {
        $defaultConfig = $this->widgetConfig->defaultConfig;
        $saved = $this->widgetConfig->saveWidgetConfig($defaultConfig);

        if ($saved) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Configuración restablecida a valores por defecto'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al restablecer la configuración'
            ]);
        }
    }

    /**
     * Obtiene el estado de un widget específico
     */
    public function getWidgetStatus($widgetId)
    {
        $enabled = $this->widgetConfig->isWidgetEnabled($widgetId);

        return $this->response->setJSON([
            'success' => true,
            'widget_id' => $widgetId,
            'enabled' => $enabled
        ]);
    }

    /**
     * Alterna el estado de un widget específico
     */
    public function toggleWidget($widgetId)
    {
        $config = $this->widgetConfig->getWidgetConfig();
        $config[$widgetId] = !($config[$widgetId] ?? false);
        
        $saved = $this->widgetConfig->saveWidgetConfig($config);

        if ($saved) {
            return $this->response->setJSON([
                'success' => true,
                'widget_id' => $widgetId,
                'enabled' => $config[$widgetId],
                'message' => 'Widget ' . ($config[$widgetId] ? 'habilitado' : 'deshabilitado')
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cambiar el estado del widget'
            ]);
        }
    }
}
