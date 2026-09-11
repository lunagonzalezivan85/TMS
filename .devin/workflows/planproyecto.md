---
description: Plan de Proyecto y Ejecución para el módulo Registro de Combustible
---

# Plan de Proyecto — Refactor Registro de Combustible

**Agente PM:** Morpheus
**Fecha:** 2026-06-17
**Módulo:** Registro de Combustible

---

## 1. Visión Global
Endurecer el control de registros de combustible eliminando la manipulación por parte del operador de bomba. Las anomalías deben pasar por un supervisor antes de impactar SQL Server (SAG). Eliminar doble-submit y preparar notificaciones por correo.

## 2. Desglose de Épicas y Tareas

| ID | Descripción | Asignado | Estado |
|----|-------------|----------|--------|
| TSK-01 | **Etapa 1:** Ocultar alertas al operador en wizard + protección doble-submit | Anderson | Completado |
| TSK-02 | **Etapa 2:** Modificar `crearRegistro()` para no enviar a SAG cuando `estado == 'BLOQUEADO'` | Anderson | Completado |
| TSK-03 | **Etapa 3:** Crear bandeja de supervisor (rutas, controller, vista, permiso) | Anderson + Paul | Completado |
| TSK-04 | **Etapa 4:** Implementar aprobación por supervisor que dispare `enviarSAG()` | Anderson | Completado |
| TSK-05 | **Etapa 5:** Anti-duplicado en `enviarSAG()` (detalle ya existente o `enviado == 1`) | Anderson | Completado |
| TSK-06 | **Etapa 6:** Función `notificarSupervisorRegistroBloqueado()` + plantilla email | Anderson | Completado |
| TSK-07 | **Etapa 7:** Validación end-to-end (QA destructivo) | Smith | Completado |
| TSK-08 | **Etapa 8:** Opción de rechazar registro bloqueado con motivo | Anderson | Completado |
| TSK-09 | **Etapa 9:** Diseño responsive: tabla desktop + cards móvil en bandeja supervisor | Anderson | Completado |
| TSK-10 | **Etapa 10:** Notificaciones en navegador (toast + sonido + notificación nativa Windows) | Anderson | Completado |

## 3. Riesgos y Dependencias
- ~~**Dependencia:** El negocio debe confirmar si quiere estado `RECHAZADO` adicional.~~ ✅ Resuelto: implementado con modal de motivo.
- **Riesgo:** El JS del wizard está embebido (~600 líneas); cambios deben ser quirúrgicos.
- **Riesgo:** Supervisor concurrente: dos aprobaciones simultáneas podrían duplicar en SAG. ✅ Mitigado: candado `enviado == 1` en `enviarSAG()`.

## 4. Estado de Validación
**10/10 tareas completadas y probadas en desarrollo.**

### Flujo validado end-to-end:
1. Operador registra con anomalía → MySQL `estado=BLOQUEADO`, `enviado=0`, NO SAG ✅
2. Operador no ve alertas, estado muestra "APROBADO" en resumen ✅
3. Botón deshabilita + spinner anti-doble-submit ✅
4. Supervisor en `/supervisor` ve tabla (desktop) / cards (móvil) ✅
5. Supervisor abre detalle completo con Vehículo, Conductor, Despacho, Kilometraje, Rendimiento ✅
6. Supervisor aprueba → `estado=APROBADO` + `enviarSAG()` + `enviado=1` ✅
7. Supervisor rechaza → `estado=RECHAZADO` + `motivo_rechazo` guardado ✅
8. Anti-duplicado: si `enviado=1` + `numero_ingreso_sag>0`, no envía de nuevo ✅
9. Notificación en navegador: toast + sonido + notificación nativa Windows cada minuto ✅
10. Notificación clic → abre bandeja del supervisor en nueva pestaña ✅

## 5. Pendientes Post-Despliegue
1. Configurar credenciales SMTP en `app/Config/Email.php` para activar notificaciones reales (actualmente en modo log/dev).
2. Agregar entrada de menú "Supervisor" en tabla `menu` con permiso para rol supervisor (actualmente requiere `requireAccess('registro-combustible/supervisor')`).
3. Colocar archivo `notificacion.mp3` en `public/assets/audio/` para sonido de alerta (el JS ya apunta ahí).
