<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'icono',
        'menu', 
        'id_superior',
        'nivel',
        'ruta',
        'orden',
        'usuario_crea',
        'usuario_edita',
        'fecha_registra',
        'fecha_actualiza'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_registra';
    protected $updatedField = 'fecha_actualiza';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Obtiene todos los menús con estructura jerárquica
     */
    public function getMenusJerarquicos()
    {
        $menus = $this->orderBy('orden', 'ASC')
                     ->findAll();
        
        return $this->buildMenuTree($menus);
    }

    /**
     * Obtiene menús padre (nivel 1)
     */
    public function getMenusPadre()
    {
        return $this->where('nivel', 1)
                   ->where('id_superior IS NULL OR id_superior = 0')
                   ->orderBy('orden', 'ASC')
                   ->findAll();
    }

    /**
     * Obtiene submenús de un menú padre
     */
    public function getSubmenus($idPadre)
    {
        return $this->where('id_superior', $idPadre)
                   ->orderBy('orden', 'ASC')
                   ->findAll();
    }

    /**
     * Construye árbol de menús jerárquico
     */
    private function buildMenuTree($menus, $parentId = null, $level = 1)
    {
        $tree = [];
        
        foreach ($menus as $menu) {
            if (($parentId === null && ($menu['id_superior'] === null || $menu['id_superior'] == 0)) ||
                ($parentId !== null && $menu['id_superior'] == $parentId)) {
                
                $menu['children'] = $this->buildMenuTree($menus, $menu['id'], $level + 1);
                $tree[] = $menu;
            }
        }
        
        return $tree;
    }

    /**
     * Obtiene el nivel máximo de un menú padre
     */
    public function getNivelMaximo($idPadre = null)
    {
        $builder = $this->builder();
        
        if ($idPadre) {
            $builder->where('id_superior', $idPadre);
        } else {
            $builder->where('id_superior IS NULL OR id_superior = 0');
        }
        
        $result = $builder->selectMax('nivel')->get()->getRowArray();
        return $result['nivel'] ?? 0;
    }

    /**
     * Verifica si un menú tiene submenús
     */
    public function tieneSubmenus($idMenu)
    {
        $count = $this->where('id_superior', $idMenu)->countAllResults();
        return $count > 0;
    }

    /**
     * Obtiene la ruta completa de un menú (breadcrumb)
     */
    public function getRutaCompleta($idMenu)
    {
        $menu = $this->find($idMenu);
        if (!$menu) {
            return [];
        }

        $ruta = [$menu];
        
        while ($menu['id_superior'] && $menu['id_superior'] > 0) {
            $menu = $this->find($menu['id_superior']);
            if ($menu) {
                array_unshift($ruta, $menu);
            } else {
                break;
            }
        }
        
        return $ruta;
    }

    /**
     * Obtiene menús principales para select/dropdown (solo menús con id_superior NULL o 0)
     */
    public function getMenusParaSelect($excluirId = null)
    {
        $builder = $this->builder();
        
        // Filtrar solo menús principales (id_superior NULL o 0)
        $builder->groupStart()
                ->where('id_superior IS NULL')
                ->orWhere('id_superior', 0)
                ->groupEnd();
        
        if ($excluirId) {
            $builder->where('id !=', $excluirId);
        }
        
        $menus = $builder->orderBy('menu', 'ASC')
                        ->get()
                        ->getResultArray();
        
        $options = ['' => '-- Menú Principal (Sin Superior) --'];
        
        foreach ($menus as $menu) {
            $options[$menu['id']] = $menu['menu'];
        }
        
        return $options;
    }

    /**
     * Valida que no se cree un ciclo en la jerarquía
     */
    public function validarJerarquia($idMenu, $idPadre)
    {
        if (!$idPadre || $idPadre == 0) {
            return true; // Es menú raíz, no hay problema
        }
        
        if ($idMenu == $idPadre) {
            return false; // Un menú no puede ser padre de sí mismo
        }
        
        // Verificar que el padre propuesto no sea descendiente del menú actual
        $descendientes = $this->getDescendientes($idMenu);
        
        foreach ($descendientes as $descendiente) {
            if ($descendiente['id'] == $idPadre) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Obtiene todos los descendientes de un menú
     */
    private function getDescendientes($idMenu)
    {
        $descendientes = [];
        $hijos = $this->where('id_superior', $idMenu)->findAll();
        
        foreach ($hijos as $hijo) {
            $descendientes[] = $hijo;
            $descendientes = array_merge($descendientes, $this->getDescendientes($hijo['id']));
        }
        
        return $descendientes;
    }

    /**
     * Actualiza el nivel de un menú y sus descendientes
     */
    public function actualizarNiveles($idMenu)
    {
        $menu = $this->find($idMenu);
        if (!$menu) {
            return false;
        }
        
        // Calcular el nuevo nivel
        $nuevoNivel = 1;
        if ($menu['id_superior'] && $menu['id_superior'] > 0) {
            $padre = $this->find($menu['id_superior']);
            if ($padre) {
                $nuevoNivel = $padre['nivel'] + 1;
            }
        }
        
        // Actualizar el nivel del menú actual
        $this->update($idMenu, ['nivel' => $nuevoNivel]);
        
        // Actualizar niveles de descendientes recursivamente
        $this->actualizarNivelesDescendientes($idMenu, $nuevoNivel);
        
        return true;
    }

    /**
     * Actualiza niveles de descendientes recursivamente
     */
    private function actualizarNivelesDescendientes($idPadre, $nivelPadre)
    {
        $hijos = $this->where('id_superior', $idPadre)->findAll();
        
        foreach ($hijos as $hijo) {
            $nuevoNivel = $nivelPadre + 1;
            $this->update($hijo['id'], ['nivel' => $nuevoNivel]);
            $this->actualizarNivelesDescendientes($hijo['id'], $nuevoNivel);
        }
    }

    /**
     * Obtiene estadísticas de menús
     */
    public function getEstadisticas()
    {
        $stats = [
            'total_menus' => $this->countAllResults(false),
            'menus_padre' => $this->where('nivel', 1)->countAllResults(false),
            'submenus' => $this->where('nivel >', 1)->countAllResults(false),
            'nivel_maximo' => $this->selectMax('nivel')->get()->getRowArray()['nivel'] ?? 0
        ];
        
        return $stats;
    }
}
