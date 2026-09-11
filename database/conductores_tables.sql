-- tms.accesorios_vehiculos definition

CREATE TABLE `accesorios_vehiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accesorio` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp(),
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.accesos definition

CREATE TABLE `accesos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_rol` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_actualiza` varchar(100) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=392 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.asignacion_vehiculos definition

CREATE TABLE `asignacion_vehiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_vehiculo` int(11) DEFAULT NULL,
  `id_conductor` int(11) DEFAULT NULL,
  `fecha_asignacion` datetime DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_crea` int(11) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `motivo_desasignacion` varchar(100) DEFAULT NULL,
  `fecha_desasignacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.catalogo definition

CREATE TABLE `catalogo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `id_superior` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `referencia` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `nivel` int(11) DEFAULT 1,
  `referencia2` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `edicion` int(11) DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_actualiza` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualiza` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.conductores definition

CREATE TABLE `conductores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `codigo_consecutivo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `fechaIngreso` date NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuarioCrea` int(11) DEFAULT NULL,
  `usuarioEdita` int(11) DEFAULT NULL,
  `licencia` varchar(100) DEFAULT NULL,
  `fechaVencimientoLicencia` datetime DEFAULT current_timestamp(),
  `carnet` varchar(100) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `codigoERP` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dni` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.consecutivos_detalle definition

CREATE TABLE `consecutivos_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `tabla` varchar(50) NOT NULL,
  `numero` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_consecutivo` (`id_empresa`,`tabla`,`numero`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.consumo_materiales definition

CREATE TABLE `consumo_materiales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `codigo_consecutivo` varchar(50) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `costo_total` decimal(10,2) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuarioCrea` int(11) DEFAULT NULL,
  `usuarioEdita` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.detalle_movimiento definition

CREATE TABLE `detalle_movimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_movimiento` int(11) DEFAULT NULL,
  `id_material` int(11) DEFAULT NULL,
  `cantidad` decimal(10,0) DEFAULT NULL,
  `precio` decimal(10,0) DEFAULT NULL,
  `impuesto` decimal(10,0) DEFAULT NULL,
  `linea` int(11) DEFAULT NULL,
  `subtotal` decimal(10,0) DEFAULT NULL,
  `total_linea` decimal(10,0) DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualiza` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.direcciones definition

CREATE TABLE `direcciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `direccion` varchar(255) DEFAULT NULL,
  `codigo_integracion` varchar(100) DEFAULT NULL,
  `longitud` varchar(100) DEFAULT NULL,
  `latitud` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_actualizacion` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp(),
  `estado` int(11) DEFAULT 1,
  `id_empresa` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.documentacion_conductor definition

CREATE TABLE `documentacion_conductor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_documento` varchar(100) DEFAULT NULL,
  `observacion` varchar(250) DEFAULT NULL,
  `ruta_documento` varchar(255) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT NULL,
  `usuario_crea` int(11) DEFAULT NULL,
  `usuario_actualiza` int(11) DEFAULT NULL,
  `fecha_vencimiento` datetime DEFAULT current_timestamp(),
  `IdConductor` int(11) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT current_timestamp(),
  `id_empresa` int(11) DEFAULT NULL,
  `numero_documento` varchar(100) DEFAULT NULL,
  `fecha_emision` datetime DEFAULT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `notificacion` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.documentos_vehiculos definition

CREATE TABLE `documentos_vehiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_vehiculo` int(11) DEFAULT NULL,
  `tipo_documento` varchar(100) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_crea` int(11) DEFAULT NULL,
  `usuario_edita` int(11) DEFAULT NULL,
  `fecha_vencimiento` datetime DEFAULT NULL,
  `nombre_archivo` varchar(100) DEFAULT NULL,
  `notificacion` int(11) DEFAULT NULL,
  `numero` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.empresas definition

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `ruc` varchar(50) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `codigo_inicial` varchar(5) NOT NULL,
  `serie_documento` varchar(20) NOT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuarioCrea` int(11) DEFAULT NULL,
  `usuarioEdita` int(11) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.historial_estado_vehiculo definition

CREATE TABLE `historial_estado_vehiculo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO','EN REPARACION') NOT NULL,
  `motivo` text DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT current_timestamp(),
  `fecha_fin` datetime DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuarioCrea` int(11) DEFAULT NULL,
  `usuarioEdita` int(11) DEFAULT NULL,
  `id_conductor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.historial_orden_trabajo definition

CREATE TABLE `historial_orden_trabajo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(11) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `usuario_registra` varchar(100) DEFAULT NULL,
  `comentario` varchar(100) DEFAULT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.materiales definition

CREATE TABLE `materiales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `codigo_consecutivo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `unidad_medida` varchar(20) DEFAULT NULL,
  `costo_unitario` decimal(10,2) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuarioCrea` int(11) DEFAULT NULL,
  `usuarioEdita` int(11) DEFAULT NULL,
  `codigo_vinculacion` varchar(100) DEFAULT NULL,
  `impuesto` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.materiales_trabajo definition

CREATE TABLE `materiales_trabajo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_registro_trabajo` int(11) NOT NULL,
  `id_material` int(11) NOT NULL COMMENT 'ID del material de la tabla materiales',
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `costo_unitario` decimal(8,2) NOT NULL DEFAULT 0.00,
  `costo_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.menu definition

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icono` varchar(100) DEFAULT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `id_superior` varchar(100) DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  `fecha_registra` datetime DEFAULT current_timestamp(),
  `fecha_actualiza` datetime DEFAULT current_timestamp(),
  `ruta` varchar(100) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.motivos_asignacion_combustible definition

CREATE TABLE `motivos_asignacion_combustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `motivo` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.movimientos definition

CREATE TABLE `movimientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_movimiento` varchar(100) DEFAULT NULL,
  `codigo` varchar(100) DEFAULT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL,
  `fecha_movimiento` datetime DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  `usuario_aprueba` varchar(100) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `codigo_origen` varchar(100) DEFAULT NULL,
  `codigo_destino` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.registro_combustible definition

CREATE TABLE `registro_combustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_vehiculo` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `kilometraje_anterior` int(11) DEFAULT NULL,
  `kilometraje_actual` int(11) NOT NULL,
  `cantidad_litros` decimal(10,2) NOT NULL,
  `id_motivo` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  `fecha_actualiza` datetime DEFAULT current_timestamp(),
  `id_tipo_motivo` int(11) DEFAULT 1,
  `id_direccion` int(11) DEFAULT 0,
  `medicion` float DEFAULT NULL,
  `nombreCliente` varchar(100) DEFAULT NULL,
  `dni` varchar(100) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `combustible_tanque` float DEFAULT NULL,
  `monto_nio` float DEFAULT 0,
  `monto_usd` float DEFAULT 0,
  `estado` varchar(100) DEFAULT NULL,
  `rendimiento` float DEFAULT NULL,
  `rendimiento_promedio` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.registro_trabajo_realizado definition

CREATE TABLE `registro_trabajo_realizado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_consecutivo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `id_tecnico` int(11) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `trabajo_realizado` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Descripción detallada del trabajo realizado',
  `kilometraje_actual` int(11) DEFAULT NULL COMMENT 'Kilometraje del vehículo al momento del trabajo',
  `horas_trabajo` decimal(5,2) DEFAULT NULL COMMENT 'Horas invertidas en el trabajo',
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observaciones adicionales y recomendaciones',
  `estado_vehiculo_post` enum('OPERATIVO','REQUIERE_REVISION','FUERA_DE_SERVICIO','PENDIENTE_REPUESTOS') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Estado del vehículo después del trabajo',
  `trabajo_completado` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 si el trabajo está completado, 0 si está en progreso',
  `costo_total_materiales` decimal(10,2) DEFAULT 0.00 COMMENT 'Costo total de materiales utilizados',
  `usuario_crea` int(11) NOT NULL,
  `usuario_actualiza` int(11) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_consecutivo` (`codigo_consecutivo`),
  UNIQUE KEY `uk_codigo_consecutivo` (`codigo_consecutivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.roles definition

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_crea` int(11) DEFAULT NULL,
  `usuario_actualiza` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.solicitudes definition

CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `codigo_consecutivo` varchar(50) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `id_solicitante` int(11) NOT NULL,
  `id_tipo_problema` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'PENDIENTE',
  `fecha_solicitud` datetime DEFAULT current_timestamp(),
  `fecha_cierre` datetime DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_crea` int(11) DEFAULT NULL,
  `usuario_actualiza` int(11) DEFAULT NULL,
  `fecha_planificacion` datetime DEFAULT NULL,
  `id_tipo_mantenimiento` int(11) DEFAULT NULL,
  `prioridad` int(11) DEFAULT NULL,
  `id_asignado` int(11) DEFAULT NULL,
  `fecha_asignacion` datetime DEFAULT NULL,
  `url_foto` varchar(255) DEFAULT NULL,
  `solicitante` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.tipo_documentos definition

CREATE TABLE `tipo_documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualiza` datetime DEFAULT current_timestamp(),
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.tipo_motivo_combustible definition

CREATE TABLE `tipo_motivo_combustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualiza` datetime DEFAULT current_timestamp(),
  `id_empresa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.tipo_operacion definition

CREATE TABLE `tipo_operacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) DEFAULT NULL,
  `UsuarioCrea` varchar(100) DEFAULT NULL,
  `UsuarioEdita` varchar(100) DEFAULT NULL,
  `fechaRegistra` datetime DEFAULT current_timestamp(),
  `fechaActualiza` datetime DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.tipo_unidad definition

CREATE TABLE `tipo_unidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) DEFAULT NULL,
  `UsuarioCrea` varchar(100) DEFAULT NULL,
  `UsuarioEdita` varchar(100) DEFAULT NULL,
  `fechaRegistra` datetime DEFAULT current_timestamp(),
  `fechaActualiza` datetime DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.tipos_problema definition

CREATE TABLE `tipos_problema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuarioCrea` int(11) DEFAULT NULL,
  `usuarioEdita` int(11) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.usuarios definition

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuarioCrea` int(11) DEFAULT NULL,
  `usuarioEdita` int(11) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `id_superior` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.vehiculos definition

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) DEFAULT NULL,
  `codigo_consecutivo` varchar(50) DEFAULT NULL,
  `placa` varchar(20) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `anio` year(4) DEFAULT NULL,
  `kilometraje` int(11) DEFAULT NULL,
  `id_conductor` int(11) DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO','EN REPARACION') DEFAULT 'ACTIVO',
  `motivo_inactividad` text DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuarioCrea` int(11) DEFAULT NULL,
  `usuarioEdita` int(11) DEFAULT NULL,
  `idTipoUnidad` int(11) DEFAULT NULL,
  `idTipoOperacion` int(11) DEFAULT NULL,
  `codigo_unidad` varchar(100) DEFAULT NULL,
  `numero_motor` varchar(100) DEFAULT NULL,
  `numero_chasis` varchar(100) DEFAULT NULL,
  `disponible` varchar(100) DEFAULT 'DISPONIBLE',
  `compuesto` int(11) DEFAULT 0,
  `codigo_centro_costo` varchar(100) DEFAULT NULL,
  `id_color` int(11) DEFAULT NULL,
  `id_tipo_vehiculo` int(11) DEFAULT NULL,
  `id_tipo_producto` int(11) NOT NULL,
  `rendimiento` float DEFAULT 0,
  `max_combustible` float DEFAULT 0,
  `Disponibilidad` varchar(50) DEFAULT NULL,
  `Color` varchar(50) DEFAULT NULL,
  `tipoMotor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `placa` (`placa`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.vta_clientes definition

CREATE TABLE `vta_clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_de_cliente` int(11) NOT NULL,
  `nombre_cliente` varchar(100) DEFAULT NULL,
  `estado_cliente` char(1) DEFAULT NULL,
  `contacto_cliente` varchar(100) DEFAULT NULL,
  `direccion_cliente` varchar(300) DEFAULT NULL,
  `direccion_envio` varchar(300) DEFAULT NULL,
  `telefono_cliente` varchar(30) DEFAULT NULL,
  `celular_cliente` varchar(30) DEFAULT NULL,
  `fecha_apertura` datetime DEFAULT NULL,
  `nit_cliente` varchar(30) DEFAULT NULL,
  `limite_cred_cliente` decimal(18,2) DEFAULT NULL,
  `saldo_cliente` decimal(18,2) DEFAULT NULL,
  `exent_impto_cliente` char(1) DEFAULT NULL,
  `tipo_de_cliente` char(1) DEFAULT NULL,
  `tpo_despacho_cliente` char(1) DEFAULT NULL,
  `codigo_departamento` int(11) DEFAULT NULL,
  `codigo_de_pais` int(11) DEFAULT NULL,
  `codigo_municipio` int(11) DEFAULT NULL,
  `codigo_territorio` int(11) DEFAULT NULL,
  `codigo_de_clase` char(2) DEFAULT NULL,
  `nivel_precio` char(2) DEFAULT NULL,
  `codigo_de_condicion` int(11) DEFAULT NULL,
  `codigo_tipo_pago` char(2) DEFAULT NULL,
  `total_cheques_rech` int(11) DEFAULT NULL,
  `giro_de_negocio` varchar(60) DEFAULT NULL,
  `numero_registro` varchar(20) DEFAULT NULL,
  `codigo_impuesto` char(3) DEFAULT NULL,
  `codigo_de_moneda` char(2) DEFAULT NULL,
  `codigo_cobrador` int(11) DEFAULT NULL,
  `codigo_de_grupo` char(8) DEFAULT NULL,
  `porcentaje_descuento` decimal(18,2) DEFAULT NULL,
  `saldo_financiamiento` decimal(18,2) DEFAULT NULL,
  `codigo_ecc` varchar(5) DEFAULT NULL,
  `codigo_ruta_despacho` int(11) DEFAULT NULL,
  `categoria_cliente` int(11) DEFAULT NULL,
  `porc_mora` decimal(18,2) DEFAULT NULL,
  `nombre_comercial` varchar(100) DEFAULT NULL,
  `precios_con_iva` char(1) DEFAULT NULL,
  `referencia_cliente` varchar(10) DEFAULT NULL,
  `tpo_despacho_parcial` varchar(1) DEFAULT NULL,
  `consumo_interno` char(1) DEFAULT NULL,
  `cedula_cliente` varchar(30) DEFAULT NULL,
  `direccion_cobro` varchar(300) DEFAULT NULL,
  `direccion_email` varchar(100) DEFAULT NULL,
  `clave_cliente` varchar(30) DEFAULT NULL,
  `contacto_cobro` varchar(100) DEFAULT NULL,
  `telefono_cobro` varchar(30) DEFAULT NULL,
  `correo_cobro` varchar(100) DEFAULT NULL,
  `latitud` varchar(60) DEFAULT NULL,
  `longitud` varchar(60) DEFAULT NULL,
  `buro_credito` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.vta_clientes_direcciones definition

CREATE TABLE `vta_clientes_direcciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_address` int(11) NOT NULL,
  `codigo_de_cliente` int(11) NOT NULL,
  `direccion` varchar(600) DEFAULT NULL,
  `latitud` varchar(60) DEFAULT NULL,
  `longitud` varchar(60) DEFAULT NULL,
  `kilometros` decimal(18,2) DEFAULT NULL,
  `estado` bit(1) DEFAULT NULL,
  `usuario_ingreso` varchar(10) DEFAULT NULL,
  `fecha_ingreso` datetime DEFAULT NULL,
  `usuario_modifico` varchar(10) DEFAULT NULL,
  `fecha_modifico` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.vta_clientes_productos definition

CREATE TABLE `vta_clientes_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_address` int(11) DEFAULT NULL,
  `codigo_de_cliente` int(11) NOT NULL,
  `codigo_producto` varchar(20) NOT NULL,
  `precio` float DEFAULT NULL,
  `flete` float DEFAULT NULL,
  `comision_vendedor` float DEFAULT NULL,
  `comision_empresa` float DEFAULT NULL,
  `comision_puma` float DEFAULT NULL,
  `usuario_ingreso` varchar(10) DEFAULT NULL,
  `fecha_ingreso` datetime DEFAULT NULL,
  `usuario_modifico` varchar(10) DEFAULT NULL,
  `fecha_modifico` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.vta_orden definition

CREATE TABLE `vta_orden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_de_pedido` int(11) NOT NULL,
  `fecha_pedido` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  `dias_renta` int(11) DEFAULT NULL,
  `subtotal_pedido` decimal(18,2) DEFAULT NULL,
  `monto_flete` decimal(18,2) DEFAULT NULL,
  `porcentaje_descto` decimal(18,2) DEFAULT NULL,
  `monto_descuento` decimal(18,2) DEFAULT NULL,
  `sub_total` decimal(18,2) DEFAULT NULL,
  `impuesto` decimal(18,2) DEFAULT NULL,
  `retencion` decimal(18,2) DEFAULT NULL,
  `total_general` decimal(18,2) DEFAULT NULL,
  `codigo_de_cliente` int(11) DEFAULT NULL,
  `codigo_vendedor` int(11) DEFAULT NULL,
  `codigo_de_condicion` int(11) DEFAULT NULL,
  `nombre_a_facturar` varchar(100) DEFAULT NULL,
  `numero_ruc` varchar(30) DEFAULT NULL,
  `direccion_facturar` varchar(300) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono_cliente` varchar(50) DEFAULT NULL,
  `celular_cliente` varchar(50) DEFAULT NULL,
  `direccion_email` varchar(100) DEFAULT NULL,
  `nota1` varchar(300) DEFAULT NULL,
  `nota2` varchar(300) DEFAULT NULL,
  `nota3` varchar(300) DEFAULT NULL,
  `nota4` varchar(300) DEFAULT NULL,
  `camb_moned_local` decimal(18,4) DEFAULT NULL,
  `codigo_de_moneda` char(2) DEFAULT NULL,
  `orden_compra` varchar(30) DEFAULT NULL,
  `usuario_ingreso` char(10) DEFAULT NULL,
  `fecha_ingreso` datetime DEFAULT NULL,
  `usuario_anulacion` char(10) DEFAULT NULL,
  `fecha_anulacion` datetime DEFAULT NULL,
  `observacion_anulacion` varchar(100) DEFAULT NULL,
  `usuario_autorizo` char(10) DEFAULT NULL,
  `fecha_autorizo` datetime DEFAULT NULL,
  `toma_lista_dolar` char(1) DEFAULT NULL,
  `estado_pedido` char(1) DEFAULT NULL,
  `tipo_cotizacion` varchar(2) DEFAULT NULL,
  `tipo_de_servicios` char(2) DEFAULT NULL,
  `venta_renta` char(1) DEFAULT NULL,
  `fecha_reno_in` datetime DEFAULT NULL,
  `fecha_reno_fn` datetime DEFAULT NULL,
  `dias_renovacion` int(11) DEFAULT NULL,
  `excento` char(1) DEFAULT NULL,
  `numero_exoneracion` varchar(50) DEFAULT NULL,
  `precio_combustible` char(1) DEFAULT NULL,
  `usuario_cancela` char(12) DEFAULT NULL,
  `fecha_cancela` datetime DEFAULT NULL,
  `factura_renovacion` int(11) DEFAULT NULL,
  `codigo_planta` varchar(6) DEFAULT NULL,
  `numero_ots` int(11) DEFAULT NULL,
  `nivel_precio` char(2) DEFAULT NULL,
  `codigo_bodega` varchar(4) DEFAULT NULL,
  `exoneration_type_document` varchar(2) DEFAULT NULL,
  `exoneration_document_number` varchar(40) DEFAULT NULL,
  `exoneration_customer_nombre` varchar(160) DEFAULT NULL,
  `exoneration_date` datetime DEFAULT NULL,
  `exoneration_percentaje` decimal(6,2) DEFAULT NULL,
  `exoneration_amount` decimal(18,2) DEFAULT NULL,
  `exoneration_tax_neto` decimal(18,2) DEFAULT NULL,
  `retencion1` decimal(18,2) DEFAULT NULL,
  `retencion2` decimal(18,2) DEFAULT NULL,
  `id_sucursal` varchar(6) DEFAULT NULL,
  `codigo_equipo` varchar(20) DEFAULT NULL,
  `id_transporte` int(11) DEFAULT NULL,
  `no_carga` varchar(60) DEFAULT NULL,
  `id_rack` int(11) DEFAULT NULL,
  `placa_cisterna` varchar(20) DEFAULT NULL,
  `codigo_departamento` int(11) DEFAULT NULL,
  `punto_entrega` int(11) DEFAULT NULL,
  `codigo_proveedor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- tms.vta_orden_detalle definition

CREATE TABLE `vta_orden_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_orden` int(11) NOT NULL,
  `numero_de_pedido` int(11) NOT NULL,
  `codigo_producto` varchar(20) DEFAULT NULL,
  `codigo_unidad` varchar(4) DEFAULT NULL,
  `cantidad_pedida` decimal(18,2) DEFAULT NULL,
  `precio_unidad_venta` float DEFAULT NULL,
  `tiempo_renta` decimal(18,2) DEFAULT NULL,
  `porcentaje_descuento` decimal(18,2) DEFAULT NULL,
  `monto_descuento_det` decimal(18,2) DEFAULT NULL,
  `subtotal_ventas` decimal(18,2) DEFAULT NULL,
  `monto_iva` decimal(18,2) DEFAULT NULL,
  `total_linea` decimal(18,2) DEFAULT NULL,
  `porcentaje_iva` decimal(18,2) DEFAULT NULL,
  `paga_iva` char(1) DEFAULT NULL,
  `correlativo_detalle` int(11) DEFAULT NULL,
  `observacion` varchar(3000) DEFAULT NULL,
  `precio_sugerido` decimal(18,2) DEFAULT NULL,
  `precio_de_lista` float DEFAULT NULL,
  `afecta_precio` char(1) DEFAULT NULL,
  `afecta_existencia` char(1) DEFAULT NULL,
  `costo_unitario` decimal(18,5) DEFAULT NULL,
  `codigo_bodega` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;