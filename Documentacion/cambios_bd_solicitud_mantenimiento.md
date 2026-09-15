# Cambios en Base de Datos — Solicitud de Mantenimiento Wizard

**Fecha:** 15 de septiembre de 2026  
**Módulo:** Solicitudes de Mantenimiento (solo vehículos)  
**Rama:** `actualizacion-vehiculos`

---

## 1. Recreación de la base de datos

La base de datos local `tms` se encontraba corrupta y fue recreada desde el dump estructural más completo disponible:

- `database/conductores_tables.sql` — esquema completo actual de tablas `tms`.
- `tmp_seed_db.php` — script PHP de pruebas para insertar datos iniciales mínimos (empresa, roles, usuario admin, conductores, vehículos, tipos de problema, catálogo y menú).

**Nota:** En producción no se debe ejecutar la recreación completa sin respaldo previo. Los pasos documentados aquí son para el ambiente local de desarrollo (`c:\xampp\htdocs\GMV`).

## 2. Columnas nuevas en `solicitudes`

Para soportar el wizard de 4 pasos se agregaron los siguientes campos a la tabla `solicitudes`:

| Columna | Tipo | Requerido | Descripción |
|---------|------|-----------|-------------|
| `tipo_mantenimiento` | `VARCHAR(20)` | No | Tipo seleccionado en el wizard: `PREVENTIVO`, `CORRECTIVO` o `EMERGENCIA`. |
| `ubicacion` | `VARCHAR(255)` | No | Ubicación física actual del vehículo reportada por el solicitante. |
| `condicion_movilidad` | `VARCHAR(30)` | No | Estado de movilidad: `OPERATIVO`, `INMOVILIZADO` o `ARRASTRE`. |

### SQL ejecutado

```sql
ALTER TABLE solicitudes
    ADD COLUMN IF NOT EXISTS tipo_mantenimiento VARCHAR(20) NULL AFTER id_tipo_mantenimiento,
    ADD COLUMN IF NOT EXISTS ubicacion VARCHAR(255) NULL AFTER descripcion,
    ADD COLUMN IF NOT EXISTS condicion_movilidad VARCHAR(30) NULL AFTER ubicacion;
```

### Justificación

- `tipo_mantenimiento`: el wizard separa explícitamente la naturaleza de la solicitud (preventivo, correctivo, emergencia) y es distinto al catálogo `id_tipo_mantenimiento`.
- `ubicacion`: necesaria para despachar mecánicos o grúas al lugar correcto, especialmente en emergencias.
- `condicion_movilidad`: impacta la prioridad y el tipo de recurso a asignar (arrastre si el vehículo no puede moverse).

## 3. Otros ajustes de esquema detectados durante la recreación

### 3.1 `accesos` — incompatibilidad de tipos en claves foráneas

El script `v1.0.0_estructura_inicial.sql` creaba `accesos` con columnas `INT UNSIGNED` que referenciaban `roles.id` y `usuarios.id` definidos como `INT`. En MariaDB esto genera error 150 al crear la tabla. Se corrigió usando `INT` en las claves foráneas de `accesos`.

**Cambio realizado en el script temporal `tmp_struct_tms.sql`:**

```sql
-- Antes:
id_rol              INT UNSIGNED NOT NULL,
usuario_crea        INT UNSIGNED NOT NULL,
usuario_actualiza   INT UNSIGNED NULL DEFAULT NULL,

-- Después:
id_rol              INT NOT NULL,
usuario_crea        INT NOT NULL,
usuario_actualiza   INT NULL DEFAULT NULL,
```

### 3.2 `registro_combustible` — referencia a `direcciones`

El esquema inicial de `v1.0.0_estructura_inicial.sql` crea `registro_combustible` con una FK a `direcciones(id)`, pero la tabla `direcciones` no existía en ese archivo; sí existe en `database/conductores_tables.sql`. Al usar este último dump la tabla y sus dependencias se crean correctamente.

### 3.3 `vehiculos.tipo_consumo` — columna ausente

El dashboard intenta consultar `v.tipo_consumo`, campo que no existía en el dump estructural. Se agregó para evitar errores 500 en el login:

```sql
ALTER TABLE vehiculos ADD COLUMN IF NOT EXISTS tipo_consumo INT DEFAULT 0;
```

**Justificación:** el dashboard es la página de destino después del login; sin este campo el sistema era inaccesible para pruebas. Se mantiene como entero nullable con default 0 para no afectar datos existentes.

### 3.4 Estado "En Mantenimiento" para vehículos

Para soportar la inmovilización automática del vehículo cuando se genera una solicitud crítica, se agregó el estado `EN MANTENIMIENTO` a los enums de las tablas `vehiculos` e `historial_estado_vehiculo`:

```sql
ALTER TABLE vehiculos
    MODIFY COLUMN estado ENUM('ACTIVO','INACTIVO','EN REPARACION','EN MANTENIMIENTO') DEFAULT 'ACTIVO';

ALTER TABLE historial_estado_vehiculo
    MODIFY COLUMN estado ENUM('ACTIVO','INACTIVO','EN REPARACION','EN MANTENIMIENTO') NOT NULL;
```

**Regla de negocio implementada en `Solicitudes::store()`:**

Un vehículo pasa automáticamente a estado `EN MANTENIMIENTO` cuando una nueva solicitud cumple alguna de estas condiciones:

- `prioridad = 4` (Crítica)
- `tipo_mantenimiento = 'EMERGENCIA'`
- `condicion_movilidad = 'INMOVILIZADO'`

Además se inserta un registro en `historial_estado_vehiculo` para auditoría.

**Justificación:** evita que un vehículo con falla grave o en emergencia siga siendo asignado a viajes mientras se realiza la revisión.

## 4. Datos iniciales de prueba (desarrollo local)

Para validar el wizard se insertaron:

- 1 empresa (`Transportes GMV S.A.C.`)
- 3 roles (`Administrador`, `Mecánico`, `Conductor`)
- 1 usuario admin (`admin` / `password`)
- 3 conductores con carnet
- 4 vehículos (3 asignados a conductores, 1 sin conductor)
- 8 tipos de problema
- 4 ítems de menú con accesos para el rol Administrador

## 5. Validaciones actualizadas en el modelo

`SolicitudModel` ahora permite los nuevos campos y acepta los estados extendidos del flujo de trabajo:

```php
'estado' => 'permit_empty|in_list[PENDIENTE,PENDIENTES,PLANIFICADA,APROBADA,APROBADAS,EN_PROCESO,EN_PAUSA,COMPLETADA,CANCELADA,RECHAZADA,FINALIZADA]',
'tipo_mantenimiento' => 'permit_empty|in_list[PREVENTIVO,CORRECTIVO,EMERGENCIA]',
'ubicacion' => 'permit_empty|string|max_length[255]',
'condicion_movilidad' => 'permit_empty|in_list[OPERATIVO,INMOVILIZADO,ARRASTRE]'
```

## 6. Archivos modificados

- `app/Models/SolicitudModel.php` — `allowedFields` y `validationRules`.
- `app/Controllers/Solicitudes.php` — métodos `create`, `store`, `show`, `buscarVehiculo`.
- `app/Views/solicitudes/create.php` — wizard reformulado con estilo One UI / Bento Grid.
- `app/Views/solicitudes/show.php` — vista de confirmación/resumen.
- `app/Config/Routes.php` — ruta `buscar-vehiculo` y alias `solicitudes/crear`.

## 7. Pruebas ejecutadas

1. Recrear BD `tms` desde dump estructural.
2. Insertar datos de prueba.
3. Login con usuario `admin` / `password`.
4. Acceso a `/solicitudes/create`.
5. Búsqueda AJAX de vehículos por placa (`ABC`).
6. Envío completo del wizard con datos válidos.
7. Verificación de registro en tabla `solicitudes`.

Resultado: la solicitud se creó con estado `PENDIENTE` y los campos `tipo_mantenimiento`, `ubicacion`, `condicion_movilidad` correctamente poblados.
