<?php

namespace App\Controllers;

use App\Models\CatalogoModel;
use CodeIgniter\HTTP\ResponseInterface;

class Catalogo extends BaseController
{
    protected $catalogoModel;

    public function __construct()
    {
        $this->catalogoModel = new CatalogoModel();
    }

    /**
     * Mostrar listado de catálogos
     */
    public function index()
    {
        $search = $this->request->getGet('search') ?? '';
        $perPage = 10;
        $page = $this->request->getGet('page') ?? 1;

        $data = [
            'title' => 'Catálogo',
            'catalogos' => $this->catalogoModel->getCatalogos($search, $perPage, $page),
            'pager' => $this->catalogoModel->pager,
            'search' => $search,
            'estadisticas' => $this->catalogoModel->getEstadisticas()
        ];

        return view('catalogo/index', $data);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $idSuperior = $this->request->getGet('id_superior');
        $catalogoPadre = null;
        
        // Si viene un id_superior, obtener información del catálogo padre
        if ($idSuperior) {
            $catalogoPadre = $this->catalogoModel->getCatalogoById($idSuperior);
            if (!$catalogoPadre) {
                return redirect()->to('/catalogo')
                               ->with('error', 'El catálogo padre seleccionado no existe.');
            }
        }
        
        $data = [
            'title' => $catalogoPadre ? 'Nuevo Subcatálogo' : 'Nuevo Catálogo',
            'catalogos_padre' => $this->catalogoModel->getCatalogosPadre(),
            'catalogo_padre' => $catalogoPadre,
            'id_superior_preseleccionado' => $idSuperior
        ];

        return view('catalogo/create', $data);
    }

    /**
     * Procesar creación de catálogo
     */
    public function store()
    {
        $rules = [
            'nombre' => 'required|max_length[255]',
            'descripcion' => 'permit_empty|max_length[500]',
            'id_superior' => 'permit_empty|numeric',
            'estado' => 'required|in_list[1,0]',
            'referencia' => 'permit_empty|max_length[100]',
            'referencia2' => 'permit_empty|max_length[100]',
            'edicion' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Debug: Log de datos recibidos
        log_message('debug', 'Datos recibidos para crear catálogo: ' . json_encode($data));
        
        // Verificar sesión y establecer campos de auditoría
        $session = session();
        if (!$session->get('user_id')) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error de sesión: Usuario no identificado.');
        }
        
        if (!$session->get('empresa_id')) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error de sesión: Empresa no identificada.');
        }
        
        // Establecer campos requeridos antes de la validación
        $data['idempresa'] = $session->get('empresa_id');
        $data['usuario_crea'] = $session->get('user_id');
        $data['fecha_registro'] = date('Y-m-d H:i:s');
        
        // Debug adicional: verificar estructura de datos
        log_message('debug', 'Datos después de agregar campos de sesión: ' . json_encode($data));
        
        // Generar código automáticamente
        $idSuperior = empty($data['id_superior']) || $data['id_superior'] === '' || $data['id_superior'] == 0 ? null : $data['id_superior'];
        $data['codigo'] = $this->catalogoModel->generarCodigo($idSuperior);
        
        // Configurar jerarquía
        if ($idSuperior === null) {
            $data['id_superior'] = null;
            $data['nivel'] = 1;
            log_message('info', 'Catálogo principal - Código generado: ' . $data['codigo']);
        } else {
            // Si tiene padre, configurar nivel
            $padre = $this->catalogoModel->find($idSuperior);
            if ($padre) {
                $data['id_superior'] = $idSuperior;
                $data['nivel'] = ($padre['nivel'] ?? 0) + 1;
                log_message('info', 'Subcatálogo - Código heredado del padre ID ' . $idSuperior . ': ' . $data['codigo']);
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'El catálogo superior seleccionado no existe.');
            }
        }

        // Nota: No validamos código duplicado porque los subcatálogos heredan el código del padre
        // El código repetido sirve como filtro para agrupar catálogos relacionados

        // Log de datos antes de insertar
        log_message('debug', 'Datos finales antes de insertar: ' . json_encode($data));
        
        try {
            $insertId = $this->catalogoModel->insert($data);
            
            if ($insertId) {
                log_message('info', 'Catálogo creado exitosamente con ID: ' . $insertId);
                return redirect()->to('/catalogo')
                               ->with('success', 'Catálogo creado exitosamente.');
            } else {
                // Obtener errores específicos del modelo
                $errors = $this->catalogoModel->errors();
                $errorMessage = 'Error al crear el catálogo.';
                
                if (!empty($errors)) {
                    $errorMessage .= ' Detalles: ' . implode(', ', $errors);
                } else {
                    $errorMessage .= ' No se pudieron obtener detalles específicos del error.';
                }
                
                log_message('error', 'Error al crear catálogo - Errores del modelo: ' . json_encode($errors));
                log_message('error', 'Datos que causaron el error: ' . json_encode($data));
                
                return redirect()->back()
                               ->withInput()
                               ->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error interno del servidor: ' . $e->getMessage();
            log_message('error', 'Excepción al crear catálogo: ' . $e->getMessage() . ' - Línea: ' . $e->getLine() . ' - Archivo: ' . $e->getFile());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', $errorMessage);
        }
    }

    /**
     * Mostrar detalle de catálogo
     */
    public function show($id)
    {
        $catalogo = $this->catalogoModel->getCatalogoById($id);
        
        if (!$catalogo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Catálogo no encontrado');
        }

        $data = [
            'title' => 'Detalle del Catálogo',
            'catalogo' => $catalogo,
            'subcatalogos' => $this->catalogoModel->getSubcatalogos($id)
        ];

        return view('catalogo/show', $data);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $catalogo = $this->catalogoModel->getCatalogoById($id);
        
        if (!$catalogo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Catálogo no encontrado');
        }

        $data = [
            'title' => 'Editar Catálogo',
            'catalogo' => $catalogo,
            'catalogos_padre' => $this->catalogoModel->getCatalogosPadre($id)
        ];

        return view('catalogo/edit', $data);
    }

    /**
     * Procesar actualización de catálogo
     */
    public function update($id)
    {
        $catalogo = $this->catalogoModel->find($id);
        
        if (!$catalogo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Catálogo no encontrado');
        }

        $rules = [
            'nombre' => 'required|max_length[255]',
            'descripcion' => 'permit_empty|max_length[500]',
            'id_superior' => 'permit_empty|numeric',
            'estado' => 'required|in_list[1,0]',
            'referencia' => 'permit_empty|max_length[100]',
            'nivel' => 'permit_empty|numeric',
            'referencia2' => 'permit_empty|max_length[100]',
            'edicion' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Regenerar código si cambió la jerarquía
        $idSuperiorActual = $catalogo['id_superior'];
        $idSuperiorNuevo = empty($data['id_superior']) || $data['id_superior'] === '' || $data['id_superior'] == 0 ? null : $data['id_superior'];
        
        // Si cambió la jerarquía, regenerar código
        if ($idSuperiorActual != $idSuperiorNuevo) {
            $data['codigo'] = $this->catalogoModel->generarCodigo($idSuperiorNuevo);
            log_message('info', 'Actualización - Jerarquía cambió, código regenerado: ' . $data['codigo']);
        } else {
            // Mantener el código actual si no cambió la jerarquía
            $data['codigo'] = $catalogo['codigo'];
        }
        
        // Configurar jerarquía
        if ($idSuperiorNuevo === null) {
            $data['id_superior'] = null;
            $data['nivel'] = 1;
        } else {
            $padre = $this->catalogoModel->find($idSuperiorNuevo);
            if ($padre) {
                $data['id_superior'] = $idSuperiorNuevo;
                $data['nivel'] = ($padre['nivel'] ?? 0) + 1;
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'El catálogo superior seleccionado no existe.');
            }
        }

        try {
            if ($this->catalogoModel->update($id, $data)) {
                return redirect()->to('/catalogo')
                               ->with('success', 'Catálogo actualizado exitosamente.');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Error al actualizar el catálogo.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar catálogo: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error interno del servidor.');
        }
    }

    /**
     * Eliminar catálogo
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $catalogo = $this->catalogoModel->find($id);
        
        if (!$catalogo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Catálogo no encontrado'
            ]);
        }

        // Verificar si tiene subcatálogos
        $subcatalogos = $this->catalogoModel->getSubcatalogos($id);
        if (!empty($subcatalogos)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se puede eliminar el catálogo porque tiene subcatálogos asociados'
            ]);
        }

        try {
            if ($this->catalogoModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Catálogo eliminado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el catálogo'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar catálogo: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Cambiar estado del catálogo
     */
    public function cambiarEstado()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $id = $this->request->getPost('id');
        $estado = $this->request->getPost('estado');

        if (!$id || !in_array($estado, ['1', '0', 1, 0])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos'
            ]);
        }

        $catalogo = $this->catalogoModel->find($id);
        
        if (!$catalogo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Catálogo no encontrado'
            ]);
        }

        try {
            if ($this->catalogoModel->update($id, ['estado' => $estado])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estado actualizado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el estado'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar estado del catálogo: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Obtener subcatálogos por AJAX
     */
    public function getSubcatalogos($idPadre)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $subcatalogos = $this->catalogoModel->getSubcatalogos($idPadre);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $subcatalogos
        ]);
    }

    /**
     * Verificar si un código existe (AJAX)
     */
    public function verificarCodigo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Acceso no autorizado']);
        }

        $codigo = $this->request->getPost('codigo');
        $excludeId = $this->request->getPost('exclude_id');

        $exists = $this->catalogoModel->codigoExists($codigo, $excludeId);
        
        return $this->response->setJSON([
            'exists' => $exists
        ]);
    }

    /**
     * Obtener catálogos por código (AJAX)
     */
    public function getCatalogosPorCodigo($codigo)
    {
        $isAjax = $this->request->isAJAX();
        $headers = $this->request->getHeaders();
        
        // Debug information
        log_message('debug', '=== getCatalogosPorCodigo ===');
        log_message('debug', 'Code: ' . $codigo);
        log_message('debug', 'Is AJAX: ' . ($isAjax ? 'true' : 'false'));
        log_message('debug', 'Method: ' . $this->request->getMethod());
        log_message('debug', 'Headers: ' . print_r($headers, true));
        
        // En desarrollo, permitir peticiones sin AJAX para pruebas
        if (ENVIRONMENT === 'development') {
            log_message('debug', 'Running in development mode - AJAX check bypassed');
        } 
        // En producción, requerir petición AJAX
        elseif (!$isAjax) {
            log_message('warning', 'Intento de acceso no autorizado a getCatalogosPorCodigo');
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Acceso no autorizado',
                'debug' => ENVIRONMENT === 'development' ? [
                    'isAjax' => $isAjax,
                    'method' => $this->request->getMethod(),
                    'headers' => $headers
                ] : null
            ])->setStatusCode(403);
        }
        
        // En desarrollo, no verificar CSRF para facilitar pruebas
        if (ENVIRONMENT !== 'development' && config('App')->CSRFProtection === 'session') {
            $csrfToken = $this->request->getHeaderLine('X-CSRF-TOKEN');
            if (empty($csrfToken)) {
                $csrfToken = $this->request->getGetPost('csrf_token');
            }
            
            if (!csrf_verify($csrfToken)) {
                log_message('warning', 'Token CSRF inválido o faltante');
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Token de seguridad inválido',
                    'debug' => ENVIRONMENT === 'development' ? [
                        'csrf_token_received' => $csrfToken,
                        'expected_token' => csrf_hash()
                    ] : null
                ])->setStatusCode(403);
            }
        }

        try {
            $catalogos = $this->catalogoModel->getCatalogosPorCodigo($codigo);
            return $this->response->setJSON([
                'success' => true,
                'data' => $catalogos
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en getCatalogosPorCodigo: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al obtener los catálogos',
                'debug' => ENVIRONMENT === 'development' ? $e->getMessage() : null
            ])->setStatusCode(500);
        }
    }
}
