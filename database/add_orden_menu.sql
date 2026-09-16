ALTER TABLE `menu` ADD COLUMN `orden` INT DEFAULT 0 AFTER `estado`;
UPDATE `menu` SET `orden` = `id`;
