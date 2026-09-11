<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Verificar si el usuario está autenticado
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        log_message('info', 'AuthFilter::before - URI: ' . $request->getUri());
        
        // Verificar si el usuario está logueado
        if (!session()->get('isLoggedIn')) {
            log_message('info', 'AuthFilter::before - Usuario no autenticado');
            // Si es una petición AJAX, devolver JSON
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'error' => 'Sesión expirada. Por favor, inicia sesión nuevamente.',
                    'redirect' => base_url('login')
                ])->setStatusCode(401);
            }
            
            // Redirigir al login
            return redirect()->to(base_url('login'))->with('error', 'Debes iniciar sesión para acceder a esta página');
        }

        // Verificar si el usuario está activo
        $authModel = new \App\Models\AuthModel();
        $usuario = $authModel->find(session()->get('user_id'));
        
        $estadoActivo = !$usuario
            ? false
            : (strtolower((string)($usuario['estado'] ?? '')) === 'activo' || (string)($usuario['estado'] ?? '') === '1');

        if (!$estadoActivo) {
            session()->destroy();
            
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'error' => 'Tu cuenta ha sido desactivada. Contacta al administrador.',
                    'redirect' => base_url('login')
                ])->setStatusCode(401);
            }
            
            return redirect()->to(base_url('login'))->with('error', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
        }
    }

    /**
     * Ejecutar después de la respuesta
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada después
    }
}
