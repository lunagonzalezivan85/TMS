-- Agregar campos para la hoja de trabajo del técnico
-- Ejecutar en la base de datos tms

ALTER TABLE `registro_trabajo_realizado`
ADD COLUMN `diagnostico_sistemas` TEXT NULL COMMENT 'JSON con sistemas diagnosticados (checkboxes)',
ADD COLUMN `trabajos_checklist` TEXT NULL COMMENT 'JSON con trabajos realizados (checkboxes)',
ADD COLUMN `nivel_combustible` VARCHAR(20) NULL COMMENT 'Nivel de combustible al ingreso',
ADD COLUMN `kilometraje_ingreso` INT NULL COMMENT 'Kilometraje al ingreso del vehículo',
ADD COLUMN `resultado_final` VARCHAR(50) NULL COMMENT 'Resultado final del trabajo',
ADD COLUMN `observaciones_finales` TEXT NULL COMMENT 'Observaciones finales y recomendaciones';
