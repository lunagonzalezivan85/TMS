<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    /**
     * Verificar si el usuario es administrador
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si está logueado
        if (!session()->get('isLoggedIn')) {
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'error' => 'Sesión expirada. Por favor, inicia sesión nuevamente.',
                    'redirect' => base_url('login')
                ])->setStatusCode(401);
            }
            
            return redirect()->to(base_url('login'));
        }

        // Verificar si es administrador
        if (session()->get('rol_name') !== 'Administrador') {
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'error' => 'No tienes permisos para acceder a esta función.'
                ])->setStatusCode(403);
            }
            
            return redirect()->to(base_url('dashboard'))->with('error', 'No tienes permisos para acceder a esta sección');
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
