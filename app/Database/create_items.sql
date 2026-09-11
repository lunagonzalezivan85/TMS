-- ============================================================
-- Tabla items — Productos del cotizador (MySQL)
-- ============================================================

CREATE TABLE IF NOT EXISTS `items` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(50) NOT NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `categoria` VARCHAR(80) NOT NULL DEFAULT 'combustible',
  `unidad_medida` VARCHAR(20) NOT NULL DEFAULT 'galon',
  `costo_galon` DECIMAL(10,4) NOT NULL DEFAULT 0,
  `precio_galon` DECIMAL(10,4) NOT NULL DEFAULT 0,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `fecha_creacion` DATETIME DEFAULT NULL,
  `fecha_actualiza` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_codigo` (`codigo`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seed: productos del dropdown del cotizador
INSERT INTO `items` (`codigo`, `nombre`, `descripcion`, `categoria`, `unidad_medida`, `costo_galon`, `precio_galon`, `estado`, `fecha_creacion`, `fecha_actualiza`) VALUES
('diesel',          'Diesel',          'Combustible Diesel estándar',     'combustible', 'galon', 149.3800, 149.3879, 1, NOW(), NOW()),
('maxxima_regular', 'Maxxima Regular', 'Gasolina Maxxima Regular',         'combustible', 'galon', 161.0800, 161.0800, 1, NOW(), NOW()),
('maxxima_premium', 'Maxxima Premium', 'Gasolina Maxxima Premium',         'combustible', 'galon', 165.5900, 165.5900, 1, NOW(), NOW()),
('jet_a1',          'Jet A1',          'Combustible de aviación Jet A1',   'combustible', 'galon', 126.4400, 126.4400, 1, NOW(), NOW());
