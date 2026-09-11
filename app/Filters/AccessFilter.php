<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AccessFilter implements FilterInterface
{
    /**
     * Verificar acceso antes de ejecutar el controlador
     *
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cargar el helper de acceso
        helper('access');
        
        // Obtener la URL actual
        $currentUrl = uri_string();
        
        // URLs que no requieren validación de acceso (públicas)
        $publicUrls = [
            'login',
            'logout',
            'auth',
            'auth/login',
            'auth/logout',
            'auth/reset-password',
            'reset-password',
            'register',
            'register/step1',
            'register/step2',
            'dashboard',
        ];

        // Si es una URL pública, permitir acceso (solo coincidencia exacta o sub-ruta con prefijo no vacío)
        $cleanUrl = trim($currentUrl, '/');
        if ($cleanUrl === '' || $cleanUrl === '/') {
            return;
        }
        foreach ($publicUrls as $publicUrl) {
            if ($cleanUrl === $publicUrl || strpos($cleanUrl, $publicUrl . '/') === 0) {
                return;
            }
        }
        
        // Verificar si el usuario está logueado
        if (!session('user_id')) {
            // Usuario no logueado, redirigir al login
            session()->setFlashdata('error', 'Debes iniciar sesión para acceder al sistema.');
            return redirect()->to('login');
        }
        
        // El rol Administrador tiene acceso total (sin distinción de mayúsculas)
        if (strtolower(trim((string) session('rol_name'))) === 'administrador') {
            return;
        }

        // Verificar si tiene acceso a la URL actual
        if (!hasAccess($currentUrl)) {
            // Log del intento de acceso no autorizado
            log_message('warning', 'Acceso denegado a: ' . $currentUrl . ' para usuario: ' . session('user_id') . ' (' . (session('nombre') ?? 'Sin nombre') . ')');
            
            // Evitar loop: si ya está en dashboard, redirigir al login
            $redirectTo = (trim($currentUrl, '/') === 'dashboard') ? 'login' : 'dashboard';
            session()->setFlashdata('error', 'No tienes permisos para acceder a esta sección.');
            return redirect()->to($redirectTo);
        }
        
        // Si llegamos aquí, el usuario tiene acceso
        return;
    }

    /**
     * Ejecutar después del controlador (no usado en este caso)
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos hacer nada después
        return;
    }
}
