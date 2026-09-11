<?php

namespace App\Controllers;

use App\Models\CatalogoModel;
use App\Services\CotizacionService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Cotizador extends SecureController
{
    private CotizacionService $service;
    private CatalogoModel $catalogoModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->service = new CotizacionService();
        $this->catalogoModel = new CatalogoModel();
    }

    /**
     * Vista principal del cotizador (formulario + historial).
     */
    public function index()
    {
        $data = [
            'title'       => 'Cotizador - Sistema GMV',
            'page_title'  => 'Cotizador de Flete',
            'productos'   => $this->service->getProductosCombustible(),
            'factores'    => $this->service->getFactoresFijos(),
            'tiposUnidad' => $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0001'),
            'resumen'     => $this->service->getResumen(),
        ];
        return view('cotizador/index', $data);
    }

    /**
     * Calcula la cotización en tiempo real (AJAX) sin guardar.
     */
    public function calcular()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $calc = $this->service->calcularCotizacion($input);

        // Si incluye datos de pricing de combustible, calcular también
        if (isset($input['precio_referencia'])) {
            $input['flete_por_galon'] = $calc['flete_por_galon'];
            $calc['pricing_combustible'] = $this->service->calcularPricingCombustible($input);
        }

        return $this->response->setJSON(['success' => true, 'calc' => $calc]);
    }

    /**
     * Guarda una nueva cotización.
     */
    public function store()
    {
        $input = $this->request->getPost();
        $input['usuario_crea'] = session()->get('usuario') ?? 'admin';

        $result = $this->service->crearCotizacion($input);

        if ($result['success']) {
            session()->setFlashdata('success', $result['message']);
            return redirect()->to('/cotizador/historial');
        }

        session()->setFlashdata('error', $result['message'] ?? 'Error al crear cotización');
        if (!empty($result['errors'])) {
            session()->setFlashdata('errors', $result['errors']);
        }
        return redirect()->back()->withInput();
    }

    /**
     * Muestra una cotización específica.
     */
    public function show($id)
    {
        $cot = $this->service->getCotizacion((int) $id);
        if (!$cot) {
            session()->setFlashdata('error', 'Cotización no encontrada');
            return redirect()->to('/cotizador/historial');
        }

        $data = [
            'title'      => 'Cotización ' . $cot['numero_cotizacion'] . ' - Sistema GMV',
            'page_title' => 'Detalle de Cotización',
            'cotizacion' => $cot,
            'desglose'   => $cot['desglose'] ?? [],
        ];
        return view('cotizador/show', $data);
    }

    /**
     * Muestra el formulario para editar una cotización.
     */
    public function edit($id)
    {
        $cot = $this->service->getCotizacion((int) $id);
        if (!$cot) {
            session()->setFlashdata('error', 'Cotización no encontrada');
            return redirect()->to('/cotizador/historial');
        }

        $data = [
            'title'       => 'Editar Cotización - Sistema GMV',
            'page_title'  => 'Editar Cotización ' . $cot['numero_cotizacion'],
            'cotizacion'  => $cot,
            'desglose'    => $cot['desglose'] ?? [],
            'productos'   => $this->service->getProductosCombustible(),
            'factores'    => $this->service->getFactoresFijos(),
            'tiposUnidad' => $this->catalogoModel->getOpcionesHijosActivosPorCodigo('CAT-0001'),
        ];
        return view('cotizador/edit', $data);
    }

    /**
     * Actualiza una cotización existente.
     */
    public function update($id)
    {
        $input = $this->request->getPost();
        $result = $this->service->actualizarCotizacion((int) $id, $input);

        if ($result['success']) {
            session()->setFlashdata('success', $result['message']);
            return redirect()->to('/cotizador/historial');
        }

        session()->setFlashdata('error', $result['message'] ?? 'Error al actualizar');
        if (!empty($result['errors'])) {
            session()->setFlashdata('errors', $result['errors']);
        }
        return redirect()->back()->withInput();
    }

    /**
     * Elimina una cotización (AJAX o POST).
     */
    public function delete($id)
    {
        $result = $this->service->eliminarCotizacion((int) $id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            session()->setFlashdata('success', $result['message']);
        } else {
            session()->setFlashdata('error', $result['message']);
        }
        return redirect()->to('/cotizador/historial');
    }

    /**
     * Vista de impresión de cotización (formato carta para cliente).
     */
    public function imprimir($id)
    {
        $cot = $this->service->getCotizacion((int) $id);
        if (!$cot) {
            session()->setFlashdata('error', 'Cotización no encontrada');
            return redirect()->to('/cotizador/historial');
        }

        $data = [
            'cotizacion' => $cot,
            'desglose'   => $cot['desglose'] ?? [],
        ];
        return view('cotizador/imprimir', $data);
    }

    /**
     * Vista de historial de cotizaciones.
     */
    public function historial()
    {
        $data = [
            'title'        => 'Historial de Cotizaciones - Sistema GMV',
            'page_title'   => 'Historial de Cotizaciones',
            'cotizaciones' => $this->service->listarCotizaciones(100),
            'resumen'      => $this->service->getResumen(),
        ];
        return view('cotizador/historial', $data);
    }

    /**
     * Lista cotizaciones (AJAX).
     */
    public function listar()
    {
        $limite = (int) ($this->request->getGet('limite') ?? 100);
        $cotizaciones = $this->service->listarCotizaciones($limite);
        return $this->response->setJSON(['success' => true, 'cotizaciones' => $cotizaciones]);
    }

    /**
     * Resumen para dashboard (AJAX).
     */
    public function resumen()
    {
        $resumen = $this->service->getResumen();
        return $this->response->setJSON(['success' => true, 'resumen' => $resumen]);
    }

    /**
     * Aprueba una cotización (cambia estado BORRADOR → APROBADA).
     */
    public function aprobar($id)
    {
        $result = $this->service->aprobarCotizacion((int) $id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            session()->setFlashdata('success', $result['message']);
        } else {
            session()->setFlashdata('error', $result['message']);
        }
        return redirect()->to('/cotizador/historial');
    }

    /**
     * Rechaza una cotización (cambia estado BORRADOR → RECHAZADA).
     */
    public function rechazar($id)
    {
        $result = $this->service->rechazarCotizacion((int) $id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            session()->setFlashdata('success', $result['message']);
        } else {
            session()->setFlashdata('error', $result['message']);
        }
        return redirect()->to('/cotizador/historial');
    }
}
