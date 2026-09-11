<?php

namespace App\Controllers;

use App\Controllers\SecureController;
use App\Models\MenuModel;
use Config\FontAwesome;

class Menu extends SecureController
{
    protected $menuModel;
    protected $fontAwesome;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->menuModel = new MenuModel();
        $this->fontAwesome = new FontAwesome();
    }

    /**
     * Lista principal de menús
     */
    public function index()
    {
        $this->requireAccess('menu');
        
        $data = [
            'title' => 'Gestión de Menús',
            'menus' => $this->menuModel->getMenusJerarquicos(),
            'estadisticas' => $this->menuModel->getEstadisticas()
        ];

        return view('menu/index', $data);
    }

    /**
     * Formulario para crear nuevo menú
     */
    public function create()
    {
        $data = [
            'title' => 'Crear Menú',
            'iconos' => $this->fontAwesome->getAllIcons(),
            'menus_padre' => $this->menuModel->getMenusParaSelect(),
            'menu' => null
        ];

        return view('menu/form', $data);
    }

    /**
     * Procesar creación de menú
     */
    public function store()
    {
        $rules = [
            'menu' => 'required|min_length[2]|max_length[100]',
            'icono' => 'required',
            'ruta' => 'required|min_length[1]|max_length[100]|regex_match[/^[a-zA-Z0-9\-_\/]+$/]',
            'id_superior' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'menu' => $this->request->getPost('menu'),
            'icono' => $this->request->getPost('icono'),
            'ruta' => $this->request->getPost('ruta'),
            'id_superior' => $this->request->getPost('id_superior') ?: null,
            'usuario_crea' => session('user_id')
        ];

        // Calcular nivel
        if ($data['id_superior']) {
            $padre = $this->menuModel->find($data['id_superior']);
            $data['nivel'] = $padre ? $padre['nivel'] + 1 : 1;
        } else {
            $data['nivel'] = 1;
        }

        // Validar icono existe
        if (!$this->fontAwesome->iconExists($data['icono'])) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'El icono seleccionado no es válido.');
        }

        try {
            $this->menuModel->insert($data);
            return redirect()->to('/menu')
                           ->with('success', 'Menú creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear el menú: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalles de un menú
     */
    public function show($id)
    {
        $menu = $this->menuModel->find($id);
        
        if (!$menu) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Menú no encontrado');
        }

        $menuSuperior = null;
        if ($menu['id_superior']) {
            $menuSuperior = $this->menuModel->find($menu['id_superior']);
        }

        $data = [
            'title' => 'Detalles del Menú',
            'menu' => $menu,
            'menu_superior' => $menuSuperior,
            'ruta_completa' => $this->menuModel->getRutaCompleta($id),
            'submenus' => $this->menuModel->getSubmenus($id),
            'tiene_submenus' => $this->menuModel->tieneSubmenus($id),
            'nombre_icono' => $this->fontAwesome->getIconName($menu['icono']),
            'icon_name' => $this->fontAwesome->getIconName($menu['icono'])
        ];

        return view('menu/show', $data);
    }

    /**
     * Formulario para editar menú
     */
    public function edit($id)
    {
        $menu = $this->menuModel->find($id);
        
        if (!$menu) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Menú no encontrado');
        }

        $data = [
            'title' => 'Editar Menú',
            'iconos' => $this->fontAwesome->getAllIcons(),
            'menus_padre' => $this->menuModel->getMenusParaSelect($id),
            'menu' => $menu
        ];

        return view('menu/form', $data);
    }

    /**
     * Procesar actualización de menú
     */
    public function update($id)
    {
        $menu = $this->menuModel->find($id);
        
        if (!$menu) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Menú no encontrado');
        }

        $rules = [
            'menu' => 'required|min_length[2]|max_length[100]',
            'icono' => 'required',
            'ruta' => 'required|min_length[1]|max_length[100]|regex_match[/^[a-zA-Z0-9\-_\/]+$/]',
            'id_superior' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $idSuperior = $this->request->getPost('id_superior') ?: null;

        // Validar jerarquía para evitar ciclos
        if (!$this->menuModel->validarJerarquia($id, $idSuperior)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'No se puede crear un ciclo en la jerarquía de menús.');
        }

        $data = [
            'menu' => $this->request->getPost('menu'),
            'icono' => $this->request->getPost('icono'),
            'ruta' => $this->request->getPost('ruta'),
            'id_superior' => $idSuperior,
            'usuario_edita' => session('user_id')
        ];

        // Validar icono existe
        if (!$this->fontAwesome->iconExists($data['icono'])) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'El icono seleccionado no es válido.');
        }

        try {
            $this->menuModel->update($id, $data);
            
            // Actualizar niveles si cambió el padre
            if ($menu['id_superior'] != $idSuperior) {
                $this->menuModel->actualizarNiveles($id);
            }
            
            return redirect()->to('/menu')
                           ->with('success', 'Menú actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar el menú: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar menú
     */
    public function delete($id)
    {
        $menu = $this->menuModel->find($id);
        
        if (!$menu) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Menú no encontrado'
            ]);
        }

        // Verificar si tiene submenús
        if ($this->menuModel->tieneSubmenus($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se puede eliminar un menú que tiene submenús. Elimine primero los submenús.'
            ]);
        }

        try {
            $this->menuModel->delete($id);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Menú eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar el menú: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener datos para DataTables (AJAX)
     */
    public function getData()
    {
        $request = $this->request;
        
        // Parámetros de DataTables
        $draw = $request->getPost('draw');
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $searchValue = $request->getPost('search')['value'] ?? '';
        
        // Construir consulta
        $builder = $this->menuModel->builder();
        
        // Búsqueda
        if (!empty($searchValue)) {
            $builder->groupStart()
               ->like('menu', $searchValue)
               ->orLike('icono', $searchValue)
               ->orLike('ruta', $searchValue)
               ->groupEnd();
        }
        
        // Total de registros filtrados
        $recordsFiltered = $builder->countAllResults(false);
        
        // Aplicar paginación y ordenamiento
        $builder->orderBy('nivel', 'ASC')
               ->orderBy('menu', 'ASC')
               ->limit($length, $start);
        
        $menus = $builder->get()->getResultArray();
        
        // Total de registros sin filtrar
        $recordsTotal = $this->menuModel->countAllResults(false);
        
        // Formatear datos para DataTables
        $data = [];
        foreach ($menus as $menu) {
            $padre = '';
            if ($menu['id_superior']) {
                $menuPadre = $this->menuModel->find($menu['id_superior']);
                $padre = $menuPadre ? $menuPadre['menu'] : 'N/A';
            }
            
            $tieneSubmenus = $this->menuModel->tieneSubmenus($menu['id']);
            $nombreIcono = $this->fontAwesome->getIconName($menu['icono']);
            
            $data[] = [
                'id' => $menu['id'],
                'icono' => '<i class="' . esc($menu['icono']) . '"></i>',
                'menu' => esc($menu['menu']),
                'ruta' => esc($menu['ruta']),
                'padre' => esc($padre),
                'nivel' => $menu['nivel'],
                'submenus' => $tieneSubmenus ? 'Sí' : 'No',
                'nombre_icono' => esc($nombreIcono),
                'fecha_registra' => date('d/m/Y H:i', strtotime($menu['fecha_registra'])),
                'acciones' => $this->generarBotonesAccion($menu['id'], $tieneSubmenus)
            ];
        }
        
        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    /**
     * Generar botones de acción para DataTables
     */
    private function generarBotonesAccion($id, $tieneSubmenus)
    {
        $botones = '<div class="btn-group" role="group">';
        
        // Botón Ver
        $botones .= '<a href="' . base_url('menu/show/' . $id) . '" class="btn btn-sm btn-outline-info" title="Ver detalles">';
        $botones .= '<i class="fas fa-eye"></i>';
        $botones .= '</a>';
        
        // Botón Editar
        $botones .= '<a href="' . base_url('menu/edit/' . $id) . '" class="btn btn-sm btn-outline-warning" title="Editar">';
        $botones .= '<i class="fas fa-edit"></i>';
        $botones .= '</a>';
        
        // Botón Eliminar (solo si no tiene submenús)
        if (!$tieneSubmenus) {
            $botones .= '<button type="button" class="btn btn-sm btn-outline-danger eliminar-menu" ';
            $botones .= 'data-id="' . $id . '" title="Eliminar">';
            $botones .= '<i class="fas fa-trash"></i>';
            $botones .= '</button>';
        } else {
            $botones .= '<button type="button" class="btn btn-sm btn-outline-secondary" disabled title="No se puede eliminar: tiene submenús">';
            $botones .= '<i class="fas fa-ban"></i>';
            $botones .= '</button>';
        }
        
        $botones .= '</div>';
        
        return $botones;
    }

    /**
     * Obtener estructura de menús para vista en árbol
     */
    public function getMenuTree()
    {
        $menus = $this->menuModel->getMenusJerarquicos();
        return $this->response->setJSON($menus);
    }

    /**
     * Reordenar menús (para futuro drag & drop)
     */
    public function reorder()
    {
        $orden = $this->request->getJSON(true);
        
        if (!$orden) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos de ordenamiento no válidos'
            ]);
        }
        
        try {
            foreach ($orden as $item) {
                $this->menuModel->update($item['id'], [
                    'id_superior' => $item['parent'] ?: null,
                    'usuario_edita' => session('user_id')
                ]);
                
                // Actualizar niveles
                $this->menuModel->actualizarNiveles($item['id']);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Menús reordenados exitosamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al reordenar menús: ' . $e->getMessage()
            ]);
        }
    }
}
