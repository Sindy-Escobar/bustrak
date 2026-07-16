-- ============================================
-- CONSULTAS PARA PHPMINIADMIN - ORDEN DE EJECUCIÓN
-- ============================================

USE bustrak_db;

-- =============================================
-- CONSULTA 1: VERIFICAR estado actual (EJECUTAR PRIMERO)
-- =============================================
SELECT TABLE_NAME 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'bustrak_db' 
AND TABLE_NAME IN ('calificacions', 'calificaciones', 'usuarios', 'users', 'asientos', 'historial_abordajes');


-- =============================================
-- CONSULTA 2: SI EXISTE "calificacions", RENOMBRARLA
-- =============================================
SHOW TABLES LIKE 'calificacions';
-- Si el resultado anterior muestra la tabla, ejecutar esta línea:
-- RENAME TABLE `calificacions` TO `calificaciones`;


-- =============================================
-- CONSULTA 3: CONTAR USUARIOS (verificar)
-- =============================================
SELECT 'Tabla usuarios' AS tabla, COUNT(*) AS cantidad FROM usuarios
UNION ALL
SELECT 'Tabla users' AS tabla, COUNT(*) AS cantidad FROM users;


-- =============================================
-- CONSULTA 4: MIGRAR USUARIOS A USERS
-- =============================================
INSERT IGNORE INTO users (name, email, password, created_at, updated_at)
SELECT nombre_completo, email, password, created_at, updated_at
FROM usuarios u_old
WHERE u_old.email NOT IN (SELECT email FROM users u_new);


-- =============================================
-- CONSULTA 5: CREAR TABLA DE MAPEO TEMPORAL
-- =============================================
CREATE TABLE IF NOT EXISTS `mapeo_usuarios_temporal` (
  `usuario_id_antiguo` bigint unsigned NOT NULL,
  `user_id_nuevo` bigint unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`usuario_id_antiguo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =============================================
-- CONSULTA 6: LLENAR MAPEO TEMPORAL
-- =============================================
INSERT INTO mapeo_usuarios_temporal (usuario_id_antiguo, user_id_nuevo, email)
SELECT u_old.id, u_new.id, u_old.email
FROM usuarios u_old
INNER JOIN users u_new ON u_old.email = u_new.email;


-- =============================================
-- CONSULTA 7: VERIFICAR MAPEO (debe mostrar registros)
-- =============================================
SELECT COUNT(*) AS `Registros mapeados` FROM mapeo_usuarios_temporal;


-- =============================================
-- CONSULTA 8: ACTUALIZAR comentario_conductores
-- =============================================
UPDATE comentario_conductores cc
JOIN mapeo_usuarios_temporal mt ON cc.usuario_id = mt.usuario_id_antiguo
SET cc.usuario_id = mt.user_id_nuevo;


-- =============================================
-- CONSULTA 9: ACTUALIZAR registro_rentas
-- =============================================
UPDATE registro_rentas rr
JOIN mapeo_usuarios_temporal mt ON rr.usuario_id = mt.usuario_id_antiguo
SET rr.usuario_id = mt.user_id_nuevo;


-- =============================================
-- CONSULTA 10: VERIFICAR FKs EXISTENTES (consulta de verificación)
-- =============================================
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_NAME = 'usuarios';


-- =============================================
-- CONSULTA 11: ELIMINAR FKs ANTIGUAS (si existen)
-- =============================================
-- Si la consulta 10 devuelve resultados, ejecutar esto:
ALTER TABLE `comentario_conductores` DROP FOREIGN KEY `comentario_conductores_usuario_id_foreign`;
ALTER TABLE `registro_rentas` DROP FOREIGN KEY `registro_rentas_usuario_id_foreign`;


-- =============================================
-- CONSULTA 12: CREAR NUEVAS FKs apuntando a users
-- =============================================
ALTER TABLE `comentario_conductores`
  ADD CONSTRAINT `comentario_conductores_usuario_id_foreign`
  FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `registro_rentas`
  ADD CONSTRAINT `registro_rentas_usuario_id_foreign`
  FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;


-- =============================================
-- CONSULTA 13: CONTEO FINAL antes de eliminar tabla
-- =============================================
SELECT 
    (SELECT COUNT(*) FROM usuarios) AS `usuarios_table`,
    (SELECT COUNT(*) FROM users) AS `users_table`,
    (SELECT COUNT(DISTINCT usuario_id) FROM comentario_conductores) AS `comentarios_conductores`,
    (SELECT COUNT(DISTINCT usuario_id) FROM registro_rentas) AS `registro_rentas`;


-- =============================================
-- CONSULTA 14: ELIMINAR TABLA USUARIOS (PUNTO DE NO RETORNO)
-- =============================================
-- ADVERTENCIA: Este cambio es IRREVERSIBLE
-- Los datos están respaldados en mapeo_usuarios_temporal
DROP TABLE IF EXISTS `usuarios`;


-- =============================================
-- CONSULTA 15: ELIMINAR TABLA MAPEO TEMPORAL
-- =============================================
DROP TABLE IF EXISTS `mapeo_usuarios_temporal`;


-- =============================================
-- CONSULTA 16: VERIFICAR ESTRUCTURA FINAL
-- =============================================
DESCRIBE asientos;
DESCRIBE historial_abordajes;


-- =============================================
-- CONSULTA 17: VALIDAR INTEGRIDAD (sin datos huérfanos)
-- =============================================
SELECT COUNT(*) AS `asientos sin viaje válido` FROM asientos 
WHERE viaje_id NOT IN (SELECT id FROM viajes);

SELECT COUNT(*) AS `abordajes sin asiento` FROM historial_abordajes
WHERE asiento_id NOT IN (SELECT id FROM asientos);

SELECT COUNT(*) AS `abordajes sin reserva válida` FROM historial_abordajes
WHERE reserva_id IS NOT NULL 
AND reserva_id NOT IN (SELECT id FROM reservas);


-- =============================================
-- CONSULTA 18: VERIFICACIÓN FINAL DE FKs
-- =============================================
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'bustrak_db'
ORDER BY TABLE_NAME, CONSTRAINT_NAME;
