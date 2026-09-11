<?php

namespace App\Models;

use CodeIgniter\Model;

class ConductorErpModel extends Model
{
    protected $table = 'VW_CONDUCTORES';
    protected $primaryKey = 'CODIGO';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [];

    /**
     * Obtiene la lista de conductores disponibles en el ERP.
     *
     * @param string|null $search Filtro de búsqueda opcional.
     * @param int $limit  Límite de registros a retornar.
     * @param int $offset Offset para la consulta.
     *
     * @return array
     */
    public function obtenerConductores(?string $search = null, int $limit = 100, int $offset = 0): array
    {
        try {
            $builder = $this->db->table($this->table)->select('*');

            if (!empty($search)) {
                $builder->groupStart()
                    ->like('CODIGO', $search)
                    ->orLike('NOMBRE', $search)
                    ->orLike('APELLIDO', $search)
                    ->orLike('IDENTIFICACION', $search)
                    ->groupEnd();
            }

            $builder->limit($limit, $offset);

            return $builder->get()->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', '[ConductorErpModel] Error al obtener conductores: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un conductor del ERP por su código.
     *
     * @param string $codigo
     *
     * @return array|null
     */
    public function obtenerConductorPorCodigo(string $codigo): ?array
    {
        try {
            return $this->db->table($this->table)
                ->select('*')
                ->where('CODIGO', $codigo)
                ->get()
                ->getRowArray();
        } catch (\Throwable $e) {
            log_message('error', '[ConductorErpModel] Error al obtener conductor por código: ' . $e->getMessage());
            return null;
        }
    }
}
