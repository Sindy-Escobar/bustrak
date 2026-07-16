--- INFORME_CORRECCION_INCIDENCIAS.md (原始)


+++ INFORME_CORRECCION_INCIDENCIAS.md (修改后)
# Informe de Corrección de Incidencias Técnicas - Base de Datos BusTrack

## Resumen Ejecutivo

Este documento presenta las **incidencias técnicas identificadas** en la base de datos `bustrak_db` que pueden corregirse **sin afectar la estabilidad del sistema**, junto con las consultas SQL exactas y las modificaciones de código requeridas.

---

## Incidencias Identificadas (Corrección Segura)

### 1. 🚨 ELIMINAR campo `plain_password` (Prueba 37 - CRÍTICA)

**Descripción:** La tabla `users` contiene un campo `plain_password` que almacena contraseñas en texto plano, lo cual representa un riesgo grave de seguridad.

**Estado actual:**
- Tabla `users` tiene campo `plain_password varchar(255) DEFAULT NULL`
- El modelo `User.php` incluye `plain_password` en `$fillable`

**Impacto:** Nulo - El campo ya es nullable y no hay evidencia de uso activo en el código.

---

### 2. 🔁 UNIFICAR tabla `lugars` → `lugares` (Pruebas 3, 9, 35)

**Descripción:** Existe una tabla `lugars` (mal escrita) duplicada con la misma estructura que `lugares`.

**Estado actual:**
- `lugares`: tabla correcta con FK a `departamentos`
- `lugars`: tabla duplicada con FK a `departamentos`
- No hay referencias en el código a `lugars`

**Impacto:** Bajo - Se migrarán datos y se eliminará la tabla redundante.

---

### 3. 🔁 UNIFICAR tabla `usuarios` → `users` (Pruebas 11, 32, 36)

**Descripción:** Coexistencia de dos tablas de usuarios con estructuras diferentes.

**Estado actual:**
- `users`: tabla Laravel estándar con autenticación
- `usuarios`: tabla personalizada con campos diferentes
- Algunas FKs apuntan a `usuarios` (ej: `comentario_conductores.usuario_id`, `registro_rentas.usuario_id`)

**Impacto:** Medio - Requiere actualizar FKs que apuntan a `usuarios`.

---

### 4. 📝 Renombrar `calificacions` → `calificaciones` (Prueba 34)

**Descripción:** La tabla `calificacions` está mal escrita (debería ser `calificaciones`).

**Estado actual:**
- Tabla `calificacions` existe con estructura correcta
- Tabla `calificaciones_chofer` también existe (diferente propósito)
- El controlador `CalificacionController.php` usa el modelo `Calificacion`

**Impacto:** Bajo - Solo requiere renombrar tabla y actualizar modelo.

---

### 5. ⚠️ Agregar FK `buses.empresa_id` → `empresa_buses.id` (Prueba 18)

**Descripción:** La tabla `buses` debería tener una relación con `empresa_buses` pero no existe el campo ni la FK.

**Estado actual:**
- Tabla `buses` no tiene campo `empresa_id`
- Tabla `empresa_buses` existe pero no tiene relaciones salientes

**Impacto:** Bajo - Requiere agregar columna y FK.

---

## Consultas SQL para Ejecutar

### 1. Eliminar campo `plain_password`

```sql
-- Verificar que no haya datos críticos en plain_password
SELECT id, email, plain_password FROM users WHERE plain_password IS NOT NULL;

-- Eliminar el campo (es seguro, ya es nullable)
ALTER TABLE `users` DROP COLUMN `plain_password`;
```

---

### 2. Unificar `lugars` → `lugares`

```sql
-- Migrar datos de lugars a lugares (evitando duplicados por nombre)
INSERT IGNORE INTO `lugares` (`id`, `departamento_id`, `nombre`, `imagen`, `created_at`, `updated_at`)
SELECT `id`, `departamento_id`, `nombre`, `imagen`, `created_at`, `updated_at`
FROM `lugars`
WHERE NOT EXISTS (
    SELECT 1 FROM `lugares` l WHERE l.nombre = `lugars`.nombre
);

-- Actualizar IDs si hay conflictos
-- (Opcional: si hay IDs duplicados, reasignar nuevos IDs)

-- Eliminar tabla lugars
DROP TABLE IF EXISTS `lugars`;

-- Verificar que no queden referencias
-- (No hay FKs apuntando a lugars según el dump)
```

---

### 3. Unificar `usuarios` → `users`

```sql
-- Paso 1: Analizar datos duplicados por email
SELECT u1.email, u1.id as users_id, u2.id as usuarios_id
FROM users u1
INNER JOIN usuarios u2 ON u1.email = u2.email;

-- Paso 2: Migrar datos de usuarios que no existen en users
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `dni`, `telefono`, `password`, `created_at`, `updated_at`)
SELECT
    u2.id,
    u2.nombre_completo as name,
    u2.email,
    u2.dni,
    u2.telefono,
    u2.password,
    u2.created_at,
    u2.updated_at
FROM `usuarios` u2
WHERE NOT EXISTS (
    SELECT 1 FROM `users` u1 WHERE u1.email = u2.email
);

-- Paso 3: Actualizar FKs que apuntan a usuarios
-- ComentarioConductores
ALTER TABLE `comentario_conductores`
    DROP FOREIGN KEY `comentario_conductores_usuario_id_foreign`;

ALTER TABLE `comentario_conductores`
    ADD CONSTRAINT `comentario_conductores_usuario_id_foreign`
    FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Registro_rentas
ALTER TABLE `registro_rentas`
    DROP FOREIGN KEY `registro_rentas_usuario_id_foreign`;

ALTER TABLE `registro_rentas`
    ADD CONSTRAINT `registro_rentas_usuario_id_foreign`
    FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Paso 4: Eliminar tabla usuarios (SOLO después de verificar que todas las FKs fueron actualizadas)
-- DROP TABLE IF EXISTS `usuarios`;
-- NOTA: Esta línea debe ejecutarse manualmente después de verificar que no hay más dependencias
```

---

### 4. Renombrar `calificacions` → `calificaciones`

```sql
-- Renombrar tabla
RENAME TABLE `calificacions` TO `calificaciones`;

-- La FK ya existe y apunta correctamente:
-- calificacions_reserva_id_foreign -> se actualizará automáticamente
-- calificacions_usuario_id_foreign -> se actualizará automáticamente
```

---

### 5. Agregar FK `buses.empresa_id`

```sql
-- Agregar columna empresa_id a buses
ALTER TABLE `buses`
    ADD COLUMN `empresa_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `tipo_servicio_id`;

-- Agregar FK con ON DELETE SET NULL (para no perder buses si se elimina empresa)
ALTER TABLE `buses`
    ADD CONSTRAINT `buses_empresa_id_foreign`
    FOREIGN KEY (`empresa_id`) REFERENCES `empresa_buses` (`id`) ON DELETE SET NULL;
```

---

## Modificaciones de Código Requeridas

### 1. Modelo `User.php` - Eliminar `plain_password`

**Archivo:** `/workspace/app/Models/User.php`

```php
// CAMBIAR:
protected $fillable = [
    'name',
    'nombre_completo',
    'dni',
    'telefono',
    'email',
    'password',
    'plain_password',  // ← ELIMINAR ESTA LÍNEA
    'role',
    'estado',
];

// POR:
protected $fillable = [
    'name',
    'nombre_completo',
    'dni',
    'telefono',
    'email',
    'password',
    'role',
    'estado',
];
```

---

### 2. Modelo `Lugar.php` - Verificar que use tabla correcta

**Archivo:** `/workspace/app/Models/Lugar.php`

El modelo ya está correcto, usa `lugares` por defecto. No requiere cambios.

**Acción adicional:** Eliminar cualquier referencia a `lugars` en migraciones futuras.

---

### 3. Crear Modelo `Calificacion.php` (renombrado)

**Archivo:** `/workspace/app/Models/Calificacion.php` (NUEVO)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';

    protected $fillable = [
        'reserva_id',
        'usuario_id',
        'estrellas',
        'comentario',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
```

---

### 4. Controlador `CalificacionController.php` - Actualizar referencia

**Archivo:** `/workspace/app/Http/Controllers/CalificacionController.php`

El controlador ya usa `App\Models\Calificacion`, pero debe verificarse que el modelo exista después del renombrado.

---

### 5. Modelo `Bus.php` - Agregar relación con Empresa

**Archivo:** `/workspace/app/Models/Bus.php`

```php
// AGREGAR al modelo Bus:

protected $fillable = [
    // ... campos existentes
    'empresa_id',  // ← AGREGAR
];

public function empresa()
{
    return $this->belongsTo(EmpresaBus::class, 'empresa_id');
}
```

---

### 6. Modelo `EmpresaBus.php` - Agregar relación inversa

**Archivo:** `/workspace/app/Models/EmpresaBus.php`

```php
// AGREGAR al modelo EmpresaBus:

public function buses()
{
    return $this->hasMany(Bus::class, 'empresa_id');
}
```

---

## Orden de Ejecución Recomendado

### Fase 1: Seguridad (Inmediato)
1. Ejecutar SQL para eliminar `plain_password`
2. Actualizar modelo `User.php`

### Fase 2: Limpieza de Tablas Duplicadas
3. Migrar datos de `lugars` → `lugares`
4. Eliminar tabla `lugars`
5. Migrar datos de `usuarios` → `users`
6. Actualizar FKs de `usuarios` → `users`
7. Eliminar tabla `usuarios` (verificar dependencias primero)

### Fase 3: Corrección de Nomenclatura
8. Renombrar `calificacions` → `calificaciones`
9. Crear modelo `Calificacion.php`
10. Verificar controladores

### Fase 4: Mejoras de Integridad
11. Agregar columna `empresa_id` a `buses`
12. Agregar FK `buses.empresa_id`
13. Actualizar modelos `Bus.php` y `EmpresaBus.php`

---

## Verificación Post-Corrección

Ejecutar las siguientes consultas para verificar:

```sql
-- Verificar que plain_password no exista
SHOW COLUMNS FROM users LIKE 'plain_password'; -- Debe retornar 0 filas

-- Verificar que lugars no exista
SHOW TABLES LIKE 'lugars'; -- Debe retornar 0 filas

-- Verificar que usuarios no exista
SHOW TABLES LIKE 'usuarios'; -- Debe retornar 0 filas

-- Verificar que calificaciones exista
SHOW TABLES LIKE 'calificaciones'; -- Debe retornar 1 fila

-- Verificar FKs de buses
SELECT
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_NAME = 'buses' AND CONSTRAINT_NAME = 'buses_empresa_id_foreign';
```

---

## Notas Importantes

1. **Backup:** Realizar backup completo de la base de datos antes de ejecutar cualquier consulta.
2. **Entorno de Pruebas:** Ejecutar primero en un entorno de staging/testing.
3. **Ventana de Mantenimiento:** Programar las correcciones en horario de bajo tráfico.
4. **Rollback:** Tener script de rollback preparado para cada fase.

---

**Elaborado por:** Asistente de Análisis Técnico
**Fecha:** Julio 2026
**Versión:** 1.0