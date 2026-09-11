# NEURONA — Mapa del Sistema TMS

> Mapa neuronal de relaciones entre Vistas, Controladores, Services y Models.  
> Para que cualquier IA o dev encuentre rápidamente dónde está cada cosa.

---

## Leyenda

```
[V] Vista          → app/Views/{carpeta}/{archivo}.php
[C] Controlador    → app/Controllers/{Archivo}.php
[S] Service        → app/Services/{Archivo}.php
[M] Model          → app/Models/{Archivo}.php
[H] Helper         → app/Helpers/{archivo}.php
[F] Filter         → app/Filters/{Archivo}.php
[JS] JavaScript    → public/assets/js/{core|modules}/{archivo}.js
[L] Layout         → app/Views/layouts/{archivo}.php
[R] Ruta           → app/Config/Routes.php (línea aprox.)
```

---

## Módulo: Vehículos

### Flujo principal
```
[R] /vehiculos (línea 51)
 └── [C] Vehiculos.php
      ├── index()        → [V] vehiculos/index.php    → [JS] Vehiculos.js (VehiculosIndex.init)
      ├── create()       → [V] vehiculos/create.php
      ├── show($id)      → [V] vehiculos/show.php      → [JS] Vehiculos.js (VehiculosShow.init)
      ├── edit($id)      → [V] vehiculos/edit.php
      ├── store()        → [S] VehiculoService::crear()
      ├── update($id)    → [S] VehiculoService::actualizar()
      ├── delete($id)    → [S] VehiculoService::eliminar()
      ├── cambiarEstado()→ [S] VehiculoService::cambiarEstado()
      ├── getData()      → [S] VehiculoService::getParaVista() (AJAX)
      └── getEstadisticas() → [M] VehiculoModel (AJAX)
```

### Documentos del vehículo
```
[R] /vehiculos/documentos/(:num) (línea 67)
 └── [C] Vehiculos::documentos($id)
      ├── [M] VehiculoModel::find($id)
      ├── [M] CatalogoModel::getCatalogosPorCodigo('CAT-0002')
      ├── Calcula alertas (notificacion + fecha_vencimiento)
      └── [V] vehiculos/documentos.php
           ├── [V] vehiculos/partials/documentos_cards.php (vista cards)
           └── [V] vehiculos/partials/documentos_table.php (vista tabla)

[C] Vehiculos::subirDocumento()
 ├── [M] DocumentacionVehiculoModel (INSERT)
 └── Upload a public/uploads/documentos/

[C] Vehiculos::getAlertasDocumentos() (línea 2137)
 └── [V] layouts/main.php → GMVNotifications (badge + sidebar)
```

### Reportes de vehículos
```
[C] Vehiculos::reportes()       → [V] vehiculos/reportes.php
[C] Vehiculos::reporteDocumentosPorVencer() → JSON (AJAX)
[C] Vehiculos::reporteVehiculosPorEstado()  → JSON (AJAX)
[C] Vehiculos::reporteMantenimientoVencido() → JSON (AJAX)
[C] Vehiculos::reporteKilometraje()         → JSON (AJAX)
```

### Modelos relacionados
```
[M] VehiculoModel.php
 ├── getCentrosCosto()        → SQL Server (sqlsrv_configure LoginTimeout=3)
 ├── getVehiculosConAsignacion()
 ├── getKilometrajeActual($id)
 └── getHistorialEstados($id)

[M] DocumentacionVehiculoModel.php
[M] DocumentosVehiculosVencidosModel.php
```

### Service
```
[S] VehiculoService.php
 ├── getParaVista()
 ├── crear()
 ├── actualizar()
 ├── cambiarEstado()
 ├── eliminar()
 ├── getCentrosCostoParaSelect()
 ├── getCentroCosto()
 ├── getConductoresDisponibles()
 └── getHistorialEstados()
```

### Helper
```
[H] vehiculo_helper.php
 ├── vehiculo_estado_badge($estado)
 ├── vehiculo_estado_class($estado)
 └── vehiculo_estado_label($estado)
```

---

## Módulo: Órdenes de Trabajo

### Flujo principal
```
[R] /ordenes-trabajo (línea 196)
 └── [C] OrdenesTrabajo.php
      ├── index()             → [V] ordenes_trabajo/dashboard.php
      ├── misOrdenes()        → [V] ordenes_trabajo/mis_ordenes.php
      ├── consulta()          → [V] ordenes_trabajo/consulta.php
      ├── bandejaPendientes() → [V] ordenes_trabajo/bandeja_pendientes.php
      ├── kanban()            → [V] ordenes_trabajo/kanban.php
      ├── calendario()        → [V] ordenes_trabajo/calendario.php
      ├── create()            → [V] ordenes_trabajo/create.php
      ├── show($id)           → [V] ordenes_trabajo/show.php
      ├── clasificacion($id)  → [V] ordenes_trabajo/clasificacion.php
      ├── realizar($id)       → [V] ordenes_trabajo/realizar.php
      ├── guardarTrabajo($id) → [M] RegistroTrabajoModel
      ├── cambiarEstado()     → [M] SolicitudModel (AJAX)
      ├── asignarOrden()      → [M] SolicitudModel (AJAX)
      └── apiEventosCalendario() → [M] SolicitudModel::getSolicitudesParaCalendario()
```

### Modelos relacionados
```
[M] SolicitudModel.php (tabla: solicitudes)
 ├── getSolicitudesParaCalendario($idEmpresa)
 ├── getSolicitudesConVehiculo()
 └── getEstadisticas()

[M] RegistroTrabajoModel.php (tabla: registro_trabajo)
[M] HistorialOrdenTrabajoModel.php (tabla: solicitudes_historial)
[M] SolicitudHistorialModel.php
[M] SolicitudDocumentoModel.php
[M] TipoProblemaModel.php (tabla: tipos_problema)
[M] MaterialesTrabajoModel.php
```

### Vistas
```
[V] ordenes_trabajo/
 ├── dashboard.php        → Panel principal con stats
 ├── kanban.php           → Tablero Kanban (drag & drop)
 ├── calendario.php       → FullCalendar con eventos
 ├── create.php           → Crear nueva OT
 ├── show.php             → Detalle de OT
 ├── realizar.php         → Ejecutar trabajo + materiales
 ├── clasificacion.php    → Clasificar problema
 ├── consulta.php         → Buscar/filtrar OT
 ├── bandeja_pendientes.php → Solicitudes pendientes
 ├── mis_ordenes.php      → OT asignadas al usuario
 └── partials/            → Fragmentos reutilizables
```

---

## Módulo: Solicitudes de Mantenimiento

### Flujo principal
```
[R] /solicitudes (línea 246)
 └── [C] Solicitudes.php
      ├── index()      → [V] solicitudes/index.php
      ├── create()     → [V] solicitudes/create.php
      ├── show($id)    → [V] solicitudes/show.php
      ├── store()      → [M] SolicitudModel
      ├── updateStatus() → [M] SolicitudModel + [M] SolicitudHistorialModel
      ├── subirDocumento($id) → [M] SolicitudDocumentoModel
      └── getData()    → [M] SolicitudModel (AJAX)

[C] PortalConductores.php (ruta: /portal)
 ├── index()      → [V] portal_conductores/index.php
 ├── crear()      → [V] portal_conductores/crear.php
 ├── store()      → [M] SolicitudModel
 └── confirmacion() → [V] portal_conductores/confirmacion.php
```

### Vistas
```
[V] solicitudes/
 ├── index.php       → Lista de solicitudes
 ├── create.php      → Wizard de creación
 ├── nuevo_wizard.php → Nuevo wizard (alternativo)
 └── show.php        → Detalle + documentos + historial
```

---

## Módulo: Registro de Combustible

### Flujo principal
```
[R] /registro-combustible (línea 124)
 └── [C] RegistroCombustible.php
      ├── index()         → [V] registro_combustible/index.php
      ├── create()        → [V] registro_combustible/form.php
      ├── createVenta()   → [V] registro_combustible/form_venta.php
      ├── show($id)       → [V] registro_combustible/show.php
      ├── edit($id)       → [V] registro_combustible/form.php
      ├── estadisticas()  → [V] registro_combustible/estadisticas.php
      ├── store()         → [S] RegistroCombustibleService
      ├── update($id)     → [S] RegistroCombustibleService
      ├── voucherConsumo()  → [V] registro_combustible/voucher_consumo.php
      ├── voucherVenta()    → [V] registro_combustible/voucher_venta.php
      ├── supervisor()      → [V] registro_combustible/supervisor.php
      ├── aprobar($id)      → [S] RegistroCombustibleService
      ├── rechazar($id)     → [S] RegistroCombustibleService
      ├── reenviarSAG($id)  → [S] RegistroCombustibleService → SAG
      └── getData()         → [S] RegistroCombustibleService (AJAX)
```

### Service
```
[S] RegistroCombustibleService.php
 ├── Validar datos
 ├── Calcular rendimiento (km/gal)
 ├── Enviar a SAG
 ├── Aprobar/Rechazar (supervisor)
 └── Vouchers
```

### Modelos
```
[M] RegistroCombustibleModel.php (tabla: registro_combustible)
[M] MotivoAsignacionCombustibleModel.php
[M] TipoMotivoCombustibleModel.php
```

### Vistas
```
[V] registro_combustible/
 ├── index.php          → Lista con filtros
 ├── form.php           → Formulario consumo (con modal vehículo, cálculos JS)
 ├── form_venta.php     → Formulario venta
 ├── show.php           → Detalle del registro
 ├── estadisticas.php   → Gráficos ApexCharts
 ├── voucher_consumo.php → Voucher imprimible
 ├── voucher_venta.php   → Voucher venta imprimible
 ├── supervisor.php     → Bandeja de aprobación
 └── partials/          → Fragmentos
```

### JS
```
[JS] RegistroCombustible.js → Lógica de formularios, cálculos, validaciones
```

---

## Módulo: Lectura de Bomba

### Flujo principal
```
[R] /lectura-bomba (línea 662)
 └── [C] LecturaBomba.php
      ├── index()      → [V] lectura_bomba/index.php
      ├── dashboard()  → [V] lectura_bomba/dashboard.php
      ├── monitor()    → [V] lectura_bomba/monitor.php
      ├── apertura()   → [V] lectura_bomba/apertura.php
      ├── cierre($id)  → [V] lectura_bomba/cierre.php
      ├── detalle($id) → [V] lectura_bomba/detalle.php
      ├── guardarApertura() → [S] LecturaBombaService
      └── guardarCierre()   → [S] LecturaBombaService
```

### Service + Model
```
[S] LecturaBombaService.php
[M] LecturaBombaModel.php
```

---

## Módulo: Conductores

### Flujo principal
```
[R] /conductores (línea 274)
 └── [C] Conductores.php
      ├── index()      → [V] conductores/index.php
      ├── create()     → [V] conductores/create.php
      ├── show($id)    → [V] conductores/show.php
      ├── edit($id)    → [V] conductores/edit.php
      ├── documentos($id) → [V] conductores/documentos.php
      ├── sincronizar() → [M] ConductorErpModel (SQL Server)
      └── getData()    → [M] ConductorModel (AJAX)
```

### Modelos
```
[M] ConductorModel.php
[M] ConductorErpModel.php (SQL Server — sincronización)
[M] DocumentacionConductorModel.php
```

---

## Módulo: Materiales y Movimientos (SAG)

### Materiales
```
[R] /materiales (línea 310)
 └── [C] Materiales.php
      ├── index()    → [V] materiales/index.php
      ├── create()   → [V] materiales/create.php
      ├── show($id)  → [V] materiales/show.php
      ├── sincronizacion() → [V] materiales/sincronizacion.php
      └── obtenerProductosSqlServer() → [M] InvProductosModel (SQL Server)

[M] MaterialesModel.php
[M] MaterialesTrabajoModel.php
```

### Movimientos
```
[R] /movimientos (línea 639)
 └── [C] Movimientos.php
      ├── index()    → [V] movimientos/index.php
      ├── create()   → [V] movimientos/create.php
      └── getData()  → [M] MovimientosModel (AJAX)

[M] MovimientosModel.php
[M] DetalleMovimientoModel.php
[M] InvProductosModel.php (SQL Server — catálogo SAG)
[M] InvComprasEModel.php (SQL Server — compras SAG)
```

---

## Módulo: Catálogo

```
[R] /catalogo (línea 336)
 └── [C] Catalogo.php
      ├── index()   → [V] catalogo/index.php
      ├── create()  → [V] catalogo/create.php
      ├── show($id) → [V] catalogo/show.php
      └── getCatalogosPorCodigo($codigo) → [M] CatalogoModel (AJAX, público)

[M] CatalogoModel.php
 └── getCatalogosPorCodigo($codigo) — usado por CAT-0002 (tipos documento), etc.
```

---

## Módulo: Asignación de Vehículos

```
[R] /asignacion-vehiculos (línea 494)
 └── [C] AsignacionVehiculos.php
      ├── index()    → [V] asignacion_vehiculos/index.php
      ├── wizard()   → [V] asignacion_vehiculos/wizard.php (3 pasos)
      ├── show($id)  → [V] asignacion_vehiculos/show.php
      └── getData()  → [M] AsignacionVehiculoModel (AJAX)

[M] AsignacionVehiculoModel.php
```

---

## Módulo: Autenticación y Permisos

### Auth
```
[C] Auth.php
 ├── login()     → [V] auth/login.php    → [L] layouts/login.php
 ├── authenticate() → [M] AuthModel
 ├── registerWizard() → [V] auth/register_wizard.php
 └── logout()

[M] AuthModel.php
```

### Roles, Menús, Accesos
```
[C] Rol.php     → [V] rol/     → [M] RolModel.php
[C] Roles.php   → [V] roles/   (admin only)
[C] Menu.php    → [V] menu/    → [M] MenuModel.php
[C] Acceso.php  → [V] acceso/  → [M] AccesoModel.php
[C] MenuRoutes.php → Búsqueda de rutas (AJAX)

[F] AuthFilter.php    → Verifica sesión
[F] AccessFilter.php  → Verifica permiso de acceso (rol → menú → ruta)
[F] AdminFilter.php   → Solo admin

[H] access_helper.php
 ├── tiene_acceso($ruta)
 ├── get_menu_usuario()
 └── verificar_permiso($ruta, $accion)
```

---

## Módulo: Dashboard

```
[R] /dashboard (línea 22)
 └── [C] Dashboard.php
      └── index() → [V] dashboard/index.php → [JS] Dashboard.js

[C] WidgetConfig.php
 ├── getConfig()   → JSON
 ├── saveConfig()  → JSON
 └── toggleWidget() → JSON

[JS] Dashboard.js → Inicializa widgets, gráficos, carga dinámica
```

---

## Módulo: Reportes

```
[R] /reportes (línea 526)
 └── [C] Reportes.php
      ├── combustible() → [V] reportes/combustible.php
      ├── vehiculos()   → [V] reportes/vehiculos.php
      ├── ajaxTop10()   → JSON
      ├── ajaxRendimiento() → JSON
      └── exportarExcel() → CSV/Excel
```

---

## Módulo: Cotizador

```
[R] /cotizador (línea 160)
 └── [C] Cotizador.php
      ├── index()    → [V] cotizador/index.php
      ├── historial() → [V] cotizador/historial.php
      ├── show($id)  → [V] cotizador/show.php
      ├── store()    → [S] CotizacionService
      └── calcular() → [S] CotizacionService

[S] CotizacionService.php
[M] CotizacionModel.php
[M] ItemModel.php
```

---

## Módulo: Configuración y Usuarios

```
[C] Configuracion.php → [V] configuracion/index.php
[C] Usuarios.php     → [V] usuarios/index.php, create.php, show.php, edit.php
[C] Profile.php      → [V] profile/index.php, edit.php
[M] UsuarioModel.php
[M] EmpresaModel.php
```

---

## Módulo: Direcciones

```
[R] /direcciones (línea 606)
 └── [C] Direccion.php
      ├── index() → [V] direcciones/index.php
      ├── mapa()  → [V] direcciones/mapa.php
      ├── geocode() → Google Maps API
      └── sincronizar() → SQL Server

[M] DireccionModel.php
```

---

## Infraestructura transversal

### Layout
```
[L] layouts/main.php
 ├── Sidebar (menú dinámico desde [M] MenuModel + [H] access_helper)
 ├── Navbar (buscador de rutas → [C] MenuRoutes)
 ├── Notification sidebar (GMVNotifications API)
 │    ├── Badge con contador
 │    ├── Render de items
 │    └── Fetch automático: vehiculos/getAlertasDocumentos
 ├── CSRF override (window.fetch + jQuery ajaxSend)
 └── [L] layouts/sidebar.php (include)
```

### JS Core
```
[JS] core/HttpClient.js → Wrapper de fetch con CSRF automático
[JS] core/UI.js        → Helpers de UI (toasts, modals, loading)
```

### Config
```
[Config] Routes.php     → 708 líneas, todas las rutas del sistema
[Config] Database.php   → SQL Server + MySQL
[Config] Security.php   → CSRF ($regenerate = false)
[Config] Filters.php    → Registro de filtros auth, access, admin
[Config] Menu.php       → Configuración del menú (array estático)
```

---

## Tablas principales de BD (SQL Server)

| Tabla | Modelo | Módulo | Descripción |
|-------|--------|--------|-------------|
| `vehiculos` | VehiculoModel | Vehículos | Maestro de vehículos |
| `documentos_vehiculos` | DocumentacionVehiculoModel | Vehículos | Documentos por vehículo |
| `solicitudes` | SolicitudModel | OT/Solicitudes | Solicitudes de mantenimiento |
| `solicitudes_documentos` | SolicitudDocumentoModel | Solicitudes | Documentos de solicitudes |
| `solicitudes_historial` | SolicitudHistorialModel | Solicitudes | Historial de cambios de estado |
| `registro_trabajo` | RegistroTrabajoModel | OT | Trabajo ejecutado en OT |
| `registro_combustible` | RegistroCombustibleModel | Combustible | Registros de consumo/venta |
| `lectura_bomba` | LecturaBombaModel | Bomba | Apertura/cierre de bomba |
| `materiales` | MaterialesModel | Materiales | Catálogo de materiales |
| `materiales_trabajo` | MaterialesTrabajoModel | OT | Materiales usados en OT |
| `movimientos` | MovimientosModel | Movimientos | Movimientos de inventario SAG |
| `detalle_movimientos` | DetalleMovimientoModel | Movimientos | Detalle de movimientos |
| `catalogo` | CatalogoModel | Catálogo | Catálogos genéricos |
| `tipos_problema` | TipoProblemaModel | OT | Tipos de problema |
| `usuarios` | UsuarioModel | Auth | Usuarios del sistema |
| `roles` | RolModel | Roles | Roles del sistema |
| `menus` | MenuModel | Menú | Menú del sistema |
| `accesos` | AccesoModel | Accesos | Permisos rol-menú |
| `empresas` | EmpresaModel | Auth | Empresas (multi-tenant) |
| `conductores` | ConductorModel | Conductores | Maestro de conductores |
| `asignacion_vehiculos` | AsignacionVehiculoModel | Asignación | Asignaciones vehículo-conductor |
| `cotizaciones` | CotizacionModel | Cotizador | Cotizaciones de transporte |
| `items` | ItemModel | Cotizador | Items de cotización |
| `direcciones` | DireccionModel | Direcciones | Direcciones geográficas |

### Tablas SAG (SQL Server — solo lectura desde TMS)
| Tabla/Vista | Modelo | Uso |
|-------------|--------|-----|
| Productos SAG | InvProductosModel | Catálogo de artículos |
| Compras SAG | InvComprasEModel | Órdenes de compra |
| Conductores ERP | ConductorErpModel | Sincronización conductores |

---

## Rutas AJAX principales

| Endpoint | Controlador::Método | Uso |
|----------|---------------------|-----|
| `vehiculos/getData` | Vehiculos::getData | DataTables vehículos |
| `vehiculos/getAlertasDocumentos` | Vehiculos::getAlertasDocumentos | Notificaciones de documentos |
| `vehiculos/getEstadisticas` | Vehiculos::getEstadisticas | Stats del dashboard |
| `ordenes-trabajo/cambiar-estado` | OrdenesTrabajo::cambiarEstado | Cambiar estado OT |
| `ordenes-trabajo/api-eventos-calendario` | OrdenesTrabajo::apiEventosCalendario | Eventos FullCalendar |
| `solicitudes/getData` | Solicitudes::getData | DataTables solicitudes |
| `solicitudes/updateStatus/(:num)` | Solicitudes::updateStatus | Cambiar estado solicitud |
| `registro-combustible/getData` | RegistroCombustible::getData | DataTables combustible |
| `registro-combustible/getKilometrajeVehiculo/(:num)` | RegistroCombustible::getKilometrajeVehiculo | KM actual vehículo |
| `catalogo/getCatalogosPorCodigo/(:segment)` | Catalogo::getCatalogosPorCodigo | Catálogo por código |
| `conductores/getData` | Conductores::getData | DataTables conductores |
| `movimientos/getData` | Movimientos::getData | DataTables movimientos |

---

## Índice rápido: "¿Dónde está...?"

| Si buscas... | Mira en... |
|--------------|-----------|
| Lista de vehículos | `Vehiculos::index` → `vehiculos/index.php` |
| Detalle de vehículo | `Vehiculos::show` → `vehiculos/show.php` |
| Documentos de vehículo | `Vehiculos::documentos` → `vehiculos/documentos.php` |
| Crear OT | `OrdenesTrabajo::create` → `ordenes_trabajo/create.php` |
| Kanban de OT | `OrdenesTrabajo::kanban` → `ordenes_trabajo/kanban.php` |
| Calendario de OT | `OrdenesTrabajo::calendario` → `ordenes_trabajo/calendario.php` |
| Ejecutar trabajo | `OrdenesTrabajo::realizar` → `ordenes_trabajo/realizar.php` |
| Crear solicitud | `Solicitudes::create` → `solicitudes/create.php` |
| Registro de combustible | `RegistroCombustible::create` → `registro_combustible/form.php` |
| Apertura de bomba | `LecturaBomba::apertura` → `lectura_bomba/apertura.php` |
| Notificaciones | `layouts/main.php` → `GMVNotifications` (JS) |
| Permisos de acceso | `access_helper.php` → `tiene_acceso($ruta)` |
| Configuración BD | `Config/Database.php` + `.env` |
| Rutas del sistema | `Config/Routes.php` |
| CSRF | `Config/Security.php` + `layouts/main.php` (override fetch) |
| Catálogos (tipos doc) | `Catalogo::getCatalogosPorCodigo('CAT-0002')` |
| Sincronización SAG | `InvProductosModel`, `InvComprasEModel`, `MovimientosModel` |
| Reportes combustible | `Reportes::combustible` → `reportes/combustible.php` |
| Dashboard | `Dashboard::index` → `dashboard/index.php` + `Dashboard.js` |
| Plan de mejora | `Documentacion/plan_mejora_mantenimiento.md` |
| Cambios de BD | `Documentacion/cambios_bd.md` |
| Análisis TDR | `Documentacion/analisis_tdr_mantenimiento.md` |
