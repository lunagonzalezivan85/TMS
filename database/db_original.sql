-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-09-2026 a las 03:29:52
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tms`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesorios_vehiculos`
--

CREATE TABLE `accesorios_vehiculos` (
  `id` int(11) NOT NULL,
  `accesorio` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp(),
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--

CREATE TABLE `accesos` (
  `id` int(11) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_actualiza` varchar(100) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `accesos`
--

INSERT INTO `accesos` (`id`, `id_rol`, `id_menu`, `fecha_registro`, `fecha_actualizacion`, `usuario_crea`, `usuario_actualiza`, `estado`) VALUES
(520, 2, 4, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(521, 2, 38, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(522, 2, 36, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(523, 2, 13, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(524, 2, 35, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(525, 2, 12, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(526, 2, 37, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(527, 2, 34, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(528, 2, 2, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(529, 2, 8, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(530, 2, 9, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(531, 2, 5, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(532, 2, 14, '2026-06-18 02:31:55', NULL, '1', NULL, 1),
(570, 3, 4, '2026-06-24 15:58:54', NULL, '1', NULL, 1),
(571, 3, 36, '2026-06-24 15:58:54', NULL, '1', NULL, 1),
(572, 3, 13, '2026-06-24 15:58:54', NULL, '1', NULL, 1),
(573, 3, 35, '2026-06-24 15:58:54', NULL, '1', NULL, 1),
(574, 3, 12, '2026-06-24 15:58:54', NULL, '1', NULL, 1),
(575, 3, 37, '2026-06-24 15:58:54', NULL, '1', NULL, 1),
(646, 1, 1, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(647, 1, 2, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(648, 1, 8, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(649, 1, 9, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(650, 1, 3, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(651, 1, 10, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(652, 1, 11, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(653, 1, 4, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(654, 1, 12, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(655, 1, 13, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(656, 1, 36, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(657, 1, 37, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(658, 1, 38, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(659, 1, 6, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(660, 1, 15, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(661, 1, 16, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(662, 1, 7, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(663, 1, 17, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(664, 1, 18, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(665, 1, 19, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(666, 1, 20, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(667, 1, 21, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(668, 1, 22, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(669, 1, 27, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(670, 1, 28, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(671, 1, 29, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(672, 1, 32, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(673, 1, 25, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(674, 1, 26, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(675, 1, 33, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(676, 1, 30, '2026-09-10 22:46:38', NULL, '1', NULL, 1),
(677, 1, 31, '2026-09-10 22:46:38', NULL, '1', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_vehiculos`
--

CREATE TABLE `asignacion_vehiculos` (
  `id` int(11) NOT NULL,
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
  `fecha_desasignacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignacion_vehiculos`
--

INSERT INTO `asignacion_vehiculos` (`id`, `id_vehiculo`, `id_conductor`, `fecha_asignacion`, `fecha_registro`, `fecha_actualizacion`, `usuario_crea`, `usuario_edita`, `estado`, `observaciones`, `id_empresa`, `motivo_desasignacion`, `fecha_desasignacion`) VALUES
(1, 2, 1, '2025-08-20 00:00:00', '2025-08-20 14:45:16', '2025-08-20 14:45:16', 1, '1', 'ACTIVA', 'NINGUNA POR EL MOMENTO', 1, NULL, NULL),
(2, 1, 2, '2025-08-22 00:00:00', '2025-08-22 01:11:55', '2025-08-22 01:11:55', 1, '1', 'ACTIVA', 'ES MI CHOFER', 1, NULL, NULL),
(3, 4, 2, '2025-08-22 00:00:00', '2025-08-22 01:55:12', '2025-08-22 02:31:07', 1, '1', 'INACTIVA', 'vjhjhgjhgkjjhkjhkjh', 1, 'sdasdasdads', '2025-08-22 02:31:07'),
(4, 6, 3, '2025-08-27 00:00:00', '2025-08-27 03:02:35', '2025-08-27 03:03:19', 1, '1', 'INACTIVA', 'GDFGFGFGDFGFGFGFG', 1, 'LA LLEVA COMO QUE FUERA MOTO DE CARRERA', '2025-08-27 03:03:19'),
(5, 6, 3, '2025-08-27 00:00:00', '2025-08-27 03:04:16', '2025-08-27 03:04:16', 1, '1', 'ACTIVA', 'DDDDDDDDDDDDDDDDDDD', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo`
--

CREATE TABLE `catalogo` (
  `id` int(11) NOT NULL,
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
  `fecha_actualiza` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `catalogo`
--

INSERT INTO `catalogo` (`id`, `codigo`, `nombre`, `descripcion`, `id_superior`, `estado`, `referencia`, `nivel`, `referencia2`, `idempresa`, `edicion`, `usuario_crea`, `usuario_actualiza`, `fecha_registro`, `fecha_actualiza`) VALUES
(1, 'CAT-0001', 'TIPO DE UNIDAD', 'Se registran los tipo de unidades', 0, 1, 'NINGUNA', 1, 'NINGUNA', 1, NULL, NULL, '1', '2025-09-28 15:47:16', '2025-09-28 22:58:23'),
(2, 'CAT-0002', 'TIPO DE DOCUMENTOS DE VEHICULOS', 'Todo los tipos de documentos que poseen los vehiculos', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-10-02 22:54:20', NULL),
(3, 'CAT-0003', 'MOTIVOS DE ASIGNACION DE COMBUSTIBLES', 'Con este catalogo se gestionan los motivo por el cual se le asignan el combustible al transportista', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-10-02 22:55:59', NULL),
(4, 'CAT-0003', 'TRASLADO', 'ESTE MOTIVO ES PARA TRASLADAR DE UN PUNTO A A UN PUNTO B', 3, 1, '', 2, '', 1, 0, '1', NULL, '2025-10-02 22:58:48', NULL),
(5, 'CAT-0004', 'TIPO DOCUMENTO DE CONDUCTORES', 'TIPO DE DOCUMENTOS QUE ENTREGAN LOS CONDUCTORES', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-10-07 01:08:04', NULL),
(6, 'CAT-0004', 'LICENCIA DE CONDUCIR', '', 5, 1, '', 2, '', 1, 0, '1', NULL, '2025-10-07 01:39:58', NULL),
(7, 'CAT-0004', 'CEDULA DE IDENTIDAD', '', 5, 1, '', 2, '', 1, 0, '1', NULL, '2025-10-07 02:32:09', NULL),
(8, 'CAT-0002', 'EMISION DE GASES', '', 2, 1, '', 2, '', 1, 0, '1', '1', '2025-10-07 02:42:21', '2026-09-09 12:14:33'),
(9, 'CAT-0002', 'CIRCULACION', '', 2, 1, '', 2, '', 1, 0, '1', '1', '2025-10-07 02:42:53', '2026-09-09 12:14:55'),
(10, 'CAT-0002', 'SEGURO', '', 2, 1, '', 2, '', 1, 0, '1', NULL, '2025-10-07 02:43:34', NULL),
(11, 'CAT-0005', 'TIPOS DE MOVIMIENTOS', 'Son todo los tipos de movimientos, ya sea traslado de inventarios, salida, ingresos, etc.', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-10-07 13:53:35', NULL),
(12, 'CAT-0006', 'BODEGAS', 'Son todas la bodegas a la que se le har├í transferencias.', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-10-07 13:54:32', NULL),
(13, 'CAT-0007', 'MOTIVO DE PAUSA DE TRABAJO', '', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-10-09 20:20:22', NULL),
(14, 'CAT-0007', 'PROCESO DE COMPRA DE MATERIALES', '', 13, 1, '', 2, '', 1, 0, '1', NULL, '2025-10-09 20:21:06', NULL),
(15, 'CAT-0001', 'Rigido', '', 1, 1, '', 2, '', 1, 0, '1', NULL, '2025-10-22 19:33:02', NULL),
(16, 'CAT-0001', 'Plataforma', '', 1, 1, '', 2, '', 1, 0, '1', '1', '2025-10-22 19:33:55', '2026-06-12 13:12:04'),
(17, 'CAT-0008', 'COLOR', 'Paleta de colores', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-10-27 23:48:35', NULL),
(18, 'CAT-0008', 'ROJO', '', 17, 1, '', 2, '', 1, 0, '1', NULL, '2025-10-27 23:49:22', NULL),
(19, 'CAT-0008', 'AZUL', '', 17, 1, '', 2, '', 1, 0, '1', NULL, '2025-10-27 23:49:59', NULL),
(20, 'CAT-0009', 'TIPO DE MOTOR', '', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-10-27 23:51:17', NULL),
(21, 'CAT-0009', 'DETROY', '', 20, 1, '', 2, '', 1, 0, '1', '1', '2025-10-27 23:52:12', '2025-11-14 19:29:25'),
(22, 'CAT-0009', 'HIBRIDO', '', 20, 1, '', 2, '', 1, 0, '1', NULL, '2025-10-27 23:52:45', NULL),
(23, 'CAT-0010', 'TIPO DE MANTENIMIENTO', '', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-11-04 01:42:44', NULL),
(24, 'CAT-0010', 'PREVENTIVO', '', 23, 1, '', 2, '', 1, 0, '1', NULL, '2025-11-04 01:43:22', NULL),
(25, 'CAT-0011', 'TIPO DE OPERACION DEL VEHICULO', '', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-11-14 19:52:45', NULL),
(26, 'CAT-0011', 'GCM', '', 25, 1, '', 2, '', 1, 0, '1', NULL, '2025-11-14 19:54:58', NULL),
(27, 'CAT-0011', 'PUMA', '', 25, 1, '', 2, '', 1, 0, '1', NULL, '2025-11-14 19:56:25', NULL),
(28, 'CAT-0001', 'CABEZAL', '', 1, 1, '', 2, '', 1, 0, '1', NULL, '2025-11-14 20:02:06', NULL),
(29, 'CAT-0011', 'ACEITE', '', 25, 1, '', 2, '', 1, 0, '1', '1', '2025-11-14 20:02:53', '2025-11-14 20:12:54'),
(30, 'CAT-0011', 'MODIFICAR LUEGO', '', 25, 1, '', 2, '', 1, 0, '1', '1', '2025-11-14 20:03:10', '2025-11-14 20:07:26'),
(31, 'CAT-0012', 'TIPO DE PRODUCTO  A TRANSPORTAR', '', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-11-14 20:04:21', NULL),
(32, 'CAT-0012', 'LIMPIO', '', 31, 1, '', 2, '', 1, 0, '1', NULL, '2025-11-14 20:04:40', NULL),
(33, 'CAT-0012', 'SUCIO', '', 31, 1, '', 2, '', 1, 0, '1', NULL, '2025-11-14 20:04:53', NULL),
(34, 'CAT-0012', 'LPG', '', 31, 1, '', 2, '', 1, 0, '1', NULL, '2025-11-14 20:06:08', NULL),
(35, 'CAT-0012', 'ACEITE', '', 31, 1, '', 2, '', 1, 0, '1', NULL, '2025-11-14 20:14:08', NULL),
(36, 'CAT-0004', 'Permiso de Bomberos', '', 5, 1, '', 2, '', 1, 0, '1', NULL, '2025-11-14 20:58:53', NULL),
(37, 'CAT-0004', 'Carnet de Puma', '', 5, 1, '', 2, '', 1, 0, '1', NULL, '2025-11-14 20:59:13', NULL),
(38, 'CAT-0013', 'CORREO DE NOTIFICACIÓN', '', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2025-11-17 19:43:08', NULL),
(39, 'CAT-0001', 'CISTERNAS', '', 1, 1, '', 2, '', 1, 0, '1', NULL, '2026-05-06 15:42:00', NULL),
(40, 'CAT-0014', 'CONCEPTO DE SALIDA', '', NULL, 1, '', 1, '', 1, 0, '1', '1', '2026-05-21 12:48:24', '2026-07-13 16:14:35'),
(41, 'CAT-0014', 'CONSUMO INTERNO', '', 40, 1, '6', 2, '6', 1, 0, '1', '1', '2026-05-21 12:49:17', '2026-07-14 14:07:28'),
(42, 'CAT-0014', 'CONSUMO EXTERNO', '', 40, 1, '7', 2, '7', 1, 0, '1', '1', '2026-05-21 12:49:57', '2026-07-14 14:07:51'),
(43, 'CAT-0015', 'BOMBAS', 'SE ADMINISTRAN LAS BOMBAS DE CONSUMO', NULL, 1, '', 1, '', 1, 0, '1', NULL, '2026-06-02 12:00:27', NULL),
(44, 'CAT-0015', 'B01 - TANQUE DE CONSUMO 2850 GLNS', 'TANQUE DE CONSUMO 2850 GLNS', 43, 1, 'B01', 2, '2850 GLNS', 1, 0, '1', '1', '2026-06-02 12:01:02', '2026-06-22 21:02:55'),
(45, 'CAT-0015', 'B02 - TANQUES DE CONSUMO 2850 GLNS', 'TANQUES DE CONSUMO 2850 GLNS', 43, 1, 'B02', 2, '2850 GLNS', 1, 0, '1', '1', '2026-06-02 12:01:29', '2026-06-23 14:41:45'),
(46, 'CAT-0015', 'B03 - TANQUES DE CONSUMO 500 GLNS', 'TANQUES DE CONSUMO 500 GLNS', 43, 1, '500 GLNS', 2, '', 1, 0, '1', '1', '2026-06-22 21:04:35', '2026-06-22 21:05:42'),
(47, 'CAT-0014', 'VEHICULO ADMINISTRACION', '', 40, 1, '5', 2, '5', 1, 0, '1', '1', '2026-07-14 14:02:39', '2026-07-14 14:08:26'),
(48, 'CAT-0002', 'Permiso de Bomberos', '', 2, 1, 'Permiso de bomeberos', 2, '', 1, 0, '1', NULL, '2026-09-10 15:40:42', NULL),
(49, 'CAT-0002', 'Prueba Valvula de alivio', 'Se utiliza apara la documentación de las cisternas y equipos integrados', 2, 1, 'Prueba de válvula de alivio', 2, '', 1, 0, '1', NULL, '2026-09-11 08:55:48', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductores`
--

CREATE TABLE `conductores` (
  `id` int(11) NOT NULL,
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
  `codigoERP` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `conductores`
--

INSERT INTO `conductores` (`id`, `id_empresa`, `codigo_consecutivo`, `nombre`, `apellido`, `dni`, `fechaIngreso`, `estado`, `fechaRegistro`, `fechaUpdate`, `usuarioCrea`, `usuarioEdita`, `licencia`, `fechaVencimientoLicencia`, `carnet`, `telefono`, `codigoERP`) VALUES
(10, 1, 'COND-001', 'Jorge Gregorio Ney Almendárez', '', '001-011179-0010X', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:28', '2025-11-14 20:43:28', 1, NULL, NULL, '2025-11-14 14:43:28', '0045', NULL, NULL),
(12, 1, 'COND-002', 'Denis Alberto Corea Olivas', '', '001-220171-0006S', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:55', '2025-11-14 20:43:55', 1, NULL, NULL, '2025-11-14 14:43:55', '0046', NULL, NULL),
(13, 1, 'COND-003', 'Wilbert Antonio Santana Acevedo', '', '084-090570-0005N', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:56', '2025-11-14 20:43:56', 1, NULL, NULL, '2025-11-14 14:43:56', '0047', NULL, NULL),
(14, 1, 'COND-004', 'José Santos Guido Somarriba', '', '281-221272-0011J', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:56', '2025-11-14 20:43:56', 1, NULL, NULL, '2025-11-14 14:43:56', '0057', NULL, NULL),
(15, 1, 'COND-005', 'Otoniel Israel Vásquez Chávez', '', '001-100884-0013Y', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:57', '2025-11-14 20:43:57', 1, NULL, NULL, '2025-11-14 14:43:57', '0059', NULL, NULL),
(16, 1, 'COND-006', 'Fernando José Martínez López', '', '001-130490-0055S', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:57', '2025-11-14 20:43:57', 1, NULL, NULL, '2025-11-14 14:43:57', '0062', NULL, NULL),
(17, 1, 'COND-007', 'Felipe Ramón Guido Somarriba', '', '281-200971-0018X', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:57', '2025-11-14 20:43:57', 1, NULL, NULL, '2025-11-14 14:43:57', '0064', NULL, NULL),
(18, 1, 'COND-008', 'Ronald Antonio Porras Palacios', '', '001-250390-0003F', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:58', '2025-11-14 20:43:58', 1, NULL, NULL, '2025-11-14 14:43:58', '0066', NULL, NULL),
(19, 1, 'COND-009', 'Jorge Ulises Ney Dávila', '', '001-300779-0006F', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:58', '2025-11-14 20:43:58', 1, NULL, NULL, '2025-11-14 14:43:58', '0067', NULL, NULL),
(20, 1, 'COND-010', 'Reynaldo José Sánchez Gutiérrez', '', '001-200864-0055A', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:58', '2025-11-14 20:43:58', 1, NULL, NULL, '2025-11-14 14:43:58', '0068', NULL, NULL),
(21, 1, 'COND-011', 'Dionisio Gilberto Rios Pulidos', '', '290-010583-0000C', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:59', '2025-11-14 20:43:59', 1, NULL, NULL, '2025-11-14 14:43:59', '0069', NULL, NULL),
(22, 1, 'COND-012', 'Rolando José Silva Poveda', '', '284-260184-0004A', '0000-00-00', 'ACTIVO', '2025-11-14 20:43:59', '2025-11-14 20:43:59', 1, NULL, NULL, '2025-11-14 14:43:59', '0070', NULL, NULL),
(23, 1, 'COND-013', 'Julio José Mendoza Ruíz', '', '001-080300-1009B', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:00', '2025-11-14 20:44:00', 1, NULL, NULL, '2025-11-14 14:44:00', '0074', NULL, NULL),
(24, 1, 'COND-014', 'Roberto Aldomar Chinchilla Mejía', '', '004-140777-0001D', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:00', '2025-11-14 20:44:00', 1, NULL, NULL, '2025-11-14 14:44:00', '0075', NULL, NULL),
(25, 1, 'COND-015', 'Enrique Yamir Varela Gutiérrez', '', '001-180799-1016T', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:00', '2025-11-14 20:44:00', 1, NULL, NULL, '2025-11-14 14:44:00', '0076', NULL, NULL),
(26, 1, 'COND-016', 'Agustín  Miranda ', '', '603-270878-0004D', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:01', '2025-11-14 20:44:01', 1, NULL, NULL, '2025-11-14 14:44:01', '0078', NULL, NULL),
(27, 1, 'COND-017', 'Laureano del Pilar Moran Peña', '', '284-121067-0000R', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:01', '2025-11-14 20:44:01', 1, NULL, NULL, '2025-11-14 14:44:01', '0079', NULL, NULL),
(28, 1, 'COND-018', 'Héctor Vladimir Flores López', '', '001-170584-0018A', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:02', '2025-11-14 20:44:02', 1, NULL, NULL, '2025-11-14 14:44:02', '0081', NULL, NULL),
(29, 1, 'COND-019', 'Evert Antonio Solorzano Carvajal', '', '001-270694-0028K', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:02', '2025-11-14 20:44:02', 1, NULL, NULL, '2025-11-14 14:44:02', '0100', NULL, NULL),
(30, 1, 'COND-020', 'Ronald Antonio Guerrero Solorzano', '', '001-221175-0072H', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:02', '2025-11-14 20:44:02', 1, NULL, NULL, '2025-11-14 14:44:02', '0101', NULL, NULL),
(31, 1, 'COND-021', 'Felipe Ramón Guido Silva', '', '006-081093-0000G', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:03', '2025-11-14 20:44:03', 1, NULL, NULL, '2025-11-14 14:44:03', '0105', NULL, NULL),
(32, 1, 'COND-022', 'Miguel Ernesto Flores Guadamuz', '', '001-290985-0043Y', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:03', '2025-11-14 20:44:03', 1, NULL, NULL, '2025-11-14 14:44:03', '0106', NULL, NULL),
(33, 1, 'COND-023', 'Mario Francisco Romero González', '', '003-120983-0002N', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:04', '2025-11-14 20:44:04', 1, NULL, NULL, '2025-11-14 14:44:04', '0119', NULL, NULL),
(34, 1, 'COND-024', 'Lesther José Espinoza Martínez', '', '001-030590-0020P', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:04', '2025-11-14 20:44:04', 1, NULL, NULL, '2025-11-14 14:44:04', '0123', NULL, NULL),
(35, 1, 'COND-025', 'Juan Ramón Torres Gámez', '', '001-040663-0057K', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:04', '2025-11-14 20:44:04', 1, NULL, NULL, '2025-11-14 14:44:04', '0126', NULL, NULL),
(36, 1, 'COND-026', 'Wilmer José Gutierrez Morales', '', '001-250486-0052M', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:05', '2025-11-14 20:44:05', 1, NULL, NULL, '2025-11-14 14:44:05', '0127', NULL, NULL),
(37, 1, 'COND-027', 'José Luis Parrales Gonzalez', '', '001-020193-0004C', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:05', '2025-11-14 20:44:05', 1, NULL, NULL, '2025-11-14 14:44:05', '0131', NULL, NULL),
(38, 1, 'COND-028', 'Juan Carlos Centeno Marin', '', '001-080378-0012T', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:06', '2025-11-14 20:44:06', 1, NULL, NULL, '2025-11-14 14:44:06', '0132', NULL, NULL),
(39, 1, 'COND-029', 'Ricardo Arturo Mayorga Aragón', '', '401-121187-0007G', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:06', '2025-11-14 20:44:06', 1, NULL, NULL, '2025-11-14 14:44:06', '0140', NULL, NULL),
(40, 1, 'COND-030', 'Martín Alonso Jiménez ', '', '492-160378-0001N', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:06', '2025-11-14 20:44:06', 1, NULL, NULL, '2025-11-14 14:44:06', '0143', NULL, NULL),
(41, 1, 'COND-031', 'Eladio Agustín Peralta Arauz', '', '291-291279-0000X', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:07', '2025-11-14 20:44:07', 1, NULL, NULL, '2025-11-14 14:44:07', '0147', NULL, NULL),
(42, 1, 'COND-032', 'German Luis Valle ', '', '284-111071-0000S', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:07', '2025-11-14 20:44:07', 1, NULL, NULL, '2025-11-14 14:44:07', '0150', NULL, NULL),
(43, 1, 'COND-033', 'Ismael Abraham Rizo García', '', '008-060801-1000L', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:08', '2025-11-14 20:44:08', 1, NULL, NULL, '2025-11-14 14:44:08', '0153', NULL, NULL),
(44, 1, 'COND-034', 'Hanio Antonio Vargas Matamoros', '', '084-190684-0000K', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:08', '2025-11-14 20:44:08', 1, NULL, NULL, '2025-11-14 14:44:08', '0156', NULL, NULL),
(45, 1, 'COND-035', 'Julio César Ríos Rocha', '', '365-290486-0001V', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:08', '2025-11-14 20:44:08', 1, NULL, NULL, '2025-11-14 14:44:08', '0157', NULL, NULL),
(46, 1, 'COND-036', 'Alvaro José Pettyn Téllez', '', '001-251272-0004N', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:09', '2025-11-14 20:44:09', 1, NULL, NULL, '2025-11-14 14:44:09', '0161', NULL, NULL),
(47, 1, 'COND-037', 'Gerlyn Javier Cuarezma García', '', '001-150294-0001D', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:09', '2025-11-14 20:44:09', 1, NULL, NULL, '2025-11-14 14:44:09', '0164', NULL, NULL),
(48, 1, 'COND-038', 'Bismarck Antonio Gutiérrez Obando', '', '002-081291-0004Q', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:10', '2025-11-14 20:44:10', 1, NULL, NULL, '2025-11-14 14:44:10', '0165', NULL, NULL),
(49, 1, 'COND-039', 'Wiston Raúl Domínguez De La Rocha', '', '001-020572-0096S', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:10', '2025-11-14 20:44:10', 1, NULL, NULL, '2025-11-14 14:44:10', '0166', NULL, NULL),
(50, 1, 'COND-040', 'Oscar Ramón Sánchez Zavala', '', '002-310789-0000P', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:11', '2025-11-14 20:44:11', 1, NULL, NULL, '2025-11-14 14:44:11', '0167', NULL, NULL),
(51, 1, 'COND-041', 'Alvaro Gregorio Gutiérrez Reyes', '', '241-130373-0010D', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:11', '2025-11-14 20:44:11', 1, NULL, NULL, '2025-11-14 14:44:11', '0168', NULL, NULL),
(52, 1, 'COND-042', 'Milton José Mendoza Alanis', '', '001-110581-0010U', '0000-00-00', 'ACTIVO', '2025-11-14 20:44:11', '2025-11-14 20:44:11', 1, NULL, NULL, '2025-11-14 14:44:11', '0169', NULL, NULL),
(53, 1, 'COND-043', 'Luis Felipe ', 'Estrada Olivares', '0011611800028U', '2025-12-17', 'ACTIVO', '2026-01-21 17:09:38', '2026-01-21 17:21:12', 1, 1, NULL, '2026-01-21 11:09:38', '0060', NULL, NULL),
(54, 1, 'COND-044', 'Marlon Javier Reyes Delgado', '', '001-021003-1016A', '0000-00-00', 'ACTIVO', '2026-08-04 11:03:12', '2026-08-04 11:03:12', 1, NULL, NULL, '2026-08-04 05:03:12', '0130', NULL, NULL),
(55, 1, 'COND-045', 'Gabriel Antonio López Quiroz', '', '001-020699-1014A', '0000-00-00', 'ACTIVO', '2026-08-12 08:04:52', '2026-08-12 08:04:52', 1, NULL, NULL, '2026-08-12 08:04:52', '0141', NULL, NULL),
(57, 1, 'COND-046', 'Sergio Antonio Manzanarez López', '', '001-170685-0016Y', '0000-00-00', 'ACTIVO', '2026-08-12 08:05:18', '2026-08-12 08:05:18', 1, NULL, NULL, '2026-08-12 08:05:18', '0177', NULL, NULL),
(58, 1, 'COND-047', 'Kener José Martínez Zuniga', '', '047-110589-0000Q', '0000-00-00', 'ACTIVO', '2026-08-12 08:05:18', '2026-08-12 08:05:18', 1, NULL, NULL, '2026-08-12 08:05:18', '0178', NULL, NULL),
(59, 1, 'COND-048', 'Rolando Exequiel Arana ', '', '081-111069-0007F', '0000-00-00', 'ACTIVO', '2026-08-12 08:05:18', '2026-08-12 08:05:18', 1, NULL, NULL, '2026-08-12 08:05:18', '0185', NULL, NULL),
(60, 1, 'COND-049', 'Ulises Joel Bautista Gómez', '', '001-140100-1011D', '0000-00-00', 'ACTIVO', '2026-08-12 08:05:18', '2026-08-12 08:05:18', 1, NULL, NULL, '2026-08-12 08:05:18', '0186', NULL, NULL),
(61, 1, 'COND-050', 'Moisés Isaac Gutiérrez Valerio', '', '002-241193-0000D', '0000-00-00', 'ACTIVO', '2026-08-12 08:05:19', '2026-08-12 08:05:19', 1, NULL, NULL, '2026-08-12 08:05:19', '0189', NULL, NULL),
(62, 1, 'COND-051', 'Alberto Enrique Rodríguez Núñez', '', '281-151182-0015M', '0000-00-00', 'ACTIVO', '2026-08-12 08:05:19', '2026-08-12 08:05:19', 1, NULL, NULL, '2026-08-12 08:05:19', '0190', NULL, NULL),
(63, 1, 'COND-052', 'Omar Patricio Osejo Amador', '', '441-240882-0002F', '0000-00-00', 'ACTIVO', '2026-08-12 08:05:19', '2026-08-12 08:05:19', 1, NULL, NULL, '2026-08-12 08:05:19', '0192', NULL, NULL),
(64, 1, 'COND-053', 'Edgard Eduardo Velásquez Gutiérrez', '', '001-070705-1057A', '0000-00-00', 'ACTIVO', '2026-08-12 08:05:20', '2026-08-12 08:05:20', 1, NULL, NULL, '2026-08-12 08:05:20', '0193', NULL, NULL),
(65, 1, 'COND-054', 'Lester Enrique Sequeira Solís', '', '201-030681-0001C', '0000-00-00', 'ACTIVO', '2026-08-12 08:05:20', '2026-08-12 08:05:20', 1, NULL, NULL, '2026-08-12 08:05:20', '0194', NULL, NULL),
(66, 1, 'COND-055', 'Juan Rafael Gaitán Chévez', '', '121-231082-0003C', '0000-00-00', 'ACTIVO', '2026-09-01 12:00:17', '2026-09-01 12:00:17', 1, NULL, NULL, '2026-09-01 12:00:17', '0196', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consecutivos_detalle`
--

CREATE TABLE `consecutivos_detalle` (
  `id` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `tabla` varchar(50) NOT NULL,
  `numero` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `consecutivos_detalle`
--

INSERT INTO `consecutivos_detalle` (`id`, `id_empresa`, `tabla`, `numero`, `codigo`, `fechaRegistro`) VALUES
(1, 1, 'vehiculos', 1, 'GMV001-0001', '2025-08-08 15:20:43'),
(2, 1, 'vehiculos', 2, 'GMV001-0002', '2025-08-19 18:48:39'),
(3, 1, 'vehiculos', 3, 'GMV001-0003', '2025-08-21 18:09:59'),
(4, 1, 'vehiculos', 4, 'GMV001-0004', '2025-08-21 18:15:13'),
(5, 1, 'vehiculos', 5, 'GMV001-0005', '2025-08-21 18:18:01'),
(6, 1, 'vehiculos', 6, 'GMV001-0006', '2025-08-26 20:01:49'),
(7, 1, 'vehiculos', 7, 'GMV001-0007', '2025-10-27 17:51:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumo_materiales`
--

CREATE TABLE `consumo_materiales` (
  `id` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `codigo_consecutivo` varchar(50) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `costo_total` decimal(10,2) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuarioCrea` int(11) DEFAULT NULL,
  `usuarioEdita` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones`
--

CREATE TABLE `cotizaciones` (
  `id` int(11) UNSIGNED NOT NULL,
  `numero_cotizacion` varchar(20) NOT NULL,
  `producto_id` varchar(50) NOT NULL DEFAULT 'diesel',
  `producto_nombre` varchar(100) DEFAULT NULL,
  `cliente_nombre` varchar(200) NOT NULL,
  `cliente_ruc` varchar(40) DEFAULT NULL,
  `cliente_telefono` varchar(40) DEFAULT NULL,
  `origen` varchar(200) DEFAULT NULL,
  `destino` varchar(200) DEFAULT NULL,
  `tipo_vehiculo` varchar(100) NOT NULL DEFAULT 'Camion rigido',
  `distancia_km` decimal(10,2) NOT NULL DEFAULT 0.00,
  `volumen_galones` decimal(12,2) NOT NULL DEFAULT 0.00,
  `rendimiento_km_galon` decimal(8,2) NOT NULL DEFAULT 0.00,
  `precio_combustible_galon` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `costo_viaje` decimal(14,2) NOT NULL DEFAULT 0.00,
  `margen_porcentaje` decimal(6,2) NOT NULL DEFAULT 0.00,
  `margen_monto` decimal(14,2) NOT NULL DEFAULT 0.00,
  `precio_sugerido` decimal(14,2) NOT NULL DEFAULT 0.00,
  `precio_final` decimal(14,2) NOT NULL DEFAULT 0.00,
  `flete_por_galon` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `desglose_costos` text DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'BORRADOR',
  `usuario_crea` varchar(100) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_actualiza` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_movimiento`
--

CREATE TABLE `detalle_movimiento` (
  `id` int(11) NOT NULL,
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
  `fecha_actualiza` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_movimiento`
--

INSERT INTO `detalle_movimiento` (`id`, `id_movimiento`, `id_material`, `cantidad`, `precio`, `impuesto`, `linea`, `subtotal`, `total_linea`, `usuario_crea`, `usuario_edita`, `id_empresa`, `fecha_registro`, `fecha_actualiza`) VALUES
(1, 1, 64, 1, 0, 0, 1, 0, 0, 'ADMIN', NULL, 1, '2025-10-02 17:41:13', NULL),
(2, 2, 87, 1, 112, 0, 1, 112, 112, 'ADMIN', NULL, 1, '2025-10-07 03:52:47', NULL),
(3, 2, 68, 1, 802, 0, 2, 802, 802, 'ADMIN', NULL, 1, '2025-10-07 03:52:48', NULL),
(4, 3, 87, 1, 112, 0, 1, 112, 112, 'ADMIN', NULL, 1, '2025-10-08 01:40:47', NULL),
(5, 4, 64, 1, 20, 0, 1, 20, 20, 'ADMIN', NULL, 1, '2025-10-13 21:52:23', NULL),
(6, 4, 87, 10, 112, 0, 2, 1118, 1118, 'ADMIN', NULL, 1, '2025-10-13 21:52:23', NULL),
(7, 5, 64, 1, 20, 0, 1, 20, 20, 'ADMIN', NULL, 1, '2025-10-13 21:52:31', NULL),
(8, 5, 87, 10, 112, 0, 2, 1118, 1118, 'ADMIN', NULL, 1, '2025-10-13 21:52:31', NULL),
(9, 6, 66, 10, 0, 0, 1, 0, 0, 'ADMIN', NULL, 1, '2025-10-22 21:32:35', NULL),
(10, 7, 1, 1, 400, 0, 1, 400, 400, 'ADMIN', NULL, 1, '2025-11-04 20:29:20', NULL),
(11, 7, 81, 1, 0, 0, 2, 0, 0, 'ADMIN', NULL, 1, '2025-11-04 20:29:20', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id` int(11) NOT NULL,
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
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id`, `direccion`, `codigo_integracion`, `longitud`, `latitud`, `ciudad`, `usuario_crea`, `usuario_actualizacion`, `fecha_registro`, `fecha_actualizacion`, `estado`, `id_empresa`, `nombre`) VALUES
(1, 'Incesa standar 110 vrs al sur', '', '-86.223364', '12.148311', 'Managua', '1', NULL, '2025-09-18 03:56:36', '2025-09-17 20:57:23', 1, 1, NULL),
(2, '56a Avenida S.E., Santa Rosa, Distrito IV, Managua, 11095, Nicaragua', '', '-86.224002', '12.148963', 'Managua', '1', NULL, '2025-09-18 04:15:35', '2025-09-17 21:16:22', 1, 1, NULL),
(3, 'Hac. Sta Cecilia Km 165 Carretra Guasaule                                                                                                                                                                                                                      ', '5', '-86.2387045', '12.1303048', 'MANAGUA', '1', '1', '2025-09-18 05:00:57', '2025-09-18 05:16:58', 1, 1, 'PUMA ENERGIE GUASAULE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentacion_conductor`
--

CREATE TABLE `documentacion_conductor` (
  `id` int(11) NOT NULL,
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
  `notificacion` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentacion_conductor`
--

INSERT INTO `documentacion_conductor` (`id`, `tipo_documento`, `observacion`, `ruta_documento`, `fechaRegistro`, `fechaUpdate`, `usuario_crea`, `usuario_actualiza`, `fecha_vencimiento`, `IdConductor`, `estado`, `fecha_actualizacion`, `id_empresa`, `numero_documento`, `fecha_emision`, `observaciones`, `notificacion`) VALUES
(10, '37', NULL, 'uploads/documentos/1763154040_1e4340d136b049c0d2a2.png', '2025-11-14 21:00:40', '2026-09-09 10:00:35', 1, 1, '2025-11-15 00:00:00', 50, 'VENCIDO', '2025-11-14 15:00:40', NULL, '73629', '2025-07-29 00:00:00', '', 0),
(11, '36', NULL, 'uploads/documentos/1763155860_7d3c8268ded8a683c95d.jpg', '2025-11-14 21:31:00', '2026-09-09 10:00:35', 1, 1, '2025-11-24 00:00:00', 50, 'VENCIDO', '2025-11-14 15:31:00', NULL, '10922', '2025-06-11 00:00:00', '', 0),
(12, '36', NULL, 'uploads/documentos/1769015480_61855c3a7aa956debf63.png', '2026-01-21 17:11:20', '2026-09-09 10:00:35', 1, 1, '2026-01-22 00:00:00', 53, 'VENCIDO', '2026-01-21 11:11:20', NULL, '83839', '2026-01-01 00:00:00', 'Prueba de Gestion', 0),
(13, '6', NULL, 'uploads/documentos/1788969634_f751427ce773ab6ac5ab.pdf', '2026-09-09 10:00:34', '2026-09-09 10:00:35', 1, 1, '2026-09-30 00:00:00', 33, 'POR_VENCER', '2026-09-09 10:00:34', NULL, '847475', '2026-09-01 00:00:00', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_vehiculos`
--

CREATE TABLE `documentos_vehiculos` (
  `id` int(11) NOT NULL,
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
  `numero` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos_vehiculos`
--

INSERT INTO `documentos_vehiculos` (`id`, `id_vehiculo`, `tipo_documento`, `observaciones`, `ruta_archivo`, `fecha_registro`, `fechaUpdate`, `usuario_crea`, `usuario_edita`, `fecha_vencimiento`, `nombre_archivo`, `notificacion`, `numero`) VALUES
(1, 35, '8', '', '1763155681_ce0d056155882de235c3.png', '2025-11-14 21:28:01', '2025-11-14 15:28:01', 1, NULL, '2025-11-15 00:00:00', '1763155681_ce0d056155882de235c3.png', 15, '45637'),
(2, 35, '8', 'sfdfdfdfdfsdfdf fdsfdf dfsdfsdf', '1789104082_fff23ac4a531f5440e33.jpg', '2026-09-10 23:21:22', '2026-09-10 23:21:22', 1, NULL, '2026-09-02 00:00:00', '1789104082_fff23ac4a531f5440e33.jpg', NULL, 'COT-2026-0001'),
(3, 89, '8', 'Emision de Gases, verlo con el taller X', '1789138835_aec78cabf51ee2f7fa0e.pdf', '2026-09-11 09:00:35', '2026-09-11 09:00:35', 1, NULL, '2026-09-18 00:00:00', '1789138835_aec78cabf51ee2f7fa0e.pdf', 10, '12344');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
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
  `estado` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nombre`, `ruc`, `direccion`, `telefono`, `correo`, `codigo_inicial`, `serie_documento`, `fechaRegistro`, `fechaUpdate`, `usuarioCrea`, `usuarioEdita`, `estado`) VALUES
(1, 'GCM Transportes, S.A', '1234567', 'MANAGUA', '22334455', 'admin@gcmtransporte.com', 'GMV', '001', '2025-08-08 19:30:46', '2025-08-08 19:30:46', 1, 1, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_estado_vehiculo`
--

CREATE TABLE `historial_estado_vehiculo` (
  `id` int(11) NOT NULL,
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
  `id_conductor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_orden_trabajo`
--

CREATE TABLE `historial_orden_trabajo` (
  `id` int(11) NOT NULL,
  `id_solicitud` int(11) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `usuario_registra` varchar(100) DEFAULT NULL,
  `comentario` varchar(100) DEFAULT NULL,
  `referencia` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(11) UNSIGNED NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` varchar(80) NOT NULL DEFAULT 'combustible',
  `unidad_medida` varchar(20) NOT NULL DEFAULT 'galon',
  `costo_galon` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `precio_galon` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_actualiza` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `codigo`, `nombre`, `descripcion`, `categoria`, `unidad_medida`, `costo_galon`, `precio_galon`, `estado`, `fecha_creacion`, `fecha_actualiza`) VALUES
(1, 'diesel', 'Diesel', 'Combustible Diesel estándar', 'combustible', 'galon', 149.3800, 149.3879, 1, '2026-06-22 20:55:43', '2026-06-22 20:55:43'),
(2, 'maxxima_regular', 'Maxxima Regular', 'Gasolina Maxxima Regular', 'combustible', 'galon', 161.0800, 161.0800, 1, '2026-06-22 20:55:43', '2026-06-22 20:55:43'),
(3, 'maxxima_premium', 'Maxxima Premium', 'Gasolina Maxxima Premium', 'combustible', 'galon', 165.5900, 165.5900, 1, '2026-06-22 20:55:43', '2026-06-22 20:55:43'),
(4, 'jet_a1', 'Jet A1', 'Combustible de aviación Jet A1', 'combustible', 'galon', 126.4400, 126.4400, 1, '2026-06-22 20:55:43', '2026-06-22 20:55:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lectura_bomba`
--

CREATE TABLE `lectura_bomba` (
  `id` int(11) NOT NULL,
  `id_centro_costo` varchar(20) NOT NULL,
  `lectura_inicial_litros` float NOT NULL,
  `lectura_inicial_galones` float NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `estado` varchar(20) NOT NULL CHECK (`estado` in ('normal','anomalia')),
  `observaciones` text DEFAULT NULL,
  `referencia_1` varchar(100) DEFAULT NULL,
  `referencia_2` varchar(100) DEFAULT NULL,
  `referencia_3` varchar(100) DEFAULT NULL,
  `fecha_apertura` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_apertura` varchar(50) NOT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `lectura_final_litros` float DEFAULT NULL,
  `lectura_final_galones` float DEFAULT NULL,
  `observaciones_cierre` text DEFAULT NULL,
  `usuario_cierre` varchar(50) DEFAULT NULL,
  `consumo_turno_litros` float DEFAULT NULL,
  `consumo_turno_galones` float DEFAULT NULL,
  `ingreso_tanque` float DEFAULT 0,
  `salida_despachada` float DEFAULT 0,
  `litraje_inicial_ltr` float DEFAULT 0,
  `litraje_inicial_gal` float DEFAULT NULL,
  `litraje_final_ltr` float DEFAULT NULL,
  `litraje_final_gal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lectura_bomba`
--

INSERT INTO `lectura_bomba` (`id`, `id_centro_costo`, `lectura_inicial_litros`, `lectura_inicial_galones`, `foto`, `estado`, `observaciones`, `referencia_1`, `referencia_2`, `referencia_3`, `fecha_apertura`, `usuario_apertura`, `fecha_cierre`, `lectura_final_litros`, `lectura_final_galones`, `observaciones_cierre`, `usuario_cierre`, `consumo_turno_litros`, `consumo_turno_galones`, `ingreso_tanque`, `salida_despachada`, `litraje_inicial_ltr`, `litraje_inicial_gal`, `litraje_final_ltr`, `litraje_final_gal`) VALUES
(23, '45', 3480.97, 919.68, '1785774525_e5f1d3dcaf564c34cddf.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-03 10:28:45', 'Bombero2', '2026-08-03 20:51:16', 5988.2, 1582.09, 'Cierre 3 de agosto', 'Bombero2', 2507.23, 662.407, 5299, 1326.71, 6074.8, 1604.97, 8874.4, 2344.62),
(24, '44', 46476.7, 12279.2, '1785774682_f5fdef95eaee623b14c2.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-03 10:31:22', 'Bombero2', '2026-08-03 20:52:17', 47803.4, 12629.7, 'Cierre 3 de agosto', 'Bombero2', 1326.66, 350.485, 8705.5, 1326.71, 1244.1, 328.692, 8626, 2279),
(25, '45', 5988.02, 1582.04, '1785853209_ffa2d6a143709f5c2598.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-04 08:20:09', 'ADMIN', '2026-08-04 10:41:33', 5988.02, 1582.04, '', 'Bombero2', 0.001, -0.0001, 0, 0, 8867.4, 2342.77, 8867.4, 2342.77),
(26, '44', 47803.4, 12629.7, '1785853248_3e23d9004b4ab9bf5f87.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-04 08:20:48', 'ADMIN', '2026-08-04 10:42:48', 47803.4, 12629.7, '', 'Bombero2', 0.01, -0.0012, 0, 0, 8616.1, 2276.38, 8616.1, 2276.38),
(27, '45', 5988.02, 1582.04, '1785861724_0909a97cb3510a19cf2a.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-04 10:42:04', 'Bombero2', '2026-08-04 10:40:20', 10391.4, 2745.43, 'CIERRE 04 DE AGOSTO 2026', 'Bombero2', 4403.42, 1163.39, 0, 568.34, 8867.4, 2342.77, 4474.7, 1182.22),
(28, '44', 47803.4, 12629.7, '1785861961_a6fe6f496ae9b5c2baf9.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-04 10:46:01', 'Bombero2', '2026-08-05 10:40:49', 47864.9, 12646, 'CIERRE 04 DE AGOSTO 2026', 'Bombero2', 61.536, 16.254, 0, 568.34, 8616.1, 2276.38, 8549.5, 2258.78),
(29, '45', 10391.4, 2745.43, '1785938905_767f226fe4a7298fd9ce.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-05 08:08:25', 'Bombero2', '2026-08-05 22:35:31', 13526.2, 3573.64, 'Cierre 05 de agosto', 'Bombero2', 3134.82, 828.207, 0, 4838.88, 4470.6, 1181.14, 1317.4, 348.058),
(30, '44', 47864.9, 12646, '1785938949_8b8c0c2ad5ff4d79cce2.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-05 08:09:09', 'Bombero2', '2026-08-05 22:34:01', 49000.6, 12946, 'Cierre 05 de agosto ', 'Bombero2', 1135.71, 300.001, 0, 4838.88, 8541.6, 2256.7, 1317.4, 348.058),
(31, '45', 13526.2, 3573.64, '1786025636_cea878bf039d1e810b14.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-06 08:13:56', 'Bombero2', '2026-08-06 22:43:00', 13772.4, 3638.68, 'Cierre 06 de agosto ', 'Bombero2', 246.212, 65.0422, 7127.15, 4743.64, 1317.4, 348.058, 8206.6, 2168.19),
(32, '44', 49000.6, 12946, '1786025687_caae08ecb0b089d81e46.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-06 08:14:47', 'Bombero2', '2026-08-06 22:41:21', 53498, 14134.2, 'Cierre 06 de agosto', 'Bombero2', 4497.42, 1188.22, 0, 4743.64, 7404.7, 1956.33, 2912.1, 769.379),
(33, '45', 13772.4, 3638.68, '1786114406_45d99522ca2199d9be14.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-07 08:53:26', 'Bombero2', '2026-08-07 22:37:17', 16277, 4300.39, 'Cierre 07 de agosto', 'Bombero2', 2504.58, 661.711, 1892.5, 4173.33, 8206.6, 2168.19, 7609.9, 2010.54),
(34, '44', 53498, 14134.2, '1786114449_867f2217c3188a4a80aa.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-07 08:54:09', 'Bombero2', '2026-08-07 22:38:18', 55237.1, 14593.7, 'Cierre 07 de agosto', 'Bombero2', 1739.15, 459.499, 7721.4, 4173.33, 2912.1, 769.379, 8891, 2349.01),
(35, '45', 16277, 4300.39, '1786198765_5e8b47521a8b4ef875dc.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-08 08:19:25', 'Bombero2', '2026-08-08 22:25:13', 21688.1, 5730.01, 'CIERRE 08 DE AGOSTO', 'Bombero2', 5411.08, 1429.62, 0, 5411.14, 7607.1, 2009.8, 2196.5, 580.317),
(36, '44', 55237.1, 14593.7, '1786198841_e4e577398bc355e6d6f3.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-08 08:20:41', 'Bombero2', '2026-08-08 22:26:07', 55237.1, 14593.7, 'CIERRE 08 DE AGOSTO ', 'Bombero2', 0.049, -0.0015, 0, 5411.14, 8883.6, 2347.05, 8882.7, 2346.82),
(37, '45', 21688.1, 5730.01, '1786374061_1c8f95caaf71a2366106.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-10 09:01:01', 'Bombero2', '2026-08-10 22:29:43', 24074.4, 6360.47, 'CIERRE 10 DE AGOSTO', 'Bombero2', 2386.26, 630.455, 3785, 4475.09, 2197.4, 580.555, 3621.4, 956.777),
(38, '44', 55237.1, 14593.7, '1786374105_8f21be689bd398b3eced.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-10 09:01:45', 'Bombero2', '2026-08-10 22:30:39', 57325.9, 15145.6, 'CIERRE 10 DE AGOSTO', 'Bombero2', 2088.83, 551.857, 0, 4475.09, 8880.3, 2346.18, 6803, 1797.36),
(39, '45', 24074.4, 6360.47, '1786457270_5358b342b1c7aca0c38d.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-11 08:07:50', 'Bombero2', '2026-08-11 22:56:15', 26503.6, 7002.28, 'CIERRE 11 DE AGOSTO', 'Bombero2', 2429.25, 641.814, 0, 3639.58, 3619.2, 956.195, 1175.7, 310.621),
(40, '44', 57325.9, 15145.6, '1786457322_fadc3fa00afda0627b34.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-11 08:08:42', 'Bombero2', '2026-08-11 22:57:17', 57841.5, 15281.8, 'CIERRE 11 DE AGOSTO', 'Bombero2', 515.623, 136.176, 0, 3639.58, 6798, 1796.04, 6290.5, 1661.96),
(41, '46', 28502.7, 7530.45, '1786466556_f7f055899fbba869f500.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-11 10:42:36', 'Bombero2', '2026-08-11 14:19:52', 29197.4, 7713.98, 'CIERRE 11 DE AGOSTO', 'Bombero2', 694.704, 183.527, 0, 1312.32, 1892.5, 500, 1197.82, 316.465),
(42, '45', 26503.6, 7002.28, '1786543937_9ec63bcaaf83f875f072.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-12 08:12:17', 'Bombero2', '2026-08-12 23:49:54', 26963.7, 7123.84, 'CIERRE 12 DE AGOSTO', 'Bombero2', 460.118, 121.556, 7517.01, 5675.74, 1173.8, 310.119, 8220.7, 2171.92),
(43, '44', 57841.5, 15281.8, '1786544028_04837f6621c6b198d905.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-12 08:13:48', 'Bombero2', '2026-08-12 23:48:54', 63057.2, 16659.8, 'CIERRE 12 DE AGOSTO', 'Bombero2', 5215.67, 1377.95, 0, 5675.74, 6286, 1660.77, 1060.7, 280.238),
(44, '45', 26963.7, 7123.84, '1786636382_a2edc7e4a2a26ff7b038.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-13 09:53:02', 'Bombero2', '2026-08-13 22:34:24', 30691.1, 8108.6, 'CIERRE 13 DE AGOSTO', 'Bombero2', 3727.36, 984.763, 3669.4, 4234.53, 8211.3, 2169.43, 8161.3, 2156.22),
(45, '44', 63057.2, 16659.8, '1786636454_a25f74a36c26bc691416.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-13 09:54:14', 'Bombero2', '2026-08-13 22:33:14', 63057.2, 16659.8, 'CIERRE 13 DE AGOSTO', 'Bombero2', 0.01, -0.0351, 7545.3, 4234.53, 1058.4, 279.63, 8598.7, 2271.78),
(46, '46', 29197.4, 7713.98, '1786657349_f5af1ee0186896daa4fa.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-13 15:42:29', 'Bombero2', '2026-08-13 20:23:59', 29704.5, 7847.96, 'CIERRE 13 DE AGOSTO', 'Bombero2', 507.132, 133.981, 0, 4024.19, 1135.5, 300, 628.461, 166.04),
(47, '45', 30691.1, 8108.6, '1786718673_71812a211f8a6f2039ac.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-14 08:44:33', 'Bombero2', '2026-08-14 22:47:17', 34895.5, 9219.42, 'CIERRE 14 DE AGOSTO', 'Bombero2', 4204.4, 1110.82, 7622.99, 4451.39, 8153.4, 2154.13, 7813.6, 2064.36),
(48, '44', 63057.2, 16659.8, '1786718742_5aaabbb5d379ee9888cd.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-14 08:45:42', 'Bombero2', '2026-08-14 22:46:29', 63304.1, 16725, 'CIERRE 14 DE AGOSTO', 'Bombero2', 246.863, 65.1836, 0, 4451.39, 8589.5, 2269.35, 8341.6, 2203.86),
(49, '45', 34895.5, 9219.42, '1786804144_c116e054eee389d8025c.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-15 08:29:04', 'Bombero2', '2026-08-15 23:17:02', 40084.2, 10590.3, 'CIERRE 15 DE AGOSTO', 'Bombero2', 5188.73, 1370.86, 0, 5188.78, 7801.7, 2061.22, 2618.9, 691.916),
(50, '44', 63304.1, 16725, '1786804258_f15924055b491d566111.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-15 08:30:58', 'Bombero2', '2026-08-15 23:17:44', 63304.1, 16725, 'CIERRE 15 DE AGOSTO', 'Bombero2', 0.01, -0.004, 0, 5188.78, 8333.3, 2201.66, 8334.4, 2201.96),
(51, '45', 40084.2, 10590.3, '1786977823_beb627fecb2e396b7d07.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-17 08:43:43', 'Bombero2', '2026-08-17 22:45:37', 41067.3, 10850, 'CIERRE 17 DE AGOSTO', 'Bombero2', 983.076, 259.707, 7218, 4387.97, 2614.6, 690.779, 8836.9, 2334.72),
(52, '44', 63304.1, 16725, '1786977852_e098a1515f94d08a1d69.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-17 08:44:12', 'Bombero2', '2026-08-18 08:32:26', 67444.8, 17819, 'CIERRE 17 DE AGOSTO', 'Bombero2', 4140.69, 1093.97, 0, 0, 8325.3, 2199.55, 4204.5, 1110.83),
(53, '46', 29704.5, 7847.96, '1787063637_2d49f23a708d87a5f0d7.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-18 08:33:58', 'Bombero2', '2026-08-19 08:14:38', 30343.9, 8016.87, 'CIERRE 19 DE AGOSTO', 'Bombero2', 639.359, 168.912, 1854.65, 0, 681.3, 180, 37.85, 10),
(54, '45', 41067.3, 10850, '1787063703_bba9422a808043de7b41.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-18 08:35:03', 'Bombero2', '2026-08-19 08:15:47', 42496.5, 11227.6, 'CIERRE 19 DE AGOSTO', 'Bombero2', 1429.23, 377.617, 0, 0, 4200.4, 1109.75, 7396, 1954.03),
(55, '44', 67444.8, 17819, '1787063817_f0e78ea955591c76672c.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-18 08:36:57', 'Bombero2', '2026-08-19 08:16:41', 68789.9, 18174.3, 'CIERRE 18 DE AGOSTO', 'Bombero2', 1345.09, 355.344, 5336, 0, 4200, 1109.64, 8214.1, 2170.17),
(56, '45', 42496.5, 11227.6, '1787149117_a51e42c5e9f427a89272.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-19 08:18:37', 'Bombero2', '2026-08-19 22:26:02', 48168.1, 12726.1, 'CIERRE 19 DE AGOSTO', 'Bombero2', 5671.65, 1498.46, 0, 6423.47, 7396, 1954.03, 1713.3, 452.655),
(57, '44', 68789.9, 18174.3, '1787149139_56bb6c4a128ffc3255a0.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-19 08:18:59', 'Bombero2', '2026-08-19 22:26:44', 69541.7, 18373, 'CIERRE 19 DE AGOSTO', 'Bombero2', 751.813, 198.676, 0, 6423.47, 8214.1, 2170.17, 7458.5, 1970.54),
(58, '45', 48168.1, 12726.1, '1787234419_a51047065d38aa5cee11.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-20 08:00:19', 'Bombero2', '2026-08-20 22:22:25', 48426.2, 12794.2, 'CIERRE 20 DE AGOSTO', 'Bombero2', 258.088, 68.1373, 7607.85, 3244.03, 1710.8, 451.995, 9054.5, 2392.21),
(59, '44', 69541.7, 18373, '1787234446_2e299d3b72092c19ef03.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-20 08:00:46', 'Bombero2', '2026-08-20 22:23:29', 72527.7, 19161.9, 'CIERRE DE 20 DE AGOSTO', 'Bombero2', 2985.97, 788.868, 3762.29, 3244.03, 7453.2, 1969.14, 8244.4, 2178.18),
(60, '45', 48426.2, 12794.2, '1787325135_cc641fadc802e5129671.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-21 09:12:15', 'Bombero2', '2026-08-21 22:54:47', 54888.5, 14501.6, 'CIERRE 21 DE AGOSTO', 'Bombero2', 6462.31, 1707.39, 0, 10448.6, 9047.8, 2390.44, 2599.4, 686.763),
(61, '44', 72527.7, 19161.9, '1787325165_dc45b4656bd211b849fd.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-21 09:12:45', 'Bombero2', '2026-08-21 22:55:31', 75863.3, 20043.1, 'CIERRE 21 DE AGOSTO', 'Bombero2', 3335.62, 881.248, 0, 10448.6, 8238, 2176.49, 4925.7, 1301.37),
(62, '46', 30343.9, 8016.87, '1787372537_6eafa2c2542b1048278e.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-21 22:22:17', 'Bombero2', '2026-08-21 22:59:14', 31154.8, 8231.12, 'CIERRE 21 DE AGOSTO', 'Bombero2', 810.9, 214.253, 0, 10448.6, 1892.5, 500, 1081.63, 285.767),
(63, '45', 75863.3, 20043.2, '1787412470_bf4fc9f62e187432c67d.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-22 09:27:50', 'Bombero2', '2026-08-22 22:49:08', 77230.7, 20404.4, 'CIERRE 22 DE AGOSTO', 'Bombero2', 1367.36, 361.202, 0, 5420.42, 2597.1, 686.156, 1331.3, 351.73),
(64, '44', 75863.3, 20043.2, '1787412558_3c32b317a2cb96b38b56.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-22 09:29:18', 'Bombero2', '2026-08-22 22:44:26', 79916.4, 21114, 'CIERRE 22 DE AGOSTO', 'Bombero2', 4053.07, 1070.77, 7570, 5420.42, 4921.6, 1300.29, 8421, 2224.83),
(65, '45', 56141, 14832.5, '1787580901_ae440e45ccf1543cf419.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-24 08:15:01', 'Bombero2', '2026-08-24 22:25:19', 56141, 14832.5, 'CIERRE 24 DE AGOSTO', 'Bombero2', 0.002, -0.0028, 5114.5, 6329.05, 1327.8, 350.806, 6438.4, 1701.03),
(66, '44', 79916.4, 21114, '1787581013_e59c91d5cac33c16e8f5.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-24 08:16:53', 'Bombero2', '2026-08-24 22:26:12', 86245.3, 22786.1, 'CIERRE 24 DE AGOSTO', 'Bombero2', 6328.88, 1672.07, 0, 6329.05, 8421, 2224.83, 2079.3, 549.353),
(67, '45', 56141, 14832.5, '1787669455_089d0c4fbd92f5242221.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-25 08:50:55', 'Bombero2', '2026-08-25 22:37:25', 59161.6, 15630.5, 'CIERRE 25 DE AGOSTO', 'Bombero2', 3020.61, 798.045, 5677.5, 3618.84, 6432.7, 1699.52, 9102, 2404.76),
(68, '44', 86245.3, 22786.1, '1787669483_002055e136233b622656.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-25 08:51:23', 'Bombero2', '2026-08-25 22:37:59', 86842.7, 22943.9, 'CIERRE 25 DE AGOSTO', 'Bombero2', 597.376, 157.804, 0, 3618.84, 2077.4, 548.851, 1476.1, 389.987),
(69, '46', 31154.8, 8231.12, '1787762866_3030fcfdc8cc55c5a633.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-26 10:47:46', 'Bombero2', '2026-08-26 22:32:50', 32685, 8635.41, 'CIERRE 26 DE AGOSTO', 'Bombero2', 1530.21, 404.285, 0, 3620.25, 1551.85, 410, 0, 0),
(70, '45', 59161.6, 15630.5, '1787780180_cd1da3129849f75f8d20.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-26 15:36:20', 'Bombero2', '2026-08-26 22:30:39', 61252.5, 16183, 'Cierre 26 de Agosto ', 'Bombero2', 2090.9, 552.459, 0, 3620.25, 9090.1, 2401.61, 7005.2, 1850.78),
(71, '44', 86842.7, 22943.9, '1787780254_4308bbb7b60d1bf68ee0.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-26 15:37:34', 'Bombero2', '2026-08-26 22:29:44', 86842.7, 22943.9, 'Cierre 26 de Agosto', 'Bombero2', 0.01, 0.0128, 0, 3620.25, 1471.2, 388.692, 1475.5, 389.828),
(72, '45', 61252.5, 16183, '1787845404_fc600000a8a54fc27a13.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-27 09:43:24', 'Bombero2', '2026-08-27 22:28:32', 61834.4, 16336.7, 'CIERRE 27 DE AGOSTO', 'Bombero2', 581.941, 153.708, 3785, 2744.31, 6997.3, 1848.69, 10174.5, 2688.11),
(73, '44', 86842.7, 22943.9, '1787845432_46933eec89c422ea76ee.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-27 09:43:52', 'Bombero2', '2026-08-27 22:30:15', 89005, 23515.2, 'CIERRE 27 DE AGOSTO', 'Bombero2', 2162.32, 571.297, 9841, 2744.31, 1473.5, 389.3, 9167.4, 2422.03),
(74, '45', 61834.4, 16336.7, '1787926734_4dd1ebcaed00cb0667c3.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-28 08:18:54', 'Bombero2', '2026-08-28 22:27:26', 62719.9, 16570.7, 'CIERRE 28 DE AGOSTO', 'Bombero2', 885.52, 233.953, 0, 8323.76, 10167.9, 2686.37, 9283.3, 2452.66),
(75, '44', 89005, 23515.2, '1787926774_b5138b6d8c861aae2d41.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-28 08:19:34', 'Bombero2', '2026-08-28 22:25:46', 96443.3, 25480.4, 'CIERRE 28 DE AGOSTO ', 'Bombero2', 7438.28, 1965.19, 0, 8323.76, 9158.4, 2419.66, 1725.2, 455.799),
(76, '45', 62719.9, 16570.7, '1788012919_de176a644c83e529602e.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-29 08:15:19', 'Bombero2', '2026-08-29 23:02:59', 64992.4, 17171, 'CIERRE 29 DE AGOSTO', 'Bombero2', 2272.48, 600.339, 0, 2447.06, 9277.3, 2451.07, 7014.5, 1853.24),
(77, '44', 96443.3, 25480.4, '1788013076_4d314fd951bebc8c4dbe.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-29 08:17:56', 'Bombero2', '2026-08-29 23:04:43', 96617.8, 25526.5, 'CIERRE 29 DE AGOSTO', 'Bombero2', 174.543, 46.1107, 3785, 2447.06, 1721.9, 454.927, 5357.1, 1415.35),
(78, '45', 64992.4, 17171, '1788185037_e24adaa48e23df1994f1.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-31 08:03:57', 'Bombero2', '2026-09-01 08:15:40', 67140.9, 17738.7, 'CIERRE 31 DE AGOSTO', 'Bombero2', 2148.55, 567.692, 4551, 0, 7011.2, 1852.36, 9416.5, 2487.85),
(79, '44', 96617.8, 25526.5, '1788185076_c43db68f9563e8915ec2.jpeg', 'normal', '', NULL, NULL, NULL, '2026-08-31 08:04:36', 'Bombero2', '2026-09-01 08:16:27', 96617.8, 25526.5, 'CIERRE 31 DE AGOSTO', 'Bombero2', 0.043, 0.0107, 4917, 0, 5352.7, 1414.19, 10258.6, 2710.33),
(80, '45', 67140.9, 17738.7, '1788272238_a453e5c5231f069cd84a.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-01 08:17:18', 'Bombero2', '2026-09-01 22:33:08', 73048.6, 19299.5, 'CIERRE 01 DE SEPTIEMBRE', 'Bombero2', 5907.68, 1560.79, 0, 6240.34, 9416.5, 2487.85, 3519.4, 929.828),
(81, '44', 96617.8, 25526.5, '1788272278_4a9f77c890cd7b4a2cb0.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-01 08:17:58', 'Bombero2', '2026-09-01 22:33:42', 96951.4, 25614.6, 'CIERRE 1 DE SEPTIEMBRE', 'Bombero2', 333.608, 88.1388, 0, 6240.34, 10258.6, 2710.33, 9925.7, 2622.38),
(82, '45', 73048.6, 19299.5, '1788370650_5c9cb9c4297d8f4d17f9.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-02 11:37:30', 'Bombero2', '2026-09-03 08:07:19', 73882.4, 19519.8, 'CIERRE 02 DE SEPTIEMBRE', 'Bombero2', 833.767, 220.28, 6434.5, 0, 3513.9, 928.375, 9136.6, 2413.9),
(83, '44', 96951.4, 25614.6, '1788370696_f22ca0447923f148b3af.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-02 11:38:16', 'Bombero2', '2026-09-03 08:51:41', 101863, 26912.3, 'CIERRE 02 DE SEPTIEMBRE', 'Bombero2', 4911.54, 1297.67, 0, 0, 9917.6, 2620.24, 5023.5, 1327.21),
(84, '45', 73882.4, 19519.8, '1788448309_5322789dc453677e7304.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-03 09:11:49', 'Bombero2', '2026-09-04 08:06:06', 74163.6, 19594.1, 'CIERRE 04 DE SEPTIEMBRE', 'Bombero2', 281.234, 74.2909, 0, 0, 9136.6, 2413.9, 8864.6, 2342.03),
(85, '44', 1862.94, 492.19, '1788448368_fd4409b30bfb9b83d607.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-03 09:12:48', 'Bombero2', '2026-09-04 14:11:10', 5664.53, 1496.57, 'CIERRE 03 DE SEPTIEMBRE', 'Bombero2', 3801.59, 1004.38, 7570, 900.53, 5023.5, 1327.21, 8774.2, 2318.15),
(86, '45', 74163.6, 19594.1, '1788531351_542c833aefa66da3960f.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-04 08:15:51', 'Bombero2', '2026-09-04 22:34:52', 78050.5, 20621, 'CIERRE 04 DE SEPTIEMBRE', 'Bombero2', 3886.89, 1026.9, 0, 3886.93, 8858.3, 2340.37, 4986.2, 1317.36),
(87, '44', 5664.53, 1496.57, '1788556090_06dc59cb249cf9ce2b18.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-04 15:08:10', 'Bombero2', '2026-09-04 22:33:17', 5664.54, 1496.58, 'CIERRE 04 DE AGOSTO', 'Bombero2', 0.01, 0.006, 0, 3886.93, 8774.2, 2318.15, 8770, 2317.04),
(88, '45', 78050.5, 20621, '1788613648_3d3f40a524ff20e111cf.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-05 07:07:28', 'Bombero2', '2026-09-05 22:53:30', 79367.3, 20968.9, 'CIERRE 05 DE SEPTIEMBRE', 'Bombero2', 1316.77, 347.895, 5677.5, 5701, 4981.1, 1316.01, 9331.2, 2465.31),
(89, '44', 5664.53, 1496.57, '1788613716_bb612525b74ca2f44c57.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-05 07:08:36', 'Bombero2', '2026-09-05 22:54:11', 10077.6, 2662.51, 'CIERRE 05 DE SEPTIEMBRE', 'Bombero2', 4413.09, 1165.94, 1892.5, 5701, 8761.2, 2314.72, 6266.9, 1655.72),
(90, '45', 79367.3, 20968.9, '1788744037_4846a6cecf4f5b81de48.jpg', 'normal', '', NULL, NULL, NULL, '2026-09-06 19:20:37', 'Bombero2', '2026-09-06 19:24:27', 79581.2, 21025.4, 'CIERRE 06 DE SEPTIEMBRE', 'Bombero2', 213.864, 56.5066, 0, 213.77, 9329.5, 2464.86, 9116.1, 2408.48),
(91, '45', 79581.2, 21025.4, '1788790199_f2e0a0dca8b967b5a64a.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-07 08:09:59', 'Bombero2', '2026-09-07 22:40:52', 82346.8, 21756.1, 'CIERRE 07 DE SEPTIEMBRE', 'Bombero2', 2765.59, 730.688, 0, 4788.93, 9116.1, 2408.48, 6343.4, 1675.93),
(92, '44', 10077.6, 2662.51, '1788790257_7473bd793f9be3c5a4fd.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-07 08:10:57', 'Bombero2', '2026-09-07 22:42:45', 12100.8, 3197.03, 'CIERRE 07 DE SEPTIEMBRE', 'Bombero2', 2023.15, 534.518, 5677.5, 4788.93, 6264.5, 1655.09, 9903, 2616.38),
(93, '45', 82346.8, 21756.1, '1788878213_1750b750a5dcc0b4fdcc.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-08 08:36:53', 'Bombero2', '2026-09-09 07:59:12', 87330.5, 23072.8, 'CIERRE 08 DE SEPTIEMBRE', 'Bombero2', 4983.75, 1316.7, 0, 0, 6343.4, 1675.93, 1346.2, 355.667),
(94, '44', 12100.8, 3197.03, '1788878263_b0d7ca8dc28892f8c9ff.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-08 08:37:43', 'Bombero2', '2026-09-09 07:59:53', 12472.1, 3295.13, 'CIERRE 08 DE SEPTIEMBRE', 'Bombero2', 371.26, 98.0981, 0, 0, 9895, 2614.27, 9533.7, 2518.81),
(95, '46', 32685, 8635.41, '1788898995_71bba25af120b44a343c.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-08 14:23:15', 'Bombero2', '2026-09-09 08:13:45', 32693.2, 8637.56, 'CIERRE 08 DE SEPTIEMBRE', 'Bombero2', 8.162, 2.1493, 0, 0, 1854.65, 490, 1845.65, 487.622),
(96, '45', 87330.5, 23072.8, '1788963614_b2f2bf100e79995814e1.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-09 08:20:14', 'Bombero2', '2026-09-10 07:24:07', 87330.5, 23072.8, 'CIERRE 09 DE SEPTIEMBRE', 'Bombero2', 0.01, -0.01, 0, 0, 1346.2, 355.667, 1347, 355.879),
(97, '44', 12472.1, 3295.13, '1788963680_5ec56862ff054ada6d49.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-09 08:21:20', 'Bombero2', '2026-09-10 07:26:15', 17999, 4755.35, 'CIERRE 09 DE SEPTIEMBRE', 'Bombero2', 5526.92, 1460.22, 0, 0, 9533.7, 2518.81, 4032.1, 1065.28),
(98, '45', 87330.5, 23072.8, '1789047512_43c1220544a1d70bd16c.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-10 07:38:32', 'Bombero2', '2026-09-11 06:42:35', 87330.5, 23072.8, 'CIERRE 10 DE SEPTIEMBRE', 'Bombero2', 0.02, -0.0074, 4542, 0, 1347.6, 356.037, 5910.6, 1561.59),
(99, '44', 17999, 4755.35, '1789047551_3180953526eb94169c6b.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-10 07:39:11', 'Bombero2', '2026-09-11 06:41:31', 22809, 6026.15, 'CIERRE 10 DE SEPTIEMBRE', 'Bombero2', 4809.99, 1270.8, 7191.5, 0, 4032.1, 1065.28, 6424.3, 1697.31),
(100, '45', 87330.5, 23072.8, '1789133076_66ff2378297b3098cf8d.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-11 07:24:36', 'Bombero2', '2026-09-12 07:43:23', 91762.2, 24243.6, 'CIERRE DE 11 DE SEPTIEMBRE', 'Bombero2', 4431.66, 1170.84, 0, 0, 5910.6, 1561.59, 1460.4, 385.839),
(101, '44', 22809, 6026.15, '1789133117_3edcb208e7e84d002c0b.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-11 07:25:17', 'Bombero2', '2026-09-12 07:46:15', 23794.2, 6286.46, 'CIERRE 11 DE SEPTIEMBRE', 'Bombero2', 985.245, 260.308, 3028, 0, 6424.3, 1697.31, 8461.8, 2235.61),
(102, '45', 91762.2, 24243.6, '1789220856_71914411e2245b09e656.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-12 07:47:36', 'Bombero2', '2026-09-13 18:48:01', 96995.8, 25626.4, 'CIERRE 12 DE SEPTIEMBRE', 'Bombero2', 5233.57, 1382.76, 7501, 0, 1460.4, 385.839, 3728.5, 985.073),
(103, '44', 23794.2, 6286.46, '1789220878_9c9e3c8356b14ad5610b.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-12 07:47:58', 'Bombero2', '2026-09-13 18:49:37', 23964.7, 6331.51, 'CIERRE 12 DE SEPTIEMBRE', 'Bombero2', 170.547, 45.0452, 89.1, 0, 8461.8, 2235.61, 8372.8, 2212.1),
(104, '45', 96995.8, 25626.4, '1789415194_408b28583286b23417d6.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-14 13:46:34', 'Bombero2', '2026-09-15 08:09:43', 99050, 26169.1, 'CIERRE 14 DE SEPTIEMBRE', 'Bombero2', 2054.23, 542.697, 0, 0, 3720.6, 982.985, 1654.8, 437.199),
(105, '44', 23964.7, 6331.51, '1789415296_c0d01eac39056df64435.jpeg', 'normal', '', NULL, NULL, NULL, '2026-09-14 13:48:16', 'Bombero2', '2026-09-15 08:10:36', 23964.8, 6331.51, 'CIERRE 14 DE SEPTIEMBRE', 'Bombero2', 0.08, 0.0039, 0, 0, 8360.9, 2208.96, 8360.1, 2208.75);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales`
--

CREATE TABLE `materiales` (
  `id` int(11) NOT NULL,
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
  `impuesto` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materiales`
--

INSERT INTO `materiales` (`id`, `id_empresa`, `codigo_consecutivo`, `nombre`, `unidad_medida`, `costo_unitario`, `fechaRegistro`, `fechaUpdate`, `usuarioCrea`, `usuarioEdita`, `codigo_vinculacion`, `impuesto`) VALUES
(1, 1, 'MAT-202500001', 'ACEITE DE MOTOR 5W', 'GALONES', 400.00, '2025-09-24 02:47:12', '2025-09-24 13:54:08', 1, NULL, NULL, NULL),
(2, 1, 'MAT-202500002', 'TUERCA 5/8 PARA CHAPAS DE MEDIR COM', 'UNIDAD', 0.00, '2025-09-24 21:29:12', '2025-09-24 14:29:42', 1, NULL, 'AR-0000001', NULL),
(3, 1, 'MAT-202500003', 'VALVULA REGULADORA LPG 25 LBRS.', 'UNIDAD', 10.00, '2025-09-24 21:32:21', '2025-09-24 14:33:37', 1, NULL, 'AR-0000002', NULL),
(4, 1, 'MAT-202500004', 'BRAZO DE LEVA 2-┬┐ (101D/200-HRP-BR)', 'UNIDAD', 354.50, '2025-09-24 21:37:08', '2025-09-24 14:37:37', 1, NULL, 'AR-0000050', NULL),
(5, 1, 'MAT-202500005', 'MR - 15 NEUMATICO TR 13', 'UNIDAD', 0.00, '2025-09-24 21:40:22', '2025-09-24 14:40:51', 1, NULL, 'AR-0000003', NULL),
(6, 1, 'MAT-202500006', 'FILTRO FUEL FILTER 24006', 'UNIDAD', 0.00, '2025-09-24 21:40:22', '2025-09-24 14:40:52', 1, NULL, 'AR-0000004', NULL),
(7, 1, 'MAT-202500007', 'AGUACATE 2000', 'UNIDAD', 122.00, '2025-09-24 21:53:17', '2025-09-24 14:53:47', 1, NULL, NULL, NULL),
(8, 1, 'MAT-202500008', 'MR - 16 NEUMATICO TR 13', 'UNIDAD', 0.00, '2025-09-24 23:58:05', '2025-09-24 16:58:35', 1, NULL, 'AR-0000005', NULL),
(9, 1, 'MAT-202500009', 'R16 TR75 A VALCULA LARGA', 'UNIDAD', 0.00, '2025-09-24 23:58:06', '2025-09-24 16:58:35', 1, NULL, 'AR-0000006', NULL),
(10, 1, 'MAT-202500010', 'BOMBA PIUSI E-140V(FLANGE 1\",PISTOLA 1\",4MTRS MANGUERA 1\",CONTADOR MANUAL EN GALONES, 37GPM', 'UNIDAD', 0.00, '2025-10-02 15:00:24', '2025-10-02 08:00:52', 1, NULL, 'AR-0000007', NULL),
(11, 1, 'MAT-202500011', 'PASTA PARA DETECTAR AGUA KOLOR KUT 3OZ', 'UNIDAD', 185.23, '2025-10-02 15:00:25', '2025-10-02 08:00:52', 1, NULL, 'AR-0000008', NULL),
(12, 1, 'MAT-202500012', 'CONTADOR MECANICO DE FLUJO K33 PIUSI', 'UNIDAD', 0.00, '2025-10-02 15:00:25', '2025-10-02 08:00:53', 1, NULL, 'AR-0000009', NULL),
(13, 1, 'MAT-202500013', 'BOMBA PIUSI EX 50 PRO KIT 12V', 'UNIDAD', 0.00, '2025-10-02 15:00:26', '2025-10-02 08:00:53', 1, NULL, 'AR-0000010', NULL),
(14, 1, 'MAT-202500014', 'PASTA PARA MEDIR COMBUSTIBLE', 'UNIDAD', 0.00, '2025-10-02 15:00:26', '2025-10-02 08:00:54', 1, NULL, 'AR-0000011', NULL),
(15, 1, 'MAT-202500015', 'FLUJOMETRO K-24 DE 1\" NPT', 'UNIDAD', 0.00, '2025-10-02 15:00:26', '2025-10-02 08:00:54', 1, NULL, 'AR-0000012', NULL),
(16, 1, 'MAT-202500016', 'MANGUERA DE 3 PULG. 20 PIES , TERMINAL H/M', 'UNIDAD', 0.00, '2025-10-02 15:00:27', '2025-10-02 08:00:54', 1, NULL, 'AR-0000013', NULL),
(17, 1, 'MAT-202500017', 'CONTENEDOR MOVIL  DE 3000 LTRS CON SURTIDOR', 'UNIDAD', 0.00, '2025-10-02 15:00:27', '2025-10-02 08:00:55', 1, NULL, 'AR-0000014', NULL),
(18, 1, 'MAT-202500018', 'PISTOLA DE DESPACHO 1\" OPW7H0100', 'UNIDAD', 0.00, '2025-10-02 15:00:28', '2025-10-02 08:00:55', 1, NULL, 'AR-0000015', NULL),
(19, 1, 'MAT-202500019', 'YOYO PARA ENCENDIDO DE PLANTA ELECTRICA', 'UNIDAD', 0.00, '2025-10-02 15:00:28', '2025-10-02 08:00:56', 1, NULL, 'AR-0000016', NULL),
(20, 1, 'MAT-202500020', 'SURTIDOR DE COMBUSTIBLE RT-WY224', 'UNIDAD', 0.00, '2025-10-02 15:00:28', '2025-10-02 08:00:56', 1, NULL, 'AR-0000017', NULL),
(21, 1, 'MAT-202500021', 'PISTOLA DESPACHO 3/4\" , CON MARCADOR ELECTRONICO.', 'UNIDAD', 0.00, '2025-10-02 15:00:29', '2025-10-02 08:00:57', 1, NULL, 'AR-0000018', NULL),
(22, 1, 'MAT-202500022', 'METRO CONTADOR  K44 PIUSI DE 1 PULGADA.', 'UNIDAD', 0.00, '2025-10-02 15:00:29', '2025-10-02 08:00:57', 1, NULL, 'AR-0000019', NULL),
(23, 1, 'MAT-202500023', 'TARJETA LT-B07 20181012', 'UNIDAD', 0.00, '2025-10-02 15:00:30', '2025-10-02 08:00:57', 1, NULL, 'AR-0000020', NULL),
(24, 1, 'MAT-202500024', 'TECLADO DE METAL  Z7 P1, P2, P3 Y P4', 'UNIDAD', 1242.72, '2025-10-02 15:00:30', '2025-10-02 08:00:58', 1, NULL, 'AR-0000021', NULL),
(25, 1, 'MAT-202500025', 'SOCKET API PARA SUCIO (C20770)', 'UNIDAD', 10322.13, '2025-10-02 15:00:30', '2025-10-02 08:00:58', 1, NULL, 'AR-0000022', NULL),
(26, 1, 'MAT-202500026', 'SWIVEL 3/4-? 241TPS', 'UNIDAD', 296.50, '2025-10-02 15:00:31', '2025-10-02 08:00:58', 1, NULL, 'AR-0000023', NULL),
(27, 1, 'MAT-202500027', 'POLO PARA CARGA POR BAJO (GROUND BOLT) FT-450', 'UNIDAD', 0.00, '2025-10-02 15:00:31', '2025-10-02 08:00:59', 1, NULL, 'AR-0000024', NULL),
(28, 1, 'MAT-202500028', 'MULTIMEDIA AUDIO (RADIO) BOSS 616UAB', 'UNIDAD', 0.00, '2025-10-02 15:00:32', '2025-10-02 08:00:59', 1, NULL, 'AR-0000025', NULL),
(29, 1, 'MAT-202500029', 'CINTA REFLECTIVA ROJA Y BLANCA SWRT DOT-C2 (2\"X200FT)', 'UNIDAD', 3287.30, '2025-10-02 15:00:32', '2025-10-02 08:01:00', 1, NULL, 'AR-0000026', NULL),
(30, 1, 'MAT-202500030', 'MANGUERA DE DESPACHO DE COMBUSTIBLE 3/4-? X 10 FT', 'UNIDAD', 1093.97, '2025-10-02 15:00:32', '2025-10-02 08:01:00', 1, NULL, 'AR-0000027', NULL),
(31, 1, 'MAT-202500031', 'PISTOLA DE DESPACHO DE 3/4-?', 'UNIDAD', 1700.59, '2025-10-02 15:00:33', '2025-10-02 08:01:00', 1, NULL, 'AR-0000028', NULL),
(32, 1, 'MAT-202500032', 'AFLOJATODO ABRO', 'UNIDAD', 0.00, '2025-10-02 15:00:33', '2025-10-02 08:01:01', 1, NULL, 'AR-0000029', NULL),
(33, 1, 'MAT-202500033', 'EMPAQUE DE DONA 4-? P/TUBERIA', 'UNIDAD', 1094.61, '2025-10-02 15:00:34', '2025-10-02 08:01:01', 1, NULL, 'AR-0000030', NULL),
(34, 1, 'MAT-202500034', 'CHICHAS DE ENGRASE', 'UNIDAD', 24.36, '2025-10-02 15:00:34', '2025-10-02 08:01:02', 1, NULL, 'AR-0000031', NULL),
(35, 1, 'MAT-202500035', 'ALUM-CLEANER', 'UNIDAD', 500.00, '2025-10-02 15:00:34', '2025-10-02 08:01:02', 1, NULL, 'AR-0000032', NULL),
(36, 1, 'MAT-202500036', 'DPP-80', 'UNIDAD', 500.00, '2025-10-02 15:00:35', '2025-10-02 08:01:02', 1, NULL, 'AR-0000033', NULL),
(37, 1, 'MAT-202500037', 'SOAP-80', 'UNIDAD', 400.00, '2025-10-02 15:00:35', '2025-10-02 08:01:03', 1, NULL, 'AR-0000034', NULL),
(38, 1, 'MAT-202500038', 'CLEANER-PLUS', 'UNIDAD', 400.00, '2025-10-02 15:00:36', '2025-10-02 08:01:03', 1, NULL, 'AR-0000035', NULL),
(39, 1, 'MAT-202500039', 'PARLANTE PARA VEHICULO 300W - CH6530B', 'UNIDAD', 0.00, '2025-10-02 15:00:36', '2025-10-02 08:01:04', 1, NULL, 'AR-0000036', NULL),
(40, 1, 'MAT-202500040', 'DONA VISOR 4-? (JDG-105 / OK4BCTT)', 'UNIDAD', 2324.69, '2025-10-02 15:00:36', '2025-10-02 08:01:04', 1, NULL, 'AR-0000037', NULL),
(41, 1, 'MAT-202500041', 'UNION DE HILO INTERNO 3-? A HEMBRA (300-D-AL)', 'UNIDAD', 1169.29, '2025-10-02 15:00:37', '2025-10-02 08:01:04', 1, NULL, 'AR-0000038', NULL),
(42, 1, 'MAT-202500042', 'UNION DE HILO INTERNO 3-? A MACHO (300-A-AL)', 'UNIDAD', 370.38, '2025-10-02 15:00:37', '2025-10-02 08:01:05', 1, NULL, 'AR-0000039', NULL),
(43, 1, 'MAT-202500043', 'UNION DE HILO INTERNO 2-? A HEMBRA (200-D-AL)', 'UNIDAD', 0.00, '2025-10-02 15:00:38', '2025-10-02 08:01:05', 1, NULL, 'AR-0000040', NULL),
(44, 1, 'MAT-202500044', 'ADAPTADOR MACHO MARA MANGUERA 3-? (300-E-AL)', 'UNIDAD', 0.00, '2025-10-02 15:00:38', '2025-10-02 08:01:06', 1, NULL, 'AR-0000041', NULL),
(45, 1, 'MAT-202500045', 'EMPAQUE PARA ACOPLADOR DE GRAVEDAD API (TT-08347M-JO)', 'UNIDAD', 0.00, '2025-10-02 15:00:38', '2025-10-02 08:01:06', 1, NULL, 'AR-0000042', NULL),
(46, 1, 'MAT-202500046', 'PARCHE 120 TL PARA LLANTA', 'UNIDAD', 180.00, '2025-10-02 15:00:39', '2025-10-02 08:01:06', 1, NULL, 'AR-0000043', NULL),
(47, 1, 'MAT-202500047', 'BRIDA PARA ACOPLAR TUBERIA DE 2-? ALUMINIO (30354)', 'UNIDAD', 1217.17, '2025-10-02 15:00:39', '2025-10-02 08:01:07', 1, NULL, 'AR-0000044', NULL),
(48, 1, 'MAT-202500048', 'BRIDA PARA ACOPLAR TUBERIA DE 3-? ALUMINIO (30344)', 'UNIDAD', 1708.52, '2025-10-02 15:00:39', '2025-10-02 08:01:07', 1, NULL, 'AR-0000045', NULL),
(49, 1, 'MAT-202500049', 'BRIDA PARA ACOPLAR TUBERIA DE 4-? ALUMINIO (30364)', 'UNIDAD', 1385.86, '2025-10-02 15:00:40', '2025-10-02 08:01:08', 1, NULL, 'AR-0000046', NULL),
(50, 1, 'MAT-202500050', 'ADAPTADOR DE VENTILACION DE VAPOR (12619)', 'UNIDAD', 0.00, '2025-10-02 15:00:40', '2025-10-02 08:01:08', 1, NULL, 'AR-0000047', NULL),
(51, 1, 'MAT-202500051', 'TAPON DE VAPOR DE 4-? (1711T)', 'UNIDAD', 0.00, '2025-10-02 15:00:41', '2025-10-02 08:01:08', 1, NULL, 'AR-0000048', NULL),
(52, 1, 'MAT-202500052', 'ETIQUETA DE PRODUCTO DE CISTERNA (TK699)', 'UNIDAD', 2636.21, '2025-10-02 15:00:41', '2025-10-02 08:01:09', 1, NULL, 'AR-0000049', NULL),
(53, 1, 'MAT-202500053', 'EMPAQUE PARA TAPA/MANGUERA 3-? (300-G-BU)', 'UNIDAD', 24.17, '2025-10-02 15:01:21', '2025-10-02 08:01:49', 1, NULL, 'AR-0000051', NULL),
(54, 1, 'MAT-202500054', 'EMPAQUE PARA TAPA/MANGUERA 4-? (400-G-BU)', 'UNIDAD', 47.85, '2025-10-02 15:01:22', '2025-10-02 08:01:49', 1, NULL, 'AR-0000052', NULL),
(55, 1, 'MAT-202500055', 'PASTA PARA DETECTAR AGUA (SARGEL)', 'UNIDAD', 291.11, '2025-10-02 15:01:22', '2025-10-02 08:01:50', 1, NULL, 'AR-0000053', NULL),
(56, 1, 'MAT-202500056', 'CARTEL ABATIBLE DE ALUMINIO (3734)', 'UNIDAD', 0.00, '2025-10-02 15:01:23', '2025-10-02 08:01:50', 1, NULL, 'AR-0000054', NULL),
(57, 1, 'MAT-202500057', 'ADAPTADOR DE RECUPERACI+?N DE VAPOR 4X4 (611T-AL40-NPT)', 'UNIDAD', 4101.61, '2025-10-02 15:01:23', '2025-10-02 08:01:51', 1, NULL, 'AR-0000055', NULL),
(58, 1, 'MAT-202500058', 'SELLO ROJO-AZUL  (MARCHAMOS) ', 'UNIDAD', 22.22, '2025-10-02 15:01:23', '2025-10-02 08:01:51', 1, NULL, 'AR-0000056', NULL),
(59, 1, 'MAT-202500059', 'PISTOLA DE DISPARO DE ALTA PRESION ', 'UNIDAD', 1302.45, '2025-10-02 15:01:24', '2025-10-02 08:01:51', 1, NULL, 'AR-0000057', NULL),
(60, 1, 'MAT-202500060', 'PISTOLA DE LAVADO A PRESION ', 'UNIDAD', 0.00, '2025-10-02 15:01:24', '2025-10-02 08:01:52', 1, NULL, 'AR-0000058', NULL),
(61, 1, 'MAT-202500061', 'SILICON URETANO', 'UNIDAD', 461.28, '2025-10-02 15:01:25', '2025-10-02 08:01:52', 1, NULL, 'AR-0000059', NULL),
(62, 1, 'MAT-202500062', 'VELOCIMETRO DIGITAL', 'UNIDAD', 0.00, '2025-10-02 15:01:25', '2025-10-02 08:01:53', 1, NULL, 'AR-0000060', NULL),
(63, 1, 'MAT-202500063', 'ESPEJO RETROVISOR PARA VOLVO (PAR)', 'UNIDAD', 0.00, '2025-10-02 15:01:25', '2025-10-02 08:01:53', 1, NULL, 'AR-0000061', NULL),
(64, 1, 'MAT-202500064', 'SWIVEL 1-? 241TPS', 'UNIDAD', 0.00, '2025-10-02 15:01:26', '2025-10-02 08:01:53', 1, NULL, 'AR-0000062', NULL),
(65, 1, 'MAT-202500065', 'SOLDADURA E6011 1/8  (CAJA) ELECTRODO', 'UNIDAD', 0.00, '2025-10-02 15:01:26', '2025-10-02 08:01:54', 1, NULL, 'AR-0000063', NULL),
(66, 1, 'MAT-202500066', 'SOLDADURA E6011 3/32 (CAJA) ELECTRODO X LB', 'UNIDAD', 0.00, '2025-10-02 15:01:27', '2025-10-02 08:01:54', 1, NULL, 'AR-0000064', NULL),
(67, 1, 'MAT-202500067', 'SOLDADURA E7018 1/8 (CAJA) ELECTRODO', 'UNIDAD', 64.79, '2025-10-02 15:01:27', '2025-10-02 08:01:55', 1, NULL, 'AR-0000065', NULL),
(68, 1, 'MAT-202500068', 'SOLDADURA E7018 3/32 (CAJA) ELECTRODO', 'UNIDAD', 802.01, '2025-10-02 15:01:27', '2025-10-02 08:01:55', 1, NULL, 'AR-0000066', NULL),
(69, 1, 'MAT-202500069', 'DISPENSADOR DE COMBUSTIBLE RT-M111', 'UNIDAD', 50001.14, '2025-10-02 15:01:28', '2025-10-02 08:01:55', 1, NULL, 'AR-0000067', NULL),
(70, 1, 'MAT-202500070', 'TERMINAL AMARILLO 1/4 DE OJO', 'UNIDAD', 16.83, '2025-10-02 15:01:28', '2025-10-02 08:01:56', 1, NULL, 'AR-0000068', NULL),
(71, 1, 'MAT-202500071', 'TUBO CARRUGADO 1/4-?', 'UNIDAD', 17.53, '2025-10-02 15:01:29', '2025-10-02 08:01:56', 1, NULL, 'AR-0000069', NULL),
(72, 1, 'MAT-202500072', 'METROCONTADOR FILL-RITE 900CD 1-? DIGITAL', 'UNIDAD', 0.00, '2025-10-02 15:01:29', '2025-10-02 08:01:57', 1, NULL, 'AR-0000070', NULL),
(73, 1, 'MAT-202500073', 'LIMPIA BOQUILLA DE SOLDADOR', 'UNIDAD', 0.00, '2025-10-02 15:01:30', '2025-10-02 08:01:57', 1, NULL, 'AR-0000071', NULL),
(74, 1, 'MAT-202500074', 'CADENA GALVANIZADA DE 1/4-? ', 'UNIDAD', 145.05, '2025-10-02 15:01:30', '2025-10-02 08:01:58', 1, NULL, 'AR-0000072', NULL),
(75, 1, 'MAT-202500075', 'CEPILLO DE COPA DE ALAMBRE', 'UNIDAD', 255.00, '2025-10-02 15:01:31', '2025-10-02 08:01:58', 1, NULL, 'AR-0000073', NULL),
(76, 1, 'MAT-202500076', 'CRISTAL', 'UNIDAD', 0.00, '2025-10-02 15:01:31', '2025-10-02 08:01:59', 1, NULL, 'AR-0000074', NULL),
(77, 1, 'MAT-202500077', 'TAPON API DE 6-? ', 'UNIDAD', 0.00, '2025-10-02 15:01:31', '2025-10-02 08:01:59', 1, NULL, 'AR-0000075', NULL),
(78, 1, 'MAT-202500078', 'TUBO CARRUGADO -?-? (X PIE)', 'UNIDAD', 61.83, '2025-10-02 15:01:32', '2025-10-02 08:01:59', 1, NULL, 'AR-0000076', NULL),
(79, 1, 'MAT-202500079', 'TUBO CARRUGADO 3/8-? (X PIE)', 'UNIDAD', 36.80, '2025-10-02 15:01:32', '2025-10-02 08:02:00', 1, NULL, 'AR-0000077', NULL),
(80, 1, 'MAT-202500080', 'PASTA PARA MEDIR COMBUSTIBLE 2.25 ONZ KK-GGP', 'UNIDAD', 228.18, '2025-10-02 15:01:33', '2025-10-02 08:02:00', 1, NULL, 'AR-0000078', NULL),
(81, 1, 'MAT-202500081', 'BOMBA EX-50 PIUSI 12V 15GPM', 'UNIDAD', 0.00, '2025-10-02 15:01:33', '2025-10-02 08:02:01', 1, NULL, 'AR-0000079', NULL),
(82, 1, 'MAT-202500082', 'METROCONTADOR K-150 PIUSI 1-? P00556A0A', 'UNIDAD', 15520.17, '2025-10-02 15:01:33', '2025-10-02 08:02:01', 1, NULL, 'AR-0000080', NULL),
(83, 1, 'MAT-202500083', 'POLVERA PARA PALANCA DE CAMBIO 562.7461', 'UNIDAD', 0.00, '2025-10-02 15:01:34', '2025-10-02 08:02:01', 1, NULL, 'AR-0000081', NULL),
(84, 1, 'MAT-202500084', 'CARGADOR USB', 'UNIDAD', 0.00, '2025-10-02 15:01:34', '2025-10-02 08:02:02', 1, NULL, 'AR-0000082', NULL),
(85, 1, 'MAT-202500085', 'LIMPIADOR DE MANOS (DESENGRASANTE)', 'UNIDAD', 0.00, '2025-10-02 15:01:35', '2025-10-02 08:02:02', 1, NULL, 'AR-0000083', NULL),
(86, 1, 'MAT-202500086', 'HIGH TACT', 'UNIDAD', 0.00, '2025-10-02 15:01:35', '2025-10-02 08:02:03', 1, NULL, 'AR-0000084', NULL),
(87, 1, 'MAT-202500087', 'EMPAQUE TEFLONADO P/VALVULA DE FONDO 6\" G15266TFNA', 'UNIDAD', 111.78, '2025-10-02 15:01:36', '2025-10-02 08:02:03', 1, NULL, 'AR-0000085', NULL),
(88, 1, 'MAT-202500088', 'BRIDA PLASTICA (12\", 8\", 4\")', 'UNIDAD', 2.53, '2025-10-02 15:01:36', '2025-10-02 08:02:04', 1, NULL, 'AR-0000086', NULL),
(89, 1, 'MAT-202500089', 'UNION DE HILO INTERNO 2-? A MACHO (A-200)', 'UNIDAD', 0.00, '2025-10-02 15:01:36', '2025-10-02 08:02:04', 1, NULL, 'AR-0000087', NULL),
(90, 1, 'MAT-202500090', 'ATOMIZADOR', 'UNIDAD', 0.00, '2025-10-02 15:01:37', '2025-10-02 08:02:05', 1, NULL, 'AR-0000088', NULL),
(91, 1, 'MAT-202500091', 'MAIN BOARD LT-H 2020063', 'UNIDAD', 2692.55, '2025-10-02 15:01:37', '2025-10-02 08:02:05', 1, NULL, 'AR-0000089', NULL),
(92, 1, 'MAT-202500092', 'BRIDA TURBO 4-?', 'UNIDAD', 0.00, '2025-10-02 15:01:38', '2025-10-02 08:02:05', 1, NULL, 'AR-0000090', NULL),
(93, 1, 'MAT-202500093', 'BRIDA TURBO 2--?-?', 'UNIDAD', 0.00, '2025-10-02 15:01:38', '2025-10-02 08:02:06', 1, NULL, 'AR-0000091', NULL),
(94, 1, 'MAT-202500094', 'BOTON, VALVULA DE ENCLAVAMIENTO', 'UNIDAD', 438.76, '2025-10-02 15:01:39', '2025-10-02 08:02:06', 1, NULL, 'AR-0000092', NULL),
(95, 1, 'MAT-202500095', 'BUNA URETHANO PARA VALVULA DE FONDO 3-?', 'UNIDAD', 0.00, '2025-10-02 15:01:39', '2025-10-02 08:02:07', 1, NULL, 'AR-0000093', NULL),
(96, 1, 'MAT-202500096', 'EMPAQUE DE VALVULA DE FONDO DE 3-?', 'UNIDAD', 0.00, '2025-10-02 15:01:39', '2025-10-02 08:02:07', 1, NULL, 'AR-0000094', NULL),
(97, 1, 'MAT-202500097', 'CEPILLO PARA TRICO # 20', 'UNIDAD', 220.00, '2025-10-02 15:01:40', '2025-10-02 08:02:08', 1, NULL, 'AR-0000095', NULL),
(98, 1, 'MAT-202500098', 'CEPILLO PARA TRICO # 24', 'UNIDAD', 250.00, '2025-10-02 15:01:40', '2025-10-02 08:02:08', 1, NULL, 'AR-0000096', NULL),
(99, 1, 'MAT-202500099', 'TUBO CARRUGADO 5/8 (X MTS)', 'UNIDAD', 0.00, '2025-10-02 15:01:41', '2025-10-02 08:02:08', 1, NULL, 'AR-0000097', NULL),
(100, 1, 'MAT-202500100', 'BRIDA FAJA DE 3-?', 'UNIDAD', 749.04, '2025-10-02 15:01:41', '2025-10-02 08:02:09', 1, NULL, 'AR-0000098', NULL),
(101, 1, 'MAT-202500101', 'BRIDA TIPO -?U-? DE 3-?', 'UNIDAD', 0.00, '2025-10-02 15:01:41', '2025-10-02 08:02:09', 1, NULL, 'AR-0000099', NULL),
(102, 1, 'MAT-202500102', 'TUBO FLEXIBLE DE 3-?', 'UNIDAD', 235.54, '2025-10-02 15:01:42', '2025-10-02 08:02:10', 1, NULL, 'AR-0000100', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales_trabajo`
--

CREATE TABLE `materiales_trabajo` (
  `id` int(11) NOT NULL,
  `id_registro_trabajo` int(11) NOT NULL,
  `id_material` int(11) NOT NULL COMMENT 'ID del material de la tabla materiales',
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `costo_unitario` decimal(8,2) NOT NULL DEFAULT 0.00,
  `costo_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `icono` varchar(100) DEFAULT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `id_superior` varchar(100) DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  `fecha_registra` datetime DEFAULT current_timestamp(),
  `fecha_actualiza` datetime DEFAULT current_timestamp(),
  `ruta` varchar(100) DEFAULT NULL,
  `estado` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `icono`, `menu`, `id_superior`, `nivel`, `usuario_crea`, `usuario_edita`, `fecha_registra`, `fecha_actualiza`, `ruta`, `estado`) VALUES
(1, 'fas fa-tachometer-alt', 'Dashboard', NULL, 1, '1', NULL, '2025-09-02 14:48:39', '2025-09-02 07:48:41', 'dashboard', 1),
(2, 'fas fa-car', 'Gestión de Vehículos', NULL, 1, '1', NULL, '2025-09-02 14:48:39', '2025-09-02 07:48:41', NULL, 1),
(3, 'fas fa-users', 'Gestión de Conductores', NULL, 1, '1', NULL, '2025-09-02 14:48:39', '2025-09-02 07:48:41', NULL, 1),
(4, 'fas fa-gas-pump', 'Control de Combustible', NULL, 1, '1', NULL, '2025-09-02 14:48:39', '2025-09-02 07:48:41', NULL, 1),
(5, 'fas fa-tools', 'Mantenimiento', NULL, 1, '1', NULL, '2025-09-02 14:48:39', '2025-09-02 07:48:41', NULL, 1),
(6, 'fas fa-chart-bar', 'Reportes y Análisis', NULL, 1, '1', NULL, '2025-09-02 14:48:39', '2025-09-02 07:48:41', NULL, 1),
(7, 'fas fa-cog', 'Configuración', NULL, 1, '1', NULL, '2025-09-02 14:48:39', '2025-09-02 07:48:41', NULL, 1),
(8, 'fas fa-list', 'Lista de Vehículos', '2', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'vehiculos', 1),
(9, 'fas fa-plus', 'Registrar Vehículos', '2', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'vehiculos/create', 1),
(10, 'fas fa-list', 'Lista de Conductores', '3', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'conductores', 1),
(11, 'fas fa-user-plus', 'Registrar Conductor', '3', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'conductores/create', 1),
(12, 'fas fa-clipboard-list', 'Registros de Combustible', '4', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'registro-combustible', 1),
(13, 'fas fa-plus-circle', 'Nuevo Registro', '4', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'registro-combustible/create', 1),
(14, 'fas fa-wrench', 'Solicitudes de Mantenimiento', '5', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'solicitudes', 1),
(15, 'fas fa-file-pdf', 'Reportes de Vehiculos', '6', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'reportes/vehiculos', 1),
(16, 'fas fa-chart-pie', 'Analisis de Combustible', '6', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'reportes/combustible', 1),
(17, 'fas fa-users-cog', 'Gestión de Usuarios', '7', 2, '1', '1', '2025-09-02 14:48:41', '2025-09-03 17:22:03', 'usuarios', 1),
(18, 'fas fa-shield-alt', 'Roles y Permisos', '7', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'admin/roles', 1),
(19, 'fas fa-sitemap', 'Gestión de Menús', '7', 2, '1', NULL, '2025-09-02 14:48:41', '2025-09-02 07:48:42', 'menu', 1),
(20, 'fas fa-user-shield', 'Gestion de Accesos', '7', 2, '1', NULL, '2025-09-03 02:50:15', '2025-09-03 02:50:15', 'acceso', 1),
(21, 'fas fa-user-cog', 'Gestion de Roles', '7', 2, '1', NULL, '2025-09-03 02:51:47', '2025-09-03 02:51:47', 'rol', 1),
(22, 'fas fa-tachometer-alt', 'Gestion de Motivo Combustibles', '7', 2, '1', NULL, '2025-09-18 02:17:21', '2025-09-18 02:17:21', 'tipo-motivo-combustible', 1),
(23, 'fas fa-map', 'Gestion de Direcciones', NULL, 1, '1', '1', '2025-09-18 03:51:22', '2025-09-18 04:32:17', 'direcciones', 1),
(24, 'fas fa-compass', 'Registros de Direcciones', '23', 2, '1', NULL, '2025-09-18 04:33:23', '2025-09-18 04:33:23', 'direcciones', 1),
(25, 'fas fa-tools', 'Mantenimiento de Vehiculos', NULL, 1, '1', '1', '2025-09-20 00:37:16', '2026-08-18 13:11:14', 'ordenes-trabajo', 1),
(26, 'fas fa-home', 'Panel de Control', '25', 2, '1', NULL, '2025-09-20 00:38:17', '2025-09-20 00:38:17', 'ordenes-trabajo', 1),
(27, 'fas fa-wrench', 'Materiales', '7', 2, '1', NULL, '2025-09-24 02:28:54', '2025-09-24 02:28:54', 'materiales', 1),
(28, 'fas fa-route', 'Tipo de Unidad', '7', 2, '1', NULL, '2025-09-24 23:11:35', '2025-09-24 23:11:35', 'tipo-unidad', 1),
(29, 'fas fa-home', 'Catalogos', '7', 2, '1', NULL, '2025-09-28 22:33:05', '2025-09-28 22:33:05', 'catalogo', 1),
(30, 'fas fa-clipboard-list', 'Gestion de Movimientos', NULL, 1, '1', NULL, '2025-10-02 14:21:22', '2025-10-02 14:21:22', 'movimientos', 1),
(31, 'fas fa-home', 'Movimientos', '30', 2, '1', NULL, '2025-10-02 14:22:08', '2025-10-02 14:22:08', 'movimientos', 1),
(32, 'fas fa-th', 'Mantenimiento de Tablas', '7', 2, '1', NULL, '2025-10-03 01:55:49', '2025-10-03 01:55:49', 'mantenimiento-tablas', 1),
(33, 'fas fa-th', 'Kanba', '25', 2, '1', NULL, '2025-10-03 02:31:58', '2025-10-03 02:31:58', 'ordenes-trabajo/kanban', 1),
(34, 'fas fa-tachometer-alt', 'Venta de combustible', '4', 2, '1', NULL, '2025-10-28 20:41:20', '2025-10-28 20:41:20', 'registro-combustible/create-venta', 1),
(35, 'fas fa-home', 'Panel de Venta Combustible', '4', 2, '1', NULL, '2025-10-28 21:13:49', '2025-10-28 21:13:49', 'registro-combustible/ventas', 1),
(36, 'fas fa-tachometer-alt', 'Lectura de Bomba', '4', 2, '1', '1', '2026-06-02 13:07:44', '2026-06-03 05:30:24', 'lectura-bomba', 1),
(37, 'fas fa-tachometer-alt', 'Reporte de Lectura', '4', 2, '1', '1', '2026-06-02 13:08:09', '2026-06-03 05:30:51', 'lectura-bomba/dashboard', 1),
(38, 'fas fa-gas-pump', 'Aprobacion de Bloqueos', '4', 2, '1', NULL, '2026-06-18 02:31:00', '2026-06-18 02:31:00', 'registro-combustible/supervisor', 1),
(39, 'fas fa-calculator', 'Cotizador', NULL, 1, '1', NULL, '2026-06-23 02:57:26', '2026-06-23 02:57:26', 'cotizador/historial', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivos_asignacion_combustible`
--

CREATE TABLE `motivos_asignacion_combustible` (
  `id` int(11) NOT NULL,
  `motivo` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `motivos_asignacion_combustible`
--

INSERT INTO `motivos_asignacion_combustible` (`id`, `motivo`, `fecha_registro`) VALUES
(1, 'Viaje de Negocios', '2025-08-21 13:33:36'),
(2, 'Mantenimiento Preventivo', '2025-08-21 13:33:36'),
(3, 'Ruta Asignada', '2025-08-21 13:33:36'),
(4, 'Recorrido de Pruebas', '2025-08-21 13:33:36'),
(5, 'Carga de Emergencia', '2025-08-21 13:33:36'),
(6, 'Traslado de Veh├¡culo', '2025-08-21 13:33:36'),
(7, 'Reabastecimiento de Reserva', '2025-08-21 13:33:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id` int(11) NOT NULL,
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
  `codigo_destino` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `tipo_movimiento`, `codigo`, `referencia`, `monto`, `fecha_registro`, `fecha_actualizacion`, `fecha_movimiento`, `estado`, `usuario_crea`, `usuario_edita`, `usuario_aprueba`, `id_empresa`, `codigo_origen`, `codigo_destino`) VALUES
(1, 'SALIDA', 'SAL-2025000001', 'SOL-00002', 0, '2025-10-02 17:41:13', '2025-10-22 21:35:44', '2025-10-02 00:00:00', 4, 'ADMIN', NULL, NULL, 1, '', ''),
(2, 'SALIDA', 'SAL-2025000002', 'SOL-00002', 913.79, '2025-10-07 03:52:47', NULL, '2025-10-07 00:00:00', 1, 'ADMIN', NULL, NULL, 1, '', ''),
(3, 'SALIDA', 'SAL-2025000003', 'SOL-00002', 111.78, '2025-10-08 01:40:47', '2025-10-08 03:31:01', '2025-10-08 00:00:00', 1, 'ADMIN', NULL, NULL, 1, '', ''),
(4, 'SALIDA', 'SAL-2025000004', 'SOL-00002', 1137.8, '2025-10-13 21:52:23', NULL, '2025-10-13 00:00:00', 1, 'ADMIN', NULL, 'ADMINISTRADOR', 1, '', ''),
(5, 'SALIDA', 'SAL-2025000005', 'SOL-00002', 1137.8, '2025-10-13 21:52:31', NULL, '2025-10-13 00:00:00', 1, 'ADMIN', NULL, NULL, 1, '', ''),
(6, 'SALIDA', 'SAL-2025000006', 'SOL-00002', 0, '2025-10-22 21:32:34', NULL, '2025-10-22 00:00:00', 0, 'ADMIN', NULL, NULL, 1, '', ''),
(7, 'SALIDA', 'SAL-2025000007', 'SOL-00003', 400, '2025-11-04 20:29:20', NULL, '2025-11-04 00:00:00', 0, 'ADMIN', NULL, NULL, 1, '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_combustible`
--

CREATE TABLE `registro_combustible` (
  `id` int(11) NOT NULL,
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
  `referencia1` varchar(100) DEFAULT NULL,
  `referencia2` varchar(100) DEFAULT NULL,
  `enviado` int(11) DEFAULT NULL,
  `numero_ingreso_sag` int(11) DEFAULT NULL,
  `id_lectura` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_combustible`
--

INSERT INTO `registro_combustible` (`id`, `id_vehiculo`, `fecha_registro`, `kilometraje_anterior`, `kilometraje_actual`, `cantidad_litros`, `id_motivo`, `observaciones`, `usuario_crea`, `usuario_edita`, `fecha_actualiza`, `id_tipo_motivo`, `id_direccion`, `medicion`, `nombreCliente`, `dni`, `tipo`, `combustible_tanque`, `monto_nio`, `monto_usd`, `estado`, `rendimiento`, `rendimiento_promedio`, `referencia1`, `referencia2`, `enviado`, `numero_ingreso_sag`, `id_lectura`) VALUES
(224, 82, '2026-08-03', 0, 0, 38.16, NULL, 'Areli Espinoza Rizo', 'Bombero2', NULL, '2026-08-03 16:42:51', NULL, NULL, 38.16, '', '', 'CONSUMO', 38.16, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70836, 23),
(225, 62, '2026-08-03', 49988, 50128, 56.25, NULL, 'C64 Corrales Verde', 'Bombero2', NULL, '2026-08-03 17:07:33', NULL, NULL, 56.25, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 56.25, NULL, NULL, 'BLOQUEADO', 156.36, 9, '6', NULL, 1, 70837, 23),
(226, 54, '2026-08-03', 38880, 38964, 68.70, NULL, 'C45 Locales', 'Bombero2', NULL, '2026-08-03 17:12:21', NULL, NULL, 68.7, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 68.69, NULL, NULL, 'BLOQUEADO', 115.05, 8, '6', NULL, 1, 70838, 23),
(227, 53, '2026-08-03', 3169, 3339, 65.48, NULL, 'C44 Rescate Leon', 'Bombero2', NULL, '2026-08-03 17:16:57', NULL, NULL, 65.48, '', '', 'CONSUMO', 65.48, NULL, NULL, 'BLOQUEADO', 49.11, 9, '6', NULL, 1, 70839, 23),
(228, 80, '2026-08-03', 0, 0, 140.85, NULL, 'Denis Blas Ampie', 'Bombero2', NULL, '2026-08-03 17:18:30', NULL, NULL, 140.85, '', '', 'CONSUMO', 140.85, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70840, 23),
(229, 51, '2026-08-03', 938383, 93838, 343.40, NULL, 'C37 Puerto Corinto', 'Bombero2', NULL, '2026-08-03 17:19:59', NULL, NULL, 343.4, 'Julio José Mendoza Ruíz ', '001-080300-1009B', 'CONSUMO', 343.4, NULL, NULL, 'BLOQUEADO', 77.09, 10, '6', NULL, 1, 70841, 23),
(230, 75, '2026-08-03', 0, 0, 1000.00, NULL, 'IRESA', 'Bombero2', NULL, '2026-08-03 17:52:14', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70842, 23),
(231, 87, '2026-08-03', 0, 0, 28.34, NULL, 'Toyota F M362289/Cuota Oscar Briceño', 'Bombero2', NULL, '2026-08-03 17:55:30', NULL, NULL, 28.34, '', '', 'CONSUMO', 28.34, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 70843, 23),
(232, 84, '2026-08-03', 0, 0, 25.71, NULL, 'Toyota L M435527/Cuota Oscar Briceño', 'Bombero2', NULL, '2026-08-03 18:49:04', NULL, NULL, 25.71, '', '', 'CONSUMO', 25.71, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 70844, 23),
(233, 64, '2026-08-03', 8350, 8605, 117.10, NULL, 'C67 Santo Tomas', 'Bombero2', NULL, '2026-08-03 18:51:09', NULL, NULL, 117.1, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 117.09, NULL, NULL, 'APROBADO', 8.23, 9, '6', NULL, 1, 70845, 23),
(234, 88, '2026-08-03', 12139, 1347, 88.47, NULL, 'C69 Managua-Nindiri', 'Bombero2', NULL, '2026-08-03 18:56:45', NULL, NULL, 88.47, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 88.47, NULL, NULL, 'APROBADO', 57.63, 9, '6', NULL, 1, 70846, 23),
(235, 12, '2026-08-03', 330481, 330589, 64.25, NULL, 'LPG2-097 Managua', 'Bombero2', NULL, '2026-08-03 18:58:51', NULL, NULL, 64.25, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 64.25, NULL, NULL, 'BLOQUEADO', 101.4, 15, '6', NULL, 1, 70847, 23),
(236, 45, '2026-08-03', 82990, 83288, 153.51, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-03 19:45:15', NULL, NULL, 153.51, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 153.51, NULL, NULL, 'BLOQUEADO', 74.09, 10, '6', NULL, 1, 70848, 23),
(237, 6, '2026-08-03', 45957, 46241, 69.60, NULL, 'C38 Mina la India', 'Bombero2', NULL, '2026-08-03 20:06:42', NULL, NULL, 69.6, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 69.59, NULL, NULL, 'BLOQUEADO', 144.6, 15, '6', NULL, 1, 70849, 23),
(238, 50, '2026-08-03', 1156430, 1156453, 24.96, NULL, 'C36 Batahola-Los Cocos-Tropigas', 'Bombero2', NULL, '2026-08-03 20:36:29', NULL, NULL, 24.96, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 24.95, NULL, NULL, 'BLOQUEADO', 404.5, 8, '6', NULL, 1, 70850, 23),
(239, 4, '2026-08-03', 39751, 40225, 141.53, NULL, 'C12 Pavinic', 'Bombero2', NULL, '2026-08-03 21:15:36', NULL, NULL, 141.53, 'Jorge Gregorio Ney Almendárez ', '001-011179-0010X', 'CONSUMO', 141.53, NULL, NULL, 'BLOQUEADO', 12.68, 10, '6', NULL, 1, 70851, 23),
(240, 43, '2026-08-03', 70112, 70262, 80.76, NULL, 'C18 Relleno', 'Bombero2', NULL, '2026-08-03 22:19:59', NULL, NULL, 80.76, '', '', 'CONSUMO', 80.76, NULL, NULL, 'BLOQUEADO', 193.97, 10, '6', NULL, 1, 70852, 23),
(241, 40, '2026-08-04', 67250, 67616, 197.65, NULL, 'C62 Sebaco', 'Bombero2', NULL, '2026-08-04 00:05:58', NULL, NULL, 197.65, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 197.65, NULL, NULL, 'BLOQUEADO', 58.32, 9, '6', NULL, 1, 70853, 24),
(242, 9, '2026-08-04', 14922, 14948, 18.36, NULL, 'C42 Relleno', 'Bombero2', NULL, '2026-08-04 00:09:15', NULL, NULL, 18.36, '', '', 'CONSUMO', 18.36, NULL, NULL, 'APROBADO', 24.04, 15, '6', NULL, 1, 70854, 24),
(243, 39, '2026-08-04', 47000, 47872, 398.12, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-04 00:17:21', NULL, NULL, 398.12, '', '', 'CONSUMO', 398.12, NULL, NULL, 'BLOQUEADO', 59.28, 9, '6', NULL, 1, 70855, 24),
(244, 48, '2026-08-04', 90332, 90782, 227.09, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-04 00:18:53', NULL, NULL, 227.09, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 227.09, NULL, NULL, 'BLOQUEADO', 77.37, 10, '6', NULL, 1, 70856, 24),
(245, 15, '2026-08-04', 17335, 17546, 46.29, NULL, 'JS-02 león ', 'Bombero2', NULL, '2026-08-04 04:38:42', NULL, NULL, 46.29, '', '', 'CONSUMO', 46.29, NULL, NULL, 'BLOQUEADO', 144.44, 13, '6', NULL, 1, 70857, 24),
(246, 80, '2026-08-04', 0, 0, 234.91, NULL, 'Denis Blas Ampie', 'Bombero2', NULL, '2026-08-04 04:40:33', NULL, NULL, 234.91, '', '', 'CONSUMO', 234.91, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70858, 24),
(247, 5, '2026-08-04', 15959, 16000, 52.65, NULL, 'C-14 Proinco', 'Bombero2', NULL, '2026-08-04 04:41:51', NULL, NULL, 52.65, '', '', 'CONSUMO', 52.65, NULL, NULL, 'BLOQUEADO', 90.89, 12, '6', NULL, 1, 70859, 24),
(248, 54, '2026-08-04', 38964, 39009, 38.32, NULL, 'C-45 Reynaga-Loyola', 'Bombero2', NULL, '2026-08-04 04:43:26', NULL, NULL, 38.32, '', '', 'CONSUMO', 38.31, NULL, NULL, 'APROBADO', 4.41, 8, '6', NULL, 1, 70860, 24),
(249, 80, '2026-08-04', 0, 0, 113.32, NULL, 'Denis Blas Ampie', 'Bombero2', NULL, '2026-08-04 04:44:14', NULL, NULL, 113.32, '', '', 'CONSUMO', 113.31, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70861, 24),
(250, 75, '2026-08-04', 0, 0, 964.70, NULL, '', 'Bombero2', NULL, '2026-08-04 10:53:58', NULL, NULL, 964.7, '', '', 'CONSUMO', 964.7, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70862, 27),
(255, 61, '2026-08-04', 1135564, 1136279, 396.86, NULL, 'C61 Esteli-Altagracia', 'Bombero2', NULL, '2026-08-04 19:36:09', NULL, NULL, 396.86, '', '', 'CONSUMO', 396.86, NULL, NULL, 'BLOQUEADO', 21.01, 9, '6', NULL, 1, 70865, 27),
(256, 41, '2026-08-04', 176249, 176297, 43.09, NULL, 'C20 Ciudad Sandino-La Reynaga', 'Bombero2', NULL, '2026-08-04 19:40:03', NULL, NULL, 43.09, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 43.09, NULL, NULL, 'BLOQUEADO', 156.48, 9, '6', NULL, 1, 70866, 27),
(257, 40, '2026-08-04', 67616, 67745, 90.29, NULL, 'C62 Jinotepe-Local', 'Bombero2', NULL, '2026-08-04 19:46:37', NULL, NULL, 90.29, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 90.29, NULL, NULL, 'BLOQUEADO', 5.4, 9, '6', NULL, 1, 70867, 27),
(258, 15, '2026-08-04', 17546, 17788, 54.94, NULL, 'JS-02 Chichigalpa', 'Bombero2', NULL, '2026-08-04 19:47:50', NULL, NULL, 54.94, '', '', 'CONSUMO', 54.94, NULL, NULL, 'APROBADO', 16.66, 13, '6', NULL, 1, 70868, 27),
(259, 9, '2026-08-04', 14948, 15112, 53.60, NULL, 'C42 Cantera-Guanacaste', 'Bombero2', NULL, '2026-08-04 19:49:12', NULL, NULL, 53.6, '', '', 'CONSUMO', 53.6, NULL, NULL, 'APROBADO', 11.58, 15, '6', NULL, 1, 70869, 27),
(260, 5, '2026-08-04', 16000, 16001, 36.14, NULL, 'C-14 Gustavo Rocha-Llansa', 'Bombero2', NULL, '2026-08-04 19:51:11', NULL, NULL, 36.14, 'Rolando José Silva Poveda ', '284-260184-0004A', 'CONSUMO', 36.13, NULL, NULL, 'BLOQUEADO', 0.05, 12, '6', NULL, 1, 70870, 27),
(261, 46, '2026-08-04', 242931, 242932, 11.36, NULL, 'C26 Prueba de encendido', 'Bombero2', NULL, '2026-08-04 19:53:10', NULL, NULL, 11.36, '', '', 'CONSUMO', 11.36, NULL, NULL, 'APROBADO', 0.33, 10, '6', NULL, 1, 70871, 27),
(262, 60, '2026-08-04', 38098, 38183, 74.96, NULL, 'C60 Locales', 'Bombero2', NULL, '2026-08-04 19:54:08', NULL, NULL, 74.96, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 74.96, NULL, NULL, 'BLOQUEADO', 108.89, 9, '6', NULL, 1, 70872, 27),
(263, 38, '2026-08-04', 82271, 82723, 200.92, NULL, 'C58 Puerto Sandino', 'Bombero2', NULL, '2026-08-04 20:05:36', NULL, NULL, 200.92, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 200.92, NULL, NULL, 'BLOQUEADO', 55.71, 9, '6', NULL, 1, 70873, 27),
(264, 75, '2026-08-04', 0, 0, 10.00, NULL, '', 'Bombero2', NULL, '2026-08-04 21:34:33', NULL, NULL, 10, '', '', 'CONSUMO', 10, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70874, 27),
(265, 75, '2026-08-04', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-04 21:35:08', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70875, 27),
(266, 75, '2026-08-04', 0, 0, 13.43, NULL, '', 'Bombero2', NULL, '2026-08-04 21:35:33', NULL, NULL, 13.43, '', '', 'CONSUMO', 13.43, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70876, 27),
(267, 8, '2026-08-04', 659448, 659602, 41.40, NULL, 'JS-03 Malacatoya', 'Bombero2', NULL, '2026-08-04 21:39:21', NULL, NULL, 41.4, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 41.4, NULL, NULL, 'APROBADO', 60315.1, 15, '6', NULL, 1, 70877, 27),
(268, 46, '2026-08-04', 242932, 242933, 3.79, NULL, 'C26 Relleno de Filtro', 'Bombero2', NULL, '2026-08-04 21:40:09', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 10, '6', NULL, 1, 70878, 27),
(269, 84, '2026-08-04', 0, 0, 104.98, NULL, 'Toyota L M285618/Cuota Anibal Sevilla', 'Bombero2', NULL, '2026-08-04 21:41:14', NULL, NULL, 104.98, '', '', 'CONSUMO', 104.98, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 70879, 27),
(270, 43, '2026-08-04', 70262, 70563, 143.68, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-04 22:04:21', NULL, NULL, 143.68, '', '', 'CONSUMO', 143.68, NULL, NULL, 'BLOQUEADO', 7.92, 10, '6', NULL, 1, 70880, 27),
(271, 65, '2026-08-04', 14723, 15030, 157.49, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-08-04 23:07:51', NULL, NULL, 157.49, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 157.49, NULL, NULL, 'BLOQUEADO', 36.76, 9, '6', NULL, 1, 70881, 27),
(272, 6, '2026-08-04', 46241, 46658, 109.58, NULL, 'C38 Coonserva', 'Bombero2', NULL, '2026-08-04 23:09:08', NULL, NULL, 109.58, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 109.58, NULL, NULL, 'APROBADO', 14.4, 15, '6', NULL, 1, 70882, 27),
(273, 80, '2026-08-04', 0, 0, 66.03, NULL, '', 'Bombero2', NULL, '2026-08-04 23:10:26', NULL, NULL, 66.03, '', '', 'CONSUMO', 66.03, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70883, 27),
(274, 48, '2026-08-04', 90782, 91380, 303.64, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-04 23:12:11', NULL, NULL, 303.64, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 303.64, NULL, NULL, 'BLOQUEADO', 7.46, 10, '6', NULL, 1, 70884, 27),
(275, 44, '2026-08-05', 46811, 47244, 233.54, NULL, 'C19 Diriamba-Nejapa', 'Bombero2', NULL, '2026-08-05 04:34:57', NULL, NULL, 233.54, '', '', 'CONSUMO', 233.53, NULL, NULL, 'BLOQUEADO', 110, 9, '6', NULL, 1, 70885, 27),
(276, 73, '2026-08-05', 0, 0, 39.02, NULL, 'Mitsubishi M372333/Rescate', 'Bombero2', NULL, '2026-08-05 04:35:49', NULL, NULL, 39.02, '', '', 'CONSUMO', 39.02, NULL, NULL, 'APROBADO', 0, 40, '5', NULL, 1, 70886, 27),
(277, 63, '2026-08-05', 29258, 29387, 76.27, NULL, 'C66 Locales', 'Bombero2', NULL, '2026-08-05 04:36:45', NULL, NULL, 76.27, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 76.27, NULL, NULL, 'BLOQUEADO', 90.13, 9, '6', NULL, 1, 70887, 27),
(278, 80, '2026-08-05', 0, 0, 157.93, NULL, '', 'Bombero2', NULL, '2026-08-05 04:37:24', NULL, NULL, 157.93, '', '', 'CONSUMO', 157.93, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70888, 27),
(279, 62, '2026-08-05', 50128, 50271, 61.58, NULL, 'C64 Xiloa', 'Bombero2', NULL, '2026-08-05 04:39:20', NULL, NULL, 61.58, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 61.58, NULL, NULL, 'APROBADO', 8.8, 9, '6', NULL, 1, 70889, 28),
(280, 41, '2026-08-05', 176297, 176656, 185.53, NULL, 'C20 Santo Tomas', 'Bombero2', NULL, '2026-08-05 08:15:13', NULL, NULL, 185.53, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 185.53, NULL, NULL, 'BLOQUEADO', 7.32, 9, '6', NULL, 1, 70890, 29),
(281, 80, '2026-08-05', 0, 0, 186.16, NULL, '', 'Bombero2', NULL, '2026-08-05 08:16:50', NULL, NULL, 186.16, '', '', 'CONSUMO', 186.16, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70891, 29),
(282, 71, '2026-08-05', 0, 0, 53.56, NULL, 'Kia 2700 M353553/Gestiones Compras', 'Bombero2', NULL, '2026-08-05 08:18:08', NULL, NULL, 53.56, '', '', 'CONSUMO', 53.55, NULL, NULL, 'APROBADO', 0, 45, '5', NULL, 1, 70892, 29),
(283, 40, '2026-08-05', 67745, 68044, 128.39, NULL, 'C62 Chinandega', 'Bombero2', NULL, '2026-08-05 08:23:03', NULL, NULL, 128.39, '', '', 'CONSUMO', 128.39, NULL, NULL, 'APROBADO', 8.82, 9, '6', NULL, 1, 70893, 29),
(284, 41, '2026-08-05', 176656, 176657, 3.79, NULL, 'C20 Relleno de Filtro', 'Bombero2', NULL, '2026-08-05 11:12:55', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 9, '6', NULL, 1, 70894, 29),
(285, 38, '2026-08-05', 82723, 83030, 135.01, NULL, 'C58 Puerto Sandino', 'Bombero2', NULL, '2026-08-05 11:23:29', NULL, NULL, 135.01, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 135, NULL, NULL, 'APROBADO', 8.61, 9, '6', NULL, 1, 70895, 29),
(287, 75, '2026-08-05', 0, 0, 8.97, NULL, '', 'Bombero2', NULL, '2026-08-05 11:51:04', NULL, NULL, 8.97, '', '', 'CONSUMO', 8.97, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70897, 29),
(291, 15, '2026-08-05', 17789, 17790, 45.45, NULL, 'JS-02 Oscar Sanchez', 'Bombero2', NULL, '2026-08-05 13:06:52', NULL, NULL, 45.45, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 45.45, NULL, NULL, 'BLOQUEADO', 0.08, 13, '6', NULL, 1, 70901, 29),
(292, 63, '2026-08-05', 29387, 29669, 76.90, NULL, 'C66 Nandaime', 'Bombero2', NULL, '2026-08-05 13:10:37', NULL, NULL, 76.9, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 76.89, NULL, NULL, 'BLOQUEADO', 13.9, 9, '6', NULL, 1, 70902, 29),
(293, 75, '2026-08-05', 0, 0, 43.11, NULL, '', 'Bombero2', NULL, '2026-08-05 13:23:46', NULL, NULL, 43.11, '', '', 'CONSUMO', 43.11, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70903, 29),
(294, 39, '2026-08-05', 47872, 48743, 412.96, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-05 13:26:49', NULL, NULL, 412.96, '', '', 'CONSUMO', 412.96, NULL, NULL, 'BLOQUEADO', 7.98, 9, '6', NULL, 1, 70904, 29),
(295, 55, '2026-08-05', 17348, 17541, 203.62, NULL, 'C46 Managua-San Benito', 'Bombero2', NULL, '2026-08-05 13:38:30', NULL, NULL, 203.62, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 203.62, NULL, NULL, 'BLOQUEADO', 20.61, 8, '6', NULL, 1, 70905, 29),
(296, 43, '2026-08-05', 70563, 70862, 155.83, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-05 15:31:07', NULL, NULL, 155.83, '', '', 'CONSUMO', 155.83, NULL, NULL, 'BLOQUEADO', 7.27, 10, '6', NULL, 1, 70906, 29),
(297, 50, '2026-08-05', 1156453, 1157075, 329.10, NULL, 'C36 Los Brasiles', 'Bombero2', NULL, '2026-08-05 16:39:52', NULL, NULL, 329.1, '', '', 'CONSUMO', 329.1, NULL, NULL, 'BLOQUEADO', 7.15, 8, '6', NULL, 1, 70907, 29),
(298, 75, '2026-08-05', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-05 16:41:47', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70908, 29),
(299, 61, '2026-08-05', 1136279, 1136686, 147.81, NULL, 'C61 Batahola-Santo Domingo', 'Bombero2', NULL, '2026-08-05 16:43:30', NULL, NULL, 147.81, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 147.81, NULL, NULL, 'BLOQUEADO', 10.43, 9, '6', NULL, 1, 70909, 29),
(300, 73, '2026-08-05', 0, 0, 18.67, NULL, 'Mitsubishi M372333/Gestiones operaciones', 'Bombero2', NULL, '2026-08-05 16:44:16', NULL, NULL, 18.67, '', '', 'CONSUMO', 18.67, NULL, NULL, 'APROBADO', 0, 40, '5', NULL, 1, 70910, 29),
(301, 12, '2026-08-05', 330589, 331082, 155.98, NULL, 'LPG2-097 La Paz Centro ', 'Bombero2', NULL, '2026-08-05 21:21:30', NULL, NULL, 155.98, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 155.98, NULL, NULL, 'BLOQUEADO', 11.96, 15, '6', NULL, 1, 70911, 30),
(302, 45, '2026-08-05', 83288, 83589, 140.52, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-05 21:23:12', NULL, NULL, 140.52, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 140.52, NULL, NULL, 'BLOQUEADO', 8.11, 10, '6', NULL, 1, 70912, 30),
(303, 64, '2026-08-05', 8606, 8607, 53.77, NULL, 'C67 Proinco', 'Bombero2', NULL, '2026-08-05 21:25:29', NULL, NULL, 53.77, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 53.77, NULL, NULL, 'BLOQUEADO', 0.07, 9, '6', NULL, 1, 70913, 30),
(304, 48, '2026-08-05', 91380, 91977, 302.23, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-05 21:31:22', NULL, NULL, 302.23, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 302.23, NULL, NULL, 'BLOQUEADO', 7.48, 10, '6', NULL, 1, 70914, 30),
(305, 80, '2026-08-05', 0, 0, 12.13, NULL, '', 'Bombero2', NULL, '2026-08-05 21:31:52', NULL, NULL, 12.13, '', '', 'CONSUMO', 12.13, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70915, 30),
(306, 65, '2026-08-05', 15030, 15367, 165.66, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-08-05 21:33:07', NULL, NULL, 165.66, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 165.66, NULL, NULL, 'BLOQUEADO', 7.7, 9, '6', NULL, 1, 70916, 30),
(307, 80, '2026-08-05', 0, 0, 152.33, NULL, '', 'Bombero2', NULL, '2026-08-05 21:33:38', NULL, NULL, 152.33, '', '', 'CONSUMO', 152.33, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70917, 30),
(308, 14, '2026-08-05', 25129, 26016, 153.06, NULL, 'JS-01 Bonanza ', 'Bombero2', NULL, '2026-08-05 21:36:08', NULL, NULL, 153.06, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 153.06, NULL, NULL, 'BLOQUEADO', 41.58, 15, '6', NULL, 1, 70918, 30),
(310, 62, '2026-08-06', 50271, 50794, 246.20, NULL, 'C64 Wiwili-Xiloa', 'Bombero2', NULL, '2026-08-06 08:30:58', NULL, NULL, 246.2, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 246.19, NULL, NULL, 'BLOQUEADO', 8.04, 9, '6', NULL, 1, 70920, 32),
(311, 44, '2026-08-06', 47244, 47593, 157.83, NULL, 'C19 Puerto Corinto', 'Bombero2', NULL, '2026-08-06 08:32:49', NULL, NULL, 157.83, '', '', 'CONSUMO', 157.83, NULL, NULL, 'APROBADO', 8.37, 9, '6', NULL, 1, 70921, 32),
(312, 59, '2026-08-06', 1360443, 1360969, 260.77, NULL, 'C59 Puerto Sandino', 'Bombero2', NULL, '2026-08-06 08:36:43', NULL, NULL, 260.77, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 260.77, NULL, NULL, 'BLOQUEADO', 7.63, 9, '6', NULL, 1, 70922, 32),
(313, 54, '2026-08-06', 39009, 39395, 159.48, NULL, 'C45 Leon', 'Bombero2', NULL, '2026-08-06 11:16:54', NULL, NULL, 159.48, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 159.48, NULL, NULL, 'BLOQUEADO', 9.16, 8, '6', NULL, 1, 70923, 32),
(314, 40, '2026-08-06', 68044, 68438, 163.92, NULL, 'C62 Chinandega-Diriamba', 'Bombero2', NULL, '2026-08-06 11:18:18', NULL, NULL, 163.92, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 163.92, NULL, NULL, 'APROBADO', 9.1, 9, '6', NULL, 1, 70924, 32),
(315, 6, '2026-08-06', 46658, 46684, 17.39, NULL, 'C38 D Guerrero-Zeta Gaz', 'Bombero2', NULL, '2026-08-06 11:19:38', NULL, NULL, 17.39, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 17.39, NULL, NULL, 'APROBADO', 5.6, 15, '6', NULL, 1, 70925, 32),
(316, 4, '2026-08-06', 40225, 40372, 72.78, NULL, 'C12 Prodecon', 'Bombero2', NULL, '2026-08-06 13:23:12', NULL, NULL, 72.78, 'Jorge Gregorio Ney Almendárez ', '001-011179-0010X', 'CONSUMO', 72.78, NULL, NULL, 'APROBADO', 7.65, 10, '6', NULL, 1, 70926, 32),
(317, 57, '2026-08-06', 63821, 64153, 211.55, NULL, 'C56 Matagalpa-Locales', 'Bombero2', NULL, '2026-08-06 13:26:58', NULL, NULL, 211.55, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 211.54, NULL, NULL, 'BLOQUEADO', 5.94, 8, '6', NULL, 1, 70927, 32),
(318, 88, '2026-08-06', 1347, 1640, 221.00, NULL, 'C69 Competra-Managua', 'Bombero2', NULL, '2026-08-06 13:36:56', NULL, NULL, 221, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 221, NULL, NULL, 'BLOQUEADO', 5.02, 9, '6', NULL, 1, 70928, 32),
(319, 8, '2026-08-06', 659601, 660103, 62.55, NULL, 'JS-03 Chinandega', 'Bombero2', NULL, '2026-08-06 13:51:06', NULL, NULL, 62.55, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 62.55, NULL, NULL, 'BLOQUEADO', 30.38, 15, '6', NULL, 1, 70929, 32),
(320, 50, '2026-08-06', 1157075, 1157247, 80.13, NULL, 'C36 Nindiri-Coyotepe', 'Bombero2', NULL, '2026-08-06 13:52:41', NULL, NULL, 80.13, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 80.13, NULL, NULL, 'APROBADO', 8.13, 8, '6', NULL, 1, 70930, 32),
(321, 53, '2026-08-06', 3339, 3564, 242.98, NULL, 'C44 Rescate', 'Bombero2', NULL, '2026-08-06 14:36:26', NULL, NULL, 242.98, '', '', 'CONSUMO', 242.98, NULL, NULL, 'BLOQUEADO', 3.51, 9, '6', NULL, 1, 70931, 32),
(322, 39, '2026-08-06', 48743, 49323, 258.78, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-06 14:38:01', NULL, NULL, 258.78, '', '', 'CONSUMO', 258.78, NULL, NULL, 'APROBADO', 8.48, 9, '6', NULL, 1, 70932, 32),
(323, 75, '2026-08-06', 0, 0, 700.19, NULL, '', 'Bombero2', NULL, '2026-08-06 14:38:33', NULL, NULL, 700.19, '', '', 'CONSUMO', 700.19, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70933, 32),
(324, 9, '2026-08-06', 15112, 15261, 49.46, NULL, 'C42 Matadero San Martin', 'Bombero2', NULL, '2026-08-06 14:39:35', NULL, NULL, 49.46, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 49.46, NULL, NULL, 'APROBADO', 11.42, 15, '6', NULL, 1, 70934, 32),
(325, 12, '2026-08-06', 331082, 331155, 46.06, NULL, 'LPG2-097 Managua', 'Bombero2', NULL, '2026-08-06 15:52:04', NULL, NULL, 46.06, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 46.06, NULL, NULL, 'BLOQUEADO', 6, 15, '6', NULL, 1, 70935, 32),
(326, 14, '2026-08-06', 26016, 26257, 57.95, NULL, 'AJS-01 Santa Marta', 'Bombero2', NULL, '2026-08-06 15:53:28', NULL, NULL, 57.95, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 57.95, NULL, NULL, 'APROBADO', 15.76, 15, '6', NULL, 1, 70936, 32),
(327, 47, '2026-08-06', 33537, 33856, 173.50, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-06 15:59:46', NULL, NULL, 173.5, '', '', 'CONSUMO', 173.5, NULL, NULL, 'BLOQUEADO', 6.96, 9, '6', NULL, 1, 70937, 32),
(328, 85, '2026-08-06', 0, 0, 60.30, NULL, 'Isuzu M401276/Cuota Roger Vilchez', 'Bombero2', NULL, '2026-08-06 16:01:16', NULL, NULL, 60.3, '', '', 'CONSUMO', 60.3, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 70938, 32),
(329, 15, '2026-08-06', 17790, 17854, 28.42, NULL, 'JS-02 Veracruz', 'Bombero2', NULL, '2026-08-06 16:07:53', NULL, NULL, 28.42, '', '', 'CONSUMO', 28.41, NULL, NULL, 'APROBADO', 8.46, 13, '6', NULL, 1, 70939, 32),
(330, 38, '2026-08-06', 83030, 83337, 134.53, NULL, 'C58 Puerto Sandino', 'Bombero2', NULL, '2026-08-06 16:16:38', NULL, NULL, 134.53, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 134.53, NULL, NULL, 'APROBADO', 8.64, 9, '6', NULL, 1, 70940, 32),
(331, 50, '2026-08-06', 1157247, 1157248, 3.79, NULL, 'C36 Relleno de Filtro', 'Bombero2', NULL, '2026-08-06 16:22:02', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 8, '6', NULL, 1, 70941, 32),
(332, 64, '2026-08-06', 8607, 9060, 193.60, NULL, 'C67 Eladio Peralta', 'Bombero2', NULL, '2026-08-06 16:40:34', NULL, NULL, 193.6, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 193.6, NULL, NULL, 'APROBADO', 8.86, 9, '6', NULL, 1, 70942, 32),
(333, 80, '2026-08-06', 0, 0, 134.01, NULL, '', 'Bombero2', NULL, '2026-08-06 17:39:47', NULL, NULL, 134.01, '', '', 'CONSUMO', 134.01, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70943, 32),
(334, 59, '2026-08-06', 1360969, 1361117, 84.09, NULL, 'C59 Puerto Sandino', 'Bombero2', NULL, '2026-08-06 17:41:28', NULL, NULL, 84.09, '', '', 'CONSUMO', 84.09, NULL, NULL, 'BLOQUEADO', 6.68, 9, '6', NULL, 1, 70944, 32),
(335, 63, '2026-08-06', 29669, 29728, 45.07, NULL, 'C66 Locales', 'Bombero2', NULL, '2026-08-06 17:47:03', NULL, NULL, 45.07, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 45.07, NULL, NULL, 'BLOQUEADO', 4.92, 9, '6', NULL, 1, 70945, 32),
(337, 45, '2026-08-06', 83589, 84038, 245.10, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-06 19:15:27', NULL, NULL, 245.1, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 245.09, NULL, NULL, 'BLOQUEADO', 6.93, 10, '6', NULL, 1, 70947, 32),
(338, 5, '2026-08-06', 16001, 16255, 206.59, NULL, 'C14 Mina Lizawe', 'Bombero2', NULL, '2026-08-06 19:39:36', NULL, NULL, 206.59, '', '', 'CONSUMO', 206.59, NULL, NULL, 'BLOQUEADO', 4.66, 12, '6', NULL, 1, 70948, 32),
(339, 48, '2026-08-06', 91977, 92575, 306.74, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-06 19:50:58', NULL, NULL, 306.74, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 306.74, NULL, NULL, 'BLOQUEADO', 7.38, 10, '6', NULL, 1, 70949, 32),
(340, 55, '2026-08-06', 17541, 17643, 118.88, NULL, 'C46 Managua-San Benito', 'Bombero2', NULL, '2026-08-06 20:25:37', NULL, NULL, 118.88, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 118.88, NULL, NULL, 'BLOQUEADO', 3.26, 8, '6', NULL, 1, 70950, 32),
(341, 88, '2026-08-07', 1640, 1712, 13.05, NULL, 'C69 Locales', 'Bombero2', NULL, '2026-08-07 10:05:05', NULL, NULL, 13.05, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 13.05, NULL, NULL, 'APROBADO', 20.94, 9, '6', NULL, 1, 70951, 34),
(342, 54, '2026-08-07', 39395, 39602, 95.51, NULL, 'C45 Leon-Local', 'Bombero2', NULL, '2026-08-07 10:06:28', NULL, NULL, 95.51, '', '', 'CONSUMO', 95.5, NULL, NULL, 'APROBADO', 8.2, 8, '6', NULL, 1, 70952, 34),
(343, 8, '2026-08-07', 660103, 660217, 32.93, NULL, 'JS-03 Martines e Hijos', 'Bombero2', NULL, '2026-08-07 10:10:27', NULL, NULL, 32.93, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 32.93, NULL, NULL, 'APROBADO', 13.07, 15, '6', NULL, 1, 70953, 34),
(344, 43, '2026-08-07', 70862, 71311, 227.48, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-07 10:13:31', NULL, NULL, 227.48, '', '', 'CONSUMO', 227.48, NULL, NULL, 'BLOQUEADO', 7.47, 10, '6', NULL, 1, 70954, 34),
(345, 78, '2026-08-07', 0, 0, 72.47, NULL, 'Jose Reynerio Duran Cabezas', 'Bombero2', NULL, '2026-08-07 10:16:32', NULL, NULL, 72.47, '', '', 'CONSUMO', 72.47, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70955, 34),
(346, 68, '2026-08-07', 0, 0, 56.59, NULL, 'Toyota H M110142/Gestiones Taller', 'Bombero2', NULL, '2026-08-07 10:17:32', NULL, NULL, 56.59, '', '', 'CONSUMO', 56.59, NULL, NULL, 'APROBADO', 0, 50, '5', NULL, 1, 70956, 34),
(347, 61, '2026-08-07', 1136686, 1137059, 211.22, NULL, 'C61', 'Bombero2', NULL, '2026-08-07 11:44:38', NULL, NULL, 211.22, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 211.22, NULL, NULL, 'BLOQUEADO', 6.68, 9, '6', NULL, 1, 70957, 34),
(348, 41, '2026-08-07', 176657, 177305, 312.54, NULL, 'C20 El Rama', 'Bombero2', NULL, '2026-08-07 11:45:36', NULL, NULL, 312.54, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 312.54, NULL, NULL, 'BLOQUEADO', 7.85, 9, '6', NULL, 1, 70958, 34),
(349, 81, '2026-08-07', 6, 7, 3.79, NULL, 'C71 Relleno prueba de encendido', 'Bombero2', NULL, '2026-08-07 21:41:50', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 0, '6', NULL, 1, 70959, 34),
(350, 81, '2026-08-07', 7, 8, 37.85, NULL, 'C71 Relleno prueba de encendido', 'Bombero2', NULL, '2026-08-07 21:42:29', NULL, NULL, 37.85, '', '', 'CONSUMO', 37.85, NULL, NULL, 'APROBADO', 0.1, 0, '6', NULL, 1, 70960, 34),
(351, 64, '2026-08-07', 9060, 9150, 39.52, NULL, 'C67 Gustavo Rocha', 'Bombero2', NULL, '2026-08-07 21:44:19', NULL, NULL, 39.52, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 39.52, NULL, NULL, 'APROBADO', 8.62, 9, '6', NULL, 1, 70961, 34),
(352, 75, '2026-08-07', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-07 21:44:50', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70962, 33),
(353, 59, '2026-08-07', 1361117, 1361407, 141.42, NULL, 'C59 Puerto Sandino', 'Bombero2', NULL, '2026-08-07 21:45:48', NULL, NULL, 141.42, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 141.42, NULL, NULL, 'BLOQUEADO', 7.76, 9, '6', NULL, 1, 70963, 34),
(354, 44, '2026-08-07', 47593, 48158, 244.41, NULL, 'C19 Puerto Sandino', 'Bombero2', NULL, '2026-08-07 21:46:55', NULL, NULL, 244.41, '', '', 'CONSUMO', 244.41, NULL, NULL, 'APROBADO', 8.75, 9, '6', NULL, 1, 70964, 34),
(355, 80, '2026-08-07', 0, 0, 179.97, NULL, '', 'Bombero2', NULL, '2026-08-07 21:48:07', NULL, NULL, 179.97, '', '', 'CONSUMO', 179.97, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70965, 34),
(356, 65, '2026-08-07', 15367, 15701, 154.77, NULL, 'C68 Puerto Corinto', 'Bombero2', NULL, '2026-08-07 21:49:34', NULL, NULL, 154.77, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 154.77, NULL, NULL, 'APROBADO', 8.17, 9, '6', NULL, 1, 70966, 33),
(357, 15, '2026-08-07', 17854, 18194, 74.60, NULL, 'JS-02 Chinandega', 'Bombero2', NULL, '2026-08-07 21:50:56', NULL, NULL, 74.6, '', '', 'CONSUMO', 74.6, NULL, NULL, 'BLOQUEADO', 17.25, 13, '6', NULL, 1, 70967, 33),
(358, 80, '2026-08-07', 0, 0, 55.40, NULL, '', 'Bombero2', NULL, '2026-08-07 21:51:33', NULL, NULL, 55.4, '', '', 'CONSUMO', 55.4, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70968, 33),
(359, 80, '2026-08-07', 0, 0, 208.58, NULL, '', 'Bombero2', NULL, '2026-08-07 21:52:00', NULL, NULL, 208.58, '', '', 'CONSUMO', 208.58, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70969, 33),
(360, 5, '2026-08-07', 16255, 16285, 35.12, NULL, 'C14 Proinco', 'Bombero2', NULL, '2026-08-07 21:54:05', NULL, NULL, 35.12, 'Rolando José Silva Poveda ', '284-260184-0004A', 'CONSUMO', 35.12, NULL, NULL, 'BLOQUEADO', 3.25, 12, '6', NULL, 1, 70970, 33),
(361, 54, '2026-08-07', 39602, 39703, 68.29, NULL, 'C45 Los Brasiles-Competra', 'Bombero2', NULL, '2026-08-07 21:58:21', NULL, NULL, 68.29, '', '', 'CONSUMO', 68.29, NULL, NULL, 'BLOQUEADO', 5.58, 8, '6', NULL, 1, 70971, 33),
(362, 79, '2026-08-07', 5611, 5779, 68.13, NULL, 'C43 Competra', 'Bombero2', NULL, '2026-08-07 22:18:32', NULL, NULL, 68.13, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 68.13, NULL, NULL, 'APROBADO', 9.34, 8, '6', NULL, 1, 70972, 33),
(363, 47, '2026-08-07', 33856, 34304, 248.12, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-07 22:19:46', NULL, NULL, 248.12, '', '', 'CONSUMO', 248.12, NULL, NULL, 'BLOQUEADO', 6.83, 9, '6', NULL, 1, 70973, 33),
(364, 88, '2026-08-07', 1712, 2010, 119.24, NULL, 'C69 Chinandega', 'Bombero2', NULL, '2026-08-07 22:20:46', NULL, NULL, 119.24, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 119.24, NULL, NULL, 'APROBADO', 9.45, 9, '6', NULL, 1, 70974, 33),
(365, 9, '2026-08-07', 15261, 15547, 83.47, NULL, 'C42 Maria teresa', 'Bombero2', NULL, '2026-08-07 22:21:39', NULL, NULL, 83.47, '', '', 'CONSUMO', 83.47, NULL, NULL, 'APROBADO', 12.99, 15, '6', NULL, 1, 70975, 33),
(366, 40, '2026-08-07', 68438, 68617, 81.93, NULL, 'C62 Leon', 'Bombero2', NULL, '2026-08-07 22:23:02', NULL, NULL, 81.93, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 81.92, NULL, NULL, 'APROBADO', 8.26, 9, '6', NULL, 1, 70976, 33),
(367, 42, '2026-08-07', 2128823, 2129194, 200.96, NULL, 'C15 Puerto Corinto', 'Bombero2', NULL, '2026-08-07 22:29:13', NULL, NULL, 200.96, '', '', 'CONSUMO', 200.96, NULL, NULL, 'BLOQUEADO', 6.99, 10, '6', NULL, 1, 70977, 33),
(368, 12, '2026-08-07', 331155, 331500, 105.97, NULL, 'LPG2-097 Chinandega', 'Bombero2', NULL, '2026-08-07 22:30:35', NULL, NULL, 105.97, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 105.97, NULL, NULL, 'BLOQUEADO', 12.32, 15, '6', NULL, 1, 70978, 33),
(369, 6, '2026-08-08', 46684, 46685, 3.79, NULL, 'C38 Relleno de Filtro', 'Bombero2', NULL, '2026-08-08 09:22:33', NULL, NULL, 3.79, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 15, '6', NULL, 1, 70979, 35),
(370, 75, '2026-08-08', 0, 0, 986.82, NULL, '', 'Bombero2', NULL, '2026-08-08 09:23:27', NULL, NULL, 986.82, '', '', 'CONSUMO', 986.82, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70980, 35),
(371, 82, '2026-08-08', 0, 0, 42.07, NULL, 'Areli Espinoza Rizo/F-', 'Bombero2', NULL, '2026-08-08 09:24:22', NULL, NULL, 42.07, '', '', 'CONSUMO', 42.07, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70981, 35),
(372, 46, '2026-08-08', 242933, 242934, 11.36, NULL, 'C26 Lavado de Piezas', 'Bombero2', NULL, '2026-08-08 09:24:57', NULL, NULL, 11.36, '', '', 'CONSUMO', 11.36, NULL, NULL, 'APROBADO', 0.33, 10, '6', NULL, 1, 70982, 35),
(373, 82, '2026-08-08', 0, 0, 421.74, NULL, 'Areli Espinoza Rizo/F-', 'Bombero2', NULL, '2026-08-08 09:34:57', NULL, NULL, 421.74, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 421.74, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70983, 35),
(374, 85, '2026-08-08', 0, 0, 18.41, NULL, 'Isuzu M401276/Cuota y entrega de productos', 'Bombero2', NULL, '2026-08-08 10:16:20', NULL, NULL, 18.41, '', '', 'CONSUMO', 18.41, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 70984, 35),
(375, 39, '2026-08-08', 49323, 50048, 349.39, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-08 10:21:10', NULL, NULL, 349.39, '', '', 'CONSUMO', 349.39, NULL, NULL, 'BLOQUEADO', 7.85, 9, '6', NULL, 1, 70985, 35),
(376, 51, '2026-08-08', 95690, 96157, 231.87, NULL, 'C37 Puerto Corinto', 'Bombero2', NULL, '2026-08-08 11:34:33', NULL, NULL, 231.87, '', '', 'CONSUMO', 231.87, NULL, NULL, 'BLOQUEADO', 7.62, 10, '6', NULL, 1, 70986, 35),
(377, 45, '2026-08-08', 84038, 84487, 217.38, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-08 11:35:37', NULL, NULL, 217.38, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 217.38, NULL, NULL, 'BLOQUEADO', 7.82, 10, '6', NULL, 1, 70987, 35),
(378, 43, '2026-08-08', 71311, 71910, 274.70, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-08 11:37:04', NULL, NULL, 274.7, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 274.7, NULL, NULL, 'BLOQUEADO', 8.25, 10, '6', NULL, 1, 70988, 35),
(379, 48, '2026-08-08', 92575, 93174, 306.79, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-08 11:52:45', NULL, NULL, 306.79, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 306.79, NULL, NULL, 'BLOQUEADO', 7.39, 10, '6', NULL, 1, 70989, 35),
(380, 79, '2026-08-08', 5779, 6065, 96.80, NULL, 'C43 Chinandega', 'Bombero2', NULL, '2026-08-08 12:13:09', NULL, NULL, 96.8, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 96.8, NULL, NULL, 'BLOQUEADO', 11.19, 8, '6', NULL, 1, 70990, 35),
(381, 47, '2026-08-08', 34304, 34603, 152.98, NULL, 'Puerto Sandino', 'Bombero2', NULL, '2026-08-08 13:27:11', NULL, NULL, 152.98, '', '', 'CONSUMO', 152.98, NULL, NULL, 'BLOQUEADO', 7.39, 9, '6', NULL, 1, 70991, 35),
(382, 14, '2026-08-08', 26257, 26524, 83.64, NULL, 'JS-01 Boaco', 'Bombero2', NULL, '2026-08-08 13:28:55', NULL, NULL, 83.64, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 83.64, NULL, NULL, 'APROBADO', 12.07, 15, '6', NULL, 1, 70992, 35),
(383, 44, '2026-08-08', 48158, 48489, 142.31, NULL, 'C19 Puerto Corinto', 'Bombero2', NULL, '2026-08-08 13:30:47', NULL, NULL, 142.31, '', '', 'CONSUMO', 142.31, NULL, NULL, 'APROBADO', 8.79, 9, '6', NULL, 1, 70993, 35),
(384, 75, '2026-08-08', 0, 0, 498.48, NULL, '', 'Bombero2', NULL, '2026-08-08 13:35:09', NULL, NULL, 498.48, '', '', 'CONSUMO', 498.48, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70994, 35),
(385, 59, '2026-08-08', 1361407, 1361697, 144.89, NULL, 'C59 Puerto Sandino', 'Bombero2', NULL, '2026-08-08 15:58:12', NULL, NULL, 144.89, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 144.89, NULL, NULL, 'BLOQUEADO', 7.59, 9, '6', NULL, 1, 70995, 35),
(386, 80, '2026-08-08', 0, 0, 125.75, NULL, '', 'Bombero2', NULL, '2026-08-08 15:58:41', NULL, NULL, 125.75, '', '', 'CONSUMO', 125.75, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 70996, 35),
(387, 38, '2026-08-08', 83337, 83644, 142.42, NULL, 'C58 Puerto Corinto', 'Bombero2', NULL, '2026-08-08 15:59:48', NULL, NULL, 142.42, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 142.41, NULL, NULL, 'APROBADO', 8.16, 9, '6', NULL, 1, 70997, 35),
(388, 6, '2026-08-08', 46685, 46869, 56.75, NULL, 'C38 Miramontes', 'Bombero2', NULL, '2026-08-08 16:01:03', NULL, NULL, 56.75, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 56.75, NULL, NULL, 'APROBADO', 12.3, 15, '6', NULL, 1, 70998, 35),
(389, 15, '2026-08-08', 18194, 18452, 71.86, NULL, 'JS-02 Diriamba-Malacatoya-Llansa', 'Bombero2', NULL, '2026-08-08 16:04:22', NULL, NULL, 71.86, '', '', 'CONSUMO', 71.86, NULL, NULL, 'APROBADO', 13.59, 13, '6', NULL, 1, 70999, 35),
(390, 5, '2026-08-08', 16285, 16372, 184.03, NULL, 'C14 Mina', 'Bombero2', NULL, '2026-08-08 19:06:30', NULL, NULL, 184.03, 'Rolando José Silva Poveda ', '284-260184-0004A', 'CONSUMO', 184.03, NULL, NULL, 'BLOQUEADO', 1.78, 12, '6', NULL, 1, 71000, 35),
(391, 42, '2026-08-08', 2129194, 2129496, 151.59, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-08 19:42:10', NULL, NULL, 151.59, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 151.59, NULL, NULL, 'BLOQUEADO', 7.54, 10, '6', NULL, 1, 71001, 35),
(392, 40, '2026-08-08', 68617, 68775, 121.95, NULL, 'C62 Locales-Diriamba', 'Bombero2', NULL, '2026-08-08 20:52:56', NULL, NULL, 121.95, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 121.95, NULL, NULL, 'BLOQUEADO', 4.9, 9, '6', NULL, 1, 71002, 35),
(393, 54, '2026-08-08', 39703, 40088, 215.57, NULL, 'C45 Jinotepe-Competra', 'Bombero2', NULL, '2026-08-08 22:16:43', NULL, NULL, 215.57, '', '', 'CONSUMO', 215.57, NULL, NULL, 'BLOQUEADO', 6.76, 8, '6', NULL, 1, 71003, 35),
(394, 55, '2026-08-08', 17643, 18014, 272.79, NULL, 'C46 La Libertad-San Benito', 'Bombero2', NULL, '2026-08-08 22:19:43', NULL, NULL, 272.79, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 272.79, NULL, NULL, 'BLOQUEADO', 5.15, 8, '6', NULL, 1, 71004, 35),
(395, 12, '2026-08-08', 331500, 331704, 85.01, NULL, 'LPG2-097 Managua-Nindiri-San Benito-Tipitapa', 'Bombero2', NULL, '2026-08-08 22:21:12', NULL, NULL, 85.01, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 85.01, NULL, NULL, 'BLOQUEADO', 9.09, 15, '6', NULL, 1, 71005, 35),
(396, 39, '2026-08-10', 50048, 50774, 365.22, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-10 09:04:42', NULL, NULL, 365.22, '', '', 'CONSUMO', 365.22, NULL, NULL, 'BLOQUEADO', 7.52, 9, '6', NULL, 1, 71006, 37),
(397, 82, '2026-08-10', 0, 0, 443.99, NULL, 'Areli Espinoza Rizo/F-', 'Bombero2', NULL, '2026-08-10 09:05:33', NULL, NULL, 443.99, '', '', 'CONSUMO', 443.99, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71007, 37),
(398, 40, '2026-08-10', 68775, 69137, 186.09, NULL, 'C62 Matagalpa-Locales', 'Bombero2', NULL, '2026-08-10 09:10:22', NULL, NULL, 186.09, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 186.09, NULL, NULL, 'BLOQUEADO', 7.36, 9, '6', NULL, 1, 71008, 37),
(399, 48, '2026-08-10', 93174, 93773, 303.06, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-10 09:40:10', NULL, NULL, 303.06, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 303.06, NULL, NULL, 'BLOQUEADO', 7.48, 10, '6', NULL, 1, 71009, 38),
(400, 43, '2026-08-10', 71910, 72509, 295.90, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-10 09:42:02', NULL, NULL, 295.9, '', '', 'CONSUMO', 295.9, NULL, NULL, 'BLOQUEADO', 7.66, 10, '6', NULL, 1, 71010, 38),
(401, 61, '2026-08-10', 1137059, 1137806, 356.40, NULL, 'C61 Esteli-Sebaco-Leon', 'Bombero2', NULL, '2026-08-10 11:36:24', NULL, NULL, 356.4, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 356.39, NULL, NULL, 'BLOQUEADO', 7.93, 9, '6', NULL, 1, 71011, 38),
(402, 88, '2026-08-10', 2010, 2249, 158.63, NULL, 'C69 Masaya-Managua', 'Bombero2', NULL, '2026-08-10 12:15:42', NULL, NULL, 158.63, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 158.63, NULL, NULL, 'BLOQUEADO', 5.69, 9, '6', NULL, 1, 71012, 38),
(403, 45, '2026-08-10', 84487, 85085, 300.12, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-10 12:16:47', NULL, NULL, 300.12, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 300.12, NULL, NULL, 'BLOQUEADO', 7.54, 10, '6', NULL, 1, 71013, 38),
(404, 47, '2026-08-10', 34603, 35200, 292.91, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-10 12:31:40', NULL, NULL, 292.91, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 292.91, NULL, NULL, 'BLOQUEADO', 7.72, 9, '6', NULL, 1, 71014, 38),
(405, 75, '2026-08-10', 0, 0, 35.00, NULL, '', 'Bombero2', NULL, '2026-08-10 12:32:15', NULL, NULL, 35, '', '', 'CONSUMO', 35, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71015, 38),
(406, 38, '2026-08-10', 83644, 83950, 129.83, NULL, 'C58 Puerto Sandino', 'Bombero2', NULL, '2026-08-10 12:33:45', NULL, NULL, 129.83, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 129.82, NULL, NULL, 'APROBADO', 8.93, 9, '6', NULL, 1, 71016, 38),
(407, 42, '2026-08-10', 2129496, 2129945, 216.94, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-10 13:42:37', NULL, NULL, 216.94, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 216.94, NULL, NULL, 'BLOQUEADO', 7.83, 10, '6', NULL, 1, 71017, 38),
(408, 54, '2026-08-10', 40088, 40149, 68.91, NULL, 'C45 Locales', 'Bombero2', NULL, '2026-08-10 15:16:42', NULL, NULL, 68.91, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 68.91, NULL, NULL, 'BLOQUEADO', 3.33, 8, '6', NULL, 1, 71018, 37),
(409, 79, '2026-08-10', 6065, 6571, 275.50, NULL, 'C43 Competra-Jinotepe', 'Bombero2', NULL, '2026-08-10 15:51:16', NULL, NULL, 275.5, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 275.5, NULL, NULL, 'BLOQUEADO', 6.96, 8, '6', NULL, 1, 71019, 37),
(410, 41, '2026-08-10', 177305, 177647, 175.44, NULL, 'C20 Puerto Sandino', 'Bombero2', NULL, '2026-08-10 18:25:18', NULL, NULL, 175.44, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 175.44, NULL, NULL, 'BLOQUEADO', 7.38, 9, '6', NULL, 1, 71020, 37),
(412, 50, '2026-08-10', 1157248, 1157992, 399.89, NULL, 'C36 Locales-Leon', 'Bombero2', NULL, '2026-08-10 18:27:52', NULL, NULL, 399.89, '', '', 'CONSUMO', 399.88, NULL, NULL, 'BLOQUEADO', 7.04, 8, '6', NULL, 1, 71022, 37),
(413, 62, '2026-08-10', 50794, 51056, 143.13, NULL, 'C64 Llansa-Proinco-Veracruz', 'Bombero2', NULL, '2026-08-10 18:29:12', NULL, NULL, 143.13, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 143.13, NULL, NULL, 'BLOQUEADO', 6.92, 9, '6', NULL, 1, 71023, 37),
(414, 65, '2026-08-10', 15701, 16016, 153.10, NULL, 'C68 Puerto Sandino-Puerto Corinto', 'Bombero2', NULL, '2026-08-10 18:31:08', NULL, NULL, 153.1, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 153.1, NULL, NULL, 'BLOQUEADO', 7.78, 9, '6', NULL, 1, 71024, 37),
(415, 40, '2026-08-10', 69137, 69370, 113.91, NULL, 'C62 Sebaco', 'Bombero2', NULL, '2026-08-10 19:09:35', NULL, NULL, 113.91, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 113.91, NULL, NULL, 'APROBADO', 7.75, 9, '6', NULL, 1, 71025, 37),
(416, 12, '2026-08-10', 331704, 331819, 61.12, NULL, 'LPG2-097 Managua-San Benito', 'Bombero2', NULL, '2026-08-10 21:38:25', NULL, NULL, 61.12, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 61.12, NULL, NULL, 'BLOQUEADO', 7.12, 15, '6', NULL, 1, 71026, 37),
(417, 64, '2026-08-11', 9150, 9480, 140.24, NULL, 'C67 Chinandega', 'Bombero2', NULL, '2026-08-11 10:46:56', NULL, NULL, 140.24, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 140.24, NULL, NULL, 'APROBADO', 8.91, 9, '6', NULL, 1, 71027, 39),
(418, 84, '2026-08-11', 0, 0, 54.36, NULL, 'Toyota L M435527/Cuota Oscar Briceño', 'Bombero2', NULL, '2026-08-11 10:47:49', NULL, NULL, 54.36, '', '', 'CONSUMO', 54.36, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71028, 39),
(419, 86, '2026-08-11', 0, 0, 39.15, NULL, 'Toyota H M243803/Cuota Oscar Briceño', 'Bombero2', NULL, '2026-08-11 10:48:31', NULL, NULL, 39.15, '', '', 'CONSUMO', 39.15, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71029, 39),
(420, 79, '2026-08-11', 6571, 6946, 147.95, NULL, 'C43 Lesther Espinoza', 'Bombero2', NULL, '2026-08-11 13:05:39', NULL, NULL, 147.95, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 147.95, NULL, NULL, 'BLOQUEADO', 9.59, 8, '6', NULL, 1, 71030, 41),
(421, 47, '2026-08-11', 35200, 35646, 225.52, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-11 13:06:49', NULL, NULL, 225.52, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 225.52, NULL, NULL, 'BLOQUEADO', 7.49, 9, '6', NULL, 1, 71031, 41),
(422, 45, '2026-08-11', 85085, 85384, 141.09, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-11 13:07:47', NULL, NULL, 141.09, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 141.09, NULL, NULL, 'BLOQUEADO', 8.02, 10, '6', NULL, 1, 71032, 41),
(423, 8, '2026-08-11', 660218, 660429, 48.93, NULL, 'JS-03 ', 'Bombero2', NULL, '2026-08-11 13:08:58', NULL, NULL, 48.93, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 48.93, NULL, NULL, 'APROBADO', 16.33, 15, '6', NULL, 1, 71033, 41),
(424, 38, '2026-08-11', 83950, 84257, 131.19, NULL, 'C58 Puerto Sandino', 'Bombero2', NULL, '2026-08-11 14:15:01', NULL, NULL, 131.19, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 131.19, NULL, NULL, 'APROBADO', 8.86, 9, '6', NULL, 1, 71034, 41),
(426, 39, '2026-08-11', 50775, 51644, 380.53, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-11 14:16:30', NULL, NULL, 380.53, '', '', 'CONSUMO', 380.53, NULL, NULL, 'APROBADO', 8.65, 9, '6', NULL, 1, 71036, 39),
(427, 14, '2026-08-11', 26524, 26525, 3.36, NULL, 'JS-01 Relleno de Filtro', 'Bombero2', NULL, '2026-08-11 14:17:16', NULL, NULL, 3.36, '', '', 'CONSUMO', 3.36, NULL, NULL, 'APROBADO', 1.13, 15, '6', NULL, 1, 71037, 39),
(428, 61, '2026-08-11', 1137806, 1138444, 299.40, NULL, 'C61 Esteli', 'Bombero2', NULL, '2026-08-11 16:02:06', NULL, NULL, 299.4, '', '', 'CONSUMO', 299.39, NULL, NULL, 'BLOQUEADO', 8.07, 9, '6', NULL, 1, 71038, 39),
(429, 77, '2026-08-11', 0, 0, 59.07, NULL, 'Isuzu M436353/Cuota Anibal Sevilla', 'Bombero2', NULL, '2026-08-11 16:02:38', NULL, NULL, 59.07, '', '', 'CONSUMO', 59.07, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71039, 39),
(430, 65, '2026-08-11', 16016, 16334, 135.00, NULL, 'C68 Puerto Corinto', 'Bombero2', NULL, '2026-08-11 16:03:38', NULL, NULL, 135, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 135, NULL, NULL, 'APROBADO', 8.93, 9, '6', NULL, 1, 71040, 39),
(431, 63, '2026-08-11', 29728, 29801, 40.16, NULL, 'C66 Locales', 'Bombero2', NULL, '2026-08-11 16:04:27', NULL, NULL, 40.16, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 40.16, NULL, NULL, 'APROBADO', 6.83, 9, '6', NULL, 1, 71041, 39),
(432, 80, '2026-08-11', 0, 0, 37.85, NULL, 'Denis Blas Ampie/F-', 'Bombero2', NULL, '2026-08-11 16:04:54', NULL, NULL, 37.85, '', '', 'CONSUMO', 37.85, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71042, 39),
(433, 5, '2026-08-11', 16372, 16468, 80.02, NULL, 'C14 Miramontes', 'Bombero2', NULL, '2026-08-11 16:12:55', NULL, NULL, 80.02, 'Rolando José Silva Poveda ', '284-260184-0004A', 'CONSUMO', 80.02, NULL, NULL, 'BLOQUEADO', 4.55, 12, '6', NULL, 1, 71043, 39),
(434, 48, '2026-08-11', 93773, 94220, 230.06, NULL, 'C30 Puerto Sandino ', 'Bombero2', NULL, '2026-08-11 17:36:54', NULL, NULL, 230.06, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 230.06, NULL, NULL, 'BLOQUEADO', 7.36, 10, '6', NULL, 1, 71044, 39),
(435, 64, '2026-08-11', 9480, 9580, 40.77, NULL, 'C67 Centroamerica-Guerrero-Rubenia', 'Bombero2', NULL, '2026-08-11 21:00:16', NULL, NULL, 40.77, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 40.77, NULL, NULL, 'APROBADO', 9.28, 9, '6', NULL, 1, 71045, 39),
(436, 42, '2026-08-11', 2129945, 2130244, 140.48, NULL, 'C45 Puerto Sandino', 'Bombero2', NULL, '2026-08-11 21:01:37', NULL, NULL, 140.48, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 140.47, NULL, NULL, 'BLOQUEADO', 8.06, 10, '6', NULL, 1, 71046, 39),
(437, 78, '2026-08-11', 0, 0, 71.51, NULL, '', 'Bombero2', NULL, '2026-08-11 21:15:11', NULL, NULL, 71.51, '', '', 'CONSUMO', 71.51, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71047, 39),
(438, 44, '2026-08-11', 48489, 49168, 266.03, NULL, 'C19 Puerto Sandino', 'Bombero2', NULL, '2026-08-11 21:16:14', NULL, NULL, 266.03, '', '', 'CONSUMO', 266.03, NULL, NULL, 'BLOQUEADO', 9.66, 9, '6', NULL, 1, 71048, 39),
(439, 13, '2026-08-11', 460043, 460177, 52.63, NULL, 'C49 ', 'Bombero2', NULL, '2026-08-11 21:17:23', NULL, NULL, 52.63, '', '', 'CONSUMO', 52.63, NULL, NULL, 'APROBADO', 9.66, 15, '6', NULL, 1, 71049, 39),
(440, 60, '2026-08-11', 38183, 38332, 150.07, NULL, 'C60 Locales', 'Bombero2', NULL, '2026-08-11 21:18:53', NULL, NULL, 150.07, '', '', 'CONSUMO', 150.07, NULL, NULL, 'BLOQUEADO', 3.76, 9, '6', NULL, 1, 71050, 39),
(441, 78, '2026-08-11', 0, 0, 208.62, NULL, '', 'Bombero2', NULL, '2026-08-11 22:36:16', NULL, NULL, 208.62, '', '', 'CONSUMO', 208.62, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71051, 39),
(443, 80, '2026-08-11', 0, 0, 93.11, NULL, '', 'Bombero2', NULL, '2026-08-11 22:38:53', NULL, NULL, 93.11, '', '', 'CONSUMO', 93.11, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71053, 40),
(444, 12, '2026-08-11', 331819, 332200, 126.48, NULL, 'LPG2-097 Chinandega-San Benito', 'Bombero2', NULL, '2026-08-11 22:43:44', NULL, NULL, 126.48, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 126.48, NULL, NULL, 'BLOQUEADO', 11.4, 15, '6', NULL, 1, 71054, 40);
INSERT INTO `registro_combustible` (`id`, `id_vehiculo`, `fecha_registro`, `kilometraje_anterior`, `kilometraje_actual`, `cantidad_litros`, `id_motivo`, `observaciones`, `usuario_crea`, `usuario_edita`, `fecha_actualiza`, `id_tipo_motivo`, `id_direccion`, `medicion`, `nombreCliente`, `dni`, `tipo`, `combustible_tanque`, `monto_nio`, `monto_usd`, `estado`, `rendimiento`, `rendimiento_promedio`, `referencia1`, `referencia2`, `enviado`, `numero_ingreso_sag`, `id_lectura`) VALUES
(445, 43, '2026-08-11', 72509, 73108, 296.00, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-11 22:47:35', NULL, NULL, 296, '', '', 'CONSUMO', 296, NULL, NULL, 'BLOQUEADO', 7.66, 10, '6', NULL, 1, 71055, 40),
(446, 80, '2026-08-12', 0, 0, 37.85, NULL, '', 'Bombero2', NULL, '2026-08-12 09:42:51', NULL, NULL, 37.85, '', '', 'CONSUMO', 37.85, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71056, 43),
(447, 75, '2026-08-12', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-12 09:43:18', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71057, 43),
(448, 38, '2026-08-12', 84257, 84564, 136.56, NULL, 'C58 Puerto Sandino', 'Bombero2', NULL, '2026-08-12 09:45:32', NULL, NULL, 136.56, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 136.56, NULL, NULL, 'APROBADO', 8.51, 9, '6', NULL, 1, 71058, 43),
(449, 75, '2026-08-12', 0, 0, 9.86, NULL, '', 'Bombero2', NULL, '2026-08-12 15:59:24', NULL, NULL, 9.86, '', '', 'CONSUMO', 9.86, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71059, 43),
(450, 75, '2026-08-12', 0, 0, 5.82, NULL, '', 'Bombero2', NULL, '2026-08-12 16:01:07', NULL, NULL, 5.82, '', '', 'CONSUMO', 5.82, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71060, 43),
(451, 9, '2026-08-12', 15547, 15974, 115.15, NULL, 'C42 Mina la India', 'Bombero2', NULL, '2026-08-12 16:01:42', NULL, NULL, 115.15, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 115.15, NULL, NULL, 'APROBADO', 14.04, 15, '6', NULL, 1, 71061, 43),
(452, 75, '2026-08-12', 0, 0, 17.98, NULL, '', 'Bombero2', NULL, '2026-08-12 16:02:06', NULL, NULL, 17.98, '', '', 'CONSUMO', 17.98, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71062, 43),
(453, 80, '2026-08-12', 0, 0, 195.70, NULL, '', 'Bombero2', NULL, '2026-08-12 16:02:28', NULL, NULL, 195.7, '', '', 'CONSUMO', 195.7, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71063, 43),
(454, 44, '2026-08-12', 49168, 49169, 3.79, NULL, 'C19 Relleno de Filtro', 'Bombero2', NULL, '2026-08-12 16:02:57', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 9, '6', NULL, 1, 71064, 43),
(455, 60, '2026-08-12', 38332, 38366, 28.29, NULL, 'C60 Locales', 'Bombero2', NULL, '2026-08-12 16:03:52', NULL, NULL, 28.29, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 28.29, NULL, NULL, 'APROBADO', 4.6, 9, '6', NULL, 1, 71065, 43),
(456, 71, '2026-08-12', 0, 0, 50.90, NULL, 'Kia M353553/Gestiones Compras', 'Bombero2', NULL, '2026-08-12 16:04:24', NULL, NULL, 50.9, '', '', 'CONSUMO', 50.9, NULL, NULL, 'APROBADO', 0, 45, '5', NULL, 1, 71066, 43),
(457, 8, '2026-08-12', 660429, 660775, 57.09, NULL, 'JS-03 Puerto Corinto', 'Bombero2', NULL, '2026-08-12 16:27:43', NULL, NULL, 57.09, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 57.09, NULL, NULL, 'BLOQUEADO', 22.91, 15, '6', NULL, 1, 71067, 43),
(458, 75, '2026-08-12', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-12 16:28:09', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71068, 43),
(459, 61, '2026-08-12', 1138444, 1139009, 246.45, NULL, 'C61 Sebaco-Leon', 'Bombero2', NULL, '2026-08-12 16:29:04', NULL, NULL, 246.45, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 246.45, NULL, NULL, 'APROBADO', 8.68, 9, '6', NULL, 1, 71069, 43),
(460, 9, '2026-08-12', 15974, 16083, 31.99, NULL, 'C42 Reyna del Sur', 'Bombero2', NULL, '2026-08-12 16:29:51', NULL, NULL, 31.99, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 31.99, NULL, NULL, 'APROBADO', 12.9, 15, '6', NULL, 1, 71070, 43),
(461, 76, '2026-08-12', 0, 0, 103.74, NULL, 'RAM 2500 M330810/Cuota Anibal Sevilla', 'Bombero2', NULL, '2026-08-12 16:30:37', NULL, NULL, 103.74, '', '', 'CONSUMO', 103.74, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71071, 43),
(462, 65, '2026-08-12', 16334, 16670, 151.97, NULL, 'C68 Hanio Vargas', 'Bombero2', NULL, '2026-08-12 16:31:28', NULL, NULL, 151.97, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 151.97, NULL, NULL, 'APROBADO', 8.37, 9, '6', NULL, 1, 71072, 43),
(463, 4, '2026-08-12', 40372, 40636, 117.03, NULL, 'C12 Pavinic-Prodecon', 'Bombero2', NULL, '2026-08-12 16:32:36', NULL, NULL, 117.03, 'Jorge Gregorio Ney Almendárez ', '001-011179-0010X', 'CONSUMO', 117.03, NULL, NULL, 'APROBADO', 8.55, 10, '6', NULL, 1, 71073, 43),
(464, 72, '2026-08-12', 0, 0, 48.16, NULL, 'Kia 3000 M422167/Gestiones Operaciones', 'Bombero2', NULL, '2026-08-12 16:33:19', NULL, NULL, 48.16, '', '', 'CONSUMO', 48.16, NULL, NULL, 'APROBADO', 0, 45, '5', NULL, 1, 71074, 43),
(465, 79, '2026-08-12', 6946, 7252, 124.69, NULL, 'C43 Chinandega-Altagracia-Batahola', 'Bombero2', NULL, '2026-08-12 16:50:13', NULL, NULL, 124.69, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 124.69, NULL, NULL, 'BLOQUEADO', 9.3, 8, '6', NULL, 1, 71075, 43),
(466, 59, '2026-08-12', 1361697, 1362050, 210.40, NULL, 'C59 Esteli-Locales', 'Bombero2', NULL, '2026-08-12 22:18:40', NULL, NULL, 210.4, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 210.4, NULL, NULL, 'BLOQUEADO', 6.35, 9, '6', NULL, 1, 71076, 43),
(467, 64, '2026-08-12', 9580, 9860, 119.58, NULL, 'C67 Chacaraseca-Proinco-Veracruz', 'Bombero2', NULL, '2026-08-12 22:19:38', NULL, NULL, 119.58, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 119.58, NULL, NULL, 'APROBADO', 8.86, 9, '6', NULL, 1, 71077, 43),
(468, 63, '2026-08-12', 29801, 30301, 174.40, NULL, 'C66 Wiwili', 'Bombero2', NULL, '2026-08-12 22:20:58', NULL, NULL, 174.4, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 174.4, NULL, NULL, 'BLOQUEADO', 10.85, 9, '6', NULL, 1, 71078, 43),
(469, 42, '2026-08-12', 2130244, 2130692, 228.64, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-12 22:21:47', NULL, NULL, 228.64, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 228.64, NULL, NULL, 'BLOQUEADO', 7.42, 10, '6', NULL, 1, 71079, 43),
(470, 88, '2026-08-12', 2249, 2528, 206.95, NULL, 'C69 Locales-Masaya', 'Bombero2', NULL, '2026-08-12 22:23:01', NULL, NULL, 206.95, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 206.95, NULL, NULL, 'BLOQUEADO', 5.11, 9, '6', NULL, 1, 71080, 43),
(471, 15, '2026-08-12', 18452, 18930, 102.08, NULL, 'El Ayote', 'Bombero2', NULL, '2026-08-12 22:24:02', NULL, NULL, 102.08, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 102.08, NULL, NULL, 'BLOQUEADO', 17.73, 13, '6', NULL, 1, 71081, 43),
(472, 54, '2026-08-12', 40149, 40508, 208.38, NULL, 'C45 Leon-Competra-Jinotepe', 'Bombero2', NULL, '2026-08-12 22:24:57', NULL, NULL, 208.38, 'Sergio Antonio Manzanarez López ', '001-170685-0016Y', 'CONSUMO', 208.38, NULL, NULL, 'BLOQUEADO', 6.51, 8, '6', NULL, 1, 71082, 43),
(473, 11, '2026-08-12', 298366, 298592, 60.27, NULL, 'LPG1-096 Managua', 'Bombero2', NULL, '2026-08-12 22:26:03', NULL, NULL, 60.27, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 60.27, NULL, NULL, 'BLOQUEADO', 25.23, 15, '6', NULL, 1, 71083, 43),
(474, 62, '2026-08-12', 51056, 51509, 158.45, NULL, 'C64 Chinandega-Colonia Sur-Llansa', 'Bombero2', NULL, '2026-08-12 22:27:08', NULL, NULL, 158.45, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 158.44, NULL, NULL, 'BLOQUEADO', 10.82, 9, '6', NULL, 1, 71084, 43),
(475, 80, '2026-08-12', 0, 0, 166.42, NULL, '', 'Bombero2', NULL, '2026-08-12 22:27:32', NULL, NULL, 166.42, '', '', 'CONSUMO', 166.41, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71085, 43),
(476, 80, '2026-08-12', 0, 0, 97.07, NULL, '', 'Bombero2', NULL, '2026-08-12 22:27:54', NULL, NULL, 97.07, '', '', 'CONSUMO', 97.07, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71086, 43),
(477, 80, '2026-08-12', 0, 0, 111.90, NULL, '', 'Bombero2', NULL, '2026-08-12 22:28:21', NULL, NULL, 111.9, '', '', 'CONSUMO', 111.9, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71087, 42),
(478, 13, '2026-08-12', 460177, 460503, 100.01, NULL, 'C49 Chinandega', 'Bombero2', NULL, '2026-08-12 22:29:18', NULL, NULL, 100.01, '', '', 'CONSUMO', 100.01, NULL, NULL, 'APROBADO', 12.34, 15, '6', NULL, 1, 71088, 42),
(479, 12, '2026-08-12', 332200, 332360, 81.33, NULL, 'LPG2-097 Managua-San Benito', 'Bombero2', NULL, '2026-08-12 22:49:56', NULL, NULL, 81.33, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 81.33, NULL, NULL, 'BLOQUEADO', 7.42, 15, '6', NULL, 1, 71089, 42),
(481, 47, '2026-08-12', 35646, 35944, 166.84, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-12 23:46:46', NULL, NULL, 166.84, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 166.84, NULL, NULL, 'BLOQUEADO', 6.76, 9, '6', NULL, 1, 71091, 42),
(482, 38, '2026-08-13', 84564, 84871, 133.15, NULL, 'C58 Puerto Corinto', 'Bombero2', NULL, '2026-08-13 15:44:45', NULL, NULL, 133.15, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 133.15, NULL, NULL, 'APROBADO', 8.73, 9, '6', NULL, 1, 71092, 44),
(483, 39, '2026-08-13', 51644, 52657, 477.24, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-13 15:46:52', NULL, NULL, 477.24, 'Alberto Enrique Rodríguez Núñez ', '281-151182-0015M', 'CONSUMO', 477.24, NULL, NULL, 'BLOQUEADO', 8.04, 9, '6', NULL, 1, 71093, 44),
(484, 9, '2026-08-13', 16083, 16276, 56.04, NULL, 'C42 Miramontes', 'Bombero2', NULL, '2026-08-13 15:47:46', NULL, NULL, 56.04, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 56.04, NULL, NULL, 'APROBADO', 13, 15, '6', NULL, 1, 71094, 46),
(485, 81, '2026-08-13', 8, 9, 18.93, NULL, 'Hidrolavadora', 'Bombero2', NULL, '2026-08-13 15:49:10', NULL, NULL, 18.93, '', '', 'CONSUMO', 18.93, NULL, NULL, 'APROBADO', 0.2, 0, '6', NULL, 1, 71095, 46),
(486, 60, '2026-08-13', 38366, 38552, 98.29, NULL, 'C60 Lesther Espinoza', 'Bombero2', NULL, '2026-08-13 15:50:22', NULL, NULL, 98.29, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 98.29, NULL, NULL, 'BLOQUEADO', 7.15, 9, '6', NULL, 1, 71096, 46),
(487, 18, '2026-08-13', 96645, 97484, 333.87, NULL, 'C11 Puerto Sandino-Puerto Corinto', 'Bombero2', NULL, '2026-08-13 15:54:17', NULL, NULL, 333.87, 'Rolando José Silva Poveda ', '284-260184-0004A', 'CONSUMO', 333.87, NULL, NULL, 'APROBADO', 9.51, 10, '6', NULL, 1, 71097, 46),
(488, 40, '2026-08-13', 69370, 69809, 219.77, NULL, 'C62 Diriamba-Locales', 'Bombero2', NULL, '2026-08-13 15:55:28', NULL, NULL, 219.77, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 219.77, NULL, NULL, 'BLOQUEADO', 7.56, 9, '6', NULL, 1, 71098, 44),
(489, 48, '2026-08-13', 94220, 94820, 298.53, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-13 15:56:27', NULL, NULL, 298.53, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 298.53, NULL, NULL, 'BLOQUEADO', 7.61, 10, '6', NULL, 1, 71099, 44),
(490, 6, '2026-08-13', 46869, 47174, 76.59, NULL, 'C38 Serfumsa-Douglas Barrera', 'Bombero2', NULL, '2026-08-13 15:57:32', NULL, NULL, 76.59, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 76.59, NULL, NULL, 'APROBADO', 15.08, 15, '6', NULL, 1, 71100, 44),
(491, 84, '2026-08-13', 0, 0, 11.36, NULL, 'Toyota L M435527/Mandados GCM', 'Bombero2', NULL, '2026-08-13 20:09:11', NULL, NULL, 11.36, '', '', 'CONSUMO', 11.36, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71101, 44),
(492, 13, '2026-08-13', 460503, 460568, 32.94, NULL, 'C49 Veracruz', 'Bombero2', NULL, '2026-08-13 20:10:03', NULL, NULL, 32.94, '', '', 'CONSUMO', 32.94, NULL, NULL, 'APROBADO', 7.48, 15, '6', NULL, 1, 71102, 44),
(493, 8, '2026-08-13', 660775, 660963, 51.58, NULL, 'JS-03 Rivas', 'Bombero2', NULL, '2026-08-13 20:10:53', NULL, NULL, 51.58, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 51.58, NULL, NULL, 'APROBADO', 13.79, 15, '6', NULL, 1, 71103, 44),
(494, 62, '2026-08-13', 51509, 51841, 102.86, NULL, 'C64 Juigalpa', 'Bombero2', NULL, '2026-08-13 20:12:25', NULL, NULL, 102.86, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 102.86, NULL, NULL, 'BLOQUEADO', 12.2, 9, '6', NULL, 1, 71104, 44),
(495, 73, '2026-08-13', 0, 0, 45.30, NULL, 'Mitsubishi M372333/Supervision ', 'Bombero2', NULL, '2026-08-13 20:13:45', NULL, NULL, 45.3, '', '', 'CONSUMO', 45.3, NULL, NULL, 'APROBADO', 0, 40, '5', NULL, 1, 71105, 44),
(496, 75, '2026-08-13', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-13 20:14:02', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71106, 44),
(497, 80, '2026-08-13', 0, 0, 64.01, NULL, '', 'Bombero2', NULL, '2026-08-13 20:14:22', NULL, NULL, 64.01, '', '', 'CONSUMO', 64.01, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71107, 44),
(498, 42, '2026-08-13', 2130692, 2130991, 139.37, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-13 20:15:17', NULL, NULL, 139.37, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 139.37, NULL, NULL, 'BLOQUEADO', 8.12, 10, '6', NULL, 1, 71108, 44),
(499, 80, '2026-08-13', 0, 0, 134.56, NULL, '', 'Bombero2', NULL, '2026-08-13 20:15:43', NULL, NULL, 134.56, '', '', 'CONSUMO', 134.56, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71109, 44),
(500, 41, '2026-08-13', 177647, 178334, 371.52, NULL, 'C20 El Rama', 'Bombero2', NULL, '2026-08-13 20:17:23', NULL, NULL, 371.52, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 371.52, NULL, NULL, 'BLOQUEADO', 7, 9, '6', NULL, 1, 71110, 44),
(501, 63, '2026-08-13', 30301, 30445, 72.42, NULL, 'C66 Locales-Transporte Diaz', 'Bombero2', NULL, '2026-08-13 20:18:59', NULL, NULL, 72.42, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 72.42, NULL, NULL, 'APROBADO', 7.51, 9, '6', NULL, 1, 71111, 44),
(502, 64, '2026-08-13', 9860, 10005, 65.50, NULL, 'C67 Proinco', 'Bombero2', NULL, '2026-08-13 20:20:03', NULL, NULL, 65.5, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 65.5, NULL, NULL, 'APROBADO', 8.38, 9, '6', NULL, 1, 71112, 44),
(503, 47, '2026-08-13', 35944, 36391, 220.36, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-13 20:21:00', NULL, NULL, 220.36, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 220.35, NULL, NULL, 'BLOQUEADO', 7.69, 9, '6', NULL, 1, 71113, 44),
(504, 12, '2026-08-13', 332360, 332438, 51.03, NULL, 'LPG2-097 Managua-Cofradia', 'Bombero2', NULL, '2026-08-13 20:47:01', NULL, NULL, 51.03, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 51.03, NULL, NULL, 'BLOQUEADO', 5.76, 15, '6', NULL, 1, 71114, 44),
(505, 79, '2026-08-13', 7252, 7614, 159.31, NULL, 'C43 Competra-Ingenio San Antonio', 'Bombero2', NULL, '2026-08-13 21:38:45', NULL, NULL, 159.31, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 159.31, NULL, NULL, 'APROBADO', 8.6, 8, '6', NULL, 1, 71115, 44),
(506, 59, '2026-08-14', 1362050, 1362232, 72.96, NULL, 'C59 Leon-Local', 'Bombero2', NULL, '2026-08-14 08:48:14', NULL, NULL, 72.96, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 72.95, NULL, NULL, 'APROBADO', 9.45, 9, '6', NULL, 1, 71116, 47),
(507, 43, '2026-08-14', 73108, 73857, 382.73, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-14 10:52:38', NULL, NULL, 382.73, 'Moisés Isaac Gutiérrez Valerio ', '002-241193-0000D', 'CONSUMO', 382.73, NULL, NULL, 'BLOQUEADO', 7.41, 10, '6', NULL, 1, 71117, 47),
(508, 75, '2026-08-14', 0, 0, 7.07, NULL, '', 'Bombero2', NULL, '2026-08-14 10:54:22', NULL, NULL, 7.07, '', '', 'CONSUMO', 7.07, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71118, 47),
(509, 75, '2026-08-14', 0, 0, 20.00, NULL, '', 'Bombero2', NULL, '2026-08-14 10:58:07', NULL, NULL, 20, '', '', 'CONSUMO', 20, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71119, 47),
(510, 38, '2026-08-14', 84871, 85178, 131.89, NULL, 'C58 Puerto Corinto', 'Bombero2', NULL, '2026-08-14 13:06:18', NULL, NULL, 131.89, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 131.89, NULL, NULL, 'APROBADO', 8.81, 9, '6', NULL, 1, 71120, 47),
(511, 54, '2026-08-14', 40508, 41025, 293.55, NULL, 'C45 Puerto Sandino', 'Bombero2', NULL, '2026-08-14 13:07:35', NULL, NULL, 293.55, 'Sergio Antonio Manzanarez López ', '001-170685-0016Y', 'CONSUMO', 293.55, NULL, NULL, 'BLOQUEADO', 6.67, 8, '6', NULL, 1, 71121, 47),
(512, 13, '2026-08-14', 460568, 460634, 24.87, NULL, 'C49 Proinco', 'Bombero2', NULL, '2026-08-14 13:09:59', NULL, NULL, 24.87, '', '', 'CONSUMO', 24.87, NULL, NULL, 'APROBADO', 10.03, 15, '6', NULL, 1, 71122, 47),
(513, 41, '2026-08-14', 178334, 178372, 20.43, NULL, 'C20 Locales', 'Bombero2', NULL, '2026-08-14 13:46:00', NULL, NULL, 20.43, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 20.43, NULL, NULL, 'APROBADO', 7.1, 9, '6', NULL, 1, 71123, 47),
(514, 61, '2026-08-14', 1139009, 1139324, 192.65, NULL, 'C61 Chinandega-Las Mercedes', 'Bombero2', NULL, '2026-08-14 13:47:13', NULL, NULL, 192.65, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 192.65, NULL, NULL, 'BLOQUEADO', 6.2, 9, '6', NULL, 1, 71124, 47),
(515, 75, '2026-08-14', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-14 22:26:42', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71125, 47),
(516, 64, '2026-08-14', 10005, 10470, 197.56, NULL, 'C67 El Ayote', 'Bombero2', NULL, '2026-08-14 22:28:00', NULL, NULL, 197.56, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 197.56, NULL, NULL, 'APROBADO', 8.91, 9, '6', NULL, 1, 71126, 47),
(517, 63, '2026-08-14', 30445, 30696, 70.89, NULL, 'C66 Chacaraseca-Al Azar', 'Bombero2', NULL, '2026-08-14 22:29:41', NULL, NULL, 70.89, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 70.89, NULL, NULL, 'BLOQUEADO', 13.4, 9, '6', NULL, 1, 71127, 47),
(518, 5, '2026-08-14', 16468, 16622, 70.37, NULL, 'C14 Puma', 'Bombero2', NULL, '2026-08-14 22:30:53', NULL, NULL, 70.37, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 70.37, NULL, NULL, 'BLOQUEADO', 8.29, 12, '6', NULL, 1, 71128, 47),
(519, 12, '2026-08-14', 332438, 332746, 89.84, NULL, 'LPG2-097 Chinandega', 'Bombero2', NULL, '2026-08-14 22:32:06', NULL, NULL, 89.84, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 89.84, NULL, NULL, 'APROBADO', 12.97, 15, '6', NULL, 1, 71129, 47),
(520, 80, '2026-08-14', 0, 0, 127.05, NULL, '', 'Bombero2', NULL, '2026-08-14 22:32:42', NULL, NULL, 127.05, '', '', 'CONSUMO', 127.05, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71130, 47),
(521, 68, '2026-08-14', 0, 0, 60.67, NULL, 'Toyota H M110142/Rescate', 'Bombero2', NULL, '2026-08-14 22:33:26', NULL, NULL, 60.67, '', '', 'CONSUMO', 60.67, NULL, NULL, 'APROBADO', 0, 50, '5', NULL, 1, 71131, 47),
(522, 39, '2026-08-14', 52657, 53376, 334.29, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-14 22:34:23', NULL, NULL, 334.29, 'Ulises Joel Bautista Gómez ', '001-140100-1011D', 'CONSUMO', 334.29, NULL, NULL, 'BLOQUEADO', 8.14, 9, '6', NULL, 1, 71132, 47),
(523, 85, '2026-08-14', 0, 0, 57.06, NULL, 'Isuzu M401276/Cuota Roger Vilchez', 'Bombero2', NULL, '2026-08-14 22:35:10', NULL, NULL, 57.06, '', '', 'CONSUMO', 57.06, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71133, 47),
(524, 47, '2026-08-14', 36391, 36840, 219.01, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-14 22:35:56', NULL, NULL, 219.01, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 219, NULL, NULL, 'BLOQUEADO', 7.75, 9, '6', NULL, 1, 71134, 47),
(525, 42, '2026-08-14', 2130991, 2131440, 220.30, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-14 22:36:53', NULL, NULL, 220.3, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 220.3, NULL, NULL, 'BLOQUEADO', 7.71, 10, '6', NULL, 1, 71135, 47),
(526, 45, '2026-08-14', 85384, 86133, 369.98, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-14 22:38:09', NULL, NULL, 369.98, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 369.98, NULL, NULL, 'BLOQUEADO', 7.67, 10, '6', NULL, 1, 71136, 47),
(527, 55, '2026-08-14', 18014, 18353, 246.90, NULL, 'C46 San Benito-Managua-Nandaime', 'Bombero2', NULL, '2026-08-14 22:39:15', NULL, NULL, 246.9, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 246.9, NULL, NULL, 'BLOQUEADO', 5.2, 8, '6', NULL, 1, 71137, 47),
(528, 79, '2026-08-14', 7614, 7810, 87.63, NULL, 'C43 Leon-Local', 'Bombero2', NULL, '2026-08-14 22:39:57', NULL, NULL, 87.63, '', '', 'CONSUMO', 87.63, NULL, NULL, 'APROBADO', 8.45, 8, '6', NULL, 1, 71138, 47),
(529, 80, '2026-08-14', 0, 0, 153.69, NULL, '', 'Bombero2', NULL, '2026-08-14 22:40:22', NULL, NULL, 153.69, '', '', 'CONSUMO', 153.69, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71139, 47),
(530, 40, '2026-08-15', 69809, 70111, 157.66, NULL, 'C62 Sebaco', 'Bombero2', NULL, '2026-08-15 22:38:49', NULL, NULL, 157.66, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 157.66, NULL, NULL, 'BLOQUEADO', 7.25, 9, '6', NULL, 1, 71140, 49),
(531, 62, '2026-08-15', 51841, 51965, 52.33, NULL, 'C64 Managua', 'Bombero2', NULL, '2026-08-15 22:39:45', NULL, NULL, 52.33, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 52.33, NULL, NULL, 'APROBADO', 8.96, 9, '6', NULL, 1, 71141, 49),
(532, 43, '2026-08-15', 73857, 73858, 11.36, NULL, 'C18 Relleno de Filtro', 'Bombero2', NULL, '2026-08-15 22:40:34', NULL, NULL, 11.36, '', '', 'CONSUMO', 11.36, NULL, NULL, 'APROBADO', 0.33, 10, '6', NULL, 1, 71142, 49),
(533, 82, '2026-08-15', 0, 0, 736.38, NULL, '', 'Bombero2', NULL, '2026-08-15 22:40:53', NULL, NULL, 736.38, '', '', 'CONSUMO', 736.38, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71143, 49),
(534, 64, '2026-08-15', 10470, 10540, 32.61, NULL, 'C67 Pavinic', 'Bombero2', NULL, '2026-08-15 22:41:33', NULL, NULL, 32.61, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 32.6, NULL, NULL, 'APROBADO', 8.13, 9, '6', NULL, 1, 71144, 49),
(535, 82, '2026-08-15', 0, 0, 635.88, NULL, '', 'Bombero2', NULL, '2026-08-15 22:51:39', NULL, NULL, 635.88, '', '', 'CONSUMO', 635.88, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71145, 49),
(536, 75, '2026-08-15', 0, 0, 945.67, NULL, '', 'Bombero2', NULL, '2026-08-15 22:52:11', NULL, NULL, 945.67, '', '', 'CONSUMO', 945.67, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71146, 49),
(537, 9, '2026-08-15', 16276, 16316, 22.74, NULL, 'C42 TCF', 'Bombero2', NULL, '2026-08-15 22:53:00', NULL, NULL, 22.74, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 22.74, NULL, NULL, 'APROBADO', 6.69, 15, '6', NULL, 1, 71147, 49),
(538, 43, '2026-08-15', 73858, 74156, 141.36, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-15 22:54:03', NULL, NULL, 141.36, 'Moisés Isaac Gutiérrez Valerio ', '002-241193-0000D', 'CONSUMO', 141.36, NULL, NULL, 'BLOQUEADO', 7.99, 10, '6', NULL, 1, 71148, 49),
(539, 60, '2026-08-15', 38552, 38673, 96.61, NULL, 'C60 Locales-Diriamba', 'Bombero2', NULL, '2026-08-15 22:57:07', NULL, NULL, 96.61, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 96.61, NULL, NULL, 'BLOQUEADO', 4.73, 9, '6', NULL, 1, 71149, 49),
(540, 47, '2026-08-15', 36840, 37139, 139.96, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-15 22:58:05', NULL, NULL, 139.96, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 139.96, NULL, NULL, 'APROBADO', 8.08, 9, '6', NULL, 1, 71150, 49),
(541, 12, '2026-08-15', 332746, 332847, 50.90, NULL, 'LPG2-097 San Benito', 'Bombero2', NULL, '2026-08-15 22:59:12', NULL, NULL, 50.9, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 50.9, NULL, NULL, 'BLOQUEADO', 7.49, 15, '6', NULL, 1, 71151, 49),
(542, 65, '2026-08-15', 16670, 16976, 146.18, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-08-15 23:00:01', NULL, NULL, 146.18, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 146.18, NULL, NULL, 'APROBADO', 7.91, 9, '6', NULL, 1, 71152, 49),
(543, 42, '2026-08-15', 2131440, 2131739, 144.23, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-15 23:00:53', NULL, NULL, 144.23, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 144.23, NULL, NULL, 'BLOQUEADO', 7.84, 10, '6', NULL, 1, 71153, 49),
(544, 80, '2026-08-15', 0, 0, 20.34, NULL, '', 'Bombero2', NULL, '2026-08-15 23:01:17', NULL, NULL, 20.34, '', '', 'CONSUMO', 20.34, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71154, 49),
(545, 5, '2026-08-15', 16622, 16635, 34.19, NULL, 'C14 Proinco', 'Bombero2', NULL, '2026-08-15 23:02:17', NULL, NULL, 34.19, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 34.19, NULL, NULL, 'BLOQUEADO', 1.45, 12, '6', NULL, 1, 71155, 49),
(546, 48, '2026-08-15', 94820, 95419, 303.06, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-15 23:05:05', NULL, NULL, 303.06, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 303.06, NULL, NULL, 'BLOQUEADO', 7.48, 10, '6', NULL, 1, 71156, 49),
(547, 59, '2026-08-15', 1362232, 1362460, 125.89, NULL, 'C59 Locales-Leon', 'Bombero2', NULL, '2026-08-15 23:05:55', NULL, NULL, 125.89, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 125.89, NULL, NULL, 'BLOQUEADO', 6.86, 9, '6', NULL, 1, 71157, 49),
(548, 44, '2026-08-15', 49169, 49282, 148.65, NULL, '', 'Bombero2', NULL, '2026-08-15 23:08:11', NULL, NULL, 148.65, 'Kener José Martínez Zuniga ', '047-110589-0000Q', 'CONSUMO', 148.65, NULL, NULL, 'BLOQUEADO', 2.87, 9, '6', NULL, 1, 71158, 49),
(549, 13, '2026-08-15', 460634, 460825, 65.15, NULL, 'C49 Miramontes', 'Bombero2', NULL, '2026-08-15 23:08:56', NULL, NULL, 65.15, '', '', 'CONSUMO', 65.14, NULL, NULL, 'APROBADO', 11.12, 15, '6', NULL, 1, 71159, 49),
(550, 41, '2026-08-15', 178372, 178628, 90.77, NULL, 'C20 Leon-Chinandega', 'Bombero2', NULL, '2026-08-15 23:10:29', NULL, NULL, 90.77, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 90.77, NULL, NULL, 'APROBADO', 10.66, 9, '6', NULL, 1, 71160, 49),
(551, 63, '2026-08-15', 30696, 31018, 104.91, NULL, 'C66 Grupo ZP-Nagarote', 'Bombero2', NULL, '2026-08-15 23:11:33', NULL, NULL, 104.91, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 104.91, NULL, NULL, 'BLOQUEADO', 11.63, 9, '6', NULL, 1, 71161, 49),
(552, 78, '2026-08-15', 0, 0, 65.21, NULL, '', 'Bombero2', NULL, '2026-08-15 23:11:54', NULL, NULL, 65.21, '', '', 'CONSUMO', 65.2, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71162, 49),
(553, 55, '2026-08-15', 18353, 18709, 226.17, NULL, 'C46 La Libertad', 'Bombero2', NULL, '2026-08-15 23:12:38', NULL, NULL, 226.17, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 226.17, NULL, NULL, 'BLOQUEADO', 5.95, 8, '6', NULL, 1, 71163, 49),
(554, 80, '2026-08-15', 0, 0, 145.92, NULL, '', 'Bombero2', NULL, '2026-08-15 23:12:57', NULL, NULL, 145.92, '', '', 'CONSUMO', 145.92, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71164, 49),
(555, 61, '2026-08-15', 1139324, 1139986, 384.51, NULL, 'C61 Chinandega-Las Mercedes', 'Bombero2', NULL, '2026-08-15 23:14:24', NULL, NULL, 384.51, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 384.51, NULL, NULL, 'BLOQUEADO', 6.52, 9, '6', NULL, 1, 71165, 49),
(556, 79, '2026-08-15', 7810, 8123, 160.14, NULL, 'C43 Competra-Sebaco', 'Bombero2', NULL, '2026-08-15 23:15:16', NULL, NULL, 160.14, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 160.14, NULL, NULL, 'APROBADO', 7.4, 8, '6', NULL, 1, 71166, 49),
(557, 88, '2026-08-17', 2528, 2876, 253.64, NULL, 'C69 Jinotepe, Masaya, Gueguense, Altagracia, Linda Vista,Rubenia, Santo Domingo, Los Brasiles', 'Bombero2', NULL, '2026-08-17 09:59:45', NULL, NULL, 253.64, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 253.64, NULL, NULL, 'BLOQUEADO', 5.2, 9, '6', NULL, 1, 71167, 51),
(558, 46, '2026-08-17', 242934, 243112, 171.83, NULL, 'C26 La Reynaga', 'Bombero2', NULL, '2026-08-17 10:03:31', NULL, NULL, 171.83, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 171.83, NULL, NULL, 'BLOQUEADO', 3.92, 10, '6', NULL, 1, 71168, 51),
(559, 62, '2026-08-17', 51965, 52047, 36.24, NULL, 'C64 Pavinic', 'Bombero2', NULL, '2026-08-17 10:04:51', NULL, NULL, 36.24, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 36.24, NULL, NULL, 'APROBADO', 8.53, 9, '6', NULL, 1, 71169, 51),
(560, 82, '2026-08-17', 0, 0, 60.86, NULL, '', 'Bombero2', NULL, '2026-08-17 10:05:29', NULL, NULL, 60.86, '', '', 'CONSUMO', 60.86, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71170, 51),
(561, 75, '2026-08-17', 0, 0, 30.00, NULL, '', 'Bombero2', NULL, '2026-08-17 10:05:52', NULL, NULL, 30, '', '', 'CONSUMO', 30, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71171, 51),
(562, 60, '2026-08-17', 38673, 38707, 35.90, NULL, 'C60 Loyola-7 Sur', 'Bombero2', NULL, '2026-08-17 10:07:17', NULL, NULL, 35.9, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 35.9, NULL, NULL, 'BLOQUEADO', 3.59, 9, '6', NULL, 1, 71172, 51),
(563, 51, '2026-08-17', 97408, 98147, 390.79, NULL, 'C37 Puerto Corinto', 'Bombero2', NULL, '2026-08-17 10:36:50', NULL, NULL, 390.79, 'Julio José Mendoza Ruíz ', '001-080300-1009B', 'CONSUMO', 390.79, NULL, NULL, 'BLOQUEADO', 7.16, 10, '6', NULL, 1, 71173, 51),
(564, 38, '2026-08-17', 85178, 85166, 3.79, NULL, 'C58 Relleno de Filtro', 'Bombero2', NULL, '2026-08-17 10:37:30', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 9, '6', NULL, 1, 71174, 51),
(565, 87, '2026-08-17', 0, 0, 29.30, NULL, 'Toyota F/Cuota Oscar Briceño', 'Bombero2', NULL, '2026-08-17 11:15:53', NULL, NULL, 29.3, '', '', 'CONSUMO', 29.3, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71175, 52),
(566, 65, '2026-08-17', 16976, 17312, 154.73, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-08-17 11:25:46', NULL, NULL, 154.73, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 154.72, NULL, NULL, 'APROBADO', 8.22, 9, '6', NULL, 1, 71176, 52),
(567, 75, '2026-08-17', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-17 13:50:01', NULL, NULL, 264.2, '', '', 'CONSUMO', 264.2, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71177, 52),
(568, 45, '2026-08-17', 86133, 86582, 214.54, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-17 13:51:25', NULL, NULL, 214.54, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 214.54, NULL, NULL, 'BLOQUEADO', 7.93, 10, '6', NULL, 1, 71178, 52),
(569, 38, '2026-08-17', 85166, 85485, 143.02, NULL, 'C58 Puerto Sandino', 'Bombero2', NULL, '2026-08-17 13:52:38', NULL, NULL, 143.02, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 143.02, NULL, NULL, 'APROBADO', 8.09, 9, '6', NULL, 1, 71179, 52),
(570, 39, '2026-08-17', 53376, 53961, 286.92, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-17 13:53:48', NULL, NULL, 286.92, 'Ulises Joel Bautista Gómez ', '001-140100-1011D', 'CONSUMO', 286.92, NULL, NULL, 'BLOQUEADO', 7.72, 9, '6', NULL, 1, 71180, 52),
(571, 63, '2026-08-17', 31198, 31862, 195.69, NULL, 'C66 Bonanza', 'Bombero2', NULL, '2026-08-17 14:03:36', NULL, NULL, 195.69, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 195.69, NULL, NULL, 'BLOQUEADO', 12.85, 9, '6', NULL, 1, 71181, 52),
(572, 43, '2026-08-17', 74156, 74307, 85.54, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-17 14:04:35', NULL, NULL, 85.54, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 85.54, NULL, NULL, 'BLOQUEADO', 6.67, 10, '6', NULL, 1, 71182, 52),
(573, 48, '2026-08-17', 95419, 95719, 158.14, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-17 14:16:26', NULL, NULL, 158.14, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 158.13, NULL, NULL, 'BLOQUEADO', 7.17, 10, '6', NULL, 1, 71183, 52),
(574, 42, '2026-08-17', 2131739, 2131964, 137.25, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-17 22:26:08', NULL, NULL, 137.25, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 137.25, NULL, NULL, 'BLOQUEADO', 6.21, 10, '6', NULL, 1, 71184, 52),
(575, 44, '2026-08-17', 49282, 49628, 138.13, NULL, 'C19 Puerto Corinto', 'Bombero2', NULL, '2026-08-17 22:27:10', NULL, NULL, 138.13, 'Kener José Martínez Zuniga ', '047-110589-0000Q', 'CONSUMO', 138.13, NULL, NULL, 'APROBADO', 9.49, 9, '6', NULL, 1, 71185, 52),
(576, 42, '2026-08-17', 2131964, 2131965, 3.79, NULL, 'C15 Relleno de Filtro', 'Bombero2', NULL, '2026-08-17 22:27:40', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 10, '6', NULL, 1, 71186, 52),
(577, 64, '2026-08-17', 10540, 10640, 44.44, NULL, 'C67 Proinco-Colonia Rubenia', 'Bombero2', NULL, '2026-08-17 22:28:25', NULL, NULL, 44.44, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 44.44, NULL, NULL, 'APROBADO', 8.52, 9, '6', NULL, 1, 71187, 52),
(578, 9, '2026-08-17', 16316, 16618, 85.77, NULL, 'C42 Miramontes', 'Bombero2', NULL, '2026-08-17 22:29:20', NULL, NULL, 85.77, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 85.77, NULL, NULL, 'APROBADO', 13.33, 15, '6', NULL, 1, 71188, 52),
(579, 75, '2026-08-17', 0, 0, 400.00, NULL, '', 'Bombero2', NULL, '2026-08-17 22:29:53', NULL, NULL, 400, '', '', 'CONSUMO', 400, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71189, 52),
(580, 80, '2026-08-17', 0, 0, 126.00, NULL, '', 'Bombero2', NULL, '2026-08-17 22:30:15', NULL, NULL, 126, '', '', 'CONSUMO', 126, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71190, 52),
(581, 47, '2026-08-17', 37139, 37587, 223.76, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-17 22:31:12', NULL, NULL, 223.76, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 223.75, NULL, NULL, 'BLOQUEADO', 7.58, 9, '6', NULL, 1, 71191, 52),
(582, 46, '2026-08-17', 243112, 243367, 82.49, NULL, 'C26 Chinandega', 'Bombero2', NULL, '2026-08-17 22:32:07', NULL, NULL, 82.49, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 82.49, NULL, NULL, 'APROBADO', 11.7, 10, '6', NULL, 1, 71192, 52),
(583, 75, '2026-08-17', 0, 0, 107.59, NULL, '', 'Bombero2', NULL, '2026-08-17 22:32:36', NULL, NULL, 107.59, '', '', 'CONSUMO', 107.59, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71193, 52),
(584, 12, '2026-08-17', 332847, 333050, 99.59, NULL, 'LPG2-097 Managua-Nandaime', 'Bombero2', NULL, '2026-08-17 22:33:53', NULL, NULL, 99.59, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 99.59, NULL, NULL, 'BLOQUEADO', 7.72, 15, '6', NULL, 1, 71194, 52),
(585, 88, '2026-08-17', 2876, 3230, 150.96, NULL, 'C69 Competra-Chinandega', 'Bombero2', NULL, '2026-08-17 22:34:52', NULL, NULL, 150.96, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 150.96, NULL, NULL, 'APROBADO', 8.87, 9, '6', NULL, 1, 71195, 52),
(586, 40, '2026-08-17', 70111, 70688, 273.07, NULL, 'C62 Matagalpa-Sebaco-Local', 'Bombero2', NULL, '2026-08-17 22:36:00', NULL, NULL, 273.07, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 273.07, NULL, NULL, 'BLOQUEADO', 8, 9, '6', NULL, 1, 71196, 52),
(587, 11, '2026-08-18', 298592, 298789, 38.56, NULL, 'LPG1-096 Managua', 'Bombero2', NULL, '2026-08-18 13:00:34', NULL, NULL, 38.56, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 38.56, NULL, NULL, 'APROBADO', 19.3, 15, '6', NULL, 1, 71197, 53),
(588, 65, '2026-08-18', 17312, 17648, 159.02, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-08-18 13:01:35', NULL, NULL, 159.02, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 159.02, NULL, NULL, 'APROBADO', 7.99, 9, '6', NULL, 1, 71198, 53),
(589, 54, '2026-08-18', 41025, 41195, 76.76, NULL, 'C45 Rescate', 'Bombero2', NULL, '2026-08-18 13:04:14', NULL, NULL, 76.76, '', '', 'CONSUMO', 76.76, NULL, NULL, 'APROBADO', 8.38, 8, '6', NULL, 1, 71199, 53),
(590, 54, '2026-08-18', 41195, 41837, 360.99, NULL, 'C45 Puerto Sandino', 'Bombero2', NULL, '2026-08-18 13:05:02', NULL, NULL, 360.99, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 360.99, NULL, NULL, 'BLOQUEADO', 6.73, 8, '6', NULL, 1, 71200, 54),
(591, 38, '2026-08-18', 85485, 85792, 125.60, NULL, 'C58 Puerto Sandino', 'Bombero2', NULL, '2026-08-18 13:06:00', NULL, NULL, 125.6, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 125.59, NULL, NULL, 'APROBADO', 9.25, 9, '6', NULL, 1, 71201, 55),
(592, 63, '2026-08-18', 31862, 31974, 57.79, NULL, 'C66 Locales', 'Bombero2', NULL, '2026-08-18 13:06:50', NULL, NULL, 57.79, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 57.79, NULL, NULL, 'APROBADO', 7.31, 9, '6', NULL, 1, 71202, 55),
(593, 13, '2026-08-18', 460825, 461152, 94.96, NULL, 'C49 Somoto', 'Bombero2', NULL, '2026-08-18 13:07:39', NULL, NULL, 94.96, '', '', 'CONSUMO', 94.96, NULL, NULL, 'APROBADO', 13.05, 15, '6', NULL, 1, 71203, 55),
(594, 6, '2026-08-18', 47174, 47489, 69.10, NULL, 'C38 Sebaco-Malacatoya', 'Bombero2', NULL, '2026-08-18 13:08:36', NULL, NULL, 69.1, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 69.1, NULL, NULL, 'APROBADO', 17.27, 15, '6', NULL, 1, 71204, 55),
(595, 89, '2026-08-18', 0, 1, 495.39, NULL, 'C70 Relleno y Prueba', 'Bombero2', NULL, '2026-08-18 13:22:53', NULL, NULL, 495.39, '', '', 'CONSUMO', 495.39, NULL, NULL, 'APROBADO', 0.01, 0, '6', NULL, 1, 71205, 55),
(596, 75, '2026-08-18', 0, 0, 234.32, NULL, '', 'Bombero2', NULL, '2026-08-18 15:26:08', NULL, NULL, 234.32, '', '', 'CONSUMO', 234.32, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71206, 55),
(597, 64, '2026-08-18', 10640, 10670, 22.89, NULL, 'C67 Huembes', 'Bombero2', NULL, '2026-08-18 22:10:59', NULL, NULL, 22.89, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 22.89, NULL, NULL, 'APROBADO', 4.96, 9, '6', NULL, 1, 71207, 55),
(598, 59, '2026-08-18', 1362460, 1362751, 166.37, NULL, 'C59 Locales', 'Bombero2', NULL, '2026-08-18 22:12:14', NULL, NULL, 166.37, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 166.37, NULL, NULL, 'BLOQUEADO', 6.62, 9, '6', NULL, 1, 71208, 55),
(599, 12, '2026-08-18', 333050, 333240, 78.99, NULL, 'LPG2-097 San Benito', 'Bombero2', NULL, '2026-08-18 22:13:32', NULL, NULL, 78.99, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 78.99, NULL, NULL, 'BLOQUEADO', 9.1, 15, '6', NULL, 1, 71209, 55),
(600, 62, '2026-08-18', 52047, 52456, 165.85, NULL, 'C64 Santo Domingo-Libertad', 'Bombero2', NULL, '2026-08-18 22:14:21', NULL, NULL, 165.85, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 165.85, NULL, NULL, 'APROBADO', 9.33, 9, '6', NULL, 1, 71210, 55),
(601, 80, '2026-08-18', 0, 0, 164.81, NULL, '', 'Bombero2', NULL, '2026-08-18 22:14:50', NULL, NULL, 164.81, '', '', 'CONSUMO', 164.81, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71211, 55),
(602, 57, '2026-08-18', 64153, 64374, 174.07, NULL, 'C56 Montelimar-Mercedes', 'Bombero2', NULL, '2026-08-18 22:17:18', NULL, NULL, 174.07, '', '', 'CONSUMO', 174.07, NULL, NULL, 'BLOQUEADO', 4.81, 8, '6', NULL, 1, 71212, 54),
(603, 42, '2026-08-18', 2131965, 2132265, 159.86, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-18 22:18:12', NULL, NULL, 159.86, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 159.86, NULL, NULL, 'BLOQUEADO', 7.1, 10, '6', NULL, 1, 71213, 54),
(604, 65, '2026-08-18', 17648, 17984, 145.90, NULL, 'C68 Puerto Corinto-Puerto Sandino', 'Bombero2', NULL, '2026-08-18 22:19:01', NULL, NULL, 145.9, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 145.9, NULL, NULL, 'APROBADO', 8.72, 9, '6', NULL, 1, 71214, 54),
(605, 76, '2026-08-18', 0, 0, 41.23, NULL, 'RAM 2500/Cuota Anibal Sevilla', 'Bombero2', NULL, '2026-08-18 22:19:27', NULL, NULL, 41.23, '', '', 'CONSUMO', 41.23, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71215, 54),
(606, 11, '2026-08-18', 298789, 298938, 79.10, NULL, 'LPG1-096 Chinandega', 'Bombero2', NULL, '2026-08-18 22:20:22', NULL, NULL, 79.1, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 79.1, NULL, NULL, 'BLOQUEADO', 7.14, 15, '6', NULL, 1, 71216, 54),
(607, 85, '2026-08-18', 0, 0, 44.40, NULL, 'Isuzu/Cuota Roger Vilchez', 'Bombero2', NULL, '2026-08-18 22:20:59', NULL, NULL, 44.4, '', '', 'CONSUMO', 44.4, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71217, 54),
(608, 8, '2026-08-18', 660963, 661334, 75.16, NULL, 'JS-03 Corinto-Chinandega', 'Bombero2', NULL, '2026-08-18 22:21:59', NULL, NULL, 75.16, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 75.16, NULL, NULL, 'APROBADO', 18.67, 15, '6', NULL, 1, 71218, 54),
(609, 14, '2026-08-18', 26525, 26704, 81.29, NULL, 'JS-01 Cantera-Guanacaste-Reyna del Sur', 'Bombero2', NULL, '2026-08-18 22:23:00', NULL, NULL, 81.29, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 81.28, NULL, NULL, 'BLOQUEADO', 8.34, 15, '6', NULL, 1, 71219, 54),
(610, 50, '2026-08-18', 1157992, 1158349, 211.25, NULL, 'C36 Sebaco-Diriamba-Inmaculada-Batahola', 'Bombero2', NULL, '2026-08-18 22:24:21', NULL, NULL, 211.25, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 211.25, NULL, NULL, 'BLOQUEADO', 6.39, 8, '6', NULL, 1, 71220, 54),
(611, 88, '2026-08-18', 3230, 3335, 86.37, NULL, 'C69 Locales', 'Bombero2', NULL, '2026-08-18 22:25:36', NULL, NULL, 86.37, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 86.37, NULL, NULL, 'BLOQUEADO', 4.61, 9, '6', NULL, 1, 71221, 54),
(612, 59, '2026-08-19', 1362751, 1362990, 131.92, NULL, 'C59 Leon-Locales', 'Bombero2', NULL, '2026-08-19 13:08:07', NULL, NULL, 131.92, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 131.92, NULL, NULL, 'BLOQUEADO', 6.85, 9, '6', NULL, 1, 71222, 56),
(613, 38, '2026-08-19', 85792, 86098, 133.45, NULL, 'C58 Puerto Corinto', 'Bombero2', NULL, '2026-08-19 13:09:29', NULL, NULL, 133.45, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 133.45, NULL, NULL, 'APROBADO', 8.69, 9, '6', NULL, 1, 71223, 56),
(614, 54, '2026-08-19', 41837, 41999, 118.17, NULL, 'C45 Ciudad Sandino-Puerto Sandino', 'Bombero2', NULL, '2026-08-19 13:12:12', NULL, NULL, 118.17, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 118.17, NULL, NULL, 'BLOQUEADO', 5.2, 8, '6', NULL, 1, 71224, 56),
(615, 71, '2026-08-19', 0, 0, 49.34, NULL, 'Kia 2700/Gestiones Compras', 'Bombero2', NULL, '2026-08-19 13:12:46', NULL, NULL, 49.34, '', '', 'CONSUMO', 49.34, NULL, NULL, 'APROBADO', 0, 45, '5', NULL, 1, 71225, 56),
(616, 75, '2026-08-19', 0, 0, 993.75, NULL, '', 'Bombero2', NULL, '2026-08-19 13:13:06', NULL, NULL, 993.75, '', '', 'CONSUMO', 993.75, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71226, 56),
(617, 45, '2026-08-19', 86582, 87181, 304.82, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-19 13:14:10', NULL, NULL, 304.82, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 304.82, NULL, NULL, 'BLOQUEADO', 7.43, 10, '6', NULL, 1, 71227, 56),
(618, 61, '2026-08-19', 1139986, 1140631, 346.04, NULL, 'C61 Leon-Chinandega-Rubenia', 'Bombero2', NULL, '2026-08-19 13:15:36', NULL, NULL, 346.04, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 346.04, NULL, NULL, 'BLOQUEADO', 7.06, 9, '6', NULL, 1, 71228, 56),
(619, 62, '2026-08-19', 52456, 52519, 29.02, NULL, 'C64 Proinco', 'Bombero2', NULL, '2026-08-19 13:17:38', NULL, NULL, 29.02, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 29.02, NULL, NULL, 'APROBADO', 8.21, 9, '6', NULL, 1, 71229, 56),
(620, 5, '2026-08-19', 16635, 17550, 296.17, NULL, 'C14 Mina la Rosita', 'Bombero2', NULL, '2026-08-19 13:20:48', NULL, NULL, 296.17, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 296.17, NULL, NULL, 'APROBADO', 11.69, 12, '6', NULL, 1, 71230, 56),
(621, 9, '2026-08-19', 16618, 16905, 73.00, NULL, 'C42 Maria Teresa', 'Bombero2', NULL, '2026-08-19 13:21:34', NULL, NULL, 73, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 73, NULL, NULL, 'APROBADO', 14.86, 15, '6', NULL, 1, 71231, 56),
(622, 44, '2026-08-19', 49628, 50194, 264.71, NULL, 'C19 Puerto Corinto', 'Bombero2', NULL, '2026-08-19 13:22:20', NULL, NULL, 264.71, 'Kener José Martínez Zuniga ', '047-110589-0000Q', 'CONSUMO', 264.71, NULL, NULL, 'BLOQUEADO', 8.09, 9, '6', NULL, 1, 71232, 56),
(623, 48, '2026-08-19', 95719, 96318, 295.84, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-19 13:47:40', NULL, NULL, 295.84, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 295.84, NULL, NULL, 'BLOQUEADO', 7.66, 10, '6', NULL, 1, 71233, 56),
(624, 81, '2026-08-19', 9, 10, 3.79, NULL, 'Relleno de Filtro Transporte Marin', 'Bombero2', NULL, '2026-08-19 13:52:47', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 0, '6', NULL, 1, 71234, 56),
(625, 79, '2026-08-19', 8123, 8368, 147.24, NULL, 'C43 Reynaga-Jinotepe', 'Bombero2', NULL, '2026-08-19 13:53:42', NULL, NULL, 147.24, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 147.24, NULL, NULL, 'BLOQUEADO', 6.3, 8, '6', NULL, 1, 71235, 56),
(626, 75, '2026-08-19', 0, 0, 994.93, NULL, '', 'Bombero2', NULL, '2026-08-19 21:37:26', NULL, NULL, 994.93, '', '', 'CONSUMO', 994.93, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71236, 56),
(627, 84, '2026-08-19', 0, 0, 48.88, NULL, 'Toyota L/Cuota Oscar Briceño', 'Bombero2', NULL, '2026-08-19 21:38:06', NULL, NULL, 48.88, '', '', 'CONSUMO', 48.88, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71237, 56),
(628, 11, '2026-08-19', 298938, 299036, 37.58, NULL, 'LPG1-096 Managua', 'Bombero2', NULL, '2026-08-19 21:39:21', NULL, NULL, 37.58, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 37.58, NULL, NULL, 'APROBADO', 9.82, 15, '6', NULL, 1, 71238, 56),
(629, 75, '2026-08-19', 0, 0, 40.00, NULL, '', 'Bombero2', NULL, '2026-08-19 21:39:47', NULL, NULL, 40, '', '', 'CONSUMO', 40, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71239, 56),
(630, 88, '2026-08-19', 3335, 3399, 53.90, NULL, 'C69 Managua', 'Bombero2', NULL, '2026-08-19 21:41:39', NULL, NULL, 53.9, '', '', 'CONSUMO', 53.9, NULL, NULL, 'BLOQUEADO', 4.52, 9, '6', NULL, 1, 71240, 56),
(631, 12, '2026-08-19', 333240, 333524, 78.99, NULL, 'LPG2-097 Guacalito', 'Bombero2', NULL, '2026-08-19 21:42:51', NULL, NULL, 78.99, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 78.99, NULL, NULL, 'APROBADO', 13.63, 15, '6', NULL, 1, 71241, 56),
(632, 76, '2026-08-19', 0, 0, 47.44, NULL, 'RAM 2500/Cuota Anibal Sevilla', 'Bombero2', NULL, '2026-08-19 21:43:26', NULL, NULL, 47.44, '', '', 'CONSUMO', 47.44, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71242, 56),
(633, 39, '2026-08-19', 53961, 54542, 299.52, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-19 21:45:23', NULL, NULL, 299.52, 'Ulises Joel Bautista Gómez ', '001-140100-1011D', 'CONSUMO', 299.52, NULL, NULL, 'BLOQUEADO', 7.34, 9, '6', NULL, 1, 71243, 56),
(634, 57, '2026-08-19', 64374, 64655, 132.19, NULL, 'C56 Matagalpa', 'Bombero2', NULL, '2026-08-19 21:46:55', NULL, NULL, 132.19, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 132.19, NULL, NULL, 'APROBADO', 8.04, 8, '6', NULL, 1, 71244, 56),
(635, 50, '2026-08-19', 1158349, 1158613, 120.05, NULL, 'C36 Managua-Leon', 'Bombero2', NULL, '2026-08-19 21:48:47', NULL, NULL, 120.05, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 120.05, NULL, NULL, 'APROBADO', 8.33, 8, '6', NULL, 1, 71245, 56),
(636, 14, '2026-08-19', 26704, 26999, 78.43, NULL, 'JS-01 Boaco', 'Bombero2', NULL, '2026-08-19 21:50:34', NULL, NULL, 78.43, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 78.43, NULL, NULL, 'APROBADO', 14.25, 15, '6', NULL, 1, 71246, 56),
(637, 46, '2026-08-19', 243367, 243819, 224.70, NULL, 'C26 Puerto Sandino', 'Bombero2', NULL, '2026-08-19 21:52:39', NULL, NULL, 224.7, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 224.7, NULL, NULL, 'BLOQUEADO', 7.62, 10, '6', NULL, 1, 71247, 56),
(638, 42, '2026-08-19', 2132265, 2132714, 219.01, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-19 21:53:58', NULL, NULL, 219.01, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 219.01, NULL, NULL, 'BLOQUEADO', 7.75, 10, '6', NULL, 1, 71248, 56),
(640, 6, '2026-08-19', 47490, 47937, 108.76, NULL, 'Cooserva', 'Bombero2', NULL, '2026-08-19 21:56:35', NULL, NULL, 108.76, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 108.75, NULL, NULL, 'APROBADO', 15.57, 15, '6', NULL, 1, 71250, 56),
(641, 5, '2026-08-19', 17550, 17700, 51.42, NULL, 'C14 Prodecon', 'Bombero2', NULL, '2026-08-19 21:58:47', NULL, NULL, 51.42, '', '', 'CONSUMO', 51.41, NULL, NULL, 'APROBADO', 11.04, 12, '6', NULL, 1, 71251, 57),
(642, 8, '2026-08-19', 661334, 661611, 60.09, NULL, 'JS-03 Chinandega', 'Bombero2', NULL, '2026-08-19 22:00:11', NULL, NULL, 60.09, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 60.09, NULL, NULL, 'APROBADO', 17.42, 15, '6', NULL, 1, 71252, 57),
(643, 65, '2026-08-19', 17984, 18533, 262.90, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-08-19 22:02:14', NULL, NULL, 262.9, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 262.89, NULL, NULL, 'BLOQUEADO', 7.91, 9, '6', NULL, 1, 71253, 57),
(644, 43, '2026-08-19', 74307, 75056, 377.42, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-19 22:04:21', NULL, NULL, 377.42, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 377.42, NULL, NULL, 'BLOQUEADO', 7.52, 10, '6', NULL, 1, 71254, 57),
(645, 38, '2026-08-20', 86098, 86405, 139.99, NULL, 'C58 Puerto Corinto', 'Bombero2', NULL, '2026-08-20 08:44:55', NULL, NULL, 139.99, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 139.99, NULL, NULL, 'APROBADO', 8.31, 9, '6', NULL, 1, 71255, 58),
(646, 6, '2026-08-20', 47937, 48122, 44.97, NULL, 'C38 Miramontes', 'Bombero2', NULL, '2026-08-20 08:46:03', NULL, NULL, 44.97, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 44.97, NULL, NULL, 'APROBADO', 15.56, 15, '6', NULL, 1, 71256, 58),
(647, 8, '2026-08-20', 661611, 661740, 30.83, NULL, 'JS-03 Granada', 'Bombero2', NULL, '2026-08-20 08:47:27', NULL, NULL, 30.83, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 30.82, NULL, NULL, 'APROBADO', 15.89, 15, '6', NULL, 1, 71257, 58),
(648, 60, '2026-08-20', 38707, 38935, 139.49, NULL, 'C60 Locales', 'Bombero2', NULL, '2026-08-20 10:16:11', NULL, NULL, 139.49, 'Omar Patricio Osejo Amador ', '441-240882-0002F', 'CONSUMO', 139.49, NULL, NULL, 'BLOQUEADO', 6.18, 9, '6', NULL, 1, 71258, 59),
(649, 57, '2026-08-20', 64655, 64693, 42.84, NULL, 'C56 Las Mercedes', 'Bombero2', NULL, '2026-08-20 10:17:33', NULL, NULL, 42.84, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 42.84, NULL, NULL, 'BLOQUEADO', 3.34, 8, '6', NULL, 1, 71259, 59);
INSERT INTO `registro_combustible` (`id`, `id_vehiculo`, `fecha_registro`, `kilometraje_anterior`, `kilometraje_actual`, `cantidad_litros`, `id_motivo`, `observaciones`, `usuario_crea`, `usuario_edita`, `fecha_actualiza`, `id_tipo_motivo`, `id_direccion`, `medicion`, `nombreCliente`, `dni`, `tipo`, `combustible_tanque`, `monto_nio`, `monto_usd`, `estado`, `rendimiento`, `rendimiento_promedio`, `referencia1`, `referencia2`, `enviado`, `numero_ingreso_sag`, `id_lectura`) VALUES
(650, 63, '2026-08-20', 31974, 32041, 41.26, NULL, 'C66 Locales', 'Bombero2', NULL, '2026-08-20 13:53:12', NULL, NULL, 41.26, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 41.26, NULL, NULL, 'APROBADO', 6.17, 9, '6', NULL, 1, 71260, 59),
(651, 77, '2026-08-20', 0, 0, 64.95, NULL, 'Toyota L/Cuota Anibal Sevilla', 'Bombero2', NULL, '2026-08-20 13:58:04', NULL, NULL, 64.95, '', '', 'CONSUMO', 64.95, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71261, 59),
(652, 75, '2026-08-20', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-20 13:58:26', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71262, 59),
(653, 80, '2026-08-20', 0, 0, 109.94, NULL, 'Denis Blas Ampie', 'Bombero2', NULL, '2026-08-20 13:58:54', NULL, NULL, 109.94, '', '', 'CONSUMO', 109.94, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71263, 59),
(654, 42, '2026-08-20', 2132714, 2132967, 134.55, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-20 14:00:58', NULL, NULL, 134.55, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 134.55, NULL, NULL, 'BLOQUEADO', 7.1, 10, '6', NULL, 1, 71264, 59),
(655, 80, '2026-08-20', 0, 0, 145.18, NULL, '', 'Bombero2', NULL, '2026-08-20 17:37:48', NULL, NULL, 145.18, '', '', 'CONSUMO', 145.18, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71265, 59),
(656, 13, '2026-08-20', 461152, 461313, 68.06, NULL, '', 'Bombero2', NULL, '2026-08-20 17:39:18', NULL, NULL, 68.06, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 68.06, NULL, NULL, 'BLOQUEADO', 8.97, 15, '6', NULL, 1, 71266, 59),
(657, 50, '2026-08-20', 1158613, 1158910, 142.97, NULL, 'C36 Leon-Diriamba', 'Bombero2', NULL, '2026-08-20 17:43:20', NULL, NULL, 142.97, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 142.97, NULL, NULL, 'APROBADO', 7.86, 8, '6', NULL, 1, 71267, 59),
(658, 64, '2026-08-20', 10670, 10750, 34.24, NULL, 'C67 Puma-Xiloa', 'Bombero2', NULL, '2026-08-20 17:46:49', NULL, NULL, 34.24, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 34.23, NULL, NULL, 'APROBADO', 8.85, 9, '6', NULL, 1, 71268, 59),
(659, 80, '2026-08-20', 0, 0, 164.10, NULL, '', 'Bombero2', NULL, '2026-08-20 17:47:22', NULL, NULL, 164.1, '', '', 'CONSUMO', 164.1, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71269, 59),
(660, 79, '2026-08-20', 8368, 8692, 134.18, NULL, 'C43 Esteli', 'Bombero2', NULL, '2026-08-20 17:48:47', NULL, NULL, 134.18, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 134.18, NULL, NULL, 'BLOQUEADO', 9.15, 8, '6', NULL, 1, 71270, 59),
(661, 75, '2026-08-20', 0, 0, 3.66, NULL, '', 'Bombero2', NULL, '2026-08-20 17:49:25', NULL, NULL, 3.66, '', '', 'CONSUMO', 3.66, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71271, 59),
(662, 11, '2026-08-20', 299036, 299148, 42.26, NULL, 'LPG1-096 Nindiri-Masaya', 'Bombero2', NULL, '2026-08-20 17:50:39', NULL, NULL, 42.26, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 42.26, NULL, NULL, 'APROBADO', 10, 15, '6', NULL, 1, 71272, 58),
(664, 73, '2026-08-20', 0, 0, 35.31, NULL, 'Mitsubish/Gestion Operaciones', 'Bombero2', NULL, '2026-08-20 20:57:42', NULL, NULL, 35.31, '', '', 'CONSUMO', 35.31, NULL, NULL, 'APROBADO', 0, 40, '5', NULL, 1, 71274, 59),
(665, 65, '2026-08-20', 18533, 18866, 140.24, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-08-20 20:59:10', NULL, NULL, 140.24, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 140.24, NULL, NULL, 'APROBADO', 8.98, 9, '6', NULL, 1, 71275, 59),
(666, 46, '2026-08-20', 243819, 244268, 223.89, NULL, 'C26 Puerto Sandino', 'Bombero2', NULL, '2026-08-20 21:00:28', NULL, NULL, 223.89, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 223.89, NULL, NULL, 'BLOQUEADO', 7.59, 10, '6', NULL, 1, 71276, 59),
(667, 9, '2026-08-20', 16905, 17190, 81.96, NULL, 'C42 Llansa', 'Bombero2', NULL, '2026-08-20 21:01:35', NULL, NULL, 81.96, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 81.96, NULL, NULL, 'APROBADO', 13.14, 15, '6', NULL, 1, 71277, 59),
(668, 61, '2026-08-20', 1140631, 1141171, 279.16, NULL, 'C61 Sebaco-Rubenia-Chinandega', 'Bombero2', NULL, '2026-08-20 22:04:11', NULL, NULL, 279.16, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 279.16, NULL, NULL, 'BLOQUEADO', 7.33, 9, '6', NULL, 1, 71278, 59),
(669, 44, '2026-08-21', 50194, 50541, 163.13, NULL, 'C19 Puerto Corinto', 'Bombero2', NULL, '2026-08-21 09:34:54', NULL, NULL, 163.13, 'Kener José Martínez Zuniga ', '047-110589-0000Q', 'CONSUMO', 163.13, NULL, NULL, 'APROBADO', 8.05, 9, '6', NULL, 1, 71279, 61),
(670, 50, '2026-08-21', 1158910, 1159088, 58.83, NULL, 'C36 Leon', 'Bombero2', NULL, '2026-08-21 09:36:43', NULL, NULL, 58.83, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 58.83, NULL, NULL, 'BLOQUEADO', 11.47, 8, '6', NULL, 1, 71280, 61),
(671, 75, '2026-08-21', 0, 0, 7.03, NULL, '', 'Bombero2', NULL, '2026-08-21 09:37:01', NULL, NULL, 7.03, '', '', 'CONSUMO', 7.03, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71281, 61),
(672, 75, '2026-08-21', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-21 09:37:29', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71282, 61),
(673, 82, '2026-08-21', 0, 0, 567.54, NULL, '', 'Bombero2', NULL, '2026-08-21 10:34:29', NULL, NULL, 567.54, '', '', 'CONSUMO', 567.54, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71283, 61),
(674, 45, '2026-08-21', 87181, 87779, 275.11, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-21 10:57:07', NULL, NULL, 275.11, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 275.11, NULL, NULL, 'BLOQUEADO', 8.23, 10, '6', NULL, 1, 71284, 61),
(675, 75, '2026-08-21', 0, 0, 40.00, NULL, '', 'Bombero2', NULL, '2026-08-21 13:02:39', NULL, NULL, 40, '', '', 'CONSUMO', 40, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71285, 61),
(676, 64, '2026-08-21', 10750, 10820, 43.41, NULL, 'C67 Pavinic-Planta GCM', 'Bombero2', NULL, '2026-08-21 16:00:19', NULL, NULL, 43.41, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 43.41, NULL, NULL, 'APROBADO', 6.1, 9, '6', NULL, 1, 71286, 61),
(677, 48, '2026-08-21', 96318, 96916, 291.55, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-21 16:09:31', NULL, NULL, 291.55, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 291.55, NULL, NULL, 'BLOQUEADO', 7.77, 10, '6', NULL, 1, 71287, 61),
(678, 80, '2026-08-21', 0, 0, 37.85, NULL, '', 'Bombero2', NULL, '2026-08-21 16:10:12', NULL, NULL, 37.85, '', '', 'CONSUMO', 37.85, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71288, 61),
(679, 80, '2026-08-21', 0, 0, 104.46, NULL, '', 'Bombero2', NULL, '2026-08-21 16:10:42', NULL, NULL, 104.46, '', '', 'CONSUMO', 104.46, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71289, 61),
(680, 9, '2026-08-21', 17190, 17402, 44.95, NULL, 'C42 Finca el Calvario', 'Bombero2', NULL, '2026-08-21 16:11:43', NULL, NULL, 44.95, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 44.95, NULL, NULL, 'APROBADO', 17.85, 15, '6', NULL, 1, 71290, 61),
(681, 82, '2026-08-21', 0, 0, 431.64, NULL, '', 'Bombero2', NULL, '2026-08-21 16:14:19', NULL, NULL, 431.64, '', '', 'CONSUMO', 431.64, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71291, 61),
(682, 78, '2026-08-21', 0, 0, 60.62, NULL, '', 'Bombero2', NULL, '2026-08-21 22:13:00', NULL, NULL, 60.62, '', '', 'CONSUMO', 60.62, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71292, 61),
(683, 8, '2026-08-21', 661740, 661805, 26.70, NULL, 'JS-03 Guerrero', 'Bombero2', NULL, '2026-08-21 22:14:05', NULL, NULL, 26.7, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 26.7, NULL, NULL, 'APROBADO', 9.2, 15, '6', NULL, 1, 71293, 61),
(684, 75, '2026-08-21', 0, 0, 33.96, NULL, '', 'Bombero2', NULL, '2026-08-21 22:14:23', NULL, NULL, 33.96, '', '', 'CONSUMO', 33.96, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71294, 61),
(685, 12, '2026-08-21', 333524, 333814, 99.64, NULL, 'LPG2-097 Leon-Chichigalpa-Chinandega', 'Bombero2', NULL, '2026-08-21 22:15:27', NULL, NULL, 99.64, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 99.64, NULL, NULL, 'BLOQUEADO', 11.01, 15, '6', NULL, 1, 71295, 61),
(686, 72, '2026-08-21', 0, 0, 6.70, NULL, 'Kia 3000/Gestiones operaciones', 'Bombero2', NULL, '2026-08-21 22:16:00', NULL, NULL, 6.7, '', '', 'CONSUMO', 6.7, NULL, NULL, 'APROBADO', 0, 45, '5', NULL, 1, 71296, 61),
(687, 13, '2026-08-21', 461313, 461465, 42.27, NULL, 'C49 Alcatraz-Malacatoya', 'Bombero2', NULL, '2026-08-21 22:17:06', NULL, NULL, 42.27, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 42.27, NULL, NULL, 'APROBADO', 13.61, 15, '6', NULL, 1, 71297, 61),
(688, 90, '2026-08-21', 0, 0, 3785.00, NULL, '', 'Bombero2', NULL, '2026-08-21 22:21:13', NULL, NULL, 3785, '', '', 'CONSUMO', 3785, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71298, 60),
(689, 80, '2026-08-21', 0, 0, 117.92, NULL, '', 'Bombero2', NULL, '2026-08-21 22:23:19', NULL, NULL, 117.92, '', '', 'CONSUMO', 117.92, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71299, 62),
(690, 85, '2026-08-21', 0, 0, 32.16, NULL, 'Isuzu/Cuota Roger Vilchez', 'Bombero2', NULL, '2026-08-21 22:23:48', NULL, NULL, 32.16, '', '', 'CONSUMO', 32.16, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71300, 62),
(691, 40, '2026-08-21', 70688, 71157, 214.56, NULL, 'C62 Leon-Competra-Sebaco', 'Bombero2', NULL, '2026-08-21 22:24:45', NULL, NULL, 214.56, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 214.56, NULL, NULL, 'APROBADO', 8.27, 9, '6', NULL, 1, 71301, 62),
(692, 47, '2026-08-21', 37587, 38046, 228.75, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-21 22:25:34', NULL, NULL, 228.75, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 228.75, NULL, NULL, 'BLOQUEADO', 7.6, 9, '6', NULL, 1, 71302, 62),
(693, 83, '2026-08-21', 0, 0, 2271.00, NULL, '', 'Bombero2', NULL, '2026-08-21 22:26:02', NULL, NULL, 2271, '', '', 'CONSUMO', 2271, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71303, 60),
(694, 42, '2026-08-21', 2132967, 2133386, 217.57, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-21 22:27:07', NULL, NULL, 217.57, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 217.57, NULL, NULL, 'BLOQUEADO', 7.29, 10, '6', NULL, 1, 71304, 62),
(695, 3, '2026-08-21', 22075, 22150, 16.21, NULL, 'C10 TCF', 'Bombero2', NULL, '2026-08-21 22:33:07', NULL, NULL, 16.21, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 16.21, NULL, NULL, 'APROBADO', 30.1, 15, '6', NULL, 1, 71305, 60),
(696, 79, '2026-08-21', 8692, 8891, 109.94, NULL, 'C43 Loyola-Competra-Salvadorita', 'Bombero2', NULL, '2026-08-21 22:34:09', NULL, NULL, 109.94, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 109.94, NULL, NULL, 'APROBADO', 6.86, 8, '6', NULL, 1, 71306, 60),
(697, 5, '2026-08-21', 0, 233, 66.98, NULL, 'C14 Corrales Verde-Proinco-Llansa', 'Bombero2', NULL, '2026-08-21 22:37:37', NULL, NULL, 66.98, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 66.97, NULL, NULL, 'APROBADO', 13.16, 12, '6', NULL, 1, 71307, 60),
(698, 6, '2026-08-21', 48122, 48307, 53.15, NULL, 'C38 Miramontes', 'Bombero2', NULL, '2026-08-21 22:38:35', NULL, NULL, 53.15, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 53.15, NULL, NULL, 'APROBADO', 13.14, 15, '6', NULL, 1, 71308, 60),
(699, 55, '2026-08-22', 18709, 18804, 114.86, NULL, 'C46 San Benito-Managua', 'Bombero2', NULL, '2026-08-22 09:37:42', NULL, NULL, 114.86, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 114.86, NULL, NULL, 'BLOQUEADO', 3.13, 8, '6', NULL, 1, 71309, 63),
(700, 63, '2026-08-22', 32041, 32411, 120.25, NULL, 'C66 Marina Puesta el Sol', 'Bombero2', NULL, '2026-08-22 09:54:09', NULL, NULL, 120.25, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 120.25, NULL, NULL, 'BLOQUEADO', 11.66, 9, '6', NULL, 1, 71310, 63),
(701, 51, '2026-08-22', 99567, 100041, 248.80, NULL, 'C37 Puerto Corinto', 'Bombero2', NULL, '2026-08-22 09:57:41', NULL, NULL, 248.8, 'Julio José Mendoza Ruíz ', '001-080300-1009B', 'CONSUMO', 248.8, NULL, NULL, 'BLOQUEADO', 7.2, 10, '6', NULL, 1, 71311, 63),
(702, 75, '2026-08-22', 0, 0, 38.00, NULL, '', 'Bombero2', NULL, '2026-08-22 19:41:09', NULL, NULL, 38, '', '', 'CONSUMO', 38, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71312, 63),
(703, 75, '2026-08-22', 0, 0, 845.45, NULL, '', 'Bombero2', NULL, '2026-08-22 19:41:28', NULL, NULL, 845.45, '', '', 'CONSUMO', 845.45, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71313, 63),
(704, 59, '2026-08-22', 1362990, 1363376, 226.15, NULL, 'C59 Los Brasiles-Panamericano-Loyola-Chinandega', 'Bombero2', NULL, '2026-08-22 19:43:07', NULL, NULL, 226.15, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 226.15, NULL, NULL, 'BLOQUEADO', 6.46, 9, '6', NULL, 1, 71314, 64),
(705, 5, '2026-08-22', 233, 401, 69.12, NULL, 'C14 Prodecon-Llansa', 'Bombero2', NULL, '2026-08-22 19:44:01', NULL, NULL, 69.12, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 69.11, NULL, NULL, 'APROBADO', 9.18, 12, '6', NULL, 1, 71315, 64),
(706, 9, '2026-08-22', 17402, 17555, 42.99, NULL, 'C42 San Martin', 'Bombero2', NULL, '2026-08-22 19:44:46', NULL, NULL, 42.99, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 42.99, NULL, NULL, 'APROBADO', 13.5, 15, '6', NULL, 1, 71316, 64),
(707, 3, '2026-08-22', 22150, 22188, 16.05, NULL, 'C10 La Colonia-TCF', 'Bombero2', NULL, '2026-08-22 19:45:47', NULL, NULL, 16.05, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 16.05, NULL, NULL, 'APROBADO', 8.84, 15, '6', NULL, 1, 71317, 64),
(708, 75, '2026-08-22', 0, 0, 30.00, NULL, '', 'Bombero2', NULL, '2026-08-22 19:46:05', NULL, NULL, 30, '', '', 'CONSUMO', 30, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71318, 64),
(709, 61, '2026-08-22', 1141171, 1141373, 90.00, NULL, 'C61 Leon-Rubenia', 'Bombero2', NULL, '2026-08-22 19:47:03', NULL, NULL, 90, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 90, NULL, NULL, 'APROBADO', 8.5, 9, '6', NULL, 1, 71319, 64),
(710, 43, '2026-08-22', 75056, 75656, 303.93, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-22 19:47:55', NULL, NULL, 303.93, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 303.93, NULL, NULL, 'BLOQUEADO', 7.47, 10, '6', NULL, 1, 71320, 64),
(711, 44, '2026-08-22', 50541, 51086, 267.79, NULL, 'C19 Puerto Corinto-Puerto Sandino', 'Bombero2', NULL, '2026-08-22 19:49:22', NULL, NULL, 267.79, 'Kener José Martínez Zuniga ', '047-110589-0000Q', 'CONSUMO', 267.79, NULL, NULL, 'BLOQUEADO', 7.7, 9, '6', NULL, 1, 71321, 64),
(712, 65, '2026-08-22', 18866, 19629, 341.22, NULL, 'C68 Puerto Corinto-Puerto Sandino', 'Bombero2', NULL, '2026-08-22 19:50:06', NULL, NULL, 341.22, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 341.22, NULL, NULL, 'BLOQUEADO', 8.47, 9, '6', NULL, 1, 71322, 64),
(713, 80, '2026-08-22', 0, 0, 47.05, NULL, '', 'Bombero2', NULL, '2026-08-22 19:50:26', NULL, NULL, 47.05, '', '', 'CONSUMO', 47.05, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71323, 64),
(714, 47, '2026-08-22', 38046, 38346, 137.29, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-22 19:51:25', NULL, NULL, 137.29, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 137.28, NULL, NULL, 'APROBADO', 8.27, 9, '6', NULL, 1, 71324, 64),
(715, 42, '2026-08-22', 2133386, 2133611, 148.41, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-22 19:52:18', NULL, NULL, 148.41, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 148.41, NULL, NULL, 'BLOQUEADO', 5.75, 10, '6', NULL, 1, 71325, 64),
(716, 54, '2026-08-22', 41999, 42445, 214.60, NULL, 'C45 Esteli', 'Bombero2', NULL, '2026-08-22 19:53:02', NULL, NULL, 214.6, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 214.6, NULL, NULL, 'APROBADO', 7.87, 8, '6', NULL, 1, 71326, 64),
(717, 48, '2026-08-22', 96916, 97216, 149.33, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-22 19:53:55', NULL, NULL, 149.33, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 149.33, NULL, NULL, 'BLOQUEADO', 7.6, 10, '6', NULL, 1, 71327, 64),
(718, 6, '2026-08-22', 48307, 48581, 53.14, NULL, 'C38 Transporte Monserrath', 'Bombero2', NULL, '2026-08-22 19:56:01', NULL, NULL, 53.14, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 53.14, NULL, NULL, 'APROBADO', 19.48, 15, '6', NULL, 1, 71328, 64),
(719, 39, '2026-08-22', 54542, 55267, 353.96, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-22 19:56:46', NULL, NULL, 353.96, 'Ulises Joel Bautista Gómez ', '001-140100-1011D', 'CONSUMO', 353.96, NULL, NULL, 'BLOQUEADO', 7.75, 9, '6', NULL, 1, 71329, 64),
(720, 88, '2026-08-22', 3399, 3558, 140.93, NULL, 'C69 Managua', 'Bombero2', NULL, '2026-08-22 20:00:14', NULL, NULL, 140.93, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 140.93, NULL, NULL, 'BLOQUEADO', 4.27, 9, '6', NULL, 1, 71330, 64),
(721, 79, '2026-08-22', 8891, 8968, 68.55, NULL, 'C43 Nejapa-Competra', 'Bombero2', NULL, '2026-08-22 20:01:57', NULL, NULL, 68.55, 'Lester Enrique Sequeira Solís ', '201-030681-0001C', 'CONSUMO', 68.55, NULL, NULL, 'BLOQUEADO', 4.23, 8, '6', NULL, 1, 71331, 64),
(723, 8, '2026-08-22', 661805, 662062, 67.94, NULL, 'JS-03 Malacatoya', 'Bombero2', NULL, '2026-08-22 20:03:26', NULL, NULL, 67.94, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 67.94, NULL, NULL, 'APROBADO', 14.32, 15, '6', NULL, 1, 71333, 64),
(724, 80, '2026-08-22', 0, 0, 131.24, NULL, '', 'Bombero2', NULL, '2026-08-22 20:03:45', NULL, NULL, 131.24, '', '', 'CONSUMO', 131.24, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71334, 64),
(725, 80, '2026-08-22', 0, 0, 136.04, NULL, '', 'Bombero2', NULL, '2026-08-22 20:04:38', NULL, NULL, 136.04, '', '', 'CONSUMO', 136.04, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71335, 64),
(726, 64, '2026-08-22', 10820, 11300, 216.11, NULL, 'C67 El Ayote', 'Bombero2', NULL, '2026-08-22 20:05:48', NULL, NULL, 216.11, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 216.11, NULL, NULL, 'APROBADO', 8.41, 9, '6', NULL, 1, 71336, 64),
(727, 60, '2026-08-22', 38935, 39094, 116.34, NULL, 'C60 Locales-Masaya', 'Bombero2', NULL, '2026-08-22 20:06:45', NULL, NULL, 116.34, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 116.33, NULL, NULL, 'BLOQUEADO', 5.17, 9, '6', NULL, 1, 71337, 64),
(728, 80, '2026-08-22', 0, 0, 144.21, NULL, '', 'Bombero2', NULL, '2026-08-22 22:01:27', NULL, NULL, 144.21, '', '', 'CONSUMO', 144.21, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71338, 64),
(729, 11, '2026-08-22', 299148, 299460, 100.81, NULL, 'LPG1-096 Managua-Cofradia-Tola Rivas', 'Bombero2', NULL, '2026-08-22 22:02:47', NULL, NULL, 100.81, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 100.81, NULL, NULL, 'BLOQUEADO', 11.72, 15, '6', NULL, 1, 71339, 64),
(730, 41, '2026-08-22', 178628, 179292, 439.86, NULL, 'C20 Oleo Caribe-El Rama', 'Bombero2', NULL, '2026-08-22 22:43:08', NULL, NULL, 439.86, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 439.86, NULL, NULL, 'BLOQUEADO', 5.72, 9, '6', NULL, 1, 71340, 64),
(731, 73, '2026-08-24', 0, 0, 33.87, NULL, 'Mitsubishi M372333/Gestion Operaciones', 'Bombero2', NULL, '2026-08-24 08:20:11', NULL, NULL, 33.87, '', '', 'CONSUMO', 33.87, NULL, NULL, 'APROBADO', 0, 40, '5', NULL, 1, 71341, 66),
(732, 75, '2026-08-24', 0, 0, 960.16, NULL, '', 'Bombero2', NULL, '2026-08-24 10:25:18', NULL, NULL, 960.16, '', '', 'CONSUMO', 960.16, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71342, 66),
(733, 80, '2026-08-24', 0, 0, 159.15, NULL, '', 'Bombero2', NULL, '2026-08-24 10:25:39', NULL, NULL, 159.15, '', '', 'CONSUMO', 159.15, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71343, 66),
(734, 88, '2026-08-24', 3558, 3777, 116.87, NULL, 'C69 Leon-Locales', 'Bombero2', NULL, '2026-08-24 10:27:13', NULL, NULL, 116.87, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 116.87, NULL, NULL, 'BLOQUEADO', 7.1, 9, '6', NULL, 1, 71344, 66),
(735, 9, '2026-08-24', 17555, 17556, 3.79, NULL, 'C42 Relleno de Filtro', 'Bombero2', NULL, '2026-08-24 10:27:42', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 15, '6', NULL, 1, 71345, 66),
(736, 60, '2026-08-24', 39094, 39216, 56.81, NULL, 'C60 Granada', 'Bombero2', NULL, '2026-08-24 10:28:37', NULL, NULL, 56.81, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 56.81, NULL, NULL, 'APROBADO', 8.14, 9, '6', NULL, 1, 71346, 66),
(737, 72, '2026-08-24', 0, 0, 44.13, NULL, 'Kia 3000/Gestiones El Rama', 'Bombero2', NULL, '2026-08-24 10:29:09', NULL, NULL, 44.13, '', '', 'CONSUMO', 44.13, NULL, NULL, 'APROBADO', 0, 45, '5', NULL, 1, 71347, 66),
(738, 91, '2026-08-24', 0, 0, 518.55, NULL, '', 'Bombero2', NULL, '2026-08-24 11:00:17', NULL, NULL, 518.55, '', '', 'CONSUMO', 518.54, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71348, 66),
(739, 3, '2026-08-24', 22188, 22189, 0.10, NULL, 'C10 Revision de combustible', 'Bombero2', NULL, '2026-08-24 11:01:06', NULL, NULL, 0.1, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 0.1, NULL, NULL, 'APROBADO', 37.85, 15, '6', NULL, 1, 71349, 66),
(740, 45, '2026-08-24', 87779, 88378, 287.76, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-24 11:04:51', NULL, NULL, 287.76, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 287.75, NULL, NULL, 'BLOQUEADO', 7.87, 10, '6', NULL, 1, 71350, 66),
(741, 59, '2026-08-24', 1363376, 1363575, 126.24, NULL, 'C59 Diriamba-Masaya-Local', 'Bombero2', NULL, '2026-08-24 11:07:43', NULL, NULL, 126.24, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 126.24, NULL, NULL, 'BLOQUEADO', 5.97, 9, '6', NULL, 1, 71351, 66),
(742, 40, '2026-08-24', 71157, 71906, 307.71, NULL, 'C62 Esteli-Leon-Sebaco', 'Bombero2', NULL, '2026-08-24 13:34:42', NULL, NULL, 307.71, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 307.7, NULL, NULL, 'APROBADO', 9.21, 9, '6', NULL, 1, 71352, 66),
(743, 75, '2026-08-24', 0, 0, 6.11, NULL, '', 'Bombero2', NULL, '2026-08-24 13:35:00', NULL, NULL, 6.11, '', '', 'CONSUMO', 6.11, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71353, 66),
(744, 86, '2026-08-24', 0, 0, 7.57, NULL, 'Toyota H/Gestiones GCM', 'Bombero2', NULL, '2026-08-24 13:35:30', NULL, NULL, 7.57, '', '', 'CONSUMO', 7.57, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71354, 66),
(745, 86, '2026-08-24', 0, 0, 39.77, NULL, 'Toyota H/Cuota Oscar Briceño', 'Bombero2', NULL, '2026-08-24 13:36:18', NULL, NULL, 39.77, '', '', 'CONSUMO', 39.77, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71355, 66),
(746, 80, '2026-08-24', 0, 0, 65.33, NULL, '', 'Bombero2', NULL, '2026-08-24 13:36:35', NULL, NULL, 65.33, '', '', 'CONSUMO', 65.33, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71356, 66),
(747, 47, '2026-08-24', 38346, 38883, 260.19, NULL, 'C27 Puerto Sandino-Sebaco', 'Bombero2', NULL, '2026-08-24 13:37:54', NULL, NULL, 260.19, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 260.19, NULL, NULL, 'BLOQUEADO', 7.81, 9, '6', NULL, 1, 71357, 66),
(748, 42, '2026-08-24', 2133611, 2133778, 143.61, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-24 13:39:21', NULL, NULL, 143.61, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 143.6, NULL, NULL, 'BLOQUEADO', 4.39, 10, '6', NULL, 1, 71358, 66),
(749, 44, '2026-08-24', 51086, 51433, 139.73, NULL, 'C19 Puerto Corinto', 'Bombero2', NULL, '2026-08-24 20:48:51', NULL, NULL, 139.73, 'Kener José Martínez Zuniga ', '047-110589-0000Q', 'CONSUMO', 139.73, NULL, NULL, 'APROBADO', 9.4, 9, '6', NULL, 1, 71359, 66),
(750, 8, '2026-08-24', 662062, 662199, 43.62, NULL, 'JS-03 Carrizal', 'Bombero2', NULL, '2026-08-24 20:49:46', NULL, NULL, 43.62, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 43.62, NULL, NULL, 'APROBADO', 11.85, 15, '6', NULL, 1, 71360, 66),
(751, 79, '2026-08-24', 8968, 9120, 107.53, NULL, 'C43 Jinotepe-Competra', 'Bombero2', NULL, '2026-08-24 20:51:29', NULL, NULL, 107.53, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 107.53, NULL, NULL, 'BLOQUEADO', 5.35, 8, '6', NULL, 1, 71361, 66),
(752, 75, '2026-08-24', 0, 0, 800.00, NULL, '', 'Bombero2', NULL, '2026-08-24 20:52:04', NULL, NULL, 800, '', '', 'CONSUMO', 800, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71362, 66),
(753, 82, '2026-08-24', 0, 0, 54.01, NULL, '', 'Bombero2', NULL, '2026-08-24 20:52:25', NULL, NULL, 54.01, '', '', 'CONSUMO', 54.01, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71363, 66),
(754, 11, '2026-08-24', 299460, 299571, 44.40, NULL, 'LPG1-096 Managua-San Benito', 'Bombero2', NULL, '2026-08-24 20:53:23', NULL, NULL, 44.4, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 44.4, NULL, NULL, 'APROBADO', 9.42, 15, '6', NULL, 1, 71364, 66),
(755, 12, '2026-08-24', 333814, 333955, 84.55, NULL, 'LPG2-097 San Benito-Managua', 'Bombero2', NULL, '2026-08-24 20:54:21', NULL, NULL, 84.55, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 84.55, NULL, NULL, 'BLOQUEADO', 6.33, 15, '6', NULL, 1, 71365, 66),
(756, 80, '2026-08-24', 0, 0, 177.74, NULL, '', 'Bombero2', NULL, '2026-08-24 20:54:44', NULL, NULL, 177.74, '', '', 'CONSUMO', 177.74, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71366, 66),
(757, 61, '2026-08-24', 1141373, 1141928, 279.58, NULL, 'C61 Esteli-Sebaco', 'Bombero2', NULL, '2026-08-24 20:55:35', NULL, NULL, 279.58, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 279.57, NULL, NULL, 'BLOQUEADO', 7.52, 9, '6', NULL, 1, 71367, 66),
(758, 43, '2026-08-24', 75656, 76255, 288.57, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-08-24 20:56:32', NULL, NULL, 288.57, 'Moisés Isaac Gutiérrez Valerio ', '002-241193-0000D', 'CONSUMO', 288.57, NULL, NULL, 'BLOQUEADO', 7.85, 10, '6', NULL, 1, 71368, 66),
(759, 80, '2026-08-24', 0, 0, 100.33, NULL, '', 'Bombero2', NULL, '2026-08-24 20:56:54', NULL, NULL, 100.33, '', '', 'CONSUMO', 100.33, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71369, 66),
(760, 62, '2026-08-24', 52519, 53093, 209.62, NULL, 'C64 El Rama', 'Bombero2', NULL, '2026-08-24 20:57:55', NULL, NULL, 209.62, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 209.62, NULL, NULL, 'BLOQUEADO', 10.37, 9, '6', NULL, 1, 71370, 66),
(761, 64, '2026-08-24', 11300, 11330, 24.22, NULL, '', 'Bombero2', NULL, '2026-08-24 20:58:46', NULL, NULL, 24.22, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 24.21, NULL, NULL, 'APROBADO', 4.69, 9, '6', NULL, 1, 71371, 66),
(762, 57, '2026-08-24', 64693, 65430, 437.04, NULL, 'C56 El Rama-Granada', 'Bombero2', NULL, '2026-08-24 21:00:34', NULL, NULL, 437.04, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 437.04, NULL, NULL, 'BLOQUEADO', 6.38, 8, '6', NULL, 1, 71372, 66),
(763, 46, '2026-08-24', 244268, 245005, 400.39, NULL, 'C26 Rama-Granada', 'Bombero2', NULL, '2026-08-24 21:04:09', NULL, NULL, 400.39, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 400.39, NULL, NULL, 'BLOQUEADO', 6.96, 10, '6', NULL, 1, 71373, 66),
(764, 75, '2026-08-25', 0, 0, 7.26, NULL, '', 'Bombero2', NULL, '2026-08-25 10:09:53', NULL, NULL, 7.26, '', '', 'CONSUMO', 7.26, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71374, 68),
(765, 75, '2026-08-25', 0, 0, 14.58, NULL, '', 'Bombero2', NULL, '2026-08-25 10:18:18', NULL, NULL, 14.58, '', '', 'CONSUMO', 14.58, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71375, 68),
(766, 80, '2026-08-25', 0, 0, 198.72, NULL, '', 'Bombero2', NULL, '2026-08-25 11:09:39', NULL, NULL, 198.72, '', '', 'CONSUMO', 198.72, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71376, 68),
(767, 63, '2026-08-25', 32411, 32604, 90.54, NULL, 'C66 Locales-Martinez e Hijos', 'Bombero2', NULL, '2026-08-25 11:10:09', NULL, NULL, 90.54, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 90.54, NULL, NULL, 'APROBADO', 8.05, 9, '6', NULL, 1, 71377, 68),
(768, 71, '2026-08-25', 0, 0, 47.07, NULL, 'Kia 2700 M353553/Gestiones Varias', 'Bombero2', NULL, '2026-08-25 14:37:41', NULL, NULL, 47.07, '', '', 'CONSUMO', 47.07, NULL, NULL, 'APROBADO', 0, 45, '5', NULL, 1, 71378, 68),
(769, 79, '2026-08-25', 9120, 9730, 239.24, NULL, 'C43 Chinandega-Esteli', 'Bombero2', NULL, '2026-08-25 14:38:57', NULL, NULL, 239.24, 'Lester Enrique Sequeira Solís ', '201-030681-0001C', 'CONSUMO', 239.24, NULL, NULL, 'BLOQUEADO', 9.66, 8, '6', NULL, 1, 71379, 68),
(770, 40, '2026-08-25', 71906, 72409, 202.72, NULL, 'C62 Esteli-Leon', 'Bombero2', NULL, '2026-08-25 14:40:08', NULL, NULL, 202.72, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 202.72, NULL, NULL, 'APROBADO', 9.4, 9, '6', NULL, 1, 71380, 68),
(771, 48, '2026-08-25', 97216, 97814, 295.38, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-25 22:09:59', NULL, NULL, 295.38, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 295.38, NULL, NULL, 'BLOQUEADO', 7.67, 10, '6', NULL, 1, 71381, 67),
(772, 5, '2026-08-25', 401, 560, 79.50, NULL, 'C14 Prodecon', 'Bombero2', NULL, '2026-08-25 22:14:29', NULL, NULL, 79.5, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 79.5, NULL, NULL, 'BLOQUEADO', 7.59, 12, '6', NULL, 1, 71382, 67),
(773, 80, '2026-08-25', 0, 0, 41.75, NULL, '', 'Bombero2', NULL, '2026-08-25 22:15:01', NULL, NULL, 41.75, '', '', 'CONSUMO', 41.75, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71383, 67),
(774, 75, '2026-08-25', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-25 22:15:24', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71384, 67),
(775, 41, '2026-08-25', 179292, 179555, 115.96, NULL, '', 'Bombero2', NULL, '2026-08-25 22:16:29', NULL, NULL, 115.96, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 115.96, NULL, NULL, 'APROBADO', 8.58, 9, '6', NULL, 1, 71385, 67),
(776, 88, '2026-08-25', 3777, 4048, 143.51, NULL, 'C69 Locales', 'Bombero2', NULL, '2026-08-25 22:17:39', NULL, NULL, 143.51, 'Omar Patricio Osejo Amador ', '441-240882-0002F', 'CONSUMO', 143.51, NULL, NULL, 'BLOQUEADO', 7.16, 9, '6', NULL, 1, 71386, 67),
(777, 6, '2026-08-25', 48581, 49063, 117.66, NULL, 'C38 Ayote', 'Bombero2', NULL, '2026-08-25 22:18:49', NULL, NULL, 117.66, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 117.66, NULL, NULL, 'APROBADO', 15.51, 15, '6', NULL, 1, 71387, 67),
(778, 47, '2026-08-25', 38883, 39331, 225.04, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-25 22:19:49', NULL, NULL, 225.04, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 225.04, NULL, NULL, 'BLOQUEADO', 7.53, 9, '6', NULL, 1, 71388, 67),
(779, 64, '2026-08-25', 11330, 11460, 72.93, NULL, 'C68 Tipitapa-D Guerrero', 'Bombero2', NULL, '2026-08-25 22:21:01', NULL, NULL, 72.93, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 72.93, NULL, NULL, 'APROBADO', 6.75, 9, '6', NULL, 1, 71389, 67),
(780, 42, '2026-08-25', 2133778, 2133801, 218.45, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-25 22:24:58', NULL, NULL, 218.45, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 218.45, NULL, NULL, 'BLOQUEADO', 0.4, 10, '6', NULL, 1, 71390, 67),
(781, 62, '2026-08-25', 53093, 53600, 156.68, NULL, 'C64 Mulukuku', 'Bombero2', NULL, '2026-08-25 22:26:41', NULL, NULL, 156.68, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 156.68, NULL, NULL, 'BLOQUEADO', 12.24, 9, '6', NULL, 1, 71391, 67),
(782, 12, '2026-08-25', 333955, 334322, 126.47, NULL, 'LPG2-097 Chinandega-Cofradia', 'Bombero2', NULL, '2026-08-25 22:27:50', NULL, NULL, 126.47, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 126.47, NULL, NULL, 'BLOQUEADO', 10.97, 15, '6', NULL, 1, 71392, 67),
(783, 15, '2026-08-25', 18931, 19291, 75.20, NULL, 'JS-02 Leon', 'Bombero2', NULL, '2026-08-25 22:29:24', NULL, NULL, 75.2, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 75.2, NULL, NULL, 'BLOQUEADO', 18.11, 13, '6', NULL, 1, 71393, 67),
(784, 14, '2026-08-25', 26999, 26999, 14.98, NULL, 'JS-01 Relleno', 'Bombero2', NULL, '2026-08-25 22:33:32', NULL, NULL, 14.98, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 14.98, NULL, NULL, 'APROBADO', 0.08, 15, '6', NULL, 1, 71394, 67),
(785, 80, '2026-08-25', 0, 0, 135.20, NULL, '', 'Bombero2', NULL, '2026-08-25 22:34:13', NULL, NULL, 135.2, '', '', 'CONSUMO', 135.2, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71395, 67),
(787, 9, '2026-08-26', 17556, 17889, 96.16, NULL, 'C42 Granada-Leon', 'Bombero2', NULL, '2026-08-26 10:55:17', NULL, NULL, 96.16, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 96.16, NULL, NULL, 'APROBADO', 13.12, 15, '6', NULL, 1, 71397, 69),
(788, 88, '2026-08-26', 4048, 4250, 100.27, NULL, 'C69 Leon', 'Bombero2', NULL, '2026-08-26 15:14:43', NULL, NULL, 100.27, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 100.27, NULL, NULL, 'APROBADO', 7.62, 9, '6', NULL, 1, 71398, 69),
(789, 80, '2026-08-26', 0, 0, 179.28, NULL, '', 'Bombero2', NULL, '2026-08-26 15:16:03', NULL, NULL, 179.28, '', '', 'CONSUMO', 179.28, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71399, 69),
(790, 38, '2026-08-26', 86405, 86712, 141.23, NULL, 'C58 Puerto Corinto', 'Bombero2', NULL, '2026-08-26 15:18:33', NULL, NULL, 141.23, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 141.22, NULL, NULL, 'APROBADO', 8.23, 9, '6', NULL, 1, 71400, 69),
(791, 45, '2026-08-26', 88378, 89126, 367.69, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-26 15:21:02', NULL, NULL, 367.69, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 367.69, NULL, NULL, 'BLOQUEADO', 7.7, 10, '6', NULL, 1, 71401, 69),
(792, 13, '2026-08-26', 461465, 461802, 108.45, NULL, 'C49 Chinandega - Leon', 'Bombero2', NULL, '2026-08-26 15:22:30', NULL, NULL, 108.45, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 108.45, NULL, NULL, 'BLOQUEADO', 11.75, 15, '6', NULL, 1, 71402, 69),
(793, 42, '2026-08-26', 2133801, 2134058, 145.11, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-26 15:24:26', NULL, NULL, 145.11, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 145.11, NULL, NULL, 'BLOQUEADO', 6.71, 10, '6', NULL, 1, 71403, 69),
(794, 40, '2026-08-26', 72409, 72881, 204.94, NULL, 'C62 Sebaco', 'Bombero2', NULL, '2026-08-26 15:26:11', NULL, NULL, 204.94, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 204.94, NULL, NULL, 'APROBADO', 8.71, 9, '6', NULL, 1, 71404, 69),
(795, 60, '2026-08-26', 39216, 39408, 152.67, NULL, 'C60 Nindiri - Locales', 'Bombero2', NULL, '2026-08-26 15:29:35', NULL, NULL, 152.67, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 152.67, NULL, NULL, 'BLOQUEADO', 4.75, 9, '6', NULL, 1, 71405, 69),
(796, 6, '2026-08-26', 49063, 49190, 34.42, NULL, 'C38 Wiwili', 'Bombero2', NULL, '2026-08-26 15:32:19', NULL, NULL, 34.42, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 34.41, NULL, NULL, 'APROBADO', 13.97, 15, '6', NULL, 1, 71406, 69),
(797, 18, '2026-08-26', 97484, 97485, 3.79, NULL, 'C11 Relleno de filtro', 'Bombero2', NULL, '2026-08-26 16:15:25', NULL, NULL, 3.79, 'Bismarck Antonio Gutiérrez Obando ', '002-081291-0004Q', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 10, '6', NULL, 1, 71407, 70),
(798, 41, '2026-08-26', 179555, 179831, 137.87, NULL, 'C20 Matagalpa', 'Bombero2', NULL, '2026-08-26 16:19:04', NULL, NULL, 137.87, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 137.87, NULL, NULL, 'BLOQUEADO', 7.59, 9, '6', NULL, 1, 71408, 70),
(799, 39, '2026-08-26', 55267, 56135, 413.83, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-08-26 16:33:31', NULL, NULL, 413.83, 'Ulises Joel Bautista Gómez ', '001-140100-1011D', 'CONSUMO', 413.82, NULL, NULL, 'BLOQUEADO', 7.94, 9, '6', NULL, 1, 71409, 70),
(800, 80, '2026-08-26', 0, 0, 141.99, NULL, '', 'Bombero2', NULL, '2026-08-26 16:43:04', NULL, NULL, 141.99, '', '', 'CONSUMO', 141.99, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71410, 70),
(801, 63, '2026-08-26', 32604, 32976, 120.12, NULL, 'C66 Local-La Paz Centro', 'Bombero2', NULL, '2026-08-26 21:47:17', NULL, NULL, 120.12, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 120.12, NULL, NULL, 'BLOQUEADO', 11.72, 9, '6', NULL, 1, 71411, 70),
(802, 57, '2026-08-26', 65430, 65575, 120.44, NULL, 'C56 Montelimar', 'Bombero2', NULL, '2026-08-26 21:48:34', NULL, NULL, 120.44, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 120.44, NULL, NULL, 'BLOQUEADO', 4.55, 8, '6', NULL, 1, 71412, 70),
(803, 5, '2026-08-26', 560, 687, 50.83, NULL, 'C14 Puerto Sandino', 'Bombero2', NULL, '2026-08-26 21:49:57', NULL, NULL, 50.83, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 50.83, NULL, NULL, 'APROBADO', 9.48, 12, '6', NULL, 1, 71413, 70),
(804, 11, '2026-08-26', 299571, 299737, 66.18, NULL, 'LPG1-096 Managua', 'Bombero2', NULL, '2026-08-26 21:51:07', NULL, NULL, 66.18, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 66.18, NULL, NULL, 'BLOQUEADO', 9.52, 15, '6', NULL, 1, 71414, 70),
(805, 47, '2026-08-26', 39331, 39780, 222.44, NULL, 'C27 Puerto Sandino ', 'Bombero2', NULL, '2026-08-26 21:52:24', NULL, NULL, 222.44, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 222.44, NULL, NULL, 'BLOQUEADO', 7.64, 9, '6', NULL, 1, 71415, 70),
(806, 64, '2026-08-26', 11460, 11710, 119.41, NULL, 'C67 Proinco ', 'Bombero2', NULL, '2026-08-26 21:53:27', NULL, NULL, 119.41, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 119.41, NULL, NULL, 'APROBADO', 7.93, 9, '6', NULL, 1, 71416, 70),
(807, 12, '2026-08-26', 334322, 334376, 38.94, NULL, 'LPG2-097 Cofradia', 'Bombero2', NULL, '2026-08-26 21:54:43', NULL, NULL, 38.94, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 38.94, NULL, NULL, 'BLOQUEADO', 5.27, 15, '6', NULL, 1, 71417, 70),
(808, 43, '2026-08-26', 76255, 77004, 371.48, NULL, 'C18 Puerto Sandino ', 'Bombero2', NULL, '2026-08-26 21:56:06', NULL, NULL, 371.48, 'Moisés Isaac Gutiérrez Valerio ', '002-241193-0000D', 'CONSUMO', 371.48, NULL, NULL, 'BLOQUEADO', 7.63, 10, '6', NULL, 1, 71418, 70),
(809, 79, '2026-08-26', 9730, 10014, 180.85, NULL, 'C43 Jinotepe- Diriamba', 'Bombero2', NULL, '2026-08-26 21:57:12', NULL, NULL, 180.85, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 180.85, NULL, NULL, 'BLOQUEADO', 5.95, 8, '6', NULL, 1, 71419, 70),
(810, 78, '2026-08-26', 0, 0, 70.10, NULL, '', 'Bombero2', NULL, '2026-08-26 21:57:48', NULL, NULL, 70.1, '', '', 'CONSUMO', 70.1, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71420, 70),
(811, 9, '2026-08-26', 17889, 17992, 31.76, NULL, 'C42 Reyna del Sur', 'Bombero2', NULL, '2026-08-26 21:59:06', NULL, NULL, 31.76, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 31.76, NULL, NULL, 'APROBADO', 12.28, 15, '6', NULL, 1, 71421, 70),
(812, 59, '2026-08-27', 1363575, 1363753, 143.80, NULL, 'C59 Rubenia-Nejapa-Rubenia-Americano-Masaya ', 'Bombero2', NULL, '2026-08-27 14:25:11', NULL, NULL, 143.8, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 143.8, NULL, NULL, 'BLOQUEADO', 4.69, 9, '6', NULL, 1, 71422, 72),
(813, 51, '2026-08-27', 100352, 101053, 265.78, NULL, 'C37 Puerto Corinto', 'Bombero2', NULL, '2026-08-27 14:38:27', NULL, NULL, 265.78, 'Julio José Mendoza Ruíz ', '001-080300-1009B', 'CONSUMO', 265.78, NULL, NULL, 'APROBADO', 9.98, 10, '6', NULL, 1, 71423, 72),
(814, 76, '2026-08-27', 0, 0, 69.16, NULL, 'RAM 2500/Cuota Anibal Sevilla', 'Bombero2', NULL, '2026-08-27 14:40:34', NULL, NULL, 69.16, '', '', 'CONSUMO', 69.16, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71424, 72),
(815, 85, '2026-08-27', 0, 0, 57.81, NULL, 'Isuzu / Cuota Roger Vilchez ', 'Bombero2', NULL, '2026-08-27 14:42:39', NULL, NULL, 57.81, '', '', 'CONSUMO', 57.8, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71425, 72),
(816, 84, '2026-08-27', 0, 0, 3.79, NULL, 'Toyota L. / Cuota gestiones GCM', 'Bombero2', NULL, '2026-08-27 14:45:03', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71426, 72),
(817, 84, '2026-08-27', 0, 0, 41.60, NULL, 'Toyota L. / Cuota gestiones GCM ', 'Bombero2', NULL, '2026-08-27 14:47:09', NULL, NULL, 41.6, '', '', 'CONSUMO', 41.6, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71427, 72),
(818, 64, '2026-08-27', 11710, 11918, 87.44, NULL, 'C67 Miramontes-transporte Diaz ', 'Bombero2', NULL, '2026-08-27 14:55:05', NULL, NULL, 87.44, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 87.44, NULL, NULL, 'APROBADO', 9, 9, '6', NULL, 1, 71428, 73),
(819, 8, '2026-08-27', 662199, 662352, 39.58, NULL, 'AJS 03 Malacatoya', 'Bombero2', NULL, '2026-08-27 15:06:01', NULL, NULL, 39.58, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 39.58, NULL, NULL, 'APROBADO', 14.65, 15, '6', NULL, 1, 71429, 73),
(820, 80, '2026-08-27', 0, 0, 59.00, NULL, '', 'Bombero2', NULL, '2026-08-27 15:06:48', NULL, NULL, 59, '', '', 'CONSUMO', 59, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71430, 73),
(821, 42, '2026-08-27', 2134058, 2134358, 143.24, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-08-27 15:08:44', NULL, NULL, 143.24, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 143.24, NULL, NULL, 'BLOQUEADO', 7.92, 10, '6', NULL, 1, 71431, 73),
(822, 88, '2026-08-27', 4250, 4408, 105.98, NULL, 'C69 Managua', 'Bombero2', NULL, '2026-08-27 15:10:51', NULL, NULL, 105.98, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 105.98, NULL, NULL, 'BLOQUEADO', 5.64, 9, '6', NULL, 1, 71432, 73),
(823, 80, '2026-08-27', 0, 0, 174.54, NULL, '', 'Bombero2', NULL, '2026-08-27 15:16:16', NULL, NULL, 174.54, '', '', 'CONSUMO', 174.53, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71433, 73),
(824, 9, '2026-08-27', 17992, 18276, 77.32, NULL, 'C42 Mina la india ', 'Bombero2', NULL, '2026-08-27 15:18:18', NULL, NULL, 77.32, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 77.32, NULL, NULL, 'APROBADO', 13.91, 15, '6', NULL, 1, 71434, 73),
(825, 44, '2026-08-27', 51433, 51820, 265.92, NULL, 'C19 Puerto Sandino ', 'Bombero2', NULL, '2026-08-27 20:16:18', NULL, NULL, 265.92, 'Kener José Martínez Zuniga ', '047-110589-0000Q', 'CONSUMO', 265.92, NULL, NULL, 'BLOQUEADO', 5.5, 9, '6', NULL, 1, 71435, 73),
(826, 11, '2026-08-27', 299737, 299925, 62.86, NULL, 'LPG1-096 Managua', 'Bombero2', NULL, '2026-08-27 20:17:54', NULL, NULL, 62.86, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 62.86, NULL, NULL, 'APROBADO', 11.31, 15, '6', NULL, 1, 71436, 73),
(827, 80, '2026-08-27', 0, 0, 116.26, NULL, '', 'Bombero2', NULL, '2026-08-27 20:18:20', NULL, NULL, 116.26, '', '', 'CONSUMO', 116.26, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71437, 73),
(828, 12, '2026-08-27', 334376, 334476, 53.01, NULL, 'LPG2-097 Managua', 'Bombero2', NULL, '2026-08-27 20:19:30', NULL, NULL, 53.01, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 53.01, NULL, NULL, 'BLOQUEADO', 7.13, 15, '6', NULL, 1, 71438, 73),
(829, 64, '2026-08-27', 11918, 11950, 8.73, NULL, 'C67 Prueba de Manejo', 'Bombero2', NULL, '2026-08-27 20:20:17', NULL, NULL, 8.73, '', '', 'CONSUMO', 8.73, NULL, NULL, 'APROBADO', 13.87, 9, '6', NULL, 1, 71439, 73),
(830, 47, '2026-08-27', 39780, 40228, 214.85, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-27 20:21:15', NULL, NULL, 214.85, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 214.85, NULL, NULL, 'BLOQUEADO', 7.9, 9, '6', NULL, 1, 71440, 73),
(831, 79, '2026-08-27', 10014, 10335, 117.87, NULL, 'C43 Chinandega-Las Colinas', 'Bombero2', NULL, '2026-08-27 20:22:27', NULL, NULL, 117.87, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 117.87, NULL, NULL, 'BLOQUEADO', 10.3, 8, '6', NULL, 1, 71441, 73),
(832, 54, '2026-08-27', 42445, 42894, 235.14, NULL, 'C45 Puerto Sandino', 'Bombero2', NULL, '2026-08-27 20:23:32', NULL, NULL, 235.14, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 235.13, NULL, NULL, 'BLOQUEADO', 7.23, 8, '6', NULL, 1, 71442, 73),
(833, 48, '2026-08-27', 97814, 98263, 218.75, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-08-27 20:24:34', NULL, NULL, 218.75, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 218.75, NULL, NULL, 'BLOQUEADO', 7.78, 10, '6', NULL, 1, 71443, 73),
(834, 61, '2026-08-27', 1141928, 1142320, 181.88, NULL, 'C61 Santo Tomas ', 'Bombero2', NULL, '2026-08-27 22:06:53', NULL, NULL, 181.88, 'Kener José Martínez Zuniga ', '047-110589-0000Q', 'CONSUMO', 181.88, NULL, NULL, 'APROBADO', 8.16, 9, '6', NULL, 1, 71444, 73),
(835, 61, '2026-08-28', 1142320, 1142524, 101.39, NULL, 'C61 Leon', 'Bombero2', NULL, '2026-08-28 10:09:21', NULL, NULL, 101.39, 'Kener José Martínez Zuniga ', '047-110589-0000Q', 'CONSUMO', 101.39, NULL, NULL, 'APROBADO', 7.62, 9, '6', NULL, 1, 71445, 75),
(836, 75, '2026-08-28', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-08-28 10:10:33', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71446, 75),
(837, 6, '2026-08-28', 49190, 49751, 137.78, NULL, 'C38 Agricola miramontes ', 'Bombero2', NULL, '2026-08-28 10:11:44', NULL, NULL, 137.78, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 137.78, NULL, NULL, 'APROBADO', 15.41, 15, '6', NULL, 1, 71447, 75),
(838, 63, '2026-08-28', 32976, 33164, 87.61, NULL, 'C66 Locales - Martinez e Hijos ', 'Bombero2', NULL, '2026-08-28 10:16:22', NULL, NULL, 87.61, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 87.61, NULL, NULL, 'APROBADO', 8.14, 9, '6', NULL, 1, 71448, 75),
(839, 57, '2026-08-28', 65575, 66265, 381.58, NULL, 'C56 El rama - Las mercedes ', 'Bombero2', NULL, '2026-08-28 14:48:20', NULL, NULL, 381.58, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 381.58, NULL, NULL, 'BLOQUEADO', 6.84, 8, '6', NULL, 1, 71449, 74),
(840, 40, '2026-08-28', 72881, 73493, 297.00, NULL, 'C62 Sebaco - Matagalpa - Competra', 'Bombero2', NULL, '2026-08-28 14:55:22', NULL, NULL, 297, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 297, NULL, NULL, 'BLOQUEADO', 7.8, 9, '6', NULL, 1, 71450, 74),
(841, 8, '2026-08-28', 662352, 662639, 61.75, NULL, 'AJS-03 Villaquil Chinandega, Gustavo Rocha ', 'Bombero2', NULL, '2026-08-28 15:02:25', NULL, NULL, 61.75, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 61.75, NULL, NULL, 'APROBADO', 17.56, 15, '6', NULL, 1, 71451, 75),
(842, 80, '2026-08-28', 0, 0, 106.55, NULL, '', 'Bombero2', NULL, '2026-08-28 15:03:04', NULL, NULL, 106.55, '', '', 'CONSUMO', 106.55, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71452, 75),
(843, 59, '2026-08-28', 1363753, 1364046, 141.69, NULL, 'C59 Chinandega - Loyola ', 'Bombero2', NULL, '2026-08-28 15:04:50', NULL, NULL, 141.69, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 141.69, NULL, NULL, 'APROBADO', 7.84, 9, '6', NULL, 1, 71453, 75),
(844, 75, '2026-08-28', 0, 0, 966.18, NULL, '', 'Bombero2', NULL, '2026-08-28 15:05:36', NULL, NULL, 966.18, '', '', 'CONSUMO', 966.18, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71454, 75),
(845, 5, '2026-08-28', 687, 1537, 290.53, NULL, 'C14 Managua', 'Bombero2', NULL, '2026-08-28 15:12:07', NULL, NULL, 290.53, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 290.52, NULL, NULL, 'BLOQUEADO', 11.07, 12, '6', NULL, 1, 71455, 75),
(846, 11, '2026-08-28', 299925, 300208, 73.07, NULL, 'C 096 Chinandega - Leon - Chichigalpa', 'Bombero2', NULL, '2026-08-28 16:10:22', NULL, NULL, 73.07, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 73.07, NULL, NULL, 'APROBADO', 14.65, 15, '6', NULL, 1, 71456, 75),
(847, 60, '2026-08-28', 39408, 39486, 52.49, NULL, 'C60 Locales', 'Bombero2', NULL, '2026-08-28 16:11:38', NULL, NULL, 52.49, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 52.49, NULL, NULL, 'BLOQUEADO', 5.62, 9, '6', NULL, 1, 71457, 75),
(848, 82, '2026-08-28', 0, 0, 605.60, NULL, '', 'Bombero2', NULL, '2026-08-28 20:45:58', NULL, NULL, 605.6, '', '', 'CONSUMO', 605.6, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71458, 75),
(849, 88, '2026-08-28', 4408, 4680, 180.32, NULL, 'C69 Locales', 'Bombero2', NULL, '2026-08-28 20:47:23', NULL, NULL, 180.32, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 180.32, NULL, NULL, 'BLOQUEADO', 5.71, 9, '6', NULL, 1, 71459, 75),
(850, 64, '2026-08-28', 9894, 10381, 218.31, NULL, 'C67 El ayote', 'Bombero2', NULL, '2026-08-28 20:50:43', NULL, NULL, 218.31, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 218.31, NULL, NULL, 'APROBADO', 8.44, 9, '6', NULL, 1, 71460, 75),
(851, 45, '2026-08-28', 89126, 89874, 365.12, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-08-28 20:52:24', NULL, NULL, 365.12, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 365.12, NULL, NULL, 'BLOQUEADO', 7.75, 10, '6', NULL, 1, 71461, 75),
(852, 62, '2026-08-28', 53600, 53888, 122.11, NULL, 'C64 Wiwili', 'Bombero2', NULL, '2026-08-28 20:53:20', NULL, NULL, 122.11, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 122.11, NULL, NULL, 'APROBADO', 8.92, 9, '6', NULL, 1, 71462, 75),
(853, 82, '2026-08-28', 0, 0, 611.65, NULL, '', 'Bombero2', NULL, '2026-08-28 20:53:49', NULL, NULL, 611.65, '', '', 'CONSUMO', 611.65, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71463, 75),
(854, 90, '2026-08-28', 0, 0, 1854.65, NULL, '', 'Bombero2', NULL, '2026-08-28 20:54:32', NULL, NULL, 1854.65, '', '', 'CONSUMO', 1854.65, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71464, 75),
(855, 80, '2026-08-28', 0, 0, 164.47, NULL, '', 'Bombero2', NULL, '2026-08-28 20:54:57', NULL, NULL, 164.47, '', '', 'CONSUMO', 164.47, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71465, 75),
(856, 12, '2026-08-28', 334476, 334568, 66.64, NULL, 'LPG2-097 Managua', 'Bombero2', NULL, '2026-08-28 20:56:18', NULL, NULL, 66.64, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 66.64, NULL, NULL, 'BLOQUEADO', 5.23, 15, '6', NULL, 1, 71466, 74),
(857, 54, '2026-08-28', 42894, 43194, 173.76, NULL, 'C45 Puerto Sandino', 'Bombero2', NULL, '2026-08-28 21:09:39', NULL, NULL, 173.76, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 173.75, NULL, NULL, 'BLOQUEADO', 6.53, 8, '6', NULL, 1, 71467, 74);
INSERT INTO `registro_combustible` (`id`, `id_vehiculo`, `fecha_registro`, `kilometraje_anterior`, `kilometraje_actual`, `cantidad_litros`, `id_motivo`, `observaciones`, `usuario_crea`, `usuario_edita`, `fecha_actualiza`, `id_tipo_motivo`, `id_direccion`, `medicion`, `nombreCliente`, `dni`, `tipo`, `combustible_tanque`, `monto_nio`, `monto_usd`, `estado`, `rendimiento`, `rendimiento_promedio`, `referencia1`, `referencia2`, `enviado`, `numero_ingreso_sag`, `id_lectura`) VALUES
(858, 79, '2026-08-28', 10335, 10461, 90.33, NULL, 'C43 Centro Comercial-Batahola-Competra', 'Bombero2', NULL, '2026-08-28 21:21:14', NULL, NULL, 90.33, 'Lester Enrique Sequeira Solís ', '201-030681-0001C', 'CONSUMO', 90.33, NULL, NULL, 'BLOQUEADO', 5.27, 8, '6', NULL, 1, 71468, 74),
(859, 80, '2026-08-28', 0, 0, 173.18, NULL, '', 'Bombero2', NULL, '2026-08-28 22:21:55', NULL, NULL, 173.18, '', '', 'CONSUMO', 173.18, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71469, 74),
(860, 75, '2026-08-29', 0, 0, 921.74, NULL, '', 'Bombero2', NULL, '2026-08-29 10:46:18', NULL, NULL, 921.74, '', '', 'CONSUMO', 921.74, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71470, 76),
(861, 75, '2026-08-29', 0, 0, 50.00, NULL, '', 'Bombero2', NULL, '2026-08-29 10:46:48', NULL, NULL, 50, '', '', 'CONSUMO', 50, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71471, 77),
(862, 75, '2026-08-29', 0, 0, 3.78, NULL, '', 'Bombero2', NULL, '2026-08-29 10:47:18', NULL, NULL, 3.78, '', '', 'CONSUMO', 3.78, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71472, 76),
(863, 8, '2026-08-29', 662639, 662829, 42.71, NULL, 'AJS 3 Miramonte', 'Bombero2', NULL, '2026-08-29 10:49:30', NULL, NULL, 42.71, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 42.7, NULL, NULL, 'APROBADO', 16.83, 15, '6', NULL, 1, 71473, 76),
(864, 64, '2026-08-29', 10381, 10451, 35.95, NULL, 'C67 Colonia Rubenia ', 'Bombero2', NULL, '2026-08-29 10:51:13', NULL, NULL, 35.95, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 35.95, NULL, NULL, 'APROBADO', 7.37, 9, '6', NULL, 1, 71474, 76),
(865, 75, '2026-08-29', 0, 0, 8.79, NULL, '', 'Bombero2', NULL, '2026-08-29 22:27:45', NULL, NULL, 8.79, '', '', 'CONSUMO', 8.79, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71475, 76),
(866, 68, '2026-08-29', 0, 0, 42.46, NULL, 'Toyota H/Gestión Taller', 'Bombero2', NULL, '2026-08-29 22:28:20', NULL, NULL, 42.46, '', '', 'CONSUMO', 42.46, NULL, NULL, 'APROBADO', 0, 50, '5', NULL, 1, 71476, 76),
(867, 9, '2026-08-29', 18276, 18406, 41.55, NULL, 'C42 Coogrant', 'Bombero2', NULL, '2026-08-29 22:33:45', NULL, NULL, 41.55, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 41.55, NULL, NULL, 'APROBADO', 11.84, 15, '6', NULL, 1, 71477, 76),
(868, 12, '2026-08-29', 334568, 334660, 33.73, NULL, 'LPG2-097 San Benito', 'Bombero2', NULL, '2026-08-29 22:35:32', NULL, NULL, 33.73, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 33.73, NULL, NULL, 'APROBADO', 10.37, 15, '6', NULL, 1, 71478, 76),
(869, 41, '2026-08-29', 179831, 179985, 110.40, NULL, 'C20 Montelimar', 'Bombero2', NULL, '2026-08-29 22:38:59', NULL, NULL, 110.4, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 110.39, NULL, NULL, 'BLOQUEADO', 5.27, 9, '6', NULL, 1, 71479, 77),
(870, 15, '2026-08-29', 19291, 19553, 64.17, NULL, 'JS-02 Boaco', 'Bombero2', NULL, '2026-08-29 22:40:57', NULL, NULL, 64.17, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 64.17, NULL, NULL, 'APROBADO', 15.44, 13, '6', NULL, 1, 71480, 77),
(871, 75, '2026-08-29', 0, 0, 6.11, NULL, '', 'Bombero2', NULL, '2026-08-29 22:41:44', NULL, NULL, 6.11, '', '', 'CONSUMO', 6.11, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71481, 76),
(872, 6, '2026-08-29', 49751, 49843, 30.34, NULL, 'C38 Llansa-Plantel Central', 'Bombero2', NULL, '2026-08-29 22:42:49', NULL, NULL, 30.34, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 30.34, NULL, NULL, 'APROBADO', 11.53, 15, '6', NULL, 1, 71482, 76),
(873, 11, '2026-08-29', 300208, 300400, 57.88, NULL, 'LPG1-096 Managua', 'Bombero2', NULL, '2026-08-29 22:45:10', NULL, NULL, 57.88, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 57.88, NULL, NULL, 'APROBADO', 12.52, 15, '6', NULL, 1, 71483, 76),
(874, 59, '2026-08-29', 1364046, 1364170, 87.89, NULL, 'C59 Diriamba-Inmaculada-Centro Comercial', 'Bombero2', NULL, '2026-08-29 22:46:21', NULL, NULL, 87.89, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 87.89, NULL, NULL, 'BLOQUEADO', 5.33, 9, '6', NULL, 1, 71484, 76),
(875, 60, '2026-08-29', 39486, 39538, 46.14, NULL, 'C60 Managua', 'Bombero2', NULL, '2026-08-29 22:47:39', NULL, NULL, 46.14, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 46.14, NULL, NULL, 'BLOQUEADO', 4.28, 9, '6', NULL, 1, 71485, 76),
(876, 61, '2026-08-29', 1142524, 1142758, 141.77, NULL, 'C61 Leon-Linda Vista-Santo Domingo', 'Bombero2', NULL, '2026-08-29 22:48:47', NULL, NULL, 141.77, 'Kener José Martínez Zuniga ', '047-110589-0000Q', 'CONSUMO', 141.77, NULL, NULL, 'BLOQUEADO', 6.25, 9, '6', NULL, 1, 71486, 76),
(877, 62, '2026-08-29', 53888, 54027, 53.11, NULL, 'C64 Pavinic', 'Bombero2', NULL, '2026-08-29 22:49:43', NULL, NULL, 53.11, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 53.11, NULL, NULL, 'APROBADO', 9.87, 9, '6', NULL, 1, 71487, 76),
(878, 9, '2026-08-29', 18406, 18551, 31.00, NULL, 'C42 Teodoro Picado', 'Bombero2', NULL, '2026-08-29 22:51:56', NULL, NULL, 31, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 31, NULL, NULL, 'APROBADO', 17.72, 15, '6', NULL, 1, 71488, 76),
(879, 79, '2026-08-29', 10461, 10586, 67.76, NULL, 'C43 Nejapa-Gueguense-Masaya', 'Bombero2', NULL, '2026-08-29 22:53:25', NULL, NULL, 67.76, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 67.75, NULL, NULL, 'APROBADO', 6.97, 8, '6', NULL, 1, 71489, 76),
(880, 40, '2026-08-29', 73493, 73904, 170.21, NULL, 'C62 Leon-Sebaco', 'Bombero2', NULL, '2026-08-29 22:54:48', NULL, NULL, 170.21, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 170.21, NULL, NULL, 'APROBADO', 9.13, 9, '6', NULL, 1, 71490, 76),
(881, 80, '2026-08-29', 0, 0, 144.70, NULL, '', 'Bombero2', NULL, '2026-08-29 22:55:16', NULL, NULL, 144.7, '', '', 'CONSUMO', 144.7, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71491, 76),
(882, 63, '2026-08-29', 33164, 33670, 185.18, NULL, 'C66 Wiwili', 'Bombero2', NULL, '2026-08-29 22:56:57', NULL, NULL, 185.18, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 185.18, NULL, NULL, 'BLOQUEADO', 10.34, 9, '6', NULL, 1, 71492, 76),
(883, 5, '2026-08-29', 1537, 1692, 69.69, NULL, 'C14 Prodecon', 'Bombero2', NULL, '2026-08-29 23:00:44', NULL, NULL, 69.69, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 69.69, NULL, NULL, 'BLOQUEADO', 8.44, 12, '6', NULL, 1, 71493, 76),
(884, 80, '2026-08-31', 0, 0, 123.86, NULL, '', 'Bombero2', NULL, '2026-08-31 08:55:41', NULL, NULL, 123.86, '', '', 'CONSUMO', 123.86, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71494, 78),
(885, 75, '2026-08-31', 0, 0, 371.61, NULL, '', 'Bombero2', NULL, '2026-08-31 08:56:15', NULL, NULL, 371.61, '', '', 'CONSUMO', 371.61, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71495, 78),
(886, 57, '2026-08-31', 66265, 66393, 18.93, NULL, 'C56 Rescate Matagalpa ', 'Bombero2', NULL, '2026-08-31 08:59:54', NULL, NULL, 18.93, '', '', 'CONSUMO', 18.93, NULL, NULL, 'BLOQUEADO', 25.68, 8, '6', NULL, 1, 71496, 78),
(887, 87, '2026-08-31', 0, 0, 37.69, NULL, 'Toyota FT Cuota ', 'Bombero2', NULL, '2026-08-31 12:42:18', NULL, NULL, 37.69, '', '', 'CONSUMO', 37.69, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71497, 78),
(888, 72, '2026-08-31', 0, 0, 34.79, NULL, 'KIA 30005 - Entrega de productos ', 'Bombero2', NULL, '2026-08-31 16:10:48', NULL, NULL, 34.79, '', '', 'CONSUMO', 34.79, NULL, NULL, 'APROBADO', 0, 45, '5', NULL, 1, 71498, 78),
(889, 73, '2026-08-31', 0, 0, 54.74, NULL, 'Mitsubishe - Relleno', 'Bombero2', NULL, '2026-08-31 16:11:51', NULL, NULL, 54.74, '', '', 'CONSUMO', 54.74, NULL, NULL, 'APROBADO', 0, 40, '5', NULL, 1, 71499, 78),
(890, 61, '2026-08-31', 1142758, 1142764, 3.79, NULL, 'C61 - Relleno de filtro ', 'Bombero2', NULL, '2026-08-31 16:13:49', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 38.7, 9, '6', NULL, 1, 71500, 78),
(891, 80, '2026-08-31', 0, 0, 33.46, NULL, '', 'Bombero2', NULL, '2026-08-31 16:14:15', NULL, NULL, 33.46, '', '', 'CONSUMO', 33.46, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71501, 78),
(892, 14, '2026-08-31', 26999, 27876, 132.58, NULL, 'AJS 01 Bonanza ', 'Bombero2', NULL, '2026-08-31 16:18:15', NULL, NULL, 132.58, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 132.58, NULL, NULL, 'BLOQUEADO', 25.03, 15, '6', NULL, 1, 71502, 78),
(893, 47, '2026-08-31', 40228, 40526, 156.01, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-08-31 16:21:09', NULL, NULL, 156.01, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 156.01, NULL, NULL, 'BLOQUEADO', 7.23, 9, '6', NULL, 1, 71503, 78),
(894, 76, '2026-08-31', 0, 0, 93.16, NULL, 'Don Anival - Cuota ', 'Bombero2', NULL, '2026-08-31 16:36:03', NULL, NULL, 93.16, '', '', 'CONSUMO', 93.16, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71504, 78),
(896, 64, '2026-08-31', 10452, 10642, 92.86, NULL, 'C67 Malacatuya - Miramonte - Llansa', 'Bombero2', NULL, '2026-08-31 16:39:40', NULL, NULL, 92.86, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 92.86, NULL, NULL, 'APROBADO', 7.76, 9, '6', NULL, 1, 71506, 78),
(897, 80, '2026-08-31', 0, 0, 155.07, NULL, '', 'Bombero2', NULL, '2026-08-31 16:41:06', NULL, NULL, 155.07, '', '', 'CONSUMO', 155.07, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71507, 78),
(898, 41, '2026-08-31', 179985, 180381, 203.38, NULL, 'C20 Santo Tomas ', 'Bombero2', NULL, '2026-08-31 21:42:21', NULL, NULL, 203.38, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 203.38, NULL, NULL, 'BLOQUEADO', 7.38, 9, '6', NULL, 1, 71508, 78),
(899, 40, '2026-08-31', 73904, 74404, 212.76, NULL, 'C62 Sebaco', 'Bombero2', NULL, '2026-08-31 21:43:39', NULL, NULL, 212.76, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 212.76, NULL, NULL, 'APROBADO', 8.9, 9, '6', NULL, 1, 71509, 78),
(900, 80, '2026-08-31', 0, 0, 225.16, NULL, '', 'Bombero2', NULL, '2026-08-31 21:44:05', NULL, NULL, 225.16, '', '', 'CONSUMO', 225.16, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71510, 78),
(901, 57, '2026-08-31', 66393, 66549, 199.34, NULL, 'C56 Matagalpa', 'Bombero2', NULL, '2026-08-31 21:45:47', NULL, NULL, 199.34, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 199.34, NULL, NULL, 'BLOQUEADO', 2.96, 8, '6', NULL, 1, 71511, 78),
(902, 55, '2026-09-01', 18804, 19310, 333.57, NULL, 'C46 Puerto Sandino', 'Bombero2', NULL, '2026-09-01 14:11:12', NULL, NULL, 333.57, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 333.56, NULL, NULL, 'BLOQUEADO', 5.74, 8, '6', NULL, 1, 71513, 81),
(903, 8, '2026-09-01', 662829, 663074, 73.52, NULL, 'AJS 03 Reina del sur - Martínez e Hijos', 'Bombero2', NULL, '2026-09-01 14:13:03', NULL, NULL, 73.52, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 73.52, NULL, NULL, 'APROBADO', 12.63, 15, '6', NULL, 1, 71514, 80),
(904, 75, '2026-09-01', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-09-01 14:13:35', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71515, 80),
(905, 78, '2026-09-01', 0, 0, 68.44, NULL, '', 'Bombero2', NULL, '2026-09-01 14:14:25', NULL, NULL, 68.44, '', '', 'CONSUMO', 68.44, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71516, 80),
(906, 39, '2026-09-01', 56135, 56136, 3.79, NULL, 'C65 Relleno de filtro', 'Bombero2', NULL, '2026-09-01 14:15:56', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 9, '6', NULL, 1, 71517, 80),
(907, 75, '2026-09-01', 0, 0, 6.58, NULL, '', 'Bombero2', NULL, '2026-09-01 14:16:25', NULL, NULL, 6.58, '', '', 'CONSUMO', 6.58, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71518, 80),
(908, 64, '2026-09-01', 10642, 10827, 73.18, NULL, 'C67 San Benito - Cobran Granada - Comasa', 'Bombero2', NULL, '2026-09-01 14:18:26', NULL, NULL, 73.18, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 73.18, NULL, NULL, 'APROBADO', 9.56, 9, '6', NULL, 1, 71519, 80),
(909, 75, '2026-09-01', 0, 0, 6.49, NULL, '', 'Bombero2', NULL, '2026-09-01 14:18:51', NULL, NULL, 6.49, '', '', 'CONSUMO', 6.49, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71520, 80),
(910, 71, '2026-09-01', 0, 0, 51.09, NULL, 'Compras ', 'Bombero2', NULL, '2026-09-01 14:19:32', NULL, NULL, 51.09, '', '', 'CONSUMO', 51.09, NULL, NULL, 'APROBADO', 0, 45, '5', NULL, 1, 71521, 80),
(911, 38, '2026-09-01', 86712, 86791, 39.01, NULL, 'C58 Managua', 'Bombero2', NULL, '2026-09-01 14:21:15', NULL, NULL, 39.01, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 39.01, NULL, NULL, 'APROBADO', 7.69, 9, '6', NULL, 1, 71522, 80),
(912, 80, '2026-09-01', 0, 0, 117.36, NULL, '', 'Bombero2', NULL, '2026-09-01 14:21:53', NULL, NULL, 117.36, '', '', 'CONSUMO', 117.36, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71523, 80),
(913, 9, '2026-09-01', 18551, 18705, 46.00, NULL, 'C42 San Martin', 'Bombero2', NULL, '2026-09-01 14:23:12', NULL, NULL, 46, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 46, NULL, NULL, 'APROBADO', 12.63, 15, '6', NULL, 1, 71524, 80),
(914, 8, '2026-09-01', 663074, 663192, 30.61, NULL, 'AJS 03 Carrizal ', 'Bombero2', NULL, '2026-09-01 14:27:50', NULL, NULL, 30.61, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 30.61, NULL, NULL, 'APROBADO', 14.55, 15, '6', NULL, 1, 71525, 80),
(915, 48, '2026-09-01', 98263, 98862, 299.93, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-09-01 14:32:08', NULL, NULL, 299.93, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 299.93, NULL, NULL, 'BLOQUEADO', 7.56, 10, '6', NULL, 1, 71526, 80),
(916, 15, '2026-09-01', 19553, 19764, 49.47, NULL, 'AJS02 Leon - Local', 'Bombero2', NULL, '2026-09-01 14:33:39', NULL, NULL, 49.47, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 49.47, NULL, NULL, 'APROBADO', 16.16, 13, '6', NULL, 1, 71527, 80),
(917, 75, '2026-09-01', 0, 0, 20.00, NULL, '', 'Bombero2', NULL, '2026-09-01 14:34:04', NULL, NULL, 20, '', '', 'CONSUMO', 20, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71528, 80),
(918, 11, '2026-09-01', 300400, 300684, 76.56, NULL, 'C096 Chinandega Chichigalpa', 'Bombero2', NULL, '2026-09-01 14:36:06', NULL, NULL, 76.56, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 76.56, NULL, NULL, 'APROBADO', 14.04, 15, '6', NULL, 1, 71529, 80),
(919, 42, '2026-09-01', 2134358, 2134956, 287.96, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-09-01 16:26:44', NULL, NULL, 287.96, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 287.96, NULL, NULL, 'BLOQUEADO', 7.86, 10, '6', NULL, 1, 71530, 80),
(920, 75, '2026-09-01', 0, 0, 6.48, NULL, '', 'Bombero2', NULL, '2026-09-01 16:27:10', NULL, NULL, 6.48, '', '', 'CONSUMO', 6.48, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71531, 80),
(921, 88, '2026-09-01', 4680, 5021, 275.29, NULL, 'C69 Locales', 'Bombero2', NULL, '2026-09-01 16:32:23', NULL, NULL, 275.29, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 275.29, NULL, NULL, 'BLOQUEADO', 4.68, 9, '6', NULL, 1, 71532, 80),
(922, 14, '2026-09-01', 27876, 28387, 133.15, NULL, 'AJS01 El Ayote', 'Bombero2', NULL, '2026-09-01 16:34:02', NULL, NULL, 133.15, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 133.15, NULL, NULL, 'APROBADO', 14.52, 15, '6', NULL, 1, 71533, 80),
(923, 6, '2026-09-01', 49843, 50266, 101.06, NULL, 'C38 Santo Domingo - Chontales ', 'Bombero2', NULL, '2026-09-01 16:35:27', NULL, NULL, 101.06, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 101.06, NULL, NULL, 'APROBADO', 15.84, 15, '6', NULL, 1, 71534, 80),
(924, 75, '2026-09-01', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-09-01 16:36:01', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71535, 80),
(925, 59, '2026-09-01', 1364170, 1364421, 201.30, NULL, 'C59 Locales-Masaya', 'Bombero2', NULL, '2026-09-01 22:18:14', NULL, NULL, 201.3, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 201.29, NULL, NULL, 'BLOQUEADO', 4.73, 9, '6', NULL, 1, 71536, 80),
(926, 85, '2026-09-01', 0, 0, 44.94, NULL, '', 'Bombero2', NULL, '2026-09-01 22:18:40', NULL, NULL, 44.94, '', '', 'CONSUMO', 44.94, NULL, NULL, 'APROBADO', 0, 0, '5', NULL, 1, 71537, 80),
(927, 44, '2026-09-01', 51820, 51821, 0.10, NULL, 'C19 Chequeo de combustible', 'Bombero2', NULL, '2026-09-01 22:19:40', NULL, NULL, 0.1, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 0.1, NULL, NULL, 'APROBADO', 37.85, 9, '6', NULL, 1, 71538, 80),
(928, 39, '2026-09-01', 56136, 56719, 286.49, NULL, 'C65 Puerto Sandino ', 'Bombero2', NULL, '2026-09-01 22:20:50', NULL, NULL, 286.49, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 286.49, NULL, NULL, 'BLOQUEADO', 7.7, 9, '6', NULL, 1, 71539, 80),
(929, 47, '2026-09-01', 40526, 40974, 218.07, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-09-01 22:22:21', NULL, NULL, 218.07, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 218.06, NULL, NULL, 'BLOQUEADO', 7.78, 9, '6', NULL, 1, 71540, 80),
(930, 62, '2026-09-01', 54027, 54423, 169.50, NULL, 'C64 Finca el ancla ', 'Bombero2', NULL, '2026-09-01 22:23:34', NULL, NULL, 169.5, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 169.5, NULL, NULL, 'APROBADO', 8.84, 9, '6', NULL, 1, 71541, 80),
(931, 80, '2026-09-01', 0, 0, 118.13, NULL, '', 'Bombero2', NULL, '2026-09-01 22:23:52', NULL, NULL, 118.13, '', '', 'CONSUMO', 118.13, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71542, 80),
(932, 12, '2026-09-01', 334660, 334821, 78.90, NULL, 'LPG2-097 Managua - San Benito', 'Bombero2', NULL, '2026-09-01 22:25:25', NULL, NULL, 78.9, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 78.9, NULL, NULL, 'BLOQUEADO', 7.74, 15, '6', NULL, 1, 71543, 80),
(933, 61, '2026-09-01', 1142764, 1142962, 140.94, NULL, 'C61 Coyotepe-Diriamba', 'Bombero2', NULL, '2026-09-01 22:26:52', NULL, NULL, 140.94, 'Juan Rafael Gaitán Chévez ', '121-231082-0003C', 'CONSUMO', 140.94, NULL, NULL, 'BLOQUEADO', 4.43, 9, '6', NULL, 1, 71544, 80),
(934, 55, '2026-09-01', 19310, 19460, 147.11, NULL, 'C46 Puerto Sandino', 'Bombero2', NULL, '2026-09-01 22:27:50', NULL, NULL, 147.11, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 147.11, NULL, NULL, 'BLOQUEADO', 3.85, 8, '6', NULL, 1, 71545, 80),
(935, 79, '2026-09-01', 10586, 11204, 279.90, NULL, 'C43 Milton Mendoza', 'Bombero2', NULL, '2026-09-01 22:28:48', NULL, NULL, 279.9, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 279.89, NULL, NULL, 'APROBADO', 8.35, 8, '6', NULL, 1, 71546, 80),
(936, 43, '2026-09-01', 77004, 77603, 301.29, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-09-01 22:29:49', NULL, NULL, 301.29, 'Moisés Isaac Gutiérrez Valerio ', '002-241193-0000D', 'CONSUMO', 301.29, NULL, NULL, 'BLOQUEADO', 7.53, 10, '6', NULL, 1, 71547, 80),
(937, 11, '2026-09-01', 300684, 300803, 37.22, NULL, 'LPG1-096 Managua - San Benito', 'Bombero2', NULL, '2026-09-01 22:30:44', NULL, NULL, 37.22, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 37.22, NULL, NULL, 'APROBADO', 12.08, 15, '6', NULL, 1, 71548, 80),
(938, 60, '2026-09-01', 39538, 39707, 116.91, NULL, 'C60 Managua-Granada', 'Bombero2', NULL, '2026-09-01 22:32:22', NULL, NULL, 116.91, 'Edgard Eduardo Velásquez Gutiérrez ', '001-070705-1057A', 'CONSUMO', 116.91, NULL, NULL, 'BLOQUEADO', 5.48, 9, '6', NULL, 1, 71549, 80),
(940, 58, '2026-09-02', 59079, 59080, 11.36, NULL, 'C57 Relleno de filtro', 'Bombero2', NULL, '2026-09-02 12:49:45', NULL, NULL, 11.36, '', '', 'CONSUMO', 11.36, NULL, NULL, 'APROBADO', 0.33, 9, '6', NULL, 1, 71551, 82),
(941, 79, '2026-09-02', 11204, 11205, 3.79, NULL, 'C43 Relleno de filtro \r\n', 'Bombero2', NULL, '2026-09-02 12:50:32', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 1, 8, '6', NULL, 1, 71552, 82),
(942, 58, '2026-09-02', 59080, 59081, 15.14, NULL, 'C57 Relleno de filtro ', 'Bombero2', NULL, '2026-09-02 12:51:21', NULL, NULL, 15.14, '', '', 'CONSUMO', 15.14, NULL, NULL, 'APROBADO', 0.25, 9, '6', NULL, 1, 71553, 82),
(943, 51, '2026-09-02', 101053, 101596, 251.36, NULL, 'C37 Puerto Sandino', 'Bombero2', NULL, '2026-09-02 12:53:01', NULL, NULL, 251.36, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 251.36, NULL, NULL, 'BLOQUEADO', 8.18, 10, '6', NULL, 1, 71554, 82),
(944, 38, '2026-09-02', 86791, 87097, 138.39, NULL, 'C58 Corinto', 'Bombero2', NULL, '2026-09-02 14:59:58', NULL, NULL, 138.39, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 138.39, NULL, NULL, 'APROBADO', 8.36, 9, '6', NULL, 1, 71555, 82),
(945, 3, '2026-09-02', 22189, 22242, 37.81, NULL, 'C10 Relleno', 'Bombero2', NULL, '2026-09-02 15:01:41', NULL, NULL, 37.81, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 37.8, NULL, NULL, 'BLOQUEADO', 5.28, 15, '6', NULL, 1, 71556, 82),
(946, 83, '2026-09-02', 0, 0, 2271.00, NULL, '', 'Bombero2', NULL, '2026-09-02 15:02:40', NULL, NULL, 2271, '', '', 'CONSUMO', 2271, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71557, 83),
(947, 75, '2026-09-02', 0, 0, 4.05, NULL, '', 'Bombero2', NULL, '2026-09-02 15:03:10', NULL, NULL, 4.05, '', '', 'CONSUMO', 4.05, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71558, 82),
(948, 15, '2026-09-02', 19764, 19912, 35.39, NULL, 'AJS 02 Malacatoya ', 'Bombero2', NULL, '2026-09-02 15:04:59', NULL, NULL, 35.39, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 35.39, NULL, NULL, 'APROBADO', 15.8, 13, '6', NULL, 1, 71559, 82),
(949, 64, '2026-09-02', 10827, 11210, 156.40, NULL, 'C67 Corinto - Tonala ', 'Bombero2', NULL, '2026-09-02 15:07:19', NULL, NULL, 156.4, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 156.4, NULL, NULL, 'APROBADO', 9.27, 9, '6', NULL, 1, 71560, 82),
(951, 77, '2026-09-02', 4, 5, 18.88, NULL, 'Moisés Pérez/Cuota Aníbal Sevilla', 'Bombero2', NULL, '2026-09-02 22:11:23', NULL, NULL, 18.88, '', '', 'CONSUMO', 18.88, NULL, NULL, 'APROBADO', 0.2, 0, '5', NULL, 1, 71562, 82),
(952, 75, '2026-09-02', 0, 0, 985.06, NULL, '', 'Bombero2', NULL, '2026-09-02 22:12:00', NULL, NULL, 985.06, '', '', 'CONSUMO', 985.06, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71563, 83),
(953, 57, '2026-09-02', 66549, 66588, 40.32, NULL, 'C56 Las Mercedes', 'Bombero2', NULL, '2026-09-02 22:13:18', NULL, NULL, 40.32, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 40.32, NULL, NULL, 'BLOQUEADO', 3.66, 8, '6', NULL, 1, 71564, 83),
(954, 47, '2026-09-02', 40974, 41422, 212.00, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-09-02 22:14:27', NULL, NULL, 212, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 212, NULL, NULL, 'BLOQUEADO', 8, 9, '6', NULL, 1, 71565, 83),
(955, 8, '2026-09-02', 663192, 663545, 64.19, NULL, 'AJS-03 Chinandega', 'Bombero2', NULL, '2026-09-02 22:15:50', NULL, NULL, 64.19, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 64.19, NULL, NULL, 'BLOQUEADO', 20.84, 15, '6', NULL, 1, 71566, 83),
(956, 62, '2026-09-02', 54423, 54653, 90.28, NULL, 'C64 Mina Monte Carlos ', 'Bombero2', NULL, '2026-09-02 22:17:08', NULL, NULL, 90.28, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 90.28, NULL, NULL, 'APROBADO', 9.65, 9, '6', NULL, 1, 71567, 83),
(957, 80, '2026-09-02', 0, 0, 176.04, NULL, '', 'Bombero2', NULL, '2026-09-02 22:18:40', NULL, NULL, 176.04, '', '', 'CONSUMO', 176.04, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71568, 83),
(958, 80, '2026-09-02', 0, 0, 219.95, NULL, '', 'Bombero2', NULL, '2026-09-02 22:19:14', NULL, NULL, 219.95, '', '', 'CONSUMO', 219.94, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71569, 83),
(960, 42, '2026-09-02', 2134956, 2135405, 219.29, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-09-02 22:20:46', NULL, NULL, 219.29, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 219.28, NULL, NULL, 'BLOQUEADO', 7.75, 10, '6', NULL, 1, 71571, 83),
(961, 61, '2026-09-02', 1142962, 1143071, 79.05, NULL, 'C61 Jinotepe-Batahola-Linda Vista ', 'Bombero2', NULL, '2026-09-02 22:21:56', NULL, NULL, 79.05, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 79.05, NULL, NULL, 'BLOQUEADO', 5.23, 9, '6', NULL, 1, 71572, 83),
(962, 12, '2026-09-02', 334821, 334915, 49.00, NULL, 'LPG2-097 San Benito', 'Bombero2', NULL, '2026-09-02 22:23:08', NULL, NULL, 49, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 49, NULL, NULL, 'BLOQUEADO', 7.29, 15, '6', NULL, 1, 71573, 83),
(963, 88, '2026-09-02', 5021, 5116, 99.65, NULL, 'C69 Managua', 'Bombero2', NULL, '2026-09-02 22:24:01', NULL, NULL, 99.65, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 99.65, NULL, NULL, 'BLOQUEADO', 3.62, 9, '6', NULL, 1, 71574, 83),
(964, 5, '2026-09-02', 1692, 2272, 215.67, NULL, 'C14 Esperanza', 'Bombero2', NULL, '2026-09-02 22:25:08', NULL, NULL, 215.67, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 215.67, NULL, NULL, 'BLOQUEADO', 10.18, 12, '6', NULL, 1, 71575, 83),
(965, 6, '2026-09-02', 50266, 50543, 59.04, NULL, 'C38 Miramontes', 'Bombero2', NULL, '2026-09-02 22:26:06', NULL, NULL, 59.04, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 59.04, NULL, NULL, 'APROBADO', 17.73, 15, '6', NULL, 1, 71576, 83),
(966, 63, '2026-09-02', 33670, 33903, 131.01, NULL, 'C66 Locales', 'Bombero2', NULL, '2026-09-02 22:27:30', NULL, NULL, 131.01, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 131, NULL, NULL, 'BLOQUEADO', 6.74, 9, '6', NULL, 1, 71577, 83),
(967, 81, '2026-09-03', 10, 11, 0.10, NULL, 'Prueba de pistola TK1 por cambio de filtro - Combustible se retornó al TK 1', 'Bombero2', NULL, '2026-09-03 09:16:48', NULL, NULL, 0.1, '', '', 'CONSUMO', 0.1, NULL, NULL, 'APROBADO', 37.85, 0, '6', NULL, 1, 71578, 85),
(968, 51, '2026-09-03', 101596, 101774, 65.22, NULL, 'C37 Relleno ', 'Bombero2', NULL, '2026-09-03 09:19:13', NULL, NULL, 65.22, 'Julio José Mendoza Ruíz ', '001-080300-1009B', 'CONSUMO', 65.22, NULL, NULL, 'APROBADO', 10.35, 10, '6', NULL, 1, 71579, 85),
(969, 75, '2026-09-03', 0, 0, 33.96, NULL, '', 'Bombero2', NULL, '2026-09-03 09:19:56', NULL, NULL, 33.96, '', '', 'CONSUMO', 33.96, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71580, 85),
(970, 80, '2026-09-03', 0, 0, 129.71, NULL, '', 'Bombero2', NULL, '2026-09-03 09:20:33', NULL, NULL, 129.71, '', '', 'CONSUMO', 129.71, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71581, 85),
(971, 65, '2026-09-03', 19629, 20157, 248.07, NULL, 'C68 Corinto - Puerto Sandino - Managua', 'Bombero2', NULL, '2026-09-03 09:32:01', NULL, NULL, 248.07, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 248.07, NULL, NULL, 'BLOQUEADO', 8.06, 9, '6', NULL, 1, 71582, 85),
(972, 8, '2026-09-03', 663545, 663715, 42.70, NULL, 'AJS 03 Malacatoya - Tipitapa', 'Bombero2', NULL, '2026-09-03 09:42:15', NULL, NULL, 42.7, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 42.7, NULL, NULL, 'APROBADO', 15.06, 15, '6', NULL, 1, 71583, 85),
(973, 9, '2026-09-03', 18705, 18864, 43.67, NULL, 'C42 San Martin', 'Bombero2', NULL, '2026-09-03 09:48:17', NULL, NULL, 43.67, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 43.67, NULL, NULL, 'APROBADO', 13.76, 15, '6', NULL, 1, 71584, 85),
(974, 45, '2026-09-03', 89874, 90623, 366.01, NULL, 'C21 Puerto Sandino ', 'Bombero2', NULL, '2026-09-03 15:20:43', NULL, NULL, 366.01, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 366.01, NULL, NULL, 'BLOQUEADO', 7.74, 10, '6', NULL, 1, 71585, 85),
(975, 75, '2026-09-03', 0, 0, 12.15, NULL, '', 'Bombero2', NULL, '2026-09-03 15:21:21', NULL, NULL, 12.15, '', '', 'CONSUMO', 12.15, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71586, 85),
(976, 40, '2026-09-03', 74404, 74912, 296.37, NULL, 'C62 Locales', 'Bombero2', NULL, '2026-09-03 15:23:17', NULL, NULL, 296.37, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 296.37, NULL, NULL, 'BLOQUEADO', 6.48, 9, '6', NULL, 1, 71587, 85),
(977, 6, '2026-09-03', 50543, 50841, 61.98, NULL, 'C38 Bismark Menas - La Paciencia', 'Bombero2', NULL, '2026-09-03 15:25:59', NULL, NULL, 61.98, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 61.98, NULL, NULL, 'APROBADO', 18.2, 15, '6', NULL, 1, 71588, 85),
(978, 59, '2026-09-03', 1364421, 1364757, 181.79, NULL, 'C59 Monte Rosa - Locales ', 'Bombero2', NULL, '2026-09-03 15:27:13', NULL, NULL, 181.79, 'Juan Rafael Gaitán Chévez ', '121-231082-0003C', 'CONSUMO', 181.79, NULL, NULL, 'BLOQUEADO', 7, 9, '6', NULL, 1, 71589, 85),
(979, 48, '2026-09-03', 98862, 99460, 290.07, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-09-03 15:30:53', NULL, NULL, 290.07, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 290.06, NULL, NULL, 'BLOQUEADO', 7.8, 10, '6', NULL, 1, 71590, 85),
(980, 44, '2026-09-03', 51821, 52365, 281.27, NULL, 'C19 Puerto Corinto', 'Bombero2', NULL, '2026-09-03 15:34:11', NULL, NULL, 281.27, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 281.27, NULL, NULL, 'BLOQUEADO', 7.32, 9, '6', NULL, 1, 71591, 84),
(981, 43, '2026-09-03', 77603, 78352, 350.17, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-09-03 15:35:46', NULL, NULL, 350.17, 'Moisés Isaac Gutiérrez Valerio ', '002-241193-0000D', 'CONSUMO', 350.17, NULL, NULL, 'BLOQUEADO', 8.1, 10, '6', NULL, 1, 71592, 85),
(982, 11, '2026-09-03', 300803, 300950, 60.77, NULL, 'LPG096 ', 'Bombero2', NULL, '2026-09-03 15:37:54', NULL, NULL, 60.77, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 60.77, NULL, NULL, 'BLOQUEADO', 9.18, 15, '6', NULL, 1, 71593, 85),
(983, 80, '2026-09-03', 0, 0, 139.04, NULL, '', 'Bombero2', 'Bombero2', '2026-09-04 14:01:39', NULL, NULL, 139.04, '', '', 'CONSUMO', 139.03, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71594, 85),
(984, 75, '2026-09-03', 0, 0, 16.95, NULL, '', 'Bombero2', NULL, '2026-09-03 20:42:27', NULL, NULL, 16.95, '', '', 'CONSUMO', 16.95, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71595, 85),
(985, 62, '2026-09-03', 54653, 54748, 40.88, NULL, 'C64 Proinco-D Guerrero-Colonia Rubenia', 'Bombero2', NULL, '2026-09-03 20:44:01', NULL, NULL, 40.88, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 40.88, NULL, NULL, 'APROBADO', 8.81, 9, '6', NULL, 1, 71596, 85),
(986, 81, '2026-09-03', 11, 12, 76.63, NULL, '', 'Bombero2', NULL, '2026-09-03 20:45:07', NULL, NULL, 76.63, '', '', 'CONSUMO', 76.63, NULL, NULL, 'APROBADO', 0.05, 0, '6', NULL, 1, 71597, 85),
(987, 68, '2026-09-03', 288652, 289128, 39.84, NULL, 'Toyota H/Gestion Taller', 'Bombero2', NULL, '2026-09-03 20:49:41', NULL, NULL, 39.84, '', '', 'CONSUMO', 39.84, NULL, NULL, 'APROBADO', 45.2, 45, '5', NULL, 1, 71598, 85),
(988, 47, '2026-09-03', 41422, 41871, 218.12, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-09-03 20:50:33', NULL, NULL, 218.12, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 218.12, NULL, NULL, 'BLOQUEADO', 7.79, 9, '6', NULL, 1, 71599, 85),
(989, 80, '2026-09-03', 0, 0, 154.70, NULL, '', 'Bombero2', NULL, '2026-09-03 20:50:58', NULL, NULL, 154.7, '', '', 'CONSUMO', 154.69, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71600, 85),
(990, 76, '2026-09-03', 1, 2, 55.48, NULL, 'RAM 2500/Cuota Anibal Sevilla', 'Bombero2', NULL, '2026-09-03 21:10:32', NULL, NULL, 55.48, '', '', 'CONSUMO', 55.48, NULL, NULL, 'APROBADO', 0.07, 0, '5', NULL, 1, 71601, 85),
(991, 12, '2026-09-03', 334915, 335074, 71.27, NULL, 'LPG2-097 Managua-Masaya', 'Bombero2', NULL, '2026-09-03 21:11:40', NULL, NULL, 71.27, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 71.27, NULL, NULL, 'BLOQUEADO', 8.45, 15, '6', NULL, 1, 71602, 85),
(992, 5, '2026-09-03', 2272, 2417, 91.20, NULL, 'C14 Prodecon-Gustavo Rocha', 'Bombero2', NULL, '2026-09-03 21:35:07', NULL, NULL, 91.2, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 91.2, NULL, NULL, 'BLOQUEADO', 6.03, 12, '6', NULL, 1, 71603, 85),
(993, 65, '2026-09-03', 20157, 20383, 108.89, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-09-03 21:36:19', NULL, NULL, 108.89, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 108.89, NULL, NULL, 'APROBADO', 7.85, 9, '6', NULL, 1, 71604, 85),
(994, 80, '2026-09-03', 0, 0, 140.66, NULL, '', 'Bombero2', NULL, '2026-09-03 21:36:42', NULL, NULL, 140.66, '', '', 'CONSUMO', 140.66, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71605, 85),
(995, 60, '2026-09-03', 39707, 40089, 238.36, NULL, 'C60 Managua', 'Bombero2', NULL, '2026-09-03 22:20:12', NULL, NULL, 238.36, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 238.36, NULL, NULL, 'BLOQUEADO', 6.07, 9, '6', NULL, 1, 71606, 85),
(996, 39, '2026-09-03', 56719, 57155, 217.90, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-09-03 22:44:55', NULL, NULL, 217.9, 'Ulises Joel Bautista Gómez ', '001-140100-1011D', 'CONSUMO', 217.9, NULL, NULL, 'BLOQUEADO', 7.57, 9, '6', NULL, 1, 71607, 85),
(997, 5, '2026-09-04', 2417, 2524, 56.46, NULL, 'C14 Prodecon', 'Bombero2', NULL, '2026-09-04 08:25:00', NULL, NULL, 56.46, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 56.45, NULL, NULL, 'BLOQUEADO', 7.16, 12, '6', NULL, 1, 71608, 86),
(998, 64, '2026-09-04', 11210, 11325, 63.39, NULL, 'C67 Llanza - Martinez e Hijos - Trasiego Tellez ', 'Bombero2', NULL, '2026-09-04 08:27:11', NULL, NULL, 63.39, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 63.39, NULL, NULL, 'APROBADO', 6.88, 9, '6', NULL, 1, 71609, 86),
(999, 59, '2026-09-04', 1364757, 1365239, 215.04, NULL, 'C59 Estelí ', 'Bombero2', NULL, '2026-09-04 08:28:25', NULL, NULL, 215.04, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 215.04, NULL, NULL, 'APROBADO', 8.48, 9, '6', NULL, 1, 71610, 86),
(1000, 4, '2026-09-04', 40636, 40664, 11.78, NULL, 'C12 Prueba de filtro ', 'Bombero2', NULL, '2026-09-04 09:41:48', NULL, NULL, 11.78, '', '', 'CONSUMO', 11.78, NULL, NULL, 'APROBADO', 8.99, 10, '6', NULL, 1, 71611, 86),
(1001, 8, '2026-09-04', 663715, 663842, 27.40, NULL, 'AJS03 Granada', 'Bombero2', NULL, '2026-09-04 09:43:19', NULL, NULL, 27.4, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 27.4, NULL, NULL, 'APROBADO', 17.55, 15, '6', NULL, 1, 71612, 86),
(1002, 92, '2026-09-04', 0, 360, 151.40, NULL, 'C71 Relleno para prueba ', 'Bombero2', 'Bombero2', '2026-09-04 13:20:28', NULL, NULL, 151.4, '', '', 'CONSUMO', 151.4, NULL, NULL, 'APROBADO', 9, 9, '6', NULL, 1, 71613, 86),
(1003, 89, '2026-09-04', 130, 423, 149.76, NULL, 'C70 Chinandega - Gueguense ', 'Bombero2', NULL, '2026-09-04 13:23:49', NULL, NULL, 149.76, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 149.76, NULL, NULL, 'BLOQUEADO', 7.39, 9, '6', NULL, 1, 71614, 86),
(1004, 54, '2026-09-04', 43194, 43716, 225.30, NULL, 'C45 Chinandega - Leon - Rubenia', 'Bombero2', NULL, '2026-09-04 13:25:36', NULL, NULL, 225.3, 'Sergio Antonio Manzanarez López ', '001-170685-0016Y', 'CONSUMO', 225.29, NULL, NULL, 'BLOQUEADO', 8.77, 8, '6', NULL, 1, 71615, 86),
(1005, 41, '2026-09-04', 181046, 181174, 66.05, NULL, 'C20 Montelimar', 'Bombero2', NULL, '2026-09-04 15:02:05', NULL, NULL, 66.05, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 66.05, NULL, NULL, 'APROBADO', 7.34, 9, '6', NULL, 1, 71616, 86),
(1006, 60, '2026-09-04', 40089, 40207, 69.64, NULL, 'C60 Coyotepe - Las Colinas ', 'Bombero2', NULL, '2026-09-04 15:07:11', NULL, NULL, 69.64, 'Edgard Eduardo Velásquez Gutiérrez ', '001-070705-1057A', 'CONSUMO', 69.64, NULL, NULL, 'BLOQUEADO', 6.39, 9, '6', NULL, 1, 71617, 86),
(1007, 88, '2026-09-04', 5116, 5266, 118.41, NULL, 'C69 Locales ', 'Bombero2', NULL, '2026-09-04 15:09:21', NULL, NULL, 118.41, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 118.41, NULL, NULL, 'BLOQUEADO', 4.78, 9, '6', NULL, 1, 71618, 86),
(1008, 43, '2026-09-04', 78352, 78652, 144.40, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-09-04 15:11:16', NULL, NULL, 144.4, 'Moisés Isaac Gutiérrez Valerio ', '002-241193-0000D', 'CONSUMO', 144.4, NULL, NULL, 'BLOQUEADO', 7.87, 10, '6', NULL, 1, 71619, 86),
(1009, 80, '2026-09-04', 0, 0, 124.47, NULL, '', 'Bombero2', NULL, '2026-09-04 15:12:18', NULL, NULL, 124.47, '', '', 'CONSUMO', 124.47, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71620, 86),
(1010, 61, '2026-09-04', 1143071, 1143580, 278.69, NULL, 'C61 Matagalpa - Conpetra - Jinotepe - Locales ', 'Bombero2', NULL, '2026-09-04 15:14:33', NULL, NULL, 278.69, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 278.69, NULL, NULL, 'BLOQUEADO', 6.92, 9, '6', NULL, 1, 71621, 86),
(1011, 84, '2026-09-04', 0, 5, 7.57, NULL, 'Toyota L/Gestiones GCM', 'Bombero2', NULL, '2026-09-04 16:02:01', NULL, NULL, 7.57, '', '', 'CONSUMO', 7.57, NULL, NULL, 'APROBADO', 2.5, 0, '5', NULL, 1, 71622, 86),
(1012, 84, '2026-09-04', 0, 1, 40.59, NULL, 'Toyota L/Cuota Oscar Briceño', 'Bombero2', NULL, '2026-09-04 16:03:25', NULL, NULL, 40.59, '', '', 'CONSUMO', 40.59, NULL, NULL, 'APROBADO', 0.09, 0, '5', NULL, 1, 71623, 86),
(1013, 44, '2026-09-04', 52365, 52514, 77.56, NULL, 'C19 Miramar', 'Bombero2', NULL, '2026-09-04 16:04:50', NULL, NULL, 77.56, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 77.56, NULL, NULL, 'APROBADO', 7.29, 9, '6', NULL, 1, 71624, 86),
(1014, 12, '2026-09-04', 335074, 335354, 89.67, NULL, 'LPG097 Juigalpa - Chinandega', 'Bombero2', NULL, '2026-09-04 16:39:13', NULL, NULL, 89.67, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 89.67, NULL, NULL, 'BLOQUEADO', 11.83, 15, '6', NULL, 1, 71625, 86),
(1015, 15, '2026-09-04', 19912, 20122, 37.42, NULL, 'AJS02 Leon', 'Bombero2', NULL, '2026-09-04 16:41:03', NULL, NULL, 37.42, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 37.42, NULL, NULL, 'BLOQUEADO', 21.19, 13, '6', NULL, 1, 71626, 86),
(1016, 82, '2026-09-04', 0, 0, 632.40, NULL, '', 'Bombero2', NULL, '2026-09-04 16:42:09', NULL, NULL, 632.4, '', '', 'CONSUMO', 632.4, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71627, 86),
(1017, 57, '2026-09-04', 66588, 66947, 218.70, NULL, 'C56 Santo Tomas', 'Bombero2', NULL, '2026-09-04 21:48:39', NULL, NULL, 218.7, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 218.7, NULL, NULL, 'BLOQUEADO', 6.22, 8, '6', NULL, 1, 71628, 86),
(1018, 73, '2026-09-04', 80393, 80909, 48.79, NULL, 'Mitsubishi/Gestión Operaciones ', 'Bombero2', NULL, '2026-09-04 21:53:23', NULL, NULL, 48.79, '', '', 'CONSUMO', 48.79, NULL, NULL, 'APROBADO', 40.03, 40, '5', NULL, 1, 71629, 86),
(1019, 62, '2026-09-04', 54748, 55254, 196.03, NULL, 'C64 Wiwili', 'Bombero2', NULL, '2026-09-04 21:54:18', NULL, NULL, 196.03, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 196.03, NULL, NULL, 'APROBADO', 9.76, 9, '6', NULL, 1, 71630, 86),
(1020, 79, '2026-09-04', 11205, 11536, 156.26, NULL, 'C43 Esteli', 'Bombero2', NULL, '2026-09-04 21:55:17', NULL, NULL, 156.26, 'Omar Patricio Osejo Amador ', '441-240882-0002F', 'CONSUMO', 156.26, NULL, NULL, 'APROBADO', 8.01, 8, '6', NULL, 1, 71631, 86),
(1021, 40, '2026-09-04', 74912, 75263, 161.33, NULL, 'C62 Coyotepe-Sebaco', 'Bombero2', NULL, '2026-09-04 21:56:22', NULL, NULL, 161.33, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 161.32, NULL, NULL, 'APROBADO', 8.24, 9, '6', NULL, 1, 71632, 86),
(1022, 64, '2026-09-04', 11325, 11513, 71.43, NULL, 'C67 Miramontes', 'Bombero2', NULL, '2026-09-04 21:57:17', NULL, NULL, 71.43, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 71.43, NULL, NULL, 'APROBADO', 9.96, 9, '6', NULL, 1, 71633, 86),
(1023, 80, '2026-09-04', 0, 0, 174.19, NULL, '', 'Bombero2', NULL, '2026-09-04 21:57:40', NULL, NULL, 174.19, '', '', 'CONSUMO', 174.19, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71634, 86),
(1024, 80, '2026-09-04', 0, 0, 178.71, NULL, '', 'Bombero2', NULL, '2026-09-04 22:21:48', NULL, NULL, 178.71, '', '', 'CONSUMO', 178.71, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71635, 86),
(1025, 89, '2026-09-04', 423, 595, 94.09, NULL, 'C70 Leon', 'Bombero2', NULL, '2026-09-04 22:23:24', NULL, NULL, 94.09, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 94.09, NULL, NULL, 'BLOQUEADO', 6.92, 9, '6', NULL, 1, 71636, 86),
(1026, 75, '2026-09-05', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-09-05 08:13:15', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71637, 88),
(1027, 65, '2026-09-05', 20383, 20890, 241.18, NULL, 'C68 Corinto - Puerto Sandino - Managua', 'Bombero2', NULL, '2026-09-05 08:15:38', NULL, NULL, 241.18, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 241.18, NULL, NULL, 'BLOQUEADO', 7.96, 9, '6', NULL, 1, 71638, 88),
(1028, 75, '2026-09-05', 0, 0, 6.44, NULL, '', 'Bombero2', NULL, '2026-09-05 08:16:33', NULL, NULL, 6.44, '', '', 'CONSUMO', 6.44, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71639, 88),
(1029, 78, '2026-09-05', 0, 0, 69.16, NULL, '', 'Bombero2', NULL, '2026-09-05 09:47:52', NULL, NULL, 69.16, '', '', 'CONSUMO', 69.16, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71640, 88),
(1030, 64, '2026-09-05', 11513, 11654, 69.94, NULL, 'C67 La Boquita ', 'Bombero2', NULL, '2026-09-05 10:34:53', NULL, NULL, 69.94, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 69.94, NULL, NULL, 'APROBADO', 7.64, 9, '6', NULL, 1, 71641, 89),
(1031, 45, '2026-09-05', 90623, 91221, 284.67, NULL, 'C21 Puerto Sandino ', 'Bombero2', NULL, '2026-09-05 10:38:03', NULL, NULL, 284.67, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 284.67, NULL, NULL, 'BLOQUEADO', 7.95, 10, '6', NULL, 1, 71642, 89),
(1032, 9, '2026-09-05', 18864, 19056, 54.64, NULL, 'C42 Miramontes', 'Bombero2', NULL, '2026-09-05 19:32:45', NULL, NULL, 54.64, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 54.64, NULL, NULL, 'APROBADO', 13.28, 15, '6', NULL, 1, 71643, 89),
(1033, 12, '2026-09-05', 335354, 335532, 51.49, NULL, 'LPG2-097 Managua', 'Bombero2', NULL, '2026-09-05 19:40:52', NULL, NULL, 51.49, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 51.49, NULL, NULL, 'APROBADO', 13.09, 15, '6', NULL, 1, 71644, 89),
(1034, 85, '2026-09-05', 0, 1, 10.36, NULL, 'Isuzu/Cuota Roger Vilchez-Entrega de productos', 'Bombero2', NULL, '2026-09-05 19:41:37', NULL, NULL, 10.36, '', '', 'CONSUMO', 10.36, NULL, NULL, 'APROBADO', 0.37, 0, '5', NULL, 1, 71645, 89),
(1035, 42, '2026-09-05', 2135405, 2135703, 148.69, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-09-05 19:42:26', NULL, NULL, 148.69, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 148.69, NULL, NULL, 'BLOQUEADO', 7.59, 10, '6', NULL, 1, 71646, 89),
(1036, 80, '2026-09-05', 0, 0, 56.76, NULL, '', 'Bombero2', NULL, '2026-09-05 19:43:37', NULL, NULL, 56.76, '', '', 'CONSUMO', 56.76, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71647, 89),
(1037, 80, '2026-09-05', 0, 0, 137.69, NULL, '', 'Bombero2', NULL, '2026-09-05 19:44:00', NULL, NULL, 137.69, '', '', 'CONSUMO', 137.69, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71648, 89),
(1038, 61, '2026-09-05', 1143580, 1143643, 44.17, NULL, 'C61 Nejapa-Panamericano-Brasiles-Panamericano', 'Bombero2', NULL, '2026-09-05 19:44:57', NULL, NULL, 44.17, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 44.17, NULL, NULL, 'APROBADO', 5.36, 9, '6', NULL, 1, 71649, 89),
(1039, 48, '2026-09-05', 99460, 100058, 296.47, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-09-05 19:45:50', NULL, NULL, 296.47, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 296.47, NULL, NULL, 'BLOQUEADO', 7.63, 10, '6', NULL, 1, 71650, 89),
(1040, 47, '2026-09-05', 41871, 42470, 283.54, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-09-05 19:50:30', NULL, NULL, 283.54, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 283.54, NULL, NULL, 'BLOQUEADO', 7.99, 9, '6', NULL, 1, 71651, 89),
(1041, 40, '2026-09-05', 75263, 75570, 146.04, NULL, 'C62 Chinandega-Altagracia', 'Bombero2', NULL, '2026-09-05 19:51:54', NULL, NULL, 146.04, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 146.04, NULL, NULL, 'APROBADO', 7.96, 9, '6', NULL, 1, 71652, 89),
(1042, 82, '2026-09-05', 0, 0, 643.45, NULL, '', 'Bombero2', 'Bombero2', '2026-09-05 22:48:07', NULL, NULL, 643.45, '', '', 'CONSUMO', 643.45, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71653, 89),
(1043, 54, '2026-09-05', 43716, 44090, 194.43, NULL, 'C45 Competra-Sebaco', 'Bombero2', NULL, '2026-09-05 19:54:07', NULL, NULL, 194.43, 'Omar Patricio Osejo Amador ', '441-240882-0002F', 'CONSUMO', 194.43, NULL, NULL, 'APROBADO', 7.28, 8, '6', NULL, 1, 71654, 89),
(1044, 88, '2026-09-05', 5266, 5552, 176.89, NULL, 'C69 Masaya-Diriamba-Managua', 'Bombero2', 'Bombero2', '2026-09-05 22:51:05', NULL, NULL, 176.89, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 176.89, NULL, NULL, 'BLOQUEADO', 6.12, 9, '6', NULL, 1, 71655, 89),
(1045, 57, '2026-09-05', 66947, 67305, 205.42, NULL, 'C56 Santo Tomas', 'Bombero2', NULL, '2026-09-05 19:58:03', NULL, NULL, 205.42, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 205.42, NULL, NULL, 'BLOQUEADO', 6.59, 8, '6', NULL, 1, 71656, 89),
(1046, 60, '2026-09-05', 40207, 40294, 74.62, NULL, 'C60 Managua', 'Bombero2', NULL, '2026-09-05 20:01:47', NULL, NULL, 74.62, 'Edgard Eduardo Velásquez Gutiérrez ', '001-070705-1057A', 'CONSUMO', 74.62, NULL, NULL, 'BLOQUEADO', 4.39, 9, '6', NULL, 1, 71657, 89),
(1047, 80, '2026-09-05', 0, 0, 156.18, NULL, '', 'Bombero2', NULL, '2026-09-05 20:05:55', NULL, NULL, 156.18, '', '', 'CONSUMO', 156.18, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71658, 89),
(1048, 63, '2026-09-05', 33903, 34057, 93.99, NULL, 'C66 Proinco', 'Bombero2', NULL, '2026-09-05 22:23:03', NULL, NULL, 93.99, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 93.99, NULL, NULL, 'BLOQUEADO', 6.19, 9, '6', NULL, 1, 71659, 89),
(1049, 65, '2026-09-05', 20890, 21206, 142.55, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-09-05 22:24:14', NULL, NULL, 142.55, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 142.55, NULL, NULL, 'APROBADO', 8.39, 9, '6', NULL, 1, 71660, 89),
(1050, 44, '2026-09-05', 52514, 52840, 157.23, NULL, 'C19 Corinto', 'Bombero2', NULL, '2026-09-05 22:25:06', NULL, NULL, 157.23, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 157.22, NULL, NULL, 'BLOQUEADO', 7.84, 9, '6', NULL, 1, 71661, 89),
(1051, 12, '2026-09-05', 335532, 335622, 71.26, NULL, 'LPG2-097 San Benito', 'Bombero2', 'Bombero2', '2026-09-05 22:30:39', NULL, NULL, 71.26, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 71.26, NULL, NULL, 'BLOQUEADO', 4.79, 15, '6', NULL, 1, 71662, 89),
(1052, 80, '2026-09-05', 0, 0, 157.25, NULL, '', 'Bombero2', NULL, '2026-09-05 22:32:05', NULL, NULL, 157.25, '', '', 'CONSUMO', 157.25, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71663, 89),
(1053, 51, '2026-09-05', 102200, 102866, 299.81, NULL, 'C37 Puerto Sandino-Puerto Corinto', 'Bombero2', NULL, '2026-09-05 22:34:38', NULL, NULL, 299.81, 'Julio José Mendoza Ruíz ', '001-080300-1009B', 'CONSUMO', 299.81, NULL, NULL, 'BLOQUEADO', 8.4, 10, '6', NULL, 1, 71664, 89),
(1054, 39, '2026-09-05', 57155, 58024, 426.68, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-09-05 22:35:49', NULL, NULL, 426.68, 'Ulises Joel Bautista Gómez ', '001-140100-1011D', 'CONSUMO', 426.68, NULL, NULL, 'BLOQUEADO', 7.71, 9, '6', NULL, 1, 71665, 89),
(1055, 40, '2026-09-06', 75570, 75796, 99.72, NULL, 'C62 Locales', 'Bombero2', NULL, '2026-09-06 19:22:07', NULL, NULL, 99.72, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 99.72, NULL, NULL, 'APROBADO', 8.58, 9, '6', NULL, 1, 71666, 90),
(1056, 59, '2026-09-06', 1365239, 1365451, 114.05, NULL, 'C59 Brasiles-Leon', 'Bombero2', NULL, '2026-09-06 19:23:20', NULL, NULL, 114.05, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 114.05, NULL, NULL, 'BLOQUEADO', 7.04, 9, '6', NULL, 1, 71667, 90),
(1057, 41, '2026-09-07', 181174, 181533, 206.97, NULL, 'C20 Santo Tomas', 'Bombero2', NULL, '2026-09-07 10:51:12', NULL, NULL, 206.97, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 206.97, NULL, NULL, 'BLOQUEADO', 6.56, 9, '6', NULL, 1, 71668, 92),
(1058, 82, '2026-09-07', 0, 0, 63.32, NULL, '', 'Bombero2', NULL, '2026-09-07 10:51:40', NULL, NULL, 63.32, '', '', 'CONSUMO', 63.32, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71669, 92),
(1059, 71, '2026-09-07', 78991, 79298, 49.74, NULL, 'Kia 2700 / Gestiones Compra', 'Bombero2', NULL, '2026-09-07 11:20:32', NULL, NULL, 49.74, '', '', 'CONSUMO', 49.73, NULL, NULL, 'BLOQUEADO', 23.38, 45, '5', NULL, 1, 71670, 92),
(1060, 80, '2026-09-07', 0, 0, 150.53, NULL, '', 'Bombero2', NULL, '2026-09-07 11:21:44', NULL, NULL, 150.53, '', '', 'CONSUMO', 150.53, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71671, 92),
(1061, 8, '2026-09-07', 663842, 664028, 54.24, NULL, 'AJS03 Reyna del sur - Pavinic', 'Bombero2', NULL, '2026-09-07 13:20:08', NULL, NULL, 54.24, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 54.24, NULL, NULL, 'APROBADO', 12.97, 15, '6', NULL, 1, 71672, 92),
(1062, 80, '2026-09-07', 0, 0, 224.54, NULL, '', 'Bombero2', NULL, '2026-09-07 13:20:41', NULL, NULL, 224.54, '', '', 'CONSUMO', 224.53, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71673, 92),
(1063, 80, '2026-09-07', 0, 0, 56.78, NULL, '', 'Bombero2', NULL, '2026-09-07 13:21:07', NULL, NULL, 56.78, '', '', 'CONSUMO', 56.77, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71674, 92),
(1064, 75, '2026-09-07', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-09-07 13:21:55', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71675, 92),
(1065, 80, '2026-09-07', 0, 0, 132.80, NULL, '', 'Bombero2', NULL, '2026-09-07 13:22:35', NULL, NULL, 132.8, '', '', 'CONSUMO', 132.8, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71676, 92),
(1066, 75, '2026-09-07', 0, 0, 32.45, NULL, '', 'Bombero2', NULL, '2026-09-07 13:23:00', NULL, NULL, 32.45, '', '', 'CONSUMO', 32.45, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71677, 92),
(1067, 75, '2026-09-07', 0, 0, 18.24, NULL, '', 'Bombero2', NULL, '2026-09-07 13:23:32', NULL, NULL, 18.24, '', '', 'CONSUMO', 18.24, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71678, 92);
INSERT INTO `registro_combustible` (`id`, `id_vehiculo`, `fecha_registro`, `kilometraje_anterior`, `kilometraje_actual`, `cantidad_litros`, `id_motivo`, `observaciones`, `usuario_crea`, `usuario_edita`, `fecha_actualiza`, `id_tipo_motivo`, `id_direccion`, `medicion`, `nombreCliente`, `dni`, `tipo`, `combustible_tanque`, `monto_nio`, `monto_usd`, `estado`, `rendimiento`, `rendimiento_promedio`, `referencia1`, `referencia2`, `enviado`, `numero_ingreso_sag`, `id_lectura`) VALUES
(1068, 11, '2026-09-07', 300950, 300985, 33.55, NULL, 'C 096 Managua', 'Bombero2', NULL, '2026-09-07 13:25:07', NULL, NULL, 33.55, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 33.55, NULL, NULL, 'BLOQUEADO', 3.95, 15, '6', NULL, 1, 71679, 92),
(1069, 87, '2026-09-07', 0, 1, 22.87, NULL, 'Toyota F / Cuota Oscar Briceño', 'Bombero2', NULL, '2026-09-07 13:27:20', NULL, NULL, 22.87, '', '', 'CONSUMO', 22.86, NULL, NULL, 'APROBADO', 0.17, 0, '5', NULL, 1, 71680, 91),
(1070, 6, '2026-09-07', 50841, 51110, 60.96, NULL, 'C38 Agricola Santa Isabella - Colonia Rubenia ', 'Bombero2', NULL, '2026-09-07 13:29:14', NULL, NULL, 60.96, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 60.96, NULL, NULL, 'APROBADO', 16.69, 15, '6', NULL, 1, 71681, 91),
(1071, 18, '2026-09-07', 104908, 105879, 388.23, NULL, 'C11 Puerto Sandino - Managua - Corinto ', 'Bombero2', NULL, '2026-09-07 13:40:36', NULL, NULL, 388.23, 'Rolando José Silva Poveda ', '284-260184-0004A', 'CONSUMO', 388.23, NULL, NULL, 'BLOQUEADO', 9.46, 10, '6', NULL, 1, 71682, 91),
(1072, 89, '2026-09-07', 595, 914, 163.53, NULL, 'C70 Esteli', 'Bombero2', NULL, '2026-09-07 16:15:51', NULL, NULL, 163.53, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 163.53, NULL, NULL, 'BLOQUEADO', 7.37, 9, '6', NULL, 1, 71683, 91),
(1073, 58, '2026-09-07', 60064, 60429, 161.97, NULL, 'C57 Locales', 'Bombero2', NULL, '2026-09-07 16:28:23', NULL, NULL, 161.97, 'Edgard Eduardo Velásquez Gutiérrez ', '001-070705-1057A', 'CONSUMO', 161.97, NULL, NULL, 'APROBADO', 8.52, 9, '6', NULL, 1, 71684, 91),
(1074, 59, '2026-09-07', 1365451, 1365757, 140.66, NULL, 'C59 Local', 'Bombero2', NULL, '2026-09-07 20:33:23', NULL, NULL, 140.66, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 140.66, NULL, NULL, 'APROBADO', 8.24, 9, '6', NULL, 1, 71685, 91),
(1075, 61, '2026-09-07', 1143643, 1144073, 271.68, NULL, 'C61 Rubenia-Jinotepe-Chinandega', 'Bombero2', NULL, '2026-09-07 20:36:54', NULL, NULL, 271.68, 'Mario Francisco Romero González ', '003-120983-0002N', 'CONSUMO', 271.68, NULL, NULL, 'BLOQUEADO', 5.99, 9, '6', NULL, 1, 71686, 91),
(1076, 60, '2026-09-07', 40294, 40407, 70.72, NULL, 'C60 Inamculada-Jinotepe-Locales', 'Bombero2', NULL, '2026-09-07 20:37:54', NULL, NULL, 70.72, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 70.72, NULL, NULL, 'BLOQUEADO', 6.04, 9, '6', NULL, 1, 71687, 91),
(1077, 48, '2026-09-07', 100058, 100357, 151.25, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-09-07 20:39:54', NULL, NULL, 151.25, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 151.25, NULL, NULL, 'BLOQUEADO', 7.49, 10, '6', NULL, 1, 71688, 91),
(1078, 63, '2026-09-07', 34057, 34087, 14.36, NULL, 'C66 Prueba de equipo', 'Bombero2', NULL, '2026-09-07 20:41:00', NULL, NULL, 14.36, '', '', 'CONSUMO', 14.36, NULL, NULL, 'APROBADO', 7.91, 9, '6', NULL, 1, 71689, 91),
(1079, 64, '2026-09-07', 11654, 11692, 27.78, NULL, 'C67 Laboratorio López ', 'Bombero2', NULL, '2026-09-07 20:42:08', NULL, NULL, 27.78, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 27.78, NULL, NULL, 'APROBADO', 5.12, 9, '6', NULL, 1, 71690, 91),
(1080, 65, '2026-09-07', 21206, 21522, 138.15, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-09-07 20:43:57', NULL, NULL, 138.15, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 138.15, NULL, NULL, 'APROBADO', 8.65, 9, '6', NULL, 1, 71691, 91),
(1081, 59, '2026-09-07', 1365757, 1365766, 3.79, NULL, 'C59 Relleno de Filtro', 'Bombero2', NULL, '2026-09-07 20:44:52', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 9, 9, '6', NULL, 1, 71692, 91),
(1082, 12, '2026-09-07', 335622, 335679, 37.40, NULL, 'LPG2-087 Managua', 'Bombero2', NULL, '2026-09-07 20:45:49', NULL, NULL, 37.4, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 37.4, NULL, NULL, 'BLOQUEADO', 5.8, 15, '6', NULL, 1, 71693, 91),
(1083, 80, '2026-09-07', 0, 0, 124.48, NULL, '', 'Bombero2', NULL, '2026-09-07 20:46:11', NULL, NULL, 124.48, '', '', 'CONSUMO', 124.48, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71694, 91),
(1084, 88, '2026-09-07', 5552, 5678, 93.36, NULL, 'C69 Managua-Corinto', 'Bombero2', NULL, '2026-09-07 20:47:18', NULL, NULL, 93.36, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 93.36, NULL, NULL, 'BLOQUEADO', 5.12, 9, '6', NULL, 1, 71695, 91),
(1085, 45, '2026-09-07', 91221, 91670, 243.16, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-09-07 20:48:17', NULL, NULL, 243.16, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 243.16, NULL, NULL, 'BLOQUEADO', 6.99, 10, '6', NULL, 1, 71696, 91),
(1086, 5, '2026-09-07', 2524, 2595, 42.01, NULL, 'C14 Pavinic-Los Rocha ', 'Bombero2', NULL, '2026-09-07 20:49:34', NULL, NULL, 42.01, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 42.01, NULL, NULL, 'BLOQUEADO', 6.42, 12, '6', NULL, 1, 71697, 91),
(1087, 40, '2026-09-07', 75796, 76119, 142.18, NULL, 'C62 Managua-Coyotepe-Sebaco', 'Bombero2', NULL, '2026-09-07 20:50:37', NULL, NULL, 142.18, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 142.18, NULL, NULL, 'APROBADO', 8.6, 9, '6', NULL, 1, 71698, 91),
(1088, 79, '2026-09-07', 11536, 11799, 108.44, NULL, 'C43 Nindiri-Leon', 'Bombero2', NULL, '2026-09-07 20:51:41', NULL, NULL, 108.44, 'Omar Patricio Osejo Amador ', '441-240882-0002F', 'CONSUMO', 108.44, NULL, NULL, 'APROBADO', 9.18, 8, '6', NULL, 1, 71699, 91),
(1089, 44, '2026-09-07', 52840, 53188, 149.27, NULL, 'C19 Puerto Corinto-miramar', 'Bombero2', NULL, '2026-09-07 20:52:52', NULL, NULL, 149.27, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 149.27, NULL, NULL, 'APROBADO', 8.82, 9, '6', NULL, 1, 71700, 91),
(1090, 51, '2026-09-07', 102866, 103315, 209.52, NULL, 'C37 Puerto Sandino', 'Bombero2', NULL, '2026-09-07 22:39:45', NULL, NULL, 209.52, 'Julio José Mendoza Ruíz ', '001-080300-1009B', 'CONSUMO', 209.52, NULL, NULL, 'BLOQUEADO', 8.11, 10, '6', NULL, 1, 71701, 91),
(1091, 75, '2026-09-08', 0, 0, 7.35, NULL, '', 'Bombero2', NULL, '2026-09-08 11:07:12', NULL, NULL, 7.35, '', '', 'CONSUMO', 7.35, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71702, 93),
(1092, 75, '2026-09-08', 0, 0, 12.99, NULL, '', 'Bombero2', NULL, '2026-09-08 11:07:30', NULL, NULL, 12.99, '', '', 'CONSUMO', 12.99, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71703, 93),
(1093, 8, '2026-09-08', 664028, 664219, 47.14, NULL, 'AJS-03 Miramontes', 'Bombero2', NULL, '2026-09-08 11:09:48', NULL, NULL, 47.14, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 47.14, NULL, NULL, 'APROBADO', 15.31, 15, '6', NULL, 1, 71704, 93),
(1094, 15, '2026-09-08', 20122, 20669, 128.82, NULL, 'AJS-02 Granada-Malacatoya-Boaco', 'Bombero2', NULL, '2026-09-08 11:11:00', NULL, NULL, 128.82, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 128.82, NULL, NULL, 'BLOQUEADO', 16.06, 13, '6', NULL, 1, 71705, 93),
(1095, 9, '2026-09-08', 19056, 19226, 60.57, NULL, 'C42 Arena-Coogrant', 'Bombero2', NULL, '2026-09-08 11:11:56', NULL, NULL, 60.57, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 60.57, NULL, NULL, 'APROBADO', 10.62, 15, '6', NULL, 1, 71706, 93),
(1096, 88, '2026-09-08', 5678, 5755, 52.67, NULL, 'C69 Managua', 'Bombero2', NULL, '2026-09-08 11:13:27', NULL, NULL, 52.67, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 52.67, NULL, NULL, 'BLOQUEADO', 5.51, 9, '6', NULL, 1, 71707, 93),
(1097, 75, '2026-09-08', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-09-08 15:32:30', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71708, 93),
(1098, 62, '2026-09-08', 55254, 55263, 3.79, NULL, 'C64 Relleno de Filtro', 'Bombero2', NULL, '2026-09-08 15:33:52', NULL, NULL, 3.79, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 9, 9, '6', NULL, 1, 71709, 95),
(1099, 62, '2026-09-08', 55263, 55272, 4.37, NULL, 'C64 Relleno de Filtro', 'Bombero2', NULL, '2026-09-08 15:35:02', NULL, NULL, 4.37, '', '', 'CONSUMO', 4.37, NULL, NULL, 'APROBADO', 7.8, 9, '6', NULL, 1, 71710, 95),
(1100, 59, '2026-09-08', 1365766, 1365956, 119.32, NULL, 'C59 Leon-Loyola-Los Brasiles', 'Bombero2', NULL, '2026-09-08 15:35:58', NULL, NULL, 119.32, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 119.31, NULL, NULL, 'BLOQUEADO', 6.02, 9, '6', NULL, 1, 71711, 93),
(1101, 6, '2026-09-08', 51110, 51557, 113.83, NULL, 'C38 Coonserva', 'Bombero2', NULL, '2026-09-08 15:36:50', NULL, NULL, 113.83, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 113.83, NULL, NULL, 'APROBADO', 14.88, 15, '6', NULL, 1, 71712, 93),
(1102, 40, '2026-09-08', 76119, 76271, 90.65, NULL, 'C62 Competra-Jinotepe', 'Bombero2', NULL, '2026-09-08 15:38:16', NULL, NULL, 90.65, 'Juan Rafael Gaitán Chévez ', '121-231082-0003C', 'CONSUMO', 90.64, NULL, NULL, 'BLOQUEADO', 6.36, 9, '6', NULL, 1, 71713, 93),
(1103, 60, '2026-09-08', 40407, 40561, 107.81, NULL, 'C60 Locales-Granada', 'Bombero2', NULL, '2026-09-08 15:58:21', NULL, NULL, 107.81, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 107.81, NULL, NULL, 'BLOQUEADO', 5.41, 9, '6', NULL, 1, 71714, 93),
(1104, 63, '2026-09-08', 34087, 34251, 72.27, NULL, 'C66 Martinez e Hijos-Corrales Verde', 'Bombero2', NULL, '2026-09-08 16:04:48', NULL, NULL, 72.27, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 72.27, NULL, NULL, 'APROBADO', 8.56, 9, '6', NULL, 1, 71715, 93),
(1105, 42, '2026-09-08', 2135703, 2136301, 298.59, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-09-08 17:01:59', NULL, NULL, 298.59, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 298.59, NULL, NULL, 'BLOQUEADO', 7.58, 10, '6', NULL, 1, 71716, 93),
(1106, 43, '2026-09-08', 78652, 79101, 273.30, NULL, 'C18 Puerto Sandino', 'Bombero2', NULL, '2026-09-08 17:03:09', NULL, NULL, 273.3, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 273.3, NULL, NULL, 'BLOQUEADO', 6.22, 10, '6', NULL, 1, 71717, 93),
(1107, 79, '2026-09-08', 11799, 12086, 95.64, NULL, 'C43 Chinandega', 'Bombero2', NULL, '2026-09-08 17:04:43', NULL, NULL, 95.64, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 95.64, NULL, NULL, 'BLOQUEADO', 11.37, 8, '6', NULL, 1, 71718, 93),
(1108, 48, '2026-09-08', 100357, 100657, 175.14, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-09-08 17:05:55', NULL, NULL, 175.14, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 175.13, NULL, NULL, 'BLOQUEADO', 6.48, 10, '6', NULL, 1, 71719, 93),
(1109, 64, '2026-09-08', 11692, 11892, 95.57, NULL, 'C67 Pavinic-Casares', 'Bombero2', NULL, '2026-09-08 17:06:51', NULL, NULL, 95.57, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 95.57, NULL, NULL, 'APROBADO', 7.94, 9, '6', NULL, 1, 71720, 93),
(1110, 83, '2026-09-08', 0, 0, 1892.50, NULL, '', 'Bombero2', NULL, '2026-09-08 22:02:09', NULL, NULL, 1892.5, '', '', 'CONSUMO', 1892.5, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71721, 93),
(1111, 12, '2026-09-08', 335679, 335952, 92.00, NULL, 'LPG2-097 Chinandega', 'Bombero2', NULL, '2026-09-08 22:04:48', NULL, NULL, 92, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 92, NULL, NULL, 'BLOQUEADO', 11.23, 15, '6', NULL, 1, 71722, 93),
(1112, 5, '2026-09-08', 2595, 3150, 181.52, NULL, 'C14 Mulukuku', 'Bombero2', NULL, '2026-09-08 22:09:02', NULL, NULL, 181.52, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 181.51, NULL, NULL, 'APROBADO', 11.57, 12, '6', NULL, 1, 71723, 93),
(1113, 11, '2026-09-08', 300985, 301154, 66.11, NULL, 'LPG1-096 San Benito-Cofradia', 'Bombero2', NULL, '2026-09-08 22:10:48', NULL, NULL, 66.11, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 66.11, NULL, NULL, 'BLOQUEADO', 9.68, 15, '6', NULL, 1, 71724, 93),
(1114, 61, '2026-09-08', 1144073, 1144676, 261.99, NULL, 'C61 Matagalpa', 'Bombero2', 'Bombero2', '2026-09-09 07:56:48', NULL, NULL, 261.99, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 261.99, NULL, NULL, 'APROBADO', 8.71, 9, '6', NULL, 1, 71725, 94),
(1115, 57, '2026-09-08', 67305, 67327, 38.91, NULL, 'C56 Prueba de Taller', 'Bombero2', 'Bombero2', '2026-09-09 07:56:20', NULL, NULL, 38.91, '', '', 'CONSUMO', 38.91, NULL, NULL, 'BLOQUEADO', 2.14, 8, '6', NULL, 1, 71726, 94),
(1116, 9, '2026-09-08', 19226, 19512, 70.41, NULL, 'C42 Duque Estrada', 'Bombero2', NULL, '2026-09-08 22:18:51', NULL, NULL, 70.41, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 70.41, NULL, NULL, 'APROBADO', 15.38, 15, '6', NULL, 1, 71727, 94),
(1117, 18, '2026-09-09', 105879, 106631, 314.91, NULL, 'C11 Puerto Corinto', 'Bombero2', NULL, '2026-09-09 11:07:50', NULL, NULL, 314.91, 'Rolando José Silva Poveda ', '284-260184-0004A', 'CONSUMO', 314.91, NULL, NULL, 'BLOQUEADO', 9.04, 10, '6', NULL, 1, 71728, 97),
(1118, 68, '2026-09-09', 289127, 289422, 27.65, NULL, 'Toyota H Rescate', 'Bombero2', NULL, '2026-09-09 11:14:17', NULL, NULL, 27.65, '', '', 'CONSUMO', 27.65, NULL, NULL, 'APROBADO', 40.35, 45, '5', NULL, 1, 71729, 97),
(1119, 39, '2026-09-09', 58024, 58750, 360.47, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-09-09 11:16:57', NULL, NULL, 360.47, 'Ulises Joel Bautista Gómez ', '001-140100-1011D', 'CONSUMO', 360.47, NULL, NULL, 'BLOQUEADO', 7.62, 9, '6', NULL, 1, 71730, 97),
(1120, 8, '2026-09-09', 664219, 664350, 51.03, NULL, 'AJS03 Carrisal - Gustavo Rocha ', 'Bombero2', NULL, '2026-09-09 11:21:00', NULL, NULL, 51.03, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 51.02, NULL, NULL, 'APROBADO', 9.68, 15, '6', NULL, 1, 71731, 97),
(1121, 75, '2026-09-09', 0, 0, 73.27, NULL, '', 'Bombero2', NULL, '2026-09-09 11:21:37', NULL, NULL, 73.27, '', '', 'CONSUMO', 73.27, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71732, 97),
(1122, 45, '2026-09-09', 91670, 92419, 381.73, NULL, 'C21 Puerto Sandino ', 'Bombero2', NULL, '2026-09-09 11:30:09', NULL, NULL, 381.73, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 381.73, NULL, NULL, 'BLOQUEADO', 7.43, 10, '6', NULL, 1, 71733, 97),
(1123, 59, '2026-09-09', 1365956, 1366309, 138.90, NULL, 'C59 Leon', 'Bombero2', NULL, '2026-09-09 20:15:34', NULL, NULL, 138.9, 'Omar Patricio Osejo Amador ', '441-240882-0002F', 'CONSUMO', 138.9, NULL, NULL, 'APROBADO', 9.63, 9, '6', NULL, 1, 71734, 97),
(1125, 80, '2026-09-09', 0, 0, 120.24, NULL, '', 'Bombero2', NULL, '2026-09-09 20:16:45', NULL, NULL, 120.24, '', '', 'CONSUMO', 120.24, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71736, 97),
(1126, 80, '2026-09-09', 0, 0, 134.77, NULL, '', 'Bombero2', NULL, '2026-09-09 20:17:09', NULL, NULL, 134.77, '', '', 'CONSUMO', 134.77, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71737, 97),
(1127, 51, '2026-09-09', 103315, 103911, 288.66, NULL, 'C37 Puerto Sandino', 'Bombero2', NULL, '2026-09-09 20:18:21', NULL, NULL, 288.66, 'Julio José Mendoza Ruíz ', '001-080300-1009B', 'CONSUMO', 288.66, NULL, NULL, 'BLOQUEADO', 7.81, 10, '6', NULL, 1, 71738, 97),
(1128, 75, '2026-09-09', 0, 0, 1000.00, NULL, '', 'Bombero2', NULL, '2026-09-09 20:18:38', NULL, NULL, 1000, '', '', 'CONSUMO', 1000, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71739, 97),
(1129, 88, '2026-09-09', 5755, 5852, 86.79, NULL, 'C69 7 Sur-Rubenia-San Sebastian', 'Bombero2', NULL, '2026-09-09 20:19:31', NULL, NULL, 86.79, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 86.79, NULL, NULL, 'BLOQUEADO', 4.24, 9, '6', NULL, 1, 71740, 97),
(1130, 41, '2026-09-09', 181533, 181883, 174.57, NULL, 'C20 Las Mercedes-Villa Fontana-Matagalpa', 'Bombero2', NULL, '2026-09-09 20:21:02', NULL, NULL, 174.57, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 174.57, NULL, NULL, 'BLOQUEADO', 7.58, 9, '6', NULL, 1, 71741, 97),
(1131, 60, '2026-09-09', 40561, 40702, 85.66, NULL, 'C60 Locales-Diriamba', 'Bombero2', NULL, '2026-09-09 20:21:45', NULL, NULL, 85.66, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 85.66, NULL, NULL, 'BLOQUEADO', 6.24, 9, '6', NULL, 1, 71742, 97),
(1132, 6, '2026-09-09', 51557, 51766, 42.84, NULL, 'C38 Alpha Charley', 'Bombero2', NULL, '2026-09-09 20:22:37', NULL, NULL, 42.84, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 42.84, NULL, NULL, 'APROBADO', 18.42, 15, '6', NULL, 1, 71743, 97),
(1133, 15, '2026-09-09', 20669, 20761, 30.30, NULL, 'AJS-02 Locales-Veracruz', 'Bombero2', NULL, '2026-09-09 20:23:29', NULL, NULL, 30.3, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 30.3, NULL, NULL, 'APROBADO', 11.48, 13, '6', NULL, 1, 71744, 97),
(1134, 10, '2026-09-09', 335930, 336455, 132.84, NULL, 'C47 Carnic', 'Bombero2', NULL, '2026-09-09 20:28:24', NULL, NULL, 132.84, '', '', 'CONSUMO', 132.84, NULL, NULL, 'APROBADO', 14.96, 15, '6', NULL, 1, 71745, 97),
(1135, 80, '2026-09-09', 0, 0, 181.78, NULL, '', 'Bombero2', NULL, '2026-09-09 20:29:13', NULL, NULL, 181.78, '', '', 'CONSUMO', 181.78, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71746, 97),
(1136, 62, '2026-09-09', 55272, 55608, 120.46, NULL, 'C64 Luis Cuadra', 'Bombero2', NULL, '2026-09-09 20:30:21', NULL, NULL, 120.46, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 120.46, NULL, NULL, 'BLOQUEADO', 10.55, 9, '6', NULL, 1, 71747, 97),
(1137, 80, '2026-09-09', 0, 0, 334.80, NULL, '', 'Bombero2', NULL, '2026-09-09 20:31:01', NULL, NULL, 334.8, '', '', 'CONSUMO', 334.8, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71748, 97),
(1138, 42, '2026-09-09', 2136301, 2136748, 214.03, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-09-09 20:31:43', NULL, NULL, 214.03, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 214.03, NULL, NULL, 'BLOQUEADO', 7.9, 10, '6', NULL, 1, 71749, 97),
(1139, 63, '2026-09-09', 34251, 34773, 167.53, NULL, 'C66 El Ayote', 'Bombero2', NULL, '2026-09-09 20:32:56', NULL, NULL, 167.53, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 167.53, NULL, NULL, 'BLOQUEADO', 11.79, 9, '6', NULL, 1, 71750, 97),
(1140, 89, '2026-09-09', 914, 1164, 194.29, NULL, 'C70 Sebaco-Batahola-Gueguense', 'Bombero2', NULL, '2026-09-09 20:34:55', NULL, NULL, 194.29, 'Juan Rafael Gaitán Chévez ', '121-231082-0003C', 'CONSUMO', 194.29, NULL, NULL, 'BLOQUEADO', 4.88, 9, '6', NULL, 1, 71751, 97),
(1141, 64, '2026-09-09', 11892, 12397, 229.28, NULL, 'C67 Wiwili', 'Bombero2', NULL, '2026-09-09 20:36:25', NULL, NULL, 229.28, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 229.28, NULL, NULL, 'APROBADO', 8.34, 9, '6', NULL, 1, 71752, 97),
(1142, 40, '2026-09-09', 76271, 76603, 158.79, NULL, 'C62 Esteli', 'Bombero2', NULL, '2026-09-09 22:13:53', NULL, NULL, 158.79, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 158.79, NULL, NULL, 'BLOQUEADO', 7.9, 9, '6', NULL, 1, 71753, 97),
(1143, 72, '2026-09-09', 89956, 90247, 44.97, NULL, 'Kia 3000/Rescate', 'Bombero2', NULL, '2026-09-09 22:14:21', NULL, NULL, 44.97, '', '', 'CONSUMO', 44.97, NULL, NULL, 'BLOQUEADO', 24.5, 45, '5', NULL, 1, 71754, 97),
(1144, 92, '2026-09-09', 360, 760, 181.35, NULL, 'C71 Rescate', 'Bombero2', NULL, '2026-09-09 22:15:52', NULL, NULL, 181.35, '', '', 'CONSUMO', 181.35, NULL, NULL, 'APROBADO', 8.36, 9, '6', NULL, 1, 71755, 97),
(1145, 65, '2026-09-09', 21522, 22019, 257.08, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-09-09 23:11:27', NULL, NULL, 257.08, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 257.07, NULL, NULL, 'BLOQUEADO', 7.32, 9, '6', NULL, 1, 71756, 97),
(1146, 84, '2026-09-10', 0, 1, 40.02, NULL, 'Toyota L / Cuota Oscar Briceño', 'Bombero2', NULL, '2026-09-10 13:08:06', NULL, NULL, 40.02, '', '', 'CONSUMO', 40.02, NULL, NULL, 'APROBADO', 0.09, 0, '5', NULL, 1, 71757, 99),
(1147, 86, '2026-09-10', 0, 1, 37.60, NULL, 'Toyota H / Cuota Oscar Briceño', 'Bombero2', NULL, '2026-09-10 13:10:11', NULL, NULL, 37.6, '', '', 'CONSUMO', 37.6, NULL, NULL, 'APROBADO', 0.1, 0, '5', NULL, 1, 71758, 99),
(1148, 89, '2026-09-10', 1164, 1337, 84.73, NULL, 'C70 Leon', 'Bombero2', NULL, '2026-09-10 13:11:40', NULL, NULL, 84.73, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 84.73, NULL, NULL, 'APROBADO', 7.75, 9, '6', NULL, 1, 71759, 99),
(1149, 68, '2026-09-10', 289127, 290100, 57.46, NULL, 'Toyota H Relleno', 'Bombero2', NULL, '2026-09-10 13:14:23', NULL, NULL, 57.46, '', '', 'CONSUMO', 57.46, NULL, NULL, 'BLOQUEADO', 64.12, 45, '5', NULL, 1, 71760, 99),
(1150, 75, '2026-09-10', 0, 0, 590.08, NULL, '', 'Bombero2', NULL, '2026-09-10 13:14:53', NULL, NULL, 590.08, '', '', 'CONSUMO', 590.08, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71761, 99),
(1151, 75, '2026-09-10', 0, 0, 6.08, NULL, '', 'Bombero2', NULL, '2026-09-10 13:15:26', NULL, NULL, 6.08, '', '', 'CONSUMO', 6.08, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71762, 99),
(1152, 78, '2026-09-10', 0, 0, 70.95, NULL, '', 'Bombero2', NULL, '2026-09-10 13:16:01', NULL, NULL, 70.95, '', '', 'CONSUMO', 70.94, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71763, 99),
(1153, 19, '2026-09-10', 0, 1, 37.85, NULL, 'Cisterna 115', 'Bombero2', NULL, '2026-09-10 15:29:02', NULL, NULL, 37.85, '', '', 'CONSUMO', 37.85, NULL, NULL, 'APROBADO', 0.1, 0, '0', NULL, 1, 71764, 99),
(1155, 45, '2026-09-10', 92419, 93018, 302.14, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-09-10 15:35:13', NULL, NULL, 302.14, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 302.14, NULL, NULL, 'BLOQUEADO', 7.5, 10, '6', NULL, 1, 71766, 99),
(1156, 42, '2026-09-10', 2136748, 2137047, 143.83, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-09-10 15:36:51', NULL, NULL, 143.83, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 143.83, NULL, NULL, 'BLOQUEADO', 7.86, 10, '6', NULL, 1, 71767, 99),
(1158, 43, '2026-09-10', 79101, 79999, 446.58, NULL, 'C18 Puerto Sandino ', 'Bombero2', NULL, '2026-09-10 15:41:21', NULL, NULL, 446.58, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 446.58, NULL, NULL, 'BLOQUEADO', 7.61, 10, '6', NULL, 1, 71769, 99),
(1159, 11, '2026-09-10', 301154, 301247, 29.56, NULL, 'LPG096 San Benito', 'Bombero2', NULL, '2026-09-10 15:42:58', NULL, NULL, 29.56, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 29.56, NULL, NULL, 'APROBADO', 11.87, 15, '6', NULL, 1, 71770, 99),
(1160, 8, '2026-09-10', 664350, 664504, 37.70, NULL, 'AJS01 Malacatoya', 'Bombero2', NULL, '2026-09-10 15:45:24', NULL, NULL, 37.7, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 37.7, NULL, NULL, 'APROBADO', 15.48, 15, '6', NULL, 1, 71771, 99),
(1161, 48, '2026-09-10', 100657, 101256, 302.33, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-09-10 15:46:54', NULL, NULL, 302.33, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 302.33, NULL, NULL, 'BLOQUEADO', 7.5, 10, '6', NULL, 1, 71772, 99),
(1162, 6, '2026-09-10', 51766, 52189, 97.96, NULL, 'C38 Santo Domingo - Chontales', 'Bombero2', NULL, '2026-09-10 15:48:38', NULL, NULL, 97.96, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 97.96, NULL, NULL, 'APROBADO', 16.33, 15, '6', NULL, 1, 71773, 99),
(1163, 38, '2026-09-10', 87097, 87261, 125.76, NULL, 'C58 Cototepe - Colinas ', 'Bombero2', NULL, '2026-09-10 15:49:52', NULL, NULL, 125.76, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 125.76, NULL, NULL, 'BLOQUEADO', 4.94, 9, '6', NULL, 1, 71774, 99),
(1164, 80, '2026-09-10', 0, 0, 64.45, NULL, '', 'Bombero2', NULL, '2026-09-10 15:50:36', NULL, NULL, 64.45, '', '', 'CONSUMO', 64.45, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71775, 99),
(1165, 62, '2026-09-10', 55608, 56117, 157.25, NULL, 'C64 Mulukuku', 'Bombero2', NULL, '2026-09-10 16:03:25', NULL, NULL, 157.25, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 157.25, NULL, NULL, 'BLOQUEADO', 12.26, 9, '6', NULL, 1, 71776, 99),
(1166, 5, '2026-09-10', 3150, 3933, 305.98, NULL, 'C14 Rosita ', 'Bombero2', NULL, '2026-09-10 16:05:26', NULL, NULL, 305.98, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 305.98, NULL, NULL, 'BLOQUEADO', 9.68, 12, '6', NULL, 1, 71777, 99),
(1167, 75, '2026-09-10', 0, 0, 800.00, NULL, '', 'Bombero2', NULL, '2026-09-10 16:06:06', NULL, NULL, 800, '', '', 'CONSUMO', 800, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71778, 99),
(1168, 81, '2026-09-10', 12, 13, 378.50, NULL, 'Cisterna 54 Verificacion de medida ', 'Bombero2', NULL, '2026-09-10 21:43:59', NULL, NULL, 378.5, '', '', 'CONSUMO', 378.5, NULL, NULL, 'APROBADO', 0.01, 0, '6', NULL, 1, 71779, 99),
(1169, 9, '2026-09-10', 19512, 19672, 46.63, NULL, 'C42 San Martin', 'Bombero2', NULL, '2026-09-10 21:46:10', NULL, NULL, 46.63, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 46.63, NULL, NULL, 'APROBADO', 13.02, 15, '6', NULL, 1, 71780, 99),
(1170, 65, '2026-09-10', 22019, 22334, 140.78, NULL, 'C68 Puerto Sandino-Puerto Sandino', 'Bombero2', NULL, '2026-09-10 22:03:32', NULL, NULL, 140.78, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 140.78, NULL, NULL, 'APROBADO', 8.46, 9, '6', NULL, 1, 71781, 99),
(1171, 41, '2026-09-10', 181883, 182043, 107.89, NULL, 'C20 Montelimar-Ciudad Sandino', 'Bombero2', NULL, '2026-09-10 22:05:07', NULL, NULL, 107.89, 'Juan Ramón Torres Gámez ', '001-040663-0057K', 'CONSUMO', 107.89, NULL, NULL, 'BLOQUEADO', 5.6, 9, '6', NULL, 1, 71782, 99),
(1172, 12, '2026-09-10', 335952, 336019, 46.33, NULL, 'LPG2-097 Managua', 'Bombero2', NULL, '2026-09-10 22:06:08', NULL, NULL, 46.33, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 46.33, NULL, NULL, 'BLOQUEADO', 5.46, 15, '6', NULL, 1, 71783, 99),
(1173, 79, '2026-09-10', 12086, 12454, 141.35, NULL, 'C43 Competra-Chinandega', 'Bombero2', NULL, '2026-09-10 22:07:14', NULL, NULL, 141.35, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 141.35, NULL, NULL, 'BLOQUEADO', 9.85, 8, '6', NULL, 1, 71784, 99),
(1174, 15, '2026-09-10', 20761, 20944, 42.56, NULL, 'AJS-02 Granada', 'Bombero2', NULL, '2026-09-10 22:08:09', NULL, NULL, 42.56, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 42.55, NULL, NULL, 'APROBADO', 16.27, 13, '6', NULL, 1, 71785, 99),
(1175, 64, '2026-09-10', 12787, 13193, 167.65, NULL, 'C67 Rosita', 'Bombero2', NULL, '2026-09-10 22:20:42', NULL, NULL, 167.65, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 167.65, NULL, NULL, 'APROBADO', 9.16, 9, '6', NULL, 1, 71786, 99),
(1176, 39, '2026-09-11', 58750, 59616, 408.42, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-09-11 21:08:02', NULL, NULL, 408.42, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 408.42, NULL, NULL, 'BLOQUEADO', 8.03, 9, '6', NULL, 1, 71787, 101),
(1177, 64, '2026-09-11', 13193, 13334, 58.31, NULL, 'C67 Prodecon', 'Bombero2', NULL, '2026-09-11 21:08:47', NULL, NULL, 58.31, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 58.3, NULL, NULL, 'APROBADO', 9.13, 9, '6', NULL, 1, 71788, 101),
(1178, 82, '2026-09-11', 0, 0, 518.53, NULL, '', 'Bombero2', NULL, '2026-09-11 21:09:10', NULL, NULL, 518.53, '', '', 'CONSUMO', 518.53, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71789, 101),
(1180, 60, '2026-09-11', 40702, 40857, 123.88, NULL, 'C60 Locales-Diriamba', 'Bombero2', 'Bombero2', '2026-09-11 21:43:11', NULL, NULL, 123.88, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 123.88, NULL, NULL, 'BLOQUEADO', 4.74, 9, '6', NULL, 1, 71791, 100),
(1181, 45, '2026-09-11', 93018, 93617, 301.25, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-09-11 21:12:22', NULL, NULL, 301.25, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 301.25, NULL, NULL, 'BLOQUEADO', 7.52, 10, '6', NULL, 1, 71792, 100),
(1182, 55, '2026-09-11', 19460, 19876, 294.67, NULL, 'C46 Libertad-El Crucero', 'Bombero2', NULL, '2026-09-11 21:13:22', NULL, NULL, 294.67, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 294.67, NULL, NULL, 'BLOQUEADO', 5.35, 8, '6', NULL, 1, 71793, 100),
(1183, 63, '2026-09-11', 34773, 35089, 143.99, NULL, 'C66 Locales', 'Bombero2', NULL, '2026-09-11 21:14:07', NULL, NULL, 143.99, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 143.99, NULL, NULL, 'APROBADO', 8.32, 9, '6', NULL, 1, 71794, 100),
(1184, 54, '2026-09-11', 44090, 44420, 227.72, NULL, 'C45 Sebaco-Local-Paraiso', 'Bombero2', NULL, '2026-09-11 21:15:02', NULL, NULL, 227.72, 'Roberto Aldomar Chinchilla Mejía ', '004-140777-0001D', 'CONSUMO', 227.72, NULL, NULL, 'BLOQUEADO', 5.49, 8, '6', NULL, 1, 71795, 100),
(1185, 85, '2026-09-11', 0, 1, 56.39, NULL, 'Isuzu/Cuota Roger Vilchez', 'Bombero2', NULL, '2026-09-11 21:15:31', NULL, NULL, 56.39, '', '', 'CONSUMO', 56.39, NULL, NULL, 'APROBADO', 0.07, 0, '5', NULL, 1, 71796, 100),
(1186, 82, '2026-09-11', 0, 0, 472.48, NULL, '', 'Bombero2', NULL, '2026-09-11 21:20:08', NULL, NULL, 472.48, '', '', 'CONSUMO', 472.48, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71797, 100),
(1187, 75, '2026-09-11', 0, 0, 940.12, NULL, '', 'Bombero2', NULL, '2026-09-11 21:21:07', NULL, NULL, 940.12, '', '', 'CONSUMO', 940.12, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71798, 100),
(1188, 57, '2026-09-11', 67327, 68001, 380.04, NULL, 'C56 El Rama', 'Bombero2', NULL, '2026-09-11 21:22:15', NULL, NULL, 380.04, '', '', 'CONSUMO', 380.04, NULL, NULL, 'BLOQUEADO', 6.72, 8, '6', NULL, 1, 71799, 100),
(1189, 9, '2026-09-11', 19672, 19863, 56.75, NULL, 'C42 Miramontes', 'Bombero2', NULL, '2026-09-11 21:30:58', NULL, NULL, 56.75, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 56.75, NULL, NULL, 'APROBADO', 12.71, 15, '6', NULL, 1, 71800, 100),
(1190, 77, '2026-09-11', 4, 5, 72.10, NULL, 'Toyota L/Cuota Anibal Sevilla', 'Bombero2', NULL, '2026-09-11 21:31:38', NULL, NULL, 72.1, '', '', 'CONSUMO', 72.09, NULL, NULL, 'APROBADO', 0.05, 0, '5', NULL, 1, 71801, 100),
(1191, 80, '2026-09-11', 0, 0, 157.81, NULL, '', 'Bombero2', NULL, '2026-09-11 21:32:26', NULL, NULL, 157.81, '', '', 'CONSUMO', 157.81, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71802, 100),
(1192, 42, '2026-09-11', 2137047, 2137494, 226.49, NULL, 'C15 Puerto Sandino', 'Bombero2', NULL, '2026-09-11 21:33:32', NULL, NULL, 226.49, 'Felipe Ramón Guido Silva ', '006-081093-0000G', 'CONSUMO', 226.49, NULL, NULL, 'BLOQUEADO', 7.46, 10, '6', NULL, 1, 71803, 100),
(1193, 79, '2026-09-11', 12454, 12678, 113.94, NULL, 'C43 Competra-Leon', 'Bombero2', NULL, '2026-09-11 21:36:50', NULL, NULL, 113.94, 'Juan Rafael Gaitán Chévez ', '121-231082-0003C', 'CONSUMO', 113.94, NULL, NULL, 'APROBADO', 7.46, 8, '6', NULL, 1, 71804, 100),
(1194, 11, '2026-09-11', 301247, 301559, 91.66, NULL, 'LPG1-096 Malpaisillo-Chichigalpa-Chinandega', 'Bombero2', NULL, '2026-09-11 21:38:11', NULL, NULL, 91.66, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 91.66, NULL, NULL, 'APROBADO', 12.89, 15, '6', NULL, 1, 71805, 100),
(1195, 8, '2026-09-11', 664504, 664570, 26.37, NULL, 'AJS-03 Proinco-Veracruz', 'Bombero2', NULL, '2026-09-11 21:39:19', NULL, NULL, 26.37, 'Oscar Ramón Sánchez Zavala ', '002-310789-0000P', 'CONSUMO', 26.37, NULL, NULL, 'APROBADO', 9.42, 15, '6', NULL, 1, 71806, 100),
(1196, 80, '2026-09-11', 0, 0, 231.12, NULL, '', 'Bombero2', NULL, '2026-09-11 21:39:40', NULL, NULL, 231.12, '', '', 'CONSUMO', 231.12, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71807, 100),
(1197, 10, '2026-09-11', 336455, 336544, 76.18, NULL, 'C47 Locales', 'Bombero2', NULL, '2026-09-11 21:52:57', NULL, NULL, 76.18, '', '', 'CONSUMO', 76.18, NULL, NULL, 'BLOQUEADO', 4.44, 15, '6', NULL, 1, 71808, 100),
(1198, 65, '2026-09-11', 22334, 22860, 262.89, NULL, 'C68 Puerto Sandino', 'Bombero2', NULL, '2026-09-11 22:22:34', NULL, NULL, 262.89, 'Hanio Antonio Vargas Matamoros ', '084-190684-0000K', 'CONSUMO', 262.89, NULL, NULL, 'BLOQUEADO', 7.57, 9, '6', NULL, 1, 71809, 100),
(1199, 88, '2026-09-11', 5852, 6091, 175.79, NULL, 'C69 Brasiles-Coyotepe-Locales', 'Bombero2', NULL, '2026-09-11 22:24:07', NULL, NULL, 175.79, 'Edgard Eduardo Velásquez Gutiérrez ', '001-070705-1057A', 'CONSUMO', 175.79, NULL, NULL, 'BLOQUEADO', 5.15, 9, '6', NULL, 1, 71810, 100),
(1201, 12, '2026-09-12', 336019, 336413, 166.72, NULL, 'LPG1-097 La Mina', 'Bombero2', NULL, '2026-09-12 21:54:13', NULL, NULL, 166.72, 'Luis Felipe  Estrada Olivares', '0011611800028U', 'CONSUMO', 166.72, NULL, NULL, 'BLOQUEADO', 8.95, 15, '6', NULL, 1, 71812, 103),
(1202, 41, '2026-09-12', 182043, 182695, 336.09, NULL, 'C20 Santo Tomas', 'Bombero2', NULL, '2026-09-12 21:56:21', NULL, NULL, 336.09, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 336.09, NULL, NULL, 'BLOQUEADO', 7.34, 9, '6', NULL, 1, 71813, 102),
(1203, 55, '2026-09-12', 19876, 19976, 89.02, NULL, 'C46 San Benito', 'Bombero2', NULL, '2026-09-12 21:57:41', NULL, NULL, 89.02, 'Enrique Yamir Varela Gutiérrez ', '001-180799-1016T', 'CONSUMO', 89.02, NULL, NULL, 'BLOQUEADO', 4.24, 8, '6', NULL, 1, 71814, 102),
(1204, 65, '2026-09-12', 22860, 22869, 3.79, NULL, 'C68 Relleno de Filtro', 'Bombero2', NULL, '2026-09-12 21:58:46', NULL, NULL, 3.79, '', '', 'CONSUMO', 3.79, NULL, NULL, 'APROBADO', 9, 9, '6', NULL, 1, 71815, 103),
(1205, 75, '2026-09-12', 0, 0, 509.03, NULL, '', 'Bombero2', NULL, '2026-09-12 21:59:08', NULL, NULL, 509.03, '', '', 'CONSUMO', 509.03, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71816, 102),
(1206, 40, '2026-09-12', 77110, 77594, 222.00, NULL, 'C62 El Rama', 'Bombero2', 'Bombero2', '2026-09-13 18:41:58', NULL, NULL, 222, 'Omar Patricio Osejo Amador ', '441-240882-0002F', 'CONSUMO', 222, NULL, NULL, 'APROBADO', 8.25, 9, '6', NULL, 1, 71817, 102),
(1207, 38, '2026-09-12', 87261, 87434, 83.85, NULL, 'C58 Puerto Corinto', 'Bombero2', NULL, '2026-09-12 22:03:55', NULL, NULL, 83.85, 'José Santos Guido Somarriba ', '281-221272-0011J', 'CONSUMO', 83.85, NULL, NULL, 'APROBADO', 7.82, 9, '6', NULL, 1, 71818, 102),
(1208, 15, '2026-09-12', 20944, 21289, 78.89, NULL, 'JS-02 Locales', 'Bombero2', NULL, '2026-09-12 22:04:35', NULL, NULL, 78.89, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 78.89, NULL, NULL, 'BLOQUEADO', 16.54, 13, '6', NULL, 1, 71819, 102),
(1209, 5, '2026-09-12', 3933, 4904, 269.89, NULL, 'C14 Rosita', 'Bombero2', NULL, '2026-09-12 22:06:05', NULL, NULL, 269.89, 'Rolando Exequiel Arana  ', '081-111069-0007F', 'CONSUMO', 269.89, NULL, NULL, 'BLOQUEADO', 13.61, 12, '6', NULL, 1, 71820, 102),
(1210, 80, '2026-09-12', 0, 0, 37.85, NULL, '', 'Bombero2', NULL, '2026-09-12 22:06:45', NULL, NULL, 37.85, '', '', 'CONSUMO', 37.85, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71821, 102),
(1211, 47, '2026-09-12', 42470, 42779, 157.63, NULL, 'C27 Puerto Sandino', 'Bombero2', NULL, '2026-09-12 22:07:35', NULL, NULL, 157.63, 'Héctor Vladimir Flores López ', '001-170584-0018A', 'CONSUMO', 157.63, NULL, NULL, 'BLOQUEADO', 7.41, 9, '6', NULL, 1, 71822, 102),
(1212, 89, '2026-09-12', 1337, 1691, 239.07, NULL, 'C70 Batahola-Santo Domingo-Esteli', 'Bombero2', NULL, '2026-09-12 22:10:27', NULL, NULL, 239.07, 'Edgard Eduardo Velásquez Gutiérrez ', '001-070705-1057A', 'CONSUMO', 239.07, NULL, NULL, 'BLOQUEADO', 5.61, 9, '6', NULL, 1, 71823, 102),
(1213, 6, '2026-09-12', 52189, 52400, 48.93, NULL, 'C38 Proinco-Chacaraseca', 'Bombero2', NULL, '2026-09-12 22:11:39', NULL, NULL, 48.93, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 48.93, NULL, NULL, 'APROBADO', 16.32, 15, '6', NULL, 1, 71824, 102),
(1214, 75, '2026-09-12', 0, 0, 40.00, NULL, '', 'Bombero2', NULL, '2026-09-12 22:11:53', NULL, NULL, 40, '', '', 'CONSUMO', 40, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71825, 102),
(1215, 39, '2026-09-12', 59616, 59760, 68.80, NULL, 'C65 Puerto Sandino', 'Bombero2', NULL, '2026-09-12 22:12:53', NULL, NULL, 68.8, 'Jorge Ulises Ney Dávila ', '001-300779-0006F', 'CONSUMO', 68.8, NULL, NULL, 'APROBADO', 7.91, 9, '6', NULL, 1, 71826, 102),
(1216, 79, '2026-09-12', 12678, 12950, 93.88, NULL, 'C43 Chinandega', 'Bombero2', NULL, '2026-09-12 22:14:24', NULL, NULL, 93.88, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 93.88, NULL, NULL, 'BLOQUEADO', 10.98, 8, '6', NULL, 1, 71827, 102),
(1217, 48, '2026-09-12', 101256, 101859, 344.44, NULL, 'C30 Puerto Sandino', 'Bombero2', NULL, '2026-09-12 22:15:35', NULL, NULL, 344.44, 'Felipe Ramón Guido Somarriba ', '281-200971-0018X', 'CONSUMO', 344.44, NULL, NULL, 'BLOQUEADO', 6.62, 10, '6', NULL, 1, 71828, 102),
(1218, 54, '2026-09-12', 44420, 44942, 149.90, NULL, 'C45 Leon-Jinotepe', 'Bombero2', NULL, '2026-09-12 22:17:14', NULL, NULL, 149.9, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 149.9, NULL, NULL, 'BLOQUEADO', 13.18, 8, '6', NULL, 1, 71829, 102),
(1219, 44, '2026-09-12', 53188, 53861, 274.63, NULL, 'C19 Puerto Corinto', 'Bombero2', NULL, '2026-09-12 22:18:28', NULL, NULL, 274.63, 'Ronald Antonio Guerrero Solorzano ', '001-221175-0072H', 'CONSUMO', 274.63, NULL, NULL, 'APROBADO', 9.28, 9, '6', NULL, 1, 71830, 102),
(1220, 62, '2026-09-12', 56117, 56634, 182.78, NULL, 'C64 Mulukuku', 'Bombero2', NULL, '2026-09-12 22:19:24', NULL, NULL, 182.78, 'Fernando José Martínez López ', '001-130490-0055S', 'CONSUMO', 182.78, NULL, NULL, 'BLOQUEADO', 10.71, 9, '6', NULL, 1, 71831, 102),
(1221, 80, '2026-09-12', 0, 0, 155.61, NULL, '', 'Bombero2', NULL, '2026-09-12 22:20:13', NULL, NULL, 155.61, '', '', 'CONSUMO', 155.6, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71832, 102),
(1222, 11, '2026-09-12', 301559, 301641, 42.55, NULL, 'LPG1-096 Managua', 'Bombero2', NULL, '2026-09-12 22:20:58', NULL, NULL, 42.55, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 42.55, NULL, NULL, 'BLOQUEADO', 7.29, 15, '6', NULL, 1, 71833, 102),
(1223, 60, '2026-09-12', 40857, 40951, 101.31, NULL, 'C60 Locales', 'Bombero2', NULL, '2026-09-12 22:21:53', NULL, NULL, 101.31, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 101.31, NULL, NULL, 'BLOQUEADO', 3.53, 9, '6', NULL, 1, 71834, 102),
(1224, 80, '2026-09-12', 0, 0, 160.83, NULL, '', 'Bombero2', NULL, '2026-09-12 22:22:12', NULL, NULL, 160.83, '', '', 'CONSUMO', 160.83, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71835, 102),
(1225, 80, '2026-09-12', 0, 0, 129.81, NULL, '', 'Bombero2', NULL, '2026-09-12 22:22:30', NULL, NULL, 129.81, '', '', 'CONSUMO', 129.81, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71836, 102),
(1226, 64, '2026-09-12', 13334, 13529, 100.62, NULL, 'C67 Miramontes-Llansa-Ramos', 'Bombero2', NULL, '2026-09-12 22:23:33', NULL, NULL, 100.62, 'Eladio Agustín Peralta Arauz ', '291-291279-0000X', 'CONSUMO', 100.62, NULL, NULL, 'APROBADO', 7.33, 9, '6', NULL, 1, 71837, 102),
(1227, 59, '2026-09-12', 1366309, 1366860, 293.81, NULL, 'C59 Nejapa-Los Brasiles-Sebaco-Leon-Loyola', 'Bombero2', NULL, '2026-09-12 22:24:23', NULL, NULL, 293.81, '', '', 'CONSUMO', 293.81, NULL, NULL, 'BLOQUEADO', 7.1, 9, '6', NULL, 1, 71838, 102),
(1228, 10, '2026-09-12', 336544, 336907, 133.34, NULL, 'C47 Chinandega-Cemento-Proinco', 'Bombero2', NULL, '2026-09-12 22:25:28', NULL, NULL, 133.34, '', '', 'CONSUMO', 133.34, NULL, NULL, 'BLOQUEADO', 10.3, 15, '6', NULL, 1, 71839, 102),
(1229, 80, '2026-09-12', 0, 0, 144.67, NULL, '', 'Bombero2', NULL, '2026-09-12 22:25:45', NULL, NULL, 144.67, '', '', 'CONSUMO', 144.67, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71840, 102),
(1230, 45, '2026-09-12', 93617, 94214, 291.47, NULL, 'C21 Puerto Sandino', 'Bombero2', NULL, '2026-09-12 22:26:32', NULL, NULL, 291.47, 'Reynaldo José Sánchez Gutiérrez ', '001-200864-0055A', 'CONSUMO', 291.47, NULL, NULL, 'BLOQUEADO', 7.76, 10, '6', NULL, 1, 71841, 102),
(1231, 63, '2026-09-12', 35089, 35376, 129.17, NULL, 'C66 Nandaime', 'Bombero2', NULL, '2026-09-12 22:27:15', NULL, NULL, 129.17, '', '', 'CONSUMO', 129.17, NULL, NULL, 'APROBADO', 8.41, 9, '6', NULL, 1, 71842, 102),
(1232, 57, '2026-09-12', 68001, 68361, 211.13, NULL, 'C56 Santo Tomas', 'Bombero2', NULL, '2026-09-12 22:28:07', NULL, NULL, 211.13, 'Dionisio Gilberto Rios Pulidos ', '290-010583-0000C', 'CONSUMO', 211.13, NULL, NULL, 'BLOQUEADO', 6.45, 8, '6', NULL, 1, 71843, 102),
(1233, 41, '2026-09-12', 182695, 182873, 112.65, NULL, 'C20 Montelimar', 'Bombero2', NULL, '2026-09-12 22:28:44', NULL, NULL, 112.65, 'José Luis Parrales Gonzalez ', '001-020193-0004C', 'CONSUMO', 112.65, NULL, NULL, 'BLOQUEADO', 5.97, 9, '6', NULL, 1, 71844, 102),
(1234, 79, '2026-09-14', 12950, 13233, 150.23, NULL, 'C43 Competra-Rivas', 'Bombero2', NULL, '2026-09-14 13:53:35', NULL, NULL, 150.23, 'Juan Rafael Gaitán Chévez ', '121-231082-0003C', 'CONSUMO', 150.23, NULL, NULL, 'APROBADO', 7.14, 8, '6', NULL, 1, 71845, 104),
(1235, 54, '2026-09-14', 44942, 45373, 193.04, NULL, 'C45 Esteli-Managua-Masaya', 'Bombero2', NULL, '2026-09-14 13:54:50', NULL, NULL, 193.04, 'Edgard Eduardo Velásquez Gutiérrez ', '001-070705-1057A', 'CONSUMO', 193.04, NULL, NULL, 'APROBADO', 8.44, 8, '6', NULL, 1, 71846, 104),
(1236, 11, '2026-09-14', 301641, 301748, 38.20, NULL, 'LPG1-096 Managua-San Benito', 'Bombero2', NULL, '2026-09-14 13:56:00', NULL, NULL, 38.2, 'Agustín  Miranda  ', '603-270878-0004D', 'CONSUMO', 38.2, NULL, NULL, 'APROBADO', 10.63, 15, '6', NULL, 1, 71847, 104),
(1237, 59, '2026-09-14', 1366860, 1367346, 229.85, NULL, 'C59 Puma-Los Brasiles-Matagalpa', 'Bombero2', NULL, '2026-09-14 14:04:31', NULL, NULL, 229.85, 'Otoniel Israel Vásquez Chávez ', '001-100884-0013Y', 'CONSUMO', 229.85, NULL, NULL, 'BLOQUEADO', 8, 9, '6', NULL, 1, 71848, 104),
(1238, 40, '2026-09-14', 77594, 77825, 108.34, NULL, 'C62 El Crucero-Leon', 'Bombero2', NULL, '2026-09-14 16:19:39', NULL, NULL, 108.34, 'Evert Antonio Solorzano Carvajal ', '001-270694-0028K', 'CONSUMO', 108.34, NULL, NULL, 'APROBADO', 8.07, 9, '6', NULL, 1, 71849, 104),
(1239, 15, '2026-09-14', 21289, 21683, 99.59, NULL, 'AJS-02 Granada-Rivas', 'Bombero2', NULL, '2026-09-14 16:20:43', NULL, NULL, 99.59, 'Marlon Javier Reyes Delgado ', '001-021003-1016A', 'CONSUMO', 99.59, NULL, NULL, 'APROBADO', 14.97, 13, '6', NULL, 1, 71850, 104),
(1240, 80, '2026-09-14', 0, 0, 20.90, NULL, '', 'Bombero2', NULL, '2026-09-14 17:09:36', NULL, NULL, 20.9, '', '', 'CONSUMO', 20.9, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71851, 104),
(1241, 6, '2026-09-14', 52400, 52778, 105.38, NULL, 'C38 Miramontes-Llansa', 'Bombero2', NULL, '2026-09-14 20:08:14', NULL, NULL, 105.38, 'Ricardo Arturo Mayorga Aragón ', '401-121187-0007G', 'CONSUMO', 105.38, NULL, NULL, 'APROBADO', 13.59, 15, '6', NULL, 1, 71852, 104),
(1242, 78, '2026-09-14', 0, 0, 68.95, NULL, '', 'Bombero2', NULL, '2026-09-14 20:08:39', NULL, NULL, 68.95, '', '', 'CONSUMO', 68.95, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71853, 104),
(1243, 80, '2026-09-14', 0, 0, 210.63, NULL, '', 'Bombero2', NULL, '2026-09-14 20:09:03', NULL, NULL, 210.63, '', '', 'CONSUMO', 210.63, NULL, NULL, 'APROBADO', 0, 0, '7', NULL, 1, 71854, 104),
(1244, 60, '2026-09-14', 40951, 41108, 125.74, NULL, 'C60 Diriamba-Locales', 'Bombero2', NULL, '2026-09-14 20:10:15', NULL, NULL, 125.74, 'Lesther José Espinoza Martínez ', '001-030590-0020P', 'CONSUMO', 125.74, NULL, NULL, 'BLOQUEADO', 4.71, 9, '6', NULL, 1, 71855, 104),
(1245, 10, '2026-09-14', 336907, 337071, 74.66, NULL, 'C47 TCF-Rubenia-Granada', 'Bombero2', NULL, '2026-09-14 20:11:41', NULL, NULL, 74.66, '', '', 'CONSUMO', 74.66, NULL, NULL, 'BLOQUEADO', 8.31, 15, '6', NULL, 1, 71856, 104),
(1246, 61, '2026-09-14', 1144676, 1145244, 280.31, NULL, 'C61 Locales-Matagalpa-Jinotepe', 'Bombero2', NULL, '2026-09-14 20:13:03', NULL, NULL, 280.31, 'Denis Alberto Corea Olivas ', '001-220171-0006S', 'CONSUMO', 280.31, NULL, NULL, 'BLOQUEADO', 7.68, 9, '6', NULL, 1, 71857, 104),
(1247, 88, '2026-09-14', 6091, 6474, 271.70, NULL, 'C69 Locales-Competra-Masaya', 'Bombero2', NULL, '2026-09-14 21:54:17', NULL, NULL, 271.7, 'Alvaro Gregorio Gutiérrez Reyes ', '241-130373-0010D', 'CONSUMO', 271.69, NULL, NULL, 'BLOQUEADO', 5.33, 9, '6', NULL, 1, 71858, 104),
(1248, 79, '2026-09-14', 13233, 13431, 76.77, NULL, 'C43 San Vicente-Leon-Los Cocos-Batahola', 'Bombero2', NULL, '2026-09-14 21:55:13', NULL, NULL, 76.77, 'Milton José Mendoza Alanis ', '001-110581-0010U', 'CONSUMO', 76.77, NULL, NULL, 'APROBADO', 9.77, 8, '6', NULL, 1, 71859, 104);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_trabajo_realizado`
--

CREATE TABLE `registro_trabajo_realizado` (
  `id` int(11) NOT NULL,
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
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_crea` int(11) DEFAULT NULL,
  `usuario_actualiza` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `fecha_registro`, `fecha_actualizacion`, `usuario_crea`, `usuario_actualiza`, `estado`) VALUES
(1, 'administrador', NULL, '2025-08-08 14:45:56', '2025-09-02 17:18:02', 1, 1, 1),
(2, 'SUPERVISOR DE MANTENIMIENTO', 'SUPERVISA', '2025-09-02 08:56:24', '2025-09-02 08:56:24', 1, 1, 1),
(3, 'Auxiliar de Bomba', '', '2026-06-01 15:52:13', '2026-06-01 15:52:13', 1, 1, 1),
(4, 'Operaciones', 'Jefe de Operaciones', '2026-08-12 07:54:40', '2026-08-12 07:54:40', 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL,
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
  `solicitante` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`id`, `id_empresa`, `codigo_consecutivo`, `id_vehiculo`, `id_solicitante`, `id_tipo_problema`, `descripcion`, `estado`, `fecha_solicitud`, `fecha_cierre`, `fecha_registro`, `fecha_actualizacion`, `usuario_crea`, `usuario_actualiza`, `fecha_planificacion`, `id_tipo_mantenimiento`, `prioridad`, `id_asignado`, `fecha_asignacion`, `url_foto`, `solicitante`) VALUES
(1, 1, 'SOL-2500001', 1, 1, 1, 'fhgdfdfdgdgdghhddfdfdfdfdfdfdb gafagfggfgdfg', 'EN_PROCESO', '2025-09-21 19:11:11', NULL, '2025-09-21 19:11:11', '2025-09-21 20:14:11', 1, 1, '2025-09-21 00:00:00', 1, 1, 1, NULL, NULL, NULL),
(2, 1, 'SOL-00002', 1, 1, 1, 'Necesito que revisen el motor esta haciendo un ruido extra├▒o ', 'EN_PROCESO', '2025-09-24 23:47:44', NULL, '2025-09-24 23:47:44', '2025-09-24 23:50:21', 1, 1, '2025-09-24 00:00:00', NULL, 1, NULL, NULL, NULL, NULL),
(3, 1, 'SOL-00003', 1, 1, 24, 'ESTA DA├æADO EL ESCAPE\n\nSolicitante: JUAN\nTipo de problema: PREVENTIVO', 'PENDIENTES', '2025-11-04 02:32:50', NULL, '2025-11-04 02:32:50', '2025-11-04 03:10:14', 1, 1, NULL, NULL, 1, 2, '2025-11-04 03:10:14', NULL, 'JUAN'),
(4, 1, 'SOL-00004', 3, 1, 24, 'Se daño la Cruz Cardanica\n\nSolicitante: Alvaro Chamorro\nTipo de problema: PREVENTIVO', 'PENDIENTES', '2026-01-21 17:16:51', NULL, '2026-01-21 17:16:51', '2026-01-21 17:16:51', 1, NULL, NULL, NULL, 1, NULL, NULL, NULL, 'Alvaro Chamorro'),
(5, 1, 'SOL-00005', 3, 1, 24, 'Se daño la Cruz Cardanica\n\nSolicitante: Alvaro Chamorro\nTipo de problema: PREVENTIVO', 'EN_PROCESO', '2026-01-21 17:16:52', NULL, '2026-01-21 17:16:52', '2026-08-07 06:57:19', 1, 1, NULL, NULL, 1, NULL, NULL, NULL, 'Alvaro Chamorro'),
(6, 1, 'SOL-00006', 1, 1, 24, 'asasasasasasasasasasasasasasasas\n\nSolicitante: JUAN PEREZ\nTipo de problema: PREVENTIVO', 'EN_PROCESO', '2026-09-03 21:57:36', NULL, '2026-09-03 21:57:36', '2026-09-03 21:59:11', 1, 1, NULL, NULL, 1, NULL, NULL, NULL, 'JUAN PEREZ'),
(7, 1, 'SOL-00007', 4, 1, 24, 'Problema en la direccion\n\nSolicitante: Alvaro Chamorro\nTipo de problema: PREVENTIVO', 'PENDIENTES', '2026-09-08 12:16:39', NULL, '2026-09-08 12:16:39', '2026-09-08 12:16:39', 1, NULL, NULL, NULL, 1, NULL, NULL, NULL, 'Alvaro Chamorro'),
(8, 1, 'SOL-00008', 4, 1, 24, 'Esto es una prueba de taller\n\nSolicitante: Mario Romero\nTipo de problema: PREVENTIVO\nFoto adjunta: uploads/ordenes/1788904225_28eac8233073234ac98f.jpeg', 'PENDIENTES', '2026-09-08 15:50:25', NULL, '2026-09-08 15:50:25', '2026-09-08 15:50:25', 1, NULL, NULL, NULL, 1, NULL, NULL, 'uploads/ordenes/1788904225_28eac8233073234ac98f.jpeg', 'Mario Romero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_problema`
--

CREATE TABLE `tipos_problema` (
  `id` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `fechaUpdate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuarioCrea` int(11) DEFAULT NULL,
  `usuarioEdita` int(11) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_problema`
--

INSERT INTO `tipos_problema` (`id`, `id_empresa`, `nombre`, `fechaRegistro`, `fechaUpdate`, `usuarioCrea`, `usuarioEdita`, `estado`) VALUES
(1, 1, 'PREVENTIVO', '2025-08-14 14:19:41', '2025-08-14 14:22:51', 1, 1, 'ACTIVO'),
(2, 1, 'CORRECTIVO', '2025-08-14 14:44:37', '2025-08-14 14:44:55', 1, NULL, 'ACTIVO'),
(3, 1, 'PROBLEMAS MEC├üNICOS', '2025-08-14 14:46:43', '2025-08-14 14:48:03', 1, NULL, 'ACTIVO'),
(4, 1, 'PROBLEMAS EL├ëCTRICOS', '2025-08-14 14:47:11', '2025-08-14 14:48:03', 1, NULL, 'ACTIVO'),
(5, 1, 'PROBLEMA DE SUSPENCI├ôN Y DIRECCI├ôN', '2025-08-14 14:47:33', '2025-08-14 14:48:03', 1, NULL, 'ACTIVO'),
(6, 1, 'PROBLEMA EN EL SISTEMA DE ESCAPE', '2025-08-14 14:47:52', '2025-08-14 14:48:03', 1, NULL, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documentos`
--

CREATE TABLE `tipo_documentos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualiza` datetime DEFAULT current_timestamp(),
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_motivo_combustible`
--

CREATE TABLE `tipo_motivo_combustible` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `usuario_crea` varchar(100) DEFAULT NULL,
  `usuario_edita` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualiza` datetime DEFAULT current_timestamp(),
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_motivo_combustible`
--

INSERT INTO `tipo_motivo_combustible` (`id`, `descripcion`, `estado`, `usuario_crea`, `usuario_edita`, `fecha_registro`, `fecha_actualiza`, `id_empresa`) VALUES
(1, 'ENTREGA DE COMBUSTIBLES', 1, '1', NULL, '2025-09-18 02:42:38', '2025-09-17 19:43:25', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_operacion`
--

CREATE TABLE `tipo_operacion` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `UsuarioCrea` varchar(100) DEFAULT NULL,
  `UsuarioEdita` varchar(100) DEFAULT NULL,
  `fechaRegistra` datetime DEFAULT current_timestamp(),
  `fechaActualiza` datetime DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_operacion`
--

INSERT INTO `tipo_operacion` (`id`, `descripcion`, `UsuarioCrea`, `UsuarioEdita`, `fechaRegistra`, `fechaActualiza`, `estado`) VALUES
(1, 'ADMINITRATIVA', '1', NULL, '2025-08-22 01:06:38', NULL, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_unidad`
--

CREATE TABLE `tipo_unidad` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `UsuarioCrea` varchar(100) DEFAULT NULL,
  `UsuarioEdita` varchar(100) DEFAULT NULL,
  `fechaRegistra` datetime DEFAULT current_timestamp(),
  `fechaActualiza` datetime DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_unidad`
--

INSERT INTO `tipo_unidad` (`id`, `descripcion`, `UsuarioCrea`, `UsuarioEdita`, `fechaRegistra`, `fechaActualiza`, `estado`) VALUES
(1, 'RIGIDA', '1', '1', '2025-08-20 01:38:35', '2025-08-20 01:38:35', 'ACTIVO'),
(2, 'COMPUESTA', '1', '1', '2025-08-22 01:13:22', '2025-08-22 01:13:22', 'ACTIVO'),
(3, 'AUTOMOVIL', '1', '1', '2025-08-27 02:59:05', '2025-08-27 02:59:05', 'ACTIVO'),
(4, 'MOTOCICLETA', '1', '1', '2025-08-27 02:59:18', '2025-08-27 02:59:18', 'ACTIVO'),
(5, 'FURGONETAS', '1', '1', '2025-08-27 03:00:08', '2025-08-27 03:00:08', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
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
  `id_superior` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `id_empresa`, `nombre`, `usuario`, `clave`, `correo`, `telefono`, `id_rol`, `estado`, `fechaRegistro`, `fechaUpdate`, `usuarioCrea`, `usuarioEdita`, `apellido`, `id_superior`) VALUES
(1, 1, 'ADMINISTRADOR', 'ADMIN', '$2y$10$hXIVjKc19fw75/ZvI3jmsuXPsYEI9MDdbxrihDSWmVWGFZ573Clrm', 'admin@gsmtransporte.com', '22224444', 1, 'ACTIVO', '2025-08-08 21:17:59', '2025-09-21 12:44:49', 1, 1, 'N', 0),
(2, 1, 'JOSE ANTONIO', 'supervisor1', '$2y$10$XLtnoHmk4Ffu.p4eTgaKbe66i6AtLqAbwM2AD3.X8QtpNmqEEju5S', 'ljmartinez@gmail.com', '7718-7005', 2, 'ACTIVO', '2025-09-02 17:38:55', '2025-09-21 12:44:49', NULL, 1, 'N', 1),
(4, 1, 'Bombero AM', 'Bombero1', '$2y$10$fkUeEJaaeJnyBfTg0xu4FeofZzZVlAJtdEdBiGnxaA7qwC2LLzuee', '', '', 3, 'ACTIVO', '2026-06-01 15:54:40', '2026-06-24 10:04:56', NULL, NULL, NULL, 1),
(5, 1, 'Bombero 2', 'Bombero2', '$2y$10$kO9hlCXBot.WvsgbVGd.heqfBj3t279DWav1HuJsAQ0Unf.tTotje', '', '', 3, 'ACTIVO', '2026-06-01 15:55:29', '2026-06-24 10:04:56', NULL, NULL, NULL, 1),
(6, 1, 'achamorro', 'achamorro', '$2y$10$d/1bjlys5pQRX49bbOtpAeeWKK1AdlUR9h6OsMqiPU6/yQQ2QZCVe', 'achamorro@gcmtransportes.com', '', 4, 'ACTIVO', '2026-08-12 07:56:19', '2026-08-12 07:56:19', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
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
  `tipo_consumo` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `id_empresa`, `codigo_consecutivo`, `placa`, `marca`, `modelo`, `anio`, `kilometraje`, `id_conductor`, `estado`, `motivo_inactividad`, `fechaRegistro`, `fechaUpdate`, `usuarioCrea`, `usuarioEdita`, `idTipoUnidad`, `idTipoOperacion`, `codigo_unidad`, `numero_motor`, `numero_chasis`, `disponible`, `compuesto`, `codigo_centro_costo`, `id_color`, `id_tipo_vehiculo`, `id_tipo_producto`, `rendimiento`, `max_combustible`, `Disponibilidad`, `Color`, `tipoMotor`, `tipo_consumo`) VALUES
(1, 1, NULL, 'M136796', 'FREIGHTLINER', 'N/R', '2000', 17034, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-07-16 17:16:45', NULL, 1, NULL, NULL, 'C01', NULL, NULL, '1', 0, '1-002', NULL, NULL, 32, 18, 605.6, 'Disponible', 'Blanco', NULL, 6),
(2, 1, NULL, 'M201615', 'INTERNATIONAL', 'INTERNATIONAL', '2006', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2026-06-24 17:57:33', NULL, 1, NULL, NULL, 'C08', NULL, NULL, '1', 0, '1-007', NULL, NULL, 32, 0, 605.6, 'Disponible', 'Blanco', NULL, 0),
(3, 1, NULL, 'M211485', 'INTERNATIONAL', 'N/REG', '1998', 22242, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-02 15:01:41', NULL, 0, NULL, NULL, 'C10', NULL, NULL, '1', 0, '1-009', NULL, NULL, 32, 15, 605.6, 'Disponible', 'Blanco', NULL, 6),
(4, 1, NULL, 'M238751', 'INTERNATIONAL', '9200', '1998', 40664, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-04 09:41:48', NULL, 0, NULL, NULL, 'C12', NULL, NULL, '1', 0, '1-011', NULL, NULL, 32, 10, 605.6, 'Disponible', 'Blanco', NULL, 6),
(5, 1, NULL, 'M245551', 'INTERNATIONAL', '9200', '1998', 4904, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-12 22:06:05', NULL, 0, NULL, NULL, 'C14', NULL, NULL, '1', 0, '1-012', NULL, NULL, 32, 12, 605.6, 'Disponible', 'Blanco', NULL, 6),
(6, 1, NULL, 'M354506', 'FREIGHTLINER', 'CLAS MZ', '2004', 52778, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-14 20:08:14', NULL, 0, NULL, NULL, 'C38', NULL, NULL, '1', 0, '1-036', NULL, NULL, 32, 15, 605.6, 'Disponible', 'Negro', NULL, 6),
(7, 1, NULL, 'M354945', 'INTERNATIONAL', '4300DT', '2002', 11784, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-07-22 19:37:54', NULL, 0, NULL, NULL, 'C39', NULL, NULL, '1', 0, '1-037', NULL, NULL, 32, 15, 605.6, 'Disponible', 'Blanco', NULL, 6),
(8, 1, NULL, 'M355017', 'FREIGHTLINER', 'CLASS', '2004', 664570, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-11 21:39:19', NULL, 0, NULL, NULL, 'AJS-03', NULL, NULL, '1', 0, '1-057', NULL, NULL, 32, 15, 0, 'Disponible', 'Blanco', NULL, 6),
(9, 1, NULL, 'M363566', 'INTERNATIONAL', '4900', '1995', 19863, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-11 21:30:58', NULL, 0, 1, NULL, 'C42', NULL, NULL, '1', 0, '1-039', NULL, NULL, 32, 15, 605.6, 'Disponible', 'Blanco', NULL, 6),
(10, 1, NULL, 'M348928', 'INTERNATIONAL', '4300DT', '2004', 337071, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-14 20:11:41', NULL, 0, NULL, NULL, 'C47', '43234343423', '5.23434E+11', '1', 0, '1-044', NULL, NULL, 32, 15, 605.6, 'Disponible', 'Blanco', NULL, 6),
(11, 1, NULL, 'M312310', 'FREIGHTLINER', 'M2106', '2019', 301748, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-14 13:56:00', NULL, 0, NULL, NULL, 'LPG1-096', NULL, NULL, '1', 0, '1-058', NULL, NULL, 34, 15, 605.6, 'Disponible', 'Blanco', NULL, 6),
(12, 1, NULL, 'M312311', 'FREIGHTLINER', 'M2106', '2019', 336413, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-12 21:54:13', NULL, 0, NULL, 1, 'LPG2-097', NULL, NULL, '1', 0, '1-059', NULL, NULL, 34, 15, 605.6, 'Disponible', 'Blanco', NULL, 6),
(13, 1, NULL, 'M371722', 'INTERNATIONAL', '4900', '2000', 461802, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-08-26 15:22:30', NULL, 0, NULL, NULL, 'C49', NULL, NULL, '1', 0, '1-046', NULL, NULL, 32, 15, 605.6, 'Disponible', 'Blanco', NULL, 6),
(14, 1, NULL, 'M328984', 'INTERNATIONAL', '4900DT', '2000', 28387, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-01 16:34:02', NULL, 0, NULL, NULL, 'AJS01', NULL, NULL, '1', 0, '1-055', NULL, NULL, 32, 15, 605.6, 'Disponible', 'Blanco', NULL, 6),
(15, 1, NULL, 'M347031', 'INTERNATIONAL', '4900', '1999', 21683, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-14 16:20:43', NULL, 0, NULL, NULL, 'AJS02', NULL, NULL, '1', 0, '1-056', NULL, NULL, 32, 13, 605.6, 'Disponible', 'Blanco', NULL, 6),
(16, 1, NULL, 'M166070', 'FRUEHAUF', NULL, '1986', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R04', NULL, NULL, 'DISPONIBLE', 0, 'C-04', NULL, NULL, 32, 0, 8500, 'Disponible', 'Blanco', NULL, 0),
(17, 1, NULL, 'M178457', 'FRUEHAUF', NULL, '1986', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R07', NULL, NULL, 'DISPONIBLE', 0, 'C-07', NULL, NULL, 33, 0, 8000, 'Disponible', 'Blanco', NULL, 0),
(18, 1, NULL, 'M256356', 'Freightliner', 'Columbia', '2003', 106631, NULL, 'ACTIVO', '', '2025-11-14 14:44:10', '2026-09-09 11:07:50', NULL, 0, NULL, NULL, 'C11', NULL, NULL, '1', 0, '1-010', NULL, NULL, 33, 10, 605.6, 'Disponible', 'Blanco', NULL, 6),
(19, 1, NULL, 'M251988', 'ETNERY', NULL, '1987', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R15', NULL, NULL, 'DISPONIBLE', 0, 'C-15', NULL, NULL, 33, 0, 6000, 'Disponible', 'Blanco', NULL, 0),
(20, 1, NULL, 'M274026', 'POLAR', NULL, '1995', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R16', NULL, NULL, 'DISPONIBLE', 0, 'C-16', NULL, NULL, 32, 0, 9200, 'Disponible', 'Blanco', NULL, 0),
(21, 1, NULL, 'M276739', 'FRUEHAUF', NULL, '1991', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R18', NULL, NULL, 'DISPONIBLE', 0, 'C-18', NULL, NULL, 32, 0, 9200, 'Disponible', 'Blanco', NULL, 0),
(22, 1, NULL, 'M276738', 'FRUEHAUF', NULL, '1989', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R19', NULL, NULL, 'DISPONIBLE', 0, 'C-19', NULL, NULL, 32, 0, 9200, 'Disponible', 'Blanco', NULL, 0),
(23, 1, NULL, 'M299079', 'BAP', NULL, '1993', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R20', NULL, NULL, 'DISPONIBLE', 0, 'C-20', NULL, NULL, 33, 0, 7500, 'Disponible', 'Blanco', NULL, 0),
(24, 1, NULL, 'M264544', 'S/M', NULL, '2003', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2026-06-02 10:24:11', NULL, 0, 28, 26, 'R21', NULL, NULL, 'DISPONIBLE', 0, 'C-21', NULL, NULL, 32, 0, 9000, 'Disponible', 'Blanco', NULL, 0),
(25, 1, NULL, 'M275135', 'CUSTOM', NULL, '1988', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2026-06-01 14:20:33', NULL, 0, NULL, NULL, 'R25', NULL, NULL, '1', 0, '1-023', NULL, NULL, 32, 0, 10300, 'Disponible', 'Blanco', NULL, 0),
(26, 1, NULL, 'M322657', 'FRUEHAUF', NULL, '1989', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R27', NULL, NULL, 'DISPONIBLE', 0, 'C-27', NULL, NULL, 32, 0, 9200, 'Disponible', 'Blanco', NULL, 0),
(27, 1, NULL, 'M340598', 'HEIL', NULL, '1989', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R28', NULL, NULL, 'DISPONIBLE', 0, 'C-28', NULL, NULL, 32, 0, 12000, 'Disponible', 'Blanco', NULL, 0),
(28, 1, NULL, 'M326552', 'HARMON', NULL, '1996', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R29', NULL, NULL, 'DISPONIBLE', 0, 'C-29', NULL, NULL, 33, 0, 6500, 'Disponible', 'Blanco', NULL, 0),
(29, 1, NULL, 'M336570', 'HEIL', NULL, '2001', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R30', NULL, NULL, 'DISPONIBLE', 0, 'C-30', NULL, NULL, 33, 0, 8000, 'Disponible', 'Blanco', NULL, 0),
(30, 1, NULL, 'M336573', 'HEIL', NULL, '2000', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R31', NULL, NULL, 'DISPONIBLE', 0, 'C-31', NULL, NULL, 33, 0, 8000, 'Disponible', 'Blanco', NULL, 0),
(31, 1, NULL, 'M336569', 'HEIL', NULL, '2000', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R32', NULL, NULL, 'DISPONIBLE', 0, 'C-32', NULL, NULL, 33, 0, 8000, 'Disponible', 'Blanco', NULL, 0),
(32, 1, NULL, 'M346602', 'HEIL', NULL, '2008', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2025-11-14 14:45:42', NULL, NULL, 28, 26, 'R33', NULL, NULL, 'DISPONIBLE', 0, 'C-33', NULL, NULL, 33, 0, 8000, 'Disponible', 'Blanco', NULL, 0),
(33, 1, NULL, 'M346601', 'HEIL', NULL, '2009', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2026-06-01 16:26:03', NULL, 1, NULL, NULL, 'R34', NULL, NULL, '1', 0, '1-032', NULL, NULL, 33, 0, 8000, 'Disponible', 'Blanco', NULL, 0),
(34, 1, NULL, 'M346604', 'HEIL', NULL, '2009', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2026-05-31 22:02:12', NULL, 1, NULL, NULL, 'R35', NULL, NULL, '1', 0, '1-033', NULL, NULL, 33, 0, 8000, 'Disponible', 'Blanco', NULL, 0),
(35, 1, NULL, 'M346603', 'TRAILMASTER', NULL, '1993', 0, NULL, 'ACTIVO', NULL, '2025-11-14 14:44:10', '2026-05-31 22:03:53', NULL, 1, NULL, NULL, 'R36', NULL, NULL, '1', 0, '1-034', NULL, NULL, 33, 0, 8000, 'Disponible', 'Blanco', NULL, 0),
(36, 1, NULL, 'M346605', 'HEIL', 'Heil', '2009', 0, NULL, 'INACTIVO', 'Taller', '2025-11-14 14:44:10', '2026-08-13 09:08:50', NULL, 1, NULL, NULL, 'R37', NULL, NULL, '1', 0, '1-035', NULL, NULL, 33, 0, 8000, 'Disponible', 'Blanco', NULL, 6),
(37, 1, NULL, 'M23432', 'Toyoya', 'Corola', '2024', 0, NULL, 'ACTIVO', 'Ya no esta vendido', '2026-05-29 13:12:23', '2026-06-24 17:57:33', 1, 1, NULL, NULL, 'C00123', '43234343423', '523434234234', '1', 0, '1-047', 19, 21, 0, 100, 605.6, NULL, NULL, NULL, 0),
(38, 1, NULL, 'M421169', 'Freightliner', 'Columbia 120', '2008', 87434, NULL, 'ACTIVO', '', '2026-06-10 19:48:38', '2026-09-12 22:03:55', 1, 0, NULL, NULL, 'C58', NULL, NULL, '1', 0, '1-050', NULL, NULL, 0, 9, 454.2, NULL, NULL, NULL, 6),
(39, 1, NULL, 'M448366', 'Sinotruk', 'Howo', '2025', 59760, NULL, 'ACTIVO', '', '2026-06-10 19:51:41', '2026-09-12 22:12:53', 1, 0, NULL, NULL, 'C65', NULL, NULL, '1', 0, '1-117', NULL, NULL, 0, 9, 635, NULL, NULL, NULL, 6),
(40, 1, NULL, 'M415333', 'Freightliner', 'Class Cst120', '2006', 77825, NULL, 'ACTIVO', '', '2026-06-10 20:00:29', '2026-09-14 16:19:39', 1, 0, NULL, NULL, 'C62', NULL, NULL, '1', 0, '1-054', NULL, NULL, 0, 9, NULL, NULL, NULL, NULL, 6),
(41, 1, NULL, 'M312235', 'MACK', 'CX613 VISION', '2000', 182873, NULL, 'ACTIVO', '', '2026-06-17 15:01:28', '2026-09-12 22:28:44', 1, 0, NULL, NULL, 'C20', NULL, NULL, '1', 0, '1-018', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(42, 1, NULL, 'M255449', 'FREIGHTLINER', 'COLUMBIA', '2003', 2137494, 31, 'ACTIVO', '', '2026-06-24 16:40:58', '2026-09-11 21:33:32', 1, 0, NULL, NULL, 'C15', NULL, NULL, '1', 0, '1-013', 19, NULL, 0, 10, 605.6, NULL, NULL, NULL, 6),
(43, 1, NULL, 'M276635', 'MACK', 'CX613', '2003', 79999, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-10 15:41:21', 1, 0, NULL, NULL, 'C18', NULL, NULL, '1', 0, '1-016', NULL, NULL, 0, 10, 605.6, NULL, NULL, NULL, 6),
(44, 1, NULL, 'M276633', 'MACK', 'CX613 VISION', '2003', 53861, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-12 22:18:28', 1, 0, NULL, NULL, 'C19', NULL, NULL, '1', 0, '1-017', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(45, 1, NULL, 'M313030', 'MACK', 'CX613', '2007', 94214, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-12 22:26:32', 1, 0, NULL, NULL, 'C21', NULL, NULL, '1', 0, '1-019', NULL, NULL, 0, 10, 605.6, NULL, NULL, NULL, 6),
(46, 1, NULL, 'M136671', 'FREIGHTLINER', 'FL120', '2000', 245005, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-08-24 21:04:09', 1, 0, NULL, NULL, 'C26', NULL, NULL, '1', 0, '1-024', NULL, NULL, 0, 10, 605.6, NULL, NULL, NULL, 6),
(47, 1, NULL, 'M322659', 'MACK', 'N/R DOBLE EJE', '2008', 42779, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-12 22:07:35', 1, 0, NULL, NULL, 'C27', NULL, NULL, '1', 0, '1-025', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(48, 1, NULL, 'M326419', 'FREIGHTLINER', 'COLUMBIA', '2004', 101859, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-12 22:15:35', 1, 0, NULL, NULL, 'C30', NULL, NULL, '1', 0, '1-028', NULL, NULL, 0, 10, 605.6, NULL, NULL, NULL, 6),
(49, 1, NULL, 'M341044', 'FREIGHTLINER', 'COLUMBIA', '2005', 0, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-07-16 17:11:50', 1, 1, NULL, NULL, 'C33', NULL, NULL, '1', 0, '1-031', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(50, 1, NULL, 'M354295', 'FREIGHTLINER', 'CASCADIA', '2013', 1159088, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-08-21 09:36:43', 1, 0, NULL, NULL, 'C36', NULL, NULL, '1', 0, '1-034', NULL, NULL, 0, 8, 605.6, NULL, NULL, NULL, 6),
(51, 1, NULL, 'M282135', 'FREIGHTLINER', 'FLD120', '1995', 103911, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-09 20:18:21', 1, 0, NULL, NULL, 'C37', NULL, NULL, '1', 0, '1-035', NULL, NULL, 0, 10, 605.6, NULL, NULL, NULL, 6),
(52, 1, NULL, 'M357186', 'INTERNATIONAL', 'LF687', '2013', 55703, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-07-16 17:12:59', 1, 1, NULL, NULL, 'C41', NULL, NULL, '1', 0, '1-038', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(53, 1, NULL, 'M364112', 'INTERNATIONAL', 'N/R DOBLE EJE', '2007', 3564, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-08-06 14:36:26', 1, 0, NULL, NULL, 'C44_Grua', NULL, NULL, '1', 0, '1-041', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(54, 1, NULL, 'M364270', 'FREIGHTLINER', 'TAC F2', '2005', 45373, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-14 13:54:50', 1, 0, NULL, NULL, 'C45', NULL, NULL, '1', 0, '1-042', NULL, NULL, 0, 8, 605.6, NULL, NULL, NULL, 6),
(55, 1, NULL, 'M365312', 'PETERBILT', '386', '2010', 19976, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-12 21:57:41', 1, 0, NULL, NULL, 'C46', NULL, NULL, '1', 0, '1-043', NULL, NULL, 0, 8, 605.6, NULL, NULL, NULL, 6),
(56, 1, NULL, 'M367585', 'FREIGHTLINER', 'BUSSINES CLASS MZ', '2005', 0, NULL, 'ACTIVO', NULL, '2026-06-24 17:57:31', '2026-06-24 17:57:33', 1, 1, NULL, NULL, 'C48', NULL, NULL, '1', 0, '1-045', NULL, NULL, 0, 10, 605.6, NULL, NULL, NULL, 0),
(57, 1, NULL, 'M388569', 'FREIGHTLINER', 'CASCADIA', '2010', 68361, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-12 22:28:07', 1, 0, NULL, NULL, '56_NUEVO', NULL, NULL, '1', 0, '1-048', NULL, NULL, 0, 8, 605.6, NULL, NULL, NULL, 6),
(58, 1, NULL, 'M389374', 'FREIGHTLINER', 'CASCADIA', '2013', 60429, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-07 16:28:23', 1, 0, NULL, NULL, 'C57', NULL, NULL, '1', 0, '1-049', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(59, 1, NULL, 'M412915', 'FREIGHTLINER', 'CASCADIA 125', '2011', 1367346, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-14 14:04:31', 1, 0, NULL, NULL, 'C59', NULL, NULL, '1', 0, '1-051', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(60, 1, NULL, 'M415211', 'FREIGHTLINER', 'FLD120', '1999', 41108, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-14 20:10:15', 1, 0, NULL, NULL, 'C60', NULL, NULL, '1', 0, '1-052', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(61, 1, NULL, 'M415213', 'FREIGHTLINER', 'COLUMBIA 120', '2008', 1145244, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-14 20:13:03', 1, 0, NULL, NULL, 'C61', NULL, NULL, '1', 0, '1-053', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(62, 1, NULL, 'M448536', 'FREIGHTLINER', 'CL120', '2007', 56634, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-12 22:19:24', 1, 0, NULL, NULL, 'C64', NULL, NULL, '1', 0, '1-116', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(63, 1, NULL, 'M461121', 'FREIGHTLINER', 'M2', '2014', 35376, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-12 22:27:15', 1, 0, NULL, NULL, 'C66', NULL, NULL, '1', 0, '1-120', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(64, 1, NULL, 'M461628', 'FREIGHTLINER', 'M2', '2026', 13529, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-12 22:23:33', 1, 0, NULL, NULL, 'C67', NULL, NULL, '1', 0, '1-121', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(65, 1, NULL, 'M464154', 'HOWO', 'SINETRACK', '2026', 22869, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-12 21:58:46', 1, 0, NULL, NULL, 'C68', NULL, NULL, '1', 0, '1-122', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 6),
(66, 1, NULL, 'M314513', 'VOLVO', 'S/M', '1995', 2489, NULL, 'ACTIVO', NULL, '2026-06-24 17:57:31', '2026-06-24 17:57:33', 1, 1, NULL, NULL, 'Grua_Plataforma', NULL, NULL, '1', 0, '1-123', NULL, NULL, 0, 9, 605.6, NULL, NULL, NULL, 0),
(67, 1, NULL, 'M286772', 'FOTON', 'GRATOUR T3', '2017', 124034, NULL, 'ACTIVO', NULL, '2026-06-24 17:57:31', '2026-06-24 17:57:33', 1, 1, NULL, NULL, '1-093', NULL, NULL, '1', 0, '1-093', NULL, NULL, 0, 40, 605.6, NULL, NULL, NULL, 0),
(68, 1, NULL, 'M110142', 'TOYOTA', 'HILUX', '2008', 289127, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-07 13:00:21', 1, 1, NULL, NULL, 'Toyota H', NULL, NULL, '1', 0, '1-099', NULL, NULL, 0, 45, 605.6, NULL, NULL, NULL, 5),
(69, 1, NULL, 'M273816', 'HONDA', 'CGL125', '2024', 28552, NULL, 'ACTIVO', NULL, '2026-06-24 17:57:31', '2026-06-24 17:57:33', 1, 1, NULL, NULL, '1-063', NULL, NULL, '1', 0, '1-063', NULL, NULL, 0, 115, 605.6, NULL, NULL, NULL, 0),
(70, 1, NULL, 'M273820', 'HONDA', 'CGL125', '2024', 11902, NULL, 'ACTIVO', NULL, '2026-06-24 17:57:31', '2026-06-24 17:57:33', 1, 1, NULL, NULL, '1-064', NULL, NULL, '1', 0, '1-064', NULL, NULL, 0, 115, 605.6, NULL, NULL, NULL, 0),
(71, 1, NULL, 'M353553', 'KIA', 'K2700', '2022', 78991, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-07 11:18:43', 1, 1, NULL, NULL, 'Kia', NULL, NULL, '1', 0, '1-092', NULL, NULL, 0, 45, 605.6, NULL, NULL, NULL, 5),
(72, 1, NULL, 'M422167', 'KIA', 'K3000', '2025', 89956, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-09 22:10:21', 1, 1, NULL, NULL, '1-118', NULL, NULL, '1', 0, '1-118', NULL, NULL, 0, 45, 605.6, NULL, NULL, NULL, 5),
(73, 1, NULL, 'M372333', 'MITSUBISHI', 'L200', '2023', 80909, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-09-07 12:57:26', 1, 1, NULL, NULL, '1-098', NULL, NULL, '1', 0, '1-098', NULL, NULL, 0, 40, 605.6, NULL, NULL, NULL, 5),
(74, 1, NULL, 'M360163', 'SUZUKI', 'ALTO', '2023', 59164, NULL, 'ACTIVO', '', '2026-06-24 17:57:31', '2026-07-16 17:27:10', 1, 1, NULL, NULL, '1-094', NULL, NULL, '1', 0, '1-094', NULL, NULL, 0, 50, 605.6, NULL, NULL, NULL, 5),
(75, 1, NULL, 'IRESA-', 'Iresa', 'Iresa', '2026', 10, NULL, 'ACTIVO', '', '2026-06-25 16:39:51', '2026-08-04 19:18:49', 1, 1, NULL, NULL, 'TERCERIZADOS', NULL, NULL, '1', 0, '1-111', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 7),
(76, 1, NULL, 'M330810', 'Ram', '2500', '2026', 1, NULL, 'ACTIVO', '', '2026-06-25 19:47:04', '2026-07-16 17:29:13', 1, 1, NULL, NULL, 'RAM 2500', NULL, NULL, '1', 0, '1-097', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 5),
(77, 1, NULL, 'M426995', 'Toyota', 'Prado', '2026', 4, NULL, 'ACTIVO', '', '2026-06-25 19:48:47', '2026-07-16 19:13:33', 1, 1, NULL, NULL, 'TOYOTA LANDCRUISER', NULL, NULL, '1', 0, '1-096', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 5),
(78, 1, NULL, 'JOSE REYNERIO DURAN', 'Jose Reynerio Duran', 'Jose Reynerio Duran', '2026', 2, NULL, 'ACTIVO', '', '2026-06-26 02:38:39', '2026-07-16 17:26:00', 1, 1, NULL, NULL, 'TERCERIZADOS', NULL, NULL, '1', 0, '1-111', NULL, NULL, 0, 0, 0, NULL, NULL, NULL, 7),
(79, 1, NULL, 'M363653', 'Freightliner', 'Freightliner', '2011', 13431, NULL, 'ACTIVO', '', '2026-06-26 02:43:01', '2026-09-14 21:55:13', 1, 0, NULL, NULL, 'C43', NULL, NULL, '1', 0, '1-040', 18, NULL, 0, 8, NULL, NULL, NULL, NULL, 6),
(80, 1, NULL, 'DENIS BLAS AMPIE', 'Denis Blas Ampie', 'Denis Blas Ampie', '2026', 8, NULL, 'ACTIVO', '', '2026-06-26 02:47:17', '2026-07-16 17:25:43', 1, 1, NULL, NULL, 'TERCERIZADOS', NULL, NULL, '1', 0, '1-111', NULL, NULL, 0, 0, 0, NULL, NULL, NULL, 7),
(81, 1, NULL, 'TANQUE GCM', 'Tanque', 'Tanque', '2026', 13, NULL, 'ACTIVO', '', '2026-07-02 14:38:27', '2026-09-10 21:43:59', 1, 0, NULL, NULL, 'tanque', NULL, NULL, '1', 0, '1-112', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 6),
(82, 1, NULL, 'ARELI ESPINOZA RIZO', 'Areli Espinoza Rizo', 'Areli Espinoza Rizo', '2026', 4, NULL, 'ACTIVO', '', '2026-07-04 15:42:21', '2026-08-03 16:35:06', 1, 1, NULL, NULL, 'TERCERIZADOS', NULL, NULL, '1', 0, '1-111', NULL, NULL, 0, 0, 0, NULL, NULL, NULL, 7),
(83, 1, NULL, 'TCF NICARAGUA', 'Tcf', 'Nicaragua', '2026', 2, NULL, 'ACTIVO', '', '2026-07-07 12:47:24', '2026-07-16 17:26:32', 1, 1, NULL, NULL, 'TERCERIZADOS', NULL, '34243423434', '1', 0, '1-111', 19, 20, 0, 0, NULL, NULL, NULL, NULL, 7),
(84, 1, NULL, 'M435527', 'Toyota', 'Prado', '2026', 0, NULL, 'ACTIVO', '', '2026-07-16 19:12:54', '2026-07-16 19:38:46', 1, 1, NULL, NULL, 'TOYOTA LANDCRUISER', NULL, NULL, '1', 0, '1-124', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 5),
(85, 1, NULL, 'M401276', 'Isuzu', 'D-max', '2026', 0, NULL, 'ACTIVO', NULL, '2026-07-16 19:15:08', '2026-07-16 19:15:08', 1, 1, NULL, NULL, 'ISUZU', NULL, NULL, '1', 0, '1-113', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 5),
(86, 1, NULL, 'M243803', 'Toyota', 'Hilux', '2026', 0, NULL, 'ACTIVO', NULL, '2026-07-16 19:16:07', '2026-07-16 19:16:07', 1, 1, NULL, NULL, 'TOYOTA HILUX', NULL, NULL, '1', 0, '1-089', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 5),
(87, 1, NULL, 'M362289', 'Toyota', 'Fortuner', '2025', 0, NULL, 'ACTIVO', NULL, '2026-08-03 17:54:35', '2026-08-03 17:54:35', 1, 1, NULL, NULL, 'Toyota F', NULL, NULL, '1', 0, '1-089', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 5),
(88, 1, NULL, 'M473748', 'Freightliner', 'Cascadia', '2016', 6474, NULL, 'ACTIVO', '', '2026-08-03 18:52:49', '2026-09-14 21:54:17', 1, 0, NULL, NULL, 'C69', NULL, NULL, '1', 0, '1-123', NULL, NULL, 0, 9, 0, NULL, NULL, NULL, 6),
(89, 1, NULL, 'M477627', 'Freightliner', 'Cascadia', '2014', 1691, NULL, 'ACTIVO', '', '2026-08-18 13:22:02', '2026-09-12 22:10:27', 1, 0, NULL, NULL, 'C70', NULL, NULL, '1', 0, '1-128', NULL, NULL, 0, 9, NULL, NULL, NULL, NULL, 6),
(90, 1, NULL, 'GUERMAR S.A', 'Guerma S.a', 'Guermar S.a', '2026', 0, NULL, 'ACTIVO', NULL, '2026-08-21 22:19:50', '2026-08-21 22:19:50', 1, 1, NULL, NULL, 'GUERMAR S.A', NULL, NULL, '1', 0, '1-111', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 7),
(91, 1, NULL, 'IMMSA', 'Inversiones Mobiliarias, S.a', 'Inversiones Mobiliarias, S.a', '2026', 0, NULL, 'ACTIVO', NULL, '2026-08-24 10:59:35', '2026-08-24 10:59:35', 1, 1, NULL, NULL, 'Inversiones Mobiliarias, S.A', NULL, NULL, '1', 0, '1-111', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 7),
(92, 1, NULL, 'M471398', 'Freightliner', 'Kenworth', '2014', 760, NULL, 'ACTIVO', '', '2026-09-04 09:50:22', '2026-09-09 22:15:53', 1, 0, NULL, NULL, 'C71', NULL, NULL, '1', 0, '1-129', NULL, NULL, 0, 9, NULL, NULL, NULL, NULL, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vta_clientes`
--

CREATE TABLE `vta_clientes` (
  `id` int(11) NOT NULL,
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
  `buro_credito` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vta_clientes_direcciones`
--

CREATE TABLE `vta_clientes_direcciones` (
  `id` int(11) NOT NULL,
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
  `fecha_modifico` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vta_clientes_productos`
--

CREATE TABLE `vta_clientes_productos` (
  `id` int(11) NOT NULL,
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
  `fecha_modifico` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vta_orden`
--

CREATE TABLE `vta_orden` (
  `id` int(11) NOT NULL,
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
  `codigo_proveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vta_orden_detalle`
--

CREATE TABLE `vta_orden_detalle` (
  `id` int(11) NOT NULL,
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
  `codigo_bodega` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_documentacion_vehiculo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_documentacion_vehiculo` (
`id` int(11)
,`id_vehiculo` int(11)
,`tipo_documento` varchar(100)
,`observaciones` varchar(255)
,`ruta_archivo` varchar(255)
,`fecha_registro` datetime
,`fechaUpdate` datetime
,`usuario_crea` int(11)
,`usuario_edita` int(11)
,`fecha_vencimiento` datetime
,`nombre_archivo` varchar(100)
,`notificacion` int(11)
,`numero` varchar(100)
,`placa` varchar(20)
,`modelo` varchar(50)
,`codigo_unidad` varchar(100)
,`anio` year(4)
,`nombre_documento` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_documentacion_vehiculo`
--
DROP TABLE IF EXISTS `vw_documentacion_vehiculo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_documentacion_vehiculo`  AS SELECT `dc`.`id` AS `id`, `dc`.`id_vehiculo` AS `id_vehiculo`, `dc`.`tipo_documento` AS `tipo_documento`, `dc`.`observaciones` AS `observaciones`, `dc`.`ruta_archivo` AS `ruta_archivo`, `dc`.`fecha_registro` AS `fecha_registro`, `dc`.`fechaUpdate` AS `fechaUpdate`, `dc`.`usuario_crea` AS `usuario_crea`, `dc`.`usuario_edita` AS `usuario_edita`, `dc`.`fecha_vencimiento` AS `fecha_vencimiento`, `dc`.`nombre_archivo` AS `nombre_archivo`, `dc`.`notificacion` AS `notificacion`, `dc`.`numero` AS `numero`, `v`.`placa` AS `placa`, `v`.`modelo` AS `modelo`, `v`.`codigo_unidad` AS `codigo_unidad`, `v`.`anio` AS `anio`, `c`.`nombre` AS `nombre_documento` FROM ((`documentos_vehiculos` `dc` join `vehiculos` `v` on(`v`.`id` = `dc`.`id_vehiculo`)) join `catalogo` `c` on(`c`.`id` = `dc`.`tipo_documento`)) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesorios_vehiculos`
--
ALTER TABLE `accesorios_vehiculos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asignacion_vehiculos`
--
ALTER TABLE `asignacion_vehiculos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `conductores`
--
ALTER TABLE `conductores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `consecutivos_detalle`
--
ALTER TABLE `consecutivos_detalle`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_consecutivo` (`id_empresa`,`tabla`,`numero`);

--
-- Indices de la tabla `consumo_materiales`
--
ALTER TABLE `consumo_materiales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_numero_cotizacion` (`numero_cotizacion`),
  ADD KEY `idx_cliente_nombre` (`cliente_nombre`),
  ADD KEY `idx_fecha_creacion` (`fecha_creacion`);

--
-- Indices de la tabla `detalle_movimiento`
--
ALTER TABLE `detalle_movimiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentacion_conductor`
--
ALTER TABLE `documentacion_conductor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos_vehiculos`
--
ALTER TABLE `documentos_vehiculos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_estado_vehiculo`
--
ALTER TABLE `historial_estado_vehiculo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_orden_trabajo`
--
ALTER TABLE `historial_orden_trabajo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_codigo` (`codigo`),
  ADD KEY `idx_categoria` (`categoria`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indices de la tabla `lectura_bomba`
--
ALTER TABLE `lectura_bomba`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `materiales_trabajo`
--
ALTER TABLE `materiales_trabajo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `motivos_asignacion_combustible`
--
ALTER TABLE `motivos_asignacion_combustible`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro_combustible`
--
ALTER TABLE `registro_combustible`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro_trabajo_realizado`
--
ALTER TABLE `registro_trabajo_realizado`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_consecutivo` (`codigo_consecutivo`),
  ADD UNIQUE KEY `uk_codigo_consecutivo` (`codigo_consecutivo`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_problema`
--
ALTER TABLE `tipos_problema`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_documentos`
--
ALTER TABLE `tipo_documentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_motivo_combustible`
--
ALTER TABLE `tipo_motivo_combustible`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_operacion`
--
ALTER TABLE `tipo_operacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_unidad`
--
ALTER TABLE `tipo_unidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `placa` (`placa`);

--
-- Indices de la tabla `vta_clientes`
--
ALTER TABLE `vta_clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vta_clientes_direcciones`
--
ALTER TABLE `vta_clientes_direcciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vta_clientes_productos`
--
ALTER TABLE `vta_clientes_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vta_orden`
--
ALTER TABLE `vta_orden`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vta_orden_detalle`
--
ALTER TABLE `vta_orden_detalle`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesorios_vehiculos`
--
ALTER TABLE `accesorios_vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `accesos`
--
ALTER TABLE `accesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=678;

--
-- AUTO_INCREMENT de la tabla `asignacion_vehiculos`
--
ALTER TABLE `asignacion_vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `conductores`
--
ALTER TABLE `conductores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `consecutivos_detalle`
--
ALTER TABLE `consecutivos_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `consumo_materiales`
--
ALTER TABLE `consumo_materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_movimiento`
--
ALTER TABLE `detalle_movimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `documentacion_conductor`
--
ALTER TABLE `documentacion_conductor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `documentos_vehiculos`
--
ALTER TABLE `documentos_vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_estado_vehiculo`
--
ALTER TABLE `historial_estado_vehiculo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_orden_trabajo`
--
ALTER TABLE `historial_orden_trabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `lectura_bomba`
--
ALTER TABLE `lectura_bomba`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT de la tabla `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT de la tabla `materiales_trabajo`
--
ALTER TABLE `materiales_trabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `motivos_asignacion_combustible`
--
ALTER TABLE `motivos_asignacion_combustible`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `registro_combustible`
--
ALTER TABLE `registro_combustible`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1249;

--
-- AUTO_INCREMENT de la tabla `registro_trabajo_realizado`
--
ALTER TABLE `registro_trabajo_realizado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tipos_problema`
--
ALTER TABLE `tipos_problema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_documentos`
--
ALTER TABLE `tipo_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_motivo_combustible`
--
ALTER TABLE `tipo_motivo_combustible`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_operacion`
--
ALTER TABLE `tipo_operacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_unidad`
--
ALTER TABLE `tipo_unidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT de la tabla `vta_clientes`
--
ALTER TABLE `vta_clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vta_clientes_direcciones`
--
ALTER TABLE `vta_clientes_direcciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vta_clientes_productos`
--
ALTER TABLE `vta_clientes_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vta_orden`
--
ALTER TABLE `vta_orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vta_orden_detalle`
--
ALTER TABLE `vta_orden_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
