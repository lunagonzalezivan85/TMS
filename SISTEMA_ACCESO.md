# Sistema de Control de Acceso GMV

Este documento explica cómo funciona el nuevo sistema de control de acceso basado en roles y menús de la base de datos.

## Componentes del Sistema

### 1. Helper de Acceso (`app/Helpers/access_helper.php`)

Proporciona funciones para validar acceso a URLs y menús:

#### Funciones Principales:
- `hasAccess($url)` - Verifica si el usuario actual tiene acceso a una URL
- `requireAccess($url, $redirectTo)` - Requiere acceso o redirige con error
- `canAccessMenu($menuId)` - Verifica acceso por ID de menú
- `getUserMenuUrls()` - Obtiene todas las URLs permitidas para el usuario
- `getAccessDebugInfo()` - Información de debug sobre accesos

#### Funciones para Vistas:
- `canShow($url)` - Verifica si mostrar un elemento en la vista
- `canShowAny($urls, $requireAll)` - Verifica acceso a múltiples URLs
- `linkIfAllowed($url, $text, $attributes, $fallbackText)` - Genera enlaces condicionalmente
- `buttonIfAllowed($url, $text, $class, $attributes)` - Genera botones condicionalmente

### 2. Filtro de Acceso (`app/Filters/AccessFilter.php`)

Filtro que se ejecuta automáticamente en cada request para validar acceso:

- Permite URLs públicas sin restricción
- Verifica que el usuario esté logueado
- Valida acceso a la URL solicitada
- Redirige con mensaje de error si no tiene acceso

### 3. Controlador Base Seguro (`app/Controllers/SecureController.php`)

Controlador base que incluye validación automática de acceso:

#### Características:
- Validación automática en `initController()`
- URLs públicas configurables por controlador
- URL de redirección personalizable
- Métodos auxiliares para verificar acceso
- Carga automática del helper de acceso

### 4. Configuración del Menú (`app/Config/Menu.php`)

El método `getMenuByUserType()` ha sido actualizado para:

- Obtener menús desde la base de datos según el rol del usuario
- Construir estructura jerárquica de menús
- Filtrar menús según accesos asignados al rol

## Cómo Usar el Sistema

### En Controladores

#### Opción 1: Extender SecureController (Recomendado)
```php
<?php
namespace App\Controllers;

class MiControlador extends SecureController
{
    // URLs que no requieren validación (opcional)
    protected $publicUrls = ['mi-controlador/publico'];
    
    // URL de redirección personalizada (opcional)
    protected $redirectUrl = 'dashboard';
    
    public function index()
    {
        // El acceso ya fue validado automáticamente
        return view('mi_vista');
    }
    
    public function metodoEspecial()
    {
        // Verificar acceso a URL específica
        if (!$this->checkAccess('otra-url')) {
            return redirect()->to('dashboard')->with('error', 'Sin acceso');
        }
        
        // Continuar con la lógica
    }
}
```

#### Opción 2: Usar el Helper Manualmente
```php
<?php
namespace App\Controllers;

class MiControlador extends BaseController
{
    public function index()
    {
        // Verificar acceso manualmente
        requireAccess(); // Valida URL actual
        // o
        requireAccess('url-especifica', 'dashboard');
        
        return view('mi_vista');
    }
}
```

### En Rutas

```php
// Aplicar filtro de acceso a rutas específicas
$routes->group('mi-modulo', ['filter' => 'access'], function($routes) {
    $routes->get('/', 'MiControlador::index');
    $routes->get('crear', 'MiControlador::create');
    // ...
});

// O aplicar a ruta individual
$routes->get('mi-ruta', 'MiControlador::metodo', ['filter' => 'access']);
```

### En Vistas

#### Mostrar/Ocultar Elementos Condicionalmente
```php
<!-- Mostrar botón solo si tiene acceso -->
<?php if (canShow('vehiculos/create')): ?>
    <a href="<?= base_url('vehiculos/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Crear Vehículo
    </a>
<?php endif; ?>

<!-- Verificar acceso a múltiples URLs -->
<?php if (canShowAny(['vehiculos/create', 'vehiculos/edit'])): ?>
    <div class="admin-section">
        <!-- Contenido para usuarios con permisos de administración -->
    </div>
<?php endif; ?>

<!-- Generar enlace condicionalmente -->
<?= linkIfAllowed('vehiculos/create', 'Crear Vehículo', ['class' => 'btn btn-primary'], 'Sin permisos') ?>

<!-- Generar botón condicionalmente -->
<?= buttonIfAllowed('vehiculos/edit/1', 'Editar', 'btn btn-warning') ?>
```

#### Menús Dinámicos en Sidebar
```php
<!-- El sidebar ya usa el sistema automáticamente -->
<?php
$userType = session('rol_nombre') ?? session('rol_name') ?? 'ADMINISTRADOR';
$sidebarMenu = Menu::getMenuByUserType($userType);

foreach ($sidebarMenu as $menu):
    // Solo se muestran los menús permitidos para el rol
?>
    <li class="nav-item">
        <a href="<?= base_url($menu['ruta']) ?>" class="nav-link">
            <i class="<?= $menu['icono'] ?>"></i>
            <span><?= $menu['menu'] ?></span>
        </a>
    </li>
<?php endforeach; ?>
```

## Configuración de Accesos en Base de Datos

### Estructura de Tablas

1. **roles** - Define los roles del sistema
2. **menu** - Define los menús disponibles
3. **accesos** - Relaciona roles con menús (tabla pivot)

### Ejemplo de Configuración

```sql
-- Crear un nuevo menú
INSERT INTO menu (menu, ruta, icono, padre_id, orden, activo) 
VALUES ('Gestión de Flotas', 'flotas', 'fas fa-truck', NULL, 5, 1);

-- Asignar acceso al rol administrador
INSERT INTO accesos (rol_id, menu_id, activo) 
VALUES (1, LAST_INSERT_ID(), 1);
```

## URLs Públicas por Defecto

El sistema considera estas URLs como públicas (no requieren autenticación):

- `login`
- `logout`
- `auth/*`
- `register/*`
- `reset-password`
- `/` (página principal)

## Debug y Troubleshooting

### Información de Debug
```php
// En controlador
$debugInfo = $this->getAccessDebugInfo();
log_message('debug', 'Access Info: ' . json_encode($debugInfo));

// En vista o helper
$debugInfo = getAccessDebugInfo();
```

### Logs del Sistema
El sistema registra automáticamente:
- Accesos denegados con usuario y URL
- Errores en consultas de acceso
- Información de debug cuando está habilitada

### Verificar Configuración
```php
// Verificar si un usuario tiene acceso a una URL específica
if (hasAccess('vehiculos/create')) {
    echo "Usuario tiene acceso";
} else {
    echo "Usuario NO tiene acceso";
}

// Ver todas las URLs permitidas
$urls = getUserMenuUrls();
var_dump($urls);
```

## Mejores Prácticas

1. **Usar SecureController** para nuevos controladores
2. **Aplicar filtro 'access'** en rutas sensibles
3. **Usar funciones del helper** en vistas para mostrar/ocultar elementos
4. **Configurar URLs públicas** apropiadamente en cada controlador
5. **Mantener sincronizados** los menús en la base de datos con las rutas reales
6. **Usar logs** para monitorear accesos denegados
7. **Probar con diferentes roles** antes de desplegar

## Migración de Código Existente

### Paso 1: Actualizar Controladores
```php
// Antes
class MiControlador extends BaseController

// Después  
class MiControlador extends SecureController
```

### Paso 2: Actualizar Rutas
```php
// Antes
$routes->group('mi-modulo', ['filter' => 'auth'], function($routes) {

// Después
$routes->group('mi-modulo', ['filter' => 'access'], function($routes) {
```

### Paso 3: Actualizar Vistas
```php
// Antes
<a href="<?= base_url('crear') ?>" class="btn btn-primary">Crear</a>

// Después
<?= buttonIfAllowed('crear', 'Crear', 'btn btn-primary') ?>
```

## Soporte y Mantenimiento

- Los accesos se gestionan desde el módulo de **Gestión de Accesos**
- Los menús se configuran en la base de datos
- Los roles se asignan a usuarios en el módulo de **Usuarios**
- El sistema es compatible con la estructura existente de permisos

---

**Nota**: Este sistema reemplaza las validaciones hardcodeadas anteriores y proporciona un control de acceso más flexible y mantenible basado en la base de datos.
