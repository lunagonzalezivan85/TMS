<?php

namespace App\Controllers;

use App\Controllers\SecureController;
use App\Models\RolModel;
use CodeIgniter\HTTP\ResponseInterface;

class Rol extends SecureController
{
    protected $rolModel;
    protected $validation;
    protected $db;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->rolModel = new RolModel();
        $this->validation = \Config\Services::validation();
        $this->db = \Config\Database::connect();
    }

    /**
     * Mostrar lista de roles
     */
    public function index()
    {
        $this->requireAccess('rol');
        
        $data = [
            'title' => 'Gestión de Roles',
            'breadcrumb' => [
                ['name' => 'Inicio', 'url' => base_url('dashboard')],
                ['name' => 'Roles', 'url' => '']
            ]
        ];

        return view('rol/index', $data);
    }

    /**
     * Mostrar formulario para crear nuevo rol
     */
    public function create()
    {
        $data = [
            'title' => 'Crear Rol',
            'breadcrumb' => [
                ['name' => 'Inicio', 'url' => base_url('dashboard')],
                ['name' => 'Roles', 'url' => base_url('rol')],
                ['name' => 'Crear', 'url' => '']
            ],
            'action' => 'create'
        ];

        return view('rol/form', $data);
    }

    /**
     * Procesar creación de rol
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $rules = [
            'nombre' => 'required|min_length[2]|max_length[50]|is_unique[roles.nombre]',
            'descripcion' => 'permit_empty|max_length[200]'
        ];

        $messages = [
            'nombre' => [
                'required' => 'El nombre del rol es obligatorio',
                'min_length' => 'El nombre debe tener al menos 2 caracteres',
                'max_length' => 'El nombre no puede exceder 50 caracteres',
                'is_unique' => 'Este nombre de rol ya existe'
            ],
            'descripcion' => [
                'max_length' => 'La descripción no puede exceder 200 caracteres'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nombre' => trim($this->request->getPost('nombre')),
            'descripcion' => trim($this->request->getPost('descripcion')),
            'usuario_crea'=>session()->get('user_id'),
            'usuario_actualiza'=>session()->get('user_id')
        ];

        try {
            if ($this->rolModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Rol creado exitosamente',
                    'redirect' => base_url('rol')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear el rol',
                    'errors' => $this->rolModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al crear rol: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Mostrar detalles de un rol
     */
    public function show($id = null)
    {
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rol = $this->rolModel->find($id);
        if (!$rol) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Obtener accesos del rol agrupados por menú principal
        $accesosAgrupados = $this->obtenerAccesosAgrupados($id);
        
        // Contar total de accesos
        $totalAccesos = 0;
        foreach ($accesosAgrupados as $grupo) {
            $totalAccesos += count($grupo['submenus']);
        }

        $data = [
            'title' => 'Detalles del Rol',
            'breadcrumb' => [
                ['name' => 'Inicio', 'url' => base_url('dashboard')],
                ['name' => 'Roles', 'url' => base_url('rol')],
                ['name' => 'Detalles', 'url' => '']
            ],
            'rol' => $rol,
            'accesos_agrupados' => $accesosAgrupados,
            'total_accesos' => $totalAccesos
        ];

        return view('rol/show', $data);
    }

    /**
     * Obtener accesos del rol agrupados por menú principal
     */
    private function obtenerAccesosAgrupados($idRol)
    {
        // Obtener todos los accesos activos del rol con información del menú
        $accesos = $this->db->table('accesos')
            ->select('accesos.*, menu.menu, menu.icono, menu.ruta, menu.id_superior')
            ->join('menu', 'menu.id = accesos.id_menu')
            ->where('accesos.id_rol', $idRol)
            ->where('accesos.estado', 1)
            ->where('menu.estado', 1)
            ->orderBy('menu.id', 'ASC')
            ->get()
            ->getResultArray();

        $agrupados = [];
        $menusPrincipales = [];
        $submenus = [];

        // Separar menús principales y submenús
        foreach ($accesos as $acceso) {
            if ($acceso['id_superior'] == 0 || $acceso['id_superior'] == null) {
                // Es un menú principal
                $menusPrincipales[$acceso['id_menu']] = $acceso;
            } else {
                // Es un submenú
                if (!isset($submenus[$acceso['id_superior']])) {
                    $submenus[$acceso['id_superior']] = [];
                }
                $submenus[$acceso['id_superior']][] = $acceso;
            }
        }

        // Agrupar menús principales con sus submenús
        foreach ($menusPrincipales as $idMenu => $menuPrincipal) {
            $agrupados[] = [
                'menu_principal' => $menuPrincipal,
                'submenus' => isset($submenus[$idMenu]) ? $submenus[$idMenu] : []
            ];
        }

        // Agregar submenús huérfanos (que no tienen menú principal asignado al rol)
        foreach ($submenus as $idPadre => $submenusList) {
            if (!isset($menusPrincipales[$idPadre])) {
                // Obtener información del menú padre
                $menuPadre = $this->db->table('menu')
                    ->where('id', $idPadre)
                    ->get()
                    ->getRowArray();

                if ($menuPadre) {
                    $agrupados[] = [
                        'menu_principal' => [
                            'id_menu' => $menuPadre['id'],
                            'menu' => $menuPadre['menu'],
                            'icono' => $menuPadre['icono'],
                            'ruta' => $menuPadre['ruta'],
                            'sin_acceso_directo' => true // Indica que el rol no tiene acceso directo a este menú
                        ],
                        'submenus' => $submenusList
                    ];
                }
            }
        }

        return $agrupados;
    }

    /**
     * Mostrar formulario para editar rol
     */
    public function edit($id = null)
    {
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rol = $this->rolModel->find($id);
        if (!$rol) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Editar Rol',
            'breadcrumb' => [
                ['name' => 'Inicio', 'url' => base_url('dashboard')],
                ['name' => 'Roles', 'url' => base_url('rol')],
                ['name' => 'Editar', 'url' => '']
            ],
            'rol' => $rol,
            'action' => 'edit'
        ];

        return view('rol/form', $data);
    }

    /**
     * Procesar actualización de rol
     */
    public function update($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de rol no válido'
            ]);
        }

        $rol = $this->rolModel->find($id);
        if (!$rol) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rol no encontrado'
            ]);
        }

        $rules = [
            'nombre' => "required|min_length[2]|max_length[50]|is_unique[roles.nombre,id,$id]",
            'descripcion' => 'permit_empty|max_length[200]'
        ];

        $messages = [
            'nombre' => [
                'required' => 'El nombre del rol es obligatorio',
                'min_length' => 'El nombre debe tener al menos 2 caracteres',
                'max_length' => 'El nombre no puede exceder 50 caracteres',
                'is_unique' => 'Este nombre de rol ya existe'
            ],
            'descripcion' => [
                'max_length' => 'La descripción no puede exceder 200 caracteres'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nombre' => trim($this->request->getPost('nombre')),
            'descripcion' => trim($this->request->getPost('descripcion'))
        ];

        try {
            if ($this->rolModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Rol actualizado exitosamente',
                    'redirect' => base_url('rol')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al actualizar el rol',
                    'errors' => $this->rolModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar rol: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Eliminar rol
     */
    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de rol no válido'
            ]);
        }

        $rol = $this->rolModel->find($id);
        if (!$rol) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rol no encontrado'
            ]);
        }

        try {
            // Verificar si el rol se puede eliminar
            if (!$this->rolModel->puedeEliminar($id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se puede eliminar el rol porque tiene usuarios asignados'
                ]);
            }

            if ($this->rolModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Rol eliminado exitosamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al eliminar el rol'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar rol: ' . $e->getMessage());
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
        $start = $this->request->getPost('start') ?? 0;
        $length = $this->request->getPost('length') ?? 10;
        $searchValue = $this->request->getPost('search')['value'] ?? '';

        $builder = $this->rolModel->builder();

        // Búsqueda
        if (!empty($searchValue)) {
            $builder->groupStart()
                    ->like('nombre', $searchValue)
                    ->orLike('descripcion', $searchValue)
                    ->groupEnd();
        }

        // Total de registros filtrados
        $recordsFiltered = $builder->countAllResults(false);

        // Aplicar paginación
        $builder->limit($length, $start);

        // Obtener datos
        $roles = $builder->get()->getResultArray();

        $data = [];
        foreach ($roles as $rol) {
            $data[] = [
                'id' => $rol['id'],
                'nombre' => esc($rol['nombre']),
                'descripcion' => esc($rol['descripcion'] ?? ''),
                'fecha_registro' => date('d/m/Y H:i', strtotime($rol['fecha_registro'])),
                'acciones' => $this->generarBotonesAccion($rol['id'])
            ];
        }

        // Total de registros sin filtrar
        $recordsTotal = $this->rolModel->countAll();

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    /**
     * Verificar si el nombre del rol es único
     */
    public function verificarNombre()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $nombre = $this->request->getPost('nombre');
        $id = $this->request->getPost('id');

        $builder = $this->rolModel->builder();
        $builder->where('nombre', $nombre);
        
        if ($id) {
            $builder->where('id !=', $id);
        }

        $existe = $builder->countAllResults() > 0;

        return $this->response->setJSON([
            'disponible' => !$existe
        ]);
    }

    /**
     * Obtener estadísticas de roles
     */
    public function getEstadisticas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        try {
            $totalRoles = $this->rolModel->countAll();
            $rolesConUsuarios = $this->rolModel->getRolesConUsuarios();
            
            $estadisticas = [
                'total_roles' => $totalRoles,
                'roles_con_usuarios' => count(array_filter($rolesConUsuarios, function($rol) {
                    return $rol['total_usuarios'] > 0;
                })),
                'roles_sin_usuarios' => count(array_filter($rolesConUsuarios, function($rol) {
                    return $rol['total_usuarios'] == 0;
                }))
            ];

            return $this->response->setJSON([
                'success' => true,
                'data' => $estadisticas
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas de roles: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ]);
        }
    }

    /**
     * Generar botones de acción para cada rol
     */
    private function generarBotonesAccion($id)
    {
        $botones = '<div class="btn-group" role="group">';
        
        // Botón Ver
        $botones .= '<a href="' . base_url("rol/show/$id") . '" class="btn btn-info btn-sm" title="Ver detalles">';
        $botones .= '<i class="fas fa-eye"></i>';
        $botones .= '</a>';
        
        // Botón Editar
        $botones .= '<a href="' . base_url("rol/edit/$id") . '" class="btn btn-warning btn-sm" title="Editar">';
        $botones .= '<i class="fas fa-edit"></i>';
        $botones .= '</a>';
        
        // Botón Eliminar
        $botones .= '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarRol(' . $id . ')" title="Eliminar">';
        $botones .= '<i class="fas fa-trash"></i>';
        $botones .= '</button>';
        
        $botones .= '</div>';
        
        return $botones;
    }
}
