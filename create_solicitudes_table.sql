-- Script SQL para crear la tabla solicitudes con código consecutivo automático
-- Sistema GMV - Gestión de Mantenimiento de Vehículos

-- 1. Crear la tabla solicitudes
CREATE TABLE `solicitudes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `codigo_consecutivo` VARCHAR(20) NOT NULL UNIQUE,
    `id_empresa` INT(11) NOT NULL,
    `id_vehiculo` INT(11) NOT NULL,
    `id_solicitante` INT(11) NOT NULL,
    `id_asignado` INT(11) NULL DEFAULT NULL,
    `id_tipo_problema` INT(11) NULL DEFAULT NULL,
    `descripcion` TEXT NOT NULL,
    `prioridad` ENUM('BAJA','MEDIA','ALTA','CRITICA') NOT NULL DEFAULT 'MEDIA',
    `estado` ENUM('PENDIENTES','APROBADAS','EN_PROCESO','FINALIZADA','CANCELADA') NOT NULL DEFAULT 'PENDIENTES',
    `fecha_solicitud` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `fecha_planificacion` DATE NULL DEFAULT NULL,
    `fecha_asignacion` DATETIME NULL DEFAULT NULL,
    `fecha_inicio_trabajo` DATETIME NULL DEFAULT NULL,
    `fecha_fin_trabajo` DATETIME NULL DEFAULT NULL,
    `fecha_cierre` DATETIME NULL DEFAULT NULL,
    `trabajo_realizado` TEXT NULL DEFAULT NULL,
    `observaciones` TEXT NULL DEFAULT NULL,
    `kilometraje_trabajo` INT(11) NULL DEFAULT NULL,
    `horas_trabajo` DECIMAL(5,2) NULL DEFAULT NULL,
    `estado_vehiculo_post` VARCHAR(50) NULL DEFAULT NULL,
    `usuario_crea` INT(11) NOT NULL,
    `usuario_actualiza` INT(11) NULL DEFAULT NULL,
    `fecha_registro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_codigo_consecutivo` (`codigo_consecutivo`),
    KEY `idx_empresa` (`id_empresa`),
    KEY `idx_vehiculo` (`id_vehiculo`),
    KEY `idx_solicitante` (`id_solicitante`),
    KEY `idx_asignado` (`id_asignado`),
    KEY `idx_tipo_problema` (`id_tipo_problema`),
    KEY `idx_estado` (`estado`),
    KEY `idx_prioridad` (`prioridad`),
    KEY `idx_fecha_solicitud` (`fecha_solicitud`),
    KEY `idx_fecha_planificacion` (`fecha_planificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Solicitudes de mantenimiento de vehículos';

-- 2. Agregar foreign keys (ajustar nombres de tablas según tu BD)
ALTER TABLE `solicitudes`
    ADD CONSTRAINT `fk_solicitudes_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_solicitudes_vehiculo` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_solicitudes_solicitante` FOREIGN KEY (`id_solicitante`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_solicitudes_asignado` FOREIGN KEY (`id_asignado`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_solicitudes_tipo_problema` FOREIGN KEY (`id_tipo_problema`) REFERENCES `tipos_problema` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_solicitudes_usuario_crea` FOREIGN KEY (`usuario_crea`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_solicitudes_usuario_actualiza` FOREIGN KEY (`usuario_actualiza`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 3. Crear función para generar código consecutivo
DELIMITER $$

CREATE FUNCTION GenerarCodigoConsecutivoSolicitud(empresa_id INT) 
RETURNS VARCHAR(20)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE siguiente_numero INT DEFAULT 1;
    DECLARE codigo_generado VARCHAR(20);
    DECLARE anio_actual VARCHAR(4);
    
    -- Obtener el año actual
    SET anio_actual = YEAR(CURDATE());
    
    -- Buscar el último número consecutivo para la empresa y año actual
    SELECT COALESCE(MAX(CAST(SUBSTRING(codigo_consecutivo, -6) AS UNSIGNED)), 0) + 1
    INTO siguiente_numero
    FROM solicitudes 
    WHERE id_empresa = empresa_id 
    AND codigo_consecutivo LIKE CONCAT('SOL-', anio_actual, '-%');
    
    -- Generar el código con formato: SOL-YYYY-NNNNNN
    SET codigo_generado = CONCAT('SOL-', anio_actual, '-', LPAD(siguiente_numero, 6, '0'));
    
    RETURN codigo_generado;
END$$

DELIMITER ;

-- 4. Crear trigger para generar código consecutivo automáticamente
DELIMITER $$

CREATE TRIGGER tr_solicitudes_before_insert
BEFORE INSERT ON solicitudes
FOR EACH ROW
BEGIN
    -- Solo generar código si no se proporciona uno
    IF NEW.codigo_consecutivo IS NULL OR NEW.codigo_consecutivo = '' THEN
        SET NEW.codigo_consecutivo = GenerarCodigoConsecutivoSolicitud(NEW.id_empresa);
    END IF;
    
    -- Establecer fecha de registro si no se proporciona
    IF NEW.fecha_registro IS NULL THEN
        SET NEW.fecha_registro = NOW();
    END IF;
END$$

DELIMITER ;

-- 5. Crear tabla para tipos de problema (si no existe)
CREATE TABLE IF NOT EXISTS `tipos_problema` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(100) NOT NULL,
    `descripcion` TEXT NULL DEFAULT NULL,
    `activo` TINYINT(1) NOT NULL DEFAULT 1,
    `fecha_registro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tipos de problemas de mantenimiento';

-- 6. Insertar tipos de problema básicos
INSERT INTO `tipos_problema` (`nombre`, `descripcion`) VALUES
('Motor', 'Problemas relacionados con el motor del vehículo'),
('Frenos', 'Problemas en el sistema de frenos'),
('Transmisión', 'Problemas en la caja de cambios o transmisión'),
('Suspensión', 'Problemas en el sistema de suspensión'),
('Eléctrico', 'Problemas eléctricos del vehículo'),
('Neumáticos', 'Problemas con neumáticos y llantas'),
('Carrocería', 'Daños en la carrocería del vehículo'),
('Sistema de Enfriamiento', 'Problemas en radiador, termostato, etc.'),
('Combustible', 'Problemas en el sistema de combustible'),
('Mantenimiento Preventivo', 'Mantenimiento programado y preventivo')
ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion);

-- 7. Ejemplos de uso del código consecutivo:
-- Los códigos se generarán automáticamente con formato: SOL-2025-000001, SOL-2025-000002, etc.

-- Ejemplo de inserción (el código se genera automáticamente):
-- INSERT INTO solicitudes (id_empresa, id_vehiculo, id_solicitante, descripcion, prioridad, usuario_crea) 
-- VALUES (1, 1, 1, 'Problema con el motor', 'ALTA', 1);

-- 8. Consulta para verificar códigos generados:
-- SELECT id, codigo_consecutivo, descripcion, estado, fecha_solicitud FROM solicitudes ORDER BY id DESC LIMIT 10;
