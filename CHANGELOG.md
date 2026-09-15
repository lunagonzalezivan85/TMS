# Changelog — TMS GCM Transportes

> **Formato:** [fecha] - [tipo] - descripción  
> **Tipos:** ADDED, CHANGED, FIXED, REMOVED, DOCUMENTATION, BD  
> **Rama:** `actualizacion-vehiculos`

---

## Registro de cambios

### 2026-09-15

- **DOCUMENTATION** - Creado `Documentacion/plan_solicitud_mantenimiento_wizard.md` — Plan de proyecto para wizard de solicitud de mantenimiento de vehículos
- **DOCUMENTATION** - Creado `Documentacion/cambios_bd_solicitud_mantenimiento.md` — Registro detallado de cambios en BD para el wizard
- **ADDED** - `app/Controllers/Solicitudes::create()` — Wizard de 4 pasos para crear solicitudes de mantenimiento de vehículos
- **ADDED** - `app/Controllers/Solicitudes::store()` — Guardar solicitud desde wizard con validaciones y carga de evidencia
- **ADDED** - `app/Controllers/Solicitudes::show()` — Vista de resumen de solicitud creada con pasos del flujo
- **ADDED** - `app/Controllers/Solicitudes::buscarVehiculo()` — Búsqueda AJAX de vehículos por placa, motor o carnet de conductor
- **ADDED** - `app/Views/solicitudes/create.php` — Wizard reformulado con diseño One UI / Bento Grid
- **ADDED** - `app/Views/solicitudes/show.php` — Vista de confirmación/resumen del flujo de solicitud
- **CHANGED** - `app/Models/SolicitudModel.php` — Campos y validaciones para `tipo_mantenimiento`, `ubicacion` y `condicion_movilidad`
- **CHANGED** - `app/Config/Routes.php` — Rutas `solicitudes/buscar-vehiculo` y `solicitudes/crear` apuntan al wizard
- **BD** - `solicitudes` — Agregadas columnas `tipo_mantenimiento`, `ubicacion`, `condicion_movilidad`
- **BD** - `vehiculos` — Agregada columna `tipo_consumo` para evitar error 500 en dashboard
- **BD** - Base de datos `tms` recreada en ambiente local desde dump estructural `database/conductores_tables.sql`

### 2026-09-10

- **DOCUMENTATION** - Creado `Documentacion/analisis_tdr_mantenimiento.md` — Análisis del TDR v1.1 vs sistema actual
- **DOCUMENTATION** - Creado `Documentacion/plan_mejora_mantenimiento.md` — Plan de mejora de fácil a difícil (6 fases)
- **DOCUMENTATION** - Creado `Documentacion/cambios_bd.md` — Registro de cambios en base de datos
- **DOCUMENTATION** - Creado `Documentacion/NEURONA.md` — Mapa neuronal del sistema (relaciones V-C-S-M)
- **DOCUMENTATION** - Actualizado `README.md` — Instrucciones obligatorias para IA, estructura del proyecto, módulos, configuración
- **DOCUMENTATION** - Creado `CHANGELOG.md` — Este archivo
- **CHANGED** - `app/Views/vehiculos/documentos.php` — Hero banner compactado: layout horizontal, botones btn-sm, padding reducido
- **ADDED** - `app/Controllers/Vehiculos::getAlertasDocumentos()` — Endpoint AJAX para alertas de documentos por vencer en todos los vehículos
- **ADDED** - `app/Views/vehiculos/documentos.php` — Sección de alertas de vencimiento con badges de color
- **ADDED** - `app/Views/layouts/main.php` — `GMVNotifications` API + fetch automático de alertas de documentos al iniciar sesión
- **ADDED** - `app/Config/Routes.php` (línea 87) — Ruta `getAlertasDocumentos`
- **CHANGED** - `app/Views/vehiculos/partials/documentos_cards.php` — Rediseño Bento Grid / One UI con accent bars, iconos y badges por estado
- **CHANGED** - `app/Controllers/Vehiculos::documentos()` — Cálculo de alertas basado en campo `notificacion` (días de antelación)
- **BD** - Sin cambios en BD esta sesión (todas las tablas planificadas en `cambios_bd.md`)
- **ADDED** - `app/Views/layouts/login.php` — Video de fondo `public/assets/videos/video01.mp4` en columna izquierda del login con overlay verde semitransparente para legibilidad

---

## Convención para futuros cambios

```
### YYYY-MM-DD

- **TIPO** - `archivo/ruta` — Descripción breve del cambio
- **TIPO** - `archivo/ruta` — Otro cambio
- **BD** - `tabla` — Descripción del cambio en base de datos (registrar también en cambios_bd.md)
```

**Tipos válidos:**
- `ADDED` — Nueva funcionalidad o archivo
- `CHANGED` — Modificación de algo existente
- `FIXED` — Corrección de bug
- `REMOVED` — Eliminación de código/archivo
- `DOCUMENTATION` — Cambio en documentación
- `BD` — Cambio en base de datos
