<?php

namespace App\Controllers;

use App\Controllers\SecureController;
use App\Models\AccesoModel;
use App\Models\RolModel;
use App\Models\MenuModel;
use CodeIgniter\HTTP\ResponseInterface;

class Acceso extends SecureController
{
    protected $accesoModel;
    protected $rolModel;
    protected $menuModel;
    protected $validation;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->accesoModel = new AccesoModel();
        $this->rolModel = new RolModel();
        $this->menuModel = new MenuModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Mostrar lista de accesos
     */
    public function index()
    {
        $this->requireAccess('acceso');

        $roles = $this->rolModel->orderBy('nombre', 'ASC')->findAll();
        $menus = $this->menuModel->getMenusJerarquicos();

        $data = [
            'title' => 'Gestión de Accesos',
            'breadcrumb' => [
                ['name' => 'Inicio', 'url' => base_url('dashboard')],
                ['name' => 'Accesos', 'url' => '']
            ],
            'roles' => $roles,
            'menus' => $menus
        ];

        return view('acceso/index', $data);
    }

    /**
     * Mostrar formulario para crear nuevo acceso
     */
    public function create()
    {
        $this->requireAccess('acceso/create');
        
        $roles = $this->rolModel->where('estado', 1)->findAll();
        $menus = $this->menuModel->getMenusJerarquicos();

        $data = [
            'title' => 'Crear Acceso',
            'breadcrumb' => [
                ['name' => 'Inicio', 'url' => base_url('dashboard')],
                ['name' => 'Accesos', 'url' => base_url('acceso')],
                ['name' => 'Crear', 'url' => '']
            ],
            'roles' => $roles,
            'menus' => $menus,
            'accesos_existentes' => [],
            'action' => 'create'
        ];

        return view('acceso/form', $data);
    }

    /**
     * Procesar creación de acceso
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $rules = [
            'id_rol' => 'required|integer|is_not_unique[roles.id]',
            'id_menu' => 'required|integer|is_not_unique[menu.id]',
            'estado' => 'required|in_list[0,1]'
        ];

        $messages = [
            'id_rol' => [
                'required' => 'Debe seleccionar un rol',
                'integer' => 'El rol debe ser válido',
                'is_not_unique' => 'El rol seleccionado no existe'
            ],
            'id_menu' => [
                'required' => 'Debe seleccionar un menú',
                'integer' => 'El menú debe ser válido',
                'is_not_unique' => 'El menú seleccionado no existe'
            ],
            'estado' => [
                'required' => 'Debe seleccionar un estado',
                'in_list' => 'El estado debe ser Activo o Inactivo'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $idRol = $this->request->getPost('id_rol');
        $idMenu = $this->request->getPost('id_menu');

        // Verificar si ya existe el acceso
        if ($this->accesoModel->existeAcceso($idRol, $idMenu)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ya existe un acceso para este rol y menú'
            ]);
        }

        $data = [
            'id_rol' => $idRol,
            'id_menu' => $idMenu,
            'estado' => $this->request->getPost('estado'),
            'usuario_crea' => session('user_id') ?? 1,
            'fecha_registro' => date('Y-m-d H:i:s')
        ];

        try {
            if ($this->accesoModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Acceso creado exitosamente',
                    'redirect' => base_url('acceso')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear el acceso',
                    'errors' => $this->accesoModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al crear acceso: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Guardar múltiples accesos para un rol
     */
    public function storeMultiple()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $idRol = $this->request->getPost('id_rol');
        $menuIds = json_decode($this->request->getPost('menu_ids'), true);

        if (!$idRol || empty($menuIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos. Debe seleccionar un rol y al menos un menú.'
            ]);
        }

        // Validar que el rol existe
        $rol = $this->rolModel->find($idRol);
        if (!$rol) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El rol seleccionado no existe'
            ]);
        }

        try {
            // Eliminar accesos existentes del rol
            $this->accesoModel->where('id_rol', $idRol)->delete();

            // Crear nuevos accesos
            $accesosCreados = 0;
            $session = session();
            $usuarioId = $session->get('user_id') ?? 1;

            foreach ($menuIds as $menuId) {
                // Validar que el menú existe
                $menu = $this->menuModel->find($menuId);
                if (!$menu) {
                    continue;
                }

                $data = [
                    'id_rol' => $idRol,
                    'id_menu' => $menuId,
                    'estado' => 1,
                    'usuario_crea' => $usuarioId,
                    'fecha_registro' => date('Y-m-d H:i:s')
                ];

                if ($this->accesoModel->insert($data)) {
                    $accesosCreados++;
                }
            }

            if ($accesosCreados > 0) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Se asignaron {$accesosCreados} menús al rol exitosamente"
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo crear ningún acceso'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al crear accesos múltiples: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Obtener accesos de un rol específico (API)
     */
    public function getAccesosPorRol()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $idRol = $this->request->getPost('id_rol');
            
            if (!$idRol) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de rol requerido'
                ]);
            }

            // Verificar que el rol existe
            $rol = $this->rolModel->find($idRol);
            if (!$rol) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Rol no encontrado'
                ]);
            }

            // Obtener los IDs de menús que tiene acceso el rol
            $accesos = $this->accesoModel->where('id_rol', $idRol)
                                         ->where('accesos.estado', 1)
                                         ->findAll();
            
            $menuIds = array_column($accesos, 'id_menu');
            
            return $this->response->setJSON([
                'success' => true,
                'data' => array_map('intval', $menuIds)
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getAccesosPorRol: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }



    /**
     * Mostrar detalles de un acceso
     */
    public function show($id = null)
    {
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $acceso = $this->accesoModel->select('accesos.*, roles.nombre as rol_nombre, menu.menu as menu_nombre, menu.icono as menu_icono, menu.ruta as menu_url')
                                   ->join('roles', 'roles.id = accesos.id_rol')
                                   ->join('menu', 'menu.id = accesos.id_menu')
                                   ->find($id);

        if (!$acceso) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Detalles del Acceso',
            'breadcrumb' => [
                ['name' => 'Inicio', 'url' => base_url('dashboard')],
                ['name' => 'Accesos', 'url' => base_url('acceso')],
                ['name' => 'Detalles', 'url' => '']
            ],
            'acceso' => $acceso
        ];

        return view('acceso/show', $data);
    }

    /**
     * Mostrar formulario para editar acceso
     */
    public function edit($id = null)
    {
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $acceso = $this->accesoModel->find($id);
        if (!$acceso) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $roles = $this->rolModel->where('estado', 1)->findAll();
        $menus = $this->menuModel->where('estado', 1)->orderBy('nombre', 'ASC')->findAll();

        $data = [
            'title' => 'Editar Acceso',
            'breadcrumb' => [
                ['name' => 'Inicio', 'url' => base_url('dashboard')],
                ['name' => 'Accesos', 'url' => base_url('acceso')],
                ['name' => 'Editar', 'url' => '']
            ],
            'acceso' => $acceso,
            'roles' => $roles,
            'menus' => $menus,
            'action' => 'edit'
        ];

        return view('acceso/form', $data);
    }

    /**
     * Procesar actualización de acceso
     */
    public function update($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de acceso no válido'
            ]);
        }

        $acceso = $this->accesoModel->find($id);
        if (!$acceso) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso no encontrado'
            ]);
        }

        $rules = [
            'id_rol' => 'required|integer|is_not_unique[roles.id]',
            'id_menu' => 'required|integer|is_not_unique[menu.id]',
            'estado' => 'required|in_list[0,1]'
        ];

        $messages = [
            'id_rol' => [
                'required' => 'Debe seleccionar un rol',
                'integer' => 'El rol debe ser válido',
                'is_not_unique' => 'El rol seleccionado no existe'
            ],
            'id_menu' => [
                'required' => 'Debe seleccionar un menú',
                'integer' => 'El menú debe ser válido',
                'is_not_unique' => 'El menú seleccionado no existe'
            ],
            'estado' => [
                'required' => 'Debe seleccionar un estado',
                'in_list' => 'El estado debe ser Activo o Inactivo'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $idRol = $this->request->getPost('id_rol');
        $idMenu = $this->request->getPost('id_menu');

        // Verificar si ya existe el acceso (excluyendo el actual)
        if ($this->accesoModel->existeAcceso($idRol, $idMenu, $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ya existe un acceso para este rol y menú'
            ]);
        }

        $data = [
            'id_rol' => $idRol,
            'id_menu' => $idMenu,
            'estado' => $this->request->getPost('estado'),
            'usuario_actualiza' => session('user_id') ?? 1,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ];

        try {
            if ($this->accesoModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Acceso actualizado exitosamente',
                    'redirect' => base_url('acceso')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el acceso',
                    'errors' => $this->accesoModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar acceso: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Eliminar acceso
     */
    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de acceso no válido'
            ]);
        }

        $acceso = $this->accesoModel->find($id);
        if (!$acceso) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso no encontrado'
            ]);
        }

        try {
            if ($this->accesoModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Acceso eliminado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el acceso'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar acceso: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Cambiar estado del acceso
     */
    public function cambiarEstado($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de acceso no válido'
            ]);
        }

        $acceso = $this->accesoModel->find($id);
        if (!$acceso) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso no encontrado'
            ]);
        }

        $nuevoEstado = $acceso['estado'] == 1 ? 0 : 1;
        $estadoTexto = $nuevoEstado == 1 ? 'activado' : 'desactivado';

        try {
            if ($this->accesoModel->cambiarEstado($id, $nuevoEstado)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Acceso $estadoTexto exitosamente",
                    'nuevo_estado' => $nuevoEstado
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al cambiar el estado del acceso'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar estado del acceso: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Obtener datos para DataTables
     */
    public function getData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $draw = $this->request->getPost('draw');
        $start = (int)($this->request->getPost('start') ?? 0);
        $length = (int)($this->request->getPost('length') ?? 10);
        $searchValue = $this->request->getPost('search')['value'] ?? '';

        $builder = $this->accesoModel->select('accesos.*, roles.nombre as rol_nombre, menu.menu as menu_nombre, menu.icono as menu_icono')
                                    ->join('roles', 'roles.id = accesos.id_rol')
                                    ->join('menu', 'menu.id = accesos.id_menu');

        // Búsqueda
        if (!empty($searchValue)) {
            $builder->groupStart()
                    ->like('roles.nombre', $searchValue)
                    ->orLike('menu.menu', $searchValue)
                    ->groupEnd();
        }

        // Total de registros filtrados
        $recordsFiltered = $builder->countAllResults(false);

        // Aplicar paginación
        $builder->limit($length, $start);

        // Obtener datos
        $accesos = $builder->get()->getResultArray();

        $data = [];
        foreach ($accesos as $acceso) {
            $acciones = '
                <div class="btn-group" role="group">
                    <a href="' . base_url('acceso/show/' . $acceso['id']) . '" class="btn btn-outline-info btn-sm" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="' . base_url('acceso/edit/' . $acceso['id']) . '" class="btn btn-outline-warning btn-sm" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-outline-' . ($acceso['estado'] == 1 ? 'danger' : 'success') . ' btn-sm" 
                            onclick="cambiarEstado(' . $acceso['id'] . ', ' . ($acceso['estado'] == 1 ? 0 : 1) . ')" 
                            title="' . ($acceso['estado'] == 1 ? 'Desactivar' : 'Activar') . '">
                        <i class="fas fa-' . ($acceso['estado'] == 1 ? 'times' : 'check') . '"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" 
                            onclick="eliminarAcceso(' . $acceso['id'] . ')" 
                            title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            
            $data[] = [
                'id' => $acceso['id'],
                'rol_nombre' => esc($acceso['rol_nombre']),
                'menu_nombre' => '<i class="' . esc($acceso['menu_icono']) . '"></i> ' . esc($acceso['menu_nombre']),
                'estado' => $acceso['estado'] == 1 ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>',
                'fecha_registro' => date('d/m/Y H:i', strtotime($acceso['fecha_registro'])),
                'acciones' => $acciones
            ];
        }

        // Total de registros sin filtrar
        $recordsTotal = $this->accesoModel->countAll();

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    /**
     * Obtener estadísticas de accesos
     */
    

    /**
     * Obtener accesos existentes de un rol
     */
    public function getAccesosRol()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $idRol = $this->request->getPost('id_rol');
        
        if (!$idRol) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de rol requerido'
            ]);
        }

        try {
            $accesos = $this->accesoModel->where('id_rol', $idRol)
                                        ->where('accesos.estado', 1)
                                        ->findAll();
            
            $menuIds = array_column($accesos, 'id_menu');
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $menuIds
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener accesos del rol: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener los accesos'
            ]);
        }
    }

    /**
     * Obtener menús disponibles para un rol
     */
    public function getMenusDisponibles()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $idRol = $this->request->getPost('id_rol');
        
        if (!$idRol) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de rol requerido'
            ]);
        }

        try {
            $menus = $this->accesoModel->getMenusDisponiblesPorRol($idRol);

            return $this->response->setJSON([
                'success' => true,
                'data' => $menus
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener menús disponibles: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener menús disponibles'
            ]);
        }
    }

    /**
     * Generar botones de acción para cada acceso
     */
    private function generarBotonesAccion($id, $estado)
    {
        $botones = '<div class="btn-group" role="group">';
        
        // Botón Ver
        $botones .= '<a href="' . base_url("acceso/show/$id") . '" class="btn btn-info btn-sm" title="Ver detalles">';
        $botones .= '<i class="fas fa-eye"></i>';
        $botones .= '</a>';
        
        // Botón Editar
        $botones .= '<a href="' . base_url("acceso/edit/$id") . '" class="btn btn-warning btn-sm" title="Editar">';
        $botones .= '<i class="fas fa-edit"></i>';
        $botones .= '</a>';
        
        // Botón Cambiar Estado
        $estadoClass = $estado == 1 ? 'btn-secondary' : 'btn-success';
        $estadoIcon = $estado == 1 ? 'fa-toggle-off' : 'fa-toggle-on';
        $estadoTitle = $estado == 1 ? 'Desactivar' : 'Activar';
        
        $botones .= '<button type="button" class="btn ' . $estadoClass . ' btn-sm" onclick="cambiarEstadoAcceso(' . $id . ')" title="' . $estadoTitle . '">';
        $botones .= '<i class="fas ' . $estadoIcon . '"></i>';
        $botones .= '</button>';
        
        // Botón Eliminar
        $botones .= '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarAcceso(' . $id . ')" title="Eliminar">';
        $botones .= '<i class="fas fa-trash"></i>';
        $botones .= '</button>';
        
        $botones .= '</div>';
        
        return $botones;
    }

    /**
     * Obtener estadísticas de accesos
     */
    public function getEstadisticas()
    {
        try {
            // Total de accesos
            $totalAccesos = $this->accesoModel->countAll();
            
            // Accesos activos
            $accesosActivos = $this->accesoModel->where('accesos.estado', 1)->countAllResults(false);
            
            // Accesos inactivos
            $accesosInactivos = $totalAccesos - $accesosActivos;
            
            // Accesos por rol (top 5)
            $accesosPorRol = $this->accesoModel
                ->select('roles.nombre as rol_nombre, COUNT(accesos.id) as total_accesos')
                ->join('roles', 'roles.id = accesos.id_rol')
                ->where('accesos.estado', 1)
                ->groupBy('accesos.id_rol, roles.nombre')
                ->orderBy('total_accesos', 'DESC')
                ->limit(5)
                ->findAll();
            
            // Menús más asignados (top 5)
            $menusMasAsignados = $this->accesoModel
                ->select('menu.menu as menu_nombre, menu.icono, COUNT(accesos.id) as total_asignaciones')
                ->join('menu', 'menu.id = accesos.id_menu')
                ->where('accesos.estado', 1)
                ->groupBy('accesos.id_menu, menu.menu, menu.icono')
                ->orderBy('total_asignaciones', 'DESC')
                ->limit(5)
                ->findAll();
            
            // Accesos creados en los últimos 7 días
            $accesosRecientes = $this->accesoModel
                ->where('accesos.fecha_registro >=', date('Y-m-d H:i:s', strtotime('-7 days')))
                ->countAllResults();
            
            // Porcentaje de accesos activos
            $porcentajeActivos = $totalAccesos > 0 ? round(($accesosActivos / $totalAccesos) * 100, 2) : 0;
            
            $estadisticas = [
                'resumen' => [
                    'total_accesos' => $totalAccesos,
                    'accesos_activos' => $accesosActivos,
                    'accesos_inactivos' => $accesosInactivos,
                    'porcentaje_activos' => $porcentajeActivos,
                    'accesos_recientes' => $accesosRecientes
                ],
                'accesos_por_rol' => $accesosPorRol,
                'menus_mas_asignados' => $menusMasAsignados
            ];
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas de accesos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ]);
        }
    }
}
