-- Script para actualizar tabla registro_combustible
-- Reemplazar id_motivo con id_tipo_motivo

-- Paso 1: Agregar la nueva columna id_tipo_motivo
ALTER TABLE registro_combustible 
ADD COLUMN id_tipo_motivo INT NULL;

-- Paso 2: Migrar datos existentes (si los hay)
-- Este paso requiere mapeo manual de motivos a tipos de motivo
-- UPDATE registro_combustible SET id_tipo_motivo = 1 WHERE id_motivo IS NOT NULL;

-- Paso 3: Eliminar la columna id_motivo antigua (después de migrar datos)
-- ALTER TABLE registro_combustible DROP COLUMN id_motivo;

-- Paso 4: Hacer la nueva columna obligatoria
ALTER TABLE registro_combustible 
MODIFY COLUMN id_tipo_motivo INT NOT NULL;

-- Paso 5: Crear la clave foránea
ALTER TABLE registro_combustible 
ADD CONSTRAINT fk_registro_combustible_tipo_motivo 
FOREIGN KEY (id_tipo_motivo) REFERENCES tipo_motivo_combustible(id);

-- Paso 6: Crear índice para mejorar rendimiento
CREATE INDEX idx_registro_combustible_tipo_motivo ON registro_combustible(id_tipo_motivo);

-- Verificar la estructura actualizada
DESCRIBE registro_combustible;
