-- ============================================================
-- Tabla cotizaciones — Cotizador GCM (MySQL)
-- Basado en las migraciones 001, 002 y 003 del cotizador-gcm original
-- ============================================================

CREATE TABLE IF NOT EXISTS `cotizaciones` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero_cotizacion` VARCHAR(20) NOT NULL,
  `producto_id` VARCHAR(50) NOT NULL DEFAULT 'diesel',
  `producto_nombre` VARCHAR(100) DEFAULT NULL,
  `cliente_nombre` VARCHAR(200) NOT NULL,
  `cliente_ruc` VARCHAR(40) DEFAULT NULL,
  `cliente_telefono` VARCHAR(40) DEFAULT NULL,
  `origen` VARCHAR(200) DEFAULT NULL,
  `destino` VARCHAR(200) DEFAULT NULL,
  `tipo_vehiculo` VARCHAR(100) NOT NULL DEFAULT 'Camion rigido',
  `distancia_km` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `volumen_galones` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `rendimiento_km_galon` DECIMAL(8,2) NOT NULL DEFAULT 0,
  `precio_combustible_galon` DECIMAL(10,4) NOT NULL DEFAULT 0,
  `costo_viaje` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `margen_porcentaje` DECIMAL(6,2) NOT NULL DEFAULT 0,
  `margen_monto` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `precio_sugerido` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `precio_final` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `flete_por_galon` DECIMAL(10,4) NOT NULL DEFAULT 0,
  `desglose_costos` TEXT DEFAULT NULL,
  `estado` VARCHAR(20) NOT NULL DEFAULT 'BORRADOR',
  `usuario_crea` VARCHAR(100) DEFAULT NULL,
  `fecha_creacion` DATETIME DEFAULT NULL,
  `fecha_actualiza` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_numero_cotizacion` (`numero_cotizacion`),
  KEY `idx_cliente_nombre` (`cliente_nombre`),
  KEY `idx_fecha_creacion` (`fecha_creacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
