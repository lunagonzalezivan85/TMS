# Diagrama de Flujo — Proceso Completo de Mantenimiento de Vehículos

## Diagrama de Flujo General

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         INICIO DEL PROCESO                              │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                    ¿ORIGEN DE LA SOLICITUD?                             │
└──────────────┬──────────────────────┬───────────────────┬───────────────┘
               │                      │                   │
               ▼                      ▼                   ▼
     [OPERACIONES/CONDUCTOR]   [MANT. PREVENTIVO]   [JEFATURA DE TALLER]
     Reporta falla o necesidad  Sistema genera alerta  Detecta problema en
     de mantenimiento           de mantenimiento       inspección rutinaria
               │                      │                   │
               │                      │                   │
               ▼                      ▼                   ▼
┌──────────────────────────────────────────────────────────────────────────┐
│              1. SOLICITUD DE MANTENIMIENTO                              │
│                                                                         │
│  • Número de solicitud (auto)                                          │
│  • Fecha y hora                                                        │
│  • Vehículo o equipo                                                   │
│  • Solicitante                                                         │
│  • Conductor asignado                                                  │
│  • Kilometraje / Horas de operación                                    │
│  • Ubicación del vehículo                                              │
│  • Tipo de solicitud:                                                  │
│      - Preventivo / Correctivo / Inspección / Emergencia              │
│      - Accidente / Cambio de llantas / Cambio de aceite               │
│      - Reparación cisterna / Reparación eléctrica / Garantía / Otro   │
│  • Descripción de la falla                                             │
│  • Nivel de prioridad: Baja / Normal / Alta / Urgente / Crítica       │
│  • Evidencias fotográficas                                             │
│  • Documentos adjuntos                                                 │
│  • ¿Puede continuar operando?                                          │
│  • Riesgo asociado                                                     │
│                                                                         │
│  ESTADO: PENDIENTE                                                     │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│              2. CLASIFICACIÓN DE LA SOLICITUD                           │
│                                                                         │
│  Jefe de taller revisa y clasifica:                                     │
│  • Tipo de problema (catálogo)                                         │
│  • Prioridad (1-4)                                                     │
│  • Técnico asignado                                                    │
│  • Fecha de asignación                                                 │
│                                                                         │
│  DECISIÓN: ¿Es mantenible?                                             │
│  ├── SI → Continúa el flujo                                            │
│  └── NO → CANCELAR ORDEN                                               │
│                                                                         │
│  ESTADO: EN_DIAGNOSTICO                                                │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│              3. DIAGNÓSTICO TÉCNICO  [NUEVO]                            │
│                                                                         │
│  Técnico responsable evalúa el vehículo:                                │
│  • Fecha del diagnóstico                                               │
│  • Falla reportada (de la solicitud)                                   │
│  • Falla encontrada (real)                                             │
│  • Componente afectado                                                 │
│  • Causa probable                                                      │
│  • Nivel de severidad: Baja / Media / Alta / Crítica                   │
│  • Riesgo operativo: Bajo / Medio / Alto                               │
│  • Actividades recomendadas                                            │
│  • Repuestos requeridos (lista preliminar)                             │
│  • Servicios externos requeridos (lista preliminar)                    │
│  • Tiempo estimado                                                     │
│  • Costo estimado                                                      │
│  • Evidencias fotográficas                                             │
│                                                                         │
│  RECOMENDACIÓN TÉCNICA:                                                │
│  ├── Reparación inmediata                                              │
│  ├── Programar reparación                                              │
│  ├── Continuar operando con observación                                │
│  ├── Enviar a proveedor externo                                        │
│  ├── Sustituir componente                                              │
│  ├── Dar de baja el vehículo                                           │
│  └── Requiere autorización de gerencia                                 │
│                                                                         │
│  ESTADO: PENDIENTE_AUTORIZACION                                        │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│              4. PRESUPUESTO DE REPARACIÓN  [NUEVO]                      │
│                                                                         │
│  Se elabora presupuesto antes de autorizar:                             │
│  • Mano de obra (horas × costo por hora)                               │
│  • Repuestos (lista con cantidad y costo unitario)                     │
│  • Materiales (insumos adicionales)                                    │
│  • Servicios externos (costo estimado)                                 │
│  • Transporte                                                          │
│  • Costos adicionales                                                  │
│  • Impuestos                                                           │
│  • COSTO TOTAL ESTIMADO                                                │
│                                                                         │
│  ESTADO DEL PRESUPUESTO:                                               │
│  EN_ELABORACION → PENDIENTE_AUTORIZACION                               │
│                                                                         │
│  DECISIÓN DE AUTORIZACIÓN:                                             │
│  ├── AUTORIZADO → Continúa el flujo                                    │
│  ├── RECHAZADO → Volver a diagnóstico o cancelar                       │
│  └── EN_REVISION → Modificar y reenviar                                │
│                                                                         │
│  ESTADO ORDEN: AUTORIZADA                                              │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│              5. EJECUCIÓN DE LA ORDEN DE TRABAJO                        │
│                                                                         │
│  Técnicos ejecutan las actividades autorizadas:                        │
│                                                                         │
│  • Trabajo realizado (descripción)                                     │
│  • Técnico responsable                                                 │
│  • Fecha y hora de inicio                                              │
│  • Fecha y hora de finalización                                        │
│  • Tiempo estimado vs tiempo real                                      │
│  • Kilometraje actual del vehículo                                     │
│  • Horas de trabajo (normales y extraordinarias)                       │
│  • Estado del vehículo post-trabajo:                                   │
│      OPERATIVO / REQUIERE_REVISION / FUERA_DE_SERVICIO                │
│      / PENDIENTE_REPUESTOS                                            │
│  • Observaciones                                                       │
│                                                                         │
│  MATERIALES UTILIZADOS:                                                │
│  • Material (de inventario)                                            │
│  • Cantidad                                                            │
│  • Costo unitario                                                      │
│  • Costo subtotal                                                      │
│                                                                         │
│  DECISIÓN: ¿Requiere servicios externos?                               │
│  ├── SI → Va al paso 6 (Servicios Externos)                            │
│  └── NO → Va al paso 7 (Pruebas de Calidad)                            │
│                                                                         │
│  ESTADO: EN_PROCESO                                                    │
└──────────────┬───────────────────────────────────┬───────────────────────┘
               │                                   │
               ▼                                   ▼
┌──────────────────────────────────┐  ┌──────────────────────────────────────┐
│   ¿Faltan repuestos?             │  │   6. SERVICIOS EXTERNOS  [NUEVO]     │
│                                  │  │                                      │
│   SI → PENDIENTE_REPUESTOS      │  │   Se envía a proveedor externo:      │
│        ↓                         │  │   • Proveedor                        │
│   ¿Llegan repuestos?             │  │   • Tipo de servicio                 │
│   SI → EN_PROCESO               │  │   • Fecha de envío                   │
│   NO → Espera / Cancelar        │  │   • Fecha estimada de retorno        │
│                                  │  │   • Cotización                       │
│   NO → Continúa ejecución       │  │   • Orden de compra                  │
│                                  │  │   • Costo real                       │
└──────────────────────────────────┘  │   • Garantía                         │
                                      │   • Responsable                      │
                                      │                                      │
                                      │   ESTADOS DEL SERVICIO:              │
                                      │   COTIZACION_SOLICITADA              │
                                      │     → COTIZACION_RECIBIDA            │
                                      │       → PENDIENTE_AUTORIZACION       │
                                      │         → AUTORIZADO                 │
                                      │           → ENVIADO_PROVEEDOR        │
                                      │             → EN_PROCESO             │
                                      │               → FINALIZADO           │
                                      │                 → RECIBIDO           │
                                      │                                      │
                                      │   ¿Resultado satisfactorio?          │
                                      │   ├── SI → EN_PROCESO (continúa)     │
                                      │   ├── NO → RECHAZADO                 │
                                      │   │   → Reenviar o nuevo proveedor   │
                                      │   └── GARANTIA → Reenviar al proveed│
                                      │                                      │
                                      │   ESTADO ORDEN: EN_REPARACION_EXTERNA│
                                      └──────────────────┬───────────────────┘
                                                         │
                                                         ▼
┌──────────────────────────────────────────────────────────────────────────┐
│              7. PRUEBAS Y CONTROL DE CALIDAD  [NUEVO]                   │
│                                                                         │
│  Antes de cerrar la orden, se registran pruebas:                       │
│  • Tipo de prueba                                                      │
│  • Técnico que realiza la prueba                                       │
│  • Fecha                                                               │
│  • Hallazgos                                                           │
│  • Actividades pendientes                                              │
│  • Evidencias fotográficas                                             │
│                                                                         │
│  RESULTADO:                                                            │
│  ├── APROBADO → Puede cerrar la orden                                  │
│  ├── APROBADO_CON_OBSERVACIONES → Puede cerrar con notas               │
│  ├── REQUIERE_AJUSTE → Volver a ejecución (paso 5)                     │
│  ├── RECHAZADO → Volver a ejecución (paso 5)                           │
│  └── REQUIERE_NUEVA_REPARACION → Volver a diagnóstico (paso 3)        │
│                                                                         │
│  APROBACIÓN DEL JEFE DE TALLER:                                        │
│  • Usuario que aprueba                                                 │
│  • Fecha de aprobación                                                 │
│                                                                         │
│  VALIDACIONES:                                                         │
│  • No permitir cerrar si hay pruebas pendientes                        │
│  • No permitir cerrar si hay pruebas RECHAZADAS                        │
│    (salvo autorización especial)                                       │
│  • Requerir aprobación del jefe de taller                              │
│                                                                         │
│  ESTADO: PENDIENTE_PRUEBA → FINALIZADA                                 │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│              8. CIERRE DE ORDEN + ENTREGA DEL VEHÍCULO  [NUEVO]         │
│                                                                         │
│  CIERRE:                                                               │
│  • Diagnóstico final                                                   │
│  • Actividades realizadas (resumen)                                    │
│  • Repuestos utilizados (resumen)                                      │
│  • Horas de trabajo totales                                            │
│  • Servicios externos (resumen)                                        │
│  • Resultado de pruebas                                                │
│  • COSTO FINAL (mano de obra + repuestos + materiales + externos)      │
│  • Fecha de finalización                                               │
│  • Recomendaciones                                                     │
│  • Próximo mantenimiento sugerido                                      │
│  • Aprobación del jefe de taller                                       │
│                                                                         │
│  ENTREGA DEL VEHÍCULO:                                                 │
│  • Fecha y hora de entrega                                             │
│  • Kilometraje de salida                                               │
│  • Nivel de combustible                                                │
│  • Condiciones de entrega                                              │
│  • Trabajos realizados (resumen)                                       │
│  • Recomendaciones                                                     │
│  • Pendientes                                                          │
│  • Persona que entrega (técnico/jefe de taller)                        │
│  • Persona que recibe (conductor/operaciones)                          │
│  • Firma o confirmación digital                                        │
│  • Evidencia fotográfica                                               │
│                                                                         │
│  ACCIONES AUTOMÁTICAS:                                                 │
│  • Actualizar kilometraje del vehículo                                 │
│  • Actualizar estado del vehículo (OPERATIVO)                          │
│  • Generar comprobante de entrega (imprimible/PDF)                     │
│  • Registrar próximo mantenimiento si se sugirió                       │
│                                                                         │
│  ESTADO: ENTREGADA                                                     │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│              9. ¿REAPERTURA DE ORDEN?  [NUEVO]                          │
│                                                                         │
│  Motivos de reapertura:                                                │
│  • La falla persiste                                                   │
│  • Se detecta una reparación incompleta                                │
│  • El vehículo retorna por garantía                                    │
│  • Aparece una falla relacionada                                       │
│  • Existen actividades pendientes                                      │
│                                                                         │
│  DECISIÓN: ¿Se reabre la orden?                                        │
│  ├── SI → Registra motivo, usuario, fecha                              │
│  │        → ESTADO: EN_PROCESO (vuelve al paso 5)                      │
│  │        → Se mantiene historial completo                             │
│  └── NO → ORDEN CERRADA DEFINITIVAMENTE                                │
│                                                                         │
│  HISTORIAL:                                                            │
│  • Todas las reaperturas quedan registradas                            │
│  • Se mantiene trazabilidad de cambios                                 │
│  • Se conservan autorizaciones previas                                 │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│              10. HISTORIAL DEL VEHÍCULO                                 │
│                                                                         │
│  Expediente digital completo:                                          │
│  • Solicitudes de mantenimiento                                        │
│  • Órdenes de trabajo                                                  │
│  • Mantenimientos preventivos                                          │
│  • Reparaciones correctivas                                            │
│  • Repuestos utilizados                                                │
│  • Técnicos responsables                                               │
│  • Servicios externos                                                  │
│  • Garantías                                                           │
│  • Costos totales                                                      │
│  • Tiempo fuera de operación                                           │
│  • Fotografías                                                         │
│  • Documentos                                                          │
│  • Componentes reemplazados                                            │
│  • Fallas recurrentes                                                  │
│  • Reaperturas                                                         │
└──────────────────────────────────────────────────────────────────────────┘
```

---

## Escenarios Especiales

### Escenario A: Mantenimiento Preventivo

```
ALERTA DEL SISTEMA
  (por kilometraje / horas / fecha / consumo combustible)
        │
        ▼
  ¿Hay orden abierta para este vehículo?
        ├── SI → Reutilizar orden existente
        │        → Va a paso 2 (Clasificación)
        └── NO → Crear nueva solicitud automática
                  → Tipo: PREVENTIVO
                  → Va a paso 2 (Clasificación)
```

### Escenario B: Emergencia

```
SOLICITUD: Tipo EMERGENCIA, Prioridad CRÍTICA
        │
        ▼
  Flujo abreviado:
  1. Solicitud → Clasificación inmediata
  2. Diagnóstico rápido (puede ser simultáneo)
  3. Presupuesto: puede omitirse con autorización de gerencia
  4. Ejecución inmediata
  5. Pruebas de calidad (obligatorias)
  6. Cierre + Entrega
```

### Escenario C: Reparación Externa Total

```
DIAGNÓSTICO: Recomendación = Enviar a proveedor externo
        │
        ▼
  1. Presupuesto con costo de servicio externo
  2. Autorización
  3. Servicio externo (paso 6) como paso principal
  4. No hay ejecución interna (paso 5 se omite)
  5. Pruebas de calidad al recibir el vehículo
  6. Cierre + Entrega
```

### Escenario D: Presupuesto Rechazado

```
PRESUPUESTO: RECHAZADO
        │
        ▼
  DECISIÓN:
  ├── Volver a diagnóstico (paso 3)
  │   → Modificar recomendación
  │   → Nuevo presupuesto
  ├── Cancelar orden
  │   → ESTADO: CANCELADA
  └── Continuar operando con observación
      → ESTADO: FINALIZADA sin reparación
      → Registrar como pendiente futura
```

### Escenario E: Pruebas Rechazadas

```
PRUEBAS DE CALIDAD: RECHAZADO / REQUIERE_NUEVA_REPARACION
        │
        ▼
  DECISIÓN:
  ├── Volver a ejecución (paso 5)
  │   → Reparar nuevamente
  │   → Nuevas pruebas
  ├── Volver a diagnóstico (paso 3)
  │   → Nuevo diagnóstico
  │   → Nuevo presupuesto
  │   → Nueva ejecución
  └── Autorización especial para cerrar con observaciones
      → Registrar riesgo
      → Cerrar con pendientes
```

### Escenario F: Reapertura por Garantía

```
ORDEN: ENTREGADA
        │
  Vehículo regresa por misma falla
        │
        ▼
  REAPERTURA:
  • Motivo: Garantía
  • Usuario que reabre
  • Fecha
  • Vuelve a EN_PROCESO
  • Se ejecutan reparaciones
  • Si fue servicio externo → verifica garantía del proveedor
  • Nuevas pruebas
  • Nuevo cierre + entrega
```

### Escenario G: Orden Detenida por Repuestos

```
EJECUCIÓN: EN_PROCESO
        │
  Faltan repuestos
        │
        ▼
  ESTADO: PENDIENTE_REPUESTOS
        │
        ▼
  ¿Llegan repuestos?
        ├── SI → EN_PROCESO (continúa ejecución)
        └── NO → Espera
            │
            ¿Tiempo excesivo?
            ├── SI → Escalar a gerencia
            │        → Posible cancelación o sustitución
            └── NO → Mantener en espera
```

---

## Diagrama de Estados de la Orden de Trabajo

```
                    ┌───────────┐
                    │ PENDIENTE │
                    └─────┬─────┘
                          │
                          ▼
                   ┌─────────────────┐
                   │ EN_DIAGNOSTICO  │
                   └───────┬─────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │PENDIENTE_AUTORIZACION│
                └──┬───────────────┬───┘
                   │               │
            ┌──────▼──────┐  ┌─────▼──────┐
            │  AUTORIZADA │  │ RECHAZADA  │
            └──────┬──────┘  └─────┬──────┘
                   │               │
                   ▼               ▼
            ┌─────────────┐  ┌───────────┐
            │ EN_PROCESO  │  │ CANCELADA │
            └──┬───┬──┬───┘  └───────────┘
               │   │  │
               │   │  │ Repuestos
               │   │  ▼
               │   │ ┌─────────────────────┐
               │   │ │PENDIENTE_REPUESTOS  │
               │   │ └──────────┬──────────┘
               │   │            │
               │   │  Servicios │
               │   │  Externos  │
               │   ▼            │
               │ ┌──────────────────────┐
               │ │EN_REPARACION_EXTERNA │
               │ └──────────┬───────────┘
               │            │
               ▼            │
        ┌───────────────────▼──┐
        │  PENDIENTE_PRUEBA    │
        └──────────┬───────────┘
                   │
                   │
            ┌──────▼──────┐
            │ FINALIZADA  │
            └──────┬──────┘
                   │
                   ▼
            ┌─────────────┐
            │ ENTREGADA   │◄──── Reapertura ──┐
            └──────┬──────┘                    │
                   │                           │
                   ▼                           │
            ┌───────────┐                      │
            │  CERRADA  │──────────────────────┘
            └───────────┘   (si reapertura)
```

---

## Resumen

### Proceso completo (10 etapas)

1. **Solicitud de mantenimiento** — Reporte de falla o necesidad (ya existe)
2. **Clasificación** — Jefe de taller asigna tipo, prioridad y técnico (ya existe)
3. **Diagnóstico técnico** — Evaluación técnica, falla encontrada, recomendación (NUEVO)
4. **Presupuesto** — Costo estimado antes de autorizar (NUEVO)
5. **Ejecución** — Trabajo realizado, materiales, horas (ya existe, ampliar)
6. **Servicios externos** — Proveedores externos, cotización, garantía (NUEVO)
7. **Pruebas de calidad** — Validación antes de cerrar (NUEVO)
8. **Cierre + Entrega** — Comprobante de entrega, costo final (NUEVO)
9. **Reapertura** — Por garantía o falla persistente (NUEVO)
10. **Historial** — Expediente digital del vehículo (ampliar)

### Escenarios cubiertos

- **A. Mantenimiento preventivo** — Alerta automática del sistema
- **B. Emergencia** — Flujo abreviado con autorización de gerencia
- **C. Reparación externa total** — Proveedor externo como paso principal
- **D. Presupuesto rechazado** — Volver a diagnóstico o cancelar
- **E. Pruebas rechazadas** — Volver a ejecución o autorización especial
- **F. Reapertura por garantía** — Vuelve a ejecución con historial
- **G. Orden detenida por repuestos** — Espera, escalamiento o cancelación

### Estados de la orden de trabajo (12 estados)

PENDIENTE → EN_DIAGNOSTICO → PENDIENTE_AUTORIZACION → AUTORIZADA → EN_PROCESO → (PENDIENTE_REPUESTOS / EN_REPARACION_EXTERNA) → PENDIENTE_PRUEBA → FINALIZADA → ENTREGADA → CERRADA

Estados especiales: RECHAZADA, CANCELADA, REABIERTA

### Lo que ya existe (no requiere desarrollo)

- Solicitud de mantenimiento
- Clasificación de orden
- Asignación de técnicos
- Registro de trabajo ejecutado
- Materiales utilizados
- Estados básicos (PENDIENTE, EN_PROCESO, FINALIZADA)
- Kanban y calendario
- Historial de cambios

### Lo que se debe agregar (NUEVO)

1. **Diagnóstico técnico** — Fase entre clasificación y presupuesto
2. **Presupuesto** — Costo estimado con detalle de items y autorización
3. **Servicios externos** — Registro de proveedores y control de servicios
4. **Pruebas de calidad** — Validación obligatoria antes del cierre
5. **Cierre + entrega** — Comprobante formal con kilometraje y firmas
6. **Reapertura** — Reabrir orden con motivo e historial
7. **Mantenimiento preventivo** — Programación y alertas (módulo independiente)
8. **Panel e indicadores** — Dashboard de taller con KPIs (módulo independiente)

### Tablas nuevas a crear

- `diagnostico_orden`
- `presupuesto_orden` + `presupuesto_detalle`
- `servicios_externos` + `proveedores_externos`
- `pruebas_calidad_orden`
- `entrega_vehiculo`

### Impacto en el sistema existente

- **Controlador `OrdenesTrabajo.php`** — Agregar ~8 métodos nuevos
- **Vista `show.php`** — Agregar secciones de diagnóstico, presupuesto, servicios externos, pruebas, entrega
- **Dashboard y kanban** — Agregar nuevos estados
- **Base de datos** — 6 tablas nuevas
- **Catálogos** — Proveedores externos, tipos de prueba, tipos de servicio
