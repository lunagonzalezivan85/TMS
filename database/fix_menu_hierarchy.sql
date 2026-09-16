-- Actualizar jerarquía de menús: establecer padres e hijos

-- Nivel 1 (padres): id_superior = NULL, nivel = 1
UPDATE menu SET id_superior = NULL, nivel = 1 WHERE id IN (1, 2, 3, 4, 5, 6, 7, 23, 25, 30, 39);

-- Nivel 2: hijos de Gestión de Vehículos (id 2)
UPDATE menu SET id_superior = 2, nivel = 2 WHERE id IN (8, 9);

-- Nivel 2: hijos de Gestión de Conductores (id 3)
UPDATE menu SET id_superior = 3, nivel = 2 WHERE id IN (10, 11);

-- Nivel 2: hijos de Control de Combustible (id 4)
UPDATE menu SET id_superior = 4, nivel = 2 WHERE id IN (12, 13, 34, 36, 37, 38);

-- Nivel 2: hijos de Mantenimiento (id 5)
UPDATE menu SET id_superior = 5, nivel = 2 WHERE id = 14;

-- Nivel 2: hijos de Reportes y Análisis (id 6)
UPDATE menu SET id_superior = 6, nivel = 2 WHERE id IN (15, 16);

-- Nivel 2: hijos de Configuración (id 7)
UPDATE menu SET id_superior = 7, nivel = 2 WHERE id IN (17, 18, 19, 20, 21, 22, 27, 28, 29, 32);

-- Nivel 2: hijos de Gestion de Direcciones (id 23)
UPDATE menu SET id_superior = 23, nivel = 2 WHERE id = 24;

-- Nivel 2: hijos de Mantenimiento de Vehiculos (id 25)
UPDATE menu SET id_superior = 25, nivel = 2 WHERE id IN (26, 33);

-- Nivel 2: hijos de Gestion de Movimientos (id 30)
UPDATE menu SET id_superior = 30, nivel = 2 WHERE id = 31;

-- Nivel 3: hijos de Venta de combustible (id 34)
UPDATE menu SET id_superior = 34, nivel = 3 WHERE id = 35;
