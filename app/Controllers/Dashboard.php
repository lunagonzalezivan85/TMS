<?php

namespace App\Controllers;

use App\Models\DocumentacionConductorModel;
use App\Models\RegistroCombustibleModel;
use App\Models\VehiculoModel;
use App\Models\SolicitudModel;
use App\Models\ConductorModel;
use App\Config\Widget;

class Dashboard extends SecureController
{
    public function index(): \CodeIgniter\HTTP\RedirectResponse
    {
        $rol = strtolower((string) session('rol_name'));
        switch ($rol) {
            case 'administrador':
                return redirect()->to(base_url('dashboard/admin'));
            case 'supervisor':
                return redirect()->to(base_url('dashboard/supervisor'));
            case 'tecnico':
            case 'mecanico':
            case 'mecánico':
                return redirect()->to(base_url('dashboard/tecnico'));
            case 'conductor':
                return redirect()->to(base_url('dashboard/conductor'));
            default:
                return redirect()->to(base_url('dashboard/admin'));
        }
    }

    public function getStats()
    {
        $vehiculoModel = new VehiculoModel();
        $solicitudModel = new SolicitudModel();
        $combustibleModel = new RegistroCombustibleModel();
        $conductorModel = new ConductorModel();
        $docConductorModel = new DocumentacionConductorModel();
        
        $empresaId = session()->get('empresa_id');
        
        $statsVehiculos = $vehiculoModel->getEstadisticasVehiculos($empresaId);
        $statsSolicitudes = $solicitudModel->getEstadisticasSolicitudes($empresaId);
        $estadisticasCombustible = $combustibleModel->getEstadisticasConsumo();
        $totalConductores = $conductorModel->where('id_empresa', $empresaId)->countAllResults();
        $documentosPorVencer = $docConductorModel->getDocumentosPorVencer();
        
        $stats = [
            'vehicles' => [
                'total' => $statsVehiculos['total'] ?? 0,
                'por_estado' => $statsVehiculos['por_estado'] ?? []
            ],
            'maintenance' => [
                'total' => $statsSolicitudes['totales']['total'] ?? 0,
                'abiertas' => $statsSolicitudes['totales']['abiertas'] ?? 0,
                'cerradas' => $statsSolicitudes['totales']['cerradas'] ?? 0
            ],
            'fuel' => [
                'total_litros' => $estadisticasCombustible['total_litros'] ?? 0,
                'total_galones' => $estadisticasCombustible['total_galones'] ?? 0,
                'total_registros' => $estadisticasCombustible['total_registros'] ?? 0
            ],
            'conductores' => $totalConductores,
            'alerts' => count($documentosPorVencer)
        ];

        return $this->response->setJSON($stats);
    }

    /**
     * Dashboard para Administrador
     */
    public function admin(): string
    {
        $data = $this->buildDashboardData('Administrador');
        return view('dashboard/admin', $data);
    }

    /**
     * Dashboard para Supervisor
     */
    public function supervisor(): string
    {
        $data = $this->buildDashboardData('Supervisor');
        return view('dashboard/supervisor', $data);
    }

    /**
     * Dashboard para Técnico / Mecánico
     */
    public function tecnico(): string
    {
        $data = $this->buildDashboardData('Tecnico');
        return view('dashboard/tecnico', $data);
    }

    /**
     * Dashboard para Conductor
     */
    public function conductor(): string
    {
        $data = $this->buildDashboardData('Conductor');
        return view('dashboard/conductor', $data);
    }

    /**
     * Recopila datos comunes para todos los dashboards
     */
    private function buildDashboardData(string $rol): array
    {
        $docConductorModel = new DocumentacionConductorModel();
        $combustibleModel = new RegistroCombustibleModel();
        $vehiculoModel = new VehiculoModel();
        $solicitudModel = new SolicitudModel();
        $conductorModel = new ConductorModel();

        $empresaId = session()->get('empresa_id');
        $usuarioId = session()->get('user_id');

        $documentosConductorPorVencer = $docConductorModel->getDocumentosPorVencer();
        $estadisticasCombustible = $combustibleModel->getEstadisticasConsumo();
        $statsVehiculos = $vehiculoModel->getEstadisticasVehiculos($empresaId);
        $statsSolicitudes = $solicitudModel->getEstadisticasSolicitudes($empresaId);
        $totalConductores = $conductorModel->where('id_empresa', $empresaId)->countAllResults();

        $solicitudesPendientes = $solicitudModel
            ->where('id_empresa', $empresaId)
            ->whereIn('estado', ['PENDIENTE', 'APROBADA'])
            ->countAllResults();

        $solicitudesAsignadas = $solicitudModel
            ->where('id_empresa', $empresaId)
            ->where('id_asignado', $usuarioId)
            ->whereNotIn('estado', ['COMPLETADA', 'CANCELADA', 'RECHAZADA', 'FINALIZADA'])
            ->countAllResults();

        return [
            'title' => "Dashboard $rol - Sistema GMV",
            'page_title' => "Dashboard $rol",
            'rol' => strtolower($rol),
            'stats' => [
                'total_vehicles' => $statsVehiculos['total'] ?? 0,
                'vehicles_active' => $statsVehiculos['por_estado']['ACTIVO'] ?? 0,
                'vehicles_maintenance' => $statsVehiculos['por_estado']['EN MANTENIMIENTO'] ?? 0,
                'total_maintenance' => $statsSolicitudes['totales']['total'] ?? 0,
                'pending_maintenance' => $solicitudesPendientes,
                'assigned_to_me' => $solicitudesAsignadas,
                'total_conductores' => $totalConductores,
                'alerts' => count($documentosConductorPorVencer),
                'total_litros' => $estadisticasCombustible['total_litros'] ?? 0,
                'total_galones' => ($estadisticasCombustible['total_litros'] ?? 0) / 3.78541,
            ],
            'comandos' => $this->getComandosRol(strtolower($rol))
        ];
    }

    /**
     * Opciones rápidas del command palette según rol
     */
    private function getComandosRol(string $rol): array
    {
        $comandos = [
            ['label' => 'Ir al listado de solicitudes', 'url' => base_url('solicitudes'), 'icon' => 'fa-clipboard-list', 'roles' => ['administrador', 'supervisor', 'tecnico', 'mecanico']],
            ['label' => 'Nueva solicitud de mantenimiento', 'url' => base_url('solicitudes/create'), 'icon' => 'fa-plus-circle', 'roles' => ['administrador', 'supervisor', 'conductor']],
            ['label' => 'Asignar técnicos', 'url' => base_url('solicitudes'), 'icon' => 'fa-user-cog', 'roles' => ['administrador', 'supervisor']],
            ['label' => 'Mis trabajos asignados', 'url' => base_url('solicitudes'), 'icon' => 'fa-wrench', 'roles' => ['tecnico', 'mecanico']],
            ['label' => 'Vehículos', 'url' => base_url('vehiculos'), 'icon' => 'fa-car', 'roles' => ['administrador', 'supervisor']],
            ['label' => 'Conductores', 'url' => base_url('conductores'), 'icon' => 'fa-users', 'roles' => ['administrador', 'supervisor']],
            ['label' => 'Combustible', 'url' => base_url('combustible'), 'icon' => 'fa-gas-pump', 'roles' => ['administrador', 'supervisor']],
            ['label' => 'Mi vehículo asignado', 'url' => base_url('vehiculos'), 'icon' => 'fa-car-side', 'roles' => ['conductor']],
            ['label' => 'Configuración', 'url' => base_url('configuracion'), 'icon' => 'fa-cog', 'roles' => ['administrador']],
            ['label' => 'Cerrar sesión', 'url' => base_url('logout'), 'icon' => 'fa-sign-out-alt', 'roles' => ['administrador', 'supervisor', 'tecnico', 'mecanico', 'conductor']],
        ];

        return array_values(array_filter($comandos, fn($c) => in_array($rol, $c['roles'])));
    }
}
