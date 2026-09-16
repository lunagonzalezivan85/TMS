-- Verificar si hay mas registros con estado PENDIENTES
SELECT id, estado FROM solicitudes WHERE estado = 'PENDIENTES';

-- Corregir el estado PENDIENTES -> PENDIENTE
UPDATE solicitudes SET estado = 'PENDIENTE' WHERE estado = 'PENDIENTES';

-- Verificar la correccion
SELECT id, estado FROM solicitudes WHERE id = 8;
