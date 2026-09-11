# Cambios en Base de Datos — TMS Mantenimiento

> **Rama:** `actualizacion-vehiculos`  
> **Convención:** Registrar TODO cambio de BD aquí (tablas nuevas, columnas, índices, alteraciones)  
> **Formato:** Fecha | Cambio | Tabla(s) | Tipo (CREATE/ALTER/INDEX) | Archivo SQL/Migration

---

## Registro de cambios

| Fecha | Cambio | Tabla(s) | Tipo | Estado |
|-------|--------|----------|------|--------|
| — | — | — | — | — |

---

## Pendientes (planificados)

### Fase 1 — Fundamentos
- [ ] `diagnostico_ot` — tabla nueva
- [ ] `pausas_ot` — tabla nueva
- [ ] `solicitudes` — ALTER: agregar `ubicacion`, `condicion_movilidad`, `urgencia_percibida`, `kilometraje_horometro`, `evidencias`
- [ ] `ordenes_trabajo` — ALTER: agregar `tms_ot_id`, `mecanico_principal_id`, `descripcion_correccion`, `estimacion_tecnica_costo`, `fecha_inicio_reparacion`, `fecha_fin_reparacion`, `tiempo_efectivo_minutos`

### Fase 2 — Ciclo completo
- [ ] `pruebas_ot` — tabla nueva
- [ ] `garantia_ot` — tabla nueva
- [ ] `timestamps_ot` — tabla nueva

### Fase 3 — Preventivo
- [ ] `mantenimiento_preventivo` — tabla nueva

### Fase 4 — Dashboard y KPI
- [ ] `alertas_mantenimiento` — tabla nueva (sistema de alertas/escalamiento)

### Fase 5 — Integración SAG
- [ ] `ordenes_trabajo` — ALTER: `tms_ot_id` ya agregado en Fase 1
- [ ] `estados_sag_tms` — tabla nueva (mapeo de estados)

### Fase 6 — Reportes
- [ ] `parametros_mantenimiento` — tabla nueva
- [ ] `auditoria_estados_ot` — tabla nueva (ampliación de historial)

---

## Detalle de tablas planificadas

### `diagnostico_ot`
```sql
CREATE TABLE diagnostico_ot (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_ot INT NOT NULL,
    falla_reportada NVARCHAR(MAX),
    falla_encontrada NVARCHAR(MAX),
    causa_raiz NVARCHAR(MAX),
    id_sistema INT NULL,
    id_componente INT NULL,
    id_subclasificacion INT NULL,
    accion_recomendada NVARCHAR(MAX),
    tiempo_estimado INT NULL,          -- en minutos
    necesita_repuestos BIT DEFAULT 0,
    servicio_externo BIT DEFAULT 0,
    evidencias NVARCHAR(MAX) NULL,     -- JSON con rutas de archivos
    fecha_creacion DATETIME DEFAULT GETDATE(),
    creado_por INT NULL,
    FOREIGN KEY (id_ot) REFERENCES ordenes_trabajo(id)
);
```

### `pausas_ot`
```sql
CREATE TABLE pausas_ot (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_ot INT NOT NULL,
    tipo_pausa NVARCHAR(50) NOT NULL,  -- espera_repuesto, compras, proveedor, autorizacion, servicio_externo, diagnostico_especializado, disponibilidad_mecanico, requerimiento_operaciones, seguridad, otro
    causa NVARCHAR(500) NOT NULL,
    comentario NVARCHAR(MAX) NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NULL,
    duracion_minutos INT NULL,
    creado_por INT NULL,
    FOREIGN KEY (id_ot) REFERENCES ordenes_trabajo(id)
);
```

### `pruebas_ot`
```sql
CREATE TABLE pruebas_ot (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_ot INT NOT NULL,
    tipo_prueba NVARCHAR(30) NOT NULL, -- estatica, carretera, visual
    resultado NVARCHAR(20) NOT NULL,   -- aprobado, rechazado
    observaciones NVARCHAR(MAX) NULL,
    responsable INT NULL,
    fecha DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (id_ot) REFERENCES ordenes_trabajo(id)
);
```

### `garantia_ot`
```sql
CREATE TABLE garantia_ot (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_ot INT NOT NULL,
    dias_garantia INT DEFAULT 5,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NULL,
    estado NVARCHAR(20) DEFAULT 'activa', -- activa, reabierta, finalizada
    es_reincidencia BIT DEFAULT 0,
    reincidencia_id_ot INT NULL,  -- si reabre, referencia a nueva OT
    FOREIGN KEY (id_ot) REFERENCES ordenes_trabajo(id)
);
```

### `timestamps_ot`
```sql
CREATE TABLE timestamps_ot (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_ot INT NOT NULL,
    marca NVARCHAR(10) NOT NULL,  -- T0, T1, T2, ... T11
    descripcion NVARCHAR(100) NULL,
    fecha_hora DATETIME NOT NULL,
    estado_en_momento NVARCHAR(50) NULL,
    usuario_id INT NULL,
    FOREIGN KEY (id_ot) REFERENCES ordenes_trabajo(id)
);
```

### `mantenimiento_preventivo`
```sql
CREATE TABLE mantenimiento_preventivo (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_vehiculo INT NOT NULL,
    tipo_mp NVARCHAR(50) NOT NULL,
    frecuencia_tipo NVARCHAR(20) NOT NULL,  -- fecha, km, horometro
    frecuencia_valor INT NOT NULL,
    ultima_ejecucion DATETIME NULL,
    ultima_ejecucion_km DECIMAL(12,2) NULL,
    ultima_ejecucion_h DECIMAL(12,2) NULL,
    proxima_ejecucion DATETIME NULL,
    proxima_ejecucion_km DECIMAL(12,2) NULL,
    proxima_ejecucion_h DECIMAL(12,2) NULL,
    tolerancia INT NULL,  -- días o km según tipo
    estado NVARCHAR(30) DEFAULT 'programado', -- programado, ejecutado_a_tiempo, anticipado, pendiente, vencido, reprogramado
    observaciones NVARCHAR(MAX) NULL,
    activo BIT DEFAULT 1,
    fecha_creacion DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (id_vehiculo) REFERENCES vehiculos(id)
);
```

### `parametros_mantenimiento`
```sql
CREATE TABLE parametros_mantenimiento (
    id INT IDENTITY(1,1) PRIMARY KEY,
    clave NVARCHAR(100) NOT NULL UNIQUE,
    valor NVARCHAR(500) NOT NULL,
    descripcion NVARCHAR(MAX) NULL,
    tipo NVARCHAR(20) DEFAULT 'texto',  -- texto, numero, json, boolean
    activo BIT DEFAULT 1
);
```

### `auditoria_estados_ot`
```sql
CREATE TABLE auditoria_estados_ot (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_ot INT NOT NULL,
    estado_anterior NVARCHAR(50),
    estado_nuevo NVARCHAR(50),
    usuario_id INT NULL,
    rol NVARCHAR(50) NULL,
    fecha DATETIME DEFAULT GETDATE(),
    comentario NVARCHAR(MAX) NULL,
    motivo NVARCHAR(200) NULL,
    evidencia NVARCHAR(MAX) NULL
);
```

### `alertas_mantenimiento`
```sql
CREATE TABLE alertas_mantenimiento (
    id INT IDENTITY(1,1) PRIMARY KEY,
    tipo NVARCHAR(50) NOT NULL,  -- P1_sin_aprobar, P1_sin_diagnostico, repuesto_atrasado, ot_sin_movimiento, mp_vencido, reincidencia, presupuesto_excedido, sag_sin_actualizar
    id_ot INT NULL,
    id_vehiculo INT NULL,
    primer_nivel NVARCHAR(100) NULL,
    escalamiento NVARCHAR(200) NULL,
    dias_sin_atencion INT DEFAULT 0,
    estado NVARCHAR(20) DEFAULT 'activa', -- activa, atendida, escalada
    fecha_creacion DATETIME DEFAULT GETDATE(),
    fecha_atencion DATETIME NULL
);
```

### `estados_sag_tms`
```sql
CREATE TABLE estados_sag_tms (
    id INT IDENTITY(1,1) PRIMARY KEY,
    estado_sag NVARCHAR(100) NOT NULL,
    estado_tms NVARCHAR(100) NOT NULL,
    descripcion NVARCHAR(MAX) NULL,
    activo BIT DEFAULT 1
);
```

---

## ALTERs planificados a tablas existentes

### `solicitudes`
```sql
ALTER TABLE solicitudes ADD
    ubicacion NVARCHAR(200) NULL,
    condicion_movilidad NVARCHAR(50) NULL,
    urgencia_percibida NVARCHAR(20) NULL,
    kilometraje_horometro DECIMAL(12,2) NULL,
    evidencias NVARCHAR(MAX) NULL;
```

### `ordenes_trabajo`
```sql
ALTER TABLE ordenes_trabajo ADD
    tms_ot_id NVARCHAR(30) NULL,           -- formato: OT-2026-00125
    mecanico_principal_id INT NULL,
    mecanicos_auxiliares NVARCHAR(MAX) NULL, -- JSON array de IDs
    descripcion_correccion NVARCHAR(MAX) NULL,
    estimacion_tecnica_costo DECIMAL(12,2) NULL,
    fecha_inicio_reparacion DATETIME NULL,
    fecha_fin_reparacion DATETIME NULL,
    tiempo_efectivo_minutos INT NULL;
```
