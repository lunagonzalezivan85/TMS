# Avances TMS - GMV (Sistema de Gestión de Mantenimiento de Vehículos)

---

## 1. Reactivación del Proyecto e Instalación de Base de Datos

**Fecha:** 20 de febrero de 2026  
**Objetivo:** Levantar el entorno local con XAMPP y aplicar el esquema de base de datos del sistema GMV.

---

## Incidencias y Correcciones

### INC-001 — Error SQL: `GENERATED ALWAYS AS` con subconsulta
**Archivo:** `database/migrations/v1.0.0_estructura_inicial.sql`  
**Error:**
```
SQL Error [1901] [HY000]: Function or expression 'select ...' cannot be used in the GENERATED ALWAYS AS clause of `costo_total`
```
**Causa:** MySQL no permite subconsultas en columnas generadas.  
**Corrección:** Se eliminó la columna generada y se reemplazó con una columna `DECIMAL(10,2)` normal. Se crearon dos triggers `BEFORE INSERT` y `BEFORE UPDATE` en la tabla `consumo_materiales` para calcular `costo_total` automáticamente.

---

### INC-002 — Error al crear usuario administrador sin detalle
**Archivo:** `app/Controllers/Auth.php`  
**Error:**
```
Error al registrar: Error al crear el usuario administrador
```
**Causa:** El mensaje de error era genérico y no exponía los errores de validación del modelo.  
**Corrección:** Se modificó `processStep2()` para capturar y mostrar los errores específicos de `UsuarioModel::errors()`.

---

### INC-003 — Tabla `roles` vacía al registrar el primer usuario
**Error:** Fallo silencioso al crear el usuario administrador porque no existían roles en la BD.  
**Corrección:** Se agregaron registros iniciales en el script de migración:
- `Administrador` — Acceso completo al sistema
- `Mecánico` — Gestión de mantenimientos y reparaciones
- `Conductor` — Acceso limitado para conductores

---

### INC-004 — Tabla `accesos` no existía
**Error:**
```
Table 'tms.accesos' doesn't exist
```
**Corrección:** Se agregaron las definiciones `CREATE TABLE menu` y `CREATE TABLE accesos` al script de migración, basadas en los modelos `MenuModel` y `AccesoModel`.

---

### INC-005 — ERR_TOO_MANY_REDIRECTS al acceder al dashboard
**Error:**
```
Esta página no funciona. La página localhost te ha redirigido demasiadas veces. ERR_TOO_MANY_REDIRECTS
```
**Causa raíz:** Bug en `AccessFilter` y `SecureController` — el string vacío `''` en la lista de URLs públicas hacía que `strpos($url, '') === 0` retornara `true` para **todas** las URLs, causando un ciclo de redirecciones infinito. Además, la tabla `accesos` estaba vacía, por lo que `hasAccess()` siempre retornaba `false`.  
**Correcciones aplicadas:**
- `app/Filters/AccessFilter.php`: Eliminado `''` y `'/'` de la lista de URLs públicas. Se usa comparación segura con `$publicUrl . '/'`. Se agregó bypass completo para el rol `Administrador`.
- `app/Controllers/SecureController.php`: Mismo fix de `strpos` + skip de entradas vacías + bypass para `Administrador`.
- `app/Config/Routes.php`: La ruta `dashboard` se movió fuera del grupo `access` para usar solo el filtro `auth`.

---

### INC-006 — Tablas `menu` y `accesos` sin datos iniciales
**Causa:** El `AccessFilter` consultaba la tabla `accesos` vacía y denegaba acceso a todo.  
**Corrección:** Se generaron los INSERTs de las 48 entradas del menú basadas en `Config/Menu.php` y los accesos completos para el rol 1 (Administrador).

---

### INC-007 — Error de extensión PHP: `sqlsrv` no cargada
**Error:**
```
The required PHP extension "sqlsrv" is not loaded.
```
**Causa:** `SqlServerBaseModel::__construct()` intentaba conectarse a SQL Server al instanciarse, bloqueando toda la app aunque no se necesitara esa conexión.  
**Correcciones aplicadas:**
- `app/Models/SqlServerBaseModel.php`: La conexión se convirtió a **lazy loading** — ya no se conecta en el constructor, sino solo cuando se llama un método que realmente la necesita.
- Se instalaron los drivers PHP para SQL Server en XAMPP:
  - `php_sqlsrv_82_ts_x64.dll`
  - `php_pdo_sqlsrv_82_ts_x64.dll`
  - Habilitados en `C:\xampp\php\php.ini`
  - Requiere: Microsoft ODBC Driver 17 for SQL Server instalado

---

### INC-008 — Conexión SQL Server remota fallando ✅ RESUELTO
**Error original:**
```
Unable to connect to the database. [SQLSRV]: Login failed / TCP connection refused (code: 10061)
```
**Causas identificadas:**
1. `TrustServerCertificate = false` — el driver ODBC rechazaba el certificado del servidor antes de autenticar.
2. Las claves `.env` con puntos (`database.sqlserver.hostname`) **no funcionan en Windows** porque `putenv()` no soporta puntos en el nombre de la clave, por lo que `env()` retornaba `null`.

**Correcciones aplicadas:**
- `app/Config/Database.php`: `TrustServerCertificate = true`. Constructor lee credenciales vía `env('SQLSRV_*')` (claves sin puntos).
- `.env`: Variables `SQLSRV_HOSTNAME`, `SQLSRV_USERNAME`, `SQLSRV_PASSWORD`, `SQLSRV_DATABASE`, `SQLSRV_PORT`.
- `env` (template): Documentado el nuevo formato `SQLSRV_*`.

---

### INC-009 — CSRF bloqueaba todos los POST AJAX (403 Forbidden) ✅ RESUELTO
**Error:**
```
CRITICAL — CodeIgniter\Security\Exceptions\SecurityException: The action you requested is not allowed.
[Method: POST, Route: vehiculos/getData]
```
**Causa:** 31 vistas usan `fetch()` nativo (no jQuery). El `$.ajaxSetup` solo aplica a jQuery. El token CSRF nunca se enviaba en el body de los POST.

**Correcciones aplicadas:**
- `app/Config/Security.php`: `$regenerate = false` (token estable por sesión).
- `app/Views/layouts/main.php`: Override de `window.fetch` global para inyectar el token en todos los POST (`URLSearchParams`, `FormData`, `string`). También `$(document).ajaxSend` para jQuery AJAX.
- `app/Views/vehiculos/index.php`: Token incluido directamente en el objeto `filtros`.

---

### INC-010 — Página `vehiculos/create` cargaba lento (~15 segundos) ✅ RESUELTO
**Causa:** Al fallar la conexión SQL Server, el driver SQLSRV esperaba el timeout por defecto (15 s) antes de ceder al fallback.

**Corrección:**
- `app/Models/VehiculoModel::getCentrosCosto()`: `\sqlsrv_configure('LoginTimeout', 3)` antes de conectar. Máximo 3 s de espera antes de fallar.
- Se eliminó el fallback con centros de costo ficticios (`CC001–CC010`). Si SQL Server no responde, el select queda vacío.

---

## Tablas agregadas al script de migración

| Tabla | Descripción |
|-------|-------------|
| `menu` | Menú de navegación del sistema |
| `accesos` | Permisos por rol sobre cada ítem de menú |
| `documentacion_conductor` | Documentos y licencias de conductores |
| `registro_combustible` | Registro de consumo y ventas de combustible |

---

## Estado actual

- Base de datos `tms` creada y esquema aplicado ✅
- Roles iniciales insertados ✅
- Menú y accesos para Administrador insertados ✅
- Login y acceso al dashboard funcionando ✅
- Conexión SQL Server remota (`15.235.109.18`) funcionando ✅
- Centros de costo cargando desde SQL Server en `vehiculos/create` ✅
- CSRF resuelto para todos los POST AJAX (fetch + jQuery) ✅

---

### INC-011 — Refactorización arquitectura módulo Vehículos ✅ RESUELTO
**Objetivo:** Mejorar mantenibilidad, separar responsabilidades, eliminar código de debug.

**Cambios aplicados:**

**Backend (PHP):**
- `app/Services/VehiculoService.php` ✅ **NUEVO** — capa de negocio extraída del controller:
  - `getParaVista()`, `crear()`, `actualizar()`, `cambiarEstado()`, `eliminar()`
  - `getCentrosCostoParaSelect()`, `getCentroCosto()`, `getConductoresDisponibles()`, `getHistorialEstados()`
- `app/Helpers/vehiculo_helper.php` ✅ **NUEVO** — badge logic reutilizable:
  - `vehiculo_estado_badge($estado)` → `<span class="badge ...">` completo
  - `vehiculo_estado_class($estado)` → clase Bootstrap (success / secondary / warning)
  - `vehiculo_estado_label($estado)` → texto legible (Activo / Inactivo / En Reparación)
- `app/Controllers/Vehiculos.php` ✅ REFACTORIZADO:
  - `VehiculoService` inyectado en constructor
  - `show()`, `create()`, `store()`, `edit()`, `update()`, `delete()`, `cambiarEstado()` → usan el service
  - `debugSqlServer()` y `testSqlServerSimple()` vaciados (→ redirect a vehiculos)
  - Todos los `redirect()->back()` reemplazados por URLs explícitas

**Frontend (JS):**
- `public/assets/js/modules/Vehiculos.js` ✅ **NUEVO** — módulo JS para vistas de vehículos:
  - `VehiculosShow.init()` → estado, solicitud mantenimiento, estadísticas
  - `VehiculosIndex.init()` → filtros y carga de datos
  - Usa `HttpClient` (CSRF automático) y `UI.showToast()` en lugar de alerts inline

**Vistas:**
- `app/Views/vehiculos/show.php` ✅ REFACTORIZADO:
  - 140 líneas de `<script>` embebido → 3 líneas invocando `VehiculosShow.init()`
  - Arrays `$estadoClass`/`$estadoTexto` inline → `vehiculo_estado_class()` / `vehiculo_estado_label()`

---

## Pendiente (plan_trabajo_modernizacion.md)

- [ ] Estandarización módulo **Vehículos**: migrar `vehiculos/index.php` a Vanilla JS externo (`Vehiculos.js`)
- [ ] Utilidad **FormValidator.js**: estandarizar validación de formularios
- [ ] Módulo **Mantenimientos**: selector de repuestos/servicios vía AJAX
- [ ] Módulo **Documentación**: gestión de archivos sin jQuery
- [ ] Aplicar `helper('vehiculo')` en `vehiculos/index.php` y `vehiculos/edit.php` (usan badge logic inline)
