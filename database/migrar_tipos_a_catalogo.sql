-- ============================================================
-- Migración: mover Tipo de Problema y Tipo de Mantenimiento
-- a la tabla `catalogo` (estructura jerárquica CAT-XXXX)
-- ============================================================
-- CAT-0010 = TIPO DE MANTENIMIENTO (ya existe, id=23)
-- CAT-0016 = TIPO DE PROBLEMA (nuevo)
-- ============================================================

-- 1. Crear catálogo padre TIPO DE PROBLEMA (CAT-0016)
INSERT INTO catalogo (codigo, nombre, descripcion, id_superior, estado, referencia, nivel, idempresa, usuario_crea, fecha_registro)
SELECT 'CAT-0016', 'TIPO DE PROBLEMA', 'Tipos de problema para solicitudes de mantenimiento', NULL, 1, NULL, 1, 1, 1, NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM catalogo WHERE codigo = 'CAT-0016' AND (id_superior = 0 OR id_superior IS NULL) AND idempresa = 1
);

SET @cat_problema = (
    SELECT id FROM catalogo
    WHERE codigo = 'CAT-0016' AND (id_superior = 0 OR id_superior IS NULL) AND idempresa = 1
    LIMIT 1
);

-- 2. Migrar tipos de problema reales desde tipos_problema (ids 3-6)
--    (ids 1-2 son PREVENTIVO/CORRECTIVO = tipos de mantenimiento, no se migran aquí)
INSERT INTO catalogo (codigo, nombre, descripcion, id_superior, estado, referencia, nivel, idempresa, usuario_crea, fecha_registro)
SELECT 'CAT-0016', tp.nombre, NULL, @cat_problema, 1, 'General', 2, tp.id_empresa, COALESCE(tp.usuarioCrea, 1), NOW()
FROM tipos_problema tp
WHERE tp.id IN (3, 4, 5, 6)
  AND NOT EXISTS (
      SELECT 1 FROM catalogo c
      WHERE c.id_superior = @cat_problema AND c.nombre = tp.nombre AND c.idempresa = tp.id_empresa
  );

-- 3. Agregar EMERGENCIA a CAT-0010 TIPO DE MANTENIMIENTO (padre id=23)
INSERT INTO catalogo (codigo, nombre, descripcion, id_superior, estado, referencia, nivel, idempresa, usuario_crea, fecha_registro)
SELECT 'CAT-0010', 'EMERGENCIA', NULL, 23, 1, NULL, 2, 1, 1, NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM catalogo WHERE id_superior = 23 AND nombre = 'EMERGENCIA' AND idempresa = 1
);

-- 4. Remapear solicitudes.id_tipo_problema a IDs de catalogo
--    tipos_problema 1 (PREVENTIVO) -> catalogo 24 (PREVENTIVO, hijo de CAT-0010)
UPDATE solicitudes SET id_tipo_problema = 24 WHERE id_tipo_problema = 1;
--    tipos_problema 2 (CORRECTIVO) -> catalogo 50 (CORRECTIVO, hijo de CAT-0010)
UPDATE solicitudes SET id_tipo_problema = 50 WHERE id_tipo_problema = 2;

--    tipos_problema 3-6 -> nuevos hijos de CAT-0016 (join por nombre)
UPDATE solicitudes s
JOIN tipos_problema tp ON tp.id = s.id_tipo_problema
JOIN catalogo c ON c.nombre = tp.nombre AND c.id_superior = @cat_problema
SET s.id_tipo_problema = c.id
WHERE s.id_tipo_problema IN (3, 4, 5, 6);
