# Plan de Modernización JavaScript - Proyecto GMV

Este plan detalla la transición de un sistema basado en jQuery y código embebido hacia una arquitectura moderna de **Vanilla JavaScript** (ES6+), modular y desacoplada del código PHP.

## 1. Objetivos Principales
*   **Eliminar la dependencia de jQuery** en la lógica de negocio y comunicación AJAX.
*   **Externalizar todo el JavaScript**: Prohibir etiquetas `<script>` dentro de los archivos [.php](file:///c:/xampp/htdocs/GMV/index.php).
*   **Estructura Modular**: Organizar el código en "Core" (utilidades globales) y "Modules" (lógica por vista).
*   **Fetch API**: Estandarizar la comunicación con el servidor usando una interfaz limpia y moderna.

## 2. Nueva Estructura de Archivos
Se propone crear la siguiente estructura en `public/assets/js/`:

```text
public/assets/js/
├── core/
│   ├── App.js         # Espacio de nombres global e inicializador
│   ├── HttpClient.js  # Wrapper de Fetch con manejo de CSRF y errores
│   └── UI.js          # Helpers para Toasts, Modales y Skeletons
└── modules/
    ├── Dashboard.js   # Lógica específica del panel principal
    ├── Vehiculos.js   # Gestión de flota y tablas
    ├── Mapas.js       # Lógica de Leaflet optimizada
    └── Portal.js      # Lógica del portal de conductores
```

## 3. Fases de Implementación

### Fase 1: Núcleo (Core) y Estándares
1.  **HttpClient (Fetch Wrapper)**:
    *   Implementar una clase que intercepte todas las peticiones para inyectar automáticamente el token **CSRF** de CodeIgniter.
    *   Manejo estandarizado de respuestas JSON y errores HTTP (401, 403, 500).
2.  **Manejo de CSRF**:
    *   Inyectar el nombre y hash del token en meta-tags del layout principal para que el JS pueda leerlos.
3.  **Bootstrap 5 Integration**:
    *   Usar exclusivamente la API de JavaScript de Bootstrap 5 para controlar modales y tooltips (ej: `new bootstrap.Modal(...)`).

### Fase 2: Refactorización de Vistas (Módulo a Módulo)
Se seguirá el siguiente orden de prioridad:
1.  **Dashboard**: Mover el reloj, el refresco de stats y la configuración de widgets a `Dashboard.js`.
2.  **Portal Conductores**: Mover la lógica del wizard de solicitudes a `Portal.js`.
3.  **Mapas**: Desacoplar la lógica de Leaflet y los filtros de búsqueda a `Mapas.js`.
4.  **Vehículos**: Limpiar la inicialización de tablas. *Nota: Seguiremos usando el plugin DataTables, pero su configuración se hará desde un archivo JS externo sin mezclar lógica de negocio en la vista.*

### Fase 3: Limpieza y Optimización
1.  Eliminar la carga de jQuery del [main.php](file:///c:/xampp/htdocs/GMV/app/Views/layouts/main.php) una vez que todos los componentes propios estén migrados (o mantenerlo solo para plugins legacy como DataTables si es estrictamente necesario).
2.  Implementar **Lazy Loading** de scripts por vista para mejorar la velocidad de carga inicial.

## 4. Ejemplo de Estructura Propuesta (Código)

### HttpClient.js (Core)
```javascript
class HttpClient {
    static async fetch(url, options = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            ...options.headers
        };
        // Lógica de fetch...
    }
}
```

### Dashboard.js (Módulo)
```javascript
const Dashboard = {
    init() {
        this.initClock();
        this.bindEvents();
    },
    async refreshStats() {
        const data = await HttpClient.get('/api/stats');
        // Actualizar UI...
    }
};
document.addEventListener('DOMContentLoaded', () => Dashboard.init());
```

---
> [!IMPORTANT]
> Este plan asegura que la lógica de PHP se limite estrictamente a la presentación de datos y el JavaScript a la interactividad, facilitando el mantenimiento y las pruebas futuras.
