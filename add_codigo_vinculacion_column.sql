-- Agregar campo codigo_vinculacion a la tabla materiales
-- Este campo permitirá vincular materiales con productos de SQL Server

ALTER TABLE `materiales` 
ADD COLUMN `codigo_vinculacion` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Código de vinculación con INV_PRODUCTOS de SQL Server' 
AFTER `codigo_consecutivo`;

-- Crear índice para mejorar rendimiento en búsquedas
CREATE INDEX `idx_codigo_vinculacion` ON `materiales` (`codigo_vinculacion`);

-- Verificar la estructura actualizada
DESCRIBE materiales;
