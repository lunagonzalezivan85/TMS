-- Agregar campo PIN de acceso a la tabla conductores
ALTER TABLE `conductores`
ADD COLUMN `pin_acceso` VARCHAR(6) NULL COMMENT 'PIN de 6 dígitos para acceso al portal de conductores';
