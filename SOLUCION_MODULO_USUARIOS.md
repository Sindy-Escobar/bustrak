# 📋 Instrucciones para Solucionar el Módulo de Gestión de Usuarios

## ⚠️ Problema Detectado

El módulo de "Gestionar Usuario" estaba generando el siguiente error:

```
Illuminate\Database\QueryException
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'bustrak_db.usuarios' doesn't exist
(Connection: mysql, SQL: select count(*) as aggregate from `usuarios` inner join `users` on `usuarios`.`email` = `users`.`email`)
```

### Causa del Problema

Durante el proceso de migración de la base de datos, **la tabla `usuarios` fue eliminada** y sus datos se unificaron con la tabla `users`. Sin embargo, el código del controlador y el modelo seguían haciendo referencia a la tabla inexistente `usuarios`, causando el error al intentar realizar consultas.

---

## ✅ Solución Aplicada

Se modificaron **2 archivos** para que todo el módulo trabaje exclusivamente con la tabla `users`:

### 1. Archivo: `app/Models/Usuario.php`

**Ubicación:** `/workspace/app/Models/Usuario.php`

**Cambios realizados:**

- **Línea 12:** Se cambió el nombre de la tabla de `'usuarios'` a `'users'`
  ```php
  protected $table = 'users';
  ```

- **Líneas 14-23:** Se actualizaron los campos `fillable` para coincidir con la estructura de la tabla `users`:
  ```php
  protected $fillable = [
      'name',
      'nombre_completo',
      'dni',
      'email',
      'telefono',
      'password',
      'role',
      'estado',
  ];
  ```

---

### 2. Archivo: `app/Http/Controllers/RegistroUsuarioController.php`

**Ubicación:** `/workspace/app/Http/Controllers/RegistroUsuarioController.php`

**Cambios realizados:**

#### Método `store()` (Líneas 35-81)
- Se eliminó cualquier referencia a JOIN con la tabla `usuarios`
- Las validaciones ahora apuntan directamente a la tabla `users`:
  - Línea 41: `'dni' => 'required|numeric|digits:13|unique:users,dni'`
  - Línea 42: `'email' => 'required|email|unique:users,email'`
- El guardado de datos ahora usa exclusivamente el modelo `Usuario` que apunta a `users`

#### Método `consultar()` (Líneas 89-122)
- **SE ELIMINÓ EL JOIN** que causaba el error original
- Todas las consultas ahora se hacen directamente sobre la tabla `users` a través del modelo `Usuario`
- Los filtros funcionan correctamente sin necesidad de unir tablas

#### Método `update()` (Líneas 131-183)
- Las reglas de validación únicas ahora referencian correctamente la tabla `users`:
  - Línea 137: `Rule::unique('users', 'dni')->ignore($usuario->id)`
  - Línea 138: `Rule::unique('users', 'email')->ignore($usuario->id)`

---

## 🔍 Verificación

Para verificar que la solución funciona correctamente:

1. **Acceder a la ruta de consulta de usuarios:**
   ```
   http://127.0.0.1:8000/usuarios/consultar
   ```

2. **Probar las siguientes funcionalidades:**
   - ✅ Listado de usuarios (debe mostrar la paginación)
   - ✅ Búsqueda por nombre, email o DNI
   - ✅ Filtro por DNI
   - ✅ Filtro por estado (activo/inactivo)
   - ✅ Filtro por fecha de registro
   - ✅ Crear nuevo usuario
   - ✅ Editar usuario existente

---

## 📌 Notas Importantes

### Tablas Eliminadas en la Migración

Según la documentación del proyecto (`script maestro para la base de datos _incidencias resueltas.md`), las siguientes tablas fueron eliminadas durante la migración:

- ❌ `usuarios` → Unificada con `users`
- ❌ `lugars` → Proceso similar de migración aplicado
- ❌ Otras tablas temporales usadas en el proceso de migración

**No debe haber ningún rastro de código que haga referencia a estas tablas eliminadas.**

### Modelo Usuario

El modelo `Usuario` ahora es un alias que apunta a la tabla `users` de Laravel. Esto permite mantener la compatibilidad con el código existente sin necesidad de cambiar todas las referencias al modelo en todo el proyecto.

```php
// El modelo Usuario trabaja con la tabla users
class Usuario extends Model
{
    protected $table = 'users';
    // ... resto de configuración
}
```

---

## 🛠️ Si Encuentras Otros Errores Similares

Si encuentras otro módulo con errores de "table not found":

1. **Identifica la tabla faltante** en el mensaje de error
2. **Revisa el script maestro** para confirmar si fue eliminada en la migración
3. **Busca en el código** referencias a esa tabla:
   ```bash
   grep -r "nombre_tabla" app/
   ```
4. **Actualiza las referencias** para usar la tabla correcta (generalmente `users`)
5. **Prueba el módulo** para asegurar que funciona correctamente

---

## 📞 Contacto

Si tienes dudas sobre estos cambios, revisa:
- Archivo: `script maestro para la base de datos _incidencias resueltas.md` (documentación de la migración)
- Archivo: `archivos modificados con los ajustes de la base de datos.md` (lista de ajustes aplicados)

---

**Fecha de actualización:** $(date +%d/%m/%Y)  
**Estado:** ✅ Resuelto
