-- ========================
-- SISTEMA DE GESTIÓN DE MANTENIMIENTO VEHICULAR (GMV)
-- Versión: 1.0.0
-- Fecha: 2025-01-08
-- Descripción: Estructura inicial de la base de datos
-- ========================

CREATE DATABASE IF NOT EXISTS gestion_mantenimiento;
USE gestion_mantenimiento;

-- ========================
-- EMPRESAS
-- ========================
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    ruc VARCHAR(50),
    direccion TEXT,
    telefono VARCHAR(20),
    correo VARCHAR(100),
    codigo_inicial VARCHAR(5) NOT NULL,   -- Ej: C
    serie_documento VARCHAR(20) NOT NULL, -- Ej: 2024200
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT
);

-- ========================
-- ROLES
-- ========================
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT
);

-- ========================
-- USUARIOS DEL SISTEMA
-- ========================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    correo VARCHAR(100),
    telefono VARCHAR(20),
    id_rol INT NOT NULL,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id),
    FOREIGN KEY (id_rol) REFERENCES roles(id)
);

-- ========================
-- CONDUCTORES
-- ========================
CREATE TABLE conductores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    codigo_consecutivo VARCHAR(50) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    dni VARCHAR(20) NOT NULL UNIQUE,
    fechaIngreso DATE NOT NULL,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

-- ========================
-- VEHÍCULOS
-- ========================
CREATE TABLE vehiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    codigo_consecutivo VARCHAR(50) NOT NULL,
    placa VARCHAR(20) NOT NULL UNIQUE,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    anio YEAR,
    kilometraje INT,
    id_conductor INT,
    estado ENUM('ACTIVO', 'INACTIVO', 'EN REPARACION') DEFAULT 'ACTIVO',
    motivo_inactividad TEXT NULL,
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id),
    FOREIGN KEY (id_conductor) REFERENCES conductores(id)
);

-- ========================
-- HISTORIAL DE ESTADO DE VEHÍCULO
-- ========================
CREATE TABLE historial_estado_vehiculo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    id_vehiculo INT NOT NULL,
    estado ENUM('ACTIVO', 'INACTIVO', 'EN REPARACION') NOT NULL,
    motivo TEXT,
    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATETIME,
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id),
    FOREIGN KEY (id_vehiculo) REFERENCES vehiculos(id)
);

-- ========================
-- TIPOS DE PROBLEMA
-- ========================
CREATE TABLE tipos_problema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

-- ========================
-- SOLICITUDES DE MANTENIMIENTO
-- ========================
CREATE TABLE solicitudes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    codigo_consecutivo VARCHAR(50) NOT NULL,
    id_vehiculo INT NOT NULL,
    id_solicitante INT NOT NULL,
    id_tipo_problema INT NULL,
    descripcion TEXT,
    estado ENUM('PENDIENTE', 'EN PROCESO', 'COMPLETADA') DEFAULT 'PENDIENTE',
    fechaSolicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaCierre DATETIME,
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id),
    FOREIGN KEY (id_vehiculo) REFERENCES vehiculos(id),
    FOREIGN KEY (id_solicitante) REFERENCES usuarios(id),
    FOREIGN KEY (id_tipo_problema) REFERENCES tipos_problema(id)
);

-- ========================
-- MATERIALES
-- ========================
CREATE TABLE materiales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    codigo_consecutivo VARCHAR(50) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    unidad_medida VARCHAR(20),
    costo_unitario DECIMAL(10,2),
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

-- ========================
-- CONSUMO DE MATERIALES
-- ========================
CREATE TABLE consumo_materiales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    codigo_consecutivo VARCHAR(50) NOT NULL,
    id_solicitud INT NOT NULL,
    id_material INT NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    costo_total DECIMAL(10,2),
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id),
    FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id),
    FOREIGN KEY (id_material) REFERENCES materiales(id)
);

-- ========================
-- TRIGGERS: COSTO TOTAL EN CONSUMO DE MATERIALES
-- ========================
DELIMITER //
CREATE TRIGGER trg_consumo_materiales_insert
BEFORE INSERT ON consumo_materiales
FOR EACH ROW
BEGIN
    DECLARE v_costo DECIMAL(10,2);
    SELECT costo_unitario INTO v_costo FROM materiales WHERE id = NEW.id_material;
    SET NEW.costo_total = NEW.cantidad * v_costo;
END //

CREATE TRIGGER trg_consumo_materiales_update
BEFORE UPDATE ON consumo_materiales
FOR EACH ROW
BEGIN
    DECLARE v_costo DECIMAL(10,2);
    SELECT costo_unitario INTO v_costo FROM materiales WHERE id = NEW.id_material;
    SET NEW.costo_total = NEW.cantidad * v_costo;
END //
DELIMITER ;

-- ========================
-- REPORTES GUARDADOS
-- ========================
CREATE TABLE reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    codigo_consecutivo VARCHAR(50) NOT NULL,
    id_usuario INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('PDF', 'EXCEL'),
    parametros JSON,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarioCrea INT,
    usuarioEdita INT,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- ========================
-- TABLA DE CONTROL DE CONSECUTIVOS DETALLADOS
-- ========================
CREATE TABLE consecutivos_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT NOT NULL,
    tabla VARCHAR(50) NOT NULL,
    numero INT NOT NULL,
    codigo VARCHAR(50) NOT NULL,
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_consecutivo (id_empresa, tabla, numero),
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

-- ========================
-- PROCEDIMIENTO PARA GENERAR CÓDIGO SIN HUECOS
-- ========================
DELIMITER //
CREATE PROCEDURE generar_codigo (
    IN p_empresa_id INT,
    IN p_tabla VARCHAR(50),
    OUT p_codigo VARCHAR(50),
    OUT p_numero INT
)
BEGIN
    DECLARE v_codigo_inicial VARCHAR(5);
    DECLARE v_serie_documento VARCHAR(20);

    -- Datos de la empresa
    SELECT codigo_inicial, serie_documento
    INTO v_codigo_inicial, v_serie_documento
    FROM empresas
    WHERE id = p_empresa_id;

    -- Buscar el primer número libre
    SELECT COALESCE(MIN(t1.numero + 1), 1)
    INTO p_numero
    FROM consecutivos_detalle t1
    LEFT JOIN consecutivos_detalle t2
        ON t1.numero + 1 = t2.numero
        AND t1.id_empresa = t2.id_empresa
        AND t1.tabla = t2.tabla
    WHERE t1.id_empresa = p_empresa_id
      AND t1.tabla = p_tabla
      AND t2.numero IS NULL;

    -- Si la tabla está vacía, usar 1
    IF p_numero IS NULL THEN
        SET p_numero = 1;
    END IF;

    -- Formar el código
    SET p_codigo = CONCAT(v_codigo_inicial, v_serie_documento, '-', LPAD(p_numero, 4, '0'));

    -- Guardar el consecutivo
    INSERT INTO consecutivos_detalle (id_empresa, tabla, numero, codigo)
    VALUES (p_empresa_id, p_tabla, p_numero, p_codigo);
END //
DELIMITER ;

-- ========================
-- MENÚ DE NAVEGACIÓN
-- ========================
CREATE TABLE menu (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    icono         VARCHAR(100) NOT NULL,
    menu          VARCHAR(100) NOT NULL,
    id_superior   INT UNSIGNED NULL DEFAULT NULL,
    nivel         TINYINT UNSIGNED NOT NULL DEFAULT 1,
    orden         INT UNSIGNED NOT NULL DEFAULT 0,
    ruta          VARCHAR(255) NULL DEFAULT NULL,
    estado        TINYINT(1) NOT NULL DEFAULT 1,
    usuario_crea  INT UNSIGNED NOT NULL,
    usuario_edita INT UNSIGNED NULL DEFAULT NULL,
    fecha_registra   DATETIME NOT NULL,
    fecha_actualiza  DATETIME NULL DEFAULT NULL,
    CONSTRAINT fk_menu_superior FOREIGN KEY (id_superior) REFERENCES menu(id) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY idx_menu_superior (id_superior),
    KEY idx_menu_nivel    (nivel),
    KEY idx_menu_orden    (orden),
    KEY idx_menu_estado   (estado)
);

-- ========================
-- ACCESOS (PERMISOS ROL-MENÚ)
-- ========================
CREATE TABLE accesos (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_rol              INT UNSIGNED NOT NULL,
    id_menu             INT UNSIGNED NOT NULL,
    estado              TINYINT(1) NOT NULL DEFAULT 1,
    usuario_crea        INT UNSIGNED NOT NULL,
    usuario_actualiza   INT UNSIGNED NULL DEFAULT NULL,
    fecha_registro      TIMESTAMP NOT NULL,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uk_acceso_rol_menu (id_rol, id_menu),
    KEY idx_acceso_rol    (id_rol),
    KEY idx_acceso_menu   (id_menu),
    KEY idx_acceso_estado (estado),
    FOREIGN KEY (id_rol)            REFERENCES roles(id)    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_menu)           REFERENCES menu(id)     ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (usuario_crea)      REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (usuario_actualiza) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ========================
-- CATÁLOGO
-- ========================
CREATE TABLE catalogo (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    codigo            VARCHAR(50) NULL,
    nombre            VARCHAR(255) NOT NULL,
    descripcion       VARCHAR(500) NULL,
    id_superior       INT NULL DEFAULT 0,
    estado            TINYINT(1) NOT NULL DEFAULT 1,
    referencia        VARCHAR(100) NULL,
    nivel             INT NULL DEFAULT 1,
    referencia2       VARCHAR(100) NULL,
    idempresa         INT NOT NULL,
    edicion           INT NULL DEFAULT 0,
    usuario_crea      INT NULL,
    usuario_actualiza INT NULL,
    fecha_registro    DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualiza   DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idempresa)         REFERENCES empresas(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    KEY idx_catalogo_codigo         (codigo),
    KEY idx_catalogo_empresa        (idempresa),
    KEY idx_catalogo_superior       (id_superior),
    KEY idx_catalogo_estado         (estado)
);

-- ========================
-- REGISTRO DE COMBUSTIBLE
-- ========================
CREATE TABLE registro_combustible (
    id                    INT AUTO_INCREMENT PRIMARY KEY,
    id_vehiculo           INT NOT NULL,
    id_direccion          INT NULL,
    id_tipo_motivo        INT NULL,
    fecha_registro        DATETIME NOT NULL,
    kilometraje_anterior  DECIMAL(10,2) NOT NULL,
    kilometraje_actual    DECIMAL(10,2) NOT NULL,
    cantidad_litros       DECIMAL(10,2) NOT NULL,
    medicion              DECIMAL(10,2) NULL,
    combustible_tanque    DECIMAL(10,2) NULL,
    rendimiento           DECIMAL(10,2) NULL,
    rendimiento_promedio  DECIMAL(10,2) NULL,
    tipo                  ENUM('CONSUMO','VENTA') NULL DEFAULT 'CONSUMO',
    nombreCliente         VARCHAR(150) NULL,
    dni                   VARCHAR(50) NULL,
    monto                 DECIMAL(10,2) NULL,
    monto_nio             DECIMAL(10,2) NULL,
    monto_usd             DECIMAL(10,2) NULL,
    observaciones         VARCHAR(500) NULL,
    estado                ENUM('APROBADO','BLOQUEADO') NULL DEFAULT 'APROBADO',
    usuario_crea          VARCHAR(100) NULL,
    usuario_edita         VARCHAR(100) NULL,
    fecha_actualiza       DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_vehiculo)    REFERENCES vehiculos(id)              ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_direccion)   REFERENCES direcciones(id)            ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (id_tipo_motivo) REFERENCES catalogo(id)               ON DELETE SET NULL ON UPDATE CASCADE,
    KEY idx_rc_vehiculo  (id_vehiculo),
    KEY idx_rc_direccion (id_direccion),
    KEY idx_rc_fecha     (fecha_registro),
    KEY idx_rc_tipo      (tipo),
    KEY idx_rc_estado    (estado)
);

-- ========================
-- DOCUMENTACIÓN DE CONDUCTORES
-- ========================
CREATE TABLE documentacion_conductor (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    idConductor       INT NOT NULL,
    tipo_documento    VARCHAR(100) NOT NULL,
    numero_documento  VARCHAR(100) NULL,
    fecha_emision     DATE NULL,
    fecha_vencimiento DATE NOT NULL,
    ruta_documento    VARCHAR(255) NULL,
    observaciones     TEXT NULL,
    estado            ENUM('VIGENTE', 'VENCIDO', 'POR_VENCER') NOT NULL DEFAULT 'VIGENTE',
    notificacion      INT UNSIGNED NULL DEFAULT 0,
    usuario_crea      INT NULL,
    usuario_actualiza INT NULL,
    fechaRegistro     DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaUpdate       DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idConductor) REFERENCES conductores(id) ON DELETE CASCADE ON UPDATE CASCADE,
    KEY idx_doc_conductor  (idConductor),
    KEY idx_doc_estado     (estado),
    KEY idx_doc_vencimiento (fecha_vencimiento)
);

-- ========================
-- DATOS INICIALES: MENÚ
-- ========================
INSERT INTO menu (id, icono, menu, id_superior, nivel, orden, ruta, estado, usuario_crea, fecha_registra) VALUES
-- Nivel 1 (padres)
(1,  'fas fa-tachometer-alt', 'Dashboard',         NULL, 1,  1,  'dashboard',           1, 1, NOW()),
(2,  'fas fa-car',            'Vehículos',          NULL, 1,  2,  '#',                   1, 1, NOW()),
(3,  'fas fa-user-tie',       'Conductores',        NULL, 1,  3,  '#',                   1, 1, NOW()),
(4,  'fas fa-chart-bar',      'Reportes',           NULL, 1,  4,  '#',                   1, 1, NOW()),
(5,  'fas fa-tools',          'Órdenes de Trabajo', NULL, 1,  5,  'ordenes-trabajo',     1, 1, NOW()),
(6,  'fas fa-clipboard-list', 'Solicitudes',        NULL, 1,  6,  'solicitudes',         1, 1, NOW()),
(7,  'fas fa-warehouse',      'Inventario',         NULL, 1,  7,  '#',                   1, 1, NOW()),
(8,  'fas fa-cog',            'Configuración',      NULL, 1,  8,  '#',                   1, 1, NOW()),

-- Submenús de Vehículos (id_superior=2)
(9,  '', 'Lista de Vehículos',              2, 2,  1, 'vehiculos',                        1, 1, NOW()),
(10, '', 'Agregar Vehículo',               2, 2,  2, 'vehiculos/create',                 1, 1, NOW()),
(11, '', 'Asignación de Vehículos',        2, 2,  3, 'asignacion-vehiculos',             1, 1, NOW()),
(12, '', 'Registro de Combustible',        2, 2,  4, 'registro-combustible',             1, 1, NOW()),
(13, '', 'Ventas de Combustible',          2, 2,  5, 'registro-combustible/ventas',      1, 1, NOW()),
(14, '', 'Registrar Venta de Combustible', 2, 2,  6, 'registro-combustible/create-venta',1, 1, NOW()),

-- Submenús de Conductores (id_superior=3)
(15, '', 'Lista de Conductores',  3, 2,  1, 'conductores',        1, 1, NOW()),
(16, '', 'Registrar Conductor',   3, 2,  2, 'conductores/create', 1, 1, NOW()),

-- Submenús de Reportes (id_superior=4)
(17, '', 'Reporte General',        4, 2,  1, 'reports',            1, 1, NOW()),
(18, '', 'Reporte de Vehículos',   4, 2,  2, 'reports/vehicles',   1, 1, NOW()),
(19, '', 'Reporte de Mantenimiento',4, 2, 3, 'reports/maintenance',1, 1, NOW()),
(20, '', 'Reporte de Combustible', 4, 2,  4, 'reports/fuel',       1, 1, NOW()),
(21, '', 'Reporte de Costos',      4, 2,  5, 'reports/costs',      1, 1, NOW()),

-- Submenús de Órdenes de Trabajo (id_superior=5)
(22, '', 'Dashboard OT',           5, 2,  1, 'ordenes-trabajo',                  1, 1, NOW()),
(23, '', 'Vista Calendario',       5, 2,  2, 'ordenes-trabajo/calendario',       1, 1, NOW()),
(24, '', 'Vista Kanban',           5, 2,  3, 'ordenes-trabajo/kanban',           1, 1, NOW()),
(25, '', 'Consulta General',       5, 2,  4, 'ordenes-trabajo/consulta',         1, 1, NOW()),
(26, '', 'Bandeja Pendientes',     5, 2,  5, 'ordenes-trabajo/bandeja-pendientes',1, 1, NOW()),
(27, '', 'Bandeja Aprobadas',      5, 2,  6, 'ordenes-trabajo/bandeja-aprobadas',1, 1, NOW()),
(28, '', 'Bandeja En Proceso',     5, 2,  7, 'ordenes-trabajo/bandeja-en-proceso',1, 1, NOW()),
(29, '', 'Bandeja Finalizadas',    5, 2,  8, 'ordenes-trabajo/bandeja-finalizadas',1, 1, NOW()),
(30, '', 'Nueva Orden',            5, 2,  9, 'ordenes-trabajo/create',           1, 1, NOW()),

-- Submenús de Solicitudes (id_superior=6)
(31, '', 'Todas las Solicitudes',  6, 2,  1, 'solicitudes',        1, 1, NOW()),
(32, '', 'Nueva Solicitud',        6, 2,  2, 'solicitudes/crear',  1, 1, NOW()),

-- Submenús de Inventario (id_superior=7)
(33, '', 'Materiales',              7, 2,  1, 'materiales',                          1, 1, NOW()),
(34, '', 'Nuevo Material',          7, 2,  2, 'materiales/create',                   1, 1, NOW()),
(35, '', 'Movimientos de Inventario',7, 2, 3, 'movimientos',                         1, 1, NOW()),
(36, '', 'Requisa de Salida',       7, 2,  4, 'movimientos/create',                  1, 1, NOW()),
(37, '', 'Sincronización',          7, 2,  5, 'materiales/sincronizacion',           1, 1, NOW()),

-- Submenús de Configuración (id_superior=8)
(38, '', 'Gestión de Usuarios',         8, 2,  1, 'usuarios',              1, 1, NOW()),
(39, '', 'Tipos de Operación',          8, 2,  2, 'tipo-operacion',        1, 1, NOW()),
(40, '', 'Tipos de Unidad',             8, 2,  3, 'tipo-unidad',           1, 1, NOW()),
(41, '', 'Tipos de Problema',           8, 2,  4, 'tipos-problema',        1, 1, NOW()),
(42, '', 'Tipos de Motivo Combustible', 8, 2,  5, 'tipo-motivo-combustible',1, 1, NOW()),
(43, '', 'Gestión de Roles',            8, 2,  6, 'rol',                   1, 1, NOW()),
(44, '', 'Gestión de Menús',            8, 2,  7, 'menu',                  1, 1, NOW()),
(45, '', 'Gestión de Accesos',          8, 2,  8, 'acceso',                1, 1, NOW()),
(46, '', 'Gestión de Direcciones',      8, 2,  9, 'direcciones',           1, 1, NOW()),
(47, '', 'Gestión de Catálogo',         8, 2, 10, 'catalogo',              1, 1, NOW()),
(48, '', 'Mantenimiento de Tablas',     8, 2, 11, 'mantenimiento-tablas',  1, 1, NOW());

-- ========================
-- DATOS INICIALES: ACCESOS (rol 1 = Administrador, usuario_crea = 1)
-- ========================
INSERT INTO accesos (id_rol, id_menu, estado, usuario_crea, fecha_registro) VALUES
(1,  1, 1, 1, NOW()),
(1,  2, 1, 1, NOW()),
(1,  3, 1, 1, NOW()),
(1,  4, 1, 1, NOW()),
(1,  5, 1, 1, NOW()),
(1,  6, 1, 1, NOW()),
(1,  7, 1, 1, NOW()),
(1,  8, 1, 1, NOW()),
(1,  9, 1, 1, NOW()),
(1, 10, 1, 1, NOW()),
(1, 11, 1, 1, NOW()),
(1, 12, 1, 1, NOW()),
(1, 13, 1, 1, NOW()),
(1, 14, 1, 1, NOW()),
(1, 15, 1, 1, NOW()),
(1, 16, 1, 1, NOW()),
(1, 17, 1, 1, NOW()),
(1, 18, 1, 1, NOW()),
(1, 19, 1, 1, NOW()),
(1, 20, 1, 1, NOW()),
(1, 21, 1, 1, NOW()),
(1, 22, 1, 1, NOW()),
(1, 23, 1, 1, NOW()),
(1, 24, 1, 1, NOW()),
(1, 25, 1, 1, NOW()),
(1, 26, 1, 1, NOW()),
(1, 27, 1, 1, NOW()),
(1, 28, 1, 1, NOW()),
(1, 29, 1, 1, NOW()),
(1, 30, 1, 1, NOW()),
(1, 31, 1, 1, NOW()),
(1, 32, 1, 1, NOW()),
(1, 33, 1, 1, NOW()),
(1, 34, 1, 1, NOW()),
(1, 35, 1, 1, NOW()),
(1, 36, 1, 1, NOW()),
(1, 37, 1, 1, NOW()),
(1, 38, 1, 1, NOW()),
(1, 39, 1, 1, NOW()),
(1, 40, 1, 1, NOW()),
(1, 41, 1, 1, NOW()),
(1, 42, 1, 1, NOW()),
(1, 43, 1, 1, NOW()),
(1, 44, 1, 1, NOW()),
(1, 45, 1, 1, NOW()),
(1, 46, 1, 1, NOW()),
(1, 47, 1, 1, NOW()),
(1, 48, 1, 1, NOW());

-- ========================
-- DATOS INICIALES: ROLES
-- ========================
INSERT INTO roles (nombre, descripcion, fechaRegistro, fechaUpdate, usuarioCrea, usuarioEdita) VALUES
('Administrador', 'Acceso completo al sistema', NOW(), NOW(), 1, 1),
('Mecánico',      'Gestión de mantenimientos y reparaciones', NOW(), NOW(), 1, 1),
('Conductor',     'Acceso limitado para conductores', NOW(), NOW(), 1, 1);
