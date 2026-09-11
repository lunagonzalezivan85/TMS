# 📋 Análisis de Feedback — Módulo Registro de Combustible

**Fecha:** 2026-06-17
**Módulo:** Registro de Combustible (`RegistroCombustible`)
**Áreas involucradas:** Controller, Service, Model, Vistas, JS embebido, SQL Server (SAG)

---

## 1. Visión Global del Cambio

El negocio requiere endurecer el control sobre los registros de combustible para evitar manipulación por parte del operador de bomba y garantizar que las anomalías pasen por una revisión de supervisor antes de impactar SQL Server (SAG). Además, se busca eliminar el doble-submit y preparar el sistema para notificaciones por correo.

---

## 2. Inventario de Archivos Involucrados

| Rol | Archivo | Observación |
|-----|---------|-------------|
| Controller | `app/Controllers/RegistroCombustible.php` | `store()`, `update()`, `crearRegistro` delega a Service. |
| Service | `app/Services/RegistroCombustibleService.php` | `crearRegistro()`, `actualizarRegistro()`, `enviarSAG()`. |
| Model | `app/Models/RegistroCombustibleModel.php` | Validaciones, `marcarEnvioSAG()`, `getPendientesSAG()`. |
| Vista (form) | `app/Views/registro_combustible/form.php` | Wizard de 4 pasos; JS embebido de ~600 líneas. |
| Vista (index) | `app/Views/registro_combustible/index.php` | Listado de registros; falta bandeja de supervisor. |
| Rutas | `app/Config/Routes.php` | Grupo `registro-combustible`. |
| Helper Email | `app/Helpers/EmailHelper.php` | Ya existe `sendEmail()`, `sendEmailWithTemplate()`. |
| Config Email | `app/Config/Email.php` | Plantillas y configuración SMTP. |
| SQL Server | `InvProductosModel` | `insertarEncabezadoCombustible()`, `insertarDetalleCombustible()`. |
| BD Local | `database/migrations/v1.0.0_estructura_inicial.sql` | Tabla `registro_combustible` ya tiene `estado`, `enviado`, `numero_ingreso_sag`. |

---

## 3. Desglose de Feedback → Requerimientos

### 3.1 FB-01 — Doble submit al presionar el botón N veces

**Problema:** El formulario de registro es un submit tradicional (`method="POST"`). Aunque el botón `#btn-guardar` inicia deshabilitado, una vez habilitado el usuario puede hacer clic múltiples veces o refrescar la página tras submit, generando registros duplicados en MySQL.

**Código afectado:**
- `app/Views/registro_combustible/form.php:433` — `<button type="submit" id="btn-guardar">`.
- No hay handler `onsubmit` en el `<form>` ni lógica de debounce/deshabilitado post-submit.

**Solución propuesta:**
1. Agregar `onsubmit="deshabilitarBotonGuardar(this)"` al `<form>`.
2. En el JS, función que deshabilite `#btn-guardar` y muestre spinner inmediatamente.
3. Considerar token de idempotencia (`idempotency_key`) en el controller para rechazar POST duplicados dentro de una ventana de tiempo (ej. 60 segundos con misma placa + km + litros).

**Impacto:** Sólo frontend + controller. Bajo riesgo.

---

### 3.2 FB-02 — Ocultar la alerta al operador

**Problema:** En `calcularRendimiento()` (form.php:~918) se muestra explícitamente una alerta amarilla (`#rendimiento-alerta`) cuando el rendimiento está por debajo del promedio o cuando hay diferencia de galones. El negocio detectó que al ver la alerta, el operador puede modificar el kilometraje o la cantidad de litros para "maquillar" los números y ocultar un posible robo de combustible.

**Código afectado:**
- `app/Views/registro_combustible/form.php:274-276` — `rendimiento-alerta`.
- `app/Views/registro_combustible/form.php:427-429` — `rendimiento-alerta2` (duplicada en resumen).
- `app/Views/registro_combustible/form.php:1020` — muestra/oculta la alerta según cálculos.

**Solución propuesta:**
1. **No mostrar la alerta al operador** en el paso 3 ni en el paso 4 del wizard.
2. Los cálculos de rendimiento deben seguir ejecutándose en background (para alimentar `estado` y la bandeja del supervisor), pero el operador solo ve el resumen de datos sin banderas de advertencia.
3. El indicador de estado (APROBADO / BLOQUEADO) puede mostrarse sutilmente en el paso 4, sin detallar el motivo exacto.
4. Las observaciones auto-generadas (`actualizarObservacionesBloqueado`) deben dejar de ser un textarea visible para el operador; en su lugar, el sistema las guarda internamente.

**Impacto:** Cambio de UX en el wizard. Los cálculos no se tocan; solo la presentación.

---

### 3.3 FB-03 — Si hay alerta, NO enviar a SQL Server; crear pantalla de supervisor

**Problema:** Actualmente `crearRegistro()` (Service:~122) guarda el registro en MySQL y **inmediatamente** invoca `enviarSAG()` (Service:~153), sin importar si `estado = 'BLOQUEADO'`. Esto significa que una orden anómala ya impacta SQL Server antes de ser revisada.

**Código afectado:**
- `app/Services/RegistroCombustibleService.php:153` — `$this->enviarSAG($nuevoId, $registroGuardado);` llamado incondicionalmente.
- `app/Controllers/RegistroCombustible.php:288-327` — `store()` recibe el resultado y redirige.

**Solución propuesta (flujo de 2 pasos):**

```
OPERADOR registra → MySQL (estado = BLOQUEADO, enviado = 0) → NO SAG
SUPERVISOR revisa  → MySQL (estado = APROBADO, enviado = 1) → SÍ SAG
```

1. **Service `crearRegistro()`:**
   - Si `estado == 'BLOQUEADO'`, saltear `enviarSAG()` por completo.
   - Dejar `enviado = 0`.
   - Guardar en un campo (o historial) el motivo de bloqueo.

2. **Nueva bandeja de supervisor:**
   - Nueva ruta: `GET registro-combustible/supervisor`.
   - Nueva vista: `registro_combustible/supervisor.php` (tabla de registros bloqueados).
   - Nuevas acciones:
     - `POST supervisor/aprobar/(:num)` → cambia estado a `APROBADO`, ejecuta `enviarSAG()`.
     - `POST supervisor/rechazar/(:num)` → cambia estado a `RECHAZADO` (o deja `BLOQUEADO` + observación).
   - Nueva ruta en `Routes.php` y métodos en `RegistroCombustible.php`.

3. **Seguridad:**
   - Agregar permiso `registro-combustible/supervisor` en el sistema de acceso (tabla `acceso` / `permisos`).
   - El operador NO debe poder editar un registro bloqueado para forzar aprobación.

4. **Notificaciones:**
   - Al bloquearse, invocar helper de email para notificar al supervisor (pendiente de configuración).

**Impacto:** Alto. Cambia el flujo crítico del negocio. Requiere nueva UI y permisos.

---

### 3.4 FB-04 — Helper de envío de correo (pendiente configuración)

**Estado actual:** Ya existe `app/Helpers/EmailHelper.php` con funciones reutilizables. No es necesario re-inventar; solo crear una función de conveniencia específica.

**Solución propuesta:**
1. Crear/extendir `app/Helpers/EmailHelper.php` con:
   ```php
   function notificarSupervisorRegistroBloqueado(int $registroId, array $datosRegistro): array
   ```
2. La función debe:
   - Leer destinatario(s) del supervisor desde config o BD (tabla de usuarios con rol supervisor).
   - Usar `sendEmailWithTemplate()` con una nueva plantilla `emails/registro_bloqueado`.
   - En modo desarrollo (sin SMTP configurado), hacer `log_message` del contenido.
3. Crear plantilla de vista: `app/Views/emails/registro_bloqueado.php` (HTML simple con datos del vehículo, litros, km, motivo).
4. **Pendiente:** hasta que el negocio entregue credenciales SMTP, la función queda operativa en modo log (no envía real).

**Impacto:** Medio. No rompe funcionalidad existente.

---

### 3.5 FB-05 — Evitar duplicados en SQL Server (SAG)

**Problema:** `enviarSAG()` (Service:~208) ya intenta evitar duplicados de encabezado verificando `numero_ingreso_sag > 0`. Sin embargo, el detalle (`insertarDetalleCombustible`) se inserta **siempre**, incluso en reintentos. Si el mismo registro se reenvía, se acumulan líneas de detalle duplicadas bajo el mismo encabezado.

**Código afectado:**
- `app/Services/RegistroCombustibleService.php:256-267` — detalle siempre insertado.
- `app/Models/InvProductosModel.php` — no se muestra aquí, pero asumimos que `insertarDetalleCombustible` es un INSERT directo.

**Solución propuesta:**
1. **Doble candado:**
   - Si `numero_ingreso_sag > 0` **Y** `enviado == 1`, retornar inmediatamente sin tocar SQL Server (ya fue enviado exitosamente).
   - Si `numero_ingreso_sag > 0` **Y** `enviado == 0`, es un reintento: reutilizar encabezado pero verificar si el detalle ya existe antes de insertar.
2. **Opción práctica (sin cambiar SPs de SQL Server):**
   - Antes de `insertarDetalleCombustible`, consultar en SQL Server si ya existe un detalle con el mismo `numero_ingreso` + `codigo_producto = 'CO-0000007'`.
   - Si existe, omitir inserción.
   - Alternativa más simple: encapsular `enviarSAG()` dentro de una transacción o bloqueo a nivel de aplicación (session lock / file lock por `registro_id`) para evitar doble envío concurrente.

**Impacto:** Medio. Requiere revisar `InvProductosModel` para agregar validación de existencia de detalle.

---

## 4. Riesgos y Dependencias

| ID | Riesgo / Dependencia | Severidad | Mitigación |
|----|----------------------|-----------|------------|
| R1 | Si se ocultan las alertas al operador, el supervisor puede recibir muchos registros bloqueados por "falsos positivos" (primer ingreso de vehículo, tanque grande, etc.). | Media | Mantener umbrales de bloqueo ajustables (configurables en `Config/RegistroCombustible.php`). |
| R2 | El flujo de supervisor introduce demora en el envío al SAG; el área contable puede notar latencia. | Media | Agregar badge/estado visible en el index para que el contable sepa qué está pendiente de supervisor. |
| R3 | Cambiar permisos requiere tocar la tabla de accesos (`acceso`) y posiblemente seeders. | Baja | Documentar el nuevo permiso y ejecutar un seeder puntual. |
| R4 | El JS del wizard está embebido en la vista (~600 líneas); es propenso a acumular deuda. | Media | Hacer cambios quirúrgicos sin migrar todo a archivo JS externo (ese refactor es fuera de alcance de este feedback). |
| R5 | Bloqueo concurrente: dos supervisores pueden aprobar el mismo registro simultáneamente y generar doble envío a SAG. | Media | Usar `FOR UPDATE` o bloqueo de sesión antes de llamar `enviarSAG()`; o marcar `enviado = 1` con UPDATE condicional. |
| D1 | Se requiere confirmar si el negocio quiere que un registro `RECHAZADO` se pueda editar o solo cancelar. | Baja | Definir en la bandeja del supervisor solo Aprobar / Rechazar (sin editar). |

---

## 5. Próximos Pasos (Propuesta de Ejecución)

| Orden | Tarea | Responsable | Esfuerzo estimado |
|-------|-------|-------------|-------------------|
| 1 | Desactivar alerta visual en el wizard y ocultar observaciones auto-generadas al operador. | Frontend (form.php) | 1h |
| 2 | Agregar protección contra doble-submit en el wizard (deshabilitar botón + token opcional). | Frontend + Controller | 1h |
| 3 | Modificar `crearRegistro()` para saltear `enviarSAG()` cuando `estado == 'BLOQUEADO'`. | Service | 30m |
| 4 | Crear bandeja de supervisor (rutas, controller, vista, permiso). | Backend + Frontend | 3-4h |
| 5 | Implementar aprobación por supervisor que dispare `enviarSAG()` y marque `enviado = 1`. | Service + Controller | 1h |
| 6 | Agregar control anti-duplicado en `enviarSAG()` (detalle ya existente o `enviado == 1`). | Service + Model SAG | 1h |
| 7 | Crear función `notificarSupervisorRegistroBloqueado()` en helper y plantilla email. | Helper + Vista email | 1h |
| 8 | Validación end-to-end (registrar con anomalía, verificar que no va a SAG, supervisor aprueba, verificar que sí va, sin duplicados). | QA / Testing | 2h |

**Total estimado:** ~10-12 horas de desarrollo + testing.

---

## 6. Notas Técnicas Adicionales

- **Estado actual de `registro_combustible.estado`:** `ENUM('APROBADO','BLOQUEADO')`. Si se requiere estado `RECHAZADO`, habrá que alterar la tabla o agregar un campo adicional (`aprobacion_estado`).
- **Campo `enviado`:** ya existe (`0/1`). Es suficiente para saber si ya fue enviado a SAG.
- **Campo `numero_ingreso_sag`:** ya existe. Sirve como llave de idempotencia.
- **Permiso de supervisor:** Recomendado crear `registro-combustible/supervisor` en el sistema de accesos.
- **Email:** El sistema ya usa `Config\Services::email()` con reintentos. La nueva función debe aprovechar la infraestructura existente.
