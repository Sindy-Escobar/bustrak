# FIXES DEFINITIVO - BUSTRAK

## 1. Problema detectado

- El login de Laravel fallaba porque el controlador `AuthController` intentaba escribir en la columna `plain_password` de la tabla `users`.
- Esa columna ya no existe en la base de datos actual, por eso se producía el error `Unknown column 'plain_password' in 'field list'`.
- Además, las consultas SQL se estaban ejecutando sin seleccionar explícitamente la base de datos correcta, lo que causaba errores en phpMyAdmin cuando la sesión apuntaba a `information_schema`.

## 2. Cambios aplicados en el código

### Archivos modificados
- `app/Http/Controllers/AuthController.php`
  - Eliminada la escritura de `plain_password` en el login.
  - Eliminada la escritura de `plain_password` al registrar usuarios.
  - Eliminada la escritura de `plain_password` al resetear contraseñas.
  - Eliminada la escritura de `plain_password` al cambiar la contraseña desde el perfil.

- `app/Http/Controllers/Auth/ForgotPasswordController.php`
  - Imagen guardada con valor genérico `No disponible` en lugar de intentar leer `plain_password`.

- `app/Models/User.php`
  - Eliminado `plain_password` de `$fillable`.

- `database/seeders/DatabaseSeeder.php`
  - Eliminado el uso de `plain_password` en los usuarios de prueba.

## 3. Resultado funcional

- Ahora el login ya no depende de la columna `plain_password`.
- Los usuarios pueden iniciar sesión normalmente si su contraseña hash en `users.password` es correcta.
- El código ya está alineado con la base de datos actual.

## 4. SQL definitivo para ejecutar

### Archivo SQL final
- `SQL_DEFINITIVO_BUSTRAK.sql`

### Qué hace
- Selecciona explícitamente la base de datos `bustrak_db`.
- Verifica si existe la tabla `calificacions` antes de renombrarla.
- Migra los usuarios faltantes de `usuarios` a `users`.
- Crea tabla de mapeo temporal para actualizar FKs.
- Actualiza `comentario_conductores.usuario_id` y `registro_rentas.usuario_id`.
- Mantiene los datos y evita pérdidas.
- Valida la integridad final de `asientos`, `historial_abordajes`, y FKs.

## 5. Instrucciones de uso

1. Abrir `SQL_DEFINITIVO_BUSTRAK.sql` en phpMyAdmin.
2. Ejecutar el script paso a paso.
   - Si `SHOW TABLES LIKE 'calificacions'` no devuelve fila, no ejecutar la línea de `RENAME TABLE`.
3. Verificar que `users` y `usuarios` queden unificados correctamente.
4. Ejecutar en terminal el siguiente comando para sincronizar Laravel:

```bash
cd c:\Users\Administrador\Desktop\UNIVERSITY\bustrak
php artisan cache:clear
php artisan config:clear
composer dump-autoload
```

5. Probar el login en la aplicación.

## 6. Verificación mínima

- `users` contiene los usuarios actuales.
- `usuarios` se elimina después de migrar si todo está correcto.
- `calificaciones` existe y `calificacions` no debe ejecutarse si no existe.
- No se escribe ni se lee `plain_password` desde el código.

## 7. Archivos finales para entregar

- `SQL_DEFINITIVO_BUSTRAK.sql`
- `FIXES_DEFINITIVO_BUSTRAK.md`

---

**Estado actual:** Correcciones de código aplicadas.
**Próximo paso:** Ejecutar `SQL_DEFINITIVO_BUSTRAK.sql` y validar el login.
