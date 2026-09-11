# Plan de Modernización y Optimización GMV TMS

Este documento detalla la estrategia para escalar la nueva arquitectura, acelerar el desarrollo y limpiar el repositorio de elementos innecesarios.

## 1. Plan de Trabajo y Alcances (Corto Plazo)

El objetivo es migrar los módulos de mayor impacto visual y funcional hacia la arquitectura **Vanilla JS + HttpClient**.

### Módulos Prioritarios
| Módulo | Alcance | Razonamiento |
| :--- | :--- | :--- |
| **Vehículos** | Refactorizar listado y edición con modales dinámicos. | Es el núcleo del sistema y tiene mucha interacción. |
| **Mantenimientos** | Implementar selector de repuestos y servicios vía AJAX. | Requiere lógica compleja que hoy depende de recargas de página. |
| **Documentación** | Gestión de archivos (carga y visualización) sin jQuery. | Crítico para la operatividad y alertas actuales. |

### Hitos para Mañana
- [ ] **Estandarización de Vehículos**: Migrar [vehiculos/index.php](file:///c:/xampp/htdocs/GMV/app/Views/vehiculos/index.php) al nuevo `Layout/Main`.
- [ ] **Validación de Formularios**: Crear una utilidad `FormValidator.js` para estandarizar errores.
- [ ] **Optimización de Filtros**: Implementar filtrado dinámico en tablas sin refrescar la página.

---

## 2. Ruta para Desarrollo Ágil (Fast Development)

Para desarrollar más rápido y con menos errores, implementaremos las siguientes estrategias:

### A. Biblioteca de Componentes UI
Seguir enriqueciendo [UI.js](file:///c:/xampp/htdocs/GMV/public/assets/js/core/UI.js) con métodos estandarizados:
- `UI.renderTable(data)`: Para generar tablas dinámicas rápidamente.
- `UI.confirmDelete(id, callback)`: Wrapper de SweetAlert2 para acciones de borrado.
- `UI.formLoading(selector)`: Bloqueo visual de formularios durante el guardado.

### B. Plantillas de Código (Snippets)
Crear un estándar de módulo JS para que cada nueva vista tome menos de 5 minutos en configurarse:
```javascript
const ModuleName = {
    init() { /* bind events */ },
    async loadData() { /* HttpClient calls */ }
};
document.addEventListener('DOMContentLoaded', () => ModuleName.init());
```

### C. Backend API First
Los controladores de CodeIgniter deben empezar a retornar solo JSON para las peticiones AJAX, separando completamente la lógica de datos de la de presentación.

---

## 3. Estrategia de Limpieza (Cleanup)

El proyecto contiene archivos temporales y fragmentos de código que deben eliminarse para mejorar la mantenibilidad.

### Archivos a Eliminar
- [ ] **Scripts de Debug en Raíz**: Archivos como `debug_*.php`, `test_*.php`, `verify_*.php` en la carpeta raíz deben moverse a una carpeta `/tools` o eliminarse si ya no se usan.
- [ ] **Librerías Legadas**: Eliminar archivos `.js` de terceros que estén en `public/assets/js` y que ya se carguen por CDN o que hayan sido reemplazados por HttpClient.
- [ ] **Código Embebido**: Una vez migrado un módulo a su respectivo archivo `.js`, se debe borrar todo rastro de `<script>` dentro del archivo `.php`.

### Documentación Necesaria
Solo se mantendrá:
1. **README.md**: Guía de instalación y configuración de entorno.
2. **Arquitectura_JS.md**: Explicación de HttpClient y UI.js para nuevos desarrolladores.
3. **Avances_TMS.md**: Histórico de cambios y hoja de ruta.

---

> [!TIP]
> **Recomendación**: Empezar mañana con el módulo de **Vehículos** permitirá validar la robustez de la nueva barra lateral y el navbar con datos reales y filtrado AJAX.

---

## 5. Seguridad de endpoints AJAX — Rate Limiting ⏳ Pendiente

> Objetivo: limitar cuántas veces por minuto una sesión puede llamar a endpoints como `/vehiculos/getData`. Protege contra scraping y uso abusivo de la API interna.

### 5.1 Implementación con CI4 Throttler

CodeIgniter 4 incluye `\CodeIgniter\Throttle\Throttler` (basado en Token Bucket).

**Crear filtro `app/Filters/RateLimitFilter.php`:**
```php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Throttle\Throttler;
use Config\Services;

class RateLimitFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();

        // 60 peticiones por minuto por IP + sesión
        $key = md5($request->getIPAddress() . session()->get('user_id'));

        if ($throttler->check($key, 60, MINUTE) === false) {
            return Services::response()
                ->setStatusCode(429)
                ->setJSON(['error' => 'Demasiadas peticiones. Espera un momento.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
```

**Registrar en `app/Config/Filters.php`:**
```php
'aliases' => [
    // ...
    'ratelimit' => \App\Filters\RateLimitFilter::class,
],

'filters' => [
    'ratelimit' => ['before' => ['vehiculos/getData', 'vehiculos/getEstadisticas']],
],
```

### 5.2 Lo que ya protege sin Rate Limiting

| Protección | Estado |
|-----------|--------|
| CSRF token en todos los POST | ✅ Activo |
| `isAJAX()` en todos los endpoints | ✅ Activo |
| Validación de sesión por request | ✅ Activo |
| `empresa_id` aislado por sesión | ✅ Activo |
| Rate Limiting (60 req/min) | ⏳ Pendiente |

---

## 6. Configuración HTTP para Intranet (sin certificado SSL)

> Solución definitiva para empresas sin acceso a internet ni certificados SSL comprados.

### 6.1 Qué causa el problema

El navegador (Chrome/Edge) guarda una política **HSTS** (HTTP Strict Transport Security) que fuerza HTTPS si el sitio alguna vez se visitó por HTTPS. Esto es independiente del servidor.

### 6.2 Solución permanente

**Paso 1 — `.htaccess` del proyecto** (ya aplicado):
```apache
<IfModule mod_headers.c>
    Header always set Strict-Transport-Security "max-age=0"
</IfModule>
```
Esto envía `max-age=0` que **borra** el HSTS guardado en cada visita.

**Paso 2 — Limpiar HSTS en el navegador** (una sola vez por PC):
- Chrome/Edge: `chrome://net-internals/#hsts` → ingresar el hostname → **Delete**
- Firefox: borrar cookies y datos del sitio

**Paso 3 — Desactivar HTTPS-First** en Chrome:
- `chrome://settings/security` → desmarcar **"Usar siempre conexiones seguras"**

**Paso 4 — CI4** (ya configurado):
```php
// App.php
public bool $forceGlobalSecureRequests = false;
// Cookie.php
public bool $secure = false;
// Filters.php — forcehttps comentado
```

### 6.3 Si en el futuro quieren HTTPS sin comprar certificado

Usar **mkcert** — genera certificados de confianza local sin internet:
1. Instalar `mkcert` en el servidor XAMPP
2. `mkcert -install` → instala la CA local en el sistema
3. `mkcert gmv.empresa.local 192.168.1.10` → genera el cert
4. Configurar Apache para usarlo
5. Exportar e instalar la CA en cada PC cliente (una sola vez por IT)

---

## 4. Estándar de Vistas Index (basado en `vehiculos/index.php`)

> Toda vista de listado nueva o refactorizada **debe seguir esta estructura**.
> Referencia canónica: `app/Views/vehiculos/index.php` + `public/assets/js/modules/Vehiculos.js`

---

### 4.0 Reglas de diseño visual (obligatorias)

Estas reglas aplican a **toda** vista index. No son sugerencias.

**Cards y sombras**
- Todas las cards: `border-0 shadow-sm` — sin borde, sombra suave. Nunca `shadow` o `shadow-lg`.
- Card principal de la tabla: sin padding en `card-body` → `card-body p-0` para que la tabla ocupe todo el ancho.

**Colores de estado** — siempre el mismo mapa en PHP y JS:

| Estado | Clase Bootstrap | Uso |
|--------|----------------|-----|
| ACTIVO | `bg-success` | Verde |
| INACTIVO | `bg-secondary` | Gris |
| EN REPARACION | `bg-warning text-dark` | Ámbar |
| PENDIENTE | `bg-info text-dark` | Azul claro |
| COMPLETADO | `bg-success` | Verde |
| CANCELADO | `bg-danger` | Rojo |

**Tabla**
- `table-hover align-middle` siempre. Sin `table-bordered`.
- `thead`: `table-light` con columnas en `small text-uppercase text-muted fw-semibold`.
- Primera columna: `ps-4` (padding-start) para aire. Última columna (acciones): `text-end pe-4`.
- Filas de identificación principal (nombre, placa): badge oscuro + texto secundario debajo en `<small class="text-muted">`.
- Nunca HTML generado por PHP en campos de datos — solo en `acciones`. El JS controla el render.

**Badges de estado en la tabla**
- Siempre `rounded-pill px-3 py-2` para badges de estado.
- Nunca texto uppercase en el badge — "Activo", no "ACTIVO".

**Skeleton loading**
- 6 filas siempre. Cells con `<div class="bg-light rounded">` de altura `13px`.
- Primera celda incluye el avatar cuadrado de `40×40px` para simular la estructura real.

**Acciones**
- Nunca `btn-group` con múltiples botones. Siempre dropdown `⋮` (ver sección 4.4).
- Columna de acciones: ancho mínimo, sin etiqueta en el `<th>` o solo "Acciones".

**Header de la vista**
- Título: `h4 fw-bold mb-0`. Sin `text-gray-800` (clase SB Admin 2 legada).
- Subtítulo: `<p class="text-muted mb-0 small">` descripción en una línea.
- Botones: `btn-sm`. El botón principal en `btn-primary`, secundarios en `btn-outline-secondary`.

**Footer de la tabla**
- `bg-light` con `d-flex justify-content-between` → contador a la izquierda, timestamp a la derecha.
- Contador: `"Mostrando X de Y registros"`. Timestamp: `"Actualizado HH:MM:SS"`.

---

### 4.1 Estructura HTML de la vista

```
layouts/main
└── section('content')
    ├── Header (h4 + descripción + botones)
    ├── Métricas (row de 4 cards — col-6 col-xl-3)
    └── Card principal (border-0 shadow-sm)
        ├── card-header  → Toolbar (filtros + btn Filtrar)
        ├── card-body p-0
        │   └── table.table-hover.align-middle
        │       ├── thead.table-light  (columnas en small uppercase)
        │       └── tbody#[modulo]Body (skeleton PHP inicial)
        └── footer bg-light  → contador + timestamp
└── Modal cambiar estado (modal-dialog-centered, border-0 shadow)
└── section('scripts')
    └── <script src="modules/[Modulo].js">
    └── <script> [Modulo]Index.init('<?= base_url() ?>') </script>
```

**Reglas de nomenclatura:**
- `tbody` id: `[modulo]Body` (ej. `vehiculosBody`, `mantenimientosBody`)
- Stats ids: `stat_total`, `stat_[estado1]`, `stat_[estado2]`, `stat_[extra]`
- Filtros ids: `filtro_buscar`, `filtro_estado` (+ cualquier filtro extra)
- Footer ids: `tabla_info`, `tabla_last_update`

---

### 4.2 Cards de métricas

```html
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle bg-[color] bg-opacity-10 d-flex align-items-center
                     justify-content-center" style="width:48px;height:48px;flex-shrink:0">
                    <i class="fas fa-[icon] text-[color]"></i>
                </div>
                <div>
                    <div class="h4 fw-bold mb-0 lh-1" id="stat_[nombre]">—</div>
                    <small class="text-muted">Etiqueta</small>
                </div>
            </div>
        </div>
    </div>
    <!-- repetir x4 -->
</div>
```

Paleta de colores por módulo:
| Métrica | Color | Ícono |
|---------|-------|-------|
| Total   | `primary` | `fa-[entidad]` |
| Activos / OK | `success` | `fa-check-circle` |
| En proceso / Alerta | `warning` | `fa-tools` / `fa-clock` |
| Sin asignar / Error | `secondary` / `danger` | `fa-user-slash` / `fa-times-circle` |

---

### 4.3 Módulo JS — estructura base

Cada módulo sigue el patrón objeto literal con métodos privados prefijados `_`:

```javascript
const [Modulo]Index = {
    baseUrl: '',

    init(baseUrl) {
        this.baseUrl = baseUrl;
        this._bindFiltros();
        this._bindAcciones();     // cambiar estado, eliminar, etc.
        this.cargarDatos();
        this.cargarEstadisticas();
    },

    _bindFiltros() {
        document.getElementById('filtro_buscar')
            ?.addEventListener('input', this._debounce(() => this.cargarDatos(), 450));
        document.getElementById('filtro_estado')
            ?.addEventListener('change', () => this.cargarDatos());
        document.getElementById('btn_filtrar')
            ?.addEventListener('click', () => this.cargarDatos());
    },

    async cargarDatos() {
        const tbody = document.getElementById('[modulo]Body');
        if (!tbody) return;
        this._renderSkeleton(tbody);

        const filtros = {
            draw: 1, start: 0, length: 100,
            'search[value]': document.getElementById('filtro_buscar')?.value ?? '',
            estado:           document.getElementById('filtro_estado')?.value  ?? '',
        };

        try {
            const data = await HttpClient.post(this.baseUrl + '[modulo]/getData', filtros);
            if (!data.data?.length) { this._renderVacio(tbody); return; }
            tbody.innerHTML = data.data.map(item => this._renderFila(item)).join('');
            this._setInfo(data.data.length, data.recordsTotal);
        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>${err.message}</td></tr>`;
        }
    },

    async cargarEstadisticas() {
        try {
            const data = await HttpClient.get(this.baseUrl + '[modulo]/getEstadisticas');
            const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val ?? 0; };
            set('stat_total',    data.total);
            set('stat_activos',  data.por_estado?.['ACTIVO']);
            // ... más stats según módulo
        } catch { /* silencioso */ }
    },

    _renderFila(item) {
        return `<tr>
            <td class="ps-4">...</td>
            ...
        </tr>`;
    },

    _renderVacio(tbody) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5">
            <i class="fas fa-[icon] fa-3x text-muted mb-3 d-block opacity-25"></i>
            <p class="text-muted mb-0">No se encontraron registros</p>
            <small class="text-muted">Prueba con otros filtros</small>
        </td></tr>`;
        this._setInfo(0, 0);
    },

    _renderSkeleton(tbody, cols = 6) {
        const cel = () => `<td><div class="bg-light rounded" style="height:13px"></div></td>`;
        const row = () => `<tr>${Array(cols).fill(cel()).join('')}</tr>`;
        tbody.innerHTML = Array(6).fill(row()).join('');
    },

    _setInfo(shown, total) {
        const el = document.getElementById('tabla_info');
        if (el) el.textContent = `Mostrando ${shown} de ${total} registros`;
        const ts = document.getElementById('tabla_last_update');
        if (ts) ts.textContent = `Actualizado ${new Date().toLocaleTimeString()}`;
    },

    _debounce(fn, ms) {
        let t;
        return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
    },
};

window.[Modulo]Index = [Modulo]Index;
```

---

### 4.4 Columna de Acciones — Dropdown estándar

La columna de acciones **nunca** usa `btn-group` con múltiples botones visibles. Siempre un botón `⋮` que despliega un `dropdown-menu`:

```php
// En generarAcciones($item) del controller
$items = '';

// Acciones de navegación
$items .= '<li><a class="dropdown-item" href="...">
               <i class="fas fa-eye text-info me-2"></i>Ver detalle
           </a></li>';
$items .= '<li><a class="dropdown-item" href="...">
               <i class="fas fa-edit text-primary me-2"></i>Editar
           </a></li>';

// Separador antes de acciones destructivas / de estado
$items .= '<li><hr class="dropdown-divider"></li>';

// Acción de estado (botón, no link)
$items .= '<li><button class="dropdown-item cambiar-estado"
               data-id="' . $id . '" data-estado="INACTIVO">
               <i class="fas fa-pause-circle text-warning me-2"></i>Inactivar
           </button></li>';

// HTML del dropdown
return '
<div class="dropdown">
    <button class="btn btn-sm btn-outline-secondary"
            data-bs-toggle="dropdown" aria-expanded="false"
            style="width:32px;padding:0;line-height:30px;border-radius:6px;">
        <i class="fas fa-ellipsis-v"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">' . $items . '</ul>
</div>';
```

**Reglas:**
- El botón trigger mide exactamente `32×32px`, sin caret (Bootstrap no requiere clase `dropdown-toggle` para funcionar — `data-bs-toggle` es suficiente)
- `dropdown-menu-end` alinea el menú a la derecha para las últimas columnas
- Ícono coloreado + texto descriptivo en cada item
- `<hr class="dropdown-divider">` separa acciones destructivas / de estado de las de navegación
- Acciones de estado van como `<button>`, no `<a>`, con clases `cambiar-estado` para event delegation

---

### 4.5 Respuesta JSON del controller (`getData`)

Todo `getData()` de módulo debe devolver **campos raw** (sin HTML) para que el JS controle el render:

```php
$data[] = [
    'id'          => $item['id'],
    // Identificadores visuales (sin HTML)
    'codigo'      => $item['codigo'] ?? '',
    'nombre'      => esc($item['nombre']),
    // Estado raw para badge JS
    'estado_raw'  => $item['estado'],
    // Relaciones como texto plano
    'conductor'   => $item['conductor_nombre'] ?? '',
    'centro_costo'=> esc($item['codigo_centro_costo'] ?? ''),
    // Acciones como HTML (generadas en el controller)
    'acciones'    => $this->generarAcciones($item),
];
```

**Wrapper de respuesta estándar:**
```php
return $this->response->setJSON([
    'draw'            => intval($draw),
    'recordsTotal'    => $paginatedData['total'],
    'recordsFiltered' => $paginatedData['filtered'],
    'data'            => $data,
]);
```

---

### 4.6 Módulos que deben migrar a este estándar

| Módulo | Vista index | JS módulo | Estado |
|--------|-------------|-----------|--------|
| **Vehículos** | `vehiculos/index.php` | `Vehiculos.js` | ✅ Completado |
| **Mantenimientos** | `mantenimientos/index.php` | `Mantenimientos.js` | ⏳ Pendiente |
| **Conductores** | `conductores/index.php` | `Conductores.js` | ⏳ Pendiente |
| **Documentación** | `documentacion/index.php` | `Documentacion.js` | ⏳ Pendiente |
| **Usuarios** | `usuarios/index.php` | `Usuarios.js` | ⏳ Pendiente |

---

## 7. Sesión 2026-05-30 — Registro de Combustible + Sidebar + SAG

> Fecha: 2026-05-30 | Focus: Correcciones críticas de lógica de negocio, UI/UX del despacho de combustible, y responsive del sidebar.

### 7.1 Sidebar Responsive (layouts/main.php + layouts/sidebar.php)

| Item | Estado |
|------|--------|
| Toggle unificado móvil/escritorio | ✅ |
| Overlay CSS sin blur (`backdrop-filter` removido) | ✅ |
| Content area responsive en mobile (`margin-left: 0`, padding ajustado) | ✅ |
| Cierre con X, overlay click, y resize handler | ✅ |
| Debug `echo` statements removidos | ✅ |

**Archivos:** `app/Views/layouts/main.php`, `app/Views/layouts/sidebar.php`

### 7.2 Lógica de Rendimiento — Corrección crítica

| Item | Estado |
|------|--------|
| **Bug fix:** `rnd > prom` → `rnd < prom` (bloquear cuando el rendimiento es PEOR, no mejor) | ✅ |
| Labels corregidas: "Rendimiento Calculado" / "Promedio Histórico" | ✅ |
| Mensaje descriptivo del motivo con porcentaje de desviación | ✅ |
| Color `text-danger` cuando `rnd < prom`, `text-success` cuando `rnd >= prom` | ✅ |

**Archivo:** `app/Views/registro_combustible/form.php`

### 7.3 Validación de Capacidad del Tanque

| Item | Estado |
|------|--------|
| **Frontend:** `calcularRendimiento()` detecta `cantidad_litros > max_combustible` y marca BLOQUEADO | ✅ |
| **Backend:** `RegistroCombustibleService::validarCapacidadTanque()` rechaza guardado si excede | ✅ |
| Visual de capacidad en card de vehículo (`vInfoCap`) — selector corregido (`#modalVehiculosList`) | ✅ |
| Reset de `maxCapacidad` al limpiar vehículo | ✅ |

**Archivos:** `app/Views/registro_combustible/form.php`, `app/Services/RegistroCombustibleService.php`

### 7.4 Observaciones SAG — Auto-generadas para BLOQUEADO

| Item | Estado |
|------|--------|
| Textarea auto-rellena con mensaje estructurado cuando estado=BLOQUEADO | ✅ |
| Badge "Requerido para SAG" rojo, hint de bloqueo, borde rojo del textarea | ✅ |
| No sobrescribe si el operador edita manualmente (`_obsAutoGenerado` flag + `input` listener) | ✅ |
| Se limpia auto-texto si vuelve a APROBADO (y no fue editado manualmente) | ✅ |
| `show.php` muestra banner de alerta SAG, separa cabecera técnica de nota del operador | ✅ |
| `show.php` muestra aviso si no hay justificación registrada | ✅ |

**Archivos:** `app/Views/registro_combustible/form.php`, `app/Views/registro_combustible/show.php`

### 7.5 Resumen del despachador (Step 4) ampliado

| Sección | Campos agregados |
|---------|-----------------|
| **Vehículo** | Placa, Marca/Modelo, Año, **Capacidad del tanque** |
| **Conductor** | Nombre, DNI, Carnet |
| **Despacho** | Fecha, Tipo consumo, **Km anterior**, **Km actual**, **Recorrido**, Litros, **Galones**, **Motivo** |
| **Rendimiento** | Calculado (color), Promedio histórico, Diferencia %, **Estado badge** (APROBADO/BLOQUEADO) |
| **Observaciones** | Texto completo del operador (solo si existe) |
| **Alerta** | Duplicada del Step 3 para visibilidad en revisión final |

**Archivo:** `app/Views/registro_combustible/form.php`

### 7.6 Pendientes de esta sesión

| Tarea | Prioridad |
|-------|-----------|
| Aplicar `helper('vehiculo')` en `vehiculos/index.php` y `vehiculos/edit.php` | Media |
| Migrar JS embebido de `vehiculos/index.php` a `Vehiculos.js` | Media |
| Crear `FormValidator.js` para validación estandarizada de formularios | Media |
| Módulo Mantenimientos y Documentación (estándar index + JS modular) | Baja |
| Rate Limiting en endpoints AJAX (`RateLimitFilter`) | Baja |
