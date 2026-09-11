-- Script SQL para crear la tabla de Materiales
-- Sistema GMV - Gestión de Mantenimiento de Vehículos

-- Crear la tabla materiales
CREATE TABLE `materiales` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_empresa` INT(11) NOT NULL,
    `codigo_consecutivo` VARCHAR(20) NOT NULL UNIQUE,
    `nombre` VARCHAR(255) NOT NULL,
    `unidad_medida` VARCHAR(50) NOT NULL DEFAULT 'UNIDAD',
    `costo_unitario` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `fechaRegistro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `fechaUpdate` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `usuarioCrea` INT(11) NOT NULL,
    `usuarioEdita` INT(11) NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_codigo_consecutivo` (`codigo_consecutivo`),
    KEY `idx_empresa` (`id_empresa`),
    KEY `idx_nombre` (`nombre`),
    KEY `idx_unidad_medida` (`unidad_medida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de materiales y repuestos para mantenimiento';

-- Función para generar código consecutivo de materiales
DELIMITER $$

CREATE FUNCTION GenerarCodigoConsecutivoMaterial(empresa_id INT) 
RETURNS VARCHAR(20)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE siguiente_numero INT DEFAULT 1;
    DECLARE codigo_generado VARCHAR(20);
    DECLARE anio_actual VARCHAR(4);
    
    -- Obtener el año actual
    SET anio_actual = YEAR(CURDATE());
    
    -- Buscar el último número consecutivo para el año actual y empresa
    SELECT COALESCE(MAX(CAST(SUBSTRING(codigo_consecutivo, -6) AS UNSIGNED)), 0) + 1
    INTO siguiente_numero
    FROM materiales 
    WHERE codigo_consecutivo LIKE CONCAT('MAT-', anio_actual, '-%')
    AND id_empresa = empresa_id;
    
    -- Generar el código con formato: MAT-YYYY-NNNNNN
    SET codigo_generado = CONCAT('MAT-', anio_actual, '-', LPAD(siguiente_numero, 6, '0'));
    
    RETURN codigo_generado;
END$$

DELIMITER ;

-- Trigger para generar código consecutivo automáticamente
DELIMITER $$

CREATE TRIGGER tr_materiales_before_insert
BEFORE INSERT ON materiales
FOR EACH ROW
BEGIN
    -- Solo generar código si no se proporciona uno
    IF NEW.codigo_consecutivo IS NULL OR NEW.codigo_consecutivo = '' THEN
        SET NEW.codigo_consecutivo = GenerarCodigoConsecutivoMaterial(NEW.id_empresa);
    END IF;
    
    -- Establecer fecha de registro si no se proporciona
    IF NEW.fechaRegistro IS NULL THEN
        SET NEW.fechaRegistro = NOW();
    END IF;
END$$

DELIMITER ;

-- Ejemplos de uso:

-- Insertar materiales (el código se genera automáticamente):
/*
INSERT INTO materiales (id_empresa, nombre, unidad_medida, costo_unitario, usuarioCrea) VALUES 
(1, 'Aceite Motor 5W-30', 'LITRO', 25.50, 1),
(1, 'Filtro de Aceite', 'UNIDAD', 15.00, 1),
(1, 'Filtro de Aire', 'UNIDAD', 12.75, 1),
(1, 'Pastillas de Freno Delanteras', 'JUEGO', 85.00, 1),
(1, 'Pastillas de Freno Traseras', 'JUEGO', 65.00, 1),
(1, 'Líquido de Frenos DOT 4', 'LITRO', 18.50, 1),
(1, 'Anticongelante', 'LITRO', 22.00, 1),
(1, 'Bujías', 'UNIDAD', 8.75, 1),
(1, 'Correa de Distribución', 'UNIDAD', 45.00, 1),
(1, 'Amortiguador Delantero', 'UNIDAD', 120.00, 1);
*/

-- Consultas útiles:

-- Ver todos los materiales con información completa:
/*
SELECT 
    m.codigo_consecutivo,
    m.nombre,
    m.unidad_medida,
    m.costo_unitario,
    m.fechaRegistro,
    CONCAT(u1.nombre, ' ', u1.apellido) as creado_por,
    CONCAT(u2.nombre, ' ', u2.apellido) as editado_por
FROM materiales m
LEFT JOIN usuarios u1 ON m.usuarioCrea = u1.id
LEFT JOIN usuarios u2 ON m.usuarioEdita = u2.id
ORDER BY m.fechaRegistro DESC;
*/

-- Buscar materiales por nombre:
/*
SELECT * FROM materiales 
WHERE nombre LIKE '%aceite%' 
ORDER BY nombre;
*/

-- Materiales más costosos:
/*
SELECT codigo_consecutivo, nombre, costo_unitario 
FROM materiales 
ORDER BY costo_unitario DESC 
LIMIT 10;
*/
