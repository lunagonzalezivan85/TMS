# Requerimientos: Portal de Conductores — Solicitud de Órdenes de Trabajo

**Fecha:** 21 de febrero de 2026  
**Módulo:** Portal Conductores  
**Objetivo:** Permitir que los conductores puedan crear solicitudes de órdenes de trabajo de forma simple y guiada mediante un asistente paso a paso (wizard).

---

## Flujo del Wizard (3 pasos)

### Paso 1 — Selección de Vehículo y Tipo de Problema

- **Campo: Código del vehículo**
  - El conductor ingresa el código/placa del vehículo
  - Se muestran botones o tarjetas con los vehículos asignados al conductor para selección rápida
  - Validación: el vehículo debe existir y estar activo

- **Campo: Tipo de problema**
  - Se muestran botones/tarjetas con los tipos de problema disponibles (catálogo `CAT-0010`)
  - Selección única con resaltado visual del ítem elegido
  - Validación: selección obligatoria

- **Botón:** `Siguiente →`

---

### Paso 2 — Descripción del Problema

- **Campo: Descripción**
  - Área de texto para que el conductor describa brevemente el problema
  - Mínimo 10 caracteres
  - Placeholder orientativo: *"Describe brevemente el problema que presenta el vehículo..."*

- **Botones de navegación:**
  - `← Anterior` — regresa al paso 1
  - `Siguiente →` — avanza al paso 3

---

### Paso 3 — Adjuntar Archivo (Opcional)

- **Campo: Archivo adjunto**
  - Botón para subir una foto o documento relacionado al problema (imagen, PDF)
  - Formatos aceptados: JPG, PNG, PDF
  - Tamaño máximo: 5 MB
  - El campo es **opcional**

- **Botones:**
  - `Subir archivo` — abre selector de archivo
  - `Omitir` — continúa sin adjuntar archivo

- **Botón final:** `Enviar Solicitud`
  - Crea la solicitud en la BD con estado `PENDIENTE`
  - Muestra confirmación con número/código de la solicitud generada

---

## Consideraciones Técnicas

| Ítem | Detalle |
|------|---------|
| **Controlador** | Nuevo método en `Solicitudes` o controlador dedicado `PortalConductor` |
| **Ruta** | `portal/solicitud/crear` |
| **Modelo** | `SolicitudModel` — método `store` existente |
| **Catálogo tipos** | `CatalogoModel::getCatalogosPorCodigo('CAT-0010')` |
| **Vehículos** | Filtrar por conductor logueado (`id_conductor = session user_id`) |
| **Archivo** | Guardar en `writable/uploads/solicitudes/` y registrar ruta en `url_foto` |
| **Estado inicial** | `PENDIENTE` |
| **Prioridad inicial** | `MEDIA` (puede ajustarse según tipo de problema) |
| **Acceso** | Solo rol `CONDUCTOR` — protegido por filtro `auth` |

---

## Pendiente de Definir

- ¿Los vehículos se filtran por conductor asignado o el conductor puede buscar cualquier vehículo de su empresa?
- ¿Se envía notificación al administrador/jefe de taller al crear la solicitud?
- ¿El conductor puede ver el historial de sus solicitudes en el portal?
