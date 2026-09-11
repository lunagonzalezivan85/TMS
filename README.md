# TMS — Transportation Management System (GCM Transportes)

> **Framework:** CodeIgniter 4 (PHP)  
> **BD:** SQL Server (producción) / MySQL (desarrollo)  
> **Ubicación:** `C:\xampp\htdocs\GMV\`  
> **Repo:** https://github.com/lunagonzalezivan85/TMS.git  
> **Rama activa:** `actualizacion-vehiculos`

---

## ⚠️ INSTRUCCIONES OBLIGATORIAS PARA IA / DESARROLLADORES

> **ANTES de escribir cualquier código, DEBES leer estos archivos en este orden:**

### 1. Lectura obligatoria (en orden)
| # | Archivo | Por qué leerlo |
|---|---------|----------------|
| 1 | `Documentacion/NEURONA.md` | **Mapa neuronal del sistema.** Relaciones Vista→Controlador→Service→Model. Índice rápido "¿Dónde está...?". Si no lees esto, vas a perder tiempo buscando archivos. |
| 2 | `Documentacion/plan_mejora_mantenimiento.md` | **Plan de trabajo activo.** Fases, prioridades, qué tocar y qué no. Define el orden de desarrollo. |
| 3 | `Documentacion/cambios_bd.md` | **Registro de cambios en BD.** Toda modificación a la base de datos DEBE registrarse aquí. Scripts SQL planificados y ejecutados. |
| 4 | `Documentacion/analisis_tdr_mantenimiento.md` | **Análisis del TDR v1.1.** Brechas detectadas, lo que existe y lo que falta. Contexto del negocio. |
| 5 | `CHANGELOG.md` | **Registro de todos los cambios.** TODO cambio de código DEBE registrarse aquí con fecha, tipo y descripción. |
| 6 | `app/Config/Routes.php` | **Todas las rutas del sistema.** 708 líneas. Antes de crear una ruta nueva, verifica que no exista. |
| 7 | `app/Views/layouts/main.php` | **Layout principal.** CSRF override, sistema de notificaciones, sidebar. Cualquier vista nueva extiende este layout. |

### 2. Reglas obligatorias
- **NO inspeccionar el proyecto a ciegas.** Usa el NEURONA.md para encontrar archivos.
- **NO crear archivos sueltos.** Sigue la estructura de carpetas existente (ver sección "Convenciones" abajo).
- **NO modificar la BD sin registrar el cambio** en `Documentacion/cambios_bd.md`.
- **NO romper el CSRF.** El sistema inyecta el token automáticamente via `window.fetch` override y `$(document).ajaxSend`. Cualquier POST con fetch o jQuery ya lo tiene. Si usas otro método, incluye el token manualmente.
- **NO usar `putenv()` con claves con puntos en Windows.** Usar `SQLSRV_*` (sin puntos) en `.env`.
- **TODO cambio de código DEBE registrarse** en `CHANGELOG.md` con fecha, tipo (ADDED/CHANGED/FIXED/REMOVED/DOCUMENTATION/BD) y descripción.
- **TODO cambio de código DEBE documentarse** en el archivo correspondiente de `Documentacion/`.
- **TODO nueva tabla o ALTER TABLE DEBE** registrarse en `Documentacion/cambios_bd.md` con fecha, descripción y script SQL.
- **TODO nuevo módulo DEBE** agregarse al NEURONA.md con sus relaciones V-C-S-M.
- **Usar helpers existentes** (`vehiculo_helper`, `access_helper`, `date_helper`) antes de crear nuevos.
- **Usar services existentes** para lógica de negocio. Los controladores solo orquestan.
- **Los modelos NO contienen lógica de negocio.** Solo queries y acceso a datos.

### 3. Flujo de trabajo recomendado
```
1. Leer NEURONA.md → encontrar el módulo y archivos relacionados
2. Leer plan_mejora_mantenimiento.md → identificar la fase y tarea
3. Leer el controlador/modelo/vista correspondiente
4. Hacer el cambio
5. Registrar el cambio en CHANGELOG.md
6. Documentar (cambios_bd.md si toca BD, NEURONA.md si es módulo nuevo)
7. Commit en la rama actualizacion-vehiculos
```

---

## Estructura del proyecto

```
GMV/
├── app/
│   ├── Config/           # Configuración (Routes, Database, Filters, Security, Menu)
│   ├── Controllers/      # 37 controladores
│   ├── Database/
│   │   ├── Migrations/   # 9 migraciones
│   │   └── Seeds/        # 5 seeders
│   ├── Filters/          # AccessFilter, AdminFilter, AuthFilter
│   ├── Helpers/          # vehiculo_helper, access_helper, date_helper, EmailHelper
│   ├── Models/           # 41 modelos
│   ├── Services/         # 4 services (VehiculoService, RegistroCombustibleService, etc.)
│   └── Views/           # 33 carpetas de vistas, 160+ archivos
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   │   ├── core/     # HttpClient.js, UI.js
│   │   │   └── modules/  # Dashboard.js, Portal.js, RegistroCombustible.js, Vehiculos.js
│   │   ├── img/
│   │   └── audio/
│   └── uploads/         # Documentos subidos
├── Documentacion/       # TDR, análisis, planes, cambios BD, NEURONA
├── writable/            # Cache, logs, sessions, uploads
├── vendor/              # Dependencias Composer
├── .env                 # Credenciales (NO subir al git)
├── .gitignore
└── composer.json
```

---

## Módulos del sistema

| Módulo | Controlador | Ruta base | Descripción |
|--------|-------------|-----------|-------------|
| **Auth** | `Auth.php` | `/login` | Autenticación, registro wizard |
| **Dashboard** | `Dashboard.php` | `/dashboard` | Panel principal con widgets |
| **Vehículos** | `Vehiculos.php` | `/vehiculos` | CRUD, documentos, reportes, alertas |
| **Órdenes de Trabajo** | `OrdenesTrabajo.php` | `/ordenes-trabajo` | OT, Kanban, calendario, realizar |
| **Solicitudes** | `Solicitudes.php` | `/solicitudes` | Solicitudes de mantenimiento |
| **Conductores** | `Conductores.php` | `/conductores` | CRUD, documentos, sincronización ERP |
| **Registro Combustible** | `RegistroCombustible.php` | `/registro-combustible` | Consumo, ventas, vouchers, SAG |
| **Lectura de Bomba** | `LecturaBomba.php` | `/lectura-bomba` | Apertura/cierre de bomba |
| **Materiales** | `Materiales.php` | `/materiales` | CRUD, sincronización SQL Server |
| **Movimientos** | `Movimientos.php` | `/movimientos` | Movimientos de inventario SAG |
| **Catálogo** | `Catalogo.php` | `/catalogo` | Catálogos genéricos (CAT-0002, etc.) |
| **Asignación Vehículos** | `AsignacionVehiculos.php` | `/asignacion-vehiculos` | Wizard de asignación |
| **Reportes** | `Reportes.php` | `/reportes` | Combustible y vehículos |
| **Roles** | `Rol.php`, `Roles.php` | `/rol`, `/admin/roles` | Gestión de roles |
| **Menú** | `Menu.php` | `/menu` | Construcción del menú |
| **Accesos** | `Acceso.php` | `/acceso` | Permisos rol-menú |
| **Usuarios** | `Usuarios.php` | `/usuarios` | CRUD usuarios |
| **Configuración** | `Configuracion.php` | `/configuracion` | Configuración del sistema |
| **Tipos de Problema** | `TiposProblema.php` | `/tipos-problema` | Catálogo de problemas |
| **Tipo Operación** | `TipoOperacion.php` | `/tipo-operacion` | Tipos de operación |
| **Tipo Unidad** | `TipoUnidad.php` | `/tipo-unidad` | Tipos de unidad |
| **Tipo Motivo Combustible** | `TipoMotivoCombustible.php` | `/tipo-motivo-combustible` | Motivos de consumo |
| **Direcciones** | `Direccion.php` | `/direcciones` | CRUD, mapa, geocodificación |
| **Historial OT** | `HistorialOrdenTrabajo.php` | `/historial-orden-trabajo` | Historial de solicitudes |
| **Portal Conductores** | `PortalConductores.php` | `/portal` | Portal público de conductores |
| **Cotizador** | `Cotizador.php` | `/cotizador` | Cotizaciones de transporte |
| **Mantenimiento Tablas** | `MantenimientoTablas.php` | `/mantenimiento-tablas` | Visor de tablas SQL Server |
| **Widget Config** | `WidgetConfig.php` | `/widget-config` | Configuración de widgets del dashboard |

---

## Configuración clave

### Base de datos (SQL Server)
- `app/Config/Database.php`: Array `$sqlserver` con `TrustServerCertificate = true`
- `.env`: Variables `SQLSRV_HOSTNAME`, `SQLSRV_USERNAME`, `SQLSRV_PASSWORD`, `SQLSRV_DATABASE`, `SQLSRV_PORT`
- **Windows:** `putenv()` no soporta claves con puntos, por eso se usan `SQLSRV_*` (sin puntos)

### CSRF
- `app/Config/Security.php`: `$regenerate = false`
- `app/Views/layouts/main.php`: Override de `window.fetch` + `$(document).ajaxSend` para inyectar token automáticamente en todos los POST

### Filtros
- `auth`: Verifica sesión activa
- `access`: Verifica permisos de acceso al menú/ruta
- `admin`: Solo administradores

### Layouts
- `layouts/main.php`: Layout principal (incluye sidebar, navbar, notificaciones, CSRF override)
- `layouts/login.php`: Layout para login/registro
- `layouts/portal.php`: Layout para portal de conductores
- `layouts/sidebar.php`: Sidebar del menú

---

## Documentación

| Archivo | Descripción |
|---------|-------------|
| `Documentacion/analisis_tdr_mantenimiento.md` | Análisis del TDR v1.1 vs sistema actual |
| `Documentacion/plan_mejora_mantenimiento.md` | Plan de mejora de fácil a difícil |
| `Documentacion/cambios_bd.md` | Registro de cambios en base de datos |
| `Documentacion/NEURONA.md` | Mapa neuronal del sistema (relaciones V-C-S-M) |

---

## Comandos útiles

```bash
# Git
git checkout actualizacion-vehiculos
git add -A && git commit -m "mensaje"
git push origin actualizacion-vehiculos

# Composer (PHP en XAMPP)
C:\xampp\php\php.exe C:\xampp\php\composer.phar install
C:\xampp\php\php.exe C:\xampp\php\composer.phar require paquete/nombre

# Ejecutar PHP
C:\xampp\php\php.exe spark migrate
C:\xampp\php\php.exe spark serve
```

---

## Convenciones

- **Controladores:** PascalCase (`Vehiculos.php`, `OrdenesTrabajo.php`)
- **Modelos:** PascalCase + `Model` (`VehiculoModel.php`, `SolicitudModel.php`)
- **Vistas:** snake_case en carpetas (`vehiculos/`, `ordenes_trabajo/`)
- **Services:** PascalCase + `Service` (`VehiculoService.php`)
- **Helpers:** snake_case + `_helper` (`vehiculo_helper.php`)
- **JS modules:** PascalCase (`Vehiculos.js`, `Dashboard.js`)
- **Rutas:** kebab-case (`registro-combustible`, `ordenes-trabajo`)
- **BD (SQL Server):** snake_case (`documentos_vehiculos`, `solicitudes_historial`)
