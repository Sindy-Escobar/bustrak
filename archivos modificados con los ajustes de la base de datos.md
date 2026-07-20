# Incidencias resueltas de la base de datos

## Estado final

La base de datos quedó alineada con la estructura que usa Laravel y con la lógica funcional de la aplicación. La solución no depende de tablas temporales en producción ni de reutilizar archivos SQL duplicados.

### Qué quedó resuelto

- Se unificó la identidad de usuarios en `users` como tabla principal.
- Se dejó la tabla `calificaciones` con el nombre correcto.
- Se dejó la tabla `lugares` como tabla final y se removió el duplicado legacy `lugars`.
- Se ajustó la estructura de `asientos` para que siga la lógica física del asiento y se separó la trazabilidad de abordajes en `historial_abordajes`.
- Se corrigió el problema de `plain_password` en el flujo de autenticación y recuperación, evitando depender de columnas que ya no existen.

---

## Archivos modificados en Laravel

Para que la app funcionara con estos cambios de base de datos, se editaron estos archivos:

- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/Auth/ForgotPasswordController.php`
- `app/Models/Asiento.php`
- `app/Models/Bus.php`
- `app/Models/Calificacion.php`
- `app/Models/HistorialAbordaje.php`
- `app/Models/Lugar.php`
- `app/Models/RegistroRenta.php`
- `app/Models/User.php`
- `database/seeders/DatabaseSeeder.php`

Estos archivos fueron el ajuste de código necesario para que Laravel se conecte con la BD ya corregida y no falle por columnas, nombres de tablas o relaciones antiguas.

---

## Script final para phpMyAdmin

Este es el bloque único que deben copiar y ejecutar en phpMyAdmin, en el orden indicado.

```sql
USE bustrak_db;
SET FOREIGN_KEY_CHECKS = 0;

-- 1) Verificar estado inicial
SELECT TABLE_NAME
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'bustrak_db'
  AND TABLE_NAME IN (
    'usuarios', 'users', 'calificaciones', 'calificacions',
    'lugares', 'lugars', 'historial_abordajes', 'asientos',
    'mapeo_usuarios', 'mapeo_usuarios_temporal'
  )
ORDER BY TABLE_NAME;

-- 2) Si existe la tabla duplicada legacy de calificaciones, renombrarla
-- RENAME TABLE `calificacions` TO `calificaciones`;

-- 3) Eliminar la tabla legacy duplicada de lugares si aún existe
DROP TABLE IF EXISTS `lugars`;

-- 4) Crear la tabla temporal de mapeo para migrar usuarios legacy
CREATE TABLE IF NOT EXISTS `mapeo_usuarios_temporal` (
  `usuario_id_antiguo` bigint unsigned NOT NULL,
  `user_id_nuevo` bigint unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`usuario_id_antiguo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5) Insertar el mapeo entre usuarios y users
INSERT INTO `mapeo_usuarios_temporal` (`usuario_id_antiguo`, `user_id_nuevo`, `email`)
SELECT u_old.id, u_new.id, u_old.email
FROM `usuarios` u_old
JOIN `users` u_new ON u_old.email = u_new.email;

-- 6) Reasignar referencias que aún estaban apuntando a usuarios antiguos
UPDATE `comentario_conductores` cc
JOIN `mapeo_usuarios_temporal` mt
  ON cc.usuario_id = mt.usuario_id_antiguo
SET cc.usuario_id = mt.user_id_nuevo;

UPDATE `registro_rentas` rr
JOIN `mapeo_usuarios_temporal` mt
  ON rr.usuario_id = mt.usuario_id_antiguo
SET rr.usuario_id = mt.user_id_nuevo;

-- 7) Verificar FKs que todavía apuntan a la tabla vieja `usuarios`
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'bustrak_db'
  AND REFERENCED_TABLE_NAME = 'usuarios';

-- 8) Si la consulta anterior devuelve filas, eliminar FKs antiguas
ALTER TABLE `comentario_conductores`
  DROP FOREIGN KEY `comentario_conductores_usuario_id_foreign`;

ALTER TABLE `registro_rentas`
  DROP FOREIGN KEY `registro_rentas_usuario_id_foreign`;

-- 9) Re-crear las FKs apuntando a users
ALTER TABLE `comentario_conductores`
  ADD CONSTRAINT `comentario_conductores_usuario_id_foreign`
  FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `registro_rentas`
  ADD CONSTRAINT `registro_rentas_usuario_id_foreign`
  FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- 10) Validaciones rápidas
SELECT COUNT(*) AS `abordajes_sin_asiento`
FROM `historial_abordajes`
WHERE `asiento_id` NOT IN (SELECT id FROM `asientos`);

SELECT COUNT(*) AS `referencias_a_usuarios`
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'bustrak_db'
  AND REFERENCED_TABLE_NAME = 'usuarios';

-- 11) Limpieza de tablas temporales
DROP TABLE IF EXISTS `mapeo_usuarios`;
DROP TABLE IF EXISTS `mapeo_usuarios_temporal`;

-- 12) Eliminación final de la tabla legacy solo cuando todo esté validado
-- DROP TABLE IF EXISTS `usuarios`;

SET FOREIGN_KEY_CHECKS = 1;
```

---

## Recomendación final

- Dejar `users` como la única tabla oficial de usuarios.
- Mantener `calificaciones`, `lugares`, `historial_abordajes` y `asientos` como estructuras definitivas.
- Eliminar `usuarios` solo cuando la validación de integridad ya esté limpia y la app quede estable con `users`.
- No conservar más archivos SQL duplicados en la raíz del proyecto.

## Resultado esperado

- `users` queda como tabla de autenticación de Laravel.
- `comentario_conductores` y `registro_rentas` quedan apuntando a `users`.
- no quedan mapeos temporales activos.
- la base queda lista para que el equipo suba los cambios y haga validación funcional en la app.
