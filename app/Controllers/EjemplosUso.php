<?php

namespace App\Controllers;

use App\Models\BaseModel;
use App\Models\GenericModel;

class EjemplosUso extends BaseController
{
    /**
     * Ejemplos de uso del modelo genérico
     */
    public function ejemplos()
    {
        // ===================================
        // EJEMPLO 1: Trabajar con cualquier tabla
        // ===================================
        
        // Crear instancia para tabla 'empresas'
        $empresaModel = GenericModel::tabla('empresas');
        
        // Obtener todas las empresas
        $empresas = $empresaModel->findAll();
        
        // Insertar nueva empresa
        $nuevaEmpresa = $empresaModel->insert([
            'nombre' => 'Nueva Empresa',
            'ruc' => '20123456789',
            'direccion' => 'Dirección ejemplo',
            'codigo_inicial' => 'NE',
            'serie_documento' => '2024300'
        ]);

        // ===================================
        // EJEMPLO 2: Trabajar con tabla de vehículos
        // ===================================
        
        $vehiculoModel = GenericModel::tabla('vehiculos');
        
        // Insertar vehículo con código consecutivo automático
        $nuevoVehiculo = $vehiculoModel->insertarConCodigo([
            'id_empresa' => 1,
            'placa' => 'XYZ-999',
            'marca' => 'Toyota',
            'modelo' => 'Corolla',
            'anio' => 2023,
            'kilometraje' => 0
        ], 'vehiculos');

        // ===================================
        // EJEMPLO 3: Ejecutar procedimientos almacenados
        // ===================================
        
        $genericModel = new GenericModel();
        
        // SP que retorna datos (SELECT)
        $vehiculosConConductor = $genericModel->ejecutarSP(
            'sp_vehiculos_con_conductor', 
            [1], // parámetros: [empresaId]
            'select', // tipo de operación
            true // retornar datos
        );
        
        // SP de inserción
        $resultado = $genericModel->ejecutarSP(
            'sp_insertar_solicitud_mantenimiento',
            [1, 1, 1, 1, 'Cambio de aceite programado', '2024-01-15'], // parámetros
            'insert', // tipo de operación
            false // no retornar datos
        );
        
        // SP de actualización
        $actualizado = $genericModel->ejecutarSP(
            'sp_completar_mantenimiento',
            [1, 150.50, 'Mantenimiento completado exitosamente'], // parámetros
            'update', // tipo de operación
            false // no retornar datos
        );

        // ===================================
        // EJEMPLO 4: Búsquedas y filtros
        // ===================================
        
        // Búsqueda por texto en múltiples campos
        $resultadosBusqueda = $vehiculoModel->buscar(
            'Toyota', // texto a buscar
            ['marca', 'modelo', 'placa'], // campos donde buscar
            ['id_empresa' => 1] // filtros adicionales
        );
        
        // Obtener registros paginados
        $vehiculosPaginados = $vehiculoModel->getPaginado(
            1, // página
            10, // registros por página
            ['id_empresa' => 1, 'estado' => 'ACTIVO'], // filtros
            [ // joins
                [
                    'table' => 'conductores',
                    'condition' => 'conductores.id = vehiculos.id_conductor',
                    'type' => 'left'
                ]
            ]
        );

        // ===================================
        // EJEMPLO 5: Estadísticas
        // ===================================
        
        // Estadísticas por campo
        $estadisticasEstado = $vehiculoModel->getEstadisticas(
            'estado', // campo para agrupar
            ['id_empresa' => 1] // filtros
        );

        // ===================================
        // EJEMPLO 6: Transacciones
        // ===================================
        
        $solicitudModel = GenericModel::tabla('solicitudes');
        $materialModel = GenericModel::tabla('consumo_materiales');
        
        // Iniciar transacción
        $solicitudModel->iniciarTransaccion();
        
        try {
            // Insertar solicitud
            $solicitudId = $solicitudModel->insertarConCodigo([
                'id_empresa' => 1,
                'id_vehiculo' => 1,
                'id_solicitante' => 1,
                'id_tipo_problema' => 1,
                'descripcion' => 'Mantenimiento completo'
            ], 'solicitudes');
            
            // Insertar consumo de materiales
            $materialModel->insert([
                'id_empresa' => 1,
                'id_solicitud' => $solicitudId,
                'id_material' => 1,
                'cantidad' => 4.5
            ]);
            
            // Confirmar transacción
            $exito = $solicitudModel->confirmarTransaccion();
            
            if ($exito) {
                echo "Transacción completada exitosamente";
            } else {
                echo "Error en la transacción";
            }
            
        } catch (\Exception $e) {
            // Cancelar transacción en caso de error
            $solicitudModel->cancelarTransaccion();
            echo "Error: " . $e->getMessage();
        }

        // ===================================
        // EJEMPLO 7: Consultas SQL personalizadas
        // ===================================
        
        // Ejecutar SQL personalizado con parámetros
        $consultaPersonalizada = $genericModel->ejecutarSQL(
            "SELECT v.placa, v.marca, v.modelo, 
                    CONCAT(c.nombre, ' ', c.apellido) as conductor
             FROM vehiculos v 
             LEFT JOIN conductores c ON c.id = v.id_conductor 
             WHERE v.id_empresa = ? AND v.estado = ?",
            [1, 'ACTIVO'], // parámetros
            true // retornar datos
        );

        // ===================================
        // EJEMPLO 8: Validaciones y verificaciones
        // ===================================
        
        // Verificar si existe un registro
        $existeVehiculo = $vehiculoModel->existe([
            'placa' => 'ABC-123',
            'id_empresa' => 1
        ]);
        
        // Obtener siguiente consecutivo
        $siguienteConsecutivo = $genericModel->getSiguienteConsecutivo(1, 'vehiculos');
        
        // ===================================
        // RETORNAR RESULTADOS PARA VISTA
        // ===================================
        
        $data = [
            'empresas' => $empresas,
            'vehiculos_con_conductor' => $vehiculosConConductor,
            'busqueda_vehiculos' => $resultadosBusqueda,
            'vehiculos_paginados' => $vehiculosPaginados,
            'estadisticas' => $estadisticasEstado,
            'consulta_personalizada' => $consultaPersonalizada,
            'existe_vehiculo' => $existeVehiculo,
            'siguiente_consecutivo' => $siguienteConsecutivo
        ];
        
        return view('ejemplos/uso_modelos', $data);
    }

    /**
     * Ejemplo específico de uso con procedimientos almacenados
     */
    public function ejemplosProcedimientos()
    {
        $genericModel = new GenericModel();
        
        // ===================================
        // PROCEDIMIENTOS DE CONSULTA (SELECT)
        // ===================================
        
        // Obtener estadísticas del dashboard
        $estadisticas = $genericModel->getEstadisticasDashboard(1);
        
        // Obtener vehículos con conductor
        $vehiculos = $genericModel->getVehiculosConConductor(1);
        
        // Obtener solicitudes pendientes
        $solicitudesPendientes = $genericModel->getSolicitudesPendientes(1);
        
        // Obtener historial de mantenimientos
        $historial = $genericModel->getHistorialMantenimientos(1);
        
        // Obtener reporte de mantenimientos por período
        $reporte = $genericModel->getReporteMantenimientos(1, '2024-01-01', '2024-12-31');
        
        // ===================================
        // PROCEDIMIENTOS DE MODIFICACIÓN
        // ===================================
        
        // Completar mantenimiento
        $completado = $genericModel->completarMantenimiento(1, 250.75, 'Trabajo completado');
        
        // Cambiar estado de vehículo
        $estadoCambiado = $genericModel->cambiarEstadoVehiculo(1, 'EN REPARACION', 'Mantenimiento programado');
        
        // Asignar conductor a vehículo
        $conductorAsignado = $genericModel->asignarConductorVehiculo(1, 2);
        
        // Insertar nueva solicitud
        $nuevaSolicitud = $genericModel->insertarSolicitudMantenimiento([
            'id_empresa' => 1,
            'id_vehiculo' => 1,
            'id_solicitante' => 1,
            'id_tipo_problema' => 1,
            'descripcion' => 'Revisión general',
            'fecha_programada' => '2024-01-20'
        ]);
        
        return json_encode([
            'estadisticas' => $estadisticas,
            'vehiculos' => $vehiculos,
            'solicitudes_pendientes' => $solicitudesPendientes,
            'historial' => $historial,
            'reporte' => $reporte,
            'operaciones' => [
                'completado' => $completado,
                'estado_cambiado' => $estadoCambiado,
                'conductor_asignado' => $conductorAsignado,
                'nueva_solicitud' => $nuevaSolicitud
            ]
        ]);
    }
}
