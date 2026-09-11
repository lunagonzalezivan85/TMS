<?php

namespace Config;

class Menu
{
    /**
     * Configuración del menú del sidebar
     * 
     * @return array
     */
    public static function getSidebarMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'url' => 'dashboard',
                'active' => ['dashboard', 'dashboard/*'],
                'permission' => ['CONDUCTOR', 'ADMINISTRADOR', 'MECANICO']
            ],
            [
                'title' => 'Vehículos',
                'icon' => 'fas fa-car',
                'url' => '#',
                'submenu' => [
                    [
                        'title' => 'Lista de Vehículos',
                        'url' => 'vehiculos',
                        'permission' => ['CONDUCTOR', 'ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Agregar Vehículo',
                        'url' => 'vehiculos/create',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Mis Vehículos',
                        'url' => 'vehiculos/mis-vehiculos',
                        'permission' => ['CONDUCTOR']
                    ],
                    [
                        'title' => 'Asignación de Vehículos',
                        'url' => 'asignacion-vehiculos',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Registro de Combustible',
                        'url' => 'registro-combustible',
                        'permission' => ['ADMINISTRADOR', 'CONDUCTOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Supervisor de Combustible',
                        'url' => 'registro-combustible/supervisor',
                        'permission' => ['ADMINISTRADOR', 'SUPERVISOR']
                    ],
                    [
                        'title' => 'Lectura de Bomba',
                        'url' => 'lectura-bomba',
                        'permission' => ['ADMINISTRADOR', 'CONDUCTOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Dashboard Bomba',
                        'url' => 'lectura-bomba/dashboard',
                        'permission' => ['ADMINISTRADOR', 'CONDUCTOR', 'MECANICO']
                    ]
                ]
            ],
            [
                'title' => 'Cotizador',
                'icon' => 'fas fa-calculator',
                'url' => '#',
                'active' => ['cotizador', 'cotizador/*'],
                'submenu' => [
                    [
                        'title' => 'Historial',
                        'url' => 'cotizador/historial',
                        'permission' => ['ADMINISTRADOR', 'SUPERVISOR']
                    ],
                    [
                        'title' => 'Nueva Cotización',
                        'url' => 'cotizador/nueva',
                        'permission' => ['ADMINISTRADOR', 'SUPERVISOR']
                    ]
                ]
            ],
            [
                'title' => 'Conductores',
                'icon' => 'fas fa-user-tie',
                'url' => '#',
                'active' => ['conductores', 'conductores/*'],
                'submenu' => [
                    [
                        'title' => 'Lista de Conductores',
                        'url' => 'conductores',
                        'permission' => ['ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Registrar Conductor',
                        'url' => 'conductores/create',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Mi Perfil de Conductor',
                        'url' => 'conductores/mi-perfil',
                        'permission' => ['CONDUCTOR']
                    ]
                ]
            ],
            [
                'title' => 'Reportes',
                'icon' => 'fas fa-chart-bar',
                'url' => '#',
                'submenu' => [
                    [
                        'title' => 'Reporte General',
                        'url' => 'reports',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Reporte de Vehículos',
                        'url' => 'reports/vehicles',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Reporte de Mantenimiento',
                        'url' => 'reports/maintenance',
                        'permission' => ['ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Reporte de Combustible',
                        'url' => 'reports/fuel',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Reporte de Costos',
                        'url' => 'reports/costs',
                        'permission' => ['ADMINISTRADOR']
                    ]
                ]
            ],
            [
                'title' => 'Órdenes de Trabajo',
                'icon' => 'fas fa-tools',
                'url' => 'ordenes-trabajo',
                'active' => ['ordenes-trabajo', 'ordenes-trabajo/*'],
                'permission' => ['ADMINISTRADOR', 'MECANICO', 'CONDUCTOR'],
                'submenu' => [
                    [
                        'title' => 'Dashboard',
                        'url' => 'ordenes-trabajo',
                        'permission' => ['ADMINISTRADOR', 'MECANICO', 'CONDUCTOR']
                    ],
                    [
                        'title' => 'Vista Calendario',
                        'url' => 'ordenes-trabajo/calendario',
                        'permission' => ['ADMINISTRADOR', 'MECANICO', 'CONDUCTOR']
                    ],
                    [
                        'title' => 'Vista Kanban',
                        'url' => 'ordenes-trabajo/kanban',
                        'permission' => ['ADMINISTRADOR', 'MECANICO', 'CONDUCTOR']
                    ],
                    [
                        'title' => 'Mis Órdenes',
                        'url' => 'ordenes-trabajo/mis-ordenes',
                        'permission' => ['MECANICO', 'CONDUCTOR']
                    ],
                    [
                        'title' => 'Consulta General',
                        'url' => 'ordenes-trabajo/consulta',
                        'permission' => ['ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Bandeja Pendientes',
                        'url' => 'ordenes-trabajo/bandeja-pendientes',
                        'permission' => ['ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Bandeja Aprobadas',
                        'url' => 'ordenes-trabajo/bandeja-aprobadas',
                        'permission' => ['ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Bandeja En Proceso',
                        'url' => 'ordenes-trabajo/bandeja-en-proceso',
                        'permission' => ['ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Bandeja Finalizadas',
                        'url' => 'ordenes-trabajo/bandeja-finalizadas',
                        'permission' => ['ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Nueva Orden',
                        'url' => 'ordenes-trabajo/create',
                        'permission' => ['ADMINISTRADOR', 'MECANICO', 'CONDUCTOR']
                    ]
                ]
            ],
            [
                'title' => 'Solicitudes',
                'icon' => 'fas fa-clipboard-list',
                'url' => 'solicitudes',
                'active' => ['solicitudes', 'solicitudes/*'],
                'permission' => ['ADMINISTRADOR', 'MECANICO', 'CONDUCTOR'],
                'submenu' => [
                    [
                        'title' => 'Todas las Solicitudes',
                        'url' => 'solicitudes',
                        'permission' => ['ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Mis Solicitudes',
                        'url' => 'solicitudes/mis-solicitudes',
                        'permission' => ['CONDUCTOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Nueva Solicitud',
                        'url' => 'solicitudes/crear',
                        'permission' => ['CONDUCTOR', 'ADMINISTRADOR', 'MECANICO']
                    ]
                ]
            ],
            [
                'title' => 'Inventario',
                'icon' => 'fas fa-warehouse',
                'url' => '#',
                'active' => ['materiales', 'materiales/*', 'movimientos', 'movimientos/*'],
                'submenu' => [
                    [
                        'title' => 'Materiales',
                        'url' => 'materiales',
                        'permission' => ['ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Nuevo Material',
                        'url' => 'materiales/create',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Movimientos de Inventario',
                        'url' => 'movimientos',
                        'permission' => ['ADMINISTRADOR', 'MECANICO']
                    ],
                    [
                        'title' => 'Crear Movimientos',
                        'url' => '#',
                        'permission' => ['ADMINISTRADOR', 'MECANICO'],
                        'submenu' => [
                            [
                                'title' => 'Requisa de Salida',
                                'url' => 'movimientos/create?tipo=SALIDA',
                                'permission' => ['ADMINISTRADOR', 'MECANICO']
                            ],
                            [
                                'title' => 'Orden de Compras',
                                'url' => 'movimientos/create?tipo=ENTRADA',
                                'permission' => ['ADMINISTRADOR', 'MECANICO']
                            ],
                            [
                                'title' => 'Ajuste de Materiales',
                                'url' => 'movimientos/create?tipo=AJUSTE_POSITIVO',
                                'permission' => ['ADMINISTRADOR']
                            ],
                            [
                                'title' => 'Traslados',
                                'url' => 'movimientos/create?tipo=TRANSFERENCIA',
                                'permission' => ['ADMINISTRADOR', 'MECANICO']
                            ]
                        ]
                    ],
                    [
                        'title' => 'Sincronización',
                        'url' => 'materiales/sincronizacion',
                        'permission' => ['ADMINISTRADOR']
                    ]
                ]
            ],
            [
                'title' => 'Configuración',
                'icon' => 'fas fa-cog',
                'url' => '#',
                'active' => ['settings', 'settings/*', 'tipo-operacion', 'tipo-operacion/*', 'tipo-unidad', 'tipo-unidad/*', 'tipos-problema', 'tipos-problema/*', 'rol', 'rol/*', 'direcciones', 'direcciones/*', 'catalogo', 'catalogo/*', 'mantenimiento-tablas', 'mantenimiento-tablas/*'],
                'submenu' => [
                    [
                        'title' => 'Configuración General',
                        'url' => 'settings',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Gestión de Usuarios',
                        'url' => 'usuarios',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Tipos de Operación',
                        'url' => 'tipo-operacion',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Tipos de Unidad',
                        'url' => 'tipo-unidad',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Tipos de Problema',
                        'url' => 'tipos-problema',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Tipos de Motivo Combustible',
                        'url' => 'tipo-motivo-combustible',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Gestión de Roles',
                        'url' => 'rol',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Gestión de Menús',
                        'url' => 'menu',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Gestión de Accesos',
                        'url' => 'acceso',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Gestión de Direcciones',
                        'url' => 'direcciones',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Gestión de Catálogo',
                        'url' => 'catalogo',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Mantenimiento de Tablas',
                        'url' => 'mantenimiento-tablas',
                        'permission' => ['ADMINISTRADOR']
                    ],
                    [
                        'title' => 'Configuración del Sistema',
                        'url' => 'usuarios',
                        'permission' => ['ADMINISTRADOR']
                    ]
                ]
            ]
        ];
    }

    /**
     * Obtener menú filtrado por permisos del usuario desde la base de datos
     * 
     * @param string $userRole Nombre del rol del usuario
     * @return array
     */
    public static function getMenuByUserType(string $userRole): array
    {
        $isAdmin = strtolower(trim($userRole)) === 'administrador';

        // Obtener el ID del rol desde la base de datos
        $rolModel = new \App\Models\RolModel();
        $rol = $rolModel->where('nombre', $userRole)->first();
        
        if (!$rol) {
            // Fallback al menú estático solo para administrador
            return $isAdmin ? self::buildMenuForRole('ADMINISTRADOR') : [];
        }
        
        // Obtener los accesos permitidos para este rol
        $accesoModel = new \App\Models\AccesoModel();
        $accesos = $accesoModel->getAccesosPorRol($rol['id']);
        
        if (empty($accesos)) {
            // Sin accesos en BD: fallback estático para admin, vacío para otros
            return $isAdmin ? self::buildMenuForRole('ADMINISTRADOR') : [];
        }
        
        $menuModel = new \App\Models\MenuModel();
        $menusPadre = [];
        $submenus   = [];

        foreach ($accesos as $acceso) {
            $menu = $menuModel->find($acceso['id_menu']);
            if (!$menu) continue;

            $esPadre = (int)($menu['nivel'] ?? 0) === 1
                    || empty($menu['id_superior'])
                    || (int)$menu['id_superior'] === 0;

            if ($esPadre) {
                // Menú padre: registrar si aún no existe
                if (!isset($menusPadre[$menu['id']])) {
                    $menusPadre[$menu['id']] = [
                        'id'      => $menu['id'],
                        'title'   => $menu['menu'],
                        'icon'    => $menu['icono'] ?: 'fas fa-circle',
                        'url'     => $menu['ruta'] ?: '#',
                        'active'  => [$menu['ruta'], $menu['ruta'] . '/*'],
                        'submenu' => []
                    ];
                }
            } else {
                // Submenú: cargar automáticamente el padre aunque no esté en accesos
                $idPadre = (int)$menu['id_superior'];

                if (!isset($menusPadre[$idPadre])) {
                    $padre = $menuModel->find($idPadre);
                    if ($padre) {
                        $menusPadre[$idPadre] = [
                            'id'      => $padre['id'],
                            'title'   => $padre['menu'],
                            'icon'    => $padre['icono'] ?: 'fas fa-circle',
                            'url'     => '#',
                            'active'  => [$padre['ruta'] ?? '', ($padre['ruta'] ?? '') . '/*'],
                            'submenu' => []
                        ];
                    }
                }

                $submenus[$idPadre][] = [
                    'title' => $menu['menu'],
                    'url'   => $menu['ruta'] ?: '#'
                ];
            }
        }

        // Asignar submenús a sus padres
        foreach ($submenus as $idPadre => $items) {
            if (isset($menusPadre[$idPadre])) {
                $menusPadre[$idPadre]['submenu'] = $items;
            }
        }

        // Ordenar padres por id (las keys del array son los IDs del menú)
        ksort($menusPadre);

        return array_values($menusPadre);
    }

    /**
     * Verificar si una URL está activa
     * 
     * @param array $activePatterns
     * @param string $currentUrl
     * @return bool
     */
    public static function isActive(array $activePatterns, string $currentUrl): bool
    {
        foreach ($activePatterns as $pattern) {
            if (str_contains($pattern, '*')) {
                $pattern = str_replace('*', '', $pattern);
                if (str_starts_with($currentUrl, $pattern)) {
                    return true;
                }
            } else {
                if ($currentUrl === $pattern) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Configuración del menú del usuario (dropdown en navbar)
     * 
     * @return array
     */
    public static function getUserMenu(): array
    {
        return [
            [
                'title' => 'Mi Perfil',
                'icon' => 'fas fa-user',
                'url' => 'profile'
            ],
            [
                'title' => 'Editar Perfil',
                'icon' => 'fas fa-user-edit',
                'url' => 'profile/edit'
            ],
            [
                'title' => 'Configuración',
                'icon' => 'fas fa-cog',
                'url' => 'dashboard/settings'
            ],
            [
                'divider' => true
            ],
            [
                'title' => 'Cerrar Sesión',
                'icon' => 'fas fa-sign-out-alt',
                'url' => 'logout',
                'class' => 'text-danger'
            ]
        ];
    }

    /**
     * Construir menú desde la configuración estática filtrando por rol
     */
    public static function buildMenuForRole(string $role): array
    {
        $role = strtoupper(trim($role));
        $result = [];

        foreach (self::getSidebarMenu() as $item) {
            // Item con permiso directo
            if (isset($item['permission']) && in_array($role, $item['permission'])) {
                unset($item['permission']);
                $result[] = $item;
                continue;
            }

            // Item con submenú: filtrar submenús permitidos
            if (isset($item['submenu'])) {
                $filteredSub = [];
                foreach ($item['submenu'] as $sub) {
                    if (!isset($sub['permission']) || in_array($role, $sub['permission'])) {
                        unset($sub['permission']);
                        $filteredSub[] = $sub;
                    }
                }
                if (!empty($filteredSub)) {
                    $item['submenu'] = $filteredSub;
                    unset($item['permission']);
                    $result[] = $item;
                }
            }
        }

        return $result;
    }
}
