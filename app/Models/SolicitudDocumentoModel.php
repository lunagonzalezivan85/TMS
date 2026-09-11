<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudDocumentoModel extends Model
{
    protected $table = 'solicitudes_documentos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'id_solicitud',
        'id_usuario',
        'nombre_original',
        'nombre_archivo',
        'ruta',
        'tipo_archivo',
        'tamano',
        'descripcion',
        'estado'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_subida';
    protected $updatedField = 'fecha_actualizacion';
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Validaciones
    protected $validationRules = [
        'id_solicitud' => 'required|is_natural_no_zero',
        'id_usuario' => 'required|is_natural_no_zero',
        'nombre_original' => 'required|max_length[255]',
        'nombre_archivo' => 'required|max_length[255]',
        'ruta' => 'required|max_length[512]',
        'tipo_archivo' => 'required|max_length[100]',
        'tamano' => 'required|is_natural_no_zero',
        'estado' => 'required|in_list[ACTIVO,INACTIVO]'
    ];

    protected $validationMessages = [
        'id_solicitud' => [
            'required' => 'El ID de la solicitud es obligatorio',
            'is_natural_no_zero' => 'El ID de la solicitud debe ser un número válido'
        ],
        'nombre_original' => [
            'required' => 'El nombre original del archivo es obligatorio',
            'max_length' => 'El nombre del archivo no puede exceder los 255 caracteres'
        ],
        'tamano' => [
            'required' => 'El tamaño del archivo es obligatorio',
            'is_natural_no_zero' => 'El tamaño del archivo debe ser mayor a cero'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setUsuarioCrea'];
    protected $beforeUpdate = ['setUsuarioActualiza'];

    /**
     * Establece el usuario que crea el registro
     */
    protected function setUsuarioCrea(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['id_usuario'] = session()->get('user_id');
        }
        return $data;
    }

    /**
     * Establece el usuario que actualiza el registro
     */
    protected function setUsuarioActualiza(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['id_usuario'] = session()->get('user_id');
        }
        return $data;
    }

    /**
     * Obtiene los documentos de una solicitud
     * 
     * @param int $idSolicitud ID de la solicitud
     * @param string $estado Estado de los documentos a buscar (opcional)
     * @return array
     */
    public function getDocumentosPorSolicitud($idSolicitud, $estado = 'ACTIVO')
    {
        $builder = $this->where('id_solicitud', $idSolicitud);
        
        if ($estado) {
            $builder->where('estado', $estado);
        }
        
        return $builder->orderBy('fecha_subida', 'DESC')
                      ->findAll();
    }

    /**
     * Sube un documento para una solicitud
     * 
     * @param int $idSolicitud ID de la solicitud
     * @param array $archivo Datos del archivo a subir ($_FILES['nombre_campo'])
     * @param string $descripcion Descripción opcional del documento
     * @return array|false Datos del documento subido o false en caso de error
     */
    public function subirDocumento($idSolicitud, $archivo, $descripcion = null)
    {
        // Configuración de carga
        $config = [
            'upload_path'   => WRITEPATH . 'uploads/solicitudes',
            'allowed_types' => 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png|gif',
            'max_size'      => 10240, // 10MB
            'encrypt_name'  => true,
            'file_ext_tolower' => true
        ];

        // Crear directorio si no existe
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $upload = service('upload', $config);
        
        if (!$upload->do_upload('documento')) {
            return [
                'success' => false,
                'error' => $upload->display_errors('', '')
            ];
        }

        // Datos del archivo subido
        $uploadData = $upload->data();
        
        // Guardar en la base de datos
        $documento = [
            'id_solicitud'    => $idSolicitud,
            'id_usuario'      => session()->get('user_id'),
            'nombre_original' => $uploadData['client_name'],
            'nombre_archivo'  => $uploadData['file_name'],
            'ruta'            => 'uploads/solicitudes/' . $uploadData['file_name'],
            'tipo_archivo'    => $uploadData['file_type'],
            'tamano'          => $uploadData['file_size'],
            'descripcion'     => $descripcion,
            'estado'          => 'ACTIVO'
        ];

        if ($this->save($documento)) {
            return [
                'success' => true,
                'id' => $this->getInsertID(),
                'data' => $documento
            ];
        }

        // Si hay error al guardar, eliminar el archivo subido
        @unlink($uploadData['full_path']);
        
        return [
            'success' => false,
            'error' => 'Error al guardar el registro en la base de datos'
        ];
    }

    /**
     * Elimina un documento de forma lógica
     * 
     * @param int $idDocumento ID del documento a eliminar
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function eliminarDocumento($idDocumento)
    {
        $documento = $this->find($idDocumento);
        
        if (!$documento) {
            return [
                'success' => false,
                'error' => 'El documento no existe'
            ];
        }
        
        // Eliminación lógica
        if ($this->update($idDocumento, ['estado' => 'INACTIVO'])) {
            return [
                'success' => true,
                'mensaje' => 'Documento eliminado correctamente'
            ];
        }
        
        return [
            'success' => false,
            'error' => 'Error al intentar eliminar el documento'
        ];
    }
    
    /**
     * Elimina físicamente un documento y su archivo asociado
     * 
     * @param int $idDocumento ID del documento a eliminar
     * @return array Resultado de la operación
     */
    public function eliminarDocumentoFisico($idDocumento)
    {
        $documento = $this->find($idDocumento);
        
        if (!$documento) {
            return [
                'success' => false,
                'error' => 'El documento no existe'
            ];
        }
        
        // Ruta completa del archivo
        $rutaArchivo = WRITEPATH . $documento['ruta'];
        
        // Eliminar el archivo físico
        if (file_exists($rutaArchivo)) {
            @unlink($rutaArchivo);
        }
        
        // Eliminar el registro de la base de datos
        if ($this->delete($idDocumento)) {
            return [
                'success' => true,
                'mensaje' => 'Documento eliminado correctamente'
            ];
        }
        
        return [
            'success' => false,
            'error' => 'Error al intentar eliminar el registro de la base de datos'
        ];
    }
    
    /**
     * Obtiene la ruta física completa de un documento
     * 
     * @param int $idDocumento ID del documento
     * @return string|null Ruta completa del archivo o null si no existe
     */
    public function getRutaFisica($idDocumento)
    {
        $documento = $this->find($idDocumento);
        
        if (!$documento) {
            return null;
        }
        
        return WRITEPATH . $documento['ruta'];
    }
    
    /**
     * Verifica si un documento existe y está activo
     * 
     * @param int $idDocumento ID del documento
     * @return bool True si existe y está activo, false en caso contrario
     */
    public function existeDocumentoActivo($idDocumento)
    {
        return $this->where('id', $idDocumento)
                   ->where('estado', 'ACTIVO')
                   ->countAllResults() > 0;
    }
}
