# Módulo Taller — Flujo de Órdenes de Trabajo

## Estado actual del sistema

El sistema GMV cuenta con un flujo robusto de órdenes de trabajo que incluye:

- Catálogo de vehículos (CRUD completo)
- Solicitud de mantenimiento (creación, historial, documentos)
- Orden de trabajo (dashboard, kanban, calendario, clasificación, asignación)
- Registro de trabajo (técnico, horas, trabajo realizado, kilometraje)
- Materiales utilizados (cantidad y costo por material)
- Historial de cambios por orden
- Estados: PENDIENTE → EN_PROCESO → FINALIZADA

## Flujo propuesto de Orden de Trabajo

El flujo completo de una orden de trabajo será:

```
Solicitud → Clasificación → Diagnóstico → Presupuesto → Autorización → Ejecución → Servicios Externos → Pruebas/Calidad → Cierre + Entrega
```

### 1. Diagnóstico técnico (fase dentro de la OT)

**Ubicación:** Entre la clasificación y la ejecución de la orden.

**Propósito:** Registrar la evaluación técnica realizada por el jefe de taller o técnico responsable.

**Campos a agregar en la orden de trabajo:**

- Técnico responsable del diagnóstico
- Fecha del diagnóstico
- Falla reportada (ya existe en la solicitud)
- Falla encontrada (nueva)
- Componente afectado
- Causa probable
- Nivel de severidad (Baja, Media, Alta, Crítica)
- Riesgo operativo (Bajo, Medio, Alto)
- Actividades recomendadas
- Repuestos requeridos (lista preliminar)
- Servicios externos requeridos (lista preliminar)
- Tiempo estimado (horas o días)
- Costo estimado
- Evidencias (fotografías)
- Recomendación técnica (uno de):
  - Reparación inmediata
  - Programar reparación
  - Continuar operando con observación
  - Enviar a proveedor externo
  - Sustituir componente
  - Dar de baja el vehículo
  - Requiere autorización de gerencia

**Implementación:**

- Agregar campos a la tabla `solicitudes` o crear tabla `diagnostico_orden` relacionada.
- Nueva vista `ordenes_trabajo/diagnostico.php` accesible desde el detalle de la orden.
- El diagnóstico se puede editar mientras la orden esté en estado PENDIENTE o EN_DIAGNOSTICO.
- Al guardar el diagnóstico, la orden pasa a estado PENDIENTE_AUTORIZACION.

**Nuevo estado de orden:** EN_DIAGNOSTICO

---

### 2. Presupuesto de reparación (fase dentro de la OT)

**Ubicación:** Después del diagnóstico, antes de la autorización.

**Propósito:** Estimar el costo total antes de autorizar el trabajo.

**Campos del presupuesto:**

- Mano de obra (costo estimado por horas)
- Repuestos (lista de materiales con cantidad y costo unitario)
- Materiales (insumos adicionales)
- Servicios externos (costo estimado de proveedores)
- Transporte
- Costos adicionales
- Impuestos
- Costo total estimado

**Estados del presupuesto:**

- EN_ELABORACION
- PENDIENTE_AUTORIZACION
- AUTORIZADO
- RECHAZADO
- EN_REVISION
- VENCIDO

**Implementación:**

- Crear tabla `presupuesto_orden` relacionada a `solicitudes`.
- Crear tabla `presupuesto_detalle` para items (mano de obra, repuestos, materiales, servicios externos).
- Nueva vista `ordenes_trabajo/presupuesto.php`.
- El presupuesto se elabora después del diagnóstico.
- Al enviar a autorización, la orden pasa a PENDIENTE_AUTORIZACION.
- Si se autoriza, la orden pasa a AUTORIZADA → EN_PROCESO.
- Si se rechaza, la orden puede volver a diagnóstico o cancelarse.

**Nuevos estados de orden:** PENDIENTE_AUTORIZACION, AUTORIZADA, RECHAZADA

---

### 3. Servicios externos (dentro de la OT)

**Ubicación:** Durante la ejecución de la orden.

**Propósito:** Controlar reparaciones o servicios realizados por proveedores externos.

**Campos:**

- Orden de trabajo (relación)
- Proveedor
- Tipo de servicio
- Fecha de envío
- Fecha estimada de retorno
- Cotización (monto)
- Orden de compra (referencia)
- Costo real
- Garantía (días o descripción)
- Responsable
- Estado
- Resultado
- Documentos adjuntos

**Estados del servicio externo:**

- COTIZACION_SOLICITADA
- COTIZACION_RECIBIDA
- PENDIENTE_AUTORIZACION
- AUTORIZADO
- ENVIADO_PROVEEDOR
- EN_PROCESO
- FINALIZADO
- RECIBIDO
- RECHAZADO
- EN_GARANTIA

**Implementación:**

- Crear tabla `servicios_externos` relacionada a `solicitudes`.
- Crear tabla `proveedores_externos` (catálogo).
- Nueva vista parcial `ordenes_trabajo/partials/servicios_externos.php`.
- Se accede desde el detalle de la orden (show.php).
- Se puede agregar múltiples servicios externos por orden.
- El estado de la orden puede cambiar a EN_REPARACION_EXTERNA si todos los trabajos están externos.

**Nuevo estado de orden:** EN_REPARACION_EXTERNA

---

### 4. Pruebas y control de calidad (antes del cierre)

**Ubicación:** Después de la ejecución, antes de finalizar la orden.

**Propósito:** Registrar pruebas de funcionamiento antes de cerrar la orden.

**Campos:**

- Tipo de prueba
- Técnico que realiza la prueba
- Fecha
- Resultado (uno de):
  - APROBADO
  - APROBADO_CON_OBSERVACIONES
  - REQUIERE_AJUSTE
  - RECHAZADO
  - REQUIERE_NUEVA_REPARACION
- Hallazgos
- Actividades pendientes
- Evidencias (fotografías)
- Aprobación del jefe de taller (sí/no, usuario, fecha)

**Implementación:**

- Crear tabla `pruebas_calidad_orden` relacionada a `solicitudes`.
- Nueva vista parcial `ordenes_trabajo/partials/pruebas_calidad.php`.
- Se accede desde el detalle de la orden cuando está EN_PROCESO.
- Se pueden registrar múltiples pruebas.
- Si todas las pruebas están APROBADAS o APROBADO_CON_OBSERVACIONES, se permite cerrar la orden.
- Si hay pruebas RECHAZADAS o REQUIERE_NUEVA_REPARACION, no se permite cerrar (salvo autorización especial).

**Reglas de validación:**

- No permitir cerrar la orden si hay pruebas pendientes.
- No permitir cerrar si hay pruebas RECHAZADAS sin autorización especial.
- Requerir aprobación del jefe de taller para cerrar.

---

### 5. Cierre de orden + Entrega del vehículo

**Ubicación:** Al finalizar todas las actividades, pruebas aprobadas.

**Propósito:** Cerrar formalmente la orden y registrar la entrega del vehículo.

**Datos del cierre:**

- Diagnóstico final
- Actividades realizadas (ya existe en registro_trabajo)
- Repuestos utilizados (ya existe en materiales_trabajo)
- Horas de trabajo (ya existe en registro_trabajo)
- Servicios externos (del paso 3)
- Resultado de las pruebas (del paso 4)
- Costo final (suma de mano de obra + materiales + servicios externos)
- Fecha de finalización
- Recomendaciones
- Próximo mantenimiento sugerido
- Aprobación del jefe de taller (usuario, fecha)

**Datos de la entrega:**

- Orden de trabajo (relación)
- Fecha y hora de entrega
- Kilometraje de salida
- Nivel de combustible
- Condiciones de entrega
- Trabajos realizados (resumen)
- Recomendaciones
- Pendientes
- Persona que entrega (técnico/jefe de taller)
- Persona que recibe (conductor/operaciones)
- Firma o confirmación digital
- Evidencia fotográfica

**Implementación:**

- Ampliar el método `guardarTrabajo` o crear nuevo método `cerrarOrden`.
- Crear vista `ordenes_trabajo/cierre.php` con dos secciones: cierre y entrega.
- Al cerrar:
  - Validar que todas las actividades estén completadas.
  - Validar que las pruebas estén aprobadas.
  - Calcular costo final automáticamente.
  - Registrar aprobación del jefe de taller.
  - Generar comprobante de entrega (PDF o vista imprimible).
  - Cambiar estado de la orden a ENTREGADA.
  - Actualizar kilometraje y estado del vehículo.

**Nuevos estados de orden:** PENDIENTE_PRUEBA, FINALIZADA, ENTREGADA

---

### 6. Reapertura de órdenes

**Propósito:** Permitir reabrir una orden cuando la falla persiste o hay garantía.

**Motivos de reapertura:**

- La falla persiste
- Se detecta una reparación incompleta
- El vehículo retorna por garantía
- Aparece una falla relacionada
- Existen actividades pendientes

**Implementación:**

- Botón "Reabrir orden" en el detalle de la orden cuando está ENTREGADA o FINALIZADA.
- Al reabrir, se registra: motivo, usuario, fecha.
- La orden vuelve a estado EN_PROCESO.
- Se mantiene el historial completo (no se pierden los registros anteriores).
- Crear tabla `reapertura_orden` o usar `historial_orden_trabajo` con tipo REAPERTURA.

---

## Estados completos de la orden de trabajo

Flujo de estados actualizado:

```
PENDIENTE
  → EN_DIAGNOSTICO
    → PENDIENTE_AUTORIZACION
      → AUTORIZADA
        → EN_PROCESO
          → EN_REPARACION_EXTERNA
            → EN_PROCESO
          → PENDIENTE_PRUEBA
            → FINALIZADA
              → ENTREGADA
                → (REABIERTA → EN_PROCESO)
      → RECHAZADA → (cancelar o volver a diagnóstico)
  → CANCELADA
```

---

## Tablas a crear en base de datos

### `diagnostico_orden`
```sql
- id (PK)
- id_solicitud (FK → solicitudes)
- id_tecnico (FK → usuarios)
- fecha_diagnostico
- falla_encontrada
- componente_afectado
- causa_probable
- nivel_severidad (Baja, Media, Alta, Crítica)
- riesgo_operativo (Bajo, Medio, Alto)
- actividades_recomendadas
- tiempo_estimado
- costo_estimado
- recomendacion_tecnica (enum)
- evidencias (JSON o tabla separada)
- usuario_crea
- fecha_creacion
```

### `presupuesto_orden`
```sql
- id (PK)
- id_solicitud (FK → solicitudes)
- estado (EN_ELABORACION, PENDIENTE_AUTORIZACION, AUTORIZADO, RECHAZADO, EN_REVISION, VENCIDO)
- costo_mano_obra
- costo_repuestos
- costo_materiales
- costo_servicios_externos
- costo_transporte
- costos_adicionales
- impuestos
- costo_total
- observaciones
- usuario_autoriza
- fecha_autorizacion
- usuario_crea
- fecha_creacion
```

### `presupuesto_detalle`
```sql
- id (PK)
- id_presupuesto (FK → presupuesto_orden)
- tipo (MANO_OBRA, REPUESTO, MATERIAL, SERVICIO_EXTERNO, TRANSPORTE, OTRO)
- descripcion
- cantidad
- costo_unitario
- costo_subtotal
```

### `servicios_externos`
```sql
- id (PK)
- id_solicitud (FK → solicitudes)
- id_proveedor (FK → proveedores_externos)
- tipo_servicio
- fecha_envio
- fecha_estimada_retorno
- cotizacion
- orden_compra
- costo_real
- garantia
- responsable
- estado (COTIZACION_SOLICITADA, COTIZACION_RECIBIDA, PENDIENTE_AUTORIZACION, AUTORIZADO, ENVIADO_PROVEEDOR, EN_PROCESO, FINALIZADO, RECIBIDO, RECHAZADO, EN_GARANTIA)
- resultado
- documentos_adjuntos (JSON o tabla separada)
- usuario_crea
- fecha_creacion
```

### `proveedores_externos`
```sql
- id (PK)
- nombre
- tipo_servicio
- contacto
- telefono
- email
- direccion
- estado (ACTIVO, INACTIVO)
```

### `pruebas_calidad_orden`
```sql
- id (PK)
- id_solicitud (FK → solicitudes)
- tipo_prueba
- id_tecnico (FK → usuarios)
- fecha
- resultado (APROBADO, APROBADO_CON_OBSERVACIONES, REQUIERE_AJUSTE, RECHAZADO, REQUIERE_NUEVA_REPARACION)
- hallazgos
- actividades_pendientes
- evidencias (JSON o tabla separada)
- aprobado_jefe_taller (boolean)
- id_jefe_taller (FK → usuarios)
- fecha_aprobacion
```

### `entrega_vehiculo`
```sql
- id (PK)
- id_solicitud (FK → solicitudes)
- fecha_entrega
- kilometraje_salida
- nivel_combustible
- condiciones_entrega
- trabajos_realizados (resumen)
- recomendaciones
- pendientes
- persona_entrega (FK → usuarios)
- persona_recibe (texto o FK)
- firma_confirmacion (texto/base64)
- evidencia_fotografica (JSON o tabla separada)
- usuario_crea
- fecha_creacion
```

---

## Vistas a crear/modificar

### Nuevas vistas
- `ordenes_trabajo/diagnostico.php` — formulario de diagnóstico
- `ordenes_trabajo/presupuesto.php` — formulario de presupuesto con detalle de items
- `ordenes_trabajo/cierre.php` — cierre de orden + entrega del vehículo
- `ordenes_trabajo/partials/servicios_externos.php` — gestión de servicios externos
- `ordenes_trabajo/partials/pruebas_calidad.php` — registro de pruebas
- `ordenes_trabajo/partials/diagnostico_info.php` — vista lectura de diagnóstico
- `ordenes_trabajo/partials/presupuesto_info.php` — vista lectura de presupuesto

### Vistas a modificar
- `ordenes_trabajo/show.php` — agregar secciones de diagnóstico, presupuesto, servicios externos, pruebas, entrega
- `ordenes_trabajo/dashboard.php` — agregar nuevos estados al conteo
- `ordenes_trabajo/kanban.php` — agregar nuevas columnas de estados

---

## Controlador — Métodos a agregar en OrdenesTrabajo.php

```php
// Diagnóstico
public function diagnostico($id)
public function guardarDiagnostico($id)

// Presupuesto
public function presupuesto($id)
public function guardarPresupuesto($id)
public function autorizarPresupuesto($id)
public function rechazarPresupuesto($id)

// Servicios externos
public function agregarServicioExterno($id)
public function actualizarServicioExterno($idServicio)
public function eliminarServicioExterno($idServicio)

// Pruebas de calidad
public function agregarPrueba($id)
public function actualizarPrueba($idPrueba)

// Cierre y entrega
public function cerrarOrden($id)
public function guardarCierre($id)

// Reapertura
public function reabrirOrden($id)
```

---

## Prioridad de implementación

1. **Diagnóstico técnico** — ampliar la clasificación con falla encontrada y recomendación
2. **Presupuesto** — costo estimado antes de autorizar la ejecución
3. **Servicios externos** — registro de proveedores y costos
4. **Pruebas de calidad** — validación antes de cerrar
5. **Cierre + entrega** — comprobante de entrega al finalizar
6. **Reapertura** — reabrir por garantía o falla persistente

---

## Mantenimiento preventivo (módulo independiente)

Este sí requiere desarrollo aparte:

- Programación por kilometraje, horas de operación, días, fecha
- Alertas de próximo, vencido, crítico
- Tabla `mantenimiento_preventivo` con: vehículo, tipo, periodicidad, último mantenimiento, próximo mantenimiento
- Dashboard de alertas

## Panel e indicadores (módulo independiente)

- Vehículos disponibles vs en taller
- Órdenes abiertas, atrasadas
- Tiempo promedio de reparación
- Disponibilidad de flota
- Fallas recurrentes
- Productividad del técnico
