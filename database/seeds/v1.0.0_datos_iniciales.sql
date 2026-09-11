-- ========================
-- DATOS INICIALES PARA SISTEMA GMV
-- Versión: 1.0.0
-- Fecha: 2025-01-08
-- Descripción: Datos básicos para inicializar el sistema
-- ========================

-- ========================
-- INSERTAR EMPRESA DE PRUEBA
-- ========================
INSERT INTO empresas (nombre, ruc, direccion, telefono, correo, codigo_inicial, serie_documento, usuarioCrea, usuarioEdita) 
VALUES 
('Transportes GMV S.A.C.', '20123456789', 'Av. Principal 123, Lima, Perú', '+51 999 888 777', 'info@transportesgmv.com', 'C', '2024200', 1, 1);

-- ========================
-- INSERTAR ROLES
-- ========================
INSERT INTO roles (nombre, descripcion, usuarioCrea, usuarioEdita) 
VALUES 
('Administrador', 'Acceso completo al sistema', 1, 1),
('Conductor', 'Acceso limitado para conductores', 1, 1),
('Mecánico', 'Acceso para gestión de mantenimientos', 1, 1);

-- ========================
-- INSERTAR USUARIO ADMINISTRADOR
-- ========================
INSERT INTO usuarios (id_empresa, nombre, usuario, clave, correo, telefono, id_rol, usuarioCrea, usuarioEdita) 
VALUES 
(1, 'Administrador del Sistema', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@transportesgmv.com', '+51 999 111 222', 1, 1, 1);
-- Contraseña: password

-- ========================
-- INSERTAR CONDUCTORES DE PRUEBA
-- ========================
INSERT INTO conductores (id_empresa, codigo_consecutivo, nombre, apellido, dni, fechaIngreso, usuarioCrea, usuarioEdita) 
VALUES 
(1, 'C2024200-0001', 'Juan Carlos', 'Pérez García', '12345678', '2024-01-15', 1, 1),
(1, 'C2024200-0002', 'María Elena', 'Rodríguez López', '87654321', '2024-02-01', 1, 1),
(1, 'C2024200-0003', 'Pedro Antonio', 'Sánchez Torres', '11223344', '2024-03-10', 1, 1);

-- ========================
-- INSERTAR VEHÍCULOS DE PRUEBA
-- ========================
INSERT INTO vehiculos (id_empresa, codigo_consecutivo, placa, marca, modelo, anio, kilometraje, id_conductor, usuarioCrea, usuarioEdita) 
VALUES 
(1, 'C2024200-0001', 'ABC-123', 'Toyota', 'Corolla', 2020, 45000, 1, 1, 1),
(1, 'C2024200-0002', 'DEF-456', 'Honda', 'Civic', 2019, 52000, 2, 1, 1),
(1, 'C2024200-0003', 'GHI-789', 'Nissan', 'Sentra', 2021, 28000, 3, 1, 1),
(1, 'C2024200-0004', 'JKL-012', 'Ford', 'Focus', 2018, 68000, NULL, 1, 1);

-- ========================
-- INSERTAR TIPOS DE PROBLEMA
-- ========================
INSERT INTO tipos_problema (id_empresa, nombre, usuarioCrea, usuarioEdita) 
VALUES 
(1, 'Cambio de aceite', 1, 1),
(1, 'Revisión de frenos', 1, 1),
(1, 'Cambio de llantas', 1, 1),
(1, 'Reparación de motor', 1, 1),
(1, 'Mantenimiento preventivo', 1, 1),
(1, 'Revisión eléctrica', 1, 1),
(1, 'Alineación y balanceo', 1, 1);

-- ========================
-- INSERTAR MATERIALES
-- ========================
INSERT INTO materiales (id_empresa, codigo_consecutivo, nombre, unidad_medida, costo_unitario, usuarioCrea, usuarioEdita) 
VALUES 
(1, 'C2024200-0001', 'Aceite de motor 5W-30', 'Litro', 25.50, 1, 1),
(1, 'C2024200-0002', 'Filtro de aceite', 'Unidad', 18.00, 1, 1),
(1, 'C2024200-0003', 'Pastillas de freno delanteras', 'Juego', 85.00, 1, 1),
(1, 'C2024200-0004', 'Pastillas de freno traseras', 'Juego', 65.00, 1, 1),
(1, 'C2024200-0005', 'Llanta 185/65R15', 'Unidad', 180.00, 1, 1),
(1, 'C2024200-0006', 'Batería 12V', 'Unidad', 220.00, 1, 1),
(1, 'C2024200-0007', 'Bujías', 'Juego', 45.00, 1, 1);

-- ========================
-- INSERTAR SOLICITUDES DE MANTENIMIENTO DE PRUEBA
-- ========================
INSERT INTO solicitudes (id_empresa, codigo_consecutivo, id_vehiculo, id_solicitante, id_tipo_problema, descripcion, estado, usuarioCrea, usuarioEdita) 
VALUES 
(1, 'C2024200-0001', 1, 1, 1, 'Cambio de aceite programado cada 5000 km', 'COMPLETADA', 1, 1),
(1, 'C2024200-0002', 2, 1, 2, 'Revisión de frenos por ruido al frenar', 'EN PROCESO', 1, 1),
(1, 'C2024200-0003', 3, 1, 5, 'Mantenimiento preventivo de 30000 km', 'PENDIENTE', 1, 1);

-- ========================
-- INSERTAR CONSUMO DE MATERIALES
-- ========================
INSERT INTO consumo_materiales (id_empresa, codigo_consecutivo, id_solicitud, id_material, cantidad, usuarioCrea, usuarioEdita) 
VALUES 
(1, 'C2024200-0001', 1, 1, 4.5, 1, 1),  -- 4.5 litros de aceite
(1, 'C2024200-0002', 1, 2, 1.0, 1, 1),  -- 1 filtro de aceite
(1, 'C2024200-0003', 2, 3, 1.0, 1, 1);  -- 1 juego de pastillas delanteras

-- ========================
-- INSERTAR HISTORIAL DE ESTADO DE VEHÍCULOS
-- ========================
INSERT INTO historial_estado_vehiculo (id_empresa, id_vehiculo, estado, motivo, usuarioCrea, usuarioEdita) 
VALUES 
(1, 1, 'ACTIVO', 'Vehículo en servicio normal', 1, 1),
(1, 2, 'EN REPARACION', 'En taller por revisión de frenos', 1, 1),
(1, 3, 'ACTIVO', 'Vehículo en servicio normal', 1, 1),
(1, 4, 'INACTIVO', 'Vehículo sin conductor asignado', 1, 1);

-- ========================
-- ACTUALIZAR ESTADOS DE VEHÍCULOS
-- ========================
UPDATE vehiculos SET estado = 'EN REPARACION', motivo_inactividad = 'En taller por revisión de frenos' WHERE id = 2;
UPDATE vehiculos SET estado = 'INACTIVO', motivo_inactividad = 'Sin conductor asignado' WHERE id = 4;
