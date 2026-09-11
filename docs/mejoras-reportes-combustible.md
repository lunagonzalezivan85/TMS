# Mejoras — Reporte de Combustible (`/reportes/combustible`)

**Vista:** `app/Views/reportes/combustible.php`
**Controlador:** `app/Controllers/Reportes.php`
**Orden:** De lo más fácil a lo más difícil.

---

## ✅ Checklist de Cambios

### 1. [ ] Switch global: Rendimiento en KM/Galones o KM/Litros
**Dificultad:** Media

**Descripción:**
Agregar un switch/toggle en la parte superior (junto al filtro de fechas) que permita alternar entre ver los rendimientos en **KM/Galones** o **KM/Litros**. Este switch debe afectar:
- Tab de Rendimiento (columna Rend. KM/L → KM/Gln)
- Tab de Comparativo (Teórico, Promedio, % Eficiencia)
- KPIs si aplica

**Archivos afectados:**
- `app/Views/reportes/combustible.php` — agregar toggle UI + lógica JS
- `app/Controllers/Reportes.php` — `ajaxRendimiento()` y `ajaxComparativo()` deben devolver ambos valores (KM/L y KM/Gln) para que el JS decida cuál mostrar
- `exportarExcel()` — agregar columna correspondiente según el modo

**Notas técnicas:**
- Factor de conversión: 1 galón = 3.78541 litros
- `rendimiento_km_gln = rendimiento_km_l * 3.78541`
- El switch puede ser un botón toggle en el formulario de filtro que envíe un parámetro `unidad=galones|litros`

---

### 2. [ ] Tab de Rendimiento: Detalle de registros + Gráfico de rendimiento en el tiempo
**Dificultad:** Alta

**Descripción:**
En la tabla de rendimiento, al hacer clic en un vehículo, mostrar el detalle de sus registros de combustible con:
- Fecha del registro
- Kilómetro inicial (kilometraje_anterior)
- Kilómetro final (kilometraje_actual)
- Kilómetros recorridos
- Litros despachados
- Galones despachados
- Rendimiento del registro (KM/L y KM/Gln)

Además, agregar un **gráfico de línea** que muestre el rendimiento del vehículo en el tiempo (eje X = fechas, eje Y = rendimiento KM/Gln o KM/L según switch).

**Archivos afectados:**
- `app/Controllers/Reportes.php` — nuevo método `ajaxRendimientoDetalle($id_vehiculo)` que devuelva los registros individuales con fecha, km_inicial, km_final, litros, galones, rendimiento
- `app/Views/reportes/combustible.php` — agregar modal o panel expandible con tabla de detalle + contenedor para ApexCharts
- JS — renderizar gráfico de línea con los datos del detalle

**Notas técnicas:**
- Query: `SELECT rc.fecha_registro, rc.kilometraje_anterior, rc.kilometraje_actual, rc.cantidad_litros, rc.rendimiento FROM registro_combustible rc WHERE rc.id_vehiculo = ? ORDER BY rc.fecha_registro ASC`
- El gráfico debe respetar el switch de unidad (KM/Gln vs KM/L)
- Considerar usar un modal Bootstrap o un collapse debajo de la fila

---

### 3. [ ] Tab Comparativo: Cambiar leyendas y quitar columna "Real"
**Dificultad:** Fácil

**Descripción:**
En la tabla del Comparativo Teórico vs Real:
1. Cambiar leyenda de **"Teórico"** → **"Teórica KM/Gln"**
2. Cambiar leyenda de **"Promedio"** → **"Promedio KM/Gln"**
3. **Quitar la columna "Real"** (es un rendimiento en litros, no es relevante ya que está el promedio en galones)

**Archivos afectados:**
- `app/Views/reportes/combustible.php` — línea ~306: actualizar headers de la tabla comparativo
- `app/Views/reportes/combustible.php` — línea ~316: quitar el `<td>` que muestra `rendimiento_real_calculado`
- `app/Controllers/Reportes.php` — `ajaxComparativo()`: cambiar cálculo de `rendimiento_promedio_registrado` de `AVG(rc.rendimiento)` a `SUM(km_recorridos) / SUM(cantidad_litros) * 3.78541` (para que sea KM/Gln)
- `exportarExcel()` — sección comparativo: quitar columna "Rend Real" y renombrar headers

**Notas técnicas:**
- El promedio actual usa `AVG(rc.rendimiento)` que tiene valores basura (mismo bug corregido en reportes/vehiculos)
- El nuevo promedio debe ser: `SUM(km_actual - km_anterior) / SUM(cantidad_litros)` → esto da KM/L, multiplicar por 3.78541 para KM/Gln
- `% Eficiencia` debe recalcularse basándose en el nuevo promedio vs teórico

**Cambios concretos en la vista (renderTable, sección comparativo):**
```javascript
// ANTES:
'<th class="text-end">Teórico</th><th class="text-end">Promedio</th><th class="text-end">Real</th><th class="text-end">Dif.</th>...'

// DESPUÉS:
'<th class="text-end">Teórica KM/Gln</th><th class="text-end">Promedio KM/Gln</th><th class="text-end">Dif.</th>...'
```

---

### 4. [ ] Tab de Registros: Corregir cálculo de Diferencia
**Dificultad:** Fácil

**Descripción:**
En los registros de combustible, la diferencia se está calculando mal. Debe ser:

**Diferencia = Teórico − Despachado**

Donde:
- **Teórico** = lo que debería haberse despachado según el rendimiento teórico del vehículo y los KM recorridos
- **Despachado** = la cantidad real de litros/galones despachada

Fórmula: `teórico = (km_recorridos / rendimiento_teorico)` → litros teóricos que debería haber consumido
`diferencia = teórico - despachado` → positivo = consumió menos de lo esperado (eficiente), negativo = consumió más

**Archivos afectados:**
- `app/Controllers/Reportes.php` — identificar dónde se calcula la diferencia en los registros de combustible y corregir la fórmula
- `app/Views/reportes/combustible.php` — actualizar etiqueta de la columna si es necesario

**Notas técnicas:**
- Esta corrección podría aplicar a la tabla de Turnos (diferencia entre consumo medidor vs despachado) o a una vista de registros individuales
- **Confirmar con el usuario** a qué tabla específica se refiere con "registros de combustible" (¿tab de Top 10? ¿una vista de registros individuales que no existe aún?)

---

### 5. [ ] Tab de Consumo por Turno: Agregar nombre de bomba y usuario
**Dificultad:** Fácil-Media

**Descripción:**
En la tabla de Reporte de Consumo por Turno, agregar dos columnas:
1. **Nombre de la bomba** que se cierra (identificador de la bomba/dispositivo)
2. **Usuario de la bomba** (quién operó/abrió el turno)

**Archivos afectados:**
- `app/Controllers/Reportes.php` — `ajaxTurnos()`: agregar JOIN con tabla de bombas y tabla de usuarios para traer el nombre de la bomba y el nombre del usuario
- `app/Views/reportes/combustible.php` — `renderTable()` sección turnos: agregar columnas "Bomba" y "Usuario" en el header y en cada fila
- `exportarExcel()` — sección turnos: agregar columnas correspondientes

**Notas técnicas:**
- Necesita identificar el nombre de la tabla de bombas y el campo de relación con `lectura_bomba`
- Necesita identificar el nombre de la tabla de usuarios y el campo de relación con `lectura_bomba` (probablemente `id_usuario` o `usuario_id`)
- Query tentativa:
```sql
SELECT lb.*, 
    b.nombre AS nombre_bomba,
    u.nombre AS nombre_usuario
FROM lectura_bomba lb
LEFT JOIN bombas b ON lb.id_bomba = b.id
LEFT JOIN usuarios u ON lb.id_usuario = u.id
```
- **Confirmar** nombres reales de tablas y campos en la base de datos

---

## Orden de ejecución sugerido

| Orden | Cambio | Dificultad |
|-------|--------|------------|
| 1° | **#3** Comparativo: leyendas + quitar Real | Fácil |
| 2° | **#4** Registros: corregir diferencia | Fácil |
| 3° | **#5** Turnos: agregar bomba y usuario | Fácil-Media |
| 4° | **#1** Switch KM/Gln vs KM/L | Media |
| 5° | **#2** Rendimiento: detalle + gráfico | Alta |

---

## Pendientes de confirmación

- **#4:** ¿A qué tabla específica se refiere "registros de combustible"? ¿Es una nueva vista o un tab existente?
- **#5:** ¿Cuáles son los nombres reales de las tablas `bombas` y `usuarios` en la base de datos? ¿Qué campos las relacionan con `lectura_bomba`?
- **#1:** ¿El switch debe persistir en la URL o solo en memoria (JS)?
