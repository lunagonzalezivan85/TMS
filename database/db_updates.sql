-- Actualizar estructura de solicitudes con columnas nuevas
ALTER TABLE `solicitudes`
  ADD COLUMN `ubicacion` varchar(255) DEFAULT NULL AFTER `descripcion`,
  ADD COLUMN `condicion_movilidad` varchar(30) DEFAULT NULL AFTER `ubicacion`,
  ADD COLUMN `tipo_mantenimiento` varchar(20) DEFAULT NULL AFTER `id_tipo_mantenimiento`,
  ADD COLUMN `fecha_aprobacion` datetime DEFAULT NULL AFTER `fecha_asignacion`,
  ADD COLUMN `observaciones` text DEFAULT NULL AFTER `fecha_aprobacion`;

-- Actualizar enum de estado en vehiculos (agregar EN MANTENIMIENTO)
ALTER TABLE `vehiculos`
  MODIFY COLUMN `estado` enum('ACTIVO','INACTIVO','EN REPARACION','EN MANTENIMIENTO') DEFAULT 'ACTIVO';

-- Actualizar enum de estado en historial_estado_vehiculo (agregar EN MANTENIMIENTO)
ALTER TABLE `historial_estado_vehiculo`
  MODIFY COLUMN `estado` enum('ACTIVO','INACTIVO','EN REPARACION','EN MANTENIMIENTO') NOT NULL;

-- Actualizar tipo_consumo en vehiculos (permitir NULL)
ALTER TABLE `vehiculos`
  MODIFY COLUMN `tipo_consumo` int(11) DEFAULT 0;
