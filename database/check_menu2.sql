SELECT id, menu, id_superior, nivel, orden FROM menu ORDER BY id_superior IS NULL DESC, id_superior ASC, orden ASC;
