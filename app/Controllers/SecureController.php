<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Controlador base con validación automática de acceso
 * Extiende este controlador para páginas que requieren autenticación y autorización
 */
abstract class SecureController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['access'];

    /**
     * URLs que no requieren validación de acceso (pueden ser sobrescritas por controladores hijos)
     *
     * @var array
     */
    protected $publicUrls = [];

    /**
     * URL de redirección cuando no hay acceso (puede ser sobrescrita por controladores hijos)
     *
     * @var string
     */
    protected $redirectUrl = 'dashboard';

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        
        // Validar acceso automáticamente
        $this->validateAccess();
    }

    /**
     * Validar acceso a la URL actual
     */
    protected function validateAccess(): void
    {
        $currentUrl = uri_string();
        
        // Verificar si es una URL pública
        if ($this->isPublicUrl($currentUrl)) {
            return;
        }
        
        // Verificar si el usuario está logueado
        if (!session('user_id')) {
            session()->setFlashdata('error', 'Debes iniciar sesión para acceder al sistema.');
            redirect()->to('login')->send();
            exit;
        }
        
        // El rol Administrador tiene acceso total (comparación sin distinción de mayúsculas)
        $rolName = strtolower(trim((string) session('rol_name')));
        if ($rolName === 'administrador') {
            return;
        }

        // Si no tiene rol asignado, permitir acceso solo al dashboard para evitar bucle
        if ($rolName === '') {
            $cleanCurrent = trim($currentUrl, '/');
            if ($cleanCurrent === '' || $cleanCurrent === 'dashboard') {
                return;
            }
            session()->setFlashdata('error', 'Tu usuario no tiene un rol asignado. Contacta al administrador.');
            redirect()->to('dashboard')->send();
            exit;
        }

        // Verificar si tiene acceso a la URL actual
        $hasAccess = hasAccess($currentUrl);
        log_message('debug', 'SecureController::validateAccess - usuario=' . session('user_id') . ' rol=' . session('rol_name') . ' url=' . $currentUrl . ' hasAccess=' . ($hasAccess ? 'true' : 'false'));
        
        if (!$hasAccess) {
            log_message('warning', 'Acceso denegado a: ' . $currentUrl . ' para usuario: ' . session('user_id') . ' (' . (session('nombre') ?? 'Sin nombre') . ') rol=' . session('rol_name'));
            
            // Si está en el dashboard, redirigir al primer menú permitido o al login
            if (trim($currentUrl, '/') === 'dashboard') {
                $urls = getUserMenuUrls();
                log_message('debug', 'SecureController::validateAccess - URLs permitidas para rol ' . session('rol_name') . ': ' . json_encode($urls));
                if (!empty($urls)) {
                    redirect()->to($urls[0])->send();
                    exit;
                }
            }
            
            session()->setFlashdata('error', 'No tienes permisos para acceder a esta sección.');
            $redirectTo = (trim($currentUrl, '/') === trim($this->redirectUrl, '/')) ? 'login' : $this->redirectUrl;
            // Si redirige a login, destruir sesión para evitar bucle infinito (login redirige a dashboard si está logueado)
            if ($redirectTo === 'login') {
                session()->destroy();
            }
            redirect()->to($redirectTo)->send();
            exit;
        }
    }

    /**
     * Verificar si una URL es pública (no requiere autenticación)
     */
    protected function isPublicUrl(string $url): bool
    {
        $defaultPublicUrls = [
            'login',
            'logout', 
            'auth',
            'auth/login',
            'auth/logout',
            'auth/reset-password',
            'reset-password',
            '',
            '/'
        ];
        
        $allPublicUrls = array_merge($defaultPublicUrls, $this->publicUrls);

        $cleanUrl = trim($url, '/');
        if ($cleanUrl === '' || $cleanUrl === '/') {
            return true;
        }

        foreach ($allPublicUrls as $publicUrl) {
            if ($publicUrl === '' || $publicUrl === '/') {
                continue;
            }
            if ($cleanUrl === $publicUrl || strpos($cleanUrl, $publicUrl . '/') === 0) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Verificar acceso a una URL específica (método auxiliar)
     */
    protected function checkAccess(string $url = null): bool
    {
        return hasAccess($url);
    }

    /**
     * Requerir acceso a una URL específica (método auxiliar)
     */
    protected function requireAccess(string $url = null, string $redirectTo = null): void
    {
        requireAccess($url, $redirectTo ?? $this->redirectUrl);
    }

    /**
     * Obtener información de debug sobre accesos (solo para desarrollo)
     */
    protected function getAccessDebugInfo(): array
    {
        return getAccessDebugInfo();
    }

    /**
     * Verificar si el usuario puede acceder a un menú específico
     */
    protected function canAccessMenu(int $menuId): bool
    {
        return canAccessMenu($menuId);
    }

    /**
     * Obtener todas las URLs permitidas para el usuario actual
     */
    protected function getUserMenuUrls(): array
    {
        return getUserMenuUrls();
    }
}
