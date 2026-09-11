-- Script para crear la tabla tipo_motivo_combustible

CREATE TABLE IF NOT EXISTS `tipo_motivo_combustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=ACTIVO, 0=INACTIVO',
  `id_empresa` int(11) NOT NULL,
  `usuario_crea` int(11) DEFAULT NULL,
  `usuario_edita` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualiza` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tipo_motivo_empresa` (`id_empresa`),
  KEY `idx_tipo_motivo_estado` (`estado`),
  KEY `idx_tipo_motivo_descripcion` (`descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de prueba
INSERT INTO `tipo_motivo_combustible` (`descripcion`, `estado`, `id_empresa`, `usuario_crea`, `fecha_registro`) VALUES
('Combustible Regular', 1, 1, 1, NOW()),
('Combustible Premium', 1, 1, 1, NOW()),
('Diesel', 1, 1, 1, NOW()),
('Gas Natural', 1, 1, 1, NOW()),
('Combustible de Emergencia', 1, 1, 1, NOW());

-- Verificar la creación
SELECT * FROM tipo_motivo_combustible;
