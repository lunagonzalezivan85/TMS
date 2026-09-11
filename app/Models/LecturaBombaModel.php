<?php

namespace App\Models;

use CodeIgniter\Model;

class LecturaBombaModel extends Model
{
    protected $table = 'lectura_bomba';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_centro_costo',
        'lectura_inicial_litros',
        'lectura_inicial_galones',
        'litraje_inicial_ltr',
        'litraje_inicial_gal',
        'foto',
        'estado',
        'observaciones',
        'referencia_1',
        'referencia_2',
        'referencia_3',
        'fecha_apertura',
        'usuario_apertura',
        'fecha_cierre',
        'lectura_final_litros',
        'lectura_final_galones',
        'litraje_final_ltr',
        'litraje_final_gal',
        'observaciones_cierre',
        'usuario_cierre',
        'consumo_turno_litros',
        'consumo_turno_galones',
        'ingreso_tanque',
        'salida_despachada'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'id_centro_costo' => 'required|numeric',
        'lectura_inicial_litros' => 'required|decimal[10,4]|greater_than[0]',
        'lectura_inicial_galones' => 'required|decimal[10,4]|greater_than[0]',
        'litraje_inicial_ltr' => 'permit_empty|decimal[10,4]',
        'litraje_inicial_gal' => 'permit_empty|decimal[10,4]',
        'estado' => 'required|in_list[normal,anomalia]',
        'observaciones' => 'permit_empty|string|max_length[500]',
        'usuario_apertura' => 'required|max_length[50]',
        'lectura_final_litros' => 'permit_empty|decimal[10,4]',
        'lectura_final_galones' => 'permit_empty|decimal[10,4]',
        'litraje_final_ltr' => 'permit_empty|decimal[10,4]',
        'litraje_final_gal' => 'permit_empty|decimal[10,4]',
        'observaciones_cierre' => 'permit_empty|string|max_length[500]',
        'usuario_cierre' => 'permit_empty|max_length[50]',
        'ingreso_tanque' => 'permit_empty|decimal[10,4]',
        'salida_despachada' => 'permit_empty|decimal[10,4]'
    ];

    protected $validationMessages = [
        'id_centro_costo' => [
            'required' => 'El centro de costo es obligatorio',
            'numeric' => 'El centro de costo debe ser un número válido'
        ],
        'lectura_inicial_litros' => [
            'required' => 'La lectura inicial en litros es obligatoria',
            'decimal' => 'La lectura inicial debe ser un número válido',
            'greater_than' => 'La lectura inicial debe ser mayor a 0'
        ],
        'lectura_inicial_galones' => [
            'required' => 'La lectura inicial en galones es obligatoria',
            'decimal' => 'La lectura inicial debe ser un número válido',
            'greater_than' => 'La lectura inicial debe ser mayor a 0'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio',
            'in_list' => 'El estado debe ser normal o anomalía'
        ],
        'observaciones' => [
            'max_length' => 'Las observaciones no pueden exceder 500 caracteres'
        ],
        'usuario_apertura' => [
            'required' => 'El usuario de apertura es obligatorio',
            'max_length' => 'El usuario no puede exceder 50 caracteres'
        ]
    ];

    /**
     * Obtiene la apertura pendiente de cierre para un centro de costo
     */
    public function getAperturaPendiente($idCentroCosto)
    {
        $builder = $this->db->table($this->table);
        $builder->where('id_centro_costo', $idCentroCosto);
        $builder->where('fecha_cierre', null);
        $builder->orderBy('fecha_apertura', 'DESC');
        $builder->limit(1);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Obtiene todas las aperturas pendientes de cierre
     */
    public function getAperturasPendientes($usuario = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('lectura_bomba.*, catalogo.nombre as nombre_centro_costo, catalogo.referencia as codigo_tanque, catalogo.descripcion as tanque');
        $builder->join('catalogo', 'catalogo.id = lectura_bomba.id_centro_costo', 'left');
        $builder->where('fecha_cierre', null);
        if ($usuario) {
            $builder->where('usuario_apertura', $usuario);
        }
        $builder->orderBy('fecha_apertura', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene todas las lecturas (abiertas y cerradas)
     */
    public function getTodasLasLecturas($limit = 100)
    {
        $builder = $this->db->table($this->table);
        $builder->select('lectura_bomba.*, catalogo.nombre as nombre_centro_costo');
        $builder->join('catalogo', 'catalogo.id = lectura_bomba.id_centro_costo', 'left');
        $builder->orderBy('fecha_apertura', 'DESC');
        $builder->limit($limit);

        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene el historial de aperturas/cierres de un centro de costo
     */
    public function getHistorialCentroCosto($idCentroCosto, $limit = 50)
    {
        $builder = $this->db->table($this->table);
        $builder->select('lectura_bomba.*, catalogo.nombre as nombre_centro_costo');
        $builder->join('catalogo', 'catalogo.id = lectura_bomba.id_centro_costo', 'left');
        $builder->where('id_centro_costo', $idCentroCosto);
        $builder->orderBy('fecha_apertura', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Valida que la lectura final sea mayor a la inicial
     */
    public function validarLecturaFinal($lecturaFinal, $lecturaInicial)
    {
        return $lecturaFinal > $lecturaInicial;
    }

    /**
     * Crea una nueva apertura de bomba
     */
    public function crearApertura($data)
    {
        return $this->insert($data);
    }

    /**
     * Cierra una apertura existente
     */
    public function cerrarApertura($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Obtiene una apertura por ID
     */
    public function getAperturaById($id)
    {
        return $this->find($id);
    }

    /**
     * Reporte de conciliación de combustible.
     * Reutilizable: funciona con ID específico o con rango de fechas.
     *
     * @param int|null    $id        ID de lectura_bomba (opcional)
     * @param string|null $fechaIni  Fecha inicial YYYY-MM-DD (opcional)
     * @param string|null $fechaFin  Fecha final YYYY-MM-DD (opcional)
     * @param int|null    $idCentro  Centro de costo / bomba (opcional)
     * @return array
     */
    public function getReporteConciliacion(?int $id = null, ?string $fechaIni = null, ?string $fechaFin = null, ?int $idCentro = null): array
    {
        $db = \Config\Database::connect();

        $sql = "
            SELECT
                lb.id AS id_medicion,
                lb.id_centro_costo,
                cat.nombre AS nombre_centro_costo,
                cat.referencia AS codigo_tanque,
                lb.fecha_apertura,
                lb.fecha_cierre,
                lb.usuario_apertura,
                lb.usuario_cierre,
                lb.estado,

                -- 1. Lectura / Estado Inicial
                lb.lectura_inicial_litros,
                lb.lectura_inicial_galones,
                lb.litraje_inicial_ltr AS varillado_inicial_tanque_ltr,
                lb.litraje_inicial_gal AS varillado_inicial_tanque_gal,

                -- 2. Ingresos Realizados (Entradas de combustible al tanque)
                COALESCE(lb.ingreso_tanque, 0) AS ingresos_tanque_ltr,

                -- 3. Despacho Realizado (Total de salidas de la bomba)
                COALESCE(SUM(rc.cantidad_litros), 0) AS total_despachado_ltr,
                COUNT(rc.id) AS cantidad_despachos,

                -- 4. Lectura Final Registrada en Bomba
                lb.lectura_final_litros,
                lb.lectura_final_galones,
                lb.litraje_final_ltr AS varillado_final_medido_ltr,
                lb.litraje_final_gal AS varillado_final_medido_gal,

                -- 5. Consumo físico del turno (diferencia de lecturas de contador)
                CASE
                    WHEN lb.lectura_final_litros IS NOT NULL THEN
                        lb.lectura_final_litros - lb.lectura_inicial_litros
                    ELSE NULL
                END AS consumo_fisico_turno_ltr,

                -- 6. Valor Real/Teórico (Cómo DEBERÍA quedar el tanque)
                (lb.litraje_inicial_ltr
                    + COALESCE(lb.ingreso_tanque, 0)
                    - COALESCE(SUM(rc.cantidad_litros), 0)
                ) AS stock_teorico_tanque_ltr,

                -- 7. Diferencia o Mermas (Físico Real vs Teórico)
                (COALESCE(lb.litraje_final_ltr, 0)
                    - (lb.litraje_inicial_ltr
                        + COALESCE(lb.ingreso_tanque, 0)
                        - COALESCE(SUM(rc.cantidad_litros), 0)
                    )
                ) AS diferencia_mermas_ltr,

                -- 8. Diferencia entre consumo del contador y despachado en vales
                CASE
                    WHEN lb.lectura_final_litros IS NOT NULL THEN
                        (lb.lectura_final_litros - lb.lectura_inicial_litros)
                        - COALESCE(SUM(rc.cantidad_litros), 0)
                    ELSE NULL
                END AS diferencia_contador_vs_vales_ltr,

                lb.observaciones,
                lb.observaciones_cierre

            FROM lectura_bomba lb
            LEFT JOIN registro_combustible rc ON lb.id = rc.id_lectura
            LEFT JOIN catalogo cat ON cat.id = lb.id_centro_costo
        ";

        $where  = [];
        $params = [];

        if ($id !== null) {
            $where[]  = 'lb.id = ?';
            $params[] = $id;
        }
        if ($fechaIni !== null) {
            $where[]  = 'DATE(lb.fecha_apertura) >= ?';
            $params[] = $fechaIni;
        }
        if ($fechaFin !== null) {
            $where[]  = 'DATE(lb.fecha_apertura) <= ?';
            $params[] = $fechaFin;
        }
        if ($idCentro !== null) {
            $where[]  = 'lb.id_centro_costo = ?';
            $params[] = $idCentro;
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= "
            GROUP BY
                lb.id, lb.id_centro_costo, cat.nombre, cat.referencia,
                lb.fecha_apertura, lb.fecha_cierre, lb.usuario_apertura, lb.usuario_cierre,
                lb.estado, lb.lectura_inicial_litros, lb.lectura_inicial_galones,
                lb.litraje_inicial_ltr, lb.litraje_inicial_gal,
                lb.ingreso_tanque, lb.lectura_final_litros, lb.lectura_final_galones,
                lb.litraje_final_ltr, lb.litraje_final_gal,
                lb.observaciones, lb.observaciones_cierre
            ORDER BY lb.fecha_apertura DESC
        ";

        if ($id !== null) {
            return $db->query($sql, $params)->getRowArray();
        }

        return $db->query($sql, $params)->getResultArray();
    }
}
