-- Script para actualizar el campo compuesto en vehículos existentes
-- Ejecutar este script en la base de datos para establecer valores por defecto

-- Actualizar todos los vehículos que tienen compuesto NULL a 0 (no compuesto por defecto)
UPDATE vehiculos 
SET compuesto = 0 
WHERE compuesto IS NULL;

-- Verificar los cambios
SELECT id, placa, marca, modelo, compuesto 
FROM vehiculos 
ORDER BY id;

-- Opcional: Si quieres marcar algunos vehículos específicos como compuestos
-- UPDATE vehiculos SET compuesto = 1 WHERE placa IN ('ABC123', 'DEF456');
