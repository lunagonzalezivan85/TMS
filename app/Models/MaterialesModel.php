<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialesModel extends Model
{
    protected $table = 'materiales';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id_empresa',
        'codigo_consecutivo',
        'codigo_vinculacion',
        'nombre',
        'unidad_medida',
        'costo_unitario',
        'fechaRegistro',
        'fechaUpdate',
        'usuarioCrea',
        'usuarioEdita'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'fechaRegistro';
    protected $updatedField = 'fechaUpdate';
    protected $skipValidation = false;
    
    // Validaciones
    protected $validationRules = [
        'id_empresa' => 'required|integer',
        'nombre' => 'required|min_length[3]|max_length[255]',
        'unidad_medida' => 'required|max_length[50]',
        'costo_unitario' => 'required|decimal|greater_than_equal_to[0]',
        'usuarioCrea' => 'required|integer'
    ];

    protected $validationMessages = [
        'id_empresa' => [
            'required' => 'La empresa es requerida',
            'integer' => 'La empresa debe ser un número válido'
        ],
        'nombre' => [
            'required' => 'El nombre del material es requerido',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 255 caracteres'
        ],
        'unidad_medida' => [
            'required' => 'La unidad de medida es requerida',
            'max_length' => 'La unidad de medida no puede exceder 50 caracteres'
        ],
        'costo_unitario' => [
            'required' => 'El costo unitario es requerido',
            'decimal' => 'El costo unitario debe ser un número válido',
            'greater_than_equal_to' => 'El costo unitario debe ser mayor o igual a 0'
        ],
        'usuarioCrea' => [
            'required' => 'El usuario creador es requerido',
            'integer' => 'El usuario creador debe ser un número válido'
        ]
    ];

    /**
     * Obtener materiales por empresa con paginación
     */
    public function getMaterialesPorEmpresa($idEmpresa, $limite = 20, $offset = 0, $busqueda = null)
    {
        $builder = $this->db->table('materiales m')
            ->select('m.*, 
                    CONCAT(u1.nombre, " ", u1.apellido) as nombre_creador,
                    CONCAT(u2.nombre, " ", u2.apellido) as nombre_editor')
            ->join('usuarios u1', 'u1.id = m.usuarioCrea', 'left')
            ->join('usuarios u2', 'u2.id = m.usuarioEdita', 'left')
            ->where('m.id_empresa', $idEmpresa);
        
        if ($busqueda) {
            $builder->groupStart()
                   ->like('m.nombre', $busqueda)
                   ->orLike('m.codigo_consecutivo', $busqueda)
                   ->orLike('m.unidad_medida', $busqueda)
                   ->groupEnd();
        }
        
        return $builder->orderBy('m.fechaRegistro', 'DESC')
                      ->limit($limite, $offset)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Contar materiales por empresa
     */
    public function contarMaterialesPorEmpresa($idEmpresa, $busqueda = null)
    {
        $builder = $this->where('id_empresa', $idEmpresa);
        
        if ($busqueda) {
            $builder->groupStart()
                   ->like('nombre', $busqueda)
                   ->orLike('codigo_consecutivo', $busqueda)
                   ->orLike('unidad_medida', $busqueda)
                   ->groupEnd();
        }
        
        return $builder->countAllResults();
    }

    /**
     * Obtener material por ID con información del usuario
     */
    public function getMaterialConUsuarios($idMaterial, $idEmpresa = null)
    {
        $builder = $this->db->table('materiales m')
            ->select('m.*, 
                    CONCAT(u1.nombre, " ", u1.apellido) as nombre_creador,
                    u1.correo as email_creador,
                    CONCAT(u2.nombre, " ", u2.apellido) as nombre_editor,
                    u2.correo as email_editor')
            ->join('usuarios u1', 'u1.id = m.usuarioCrea', 'left')
            ->join('usuarios u2', 'u2.id = m.usuarioEdita', 'left')
            ->where('m.id', $idMaterial);
        
        if ($idEmpresa) {
            $builder->where('m.id_empresa', $idEmpresa);
        }
        
        return $builder->get()->getRowArray();
    }

    /**
     * Buscar materiales por nombre
     */
    public function buscarPorNombre($nombre, $idEmpresa, $limite = 10)
    {
        return $this->select('id, codigo_consecutivo, nombre, unidad_medida, costo_unitario')
                   ->where('id_empresa', $idEmpresa)
                   ->like('nombre', $nombre)
                   ->orderBy('nombre', 'ASC')
                   ->limit($limite)
                   ->findAll();
    }

    /**
     * Obtener materiales para select/dropdown
     */
    public function getMaterialesParaSelect($idEmpresa)
    {
        return $this->select('id, codigo_consecutivo, nombre, unidad_medida, costo_unitario')
                   ->where('id_empresa', $idEmpresa)
                   ->orderBy('nombre', 'ASC')
                   ->findAll();
    }

    /**
     * Verificar si un código consecutivo ya existe
     */
    public function existeCodigoConsecutivo($codigo, $idExcluir = null)
    {
        $builder = $this->where('codigo_consecutivo', $codigo);
        
        if ($idExcluir) {
            $builder->where('id !=', $idExcluir);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Obtener estadísticas de materiales por empresa
     */
    public function getEstadisticasPorEmpresa($idEmpresa)
    {
        $result = $this->db->table('materiales')
            ->select('COUNT(*) as total_materiales,
                    AVG(costo_unitario) as costo_promedio,
                    MIN(costo_unitario) as costo_minimo,
                    MAX(costo_unitario) as costo_maximo,
                    COUNT(DISTINCT unidad_medida) as unidades_diferentes')
            ->where('id_empresa', $idEmpresa)
            ->get()
            ->getRowArray();
        
        return $result;
    }

    /**
     * Obtener materiales más costosos
     */
    public function getMaterialesMasCostosos($idEmpresa, $limite = 10)
    {
        return $this->select('codigo_consecutivo, nombre, unidad_medida, costo_unitario')
                   ->where('id_empresa', $idEmpresa)
                   ->orderBy('costo_unitario', 'DESC')
                   ->limit($limite)
                   ->findAll();
    }

    /**
     * Obtener unidades de medida únicas
     */
    public function getUnidadesMedida($idEmpresa = null)
    {
        $builder = $this->select('unidad_medida')
                       ->distinct()
                       ->orderBy('unidad_medida', 'ASC');
        
        if ($idEmpresa) {
            $builder->where('id_empresa', $idEmpresa);
        }
        
        $result = $builder->findAll();
        return array_column($result, 'unidad_medida');
    }

    /**
     * Generar código consecutivo para material
     * Formato: MAT-YYYY0000N (ej: MAT-202500001)
     */
    public function generarCodigoConsecutivo($idEmpresa)
    {
        $anioActual = date('Y');
        
        // Buscar el último número consecutivo para el año actual
        $ultimoCodigo = $this->select('codigo_consecutivo')
                           ->where('id_empresa', $idEmpresa)
                           ->where('codigo_consecutivo LIKE', "MAT-{$anioActual}%")
                           ->orderBy('codigo_consecutivo', 'DESC')
                           ->first();
        
        $siguienteNumero = 1;
        
        if ($ultimoCodigo) {
            // Extraer el número del último código (últimos 5 dígitos)
            $numeroActual = (int) substr($ultimoCodigo['codigo_consecutivo'], -5);
            $siguienteNumero = $numeroActual + 1;
        }
        
        // Generar código con formato MAT-YYYY00001
        return sprintf('MAT-%s%05d', $anioActual, $siguienteNumero);
    }

    /**
     * Actualizar material
     */
    public function actualizarMaterial($id, $data, $usuarioEdita)
    {
        $data['usuarioEdita'] = $usuarioEdita;
        return $this->update($id, $data);
    }

    /**
     * Eliminar material (verificar que no esté en uso)
     */
    public function eliminarMaterial($id)
    {
        // Verificar si el material está siendo usado en trabajos
        $enUso = $this->db->table('materiales_trabajo')
                         ->where('id_material', $id)
                         ->countAllResults();
        
        if ($enUso > 0) {
            return [
                'success' => false,
                'message' => 'No se puede eliminar el material porque está siendo utilizado en registros de trabajo'
            ];
        }
        
        $eliminado = $this->delete($id);
        
        return [
            'success' => $eliminado,
            'message' => $eliminado ? 'Material eliminado exitosamente' : 'Error al eliminar el material'
        ];
    }
}
