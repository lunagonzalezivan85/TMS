<?php

namespace App\Controllers;

use App\Models\DireccionModel;

class Direccion extends SecureController
{
    protected $direccionModel;
    
    public function __construct()
    {
        helper('access');
        $this->direccionModel = new DireccionModel();
    }

    /**
     * Mostrar lista de direcciones
     */
    public function index()
    {
        requireAccess('direcciones');
        
        $filtros = [
            'buscar' => $this->request->getGet('buscar'),
            'ciudad' => $this->request->getGet('ciudad')
        ];

        if (!empty($filtros['buscar'])) {
            $direcciones = $this->direccionModel->buscarDirecciones($filtros['buscar']);
        } else {
            $direcciones = $this->direccionModel->getDireccionesCompletas();
        }
        
        $estadisticas = $this->direccionModel->getEstadisticas();

        $data = [
            'title' => 'Direcciones - Sistema GMV',
            'page_title' => 'Gestión de Direcciones',
            'direcciones' => $direcciones,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros
        ];

        return view('direcciones/index', $data);
    }

    /**
     * Mostrar formulario para crear nueva dirección
     */
    public function create()
    {
        requireAccess('direcciones/create');

        $data = [
            'title' => 'Nueva Dirección - Sistema GMV',
            'page_title' => 'Nueva Dirección',
            'direccion' => []
        ];

        return view('direcciones/form', $data);
    }

    /**
     * Procesar creación de nueva dirección
     */
    public function store()
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'direccion' => $this->request->getPost('direccion'),
            'codigo_integracion' => $this->request->getPost('codigo_integracion'),
            'longitud' => $this->request->getPost('longitud'),
            'latitud' => $this->request->getPost('latitud'),
            'ciudad' => $this->request->getPost('ciudad')
        ];

        // Validar si la dirección ya existe
        if ($this->direccionModel->existeDireccion($data['direccion'])) {
            session()->setFlashdata('error', 'Ya existe una dirección con ese texto');
            return redirect()->back()->withInput();
        }

        if ($this->direccionModel->save($data)) {
            session()->setFlashdata('success', 'Dirección creada exitosamente');
            return redirect()->to(base_url('direcciones'));
        } else {
            $errors = $this->direccionModel->errors();
            session()->setFlashdata('error', 'Error al crear la dirección: ' . implode(', ', $errors));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Mostrar formulario para editar dirección
     */
    public function edit($id)
    {
        requireAccess('direcciones/edit');
        
        $direccion = $this->direccionModel->find($id);

        if (!$direccion) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Dirección no encontrada');
        }

        $data = [
            'title' => 'Editar Dirección - Sistema GMV',
            'page_title' => 'Editar Dirección',
            'direccion' => $direccion
        ];

        return view('direcciones/form', $data);
    }

    /**
     * Procesar actualización de dirección
     */
    public function update($id)
    {
        $direccion = $this->direccionModel->find($id);

        if (!$direccion) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Dirección no encontrada');
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'direccion' => $this->request->getPost('direccion'),
            'codigo_integracion' => $this->request->getPost('codigo_integracion'),
            'longitud' => $this->request->getPost('longitud'),
            'latitud' => $this->request->getPost('latitud'),
            'ciudad' => $this->request->getPost('ciudad')
        ];

        // Validar si la dirección ya existe (excluyendo la actual)
        if ($this->direccionModel->existeDireccion($data['direccion'], $id)) {
            session()->setFlashdata('error', 'Ya existe una dirección con ese texto');
            return redirect()->back()->withInput();
        }

        if ($this->direccionModel->update($id, $data)) {
            session()->setFlashdata('success', 'Dirección actualizada exitosamente');
            return redirect()->to(base_url('direcciones'));
        } else {
            $errors = $this->direccionModel->errors();
            session()->setFlashdata('error', 'Error al actualizar la dirección: ' . implode(', ', $errors));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Eliminar dirección
     */
    public function delete($id)
    {
        requireAccess('direcciones/delete');
        
        $direccion = $this->direccionModel->find($id);

        if (!$direccion) {
            session()->setFlashdata('error', 'Dirección no encontrada');
            return redirect()->to(base_url('direcciones'));
        }

        if ($this->direccionModel->delete($id)) {
            session()->setFlashdata('success', 'Dirección eliminada exitosamente');
        } else {
            session()->setFlashdata('error', 'Error al eliminar la dirección');
        }

        return redirect()->to(base_url('direcciones'));
    }

    /**
     * Ver detalles de una dirección
     */
    public function show($id)
    {
        requireAccess('direcciones');
        
        $direccion = $this->direccionModel->getDireccionCompleta($id);

        if (!$direccion) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Dirección no encontrada');
        }

        $data = [
            'title' => 'Detalle Dirección - Sistema GMV',
            'page_title' => 'Detalle de Dirección',
            'direccion' => $direccion
        ];

        return view('direcciones/show', $data);
    }

    /**
     * API para geocodificación
     */
    public function geocode()
    {
        $direccion = $this->request->getPost('direccion');
        
        if (empty($direccion)) {
            return $this->response->setJSON(['error' => 'Dirección requerida']);
        }

        // Usar servicio de geocodificación (Nominatim de OpenStreetMap)
        $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($direccion);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'GMV Sistema');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            
            if (!empty($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'results' => array_slice($data, 0, 5) // Limitar a 5 resultados
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se encontraron resultados para la dirección'
        ]);
    }

    /**
     * API para geocodificación inversa
     */
    public function reverseGeocode()
    {
        $lat = $this->request->getPost('lat');
        $lng = $this->request->getPost('lng');
        
        if (empty($lat) || empty($lng)) {
            return $this->response->setJSON(['error' => 'Coordenadas requeridas']);
        }

        // Usar servicio de geocodificación inversa
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'GMV Sistema');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            
            if (!empty($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'address' => $data['display_name'] ?? '',
                    'city' => $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? ''
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se pudo obtener la dirección para estas coordenadas'
        ]);
    }

    /**
     * Buscar direcciones cercanas
     */
    public function cercanas()
    {
        $lat = $this->request->getGet('lat');
        $lng = $this->request->getGet('lng');
        $radio = $this->request->getGet('radio') ?? 5;

        if (empty($lat) || empty($lng)) {
            return $this->response->setJSON(['error' => 'Coordenadas requeridas']);
        }

        $direcciones = $this->direccionModel->getDireccionesCercanas($lat, $lng, $radio);

        return $this->response->setJSON([
            'success' => true,
            'direcciones' => $direcciones
        ]);
    }

    /**
     * Obtener direcciones cercanas a una dirección específica
     */
    public function getNearby($id)
    {
        try {
            $direccion = $this->direccionModel->find($id);
            
            if (!$direccion) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Dirección no encontrada'
                ]);
            }

            if (empty($direccion['latitud']) || empty($direccion['longitud'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La dirección no tiene coordenadas válidas'
                ]);
            }

            $cercanas = $this->direccionModel->getNearbyAddresses(
                $direccion['latitud'], 
                $direccion['longitud'], 
                1000, // Radio de 1km
                $id   // Excluir la dirección actual
            );

            // Formatear distancias para mostrar en metros
            foreach ($cercanas as &$dir) {
                $dir['distancia'] = round($dir['distancia'] * 1000); // Convertir km a metros
            }

            return $this->response->setJSON([
                'success' => true,
                'direcciones' => $cercanas,
                'total' => count($cercanas)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en getNearby: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Vista de mapa con todas las direcciones
     */
    public function mapa()
    {
        $data = [
            'page_title' => 'Mapa de Direcciones',
            'direcciones' => $this->direccionModel->getDireccionesConCoordenadas(),
            'estadisticas' => $this->direccionModel->getEstadisticas()
        ];

        return view('direcciones/mapa', $data);
    }

    /**
     * API para obtener todas las direcciones con coordenadas
     */
    public function getMapData()
    {
        try {
            $direcciones = $this->direccionModel->getDireccionesConCoordenadas();
            
            return $this->response->setJSON([
                'success' => true,
                'direcciones' => $direcciones,
                'total' => count($direcciones)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en getMapData: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al cargar datos del mapa'
            ]);
        }
    }

    /**
     * Vista de sincronización con SQL Server
     */
    public function sincronizar()
    {
        $data = [
            'page_title' => 'Sincronizar Direcciones',
            'direcciones_pendientes' => []
        ];

        return view('direcciones/sincronizar', $data);
    }

    /**
     * Obtener direcciones de SQL Server para sincronizar
     */
    public function getDireccionesSqlServer()
    {
        try {
            // Debug: Log de inicio
            log_message('debug', 'Direccion::obtenerDireccionesPendientes - Iniciando');
            
            // Usar conexión SQLSRV nativa (como en el test que funciona)
            $connectionInfo = [
                "Database" => "GCM_PRUEBAS",
                "UID" => "sag",
                "PWD" => "sag"
            ];
            
            $conn = sqlsrv_connect("15.235.109.18,1433", $connectionInfo);
            
            if (!$conn) {
                $errors = sqlsrv_errors();
                $errorMsg = 'Error de conexión SQLSRV: ';
                foreach ($errors as $error) {
                    $errorMsg .= $error['message'] . ' ';
                }
                    log_message('error', $errorMsg);
                throw new \Exception($errorMsg);
            }
            
            log_message('debug', 'Direccion::obtenerDireccionesPendientes - Conexión SQLSRV exitosa');

            // Obtener direcciones de SQL Server usando SQLSRV nativo
            $query = "SELECT 
                        ID_ADDRESS,
                        CODIGO_DE_CLIENTE,
                        DIRECCION,
                        LATITUD,
                        LONGITUD,
                        KILOMETROS,
                        ESTADO,
                        USUARIO_INGRESO,
                        FECHA_INGRESO
                      FROM CXC_CLIENTES_DIRECCION 
                      WHERE ESTADO = 1";

            log_message('debug', 'Direccion::obtenerDireccionesPendientes - Ejecutando query: ' . $query);
            
            $stmt = sqlsrv_query($conn, $query);
            
            if (!$stmt) {
                $errors = sqlsrv_errors();
                $errorMsg = 'Error en consulta: ';
                foreach ($errors as $error) {
                    $errorMsg .= $error['message'] . ' ';
                }
                log_message('error', $errorMsg);
                sqlsrv_close($conn);
                throw new \Exception($errorMsg);
            }
            
            // Convertir resultados a array con limpieza UTF-8
            $direccionesSqlServer = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                // Limpiar caracteres UTF-8 malformados
                $cleanRow = [];
                foreach ($row as $key => $value) {
                    if (is_string($value)) {
                        // Convertir a UTF-8 válido
                        $cleanRow[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    } else {
                        $cleanRow[$key] = $value;
                    }
                }
                $direccionesSqlServer[] = $cleanRow;
            }
            
            log_message('debug', 'Direccion::obtenerDireccionesPendientes - Registros obtenidos: ' . count($direccionesSqlServer));
            
            // Cerrar conexión SQLSRV
            sqlsrv_close($conn);

            // Verificar si hay datos
            if (empty($direccionesSqlServer)) {
                log_message('info', 'No se encontraron direcciones activas en SQL Server');
            }

            // Obtener códigos de integración existentes en MySQL
            $codigosExistentes = $this->direccionModel
                ->select('codigo_integracion')
                ->where('codigo_integracion IS NOT NULL')
                ->where('codigo_integracion !=', '')
                ->findAll();

            $codigosArray = array_column($codigosExistentes, 'codigo_integracion');

            // Filtrar direcciones que no existen en MySQL
            $direccionesPendientes = array_values(array_filter($direccionesSqlServer, function($direccion) use ($codigosArray) {
                return !in_array($direccion['ID_ADDRESS'], $codigosArray);
            }));

            // Preparar respuesta con limpieza adicional
            $response = [
                'success' => true,
                'direcciones' => $direccionesPendientes,
                'total' => count($direccionesPendientes),
                'existentes' => count($codigosArray),
                'total_sqlserver' => count($direccionesSqlServer),
                'debug_info' => [
                    'connection_status' => 'OK',
                    'query_executed' => true,
                    'records_found' => count($direccionesSqlServer)
                ]
            ];
            
            // Asegurar que la respuesta sea JSON válido
            return $this->response->setJSON($response, false);

        } catch (\Exception $e) {
            log_message('error', 'Error en getDireccionesSqlServer: ' . $e->getMessage());
            
            // Diagnóstico detallado del error
            $errorDetails = $this->diagnosticarConexionSqlServer($e);
            
            // Limpiar mensaje de error para evitar problemas UTF-8
            $errorMessage = mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8');
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al conectar con SQL Server',
                'error_details' => $errorDetails,
                'debug_info' => [
                    'error_message' => $errorMessage,
                    'error_code' => $e->getCode(),
                    'file' => basename($e->getFile()),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    /**
     * Diagnosticar problemas de conexión SQL Server
     */
    private function diagnosticarConexionSqlServer(\Exception $e)
    {
        $diagnostico = [];
        
        // 1. Verificar extensiones PHP
        $diagnostico['extensiones'] = [
            'sqlsrv' => extension_loaded('sqlsrv'),
            'pdo_sqlsrv' => extension_loaded('pdo_sqlsrv')
        ];
        
        // 2. Verificar configuración
        $config = config('Database');
        $diagnostico['configuracion'] = [
            'existe_config_sqlserver' => isset($config->sqlserver),
            'hostname' => $config->sqlserver['hostname'] ?? 'No definido',
            'database' => $config->sqlserver['database'] ?? 'No definido',
            'port' => $config->sqlserver['port'] ?? 'No definido'
        ];
        
        // 3. Verificar conectividad de red
        $host = $config->sqlserver['hostname'] ?? 'localhost';
        $port = $config->sqlserver['port'] ?? 1433;
        $timeout = 5;
        
        $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if ($socket) {
            $diagnostico['conectividad'] = [
                'accesible' => true,
                'mensaje' => "Puerto $port accesible en $host"
            ];
            fclose($socket);
        } else {
            $diagnostico['conectividad'] = [
                'accesible' => false,
                'mensaje' => "Puerto $port NO accesible en $host",
                'error_numero' => $errno,
                'error_descripcion' => $errstr,
                'sugerencias' => [
                    'Verificar firewall',
                    'Verificar que SQL Server esté ejecutándose',
                    'Verificar configuración de red de SQL Server',
                    'Verificar que TCP/IP esté habilitado en SQL Server'
                ]
            ];
        }
        
        // 4. Análisis del mensaje de error
        $errorMsg = $e->getMessage();
        $diagnostico['analisis_error'] = [];
        
        if (strpos($errorMsg, 'could not find driver') !== false) {
            $diagnostico['analisis_error'][] = 'Driver SQLSRV no encontrado - Instalar extensión PHP sqlsrv';
        }
        
        if (strpos($errorMsg, 'Login failed') !== false) {
            $diagnostico['analisis_error'][] = 'Credenciales incorrectas - Verificar usuario y contraseña';
        }
        
        if (strpos($errorMsg, 'server was not found') !== false) {
            $diagnostico['analisis_error'][] = 'Servidor no encontrado - Verificar hostname y conectividad';
        }
        
        if (strpos($errorMsg, 'Connection refused') !== false) {
            $diagnostico['analisis_error'][] = 'Conexión rechazada - Verificar puerto y firewall';
        }
        
        if (strpos($errorMsg, 'SSL') !== false || strpos($errorMsg, 'certificate') !== false) {
            $diagnostico['analisis_error'][] = 'Problema SSL - Configurar TrustServerCertificate o encrypt';
        }
        
        return $diagnostico;
    }

    /**
     * Importar dirección desde SQL Server
     */
    public function importarDireccion()
    {
        try {
            $direccionData = $this->request->getJSON(true);
            
            if (!$direccionData) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos de dirección requeridos'
                ]);
            }

            // Validar datos requeridos
            if (empty($direccionData['ID_ADDRESS']) || empty($direccionData['DIRECCION'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID_ADDRESS y DIRECCION son requeridos'
                ]);
            }

            // Verificar que no exista ya
            $existe = $this->direccionModel
                ->where('codigo_integracion', $direccionData['ID_ADDRESS'])
                ->first();

            if ($existe) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Esta dirección ya existe en el sistema'
                ]);
            }

            // Preparar datos para insertar
            $nuevaDireccion = [
                'direccion' => $direccionData['DIRECCION'],
                'ciudad' => $direccionData['CIUDAD'] ?? null,
                'codigo_integracion' => $direccionData['ID_ADDRESS'],
                'latitud' => $direccionData['latitud'] ?? null, // Coordenadas capturadas del modal
                'longitud' => $direccionData['longitud'] ?? null,
                'estado' => 1,
                'id_empresa' => session('empresa_id'),
                'usuario_crea' => session('usuario'),
                'fecha_registro' => date('Y-m-d H:i:s')
            ];

            // Insertar en MySQL
            $id = $this->direccionModel->insert($nuevaDireccion);

            if ($id) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Dirección importada correctamente',
                    'id' => $id
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al insertar la dirección'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error en importarDireccion: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }
}
