# Módulo de Asignación de Vehículos - GMV System

## Descripción General

El módulo de Asignación de Vehículos permite gestionar la asignación y desasignación de vehículos a conductores a través de un wizard intuitivo de 3 pasos. Este módulo garantiza la integridad de los datos y proporciona un seguimiento completo del historial de asignaciones.

## Características Principales

### ✅ Wizard de Asignación (3 Pasos)
- **Paso 1**: Selección del tipo de unidad mediante tarjetas visuales
- **Paso 2**: Selección del vehículo disponible filtrado por tipo
- **Paso 3**: Selección del conductor y confirmación de asignación

### ✅ Gestión Completa
- Listado de asignaciones con filtros avanzados
- Vista detallada de cada asignación
- Proceso de desasignación con motivos
- Estadísticas en tiempo real

### ✅ Validaciones y Seguridad
- Validación de disponibilidad de vehículos y conductores
- Transacciones de base de datos para integridad
- Control de acceso basado en autenticación
- Validaciones tanto client-side como server-side

## Estructura de Archivos

```
GMV/
├── app/
│   ├── Controllers/
│   │   └── AsignacionVehiculos.php          # Controlador principal
│   ├── Models/
│   │   └── AsignacionVehiculoModel.php      # Modelo de datos
│   ├── Views/
│   │   └── asignacion_vehiculos/
│   │       ├── index.php                    # Lista de asignaciones
│   │       ├── show.php                     # Detalles de asignación
│   │       ├── desasignar.php              # Formulario de desasignación
│   │       ├── wizard_step1.php            # Paso 1: Tipo de unidad
│   │       ├── wizard_step2.php            # Paso 2: Selección de vehículo
│   │       └── wizard_step3.php            # Paso 3: Conductor y confirmación
│   ├── Database/
│   │   ├── Migrations/
│   │   │   └── 2024-01-15-000000_CreateAsignacionVehiculosTable.php
│   │   └── Seeds/
│   │       └── AsignacionVehiculosSeeder.php
│   └── Config/
│       └── Routes.php                       # Rutas del módulo
└── docs/
    └── AsignacionVehiculos_README.md        # Este archivo
```

## Base de Datos

### Tabla: `asignacion_vehiculos`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT(11) PK | Identificador único |
| `id_vehiculo` | INT(11) FK | ID del vehículo asignado |
| `id_conductor` | INT(11) FK | ID del conductor asignado |
| `fecha_asignacion` | DATETIME | Fecha y hora de asignación |
| `fecha_desasignacion` | DATETIME NULL | Fecha y hora de desasignación |
| `motivo_asignacion` | TEXT | Motivo de la asignación |
| `motivo_desasignacion` | TEXT NULL | Motivo de la desasignación |
| `estado` | ENUM('ACTIVA','INACTIVA') | Estado de la asignación |
| `usuarioCrea` | INT(11) FK | Usuario que creó el registro |
| `usuarioEdita` | INT(11) FK | Usuario que editó el registro |
| `fechaRegistro` | DATETIME | Fecha de creación del registro |
| `fechaUpdate` | DATETIME | Fecha de última actualización |

### Índices
- `PRIMARY KEY (id)`
- `INDEX idx_vehiculo_estado (id_vehiculo, estado)`
- `INDEX idx_conductor_estado (id_conductor, estado)`
- `INDEX idx_fecha_estado (fecha_asignacion, estado)`

### Foreign Keys
- `id_vehiculo` → `vehiculos.id`
- `id_conductor` → `conductores.id`
- `usuarioCrea` → `usuarios.id`
- `usuarioEdita` → `usuarios.id`

## Rutas del Sistema

### Rutas Principales
```php
// Vista principal
GET /asignacion-vehiculos

// Detalles de asignación
GET /asignacion-vehiculos/show/{id}

// Wizard de asignación
GET /asignacion-vehiculos/wizard
GET /asignacion-vehiculos/wizard/step1
POST /asignacion-vehiculos/wizard/step1
GET /asignacion-vehiculos/wizard/step2
POST /asignacion-vehiculos/wizard/step2
GET /asignacion-vehiculos/wizard/step3
POST /asignacion-vehiculos/wizard/confirmar

// Desasignación
GET /asignacion-vehiculos/desasignar/{id}
POST /asignacion-vehiculos/desasignar/{id}
```

### Rutas AJAX
```php
// Datos para DataTable
GET /asignacion-vehiculos/getData

// Estadísticas
GET /asignacion-vehiculos/getEstadisticas

// Vehículos por tipo
GET /asignacion-vehiculos/getVehiculosPorTipo/{tipo_id}
```

## Funcionalidades Detalladas

### 1. Wizard de Asignación

#### Paso 1: Selección de Tipo de Unidad
- Muestra tipos de unidad activos en formato de tarjetas
- Navegación visual con iconos y descripciones
- Validación de selección requerida

#### Paso 2: Selección de Vehículo
- Filtra vehículos por tipo de unidad seleccionado
- Solo muestra vehículos disponibles y activos
- Información detallada de cada vehículo
- Opción de regresar al paso anterior

#### Paso 3: Selección de Conductor y Confirmación
- Lista de conductores disponibles (sin asignación activa)
- Campos para fecha y motivo de asignación
- Resumen de la selección completa
- Validaciones de formulario

### 2. Gestión de Asignaciones

#### Lista Principal
- DataTable con filtros por estado, tipo de unidad y conductor
- Exportación a Excel, PDF e impresión
- Estadísticas en tiempo real
- Acciones rápidas (ver, desasignar)

#### Vista de Detalles
- Información completa de la asignación
- Datos del vehículo y conductor
- Historial de fechas y motivos
- Panel de acciones contextuales
- Información de auditoría

#### Desasignación
- Formulario con validación de motivo
- Confirmación de acción
- Actualización automática de estados
- Transacciones seguras

### 3. Validaciones del Sistema

#### Reglas de Negocio
- Un vehículo solo puede tener una asignación activa
- Un conductor solo puede tener una asignación activa
- Solo vehículos disponibles y activos pueden asignarse
- Solo conductores activos sin asignación pueden asignarse

#### Validaciones de Formulario
- Motivo de asignación: mínimo 10 caracteres, máximo 500
- Motivo de desasignación: mínimo 10 caracteres, máximo 500
- Fecha de asignación: no puede ser futura
- Confirmaciones requeridas para acciones críticas

## Instalación y Configuración

### 1. Ejecutar Migración
```bash
php spark migrate
```

### 2. Ejecutar Seeder (Opcional)
```bash
php spark db:seed AsignacionVehiculosSeeder
```

### 3. Verificar Rutas
Las rutas se cargan automáticamente desde `app/Config/Routes.php`

### 4. Permisos
Asegurar que el usuario tenga acceso al filtro 'auth' configurado

## Dependencias

### Modelos Requeridos
- `VehiculoModel` - Para gestión de vehículos
- `ConductorModel` - Para gestión de conductores
- `TipoUnidadModel` - Para tipos de unidad
- `UsuarioModel` - Para auditoría

### Librerías Frontend
- Bootstrap 5 - Para UI/UX
- FontAwesome - Para iconos
- DataTables - Para tablas interactivas
- jQuery - Para JavaScript

## API Endpoints

### GET /asignacion-vehiculos/getData
Retorna datos para DataTable con filtros opcionales.

**Parámetros:**
- `estado` (opcional): ACTIVA|INACTIVA
- `tipo_unidad` (opcional): ID del tipo de unidad
- `conductor` (opcional): Texto para buscar en nombre/DNI

**Respuesta:**
```json
{
  "data": [
    {
      "id": 1,
      "vehiculo": "ABC-123 - Toyota Hiace",
      "conductor": "Juan Pérez",
      "tipo_unidad": "Microbús",
      "fecha_asignacion": "01/01/2024",
      "fecha_desasignacion": "-",
      "estado": "<span class=\"badge bg-success\">ACTIVA</span>",
      "acciones": "<div class=\"btn-group\">...</div>"
    }
  ]
}
```

### GET /asignacion-vehiculos/getEstadisticas
Retorna estadísticas del sistema.

**Respuesta:**
```json
{
  "activas": 15,
  "total": 25,
  "vehiculos_disponibles": 8,
  "conductores_disponibles": 12
}
```

### GET /asignacion-vehiculos/getVehiculosPorTipo/{tipo_id}
Retorna vehículos disponibles por tipo de unidad.

**Respuesta:**
```json
{
  "vehiculos": [
    {
      "id": 1,
      "placa": "ABC-123",
      "marca": "Toyota",
      "modelo": "Hiace",
      "anio": 2020,
      "kilometraje": 50000
    }
  ]
}
```

## Consideraciones de Rendimiento

### Índices de Base de Datos
- Índices compuestos para consultas frecuentes
- Foreign keys para integridad referencial
- Índices en campos de filtrado común

### Optimizaciones Frontend
- Carga lazy de datos con DataTables
- Paginación server-side para grandes volúmenes
- Cache de estadísticas con actualización periódica

### Transacciones
- Uso de transacciones para operaciones críticas
- Rollback automático en caso de errores
- Logging de errores para debugging

## Testing

### Casos de Prueba Sugeridos

1. **Flujo Completo de Asignación**
   - Seleccionar tipo de unidad
   - Seleccionar vehículo disponible
   - Seleccionar conductor disponible
   - Confirmar asignación
   - Verificar estados actualizados

2. **Validaciones de Negocio**
   - Intentar asignar vehículo ya asignado
   - Intentar asignar conductor ya asignado
   - Validar campos requeridos

3. **Desasignación**
   - Desasignar vehículo activo
   - Verificar liberación de recursos
   - Validar motivos requeridos

4. **Filtros y Búsquedas**
   - Filtrar por estado
   - Filtrar por tipo de unidad
   - Buscar por conductor

## Mantenimiento

### Logs del Sistema
Los errores se registran en `writable/logs/` con el prefijo del módulo.

### Backup de Datos
Incluir la tabla `asignacion_vehiculos` en rutinas de backup regulares.

### Monitoreo
- Estadísticas de asignaciones activas
- Disponibilidad de vehículos y conductores
- Rendimiento de consultas

## Soporte y Contacto

Para soporte técnico o reportar bugs relacionados con este módulo, contactar al equipo de desarrollo del sistema GMV.

---

**Versión:** 1.0  
**Fecha:** Enero 2024  
**Autor:** Sistema GMV Development Team
