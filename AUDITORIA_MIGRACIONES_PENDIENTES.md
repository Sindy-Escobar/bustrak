# 🚨 AUDITORÍA COMPLETA: Referencias a Tablas Eliminadas

## Resumen Ejecutivo

Se identificaron **5 migraciones** que aún referencian tablas que fueron eliminadas o renombradas durante el proceso de migración de la base de datos. Estas referencias causarán errores si se ejecutan las migraciones en un entorno limpio o si se regenera la base de datos.

---

## 🔴 Tablas Problemáticas Identificadas

| Tabla | Estado | Acción Requerida |
|-------|--------|------------------|
| `usuarios` | ELIMINADA (unificada con `users`) | Actualizar FKs a `users` |
| `lugars` | ELIMINADA (renombrada a `lugares`) | Eliminar migración o actualizar a `lugares` |

---

## 📋 Archivos que Requieren Edición

### 1. `database/migrations/2025_11_25_010153_create_calificaciones_chofer_table.php`

**Problema:** La foreign key apunta a la tabla `usuarios` (eliminada)

**Línea 19:**
```php
// ❌ ANTES (INCORRECTO)
$table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');

// ✅ DESPUÉS (CORRECTO)
$table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
```

**Acción:** Cambiar `'usuarios'` por `'users'` en la línea 19.

---

### 2. `database/migrations/2025_11_05_210623_create_registro_renta_table.php`

**Problema:** La foreign key `constrained` apunta a `usuarios`

**Línea 16:**
```php
// ❌ ANTES (INCORRECTO)
$table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();

// ✅ DESPUÉS (CORRECTO)
$table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
```

**Acción:** Cambiar `'usuarios'` por `'users'` en la línea 16.

---

### 3. `database/migrations/2025_11_09_025719_create_reservas_table.php`

**Problema:** La foreign key `constrained` apunta a `usuarios`

**Línea 13:**
```php
// ❌ ANTES (INCORRECTO)
$table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');

// ✅ DESPUÉS (CORRECTO)
$table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
```

**Acción:** Cambiar `'usuarios'` por `'users'` en la línea 13.

> **Nota:** Existe otra migración `2025_11_12_084627_fix_reservas_user_foreign_key.php` que ya corrige esto parcialmente, pero verifica que el nombre de la columna sea consistente (`usuario_id` vs `user_id`).

---

### 4. `database/migrations/2026_02_14_083413_create_comentario_conductores_table.php`

**Problema:** La foreign key `constrained` apunta a `usuarios`

**Línea 18:**
```php
// ❌ ANTES (INCORRECTO)
$table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');

// ✅ DESPUÉS (CORRECTO)
$table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
```

**Acción:** Cambiar `'usuarios'` por `'users'` en la línea 18.

---

### 5. `database/migrations/2025_12_01_191808_create_lugars_table.php`

**Problema:** Esta migración crea la tabla `lugars` que NO debe existir. Debe crearse `lugares`.

**Líneas 11 y 22:**
```php
// ❌ ANTES (INCORRECTO)
Schema::create('lugars', function (Blueprint $table) {
    // ...
});

Schema::dropIfExists('lugars');

// ✅ DESPUÉS (CORRECTO)
Schema::create('lugares', function (Blueprint $table) {
    // ...
});

Schema::dropIfExists('lugares');
```

**Acción:** 
1. Cambiar `'lugars'` por `'lugares'` en la línea 11
2. Cambiar `'lugars'` por `'lugares'` en la línea 22

> **Importante:** El modelo `App\Models\Lugar.php` ya apunta correctamente a la tabla `lugares`. Esta migración debe ser consistente con el modelo.

---

### 6. `database/migrations/2025_10_16_001318_create_usuarios_table.php`

**Problema:** Esta migración crea la tabla `usuarios` que fue eliminada.

**Acción Recomendada:** 
- **Opción A (Recomendada):** Eliminar esta migración si ya no se necesita recrear la tabla `usuarios` desde cero.
- **Opción B:** Renombrar para que cree la tabla `users` en su lugar (pero esto puede causar conflictos con otras migraciones de `users`).

> **Nota:** Si eliges la Opción A, asegúrate de que la tabla `users` ya exista por otras migraciones (ej. `create_users_table` de Laravel).

---

### 7. `database/migrations/2025_10_27_141425_add_campos_filtro_to_usuarios_table.php`

**Problema:** Esta migración modifica la tabla `usuarios` que fue eliminada.

**Líneas 14 y 24:**
```php
// ❌ ANTES (INCORRECTO)
Schema::table('usuarios', function (Blueprint $table) {
    // ...
});

// ✅ DESPUÉS (CORRECTO)
Schema::table('users', function (Blueprint $table) {
    // ...
});
```

**Acción:** Cambiar `'usuarios'` por `'users'` en las líneas 14 y 24.

---

## 🛠️ Pasos para Aplicar las Correcciones

### Paso 1: Editar los archivos de migración

Abre cada uno de los 7 archivos listados arriba y realiza los cambios indicados.

### Paso 2: Verificar consistencia de columnas

Asegúrate de que las columnas en las tablas coincidan:

| Tabla | Columna Esperada | Verificar en Modelos |
|-------|-----------------|---------------------|
| `users` | `id`, `name`, `email`, `dni`, `telefono`, `role`, `estado` | `App\Models\User`, `App\Models\Usuario` |
| `lugares` | `id`, `departamento_id`, `nombre`, `imagen` | `App\Models\Lugar` |

### Paso 3: Limpiar caché de configuraciones

Después de editar los archivos, ejecuta:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Paso 4: Verificar migraciones pendientes

```bash
php artisan migrate:status
```

Si hay migraciones ya ejecutadas que necesitan rollback para corregir FKs:

```bash
# Solo en entorno de desarrollo
php artisan migrate:rollback --step=1
```

### Paso 5: Ejecutar migraciones corregidas

```bash
php artisan migrate
```

---

## ✅ Verificación Final

Después de aplicar los cambios, ejecuta estas consultas SQL para verificar que no queden referencias rotas:

```sql
-- Verificar FKs apuntando a tablas inexistentes
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'bustrak_db'
  AND REFERENCED_TABLE_NAME IN ('usuarios', 'lugars');

-- Debería retornar 0 filas si todo está correcto
```

---

## 📌 Notas Importantes

1. **Backup Primero:** Antes de modificar migraciones que ya se ejecutaron en producción, haz un backup completo de la base de datos.

2. **Entorno de Desarrollo:** Prueba todos los cambios primero en un entorno de desarrollo con una base de datos limpia.

3. **Migraciones Ya Ejecutadas:** Si las migraciones problemáticas ya se ejecutaron en producción, NO las modifiques directamente. En su lugar:
   - Crea una nueva migración que corrija las FKs
   - O usa `php artisan migrate:rollback` en desarrollo para rehacerlas

4. **Modelo Usuario:** El archivo `app/Models/Usuario.php` ya está configurado correctamente para usar la tabla `users`. No requiere cambios adicionales.

5. **Tabla `lugars`:** Esta tabla NUNCA debió existir. Fue un error gramatical. La tabla correcta es `lugares` (plural de "lugar" en español).

---

## 🎯 Estado Actual del Proyecto

| Componente | Estado | Observaciones |
|------------|--------|---------------|
| Modelo `Usuario` | ✅ Correcto | Apunta a tabla `users` |
| Controlador `RegistroUsuarioController` | ✅ Correcto | Usa solo tabla `users` |
| Migraciones de FKs a `usuarios` | ❌ Pendiente | 4 archivos por corregir |
| Migración `lugars` | ❌ Pendiente | 1 archivo por corregir |
| Migración `create_usuarios_table` | ⚠️ Revisar | Evaluar si eliminar |
| Migración `add_campos_filtro_to_usuarios` | ❌ Pendiente | Cambiar a `users` |

---

## 📞 Contacto

Si tienes dudas sobre algún cambio específico, revisa el archivo original de documentación:
- `/workspace/archivos modificados con los ajustes de la base de datos.md`

Este documento resume el proceso de migración original y la lógica detrás de la unificación de tablas.
