-- Script SQL para crear la tabla de Registro de Trabajo Realizado
-- Sistema GMV - Gestión de Mantenimiento de Vehículos
-- Basado en el formulario "Realizar Orden de Trabajo"

-- 1. Crear la tabla registro_trabajo_realizado
CREATE TABLE `registro_trabajo_realizado` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `codigo_consecutivo` VARCHAR(20) NOT NULL UNIQUE,
    `id_solicitud` INT(11) NOT NULL,
    `id_vehiculo` INT(11) NOT NULL,
    `id_tecnico` INT(11) NOT NULL,
    `fecha_inicio` DATETIME NOT NULL,
    `fecha_fin` DATETIME NULL DEFAULT NULL,
    `trabajo_realizado` TEXT NOT NULL COMMENT 'Descripción detallada del trabajo realizado',
    `kilometraje_actual` INT(11) NULL DEFAULT NULL COMMENT 'Kilometraje del vehículo al momento del trabajo',
    `horas_trabajo` DECIMAL(5,2) NULL DEFAULT NULL COMMENT 'Horas invertidas en el trabajo',
    `observaciones` TEXT NULL DEFAULT NULL COMMENT 'Observaciones adicionales y recomendaciones',
    `estado_vehiculo_post` ENUM('OPERATIVO','REQUIERE_REVISION','FUERA_DE_SERVICIO','PENDIENTE_REPUESTOS') NULL DEFAULT NULL COMMENT 'Estado del vehículo después del trabajo',
    `trabajo_completado` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 si el trabajo está completado, 0 si está en progreso',
    `costo_total_materiales` DECIMAL(10,2) NULL DEFAULT 0.00 COMMENT 'Costo total de materiales utilizados',
    `usuario_crea` INT(11) NOT NULL,
    `usuario_actualiza` INT(11) NULL DEFAULT NULL,
    `fecha_registro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_codigo_consecutivo` (`codigo_consecutivo`),
    KEY `idx_solicitud` (`id_solicitud`),
    KEY `idx_vehiculo` (`id_vehiculo`),
    KEY `idx_tecnico` (`id_tecnico`),
    KEY `idx_fecha_inicio` (`fecha_inicio`),
    KEY `idx_trabajo_completado` (`trabajo_completado`),
    KEY `idx_estado_vehiculo_post` (`estado_vehiculo_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro detallado de trabajos de mantenimiento realizados';

-- 2. Crear tabla para materiales utilizados en cada trabajo
CREATE TABLE `materiales_trabajo` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_registro_trabajo` INT(11) NOT NULL,
    `id_material` INT(11) NOT NULL COMMENT 'ID del material de la tabla materiales',
    `cantidad` INT(11) NOT NULL DEFAULT 1,
    `costo_unitario` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `costo_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `fecha_registro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_registro_trabajo` (`id_registro_trabajo`),
    KEY `idx_material` (`id_material`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Materiales y repuestos utilizados en trabajos de mantenimiento';

-- 3. Foreign keys removidas según solicitud del usuario

-- 4. Crear función para generar código consecutivo de trabajos
DELIMITER $$

CREATE FUNCTION GenerarCodigoConsecutivoTrabajo(vehiculo_id INT) 
RETURNS VARCHAR(20)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE siguiente_numero INT DEFAULT 1;
    DECLARE codigo_generado VARCHAR(20);
    DECLARE anio_actual VARCHAR(4);
    
    -- Obtener el año actual
    SET anio_actual = YEAR(CURDATE());
    
    -- Buscar el último número consecutivo para el año actual
    SELECT COALESCE(MAX(CAST(SUBSTRING(codigo_consecutivo, -6) AS UNSIGNED)), 0) + 1
    INTO siguiente_numero
    FROM registro_trabajo_realizado 
    WHERE codigo_consecutivo LIKE CONCAT('TRB-', anio_actual, '-%');
    
    -- Generar el código con formato: TRB-YYYY-NNNNNN
    SET codigo_generado = CONCAT('TRB-', anio_actual, '-', LPAD(siguiente_numero, 6, '0'));
    
    RETURN codigo_generado;
END$$

DELIMITER ;

-- 5. Crear trigger para generar código consecutivo automáticamente
DELIMITER $$

CREATE TRIGGER tr_registro_trabajo_before_insert
BEFORE INSERT ON registro_trabajo_realizado
FOR EACH ROW
BEGIN
    -- Solo generar código si no se proporciona uno
    IF NEW.codigo_consecutivo IS NULL OR NEW.codigo_consecutivo = '' THEN
        SET NEW.codigo_consecutivo = GenerarCodigoConsecutivoTrabajo(NEW.id_vehiculo);
    END IF;
    
    -- Establecer fecha de registro si no se proporciona
    IF NEW.fecha_registro IS NULL THEN
        SET NEW.fecha_registro = NOW();
    END IF;
END$$

DELIMITER ;

-- 6. Crear trigger para actualizar costo total de materiales
DELIMITER $$

CREATE TRIGGER tr_materiales_after_insert
AFTER INSERT ON materiales_trabajo
FOR EACH ROW
BEGIN
    UPDATE registro_trabajo_realizado 
    SET costo_total_materiales = (
        SELECT COALESCE(SUM(costo_total), 0) 
        FROM materiales_trabajo 
        WHERE id_registro_trabajo = NEW.id_registro_trabajo
    )
    WHERE id = NEW.id_registro_trabajo;
END$$

CREATE TRIGGER tr_materiales_after_update
AFTER UPDATE ON materiales_trabajo
FOR EACH ROW
BEGIN
    UPDATE registro_trabajo_realizado 
    SET costo_total_materiales = (
        SELECT COALESCE(SUM(costo_total), 0) 
        FROM materiales_trabajo 
        WHERE id_registro_trabajo = NEW.id_registro_trabajo
    )
    WHERE id = NEW.id_registro_trabajo;
END$$

CREATE TRIGGER tr_materiales_after_delete
AFTER DELETE ON materiales_trabajo
FOR EACH ROW
BEGIN
    UPDATE registro_trabajo_realizado 
    SET costo_total_materiales = (
        SELECT COALESCE(SUM(costo_total), 0) 
        FROM materiales_trabajo 
        WHERE id_registro_trabajo = OLD.id_registro_trabajo
    )
    WHERE id = OLD.id_registro_trabajo;
END$$

DELIMITER ;

-- 7. Trigger para calcular costo_total automáticamente en materiales
DELIMITER $$

CREATE TRIGGER tr_materiales_before_insert_calc
BEFORE INSERT ON materiales_trabajo
FOR EACH ROW
BEGIN
    SET NEW.costo_total = NEW.cantidad * NEW.costo_unitario;
END$$

CREATE TRIGGER tr_materiales_before_update_calc
BEFORE UPDATE ON materiales_trabajo
FOR EACH ROW
BEGIN
    SET NEW.costo_total = NEW.cantidad * NEW.costo_unitario;
END$$

DELIMITER ;

-- 8. Ejemplos de uso:

-- Insertar un registro de trabajo (el código se genera automáticamente):
/*
INSERT INTO registro_trabajo_realizado (
    id_solicitud, id_vehiculo, id_tecnico, fecha_inicio, trabajo_realizado, 
    kilometraje_actual, horas_trabajo, observaciones, estado_vehiculo_post, 
    trabajo_completado, usuario_crea
) VALUES (
    1, 1, 2, '2025-09-21 08:00:00', 'Cambio de aceite y filtros', 
    15000, 2.5, 'Vehículo en buen estado general', 'OPERATIVO', 
    1, 1
);
*/

-- Insertar materiales utilizados:
/*
INSERT INTO materiales_trabajo (id_registro_trabajo, id_material, cantidad, costo_unitario) 
VALUES 
(1, 1, 4, 25.00),  -- Aceite motor 5W-30
(1, 2, 1, 15.00),  -- Filtro de aceite
(1, 3, 1, 12.50);  -- Filtro de aire
*/

-- 9. Consultas útiles:

-- Ver trabajos realizados con sus materiales:
/*
SELECT 
    r.codigo_consecutivo,
    r.trabajo_realizado,
    r.fecha_inicio,
    r.horas_trabajo,
    r.costo_total_materiales,
    v.placa,
    u.nombre as tecnico
FROM registro_trabajo_realizado r
JOIN vehiculos v ON r.id_vehiculo = v.id
JOIN usuarios u ON r.id_tecnico = u.id
ORDER BY r.fecha_registro DESC;
*/

-- Ver materiales por trabajo:
/*
SELECT 
    r.codigo_consecutivo,
    mat.nombre as nombre_material,
    mt.cantidad,
    mt.costo_unitario,
    mt.costo_total
FROM registro_trabajo_realizado r
JOIN materiales_trabajo mt ON r.id = mt.id_registro_trabajo
JOIN materiales mat ON mt.id_material = mat.id
WHERE r.id = 1;
*/
