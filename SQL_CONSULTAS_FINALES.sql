-- ====================================================
-- SQL DEFINITIVO - CORRECCIONES BUSTRAK DB
-- ====================================================
-- Ejecutar en phpMyAdmin línea por línea
-- Usuario: root | BD: bustrak_db
-- ====================================================

-- ====================================================
-- PASO 1: SELECCIONAR BD Y VERIFICAR ESTADO
-- ====================================================

USE bustrak_db;

-- Verificar tablas claves
SELECT TABLE_NAME 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA='bustrak_db' 
AND TABLE_NAME IN ('usuarios','users','calificaciones','calificacions','asientos','historial_abordajes','mapeo_usuarios','mapeo_usuarios_temporal')
ORDER BY TABLE_NAME;

-- ====================================================
-- PASO 2: RENOMBRAR CALIFICACIONS (si existe)
-- ====================================================

-- Verificar si existe
SHOW TABLES LIKE 'calificacions';

-- Renombrar (ejecutar SOLO si el anterior mostró 'calificacions')
-- RENAME TABLE `calificacions` TO `calificaciones`;

-- ====================================================
-- PASO 3: VERIFICAR CONTEO DE USUARIOS
-- ====================================================

SELECT 'Tabla usuarios' AS tabla, COUNT(*) AS cantidad FROM usuarios
UNION ALL
SELECT 'Tabla users' AS tabla, COUNT(*) AS cantidad FROM users
UNION ALL
SELECT 'mapeo_usuarios' AS tabla, COUNT(*) AS cantidad FROM mapeo_usuarios;

-- ====================================================
-- PASO 4: MIGRAR USUARIOS FALTANTES DE USUARIOS A USERS
-- ====================================================

INSERT IGNORE INTO users (name, email, password, role, estado, created_at, updated_at)
SELECT 
    COALESCE(nombre_completo, email) AS name,
    email,
    COALESCE(password, '') AS password,
    'Cliente' AS role,
    'activo' AS estado,
    NOW() AS created_at,
    NOW() AS updated_at
FROM usuarios u_old
WHERE u_old.email NOT IN (SELECT email FROM users u_new);

-- Verificar migracion
SELECT COUNT(*) AS usuarios_migrados 
FROM usuarios u 
WHERE u.email IN (SELECT email FROM users);

-- ====================================================
-- PASO 5: CREAR TABLA DE MAPEO TEMPORAL
-- ====================================================

DROP TABLE IF EXISTS `mapeo_usuarios_temporal`;

CREATE TABLE `mapeo_usuarios_temporal` (
  `usuario_id_antiguo` bigint unsigned NOT NULL,
  `user_id_nuevo` bigint unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`usuario_id_antiguo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================
-- PASO 6: LLENAR TABLA DE MAPEO
-- ====================================================

INSERT INTO mapeo_usuarios_temporal (usuario_id_antiguo, user_id_nuevo, email)
SELECT u_old.id, u_new.id, u_old.email
FROM usuarios u_old
INNER JOIN users u_new ON u_old.email = u_new.email;

-- Verificar mapeo
SELECT COUNT(*) AS total_mapeados FROM mapeo_usuarios_temporal;

SELECT * FROM mapeo_usuarios_temporal LIMIT 5;

-- ====================================================
-- PASO 7: ACTUALIZAR FOREIGN KEYS EN COMENTARIO_CONDUCTORES
-- ====================================================

-- Antes de actualizar, verificar cuántos registros hay
SELECT COUNT(*) AS registros_antes_actualizar 
FROM comentario_conductores 
WHERE usuario_id IN (SELECT usuario_id_antiguo FROM mapeo_usuarios_temporal);

-- Actualizar IDs
UPDATE comentario_conductores cc
JOIN mapeo_usuarios_temporal mt ON cc.usuario_id = mt.usuario_id_antiguo
SET cc.usuario_id = mt.user_id_nuevo;

-- Verificar actualización
SELECT COUNT(DISTINCT usuario_id) AS usuarios_unicos_comentarios FROM comentario_conductores;

-- ====================================================
-- PASO 8: ACTUALIZAR FOREIGN KEYS EN REGISTRO_RENTAS
-- ====================================================

-- Antes de actualizar, verificar cuántos registros hay
SELECT COUNT(*) AS registros_antes_actualizar 
FROM registro_rentas 
WHERE usuario_id IN (SELECT usuario_id_antiguo FROM mapeo_usuarios_temporal);

-- Actualizar IDs
UPDATE registro_rentas rr
JOIN mapeo_usuarios_temporal mt ON rr.usuario_id = mt.usuario_id_antiguo
SET rr.usuario_id = mt.user_id_nuevo;

-- Verificar actualización
SELECT COUNT(DISTINCT usuario_id) AS usuarios_unicos_rentas FROM registro_rentas;

-- ====================================================
-- PASO 9: VERIFICAR TODAS LAS TABLAS QUE REFERENCIA usuario_id A usuarios
-- ====================================================

SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_NAME = 'usuarios' 
AND TABLE_SCHEMA = 'bustrak_db';

-- Si hay resultados, aquí están los cambios de FK necesarios (si aplican):
-- Las migraciones ya deben haber actualizado los datos

-- ====================================================
-- PASO 10: ELIMINAR TABLAS TEMPORALES (LIMPIEZA)
-- ====================================================

-- Eliminar tabla de mapeo temporal
DROP TABLE IF EXISTS `mapeo_usuarios_temporal`;

-- Eliminar tabla de mapeo vieja si existe
DROP TABLE IF EXISTS `mapeo_usuarios`;

-- ====================================================
-- PASO 11: VERIFICAR INTEGRIDAD REFERENCIAL
-- ====================================================

-- Comprobar asientos sin viaje
SELECT COUNT(*) AS asientos_sin_viaje FROM asientos 
WHERE viaje_id NOT IN (SELECT id FROM viajes);

-- Comprobar historial_abordajes sin asiento
SELECT COUNT(*) AS abordajes_sin_asiento FROM historial_abordajes
WHERE asiento_id NOT IN (SELECT id FROM asientos);

-- Comprobar historial_abordajes sin reserva válida
SELECT COUNT(*) AS abordajes_sin_reserva_valida FROM historial_abordajes
WHERE reserva_id IS NOT NULL 
AND reserva_id NOT IN (SELECT id FROM reservas);

-- Todos estos conteos deben retornar 0

-- ====================================================
-- PASO 12: VERIFICAR ESTRUCTURA FINAL DE TABLAS
-- ====================================================

-- Estructura de asientos (debe tener: fila, columna, tipo, estado - SIN reserva_id)
DESCRIBE asientos;

-- Estructura de historial_abordajes
DESCRIBE historial_abordajes;

-- Estructura de users (debe tener usuario_id)
DESCRIBE users;

-- ====================================================
-- PASO 13: VERIFICAR ESTADÍSTICAS FINALES
-- ====================================================

SELECT 'asientos' AS tabla, COUNT(*) AS total FROM asientos
UNION ALL
SELECT 'historial_abordajes' AS tabla, COUNT(*) AS total FROM historial_abordajes
UNION ALL
SELECT 'users' AS tabla, COUNT(*) AS total FROM users
UNION ALL
SELECT 'usuarios' AS tabla, COUNT(*) AS total FROM usuarios
UNION ALL
SELECT 'calificaciones' AS tabla, COUNT(*) AS total FROM calificaciones
UNION ALL
SELECT 'comentario_conductores' AS tabla, COUNT(*) AS total FROM comentario_conductores
UNION ALL
SELECT 'registro_rentas' AS tabla, COUNT(*) AS total FROM registro_rentas;

-- ====================================================
-- PASO 14 (OPCIONAL): ELIMINAR TABLA USUARIOS ANTIGUA
-- ====================================================

-- ⚠️ PUNTO DE NO RETORNO ⚠️
-- Ejecutar SOLO cuando todo esté verificado y funcionando

-- Crear backup antes
-- CREATE TABLE `usuarios_backup_final` AS SELECT * FROM `usuarios`;

-- Eliminar tabla usuarios
-- DROP TABLE IF EXISTS `usuarios`;

-- ====================================================
-- VERIFICACION FINAL
-- ====================================================

-- Mostrar FKs finales
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'bustrak_db'
AND (REFERENCED_TABLE_NAME = 'users' OR TABLE_NAME IN ('comentario_conductores', 'registro_rentas', 'asientos', 'historial_abordajes'))
ORDER BY TABLE_NAME;

-- Contar usuarios en tabla final
SELECT COUNT(*) AS total_usuarios_finales FROM users;

-- ====================================================
-- ✅ COMPLETADO
-- ====================================================
-- Resultado esperado:
-- - users contiene todos los usuarios
-- - usuarios puede eliminarse después de validación
-- - calificaciones renombrada correctamente
-- - asientos sin reserva_id
-- - historial_abordajes con datos migrados
-- - 0 datos huérfanos
-- ====================================================
