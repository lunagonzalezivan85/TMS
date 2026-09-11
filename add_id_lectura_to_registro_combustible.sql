-- Agregar columna id_lectura a la tabla registro_combustible
-- Esta columna permite relacionar el registro de combustible con una apertura de bomba específica

ALTER TABLE registro_combustible
ADD id_lectura INT NULL;

-- Agregar índice para mejor rendimiento en búsquedas
CREATE INDEX idx_registro_combustible_id_lectura ON registro_combustible(id_lectura);

-- Agregar clave foránea opcional a lectura_bomba (si la tabla existe)
-- ALTER TABLE registro_combustible
-- ADD CONSTRAINT fk_registro_combustible_lectura
-- FOREIGN KEY (id_lectura) REFERENCES lectura_bomba(id) ON DELETE SET NULL;
