<?php

namespace App\Controllers;

use App\Services\LecturaBombaService;
use App\Models\CatalogoModel;

class LecturaBomba extends SecureController
{
    protected LecturaBombaService $service;

    public function __construct()
    {
        log_message('info', 'LecturaBomba::__construct - Constructor ejecutado');
        helper('access');
        $this->service = new LecturaBombaService();
    }

    /**
     * Muestra el formulario de apertura de bomba
     */
    public function apertura()
    {
        requireAccess('lectura-bomba/apertura');

        $usuarioActual = session()->get('usuario');

        // Obtener opciones del catálogo CAT-0015
        $catalogoModel = new CatalogoModel();
        $catalogoOpciones = $catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0015');

        $data = [
            'title' => 'Apertura de Bomba - Sistema GMV',
            'page_title' => 'Apertura de Bomba',
            'catalogo_opciones' => $catalogoOpciones
        ];

        return view('lectura_bomba/apertura', $data);
    }

    /**
     * Procesa la apertura de bomba
     */
    public function guardarApertura()
    {
        requireAccess('lectura-bomba/apertura');

        log_message('info', 'LecturaBomba::guardarApertura - POST data: ' . json_encode($this->request->getPost()));

        // Procesar la foto
        $foto = $this->request->getFile('foto');
        $fotoNombre = '';
        
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $fotoNombre = $foto->getRandomName();
            $foto->move('uploads/lectura_bomba', $fotoNombre);
        }

        $data = [
            'id_centro_costo' => $this->request->getPost('id_centro_costo'),
            'lectura_inicial_litros' => $this->request->getPost('lectura_inicial_litros'),
            'lectura_inicial_galones' => $this->request->getPost('lectura_inicial_galones'),
            'litraje_inicial_ltr' => $this->request->getPost('litraje_inicial_ltr'),
            'litraje_inicial_gal' => $this->request->getPost('litraje_inicial_gal'),
            'foto' => $fotoNombre,
            'estado' => $this->request->getPost('estado'),
            'observaciones' => $this->request->getPost('observaciones'),
            'referencia_1' => $this->request->getPost('referencia_1'),
            'referencia_2' => $this->request->getPost('referencia_2'),
            'usuario_apertura' => session()->get('usuario') ?? 'admin'
        ];

        $resultado = $this->service->crearApertura($data);

        if ($resultado['success']) {
            session()->setFlashdata('success', 'Bomba abierta exitosamente');
            return redirect()->to('/lectura-bomba');
        }

        session()->setFlashdata('error', $resultado['message'] ?? 'Error al abrir la bomba');
        if (isset($resultado['errors'])) {
            session()->setFlashdata('errors', $resultado['errors']);
        }
        if (isset($resultado['apertura_pendiente'])) {
            session()->setFlashdata('apertura_pendiente', $resultado['apertura_pendiente']);
        }
        return redirect()->back()->withInput();
    }

    /**
     * Muestra el formulario de cierre de bomba
     */
    public function cierre($id = null)
    {
        log_message('error', 'LecturaBomba::cierre - INICIO - ID: ' . $id);
        
        try {
            requireAccess('lectura-bomba/cierre');
            log_message('error', 'LecturaBomba::cierre - Después de requireAccess - ID: ' . $id);
        } catch (\Exception $e) {
            log_message('error', 'LecturaBomba::cierre - Error en requireAccess: ' . $e->getMessage());
            throw $e;
        }

        // Si se proporciona ID, mostrar esa apertura específica
        if ($id) {
            $apertura = $this->service->getAperturaById($id);
            log_message('error', 'LecturaBomba::cierre - Apertura: ' . json_encode($apertura));
            
            if (!$apertura) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Apertura no encontrada');
            }
            if (!empty($apertura['fecha_cierre'])) {
                session()->setFlashdata('error', 'Esta apertura ya está cerrada');
                return redirect()->to('/lectura-bomba');
            }
        } else {
            // Si no se proporciona ID, mostrar aperturas pendientes
            $aperturasPendientes = $this->service->getAperturasPendientes();
            if (empty($aperturasPendientes)) {
                session()->setFlashdata('info', 'No hay aperturas pendientes de cierre');
                return redirect()->to('/lectura-bomba');
            }
            $apertura = $aperturasPendientes[0];
        }

        log_message('error', 'LecturaBomba::cierre - Antes de calcular salida despachada');

        // Calcular salida despachada del día según usuario y fecha de apertura
        $salidaDespachada = $this->calcularSalidaDespachada($apertura);

        log_message('error', 'LecturaBomba::cierre - Salida despachada calculada final: ' . $salidaDespachada);

        $data = [
            'title' => 'Cierre de Bomba - Sistema GMV',
            'page_title' => 'Cierre de Bomba',
            'apertura' => $apertura,
            'salida_despachada' => $salidaDespachada
        ];

        log_message('error', 'LecturaBomba::cierre - Antes de renderizar vista');

        return view('lectura_bomba/cierre', $data);
    }

    /**
     * Depura y calcula la salida despachada basada en registros de combustible de hoy
     */
    private function calcularSalidaDespachada($apertura)
    {
        $registroCombustibleModel = new \App\Models\RegistroCombustibleModel();
        
        $usuario = $apertura['usuario_apertura'];
        $fechaHoy = date('Y-m-d'); // Fecha actual en el servidor PHP
        
        log_message('error', 'Calculando salida despachada para Hoy - Fecha Hoy: ' . $fechaHoy . ', Usuario: ' . $usuario);
        
        // Query compatible con la consulta de MySQL del usuario (sin filtrar por tipo)
        $sql = "SELECT SUM(cantidad_litros) as total 
                FROM registro_combustible 
                WHERE usuario_crea = ? 
                AND DATE(fecha_registro) = ?";
        
        // Consulta de depuración para ver los registros que se están sumando
        $depuracion = $registroCombustibleModel->db->query("
            SELECT id, usuario_crea, fecha_registro, cantidad_litros, tipo 
            FROM registro_combustible 
            WHERE usuario_crea = ? 
            AND DATE(fecha_registro) = ?
        ", [$usuario, $fechaHoy])->getResultArray();
        
        log_message('error', 'Registros individuales sumados: ' . json_encode($depuracion));
        
        log_message('error', 'SQL: ' . $sql);
        log_message('error', 'Parámetros: [' . $usuario . ', ' . $fechaHoy . ']');
        
        $result = $registroCombustibleModel->db->query($sql, [$usuario, $fechaHoy])->getRow();
        
        log_message('error', 'Resultado SQL: ' . json_encode($result));
        
        $totalLitros = $result && $result->total ? floatval($result->total) : 0;
        
        log_message('error', 'Salida despachada calculada: ' . $totalLitros . ' L para usuario ' . $usuario . ' en fecha ' . $fechaHoy);
        
        return $totalLitros;
    }

    /**
     * Procesa el cierre de bomba
     */
    public function guardarCierre()
    {
        requireAccess('lectura-bomba/cierre');

        $id = $this->request->getPost('apertura_id');
        log_message('error', 'guardarCierre - apertura_id: ' . $id);

        $data = [
            'lectura_final_litros' => $this->request->getPost('lectura_final_litros'),
            'lectura_final_galones' => $this->request->getPost('lectura_final_galones'),
            'litraje_final_ltr' => $this->request->getPost('litraje_final_ltr'),
            'litraje_final_gal' => $this->request->getPost('litraje_final_gal'),
            'ingreso_tanque' => $this->request->getPost('ingreso_tanque') ?? 0,
            'salida_despachada' => $this->request->getPost('salida_despachada') ?? 0,
            'observaciones_cierre' => $this->request->getPost('observaciones_cierre'),
            'usuario_cierre' => session()->get('usuario') ?? 'admin'
        ];
        log_message('error', 'guardarCierre - POST data: ' . json_encode($data));

        $resultado = $this->service->cerrarApertura($id, $data);
        log_message('error', 'guardarCierre - resultado: ' . json_encode($resultado));

        if ($resultado['success']) {
            $consumoMsg = sprintf(
                'Bomba cerrada exitosamente. Consumo del turno: %.2f L (%.2f gal)',
                $resultado['consumo_litros'],
                $resultado['consumo_galones']
            );
            session()->setFlashdata('success', $consumoMsg);
            return redirect()->to('/lectura-bomba');
        }

        session()->setFlashdata('error', $resultado['message'] ?? 'Error al cerrar la bomba');
        if (isset($resultado['errors'])) {
            session()->setFlashdata('errors', $resultado['errors']);
        }
        return redirect()->back()->withInput();
    }

    /**
     * Muestra el monitor de bombas en tiempo real
     */
    public function monitor()
    {
        requireAccess('lectura-bomba');

        // Verificar si se solicita una apertura específica
        $aperturaId = $this->request->getGet('apertura_id');

        if ($aperturaId) {
            // Obtener la apertura específica desde el servicio
            $aperturas = $this->service->getAperturasPendientes();
            $aperturaActiva = null;

            foreach ($aperturas as $apertura) {
                if ($apertura['id'] == $aperturaId) {
                    $aperturaActiva = $apertura;
                    break;
                }
            }
        } else {
            // Obtener la apertura activa más reciente
            $aperturaActiva = $this->service->getAperturaActiva();
        }

        // Obtener consumo real de registros de combustible
        $consumoReal = 0;
        if ($aperturaActiva) {
            $consumoReal = $this->service->getConsumoReal($aperturaActiva['id']);
        }

        $data = [
            'title' => 'Monitor de Bombas - Sistema GMV',
            'page_title' => 'Monitor de Bombas',
            'apertura_activa' => $aperturaActiva,
            'consumo_real' => $consumoReal
        ];

        return view('lectura_bomba/monitor', $data);
    }

    /**
     * Endpoint AJAX para obtener consumo real en tiempo real
     */
    public function obtenerConsumo($id)
    {
        requireAccess('lectura-bomba');

        $consumo = $this->service->getConsumoReal($id);

        return $this->response->setJSON([
            'success' => true,
            'consumo' => $consumo
        ]);
    }

    /**
     * Endpoint AJAX para obtener aperturas pendientes
     */
    public function aperturasPendientes()
    {
        requireAccess('lectura-bomba');

        $aperturas = $this->service->getAperturasPendientes();
        $usuarioActual = session()->get('usuario');
        $rolName = strtolower(session()->get('rol_name') ?? '');
        $esAdmin = in_array($rolName, ['admin', 'administrador', 'administrator']);

        // Filtrar aperturas pendientes por usuario si no es admin
        if (!$esAdmin) {
            $aperturas = array_filter($aperturas, function($item) use ($usuarioActual) {
                return $item['usuario_apertura'] === $usuarioActual;
            });
        }

        return $this->response->setJSON([
            'success' => true,
            'aperturas' => array_values($aperturas)
        ]);
    }

    /**
     * Endpoint AJAX para obtener registros de combustible de una apertura
     */
    public function registrosCombustible($aperturaId)
    {
        requireAccess('lectura-bomba');

        $registroModel = new \App\Models\RegistroCombustibleModel();
        $result = $registroModel->getRegistrosPorLectura($aperturaId);

        return $this->response->setJSON([
            'success' => true,
            'registros' => $result
        ]);
    }

    /**
     * Muestra el dashboard de estadísticas
     */
    public function dashboard()
    {
        requireAccess('lectura-bomba');

        $estadisticas = $this->service->getEstadisticas();
        
        // Obtener nombres de las bombas desde el catálogo
        $catalogoModel = new CatalogoModel();
        $catalogoOpciones = $catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0015');
        
        // Mapear IDs a nombres
        $lecturasPorBombaConNombres = [];
        foreach ($estadisticas['lecturas_por_bomba'] as $bomba) {
            $nombre = $catalogoOpciones[$bomba['id']] ?? 'Desconocido';
            $lecturasPorBombaConNombres[] = array_merge($bomba, ['nombre' => $nombre]);
        }

        $data = [
            'title' => 'Dashboard de Bomba - Sistema GMV',
            'page_title' => 'Dashboard de Bomba',
            'estadisticas' => $estadisticas,
            'lecturas_por_bomba' => $lecturasPorBombaConNombres
        ];

        return view('lectura_bomba/dashboard', $data);
    }

    /**
     * Muestra el listado de aperturas/cierres
     */
    public function index()
    {
        requireAccess('lectura-bomba');

        $idCentroCosto = $this->request->getGet('centro_costo');
        $usuarioActual = session()->get('usuario');
        $rolName = strtolower(session()->get('rol_name') ?? '');
        $esAdmin = in_array($rolName, ['admin', 'administrador', 'administrator']);
        
        $historial = [];
        $aperturasPendientes = $this->service->getAperturasPendientes();
        
        // Filtrar aperturas pendientes por usuario si no es admin
        if (!$esAdmin) {
            $aperturasPendientes = array_filter($aperturasPendientes, function($item) use ($usuarioActual) {
                return $item['usuario_apertura'] === $usuarioActual;
            });
        }

        if ($idCentroCosto) {
            $historial = $this->service->getHistorialCentroCosto($idCentroCosto);
            // Filtrar por usuario si no es admin
            if (!$esAdmin) {
                $historial = array_filter($historial, function($item) use ($usuarioActual) {
                    return $item['usuario_apertura'] === $usuarioActual;
                });
            }
        } else {
            if ($esAdmin) {
                // Admin ve todas las lecturas (abiertas y cerradas)
                $historial = $this->service->getTodasLasLecturas();
            } else {
                // Usuario normal ve solo sus aperturas pendientes
                $historial = $aperturasPendientes;
            }
        }

        // Obtener opciones del catálogo CAT-0015 para el filtro
        $catalogoModel = new CatalogoModel();
        $catalogoOpciones = $catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0015');

        $data = [
            'title' => 'Lecturas de Bomba - Sistema GMV',
            'page_title' => 'Lecturas de Bomba',
            'catalogo_opciones' => $catalogoOpciones,
            'historial' => $historial,
            'aperturas_pendientes' => $aperturasPendientes,
            'filtro_centro' => $idCentroCosto,
            'es_admin' => $esAdmin,
            'usuario_actual' => $usuarioActual
        ];

        return view('lectura_bomba/index', $data);
    }

    /**
     * Verifica si hay apertura pendiente para un centro de costo (AJAX)
     */
    public function verificarApertura()
    {
        $idCentroCosto = $this->request->getPost('id_centro_costo');

        if (!$idCentroCosto) {
            return $this->response->setJSON(['success' => false, 'message' => 'Centro de costo no proporcionado']);
        }

        $aperturaPendiente = $this->service->verificarAperturaPendiente($idCentroCosto);

        if ($aperturaPendiente) {
            return $this->response->setJSON([
                'success' => true,
                'has_pendiente' => true,
                'apertura' => $aperturaPendiente
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'has_pendiente' => false
        ]);
    }

    /**
     * Muestra el detalle de una lectura específica con conciliación completa
     */
    public function detalle($id)
    {
        requireAccess('lectura-bomba');

        $reporte = $this->service->getReporteConciliacion((int)$id);
        
        if (!$reporte) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Lectura no encontrada');
        }

        // Obtener registros de combustible detallados asociados a esta lectura
        $registroModel = new \App\Models\RegistroCombustibleModel();
        $despachos = $registroModel->getRegistrosPorLectura($id);

        $data = [
            'title'      => 'Detalle de Lectura - Sistema GMV',
            'page_title' => 'Detalle de Lectura',
            'reporte'    => $reporte,
            'despachos'  => $despachos,
        ];

        return view('lectura_bomba/detalle', $data);
    }

    /**
     * Reporte de conciliación por rango de fechas (sin ID específico)
     */
    public function reporte()
    {
        requireAccess('lectura-bomba');

        $fechaIni  = $this->request->getGet('fecha_ini');
        $fechaFin  = $this->request->getGet('fecha_fin');
        $idCentro  = $this->request->getGet('centro_costo');

        $reportes = $this->service->getReporteConciliacion(null, $fechaIni, $fechaFin, $idCentro ? (int)$idCentro : null);

        // Obtener centros de costo para el filtro
        $catalogoModel = new CatalogoModel();
        $catalogoOpciones = $catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0015');

        $data = [
            'title'            => 'Reporte de Conciliación - Sistema GMV',
            'page_title'       => 'Reporte de Conciliación',
            'reportes'         => $reportes,
            'catalogo_opciones'=> $catalogoOpciones,
            'fecha_ini'        => $fechaIni,
            'fecha_fin'        => $fechaFin,
            'filtro_centro'    => $idCentro,
        ];

        return view('lectura_bomba/reporte', $data);
    }

    /**
     * Verifica si el usuario actual tiene una apertura pendiente (para FAB)
     */
    public function verificarAperturaUsuario()
    {
        $usuarioActual = session()->get('usuario');
        log_message('info', 'verificarAperturaUsuario - Usuario: ' . $usuarioActual);
        
        $aperturasPendientes = $this->service->getAperturasPendientes();
        log_message('info', 'verificarAperturaUsuario - Aperturas pendientes: ' . count($aperturasPendientes));
        
        $aperturaUsuario = null;
        foreach ($aperturasPendientes as $apertura) {
            log_message('info', 'verificarAperturaUsuario - Apertura usuario: ' . $apertura['usuario_apertura']);
            if ($apertura['usuario_apertura'] === $usuarioActual) {
                $aperturaUsuario = $apertura;
                break;
            }
        }

        $response = [
            'has_pendiente' => !empty($aperturaUsuario),
            'apertura_id' => $aperturaUsuario ? $aperturaUsuario['id'] : null
        ];
        
        log_message('info', 'verificarAperturaUsuario - Response: ' . json_encode($response));
        return $this->response->setJSON($response);
    }
}
