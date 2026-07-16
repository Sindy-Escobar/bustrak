-- ============================================
-- CORRECCIONES DE ERRORES ENCONTRADOS
-- ============================================

-- ============================================
-- ERROR 1: plain_password ya no existe
-- ============================================
-- Error original: "#1091 - No puedo eliminar (DROP COLUMN) `plain_password`"
-- Causa: La columna ya fue eliminada previamente
-- Solución: Ignorar este error, ya está corregido

-- Verificar si existe:
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'users' 
AND COLUMN_NAME = 'plain_password';
-- Resultado: (vacío) - La columna NO existe ✅


-- ============================================
-- ERROR 2: Tabla lugars ya fue eliminada
-- ============================================
-- Error original: "#1146 - Tabla 'bustrak_db.lugars' no existe"
-- Causa: La tabla fue eliminada en una ejecución anterior
-- Solución: Ignorar este error, ya está corregido

-- Verificar si existe:
SELECT TABLE_NAME 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'bustrak_db' 
AND TABLE_NAME = 'lugars';
-- Resultado: (vacío) - La tabla NO existe ✅


-- ============================================
-- ERROR 3: empresa_id ya existía en buses
-- ============================================
-- Resultado: "0 filas afectadas"
-- Causa: La columna ya existía previamente
-- Solución: Ignorar, ya está correcta

-- Verificar estructura de buses:
DESCRIBE buses;

-- Verificar que empresa_id está presente:
SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_KEY
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'buses'
AND COLUMN_NAME = 'empresa_id';


-- ============================================
-- CORRECCIÓN NECESARIA: Verificar usuario_id
-- ============================================
-- Algunas tablas podrían tener ambas referencias

-- Ver qué tablas apuntan a 'usuarios':
SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_NAME = 'usuarios'
AND TABLE_SCHEMA = 'bustrak_db';


-- Ver qué columnas usuario_id existen:
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'bustrak_db'
AND COLUMN_NAME = 'usuario_id'
ORDER BY TABLE_NAME;


-- ============================================
-- CORRECCIÓN SEGURA: Backup antes de eliminar
-- ============================================
-- Si vas a eliminar la tabla 'usuarios', primero:

-- 1. Crear backup completo
CREATE TABLE `usuarios_backup_final` AS SELECT * FROM `usuarios`;

-- 2. Verificar que los datos se copiaron
SELECT COUNT(*) AS total_usuarios FROM `usuarios_backup_final`;

-- 3. Ahora sí, es seguro eliminar:
-- DROP TABLE `usuarios`;


-- ============================================
-- VERIFICACIÓN FINAL - EJECUTAR ESTO AL FINAL
-- ============================================

-- Contar registros por tabla clave:
SELECT 
    'users' AS tabla,
    COUNT(*) AS registros,
    'ok' AS estado
FROM users
UNION ALL
SELECT 
    'asientos',
    COUNT(*),
    'ok'
FROM asientos
UNION ALL
SELECT 
    'historial_abordajes',
    COUNT(*),
    'ok'
FROM historial_abordajes
UNION ALL
SELECT 
    'calificaciones',
    COUNT(*),
    'ok'
FROM calificaciones
UNION ALL
SELECT 
    'comentario_conductores',
    COUNT(*),
    'ok'
FROM comentario_conductores
UNION ALL
SELECT 
    'registro_rentas',
    COUNT(*),
    'ok'
FROM registro_rentas;


-- Verificar FK correctas:
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'bustrak_db'
AND REFERENCED_TABLE_NAME IS NOT NULL
ORDER BY TABLE_NAME, CONSTRAINT_NAME;


-- Verificar que NO quedan referencias a 'usuarios':
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'bustrak_db'
AND REFERENCED_TABLE_NAME = 'usuarios';
-- Resultado esperado: (vacío) - Sin referencias ✅
