<?php

namespace App\Models;

use CodeIgniter\Model;

class SqlServerModel extends Model
{
    protected $sqlsrv;
    
    public function __construct()
    {
        $this->sqlsrv = \Config\Database::connect('sqlsrv');
    }
    
    /**
     * Search in reference table with pagination
     */
    public function searchReferenceTable($table, $search = '', $perPage = 10, $page = 1)
    {
        $offset = ($page - 1) * $perPage;
        
        $builder = $this->sqlsrv->table($table);
        
        if (!empty($search)) {
            // Add your search conditions here based on table structure
            $builder->like('descripcion', $search);
            // Add more fields to search as needed
        }
        
        $total = $builder->countAllResults(false);
        
        $builder->select('*')
                ->limit($perPage, $offset);
                
        $results = $builder->get()->getResultArray();
        
        return [
            'data' => $results,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Get reference data by ID
     */
    public function getReferenceData($table, $id)
    {
        return $this->sqlsrv->table($table)
                          ->where('id', $id)
                          ->get()
                          ->getRowArray();
    }
    
    /**
     * Get all tables from SQL Server
     */
    public function getTables()
    {
        return $this->sqlsrv->listTables();
    }
    
    /**
     * Get columns from a table
     */
    public function getTableColumns($table)
    {
        return $this->sqlsrv->getFieldNames($table);
    }
}
