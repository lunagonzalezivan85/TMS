<?php

namespace App\Models;

/**
 * Modelo genérico para trabajar con cualquier tabla
 * Extiende BaseModel para funcionalidades básicas
 */
class GenericModel extends BaseModel
{
    protected $protectFields = false;

    /**
     * Crear una instancia para trabajar con una tabla específica
     */
    public static function tabla(string $nombreTabla, string $primaryKey = 'id')
    {
        $instance = new self();
        $instance->setTabla($nombreTabla);
        $instance->setPrimaryKey($primaryKey);
        return $instance;
    }

    /**
     * Ejemplos de uso de procedimientos almacenados comunes
     */

    /**
     * SP para obtener vehículos con conductor
     */
    public function getVehiculosConConductor(int $empresaId)
    {
        return $this->ejecutarSP('sp_vehiculos_con_conductor', [$empresaId], 'select');
    }

    /**
     * SP para obtener solicitudes pendientes
     */
    public function getSolicitudesPendientes(int $empresaId)
    {
        return $this->ejecutarSP('sp_solicitudes_pendientes', [$empresaId], 'select');
    }

    /**
     * SP para completar mantenimiento
     */
    public function completarMantenimiento(int $solicitudId, float $costoTotal, string $observaciones)
    {
        return $this->ejecutarSP('sp_completar_mantenimiento', [
            $solicitudId, 
            $costoTotal, 
            $observaciones
        ], 'update', false);
    }

    /**
     * SP para cambiar estado de vehículo
     */
    public function cambiarEstadoVehiculo(int $vehiculoId, string $nuevoEstado, string $motivo)
    {
        return $this->ejecutarSP('sp_cambiar_estado_vehiculo', [
            $vehiculoId, 
            $nuevoEstado, 
            $motivo
        ], 'update', false);
    }

    /**
     * SP para obtener reporte de mantenimientos
     */
    public function getReporteMantenimientos(int $empresaId, string $fechaInicio, string $fechaFin)
    {
        return $this->ejecutarSP('sp_reporte_mantenimientos', [
            $empresaId, 
            $fechaInicio, 
            $fechaFin
        ], 'select');
    }

    /**
     * SP para obtener estadísticas del dashboard
     */
    public function getEstadisticasDashboard(int $empresaId)
    {
        return $this->ejecutarSP('sp_estadisticas_dashboard', [$empresaId], 'select');
    }

    /**
     * SP para insertar nueva solicitud de mantenimiento
     */
    public function insertarSolicitudMantenimiento(array $datos)
    {
        return $this->ejecutarSP('sp_insertar_solicitud_mantenimiento', [
            $datos['id_empresa'],
            $datos['id_vehiculo'],
            $datos['id_solicitante'],
            $datos['id_tipo_problema'],
            $datos['descripcion'],
            $datos['fecha_programada'] ?? null
        ], 'insert', false);
    }

    /**
     * SP para asignar conductor a vehículo
     */
    public function asignarConductorVehiculo(int $vehiculoId, int $conductorId)
    {
        return $this->ejecutarSP('sp_asignar_conductor_vehiculo', [
            $vehiculoId, 
            $conductorId
        ], 'update', false);
    }

    /**
     * SP para obtener historial de mantenimientos de un vehículo
     */
    public function getHistorialMantenimientos(int $vehiculoId)
    {
        return $this->ejecutarSP('sp_historial_mantenimientos', [$vehiculoId], 'select');
    }

    /**
     * SP para obtener consumo de materiales por período
     */
    public function getConsumoMateriales(int $empresaId, string $fechaInicio, string $fechaFin)
    {
        return $this->ejecutarSP('sp_consumo_materiales', [
            $empresaId, 
            $fechaInicio, 
            $fechaFin
        ], 'select');
    }
}
