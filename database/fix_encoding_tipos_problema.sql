-- Corregir nombres con doble codificación (UTF-8 leído como cp850)
-- en catalogo (CAT-0016) y tipos_problema (legacy)

UPDATE catalogo SET nombre = 'PROBLEMAS MECÁNICOS'               WHERE id = 52;
UPDATE catalogo SET nombre = 'PROBLEMAS ELÉCTRICOS'              WHERE id = 53;
UPDATE catalogo SET nombre = 'PROBLEMA DE SUSPENSIÓN Y DIRECCIÓN' WHERE id = 54;
-- id 55 no tiene acentos, no requiere corrección

UPDATE tipos_problema SET nombre = 'PROBLEMAS MECÁNICOS'               WHERE id = 3;
UPDATE tipos_problema SET nombre = 'PROBLEMAS ELÉCTRICOS'              WHERE id = 4;
UPDATE tipos_problema SET nombre = 'PROBLEMA DE SUSPENSIÓN Y DIRECCIÓN' WHERE id = 5;
