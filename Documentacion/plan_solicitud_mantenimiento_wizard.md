# Plan de Proyecto — Solicitud de Mantenimiento Wizard (Vehículos)

**Agente PM:** Morpheus  
**Fecha:** 15 de septiembre de 2026  
**Módulo:** Solicitudes de Mantenimiento (solo vehículos)  
**Rama:** `actualizacion-vehiculos`  
**Diseño:** One UI / Bento Grid

---

## 1. Visión Global

Reformular el flujo de **creación de solicitudes de mantenimiento** como un wizard de 4 pasos con diseño One UI / Bento Grid. El objetivo es que cualquier usuario (conductor, operador o técnico) pueda reportar una avería de un vehículo de forma guiada, validada y conectada al vehículo correcto. El alcance se restringe exclusivamente a **mantenimiento de vehículos**.

## 2. Épicas y tareas

| ID | Tarea | Estado |
|----|-------|--------|
| TSK-01 | Análisis BD: validar columnas necesarias para solicitud de vehículo | ✅ Hecho |
| TSK-02 | Diseño wizard 4 pasos (One UI / Bento Grid) | Pendiente |
| TSK-03 | Crear/actualizar endpoint AJAX: buscar conductor por carnet + vehículo asignado | Pendiente |
| TSK-04 | Implementar vista wizard `solicitudes/crear_wizard.php` | Pendiente |
| TSK-05 | Implementar controlador `Solicitudes::crearWizard()` y `Solicitudes::storeWizard()` | Pendiente |
| TSK-06 | Integrar carga de fotos/videos con preview | Pendiente |
| TSK-07 | Pruebas funcionales: flujo completo, validaciones, CSRF | Pendiente |
| TSK-08 | Documentar cambios en `cambios_bd.md` y `CHANGELOG.md` | Pendiente |
| TSK-09 | Merge a `main` y push | Pendiente |

## 3. Wizard propuesto (4 pasos)

### Paso 1 — Identificar vehículo
**Objetivo:** Vincular la solicitud a un vehículo activo de la empresa.

- Buscar por: placa, número de motor o carnet del conductor asignado.
- Mostrar tarjeta Bento con: foto/ícono, placa, marca, modelo, año, kilometraje, conductor asignado, estado.
- Validar que el vehículo pertenezca a la empresa del usuario y esté ACTIVO.
- Guardar `id_vehiculo` (obligatorio).

### Paso 2 — Tipo de mantenimiento
**Objetivo:** Clasificar la naturaleza de la solicitud.

Opciones tipo tarjetas grandes (Bento):
- **Preventivo** — mantenimiento programado.
- **Correctivo** — reparación de falla.
- **Emergencia** — falla crítica que inhabilita el vehículo.

Guardar `tipo_mantenimiento` (obligatorio).

### Paso 3 — Detalle del problema
**Objetivo:** Recoger la información técnica del reporte.

Campos:
- Tipo de problema (catálogo `CAT-0010`) — tarjetas con iconos.
- Prioridad (Baja / Media / Alta / Crítica) — tarjetas de color.
- Descripción detallada (textarea, mínimo 10 caracteres).
- Ubicación actual del vehículo (texto, opcional).
- Condición de movilidad (operativo / inmovilizado / solo arrastre).
- Evidencias: fotos/videos con preview y límite 5MB.

Guardar: `id_tipo_problema`, `prioridad`, `descripcion`, `ubicacion`, `condicion_movilidad`, `url_foto`.

### Paso 4 — Confirmación y envío
**Objetivo:** Revisar y confirmar antes de crear la OT.

- Resumen Bento: vehículo, tipo, problema, prioridad, descripción, evidencias.
- Código consecutivo generado automáticamente (`SOL-AAAA-XXXXX`).
- Estado inicial: `PENDIENTES`.
- Botón "Enviar solicitud" con anti-doble-submit.
- Redirección a `solicitudes/show/{id}` con mensaje de éxito.

## 4. Cambios en BD

### Tabla `solicitudes`

| Columna | Tipo | Requerido | Notas |
|---------|------|-----------|-------|
| `id` | int PK AI | Sí | Existe |
| `id_empresa` | int | Sí | Existe |
| `codigo_consecutivo` | varchar(20) | Sí | Existe, formato actual `SOL-XXXXX` |
| `id_vehiculo` | int | Sí | Existe, FK a `vehiculos` |
| `id_solicitante` | int | Sí | Existe, usuario que crea la solicitud |
| `solicitante` | varchar(150) | Sí | Existe, nombre del solicitante |
| `id_tipo_problema` | int | Sí | Existe, FK a catálogo `CAT-0010` |
| `id_tipo_mantenimiento` | int | Opcional | Existe, puede usarse para el tipo |
| `tipo_mantenimiento` | varchar(20) | Sí | **Agregar** (PREVENTIVO/CORRECTIVO/EMERGENCIA) |
| `descripcion` | text | Sí | Existe |
| `prioridad` | int/varchar | Sí | Existe actualmente como `int` (1,2,3,4). Se usará varchar `BAJA/MEDIA/ALTA/CRITICA` o mapeo |
| `estado` | varchar(20) | Sí | Existe, default `PENDIENTES` |
| `ubicacion` | varchar(255) | Opcional | **Agregar** |
| `condicion_movilidad` | varchar(30) | Opcional | **Agregar** (OPERATIVO/INMOVILIZADO/ARRASTRE) |
| `url_foto` | varchar(255) | Opcional | Existe |
| `fecha_solicitud` | datetime | Sí | Existe |
| `fecha_limite` | datetime | Opcional | Existe |
| `fecha_cierre` | datetime | Opcional | Existe |
| `fecha_registro` | datetime | Sí | Existe |
| `fecha_actualizacion` | datetime | Sí | Existe |
| `usuario_crea` | int | Sí | Existe |
| `usuario_actualiza` | int | Sí | Existe |

### Columnas a agregar

```sql
ALTER TABLE solicitudes
    ADD COLUMN tipo_mantenimiento VARCHAR(20) NULL AFTER id_tipo_mantenimiento,
    ADD COLUMN ubicacion VARCHAR(255) NULL AFTER descripcion,
    ADD COLUMN condicion_movilidad VARCHAR(30) NULL AFTER ubicacion;
```

**Justificación:** Estas columnas permiten capturar en el wizard el tipo de mantenimiento seleccionado, la ubicación física del vehículo y si puede moverse por sus propios medios. Son datos mínimos para priorizar y despachar la orden.

## 5. Diseño UX/UI

- Contenedor centrado `col-lg-10` con sombra suave y bordes redondeados.
- Barra de progreso superior con 4 círculos numerados.
- Cada paso usa tarjetas grandes tipo Bento (2-3 columnas en desktop, 1 en móvil).
- Iconos circulares con fondo de color suave.
- Estados visuales: hover, selected (borde primario + sombra), disabled.
- Botones de navegación alineados a los lados.
- Preview de evidencias en grid.
- Tipografía Inter, paleta primaria `#07b889`.

## 6. Dependencias y riesgos

- **Dependencia:** El endpoint de búsqueda de conductor por carnet debe devolver el vehículo asignado. Actualmente `conductores` tiene `carnet` y `vehiculos` tiene `id_conductor`. La relación existe.
- **Riesgo:** La validación del modelo `SolicitudModel` actualmente usa `prioridad` como `in_list[1,2,3,4]`. Se debe alinear con el wizard.
- **Riesgo:** El campo `id_solicitante` se asigna automáticamente desde sesión; el campo `solicitante` (nombre) se captura en paso 1 o se toma del usuario logueado.

## 7. Definition of Done

- [ ] Wizard de 4 pasos funcional con diseño One UI/Bento Grid.
- [ ] Búsqueda de vehículo por placa/motor/carnet vía AJAX real (no simulado).
- [ ] Validaciones por paso (bloquear avance si falta datos).
- [ ] Anti-doble-submit en envío final.
- [ ] Fotos/videos con preview y límite de tamaño.
- [ ] Pruebas manuales exitosas en local.
- [ ] Documentación de BD actualizada.
- [ ] Código subido a `main`.
