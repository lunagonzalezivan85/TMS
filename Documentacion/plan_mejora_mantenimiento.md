# Plan de Mejora — Módulo de Mantenimiento TMS

> **Base:** Análisis TDR v1.1 (`Documentacion/analisis_tdr_mantenimiento.md`)  
> **Estrategia:** De fácil a difícil, priorizando el flujo de mantenimiento de vehículos  
> **Rama activa:** `actualizacion-vehiculos`

---

## Principios del plan

1. **Primero lo que ya tiene base** en el código (menos esfuerzo, más valor inmediato)
2. **Priorizar el flujo de mantenimiento** sobre reportes e integraciones
3. **Cada fase debe dejar funcionalidad usable** y testeable
4. **No romper lo existente** — agregar sobre el código actual

---

## FASE 1 — Fundamentos del flujo (1-2 semanas)

> Objetivo: Completar el ciclo básico de mantenimiento con diagnóstico y pausas

### 1.1 Ampliar estados de OT (Fácil — base existe)
**Estado actual:** PENDIENTE, EN_PROCESO, FINALIZADO, RECHAZADO  
**TDR requiere 14 estados:**

```
Solicitud creada → En revisión → Aprobada → Recibida Taller →
Diagnóstico → Asignada → En proceso → Pendiente repuesto →
Pendiente compra → Servicio externo → Reparación finalizada →
En prueba → Disponible → En garantía → Finalizada → Reabierta
```

**Trabajo:**
- Agregar columna `estado` con todos los valores en `solicitudes` y/o tabla OT
- Crear helper `estados_ot()` con etiquetas, colores e iconos
- Actualizar `OrdenesTrabajo.php` para manejar nuevos estados
- Actualizar vistas `kanban.php`, `show.php`, `dashboard.php`

**Archivos:**
- `app/Helpers/mantenimiento_helper.php` (nuevo)
- `app/Controllers/OrdenesTrabajo.php` (modificar)
- `app/Views/ordenes_trabajo/*.php` (modificar)

---

### 1.2 Diagnóstico estructurado (Fácil-Media — no existe)
**TDR Sección 9:** Registrar falla reportada, falla encontrada, causa raíz, sistema, componente, subclasificación, acción recomendada, tiempo estimado, necesidad de repuestos, servicio externo, evidencias

**Trabajo:**
- Crear tabla `diagnostico_ot` (id_ot, falla_reportada, falla_encontrada, causa_raiz, sistema, componente, accion_recomendada, tiempo_estimado, necesita_repuestos, servicio_externo, evidencias, fecha)
- Crear catálogos técnicos básicos (sistemas, componentes, fallas, causas) usando el sistema de catálogo existente (`CAT-XXXX`)
- Crear vista `ordenes_trabajo/diagnostico.php` con formulario estructurado
- Agregar botón "Diagnosticar" en OT con estado "Recibida Taller"

**Archivos:**
- `app/Models/DiagnosticoOTModel.php` (nuevo)
- `app/Views/ordenes_trabajo/diagnostico.php` (nuevo)
- `app/Controllers/OrdenesTrabajo.php` (agregar método `diagnosticar`)

---

### 1.3 Gestión de pausas (Media — no existe)
**TDR Sección 9:** Toda pausa debe clasificarse con causa obligatoria. El reloj de reparación se detiene durante esperas y se reanuda al volver a "En proceso"

**Tipos de pausa:** espera repuesto, Compras, proveedor, autorización, servicio externo, diagnóstico especializado, disponibilidad mecánico, requerimiento Operaciones, seguridad, otro

**Trabajo:**
- Crear tabla `pausas_ot` (id, id_ot, tipo_pausa, causa, comentario, fecha_inicio, fecha_fin, duracion_minutos)
- Agregar botones "Pausar" y "Reanudar" en `realizar.php`
- Al pausar: registrar motivo, detener reloj, cambiar estado a "Pendiente repuesto" o "Servicio externo"
- Al reanudar: calcular duración, volver a "En proceso", reiniciar reloj
- Mostrar historial de pausas en `show.php`

**Archivos:**
- `app/Models/PausaOTModel.php` (nuevo)
- `app/Views/ordenes_trabajo/partials/pausas.php` (nuevo)
- `app/Controllers/OrdenesTrabajo.php` (agregar métodos `pausar`, `reanudar`)
- `app/Views/ordenes_trabajo/realizar.php` (modificar)

---

## FASE 2 — Ciclo completo de OT (2-3 semanas)

> Objetivo: Cerrar el ciclo: pruebas, entrega, garantía, reincidencia y cierre automático

### 2.1 Pruebas de OT (Media — no existe)
**TDR Sección 11:** Taller ejecuta inspección visual, prueba estática y de carretera. Prueba rechazada devuelve a "En proceso"

**Trabajo:**
- Crear tabla `pruebas_ot` (id, id_ot, tipo_prueba, resultado, observaciones, responsable, fecha)
- Vista `ordenes_trabajo/prueba.php` con checklist de inspección
- Si aprobada → estado "Disponible"
- Si rechazada → estado "En proceso" con comentario obligatorio
- El mecánico debe registrar descripción de corrección antes de finalizar

**Archivos:**
- `app/Models/PruebaOTModel.php` (nuevo)
- `app/Views/ordenes_trabajo/prueba.php` (nuevo)
- `app/Controllers/OrdenesTrabajo.php` (agregar método `realizarPrueba`)

---

### 2.2 Entrega y recepción (Fácil — no existe)
**TDR Sección 11:** Operaciones confirma recepción antes de garantía

**Trabajo:**
- Botón "Entregar" en OT con estado "Disponible"
- Operaciones confirma recepción con firma/simple click
- Al confirmar → estado "En garantía", inicia contador de días

**Archivos:**
- `app/Controllers/OrdenesTrabajo.php` (agregar método `entregar`)
- `app/Views/ordenes_trabajo/show.php` (modificar — agregar botón)

---

### 2.3 Garantía y reincidencia (Media — no existe)
**TDR Sección 11:** Garantía 3-5 días configurables. Misma falla durante garantía = reincidencia → reabre OT. Sin reincidencia → cierre automático.

**Trabajo:**
- Crear tabla `garantia_ot` (id, id_ot, dias_garantia, fecha_inicio, fecha_fin, estado, es_reincidencia)
- Configurar días de garantía en parámetros (default 5)
- Al entrar en garantía: calcular fecha_fin = fecha_inicio + días
- Cronjob o validación en cada acceso: si fecha_fin < hoy y sin reincidencia → "Finalizada"
- Botón "Reportar reincidencia" durante garantía → reabre OT, estado "Reabierta"
- Validar que la reincidencia sea misma falla/sistema/componente

**Archivos:**
- `app/Models/GarantiaOTModel.php` (nuevo)
- `app/Controllers/OrdenesTrabajo.php` (agregar métodos `iniciarGarantia`, `reportarReincidencia`)
- `app/Config/Services.php` o helper para cierre automático

---

### 2.4 Timestamps automáticos (Media — parcial)
**TDR Sección 12:** Marcas T0-T11 para medir todos los tiempos del ciclo

| Marca | Desde → Hasta | Indicador |
|-------|---------------|-----------|
| T0 | Solicitud creada | Inicio ciclo |
| T1 | Solicitud → Aprobación | Tiempo aprobación |
| T2 | Aprobación → Recepción Taller | Transferencia |
| T3 | Recepción → Fin diagnóstico | Tiempo diagnóstico |
| T4 | Inicio reparación → Fin reparación | MTTR |
| T5 | Pausa → Reinicio | Tiempo improductivo |
| T6 | Solicitud repuesto → Disponible | Lead time repuesto |
| T7 | Envío Compras → Recepción SAG | Gestión Compras |
| T8 | Fin reparación → Prueba aprobada | Tiempo pruebas |
| T9 | Prueba aprobada → Recepción Operaciones | Tiempo entrega |
| T10 | Entrega → Fin garantía | Garantía |
| T11 | Reporte inicial → Equipo disponible | Downtime total |

**Trabajo:**
- Crear tabla `timestamps_ot` (id, id_ot, marca, fecha_hora, estado_en_momento)
- Disparar registro automático en cada cambio de estado
- Crear helper `registrar_timestamp($id_ot, $marca)` llamado desde el controlador
- Mostrar línea de tiempo en `show.php`

**Archivos:**
- `app/Models/TimestampOTModel.php` (nuevo)
- `app/Views/ordenes_trabajo/partials/linea_tiempo.php` (nuevo)
- `app/Controllers/OrdenesTrabajo.php` (modificar todos los cambios de estado)

---

## FASE 3 — Mantenimiento preventivo (1-2 semanas)

> Objetivo: Programar y controlar MP por fecha, kilometraje u horómetro

### 3.1 Tabla y modelo de MP (Fácil — no existe)
**TDR Sección 13:** Programar MP por fecha, kilometraje y/o horómetro. Estados: programado, ejecutado a tiempo, anticipado, pendiente, vencido, reprogramado

**Trabajo:**
- Crear tabla `mantenimiento_preventivo`:
  ```
  id, id_vehiculo, tipo_mp, frecuencia_tipo (fecha/km/horometro),
  frecuencia_valor, ultima_ejecucion, proxima_ejecucion, tolerancia,
  estado, observaciones, activo
  ```
- Crear `MantenimientoPreventivoModel.php`
- Crear controlador `MantenimientoPreventivo.php`
- CRUD completo: listar, crear, editar, eliminar, reprogramar

**Archivos:**
- `app/Models/MantenimientoPreventivoModel.php` (nuevo)
- `app/Controllers/MantenimientoPreventivo.php` (nuevo)
- `app/Views/mantenimiento_preventivo/index.php` (nuevo)
- `app/Views/mantenimiento_preventivo/form.php` (nuevo)
- `app/Views/mantenimiento_preventivo/show.php` (nuevo)

---

### 3.2 Cálculo automático de próxima ejecución (Fácil)
**Trabajo:**
- Al registrar MP: calcular `proxima_ejecucion` según frecuencia
  - Por fecha: ultima_ejecucion + frecuencia_valor días
  - Por km: ultima_ejecucion_km + frecuencia_valor km
  - Por horómetro: ultima_ejecucion_h + frecuencia_valor horas
- Comparar con kilometraje/horómetro actual del vehículo para detectar vencidos
- Marcar estado automáticamente: programado, pendiente, vencido

---

### 3.3 Alertas de MP vencido (Fácil — base existe)
**Trabajo:**
- Reutilizar el sistema de notificaciones del layout (`GMVNotifications`)
- Agregar endpoint `MantenimientoPreventivo::getAlertasMP()` similar a `getAlertasDocumentos()`
- Badge en campana de notificaciones
- Al hacer clic → redirige al MP del vehículo

**Archivos:**
- `app/Controllers/MantenimientoPreventivo.php` (agregar método `getAlertasMP`)
- `app/Views/layouts/main.php` (agregar fetch de alertas MP al `loadDocAlerts`)

---

### 3.4 Ejecución de MP y conversión a OT (Media)
**Trabajo:**
- Botón "Ejecutar MP" → crea OT automática con tipo "preventivo"
- Registrar fecha/km/horómetro de ejecución
- Recalcular próxima ejecución
- Estados: ejecutado_a_tiempo, anticipado, reprogramado (con motivo)

---

## FASE 4 — Dashboard y KPI (2-3 semanas)

> Objetivo: Tablero ejecutivo con semáforos y 40 KPI

### 4.1 Dashboard de mantenimiento (Media — base existe)
**TDR Sección 16:** Disponibilidad, utilización, equipos en Taller, MTTR, downtime, OT por estado, cumplimiento MP, presupuesto vs real, reincidencia, efectividad mecánicos

**Trabajo:**
- Mejorar `ordenes_trabajo/dashboard.php` con:
  - Cards de KPI con semáforos (verde/alerta/crítico)
  - Gráficos de OT por estado, por mecánico, por mes
  - Tabla de equipos en taller con causa de indisponibilidad
  - Indicadores con fuente identificada (TMS o SAG)

**Semáforos iniciales:**
| KPI | Verde | Alerta | Crítico |
|-----|-------|--------|---------|
| Disponibilidad flota | ≥95% | 90-94.9% | <90% |
| Cumplimiento MP | ≥95% | 85-94.9% | <85% |
| Reincidencia | ≤3% | 3.1-7% | >7% |
| First Time Fix | ≥95% | 90-94.9% | <90% |
| Presupuesto vs Real | ≤100% | 100.1-110% | >110% |
| OT dentro SLA | ≥95% | 85-94.9% | <85% |
| MP vencidos | 0 | 1-3 | >3 |

---

### 4.2 Cálculo de KPI operativos (Media — no existe)
**TDR Sección 15:** 40 KPI, los operativos se calculan con datos TMS

**Priorizar primero los de fuente TMS puro:**
1. % Disponibilidad de Flota
2. MTTR
3. Tiempo de diagnóstico
4. Tiempo efectivo de reparación
5. OT pendientes / en proceso / finalizadas
6. Backlog y edad promedio
7. % Cumplimiento MP
8. MP vencidos
9. % Preventivo vs Correctivo
10. % Reincidencia
11. First Time Fix Rate
12. OT en garantía
13. Top fallas recurrentes
14. Tiempo aprobación Operaciones
15. Tiempo detenido por causa

**Trabajo:**
- Crear `app/Models/KpiModel.php` con métodos por indicador
- Crear endpoint AJAX `OrdenesTrabajo::getKpi()` que devuelva JSON
- Renderizar en dashboard con ApexCharts (ya existe en el proyecto)

---

### 4.3 Alertas y escalamiento (Media — base existe)
**TDR Sección 17:** 8 eventos con primer nivel y escalamiento

| Evento | Primer nivel | Escalamiento |
|--------|-------------|--------------|
| P1 sin aprobar | Operaciones | Resp. Operaciones / Gerencia |
| P1 sin diagnóstico | Jefe Taller | Operaciones / Controller / Gerencia |
| Repuesto atrasado SAG | Compras/Bodega | Resp. Compras / Gerencia |
| OT sin movimiento | Jefe Taller | Operaciones / Gerencia |
| MP vencido | Taller | Operaciones / Gerencia |
| Reincidencia | Jefe Taller | Controller / Gerencia |
| Presupuesto excedido | Controller | Gerencia / Dirección |
| Integración SAG sin actualizar | TI/Admin | Resp. TI / Gerencia |

**Trabajo:**
- Crear `app/Models/AlertaModel.php`
- Cronjob o validación en cada carga de página
- Push al sidebar de notificaciones existente
- Niveles de escalamiento por días sin atención

---

## FASE 5 — Integración TMS-SAG (3-4 semanas)

> Objetivo: Consultar repuestos, existencias, compras y costos desde SAG en la OT

### 5.1 Llave TMS_OT_ID (Fácil — no existe)
**TDR Sección 5.2:** Identificador único de OT (formato OT-2026-00125) que se registra en SAG

**Trabajo:**
- Agregar columna `tms_ot_id` a tabla de OT con formato auto-generado
- Propagar en solicitudes de repuestos SAG
- Validar asociaciones correctas

---

### 5.2 Consulta de artículos SAG desde OT (Media — base existe)
**TDR Sección 10.2:** Consultar código, descripción, existencia, reservada, disponible, bodega

**Trabajo:**
- Ya existe `InvProductosModel.php` — crear vista de búsqueda integrada en la OT
- Modal "Buscar repuesto" dentro de `realizar.php`
- Mostrar: código, descripción, existencia, disponible, bodega
- Registrar necesidad de repuesto vinculada a la OT

---

### 5.3 Normalización de estados SAG → TMS (Media)
**TDR Sección 10.4:**

| SAG | TMS |
|-----|-----|
| Solicitud creada | Repuesto solicitado |
| Sin existencia / a Compras | Pendiente de compra |
| OC emitida | Ordenado |
| Recepción registrada | Recibido |
| Salida/entrega a Taller | Entregado a Taller |

**Trabajo:**
- Crear helper `normalizar_estado_sag($estado_sag)` → devuelve estado TMS
- Mostrar estados de compra en la OT (solo lectura, desde SAG)
- No permitir doble digitación

---

### 5.4 Consulta de costos SAG (Difícil)
**TDR Sección 14:** SAG es fuente oficial de costos. TMS consulta y presenta (solo lectura).

**Trabajo:**
- Consulta de costos por OT desde SAG via `TMS_OT_ID`
- Estimación técnica TMS diferenciada del costo real SAG
- Variación estimado vs real
- KPI económicos: costo por equipo, costo por km, costo por viaje, presupuesto vs real

---

## FASE 6 — Reportes y optimización (2-3 semanas)

> Objetivo: Reportes específicos del TDR y parámetros configurables

### 6.1 Reportes de mantenimiento (Media)
**TDR Sección 19:**
- OT por estado, prioridad, antigüedad, equipo y responsable
- Equipos en Taller y causa de indisponibilidad
- Backlog y edad del backlog
- Cumplimiento preventivo
- Productividad y efectividad de mecánicos
- Top fallas y reincidencias
- Historial técnico completo por equipo
- Downtime por causa y responsable

### 6.2 Parámetros configurables (Fácil)
**TDR Sección 20.2:** Prioridades, SLA, garantía, catálogos, semáforos y alertas

**Trabajo:**
- Crear tabla `parametros_mantenimiento` (clave, valor, descripción)
- Vista de configuración en `configuracion/mantenimiento.php`
- SLA por tipo de OT, días de garantía, umbrales de semáforos

### 6.3 Bitácora de auditoría completa (Media — base existe)
**TDR Sección 12:** Auditar todos los cambios con usuario, rol, estado anterior/nuevo, comentario, motivo

**Trabajo:**
- Ampliar `HistorialOrdenTrabajoModel` para registrar todos los campos
- Mostrar historial completo en `show.php`

### 6.4 UX móvil (Baja)
- Carga de fotos desde cámara
- Interfaz responsive optimizada para campo
- Captura de evidencias en solicitud desde móvil

---

## Cronograma resumido

| Fase | Duración | Entregable principal |
|------|----------|---------------------|
| **Fase 1** — Fundamentos | 1-2 sem | Estados ampliados + diagnóstico + pausas |
| **Fase 2** — Ciclo completo | 2-3 sem | Pruebas + garantía + reincidencia + timestamps |
| **Fase 3** — Preventivo | 1-2 sem | MP por fecha/km/horómetro + alertas |
| **Fase 4** — Dashboard y KPI | 2-3 sem | 40 KPI + semáforos + alertas escalamiento |
| **Fase 5** — Integración SAG | 3-4 sem | TMS_OT_ID + consulta repuestos + costos |
| **Fase 6** — Reportes | 2-3 sem | Reportes TDR + parámetros + auditoría + móvil |

**Total estimado:** 11-17 semanas

---

## Orden de ejecución recomendado

```
Fase 1.1 → Estados OT          ████████░░  (base existe, rápido)
Fase 1.2 → Diagnóstico          ░░░░░░░░░░  (nuevo, medio)
Fase 1.3 → Pausas               ░░░░░░░░░░  (nuevo, medio)
Fase 2.1 → Pruebas              ░░░░░░░░░░  (nuevo, medio)
Fase 2.2 → Entrega              ░░░░░░░░░░  (nuevo, fácil)
Fase 2.3 → Garantía             ░░░░░░░░░░  (nuevo, medio)
Fase 2.4 → Timestamps           ░░░░░░░░░░  (parcial, medio)
Fase 3.1 → Tabla MP             ░░░░░░░░░░  (nuevo, fácil)
Fase 3.2 → Cálculo próximo      ░░░░░░░░░░  (nuevo, fácil)
Fase 3.3 → Alertas MP           ░░░░░░░░░░  (base existe, fácil)
Fase 3.4 → Ejecutar MP → OT     ░░░░░░░░░░  (nuevo, medio)
Fase 4.1 → Dashboard            ░░░░░░░░░░  (base existe, medio)
Fase 4.2 → KPI operativos       ░░░░░░░░░░  (nuevo, medio)
Fase 4.3 → Alertas escalamiento ░░░░░░░░░░  (base existe, medio)
Fase 5.1 → TMS_OT_ID            ░░░░░░░░░░  (nuevo, fácil)
Fase 5.2 → Consulta SAG         ░░░░░░░░░░  (base existe, media)
Fase 5.3 → Estados SAG → TMS   ░░░░░░░░░░  (nuevo, media)
Fase 5.4 → Costos SAG           ░░░░░░░░░░  (nuevo, difícil)
Fase 6.1 → Reportes             ░░░░░░░░░░  (nuevo, media)
Fase 6.2 → Parámetros           ░░░░░░░░░░  (nuevo, fácil)
Fase 6.3 → Auditoría            ░░░░░░░░░░  (base existe, media)
Fase 6.4 → UX móvil             ░░░░░░░░░░  (nuevo, baja)
```
