# Análisis TDR Módulo de Mantenimiento GCM v1.1 vs Sistema TMS Actual

> **Fecha:** 10 de septiembre de 2026  
> **Documento base:** `Documentacion/TDR_TMS_Modulo_Mantenimiento_GCM_v1_1.pdf`  
> **Sistema actual:** CodeIgniter 4 — `C:\xampp\htdocs\GMV\`

---

## 1. Resumen del TDR

El TDR define el módulo de **Mantenimiento de Flota** para el TMS, con integración principal al sistema **SAG** (inventario, compras y costos). El principio central es:

> **TMS** administra la necesidad, ejecución y desempeño del mantenimiento.  
> **SAG** administra inventario, compras y costos.

### Actores definidos
| Rol | Sistema | Responsabilidad |
|-----|---------|-----------------|
| Conductor / Solicitante | TMS | Reportar avería, equipo, descripción y evidencia |
| Operaciones | TMS | Validar, priorizar, clasificar y aprobar |
| Jefe de Taller | TMS | Crear/asociar OT, asignar mecánicos, supervisar |
| Mecánico / Técnico | TMS | Diagnosticar, ejecutar, registrar pausas y corrección |
| Bodega | SAG | Administrar existencia, reserva, recepción y despacho |
| Compras | SAG | Gestionar adquisición, proveedor, OC y seguimiento |
| Finanzas / Controller | SAG + TMS | Presupuesto, costos, variaciones |
| Administrador TMS | TMS | Catálogos, permisos, SLA, parámetros y alertas |

---

## 2. Estado actual del sistema vs TDR

### 2.1 Lo que YA existe en el sistema

| # | Funcionalidad TDR | Estado | Archivos actuales |
|---|-------------------|--------|-------------------|
| 1 | **Solicitudes de mantenimiento** | ✅ Existe | `Solicitudes.php`, `solicitudes/create.php`, `show.php`, `index.php`, `nuevo_wizard.php` |
| 2 | **Órdenes de Trabajo (OT)** | ✅ Existe | `OrdenesTrabajo.php`, `ordenes_trabajo/create.php`, `show.php`, `realizar.php`, `dashboard.php`, `kanban.php`, `calendario.php` |
| 3 | **Estados y transiciones básicas** | ✅ Parcial | `SolicitudModel.php`, `HistorialOrdenTrabajoModel.php` |
| 4 | **Clasificación técnica** | ✅ Parcial | `ordenes_trabajo/clasificacion.php`, `TiposProblema.php` |
| 5 | **Asignación de mecánicos** | ✅ Existe | `ordenes_trabajo/realizar.php`, `show.php` |
| 6 | **Vista Kanban de OT** | ✅ Existe | `ordenes_trabajo/kanban.php` |
| 7 | **Calendario de OT** | ✅ Existe | `ordenes_trabajo/calendario.php` |
| 8 | **Dashboard de OT** | ✅ Existe | `ordenes_trabajo/dashboard.php` |
| 9 | **Materiales/Repuestos (básico)** | ✅ Parcial | `Materiales.php`, `MaterialesModel.php`, `MaterialesTrabajoModel.php` |
| 10 | **Historial de OT** | ✅ Existe | `HistorialOrdenTrabajo.php`, `historial_orden_trabajo/` |
| 11 | **Gestión de vehículos** | ✅ Existe | `Vehiculos.php` (completo), `vehiculos/` (12 vistas) |
| 12 | **Documentos de vehículos** | ✅ Existe | `vehiculos/documentos.php`, `DocumentacionVehiculoModel.php` |
| 13 | **Alertas de documentos por vencer** | ✅ Existe | `Vehiculos::getAlertasDocumentos()`, notificaciones en layout |
| 14 | **Gestión de conductores** | ✅ Existe | `Conductores.php` (completo), `conductores/` (10 vistas) |
| 15 | **Registro de combustible** | ✅ Existe | `RegistroCombustible.php`, `registro_combustible/` (11 vistas) |
| 16 | **Roles y permisos** | ✅ Existe | `Rol.php`, `Roles.php`, `Acceso.php` |
| 17 | **Catálogos** | ✅ Existe | `Catalogo.php`, `CatalogoModel.php` |
| 18 | **Reportes básicos** | ✅ Existe | `Reportes.php`, `reportes/` |
| 19 | **Movimientos (inventario SAG)** | ✅ Existe | `Movimientos.php`, `MovimientosModel.php`, `InvProductosModel.php`, `InvComprasEModel.php` |
| 20 | **Lectura de bomba** | ✅ Existe | `LecturaBomba.php` |
| 21 | **Portal de conductores** | ✅ Existe | `PortalConductores.php` |
| 22 | **Asignación de vehículos** | ✅ Existe | `AsignacionVehiculos.php` |

### 2.2 Lo que NO existe o está incompleto

| # | Funcionalidad TDR | Estado | Brecha detectada |
|---|-------------------|--------|------------------|
| 1 | **Motor de estados con timestamps automáticos** | ⚠️ Parcial | No hay reloj de reparación que se detenga durante pausas. Faltan timestamps T0-T11 definidos en el TDR |
| 2 | **Gestión de pausas con causa obligatoria** | ❌ No existe | No hay registro de pausas (espera repuesto, Compras, proveedor, autorización, servicio externo, etc.) |
| 3 | **Diagnóstico estructurado** | ❌ No existe | Falta registro de: falla encontrada, causa raíz, sistema, componente, subclasificación, acción recomendada, tiempo estimado |
| 4 | **Integración TMS-SAG con TMS_OT_ID** | ⚠️ Parcial | Hay modelos de SAG (`InvProductosModel`, `InvComprasEModel`, `MovimientosModel`) pero no hay llave `TMS_OT_ID` vinculando transacciones |
| 5 | **Consulta de artículos/existencias SAG desde TMS** | ⚠️ Parcial | Existen modelos pero no hay vista integrada en la OT para consultar disponibilidad en tiempo real |
| 6 | **Estados normalizados SAG → TMS** | ❌ No existe | Falta mapeo: Solicitud SAG → "Repuesto solicitado", OC → "Ordenado", Recepción → "Recibido", Despacho → "Entregado a TMS" |
| 7 | **Mantenimiento preventivo** | ❌ No existe | No hay módulo de MP por fecha, kilometraje u horómetro con estados (programado, ejecutado, vencido, reprogramado) |
| 8 | **Garantía y reincidencia** | ❌ No existe | No hay período de garantía configurable (3-5 días), ni detección de reincidencia que reabra OT |
| 9 | **Cierre automático sin reincidencia** | ❌ No existe | No hay cierre automático al vencer garantía |
| 10 | **Pruebas (estática/carretera) con aprobación/rechazo** | ❌ No existe | No hay etapa de prueba con resultado aprobado/rechazado que retorne a "En proceso" |
| 11 | **Dashboard ejecutivo con semáforos** | ⚠️ Parcial | Hay `dashboard.php` pero no tiene los KPI con semáforos definidos (verde/alerta/crítico) |
| 12 | **KPI del módulo (40 indicadores)** | ❌ No existe | No hay cálculo de los 40 KPI definidos: MTTR, MTBF, % disponibilidad, % utilización, First Time Fix, reincidencia, etc. |
| 13 | **Alertas y escalamiento** | ⚠️ Parcial | Hay notificaciones de documentos pero no alertas de: P1 sin aprobar, OT sin movimiento, MP vencido, reincidencia, presupuesto excedido |
| 14 | **Costos y presupuesto** | ❌ No existe | No hay estimación técnica vs costo real SAG, ni variación presupuestaria |
| 15 | **Bitácora de auditoría completa** | ⚠️ Parcial | Hay `HistorialOrdenTrabajoModel` pero no audita todos los cambios de estado con usuario, rol, estado anterior/nuevo, comentario, motivo |
| 16 | **Catálogo técnico TMS** | ⚠️ Parcial | Hay `TiposProblema` pero falta catálogo de: sistemas, componentes, fallas, causas y soluciones |
| 17 | **SLA configurables** | ❌ No existe | No hay SLA por tipo de OT ni control de % OT dentro de SLA |
| 18 | **Carga de fotos/videos desde móvil** | ⚠️ Parcial | Hay carga de documentos pero no captura directa desde cámara móvil |
| 19 | **Exportación de reportes** | ⚠️ Parcial | Hay exportación básica pero no los reportes específicos del TDR |
| 20 | **Backlog y edad de backlog** | ❌ No existe | No hay reporte de OT abiertas/no finalizadas con antigüedad |
| 21 | **Productividad y efectividad de mecánicos** | ❌ No existe | No hay cálculo de horas efectivas vs disponibles, ni First Time Fix por mecánico |
| 22 | **Downtime por causa y responsable** | ❌ No existe | No hay registro de tiempo detenido por motivo de pausa |
| 23 | **Top fallas recurrentes** | ❌ No existe | No hay ranking de fallas por frecuencia |
| 24 | **Parámetros configurables (SLA, garantía, semáforos)** | ❌ No existe | No hay pantalla de configuración de parámetros del módulo |

---

## 3. Análisis por secciones del TDR

### 3.1 Flujo de Solicitud y OT (Sección 7)

**TDR define 11 etapas:**
1. Solicitud → 2. Revisión → 3. Recepción Taller → 4. Diagnóstico → 5. Consulta SAG → 6. Solicitud SAG → 7. Reparación → 8. Prueba → 9. Entrega → 10. Garantía → 11. Cierre

**Estado actual:** Existen solicitudes y OT, pero el flujo está incompleto:
- ✅ Etapas 1-3: Solicitudes → revisión → creación de OT
- ⚠️ Etapa 4: Diagnóstico no estructurado (falta falla encontrada, causa raíz, sistema/componente)
- ❌ Etapas 5-6: No hay consulta SAG integrada en la OT ni solicitud formal vinculada
- ⚠️ Etapa 7: Reparación existe pero sin control de pausas
- ❌ Etapas 8-11: Pruebas, entrega, garantía y cierre automático no existen

### 3.2 Estados y transiciones (Sección 8)

**TDR define 14 estados:** Solicitud creada → En revisión → Aprobada → Recibida Taller → Diagnóstico → Asignada → En proceso → Pendiente repuesto → Pendiente compra → Servicio externo → Reparación finalizada → En prueba → Disponible → En garantía → Finalizada

**Estado actual:** Hay estados básicos (PENDIENTE, EN_PROCESO, FINALIZADO, RECHAZADO) pero faltan:
- Pendiente repuesto / Pendiente compra
- Servicio externo
- Reparación finalizada
- En prueba
- Disponible
- En garantía
- Reabierta (por reincidencia)

### 3.3 Diagnóstico, reparación y pausas (Sección 9)

**TDR requiere:**
- Diagnóstico: falla reportada, falla encontrada, causa probable/raíz, sistema, componente, subclasificación, acción recomendada, tiempo estimado, necesidad de repuestos, servicio externo, evidencias
- Reparación: mecánico principal y auxiliares, inicio, pausas, reinicios, fin, actividades ejecutadas, descripción de corrección
- Pausas clasificadas: espera repuesto, Compras, proveedor, autorización, servicio externo, diagnóstico especializado, disponibilidad mecánico, requerimiento Operaciones, seguridad, Otro

**Estado actual:** ❌ No existe estructura de diagnóstico ni gestión de pausas

### 3.4 Gestión de repuestos con SAG (Sección 10)

**TDR requiere:**
- Consulta desde TMS: código, descripción, unidad, existencia, reservada, disponible, bodega, solicitud SAG, estado compra, OC, proveedor, fechas prometida/recepción/despacho, costo real
- Normalización de estados SAG → TMS
- Llave TMS_OT_ID en todas las transacciones de mantenimiento

**Estado actual:** ⚠️ Hay modelos de SAG (`InvProductosModel`, `InvComprasEModel`, `MovimientosModel`) pero no están integrados en el flujo de OT

### 3.5 KPI y Dashboard (Secciones 15-16)

**TDR define 40 KPI** con fórmulas y fuente (TMS o SAG), más 7 semáforos con umbrales verde/alerta/crítico

**Estado actual:** ❌ No existe cálculo de KPI ni dashboard con semáforos

### 3.6 Alertas y escalamiento (Sección 17)

**TDR define 8 eventos de alerta** con primer nivel y escalamiento

**Estado actual:** ⚠️ Solo hay alertas de documentos por vencer

### 3.7 Mantenimiento preventivo (Sección 13)

**TDR requiere:** Programar MP por fecha, kilometraje y/o horómetro con estados: programado, ejecutado a tiempo, anticipado, pendiente, vencido, reprogramado

**Estado actual:** ❌ No existe

---

## 4. Puntos de mejora priorizados

### 🔴 Prioridad Alta (Core del módulo)

1. **Motor de estados con timestamps automáticos**
   - Implementar los 14 estados del TDR con transiciones válidas
   - Registrar timestamps T0-T11 en cada cambio de estado
   - Reloj de reparación que se detiene durante pausas

2. **Diagnóstico estructurado**
   - Crear tabla `diagnostico_ot` con: falla_reportada, falla_encontrada, causa_raiz, sistema, componente, subclasificacion, accion_recomendada, tiempo_estimado, necesita_repuestos, servicio_externo, evidencias
   - Crear catálogo técnico: sistemas, componentes, fallas, causas, soluciones

3. **Gestión de pausas**
   - Crear tabla `pausas_ot` con: id_ot, tipo_pausa, fecha_inicio, fecha_fin, causa, comentario
   - Tipos: espera_repuesto, compras, proveedor, autorizacion, servicio_externo, diagnostico_especializado, disponibilidad_mecanico, requerimiento_operaciones, seguridad, otro
   - El reloj de reparación se detiene al iniciar pausa y se reanuda al cerrar

4. **Pruebas, garantía y reincidencia**
   - Etapa de prueba con resultado (aprobado/rechazado)
   - Período de garantía configurable (3-5 días)
   - Detección de reincidencia (misma falla en garantía) → reabre OT
   - Cierre automático sin reincidencia al vencer garantía

5. **Mantenimiento preventivo**
   - Crear tabla `mantenimiento_preventivo` con: equipo, tipo, frecuencia (fecha/km/horómetro), última ejecución, próxima ejecución, tolerancia, estado
   - Estados: programado, ejecutado_a_tiempo, anticipado, pendiente, vencido, reprogramado
   - Alertas de MP vencido

### 🟡 Prioridad Media (Integración y KPI)

6. **Integración TMS-SAG con TMS_OT_ID**
   - Propagar `TMS_OT_ID` en solicitudes de repuestos SAG
   - Consulta de artículos/existencias desde la OT
   - Normalización de estados SAG → TMS
   - Vista de solicitudes/OC/recepciones/despachos por OT

7. **Dashboard ejecutivo con semáforos**
   - Implementar los 40 KPI del TDR
   - Semáforos con umbrales (verde/alerta/crítico)
   - Indicadores económicos con identificación de fuente SAG

8. **Alertas y escalamiento**
   - Implementar los 8 eventos de alerta del TDR
   - Notificaciones en el botón de notificaciones del layout
   - Escalamiento por niveles

9. **Costos y presupuesto**
   - Estimación técnica TMS diferenciada del costo real SAG
   - Variación estimado vs real
   - Consulta de costos por OT desde SAG (solo lectura)

10. **Bitácora de auditoría completa**
    - Auditar todos los cambios de estado con: OT, equipo, usuario, rol, estado anterior, estado nuevo, fecha, hora, comentario, motivo, evidencia

### 🟢 Prioridad Baja (Reportes y optimización)

11. **Reportes específicos del TDR**
    - Backlog y edad de backlog
    - Cumplimiento preventivo
    - Productividad y efectividad de mecánicos
    - Top fallas y reincidencias
    - Downtime por causa y responsable
    - Lead time de repuestos y desempeño de proveedores

12. **Parámetros configurables**
    - Pantalla de configuración: prioridades, SLA, garantía, semáforos, alertas
    - Catálogos operativos editables

13. **Mejoras de UX móvil**
    - Carga de fotos/videos desde cámara móvil
    - Interfaz responsive optimizada para campo

14. **Exportación de reportes**
    - Exportar a Excel/PDF los reportes específicos del TDR

---

## 5. Arquitectura sugerida para nuevos desarrollos

### Nuevas tablas requeridas

```
diagnostico_ot
  - id, id_ot, falla_reportada, falla_encontrada, causa_raiz
  - id_sistema, id_componente, id_subclasificacion
  - accion_recomendada, tiempo_estimado, necesita_repuestos
  - servicio_externo, evidencias, fecha_creacion

pausas_ot
  - id, id_ot, tipo_pausa, causa, comentario
  - fecha_inicio, fecha_fin, duracion_minutos

catalogo_tecnico_sistemas
  - id, nombre, descripcion, activo

catalogo_tecnico_componentes
  - id, id_sistema, nombre, descripcion, activo

catalogo_tecnico_fallas
  - id, id_componente, nombre, descripcion, activo

catalogo_tecnico_causas
  - id, id_falla, nombre, descripcion, activo

catalogo_tecnico_soluciones
  - id, id_causa, nombre, descripcion, activo

mantenimiento_preventivo
  - id, id_vehiculo, tipo, frecuencia_tipo (fecha/km/horometro)
  - frecuencia_valor, ultima_ejecucion, proxima_ejecucion
  - tolerancia, estado, observaciones

pruebas_ot
  - id, id_ot, tipo_prueba (estatica/carretera)
  - resultado (aprobado/rechazado), observaciones
  - responsable, fecha

garantia_ot
  - id, id_ot, dias_garantia, fecha_inicio, fecha_fin
  - estado (activa/reabierta/finalizada), reincidencia (bool)

parametros_mantenimiento
  - id, clave, valor, descripcion
  -- SLA por tipo, días de garantía, umbrales semáforos, etc.

auditoria_estados_ot
  - id, id_ot, estado_anterior, estado_nuevo
  - usuario_id, rol, fecha, hora, comentario, motivo, evidencia
```

### Modificaciones a tablas existentes

```
solicitudes
  + ubicacion, condicion_movilidad, urgencia_percibida
  + kilometraje_horometro, evidencias (JSON/fotos)

ordenes_trabajo (o tabla equivalente)
  + tms_ot_id (formato OT-2026-XXXXX)
  + estado_actual (ampliar a 14 estados del TDR)
  + mecanico_principal_id, mecanicos_auxiliares (JSON)
  + descripcion_correccion
  + estimacion_tecnica_costo
  + fecha_inicio_reparacion, fecha_fin_reparacion
  + tiempo_efectivo_minutos
```

---

## 6. Conclusión

El sistema TMS actual tiene una **base sólida** con solicitudes, OT, vehículos, conductores, combustible y roles implementados. Sin embargo, el **módulo de mantenimiento está incompleto** respecto al TDR v1.1:

- **30% del TDR está implementado** (solicitudes básicas, OT básica, vehículos, documentación)
- **40% está parcial** (estados, materiales, historial, dashboard)
- **30% no existe** (pausas, diagnóstico estructurado, preventivo, garantía, KPI, semáforos, integración SAG, alertas)

El desarrollo debe priorizar el **motor de estados con pausas**, **diagnóstico estructurado**, **garantía/reincidencia** y **mantenimiento preventivo** antes de avanzar a la integración SAG y los KPI.
