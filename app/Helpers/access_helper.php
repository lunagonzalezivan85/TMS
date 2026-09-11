<?php

if (!function_exists('hasAccess')) {
    /**
     * Verificar si el usuario actual tiene acceso a una URL específica
     * 
     * @param string $url URL a verificar (sin dominio, solo la ruta)
     * @return bool
     */
    function hasAccess(string $url = null): bool
    {
        // Si no se proporciona URL, usar la URL actual
        if ($url === null) {
            $url = uri_string();
        }
        
        // Limpiar la URL (remover parámetros y barras extras)
        $url = trim($url, '/');
        
        // Obtener el rol del usuario desde la sesión
        $userRole = session('rol_nombre') ?? session('rol_name') ?? null;
        $userId = session('user_id') ?? null;
        
        // Si no hay usuario logueado, denegar acceso
        if (!$userId || !$userRole) {
            log_message('debug', 'hasAccess: sin usuario o rol - userId=' . ($userId ?? 'null') . ' role=' . ($userRole ?? 'null'));
            return false;
        }

        // El administrador tiene acceso total sin consultar la BD
        if (strtolower(trim($userRole)) === 'administrador') {
            return true;
        }
        
        // Obtener el ID del rol desde la base de datos
        $rolModel = new \App\Models\RolModel();
        $rol = $rolModel->where('nombre', $userRole)->first();
        
        if (!$rol) {
            log_message('debug', 'hasAccess: rol no encontrado en BD - role=' . $userRole);
            return false;
        }
        
        // Obtener los accesos permitidos para este rol
        $accesoModel = new \App\Models\AccesoModel();
        $accesos = $accesoModel->getAccesosPorRol($rol['id']);
        
        if (empty($accesos)) {
            log_message('debug', 'hasAccess: sin accesos para rol - role=' . $userRole . ' rolId=' . $rol['id']);
            return false;
        }
        
        $urlsPermitidas = [];
        // Verificar si la URL coincide con algún acceso permitido
        foreach ($accesos as $acceso) {
            $menuUrl = trim($acceso['menu_url'] ?? '', '/');
            $urlsPermitidas[] = $menuUrl;
            
            // Coincidencia exacta
            if ($url === $menuUrl) {
                return true;
            }
            
            // Coincidencia con patrón (URL padre)
            if (!empty($menuUrl) && strpos($url, $menuUrl) === 0) {
                // Verificar que sea una sub-ruta válida (no solo que contenga la cadena)
                $remaining = substr($url, strlen($menuUrl));
                if (empty($remaining) || $remaining[0] === '/') {
                    return true;
                }
            }
        }
        
        log_message('debug', 'hasAccess: denegado - url=' . $url . ' role=' . $userRole . ' urlsPermitidas=' . json_encode($urlsPermitidas));
        return false;
    }
}

if (!function_exists('requireAccess')) {
    /**
     * Requiere acceso a una URL específica, redirige si no tiene permisos
     * 
     * @param string $url URL a verificar
     * @param string $redirectTo URL de redirección si no tiene acceso (por defecto: dashboard)
     * @return void
     */
    function requireAccess(string $url = null, string $redirectTo = 'dashboard'): void
    {
        if (!hasAccess($url)) {
            // Registrar intento de acceso no autorizado
            log_message('warning', 'Intento de acceso no autorizado a: ' . ($url ?? uri_string()) . ' por usuario: ' . (session('user_id') ?? 'no logueado'));
            
            // Mostrar mensaje de error y redirigir
            session()->setFlashdata('error', 'No tienes permisos para acceder a esta sección.');
            redirect()->to($redirectTo)->send();
            exit;
        }
    }
}

if (!function_exists('getUserMenuUrls')) {
    /**
     * Obtener todas las URLs permitidas para el usuario actual
     * 
     * @return array Array de URLs permitidas
     */
    function getUserMenuUrls(): array
    {
        $userRole = session('rol_nombre') ?? session('rol_name') ?? null;
        $userId = session('user_id') ?? null;
        
        if (!$userId || !$userRole) {
            return [];
        }
        
        // Obtener el ID del rol desde la base de datos
        $rolModel = new \App\Models\RolModel();
        $rol = $rolModel->where('nombre', $userRole)->first();
        
        if (!$rol) {
            return [];
        }
        
        // Obtener los accesos permitidos para este rol
        $accesoModel = new \App\Models\AccesoModel();
        $accesos = $accesoModel->getAccesosPorRol($rol['id']);
        
        $urls = [];
        foreach ($accesos as $acceso) {
            if (!empty($acceso['menu_url'])) {
                $urls[] = trim($acceso['menu_url'], '/');
            }
        }
        
        return $urls;
    }
}

if (!function_exists('canAccessMenu')) {
    /**
     * Verificar si el usuario puede acceder a un menú específico por su ID
     * 
     * @param int $menuId ID del menú
     * @return bool
     */
    function canAccessMenu(int $menuId): bool
    {
        $userRole = session('rol_nombre') ?? session('rol_name') ?? null;
        $userId = session('user_id') ?? null;
        
        if (!$userId || !$userRole) {
            return false;
        }
        
        // Obtener el ID del rol desde la base de datos
        $rolModel = new \App\Models\RolModel();
        $rol = $rolModel->where('nombre', $userRole)->first();
        
        if (!$rol) {
            return false;
        }
        
        // Verificar si existe el acceso específico
        $accesoModel = new \App\Models\AccesoModel();
        return $accesoModel->where('id_rol', $rol['id'])
                          ->where('id_menu', $menuId)
                          ->where('estado', 1)
                          ->countAllResults() > 0;
    }
}

if (!function_exists('getAccessDebugInfo')) {
    /**
     * Obtener información de debug sobre los accesos del usuario (solo para desarrollo)
     * 
     * @return array
     */
    function getAccessDebugInfo(): array
    {
        $userRole = session('rol_nombre') ?? session('rol_name') ?? null;
        $userId = session('user_id') ?? null;
        
        $debug = [
            'user_id' => $userId,
            'user_role' => $userRole,
            'current_url' => uri_string(),
            'has_access_to_current' => hasAccess(),
            'allowed_urls' => getUserMenuUrls()
        ];
        
        return $debug;
    }
}

/**
 * Función para usar en vistas - verificar si se debe mostrar un elemento
 * 
 * @param string|null $url URL a verificar (null = URL actual)
 * @return bool
 */
if (!function_exists('canShow')) {
    function canShow(?string $url = null): bool
    {
        return hasAccess($url);
    }
}

/**
 * Función para usar en vistas - verificar acceso a múltiples URLs
 * 
 * @param array $urls Array de URLs a verificar
 * @param bool $requireAll Si true, requiere acceso a TODAS las URLs. Si false, requiere acceso a AL MENOS UNA
 * @return bool
 */
if (!function_exists('canShowAny')) {
    function canShowAny(array $urls, bool $requireAll = false): bool
    {
        if (empty($urls)) {
            return true;
        }

        $hasAccess = [];
        foreach ($urls as $url) {
            $hasAccess[] = hasAccess($url);
        }

        if ($requireAll) {
            // Requiere acceso a TODAS las URLs
            return !in_array(false, $hasAccess);
        } else {
            // Requiere acceso a AL MENOS UNA URL
            return in_array(true, $hasAccess);
        }
    }
}

/**
 * Función para generar enlaces condicionalmente basado en permisos
 * 
 * @param string $url URL del enlace
 * @param string $text Texto del enlace
 * @param array $attributes Atributos HTML adicionales
 * @param string $fallbackText Texto a mostrar si no tiene acceso (opcional)
 * @return string HTML del enlace o texto alternativo
 */
if (!function_exists('linkIfAllowed')) {
    function linkIfAllowed(string $url, string $text, array $attributes = [], string $fallbackText = ''): string
    {
        if (hasAccess($url)) {
            $attributeString = '';
            foreach ($attributes as $key => $value) {
                $attributeString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            }
            return '<a href="' . base_url($url) . '"' . $attributeString . '>' . $text . '</a>';
        }
        
        return $fallbackText;
    }
}

/**
 * Obtiene la clase CSS para el badge de estado
 * 
 * @param string $estado El estado actual
 * @return string Clase CSS para el badge
 */
if (!function_exists('getEstadoBadgeClass')) {
    function getEstadoBadgeClass(string $estado): string
    {
        $estado = strtoupper(trim($estado));
        
        $estados = [
            'PENDIENTE' => 'warning',
            'EN_PROCESO' => 'primary',
            'APROBADA' => 'info',
            'FINALIZADA' => 'success',
            'CERRADA' => 'secondary',
            'CANCELADA' => 'danger',
            'RECHAZADA' => 'danger',
            'PENDIENTES' => 'warning',
            'APROBADAS' => 'info',
            'EN PROCESO' => 'primary',
            'FINALIZADAS' => 'success',
            'CANCELADAS' => 'danger',
            'RECHAZADAS' => 'danger',
            'SIN_ASIGNAR' => 'secondary',
            'ASIGNADA' => 'info',
            'EN_CURSO' => 'primary',
            'COMPLETADA' => 'success',
            'SUSPENDIDA' => 'warning'
        ];
        
        return $estados[$estado] ?? 'secondary';
    }
}

/**
 * Función para generar botones condicionalmente basado en permisos
 * 
 * @param string $url URL a la que apunta el botón
 * @param string $text Texto del botón
 * @param string $class Clases CSS adicionales (por defecto: 'btn btn-primary')
 * @param array $attributes Atributos HTML adicionales
 * @return string
 */
if (!function_exists('buttonIfAllowed')) {
    function buttonIfAllowed(string $url, string $text, string $class = 'btn btn-primary', array $attributes = []): string
    {
        if (hasAccess($url)) {
            $attributeString = '';
            foreach ($attributes as $key => $value) {
                $attributeString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            }
            return '<a href="' . base_url($url) . '" class="' . $class . '"' . $attributeString . '>' . $text . '</a>';
        }
        
        return '';
    }
}
