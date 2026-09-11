<?php

namespace App\Controllers;

use Config\Menu as MenuConfig;

class MenuRoutes extends BaseController
{
    /**
     * Obtener todas las rutas disponibles del menú de configuración
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getRutas()
    {
        try {
            $menuConfig = MenuConfig::getSidebarMenu();
            $rutas = [];
            
            $this->extraerRutas($menuConfig, $rutas);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $rutas
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener las rutas: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Extraer rutas recursivamente del menú
     * 
     * @param array $menuItems
     * @param array &$rutas
     * @param string $parentTitle
     */
    private function extraerRutas(array $menuItems, array &$rutas, string $parentTitle = '')
    {
        foreach ($menuItems as $item) {
            $title = $parentTitle ? $parentTitle . ' > ' . $item['title'] : $item['title'];
            
            // Si tiene URL válida (no es '#'), agregarla
            if (isset($item['url']) && $item['url'] !== '#' && !empty($item['url'])) {
                $rutas[] = [
                    'title' => $title,
                    'url' => $item['url'],
                    'icon' => $item['icon'] ?? 'fas fa-link',
                    'type' => $parentTitle ? 'submenu' : 'menu'
                ];
            }
            
            // Si tiene submenú, procesarlo recursivamente
            if (isset($item['submenu']) && is_array($item['submenu'])) {
                $this->extraerRutas($item['submenu'], $rutas, $title);
            }
        }
    }
    
    /**
     * Buscar rutas por término de búsqueda
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function buscarRutas()
    {
        try {
            $termino = $this->request->getGet('q') ?? '';
            $menuConfig = MenuConfig::getSidebarMenu();
            $todasLasRutas = [];
            
            $this->extraerRutas($menuConfig, $todasLasRutas);
            
            // Filtrar rutas por término de búsqueda
            $rutasFiltradas = [];
            if (!empty($termino)) {
                foreach ($todasLasRutas as $ruta) {
                    if (stripos($ruta['title'], $termino) !== false || 
                        stripos($ruta['url'], $termino) !== false) {
                        $rutasFiltradas[] = $ruta;
                    }
                }
            } else {
                $rutasFiltradas = $todasLasRutas;
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $rutasFiltradas,
                'total' => count($rutasFiltradas)
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error en la búsqueda: ' . $e->getMessage()
            ]);
        }
    }
}
