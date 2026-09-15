<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

// Rutas públicas de autenticación
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->get('register', 'Auth::registerWizard');
$routes->get('register/step1', 'Auth::registerStep1');
$routes->post('register/step1', 'Auth::processStep1');
$routes->get('register/step2', 'Auth::registerStep2');
$routes->post('register/step2', 'Auth::processStep2');
$routes->get('logout', 'Auth::logout');

// Dashboard: solo requiere estar logueado
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Rutas protegidas con autenticación y control de acceso
$routes->group('', ['filter' => 'access'], function($routes) {
    
    // Sistema de perfiles
    $routes->get('profile', 'Profile::index');                    // Mi perfil
    $routes->get('profile/edit', 'Profile::edit');                // Editar mi perfil
    $routes->post('profile/update', 'Profile::update');           // Actualizar mi perfil
    $routes->get('profile/(:num)', 'Profile::index/$1');          // Ver perfil de otro usuario
    $routes->get('profile/(:num)/edit', 'Profile::edit/$1');      // Editar perfil de otro usuario
    $routes->post('profile/(:num)/update', 'Profile::update/$1'); // Actualizar perfil de otro usuario
});

// Rutas de administración (solo administradores)
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    // Gestión de roles
    $routes->get('roles', 'Roles::index');
    $routes->get('roles/create', 'Roles::create');
    $routes->post('roles/store', 'Roles::store');
    $routes->get('roles/(:num)', 'Roles::show/$1');
    $routes->get('roles/(:num)/edit', 'Roles::edit/$1');
    $routes->post('roles/(:num)/update', 'Roles::update/$1');
    $routes->delete('roles/(:num)', 'Roles::delete/$1');
    $routes->get('roles/ajax', 'Roles::getRolesAjax');
    $routes->get('roles/permisos/(:segment)', 'Roles::getPermisos/$1');
});

// Rutas de vehículos (requieren autenticación y control de acceso)
$routes->group('vehiculos', ['filter' => 'access'], function($routes) {
    $routes->get('/', 'Vehiculos::index');
    $routes->post('getData', 'Vehiculos::getData');
    $routes->get('create', 'Vehiculos::create');
    $routes->post('store', 'Vehiculos::store');
    $routes->get('show/(:num)', 'Vehiculos::show/$1');
    $routes->get('edit/(:num)', 'Vehiculos::edit/$1');
    $routes->post('update/(:num)', 'Vehiculos::update/$1');
    $routes->post('delete/(:num)', 'Vehiculos::delete/$1');
    $routes->post('cambiarEstado', 'Vehiculos::cambiarEstado');
    // Importación / Exportación masiva
    $routes->get('exportar', 'Vehiculos::exportar');
    $routes->get('plantillaImportacion', 'Vehiculos::plantillaImportacion');
    $routes->post('importar', 'Vehiculos::importar');
    $routes->get('getEstadisticas', 'Vehiculos::getEstadisticas');
    $routes->post('verificarPlaca', 'Vehiculos::verificarPlaca');
    $routes->get('documentos/(:num)', 'Vehiculos::documentos/$1');
    $routes->post('subirDocumento', 'Vehiculos::subirDocumento');
    $routes->post('eliminarDocumento', 'Vehiculos::eliminarDocumento');
    $routes->get('verDocumento/(:num)', 'Vehiculos::verDocumento/$1');
    $routes->get('descargarDocumento/(:num)', 'Vehiculos::descargarDocumento/$1');
    // Rutas para solicitudes de mantenimiento
    $routes->post('crearSolicitudMantenimiento', 'Vehiculos::crearSolicitudMantenimiento');
    $routes->get('solicitud-mantenimiento/(:num)', 'Vehiculos::verSolicitudMantenimiento/$1');
    $routes->get('getEstadisticasVehiculo/(:num)', 'Vehiculos::getEstadisticasVehiculo/$1');
    $routes->post('filtrarCombustible/(:num)', 'Vehiculos::filtrarCombustible/$1');
    // Rutas para reportes de vehículos
    $routes->get('reportes', 'Vehiculos::reportes');
    $routes->get('test-reporte', 'Vehiculos::testReporte');
    $routes->get('reporteVehiculosPorEstado', 'Vehiculos::reporteVehiculosPorEstado');
    $routes->get('reporteMantenimientoVencido', 'Vehiculos::reporteMantenimientoVencido');
    $routes->get('reporteKilometraje', 'Vehiculos::reporteKilometraje');
    $routes->get('reporteDocumentosPorVencer', 'Vehiculos::reporteDocumentosPorVencer');
    $routes->get('reporteAsignaciones', 'Vehiculos::reporteAsignaciones');
    $routes->get('reporteVehiculosSinDocumentacion', 'Vehiculos::reporteVehiculosSinDocumentacion');
    $routes->post('exportarReporte', 'Vehiculos::exportarReporte');
    $routes->get('getAlertasDocumentos', 'Vehiculos::getAlertasDocumentos');
});

// Rutas de mantenimiento (requieren autenticación)
$routes->group('maintenance', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Maintenance::index');
    $routes->get('preventive', 'Maintenance::preventive');
    $routes->get('corrective', 'Maintenance::corrective');
    $routes->get('create', 'Maintenance::create');
    $routes->post('store', 'Maintenance::store');
    $routes->get('(:num)', 'Maintenance::show/$1');
    $routes->get('(:num)/edit', 'Maintenance::edit/$1');
    $routes->post('(:num)/update', 'Maintenance::update/$1');
});

// Rutas de combustible (requieren autenticación)
$routes->group('fuel', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Fuel::index');
    $routes->get('create', 'Fuel::create');
    $routes->post('store', 'Fuel::store');
    $routes->get('reports', 'Fuel::reports');
});

// Rutas para tipos de motivo de combustible
$routes->group('tipo-motivo-combustible', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'TipoMotivoCombustible::index');
    $routes->get('create', 'TipoMotivoCombustible::create');
    $routes->post('store', 'TipoMotivoCombustible::store');
    $routes->get('show/(:num)', 'TipoMotivoCombustible::show/$1');
    $routes->get('edit/(:num)', 'TipoMotivoCombustible::edit/$1');
    $routes->post('update/(:num)', 'TipoMotivoCombustible::update/$1');
    $routes->delete('delete/(:num)', 'TipoMotivoCombustible::delete/$1');
    $routes->post('cambiarEstado', 'TipoMotivoCombustible::cambiarEstado');
    $routes->post('verificarDescripcion', 'TipoMotivoCombustible::verificarDescripcion');
});

// Rutas para el módulo de registro de combustible
$routes->group('registro-combustible', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'RegistroCombustible::index');
    $routes->get('create', 'RegistroCombustible::create');
    $routes->get('create-venta', 'RegistroCombustible::createVenta');
    $routes->get('ventas', 'RegistroCombustible::ventas');
    $routes->get('ventas/(:num)/montos', 'RegistroCombustible::editMontosVenta/$1');
    $routes->post('ventas/(:num)/montos', 'RegistroCombustible::updateMontosVenta/$1');
    $routes->get('voucher/consumo/(:num)', 'RegistroCombustible::voucherConsumo/$1');
    $routes->get('voucher/venta/(:num)', 'RegistroCombustible::voucherVenta/$1');
    $routes->get('show/(:num)', 'RegistroCombustible::show/$1');
    $routes->get('edit/(:num)', 'RegistroCombustible::edit/$1');
    $routes->get('estadisticas', 'RegistroCombustible::estadisticas');
    
    // Acciones CRUD
    $routes->post('store', 'RegistroCombustible::store');
    $routes->post('update/(:num)', 'RegistroCombustible::update/$1');
    $routes->delete('delete/(:num)', 'RegistroCombustible::delete/$1');
    
    // AJAX
    $routes->get('getKilometrajeVehiculo/(:num)', 'RegistroCombustible::getKilometrajeVehiculo/$1');
    $routes->get('siguiente-numero-recibo', 'RegistroCombustible::siguienteNumeroRecibo');
    $routes->get('getEstadisticas', 'RegistroCombustible::getEstadisticas');
    $routes->post('getData', 'RegistroCombustible::getData');

    // SAG
    $routes->post('reenviar-sag/(:num)', 'RegistroCombustible::reenviarSAG/$1');

    // Supervisor
    $routes->get('supervisor', 'RegistroCombustible::supervisor');
    $routes->get('getBloqueadosCount', 'RegistroCombustible::getBloqueadosCount');
    $routes->post('aprobar/(:num)', 'RegistroCombustible::aprobar/$1');
    $routes->post('rechazar/(:num)', 'RegistroCombustible::rechazar/$1');
});

// Rutas para el módulo de cotizador
$routes->group('cotizador', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Cotizador::historial');
    $routes->get('nueva', 'Cotizador::index');
    $routes->get('historial', 'Cotizador::historial');
    $routes->get('show/(:num)', 'Cotizador::show/$1');
    $routes->get('imprimir/(:num)', 'Cotizador::imprimir/$1');
    $routes->get('edit/(:num)', 'Cotizador::edit/$1');
    $routes->post('store', 'Cotizador::store');
    $routes->post('update/(:num)', 'Cotizador::update/$1');
    $routes->delete('delete/(:num)', 'Cotizador::delete/$1');
    $routes->post('calcular', 'Cotizador::calcular');
    $routes->get('listar', 'Cotizador::listar');
    $routes->get('resumen', 'Cotizador::resumen');
    $routes->post('aprobar/(:num)', 'Cotizador::aprobar/$1');
    $routes->post('rechazar/(:num)', 'Cotizador::rechazar/$1');
});

// Rutas para el módulo de tipo de operación
$routes->group('tipo-operacion', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'TipoOperacion::index');
    $routes->get('create', 'TipoOperacion::create');
    $routes->get('show/(:num)', 'TipoOperacion::show/$1');
    $routes->get('edit/(:num)', 'TipoOperacion::edit/$1');
    
    // Acciones CRUD
    $routes->post('store', 'TipoOperacion::store');
    $routes->post('update/(:num)', 'TipoOperacion::update/$1');
    $routes->delete('delete/(:num)', 'TipoOperacion::delete/$1');
    
    // AJAX
    $routes->post('getData', 'TipoOperacion::getData');
    $routes->get('getEstadisticas', 'TipoOperacion::getEstadisticas');
});

// Rutas para el módulo de órdenes de trabajo
$routes->group('ordenes-trabajo', ['filter' => 'auth'], function($routes) {
    // Dashboard principal
    $routes->get('/', 'OrdenesTrabajo::index');
    
    // Secciones principales
    $routes->get('mis-ordenes', 'OrdenesTrabajo::misOrdenes');
    $routes->get('consulta', 'OrdenesTrabajo::consulta');
    $routes->get('bandeja-pendientes', 'OrdenesTrabajo::bandejaPendientes');
    $routes->get('bandeja-aprobadas', 'OrdenesTrabajo::bandejaAprobadas');
    $routes->get('bandeja-en-proceso', 'OrdenesTrabajo::bandejaEnProceso');
    $routes->get('bandeja-finalizadas', 'OrdenesTrabajo::bandejaFinalizadas');
    
    // Nuevas vistas
    $routes->get('calendario', 'OrdenesTrabajo::calendario');              // Vista calendario
    $routes->get('kanban', 'OrdenesTrabajo::kanban');                      // Vista Kanban
    
    // CRUD
    $routes->get('create', 'OrdenesTrabajo::create');
    $routes->post('store', 'OrdenesTrabajo::store');
    $routes->get('show/(:num)', 'OrdenesTrabajo::show/$1');
    $routes->get('edit/(:num)', 'OrdenesTrabajo::edit/$1');
    $routes->post('update/(:num)', 'OrdenesTrabajo::update/$1');
    $routes->get('clasificacion/(:num)', 'OrdenesTrabajo::clasificacion/$1');
    $routes->post('clasificacion/(:num)', 'OrdenesTrabajo::actualizarClasificacion/$1');
    
    // AJAX
    $routes->post('cambiar-estado', 'OrdenesTrabajo::cambiarEstado');
    $routes->post('asignar-orden', 'OrdenesTrabajo::asignarOrden');
    $routes->post('mover-solicitud', 'OrdenesTrabajo::moverSolicitud');    // Para Kanban
    $routes->get('api-eventos-calendario', 'OrdenesTrabajo::apiEventosCalendario'); // Para calendario
    
    // Realizar orden de trabajo
    $routes->get('realizar/(:num)', 'OrdenesTrabajo::realizar/$1');
    $routes->post('guardar-trabajo/(:num)', 'OrdenesTrabajo::guardarTrabajo/$1');
});

// Alias: solicitudes/crear → Solicitudes::create (usado en Menu.php)
$routes->get('solicitudes/crear', 'Solicitudes::create', ['filter' => 'auth']);

// Portal de Conductores (sin login, acceso público — futuro: auth por PIN)
$routes->group('portal', function($routes) {
    $routes->get('',                         'PortalConductores::index');
    $routes->get('solicitud/crear',          'PortalConductores::crear');
    $routes->post('solicitud/store',         'PortalConductores::store');
    $routes->get('solicitud/confirmacion/(:num)', 'PortalConductores::confirmacion/$1');
    $routes->get('solicitud/historial',      'PortalConductores::historial');
    $routes->get('solicitud/buscar-vehiculo','PortalConductores::buscarVehiculo');
});

// Rutas para el módulo de solicitudes de mantenimiento
$routes->group('solicitudes', ['filter' => 'auth'], function($routes) {
    // API/Data
    $routes->get('/', 'Solicitudes::index');
    $routes->get('create', 'Solicitudes::create');
    $routes->get('show/(:num)', 'Solicitudes::show/$1');
    $routes->get('buscar-vehiculo', 'Solicitudes::buscarVehiculo');
    
    // API/Data
    $routes->post('getData', 'Solicitudes::getData');
    $routes->post('store', 'Solicitudes::store');
    $routes->post('updateStatus/(:num)', 'Solicitudes::updateStatus/$1');
    $routes->post('getEstadisticas', 'Solicitudes::getEstadisticas');
    $routes->get('buscar', 'Solicitudes::buscar');
    
    // Documentos
    $routes->post('subirDocumento/(:num)', 'Solicitudes::subirDocumento/$1');
    $routes->post('eliminarDocumento/(:num)', 'Solicitudes::eliminarDocumento/$1');
});

// Rutas para configuración de widgets del dashboard
$routes->group('widget-config', ['filter' => 'auth'], function($routes) {
    $routes->get('get-config', 'WidgetConfig::getConfig');
    $routes->post('save-config', 'WidgetConfig::saveConfig');
    $routes->post('reset-config', 'WidgetConfig::resetConfig');
    $routes->get('widget-status/(:segment)', 'WidgetConfig::getWidgetStatus/$1');
    $routes->post('toggle-widget/(:segment)', 'WidgetConfig::toggleWidget/$1');
});

// Rutas para el módulo de conductores
$routes->group('conductores', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Conductores::index');
    $routes->get('getData', 'Conductores::getData');
    $routes->post('getData', 'Conductores::getData');
    $routes->get('getEstadisticas', 'Conductores::getEstadisticas');
    $routes->get('create', 'Conductores::create');
    $routes->post('store', 'Conductores::store');
    $routes->get('show/(:num)', 'Conductores::show/$1');
    $routes->get('edit/(:num)', 'Conductores::edit/$1');
    $routes->post('update/(:num)', 'Conductores::update/$1');
    $routes->delete('delete/(:num)', 'Conductores::delete/$1');
    $routes->post('cambiarEstado', 'Conductores::cambiarEstado');
    $routes->post('verificarDni', 'Conductores::verificarDni');
    
    // Rutas para la gestión de documentos de conductores
    $routes->get('documentos/(:num)', 'Conductores::documentos/$1');
    $routes->post('guardarDocumento', 'Conductores::guardarDocumento');
    $routes->get('editar-documento/(:num)', 'Conductores::editarDocumento/$1');
    $routes->post('actualizar-documento/(:num)', 'Conductores::actualizarDocumento/$1');
    $routes->delete('eliminar-documento/(:num)', 'Conductores::eliminarDocumento/$1');

    // Sincronización con ERP
    $routes->get('sincronizar', 'Conductores::sincronizar');
    $routes->get('conductores-erp', 'Conductores::listarConductoresErp');
    $routes->post('sincronizar-conductor', 'Conductores::sincronizarConductor');
    $routes->get('ver-documento/(:num)', 'Conductores::verDocumento/$1');
    $routes->get('descargar-documento/(:num)', 'Conductores::descargarDocumento/$1');
});

// Rutas para el buscador de rutas de menú
$routes->group('menu-routes', ['filter' => 'auth'], function($routes) {
    $routes->get('getRutas', 'MenuRoutes::getRutas');        // Obtener todas las rutas
    $routes->get('buscarRutas', 'MenuRoutes::buscarRutas');  // Buscar rutas
});

// Rutas para el módulo de materiales
$routes->group('materiales', ['filter' => 'auth'], function($routes) {
    // CRUD básico
    $routes->get('/', 'Materiales::index');
    $routes->get('create', 'Materiales::create');
    $routes->post('store', 'Materiales::store');
    $routes->get('show/(:num)', 'Materiales::show/$1');
    $routes->get('edit/(:num)', 'Materiales::edit/$1');
    $routes->put('update/(:num)', 'Materiales::update/$1');
    $routes->post('update/(:num)', 'Materiales::update/$1');
    $routes->post('delete/(:num)', 'Materiales::delete/$1');
    
    // APIs AJAX
    $routes->get('buscar', 'Materiales::buscar');
    $routes->get('obtener/(:num)', 'Materiales::obtener/$1');
    
    // Sincronización con SQL Server
    $routes->get('sincronizacion', 'Materiales::sincronizacion');
    $routes->get('productos-sqlserver', 'Materiales::obtenerProductosSqlServer');
    $routes->post('sincronizar-producto', 'Materiales::sincronizarProducto');
    $routes->post('sincronizar-masivo', 'Materiales::sincronizarProductosMasivo');
    
    // Exportación
    $routes->get('exportar', 'Materiales::exportar');
});

// Rutas para el módulo de catálogo
$routes->group('catalogo', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'Catalogo::index');                      // Lista de catálogos
    $routes->get('create', 'Catalogo::create');                // Formulario crear catálogo
    $routes->get('show/(:num)', 'Catalogo::show/$1');         // Ver detalles del catálogo
    $routes->get('edit/(:num)', 'Catalogo::edit/$1');         // Formulario editar catálogo
    
    // Acciones CRUD
    $routes->post('store', 'Catalogo::store');                // Crear catálogo
    $routes->post('update/(:num)', 'Catalogo::update/$1');    // Actualizar catálogo
    $routes->post('delete/(:num)', 'Catalogo::delete/$1');    // Eliminar catálogo
    
    // Cambio de estado
    $routes->post('cambiarEstado', 'Catalogo::cambiarEstado'); // Cambiar estado activo/inactivo
    
    // APIs AJAX
    $routes->get('getSubcatalogos/(:num)', 'Catalogo::getSubcatalogos/$1'); // Obtener subcatálogos
    $routes->post('verificarCodigo', 'Catalogo::verificarCodigo');          // Verificar código único
});

$routes->group('catalogo', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('getCatalogosPorCodigo/(:segment)', 'Catalogo::getCatalogosPorCodigo/$1');
});

// Rutas para el mantenimiento de tablas SQL Server
$routes->group('mantenimiento-tablas', ['filter' => 'auth'], function($routes) {
    // Vista principal
    $routes->get('/', 'MantenimientoTablas::index');                           // Lista de tablas
    
    // Ver datos de tabla
    $routes->get('ver/(:segment)', 'MantenimientoTablas::verTabla/$1');        // Ver datos de tabla específica
    $routes->get('simple/(:segment)', 'MantenimientoTablas::verTablaSimple/$1'); // Ver datos simple
    
    // Ver estructura de tabla
    $routes->get('estructura/(:segment)', 'MantenimientoTablas::estructuraTabla/$1'); // Ver estructura
    
    // Exportar datos
    $routes->get('exportar/(:segment)', 'MantenimientoTablas::exportarCSV/$1'); // Exportar a CSV
    
    // API para datos
    $routes->get('api/datos/(:segment)', 'MantenimientoTablas::apiDatosTabla/$1'); // API JSON
    $routes->get('obtener-tablas', 'MantenimientoTablas::obtenerTablas'); // Obtener lista de tablas
    $routes->get('obtener-vistas', 'MantenimientoTablas::obtenerVistas'); // Obtener lista de vistas
    $routes->get('obtener-referencias/(:segment)', 'MantenimientoTablas::buscarDatos/$1'); // Buscar referencias
    $routes->get('buscar-referencia', 'MantenimientoTablas::buscarReferencia'); // Buscar en tabla de referencia
    
    // Debug de tabla
    $routes->get('debug/(:segment)', 'MantenimientoTablas::debugTabla/$1'); // Debug tabla
});

// Rutas para el módulo de roles
$routes->group('rol', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'Rol::index');                        // Lista de roles
    $routes->get('create', 'Rol::create');                  // Formulario crear rol
    $routes->get('show/(:num)', 'Rol::show/$1');           // Ver detalles del rol
    $routes->get('edit/(:num)', 'Rol::edit/$1');           // Formulario editar rol
    
    // Acciones CRUD
    $routes->post('store', 'Rol::store');                  // Crear rol
    $routes->put('update/(:num)', 'Rol::update/$1');       // Actualizar rol
    $routes->delete('delete/(:num)', 'Rol::delete/$1');    // Eliminar rol
    
    // APIs y datos AJAX
    $routes->post('getData', 'Rol::getData');              // Datos para DataTables
    $routes->post('verificarNombre', 'Rol::verificarNombre'); // Verificar nombre único
    $routes->post('getEstadisticas', 'Rol::getEstadisticas'); // Estadísticas de roles
});

// Rutas para el módulo de menú
$routes->group('menu', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'Menu::index');                      // Lista de menús
    $routes->get('create', 'Menu::create');                // Formulario crear menú
    $routes->get('show/(:num)', 'Menu::show/$1');         // Ver detalles del menú
    $routes->get('edit/(:num)', 'Menu::edit/$1');         // Formulario editar menú
    
    // Acciones CRUD
    $routes->post('store', 'Menu::store');                // Crear menú
    $routes->post('update/(:num)', 'Menu::update/$1');    // Actualizar menú
    $routes->delete('delete/(:num)', 'Menu::delete/$1');    // Eliminar menú
    
    // API endpoints
    $routes->post('getData', 'Menu::getData');            // DataTables AJAX
    $routes->get('getMenuTree', 'Menu::getMenuTree');     // Estructura en árbol
    $routes->post('reorder', 'Menu::reorder');            // Reordenar menús
});

// Rutas para el módulo de accesos (gestión de permisos rol-menú)
$routes->group('acceso', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'Acceso::index');                    // Lista de accesos
    $routes->get('create', 'Acceso::create');              // Formulario crear acceso
    $routes->get('show/(:num)', 'Acceso::show/$1');       // Ver detalles del acceso
    $routes->get('edit/(:num)', 'Acceso::edit/$1');       // Formulario editar acceso
    
    // Acciones CRUD
    $routes->post('store', 'Acceso::store');              // Crear acceso
    $routes->post('store-multiple', 'Acceso::storeMultiple'); // Crear múltiples accesos
    
    // API endpoints
    $routes->post('api/accesos-rol', 'Acceso::getAccesosPorRol'); // Obtener accesos de un rol
    $routes->post('getData', 'Acceso::getData'); // DataTables AJAX
    $routes->put('update/(:num)', 'Acceso::update/$1');   // Actualizar acceso
    $routes->delete('delete/(:num)', 'Acceso::delete/$1'); // Eliminar acceso
    
    // Acciones de estado
    $routes->post('cambiarEstado/(:num)', 'Acceso::cambiarEstado/$1'); // Cambiar estado
    $routes->post('api/datatable', 'Acceso::getDatatableData');
    $routes->post('api/menus-disponibles', 'Acceso::getMenusDisponibles');
    $routes->post('api/accesos-rol', 'Acceso::getAccesosRol');
    $routes->get('api/estadisticas', 'Acceso::getEstadisticas'); // Estadísticas de accesos
    $routes->get('getEstadisticas', 'Acceso::getEstadisticas'); // Estadísticas de accesos (ruta directa)
    $routes->post('getEstadisticas', 'Acceso::getEstadisticas'); // Estadísticas de accesos (POST)
    $routes->get('getMenusDisponibles', 'Acceso::getMenusDisponibles'); // Menús disponibles para un rol
});

// Rutas para el módulo de tipos de operación
$routes->group('tipo-operacion', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'TipoOperacion::index');
    $routes->get('create', 'TipoOperacion::create');
    $routes->get('show/(:num)', 'TipoOperacion::show/$1');
    $routes->get('edit/(:num)', 'TipoOperacion::edit/$1');
    
    // Acciones CRUD
    $routes->post('store', 'TipoOperacion::store');
    $routes->post('update/(:num)', 'TipoOperacion::update/$1');
    $routes->delete('delete/(:num)', 'TipoOperacion::delete/$1');
    
    // Acciones AJAX
    $routes->post('getData', 'TipoOperacion::getData');
    $routes->post('cambiarEstado', 'TipoOperacion::cambiarEstado');
    $routes->post('getEstadisticas', 'TipoOperacion::getEstadisticas');
    $routes->post('verificarDescripcion', 'TipoOperacion::verificarDescripcion');
});

// Rutas para el módulo de tipos de unidad
$routes->group('tipo-unidad', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'TipoUnidad::index');
    $routes->get('create', 'TipoUnidad::create');
    $routes->get('show/(:num)', 'TipoUnidad::show/$1');
    $routes->get('edit/(:num)', 'TipoUnidad::edit/$1');
    
    // Acciones CRUD
    $routes->post('store', 'TipoUnidad::store');
    $routes->post('update/(:num)', 'TipoUnidad::update/$1');
    $routes->delete('delete/(:num)', 'TipoUnidad::delete/$1');
    
    // Acciones AJAX
    $routes->post('getData', 'TipoUnidad::getData');
    $routes->post('cambiarEstado', 'TipoUnidad::cambiarEstado');
    $routes->post('getEstadisticas', 'TipoUnidad::getEstadisticas');
    $routes->post('verificarDescripcion', 'TipoUnidad::verificarDescripcion');
});

// Rutas para el módulo de asignación de vehículos
$routes->group('asignacion-vehiculos', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'AsignacionVehiculos::index');
    $routes->get('show/(:num)', 'AsignacionVehiculos::show/$1');
    $routes->get('test', 'AsignacionVehiculos::test');
    
    // Wizard de asignación
    $routes->get('wizard', 'AsignacionVehiculos::wizard');
    $routes->get('wizard/step1', 'AsignacionVehiculos::wizardStep1');
    $routes->post('wizard/step1', 'AsignacionVehiculos::processStep1');
    $routes->get('wizard/step2/(:num)', 'AsignacionVehiculos::wizardStep2/$1');
    $routes->get('wizard/step2', 'AsignacionVehiculos::wizardStep2');
    $routes->post('wizard/step2', 'AsignacionVehiculos::processStep2');
    $routes->get('wizard/step3/(:num)/(:num)', 'AsignacionVehiculos::wizardStep3/$1/$2');
    $routes->get('wizard/step3', 'AsignacionVehiculos::wizardStep3');
    $routes->post('wizard/confirmar', 'AsignacionVehiculos::confirmarAsignacion');
    
    // Guardar asignación
    $routes->post('store', 'AsignacionVehiculos::store');
    
    // Desasignación
    $routes->get('desasignar/(:num)', 'AsignacionVehiculos::desasignar/$1');
    $routes->post('desasignar/(:num)', 'AsignacionVehiculos::processDesasignar/$1');
    
    // Métodos AJAX
    $routes->get('getData', 'AsignacionVehiculos::getData');
    $routes->get('getEstadisticas', 'AsignacionVehiculos::getEstadisticas');
    $routes->get('getVehiculosPorTipo/(:num)', 'AsignacionVehiculos::getVehiculosPorTipo/$1');
    $routes->get('testConnection', 'AsignacionVehiculos::testConnection');
});

// Rutas de reportes (requieren autenticación)
$routes->group('reportes', ['filter' => 'auth'], function($routes) {
    $routes->get('combustible', 'Reportes::combustible');
    $routes->get('ajax-top10', 'Reportes::ajaxTop10');
    $routes->get('ajax-rendimiento', 'Reportes::ajaxRendimiento');
    $routes->get('ajax-rendimiento-detalle/(:num)', 'Reportes::ajaxRendimientoDetalle/$1');
    $routes->get('ajax-comparativo', 'Reportes::ajaxComparativo');
    $routes->get('ajax-turnos', 'Reportes::ajaxTurnos');
    $routes->get('exportar-excel', 'Reportes::exportarExcel');

    $routes->get('vehiculos', 'Reportes::vehiculos');
    $routes->get('ajax-resumen-flota', 'Reportes::ajaxResumenFlota');
    $routes->get('ajax-ranking-rendimiento', 'Reportes::ajaxRankingRendimiento');
    $routes->get('ajax-kilometraje', 'Reportes::ajaxKilometraje');
    $routes->get('exportar-excel-vehiculos', 'Reportes::exportarExcelVehiculos');
});

// Rutas para el módulo de tipos de problema
$routes->group('tipos-problema', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'TiposProblema::index');
    $routes->post('store', 'TiposProblema::store');
    $routes->post('update/(:num)', 'TiposProblema::update/$1');
    $routes->delete('delete/(:num)', 'TiposProblema::delete/$1');
    $routes->get('get/(:num)', 'TiposProblema::get/$1');
});

// Rutas para el módulo de configuración del sistema
$routes->group('configuracion', ['filter' => 'auth'], function($routes) {
    // Vista principal de configuración
    $routes->get('/', 'Configuracion::index');
    
    // Gestión de usuarios
    $routes->get('registro-usuario', 'Configuracion::registroUsuario');
    $routes->post('crear-usuario', 'Configuracion::crearUsuario');
    
    // APIs y estadísticas
    $routes->get('get-estadisticas-usuarios', 'Configuracion::getEstadisticasUsuarios');
    $routes->get('get-usuarios-empresa', 'Configuracion::getUsuariosEmpresa');
});

// Rutas para el módulo de usuarios
$routes->group('usuarios', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'Usuarios::index');
    $routes->get('create', 'Usuarios::create');
    $routes->get('show/(:num)', 'Usuarios::show/$1');
    $routes->get('edit/(:num)', 'Usuarios::edit/$1');
    $routes->get('(:num)', 'Usuarios::show/$1'); // Alias para show
    $routes->get('(:num)/edit', 'Usuarios::edit/$1'); // Alias para edit
    
    // Acciones CRUD
    $routes->post('store', 'Usuarios::store');
    $routes->post('update/(:num)', 'Usuarios::update/$1');
    $routes->post('(:num)', 'Usuarios::update/$1'); // Alias para update
    $routes->delete('delete/(:num)', 'Usuarios::delete/$1');
    
    // Resetear contraseña
    $routes->get('(:num)/reset-password', 'Usuarios::resetPassword/$1');
    $routes->post('(:num)/reset-password', 'Usuarios::processResetPassword/$1');
    $routes->post('(:num)/generate-temp-password', 'Usuarios::generateTempPassword/$1');
    
    // Acciones AJAX
    $routes->post('cambiarEstado/(:num)', 'Usuarios::cambiarEstado/$1');
    $routes->match(['get', 'post'], 'getUsuariosAjax', 'Usuarios::getUsuariosAjax');
});

// Rutas para el módulo de direcciones
$routes->group('historial-orden-trabajo', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'HistorialOrdenTrabajo::index');
    $routes->get('create', 'HistorialOrdenTrabajo::create');
    $routes->get('create/(:num)', 'HistorialOrdenTrabajo::create/$1');
    $routes->post('store', 'HistorialOrdenTrabajo::store');
    $routes->get('show/(:num)', 'HistorialOrdenTrabajo::show/$1');
    $routes->get('delete/(:num)', 'HistorialOrdenTrabajo::delete/$1');
    
    // Rutas AJAX
    $routes->get('search', 'HistorialOrdenTrabajo::searchSolicitudes');
    $routes->get('get-historial/(:num)', 'HistorialOrdenTrabajo::getHistorialSolicitud/$1');
});

$routes->group('direcciones', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'Direccion::index');
    $routes->get('create', 'Direccion::create');
    $routes->get('show/(:num)', 'Direccion::show/$1');
    $routes->get('edit/(:num)', 'Direccion::edit/$1');
    
    // Acciones CRUD
    $routes->post('store', 'Direccion::store');
    $routes->post('update/(:num)', 'Direccion::update/$1');
    $routes->delete('delete/(:num)', 'Direccion::delete/$1');
    
    // Vista de mapa
    $routes->get('mapa', 'Direccion::mapa');
    
    // Sincronización con SQL Server
    $routes->get('sincronizar', 'Direccion::sincronizar');
    $routes->get('getDireccionesSqlServer', 'Direccion::getDireccionesSqlServer');
    $routes->post('importarDireccion', 'Direccion::importarDireccion');
    
    // APIs para geocodificación
    $routes->post('geocode', 'Direccion::geocode');
    $routes->post('reverseGeocode', 'Direccion::reverseGeocode');
    $routes->get('nearby/(:num)', 'Direccion::getNearby/$1');
    
    // Acciones AJAX
    $routes->post('getData', 'Direccion::getData');
    $routes->get('getMapData', 'Direccion::getMapData');
    $routes->post('cambiarEstado/(:num)', 'Direccion::cambiarEstado/$1');
    $routes->get('getEstadisticas', 'Direccion::getEstadisticas');
});

// Rutas de Movimientos de Inventario (requieren autenticación)
$routes->group('movimientos', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'Movimientos::index');
    $routes->get('create', 'Movimientos::create');
    $routes->get('show/(:num)', 'Movimientos::show/$1');
    $routes->get('edit/(:num)', 'Movimientos::edit/$1');
    
    // Acciones CRUD
    $routes->post('store', 'Movimientos::store');
    $routes->post('update/(:num)', 'Movimientos::update/$1');
    $routes->delete('delete/(:num)', 'Movimientos::delete/$1');
    
    // Acciones AJAX
    $routes->match(['get', 'post'], 'getData', 'Movimientos::getData');
    $routes->post('cambiarEstado', 'Movimientos::cambiarEstado');
    $routes->get('getEstadisticas', 'Movimientos::getEstadisticas');
    $routes->get('buscarMateriales', 'Movimientos::buscarMateriales');
    $routes->get('buscarOrdenesEnProceso', 'Movimientos::buscarOrdenesEnProceso');
    $routes->post('buscarMaterialPorCodigo', 'Movimientos::buscarMaterialPorCodigo');
    $routes->get('debugMaterialSearch', 'Movimientos::debugMaterialSearch');
});

// Rutas para el módulo de lectura de bomba (requieren autenticación)
$routes->group('lectura-bomba', ['filter' => 'auth'], function($routes) {
    // Vistas principales
    $routes->get('/', 'LecturaBomba::index');
    $routes->get('dashboard', 'LecturaBomba::dashboard');
    $routes->get('monitor', 'LecturaBomba::monitor');
    $routes->get('apertura', 'LecturaBomba::apertura');
    $routes->get('cierre/(:num)', 'LecturaBomba::cierre/$1');
    $routes->get('cierre', 'LecturaBomba::cierre');
    $routes->get('detalle/(:num)', 'LecturaBomba::detalle/$1');
    $routes->get('reporte', 'LecturaBomba::reporte');

    // Acciones CRUD
    $routes->post('guardar-apertura', 'LecturaBomba::guardarApertura');
    $routes->post('guardar-cierre', 'LecturaBomba::guardarCierre');

    // AJAX endpoints
    $routes->post('verificar-apertura', 'LecturaBomba::verificarApertura');
    $routes->get('verificar-apertura-usuario', 'LecturaBomba::verificarAperturaUsuario');
    $routes->get('obtener-consumo/(:num)', 'LecturaBomba::obtenerConsumo/$1');
    $routes->get('aperturas-pendientes', 'LecturaBomba::aperturasPendientes');
    $routes->get('registros-combustible/(:num)', 'LecturaBomba::registrosCombustible/$1');
});

// Rutas para pruebas de correo electrónico (requieren autenticación)
$routes->group('test-email', ['filter' => 'auth'], function($routes) {
    // Página principal de pruebas
    $routes->get('/', 'TestEmail::index');
    
    // Pruebas de estado y conexión
    $routes->get('status', 'TestEmail::testStatus');
    $routes->get('connection', 'TestEmail::testConnection');
    $routes->get('info', 'TestEmail::getEmailInfo');
    
    // Envío de correos de prueba
    $routes->post('send-test', 'TestEmail::sendTestEmail');
    $routes->post('send-template', 'TestEmail::sendTemplateEmail');
    $routes->post('send-notification', 'TestEmail::sendSystemNotification');
    
    // Validación de correos
    $routes->post('validate', 'TestEmail::validateEmail');
    
    // Gestión de logs
    $routes->get('logs', 'TestEmail::getEmailLogs');
    $routes->post('logs/clear', 'TestEmail::clearEmailLogs');
});

