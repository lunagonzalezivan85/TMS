<?php

namespace App\Models;

use App\Models\BaseModel;

class EmpresaModel extends BaseModel
{
    protected $table = 'empresas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'codigo',
        'nombre', 
        'ruc', // Cambiar de nit a ruc
        'direccion',
        'telefono',
        'correo',
        'codigo_inicial',
        'serie_documento',
        'estado',
        'usuarioCrea',
        'usuarioEdita',
        'fechaRegistro',
        'fechaUpdate'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fechaRegistro';
    protected $updatedField = 'fechaUpdate';

    // Validation
    protected $validationRules = [
        'nombre' => 'required|max_length[100]',
        'ruc' => 'required|max_length[20]|is_unique[empresas.ruc,id,{id}]',
        'estado' => 'in_list[ACTIVO,INACTIVO]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre de la empresa es obligatorio',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'ruc' => [
            'required' => 'El RUC es obligatorio',
            'max_length' => 'El RUC no puede exceder 20 caracteres',
            'is_unique' => 'Este RUC ya está registrado'
        ],
        'estado' => [
            'in_list' => 'El estado debe ser ACTIVO o INACTIVO'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = false;

    /**
     * Crear empresa durante el proceso de registro
     */
    public function crearEmpresaRegistro(array $data)
    {
        // Asegurar que los campos de auditoría estén presentes
        if (!isset($data['fechaRegistro'])) {
            $data['fechaRegistro'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['fechaUpdate'])) {
            $data['fechaUpdate'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['usuarioCrea'])) {
            $data['usuarioCrea'] = session()->get('user_id') ?? 1;
        }
        if (!isset($data['usuarioEdita'])) {
            $data['usuarioEdita'] = session()->get('user_id') ?? 1;
        }
        if (!isset($data['estado'])) {
            $data['estado'] = 'ACTIVO';
        }

        return $this->insert($data);
    }

    /**
     * Obtener empresas activas
     */
    public function getEmpresasActivas()
    {
        return $this->where('estado', 'ACTIVO')->findAll();
    }

    /**
     * Obtener empresa con estadísticas
     */
    public function getEmpresaConEstadisticas($id)
    {
        $empresa = $this->find($id);
        
        if (!$empresa) {
            return null;
        }

        // Obtener estadísticas
        $db = \Config\Database::connect();
        
        $empresa['total_usuarios'] = $db->table('usuarios')
            ->where('id_empresa', $id)
            ->countAllResults();
            
        $empresa['total_vehiculos'] = $db->table('vehiculos')
            ->where('id_empresa', $id)
            ->countAllResults();
            
        $empresa['total_solicitudes'] = $db->table('solicitudes')
            ->join('vehiculos', 'vehiculos.id = solicitudes.id_vehiculo')
            ->where('vehiculos.id_empresa', $id)
            ->countAllResults();

        return $empresa;
    }

    /**
     * Crear empresa con código automático
     */
    public function crearEmpresa($datos)
    {
        try {
            $this->db->transStart();

            // Generar código automático
            $codigo = $this->generarCodigoConsecutivo('empresas', 'EMP');
            $datos['codigo'] = $codigo;
            $datos['usuarioCrea'] = session()->get('user_id');

            $id = $this->insert($datos);

            if (!$id) {
                $this->db->transRollback();
                return ['error' => 'No se pudo crear la empresa'];
            }

            $this->db->transCommit();
            return ['success' => true, 'id' => $id];

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error creando empresa: ' . $e->getMessage());
            return ['error' => 'Error interno del servidor'];
        }
    }

    /**
     * Cambiar estado de empresa
     */
    public function cambiarEstado($id, $estado)
    {
        if (!in_array($estado, ['ACTIVO', 'INACTIVO'])) {
            return ['error' => 'Estado inválido'];
        }

        // Verificar si tiene usuarios activos antes de inactivar
        if ($estado === 'INACTIVO') {
            $usuariosActivos = $this->db->table('usuarios')
                ->where('id_empresa', $id)
                ->where('estado', 'ACTIVO')
                ->countAllResults();

            if ($usuariosActivos > 0) {
                return ['error' => 'No se puede inactivar una empresa con usuarios activos'];
            }
        }

        $datos = [
            'estado' => $estado,
            'usuarioEdita' => session()->get('user_id')
        ];

        if ($this->update($id, $datos)) {
            return ['success' => true];
        }

        return ['error' => 'No se pudo cambiar el estado'];
    }

    /**
     * Verificar si se puede eliminar la empresa
     */
    public function puedeEliminar($id)
    {
        // Verificar usuarios
        $usuarios = $this->db->table('usuarios')
            ->where('id_empresa', $id)
            ->countAllResults();

        if ($usuarios > 0) {
            return ['puede' => false, 'razon' => 'La empresa tiene usuarios asociados'];
        }

        // Verificar vehículos
        $vehiculos = $this->db->table('vehiculos')
            ->where('id_empresa', $id)
            ->countAllResults();

        if ($vehiculos > 0) {
            return ['puede' => false, 'razon' => 'La empresa tiene vehículos asociados'];
        }

        return ['puede' => true];
    }
}
