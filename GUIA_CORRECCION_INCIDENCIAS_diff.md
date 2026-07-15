--- GUIA_CORRECCION_INCIDENCIAS.md (原始)


+++ GUIA_CORRECCION_INCIDENCIAS.md (修改后)
# 📋 Guía de Corrección de Incidencias - BusTrack

**Fecha:** Julio 2026
**Objetivo:** Resolver incidencias técnicas de base de datos y actualizar el código del proyecto

---

## 🎯 Incidencias que se Resuelven

| ID | Incidencia | Estado |
|----|------------|--------|
| **37** | Eliminar campo `plain_password` de `users` | ✅ RESUELTA |
| **3, 9, 35** | Unificar tabla `lugars` → `lugares` | ✅ RESUELTA |
| **11, 32, 36** | Unificar tabla `usuarios` → `users` | ✅ RESUELTA |
| **34** | Renombrar `calificacions` → `calificaciones` | ✅ RESUELTA |
| **10, 18** | Agregar FK `buses.empresa_id` → `empresa_buses` | ✅ RESUELTA |

> **Nota:** Las incidencias de seguridad (SSL, usuarios root) deben configurarse a nivel de servidor MySQL y no se incluyen en este script.

---

## PARTE 1 - CONSULTAS SQL PARA EJECUTAR EN LOCAL

### ⚠️ PRECAUCIONES ANTES DE EJECUTAR

1. **Hacer backup completo de la base de datos**
2. Ejecutar en entorno local primero
3. Verificar que no haya conexiones activas durante la migración
4. El orden de ejecución es CRÍTICO

---

### FASE 1: Eliminar `plain_password` (Seguridad - Prioridad Máxima)

```sql
-- ========================================
-- FASE 1: ELIMINAR CAMPO PLAIN_PASSWORD
-- ========================================
-- Incidencia #37: Almacenamiento de contraseñas en texto plano

ALTER TABLE `users` DROP COLUMN `plain_password`;
```

---

### FASE 2: Unificar Tablas Duplicadas

#### 2.1 Unificar `lugars` → `lugares`

```sql
-- ========================================
-- FASE 2.1: UNIFICAR LUGARS → LUGARES
-- ========================================
-- Incidencias #3, #9, #35

-- Paso 1: Migrar datos únicos de lugars a lugares (si existen)
INSERT IGNORE INTO `lugares` (`id`, `departamento_id`, `nombre`, `imagen`, `created_at`, `updated_at`)
SELECT `id`, `departamento_id`, `nombre`, `imagen`, `created_at`, `updated_at`
FROM `lugars`
WHERE NOT EXISTS (
    SELECT 1 FROM `lugares` WHERE `lugares`.`id` = `lugars`.`id`
);

-- Paso 2: Actualizar FKs que apuntan a lugars (si existen)
-- Nota: En tu DB actual, lugars tiene FK a departamentos, pero no hay tablas apuntando a lugars

-- Paso 3: Eliminar FK de lugars
ALTER TABLE `lugars` DROP FOREIGN KEY IF EXISTS `lugars_departamento_id_foreign`;

-- Paso 4: Eliminar tabla lugars
DROP TABLE IF EXISTS `lugars`;
```

#### 2.2 Unificar `usuarios` → `users`

```sql
-- ========================================
-- FASE 2.2: UNIFICAR USUARIOS → USERS
-- ========================================
-- Incidencias #11, #32, #36

-- Paso 1: Migrar datos de usuarios a users (solo los que no existen)
-- Usamos email como identificador único
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `dni`, `telefono`, `password`, `role`, `estado`, `created_at`, `updated_at`)
SELECT
    u.`id`,
    u.`nombre_completo` as `name`,
    u.`email`,
    u.`dni`,
    u.`telefono`,
    u.`password`,
    COALESCE(u.`rol`, 'Cliente') as `role`,
    COALESCE(u.`estado`, 'activo') as `estado`,
    u.`created_at`,
    u.`updated_at`
FROM `usuarios` u
WHERE NOT EXISTS (
    SELECT 1 FROM `users` WHERE `users`.`email` = u.`email`
);

-- Paso 2: Actualizar todas las FKs que apuntan a usuarios.id para que apunten a users.id

-- 2.2.1: comentario_conductores
ALTER TABLE `comentario_conductores`
    DROP FOREIGN KEY IF EXISTS `comentario_conductores_usuario_id_foreign`;

ALTER TABLE `comentario_conductores`
    CHANGE COLUMN `usuario_id` `usuario_id` BIGINT(20) UNSIGNED DEFAULT NULL;

-- 2.2.2: registro_rentas
ALTER TABLE `registro_rentas`
    DROP FOREIGN KEY IF EXISTS `registro_rentas_usuario_id_foreign`;

-- 2.2.3: canjes_realizados
ALTER TABLE `canjes_realizados`
    DROP FOREIGN KEY IF EXISTS `canjes_realizados_usuario_id_foreign`;

-- 2.2.4: registrar_puntos
ALTER TABLE `registrar_puntos`
    DROP FOREIGN KEY IF EXISTS `registrar_puntos_usuario_id_foreign`;

-- Paso 3: Re-crear FKs apuntando a users.id

-- Comentario conductores
ALTER TABLE `comentario_conductores`
    ADD CONSTRAINT `comentario_conductores_usuario_id_foreign`
    FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Registro rentas
ALTER TABLE `registro_rentas`
    ADD CONSTRAINT `registro_rentas_usuario_id_foreign`
    FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Canjes realizados
ALTER TABLE `canjes_realizados`
    ADD CONSTRAINT `canjes_realizados_usuario_id_foreign`
    FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Registrar puntos
ALTER TABLE `registrar_puntos`
    ADD CONSTRAINT `registrar_puntos_usuario_id_foreign`
    FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Paso 4: Eliminar tabla usuarios
DROP TABLE IF EXISTS `usuarios`;
```

#### 2.3 Renombrar `calificacions` → `calificaciones`

```sql
-- ========================================
-- FASE 2.3: RENOMBRAR CALIFICACIONS → CALIFICACIONES
-- ========================================
-- Incidencia #34

-- Paso 1: Renombrar tabla
RENAME TABLE `calificacions` TO `calificaciones`;

-- Paso 2: Las FKs se mantienen automáticamente al renombrar
-- Verificar que las FKs existan
ALTER TABLE `calificaciones`
    ADD CONSTRAINT IF NOT EXISTS `calificaciones_reserva_id_foreign`
    FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`) ON DELETE CASCADE;

ALTER TABLE `calificaciones`
    ADD CONSTRAINT IF NOT EXISTS `calificaciones_usuario_id_foreign`
    FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
```

---

### FASE 3: Agregar FKs Faltantes

#### 3.1 Agregar FK `buses.empresa_id` → `empresa_buses`

```sql
-- ========================================
-- FASE 3: AGREGAR FKs FALTANTES
-- ========================================
-- Incidencias #10, #18

-- Paso 1: Agregar columna empresa_id si no existe
ALTER TABLE `buses`
    ADD COLUMN IF NOT EXISTS `empresa_id` BIGINT(20) UNSIGNED DEFAULT NULL
    AFTER `tipo_servicio_id`;

-- Paso 2: Agregar constraint FK
ALTER TABLE `buses`
    ADD CONSTRAINT IF NOT EXISTS `buses_empresa_id_foreign`
    FOREIGN KEY (`empresa_id`) REFERENCES `empresa_buses` (`id`)
    ON DELETE SET NULL;
```

---

### FASE 4: Script de Verificación Post-Migración

```sql
-- ========================================
-- FASE 4: VERIFICACIÓN POST-MIGRACIÓN
-- ========================================

-- 1. Verificar que plain_password no exista
SELECT COUNT(*) AS debe_ser_0
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'bustrak_db'
  AND TABLE_NAME = 'users'
  AND COLUMN_NAME = 'plain_password';

-- 2. Verificar que lugars no exista
SELECT COUNT(*) AS debe_ser_0
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'bustrak_db'
  AND TABLE_NAME = 'lugars';

-- 3. Verificar que usuarios no exista
SELECT COUNT(*) AS debe_ser_0
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'bustrak_db'
  AND TABLE_NAME = 'usuarios';

-- 4. Verificar que calificaciones exista (con 'e')
SELECT COUNT(*) AS debe_ser_1
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'bustrak_db'
  AND TABLE_NAME = 'calificaciones';

-- 5. Verificar FK buses -> empresa_buses
SELECT COUNT(*) AS debe_ser_1
FROM information_schema.TABLE_CONSTRAINTS
WHERE TABLE_SCHEMA = 'bustrak_db'
  AND CONSTRAINT_NAME = 'buses_empresa_id_foreign';

-- 6. Contar registros en cada tabla principal
SELECT 'users' AS tabla, COUNT(*) AS registros FROM `users`
UNION ALL
SELECT 'empresa_buses', COUNT(*) FROM `empresa_buses`
UNION ALL
SELECT 'buses', COUNT(*) FROM `buses`
UNION ALL
SELECT 'lugares', COUNT(*) FROM `lugares`
UNION ALL
SELECT 'calificaciones', COUNT(*) FROM `calificaciones`;
```

---

## PARTE 2 - MODIFICACIONES DE CÓDIGO

### Orden de Aplicación:
1. Modelos
2. Controladores
3. Vistas (si aplica)

---

### ARCHIVO 1: `/workspace/app/Models/User.php`

**Cambio:** Eliminar referencias a `plain_password` del modelo

**CÓDIGO ACTUAL:**
```php
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

**CÓDIGO NUEVO:**
```php
protected $fillable = [
    'name',
    'nombre',
    'apellido1',
    'apellido2',
    'fecha_nacimiento',
    'pais',
    'tipo_doc',
    'email',
    'dni',
    'telefono',
    'password',
    'role',
    'estado',
];
```

---

### ARCHIVO 2: `/workspace/app/Http/Controllers/AuthController.php`

**Cambio:** Eliminar todas las referencias a `plain_password`

**SECCIÓN 1 - Líneas 54-58:**

**CÓDIGO ACTUAL:**
```php
//  Guardar plain_password si no existe
if (!$user->plain_password) {
    $user->plain_password = $request->password;
    $user->save();
}
```

**CÓDIGO NUEVO:**
```php
//  Usuario autenticado correctamente
//  (plain_password eliminado por seguridad)
```

**SECCIÓN 2 - Línea 104:**

**CÓDIGO ACTUAL:**
```php
$user = User::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'password' => Hash::make($validated['password']),
    'plain_password' => $validated['password'],
    'role' => 'Cliente',
    'estado' => 'activo',
]);
```

**CÓDIGO NUEVO:**
```php
$user = User::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'password' => Hash::make($validated['password']),
    'role' => 'Cliente',
    'estado' => 'activo',
]);
```

**SECCIÓN 3 - Líneas 166-169:**

**CÓDIGO ACTUAL:**
```php
$user->forceFill([
    'password' => Hash::make($password),
    'plain_password' => $password
])->setRememberToken(Str::random(60));
```

**CÓDIGO NUEVO:**
```php
$user->forceFill([
    'password' => Hash::make($password)
])->setRememberToken(Str::random(60));
```

**SECCIÓN 4 - Líneas 220-222:**

**CÓDIGO ACTUAL:**
```php
$user->password = Hash::make($request->password);
$user->plain_password = $request->password;
$user->save();
```

**CÓDIGO NUEVO:**
```php
$user->password = Hash::make($request->password);
$user->save();
```

**SECCIÓN 5 - Líneas 247-249:**

**CÓDIGO ACTUAL:**
```php
$user->password = Hash::make($request->password_nuevo);
$user->plain_password = $request->password_nuevo;
$user->save();
```

**CÓDIGO NUEVO:**
```php
$user->password = Hash::make($request->password_nuevo);
$user->save();
```

---

### ARCHIVO 3: `/workspace/app/Http/Controllers/Auth/ForgotPasswordController.php`

**Cambio:** Eliminar referencia a `plain_password`

**SECCIÓN - Líneas 41-47:**

**CÓDIGO ACTUAL:**
```php
return back()->with([
    'user_data' => [
        'name' => $user->nombre_completo ?? $user->name,
        'email' => $user->email,
        'password' => $user->plain_password ?? 'No disponible',
    ],
]);
```

**CÓDIGO NUEVO:**
```php
return back()->with([
    'user_data' => [
        'name' => $user->nombre_completo ?? $user->name,
        'email' => $user->email,
        'password' => 'No disponible (seguridad)',
        'mensaje' => 'Por razones de seguridad, las contraseñas no se muestran. Use la opción de restablecimiento.',
    ],
]);
```

---

### ARCHIVO 4: `/workspace/app/Models/Usuario.php`

**Cambio:** Marcar modelo como DEPRECATED (la tabla será eliminada)

**CÓDIGO ACTUAL:** (todo el archivo)

**CÓDIGO NUEVO:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @deprecated Esta tabla fue unificada con 'users'.
 * Este modelo se mantiene solo para compatibilidad temporal.
 * Usar App\Models\User en su lugar.
 */
class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre_completo',
        'dni',
        'email',
        'telefono',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
```

---

### ARCHIVO 5: `/workspace/app/Http/Controllers/RegistroUsuarioController.php`

**Cambio:** Actualizar para usar solo tabla `users`

**SECCIÓN 1 - Líneas 1-10:**

**CÓDIGO ACTUAL:**
```php
use App\Models\Usuario;
use App\Models\User;
```

**CÓDIGO NUEVO:**
```php
use App\Models\User;
// Usuario:: class eliminada - tabla unificada con users
```

**SECCIÓN 2 - Líneas 13-28:**

**CÓDIGO ACTUAL:**
```php
public function index(Request $request)
{
    $search = $request->input('search');

    $usuarios = Usuario::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_completo', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        })
        ->paginate(10);

    return view('usuarios.index', compact('usuarios'));
}
```

**CÓDIGO NUEVO:**
```php
public function index(Request $request)
{
    $search = $request->input('search');

    $usuarios = User::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        })
        ->paginate(10);

    return view('usuarios.index', compact('usuarios'));
}
```

**SECCIÓN 3 - Líneas 36-90:**

**CÓDIGO ACTUAL:**
```php
public function store(Request $request)
{
    $request->validate([
        'nombre_completo' => 'required|regex:/^[\pL\s\-]+$/u|max:60',
        'dni' => 'required|numeric|digits:13|unique:usuarios,dni',
        'email' => 'required|email|unique:usuarios,email|unique:users,email',
        'telefono' => 'required|numeric|digits:8',
        'password' => 'required|string|min:8|confirmed',
    ], [
        // ... mensajes personalizados
    ]);

    // Guardar en tabla usuarios
    $usuario = new Usuario();
    $usuario->nombre_completo = $request->nombre_completo;
    $usuario->dni = $request->dni;
    $usuario->email = $request->email;
    $usuario->telefono = $request->telefono;
    $usuario->password = Hash::make($request->password);
    $usuario->save();

    // Crear también en tabla users para login
    User::create([
        'name' => $usuario->nombre_completo,
        'email' => $usuario->email,
        'password' => Hash::make($request->password),
        'dni' => $usuario->dni,
        'telefono' => $usuario->telefono,
        'role' => 'cliente',
        'estado' => 'activo',
    ]);

    return redirect()
        ->back()
        ->with('success', 'Usuario registrado correctamente. Ya puedes iniciar sesión.');
}
```

**CÓDIGO NUEVO:**
```php
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
        'dni' => 'required|numeric|digits:13|unique:users,dni',
        'email' => 'required|email|unique:users,email',
        'telefono' => 'required|numeric|digits:8',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:Empleado,Administrador,Cliente',
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.regex' => 'El nombre solo puede contener letras y espacios.',
        'dni.required' => 'El DNI es obligatorio.',
        'dni.numeric' => 'El DNI debe contener solo números.',
        'dni.digits' => 'El DNI debe contener exactamente 13 dígitos.',
        'dni.unique' => 'El DNI ya está registrado.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico debe ser válido.',
        'email.unique' => 'El correo electrónico ya está registrado.',
        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.numeric' => 'El teléfono debe contener solo números.',
        'telefono.digits' => 'El teléfono debe contener exactamente 8 dígitos.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'La confirmación de la contraseña no coincide.',
    ]);

    // Guardar únicamente en tabla users
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'dni' => $request->dni,
        'telefono' => $request->telefono,
        'role' => $request->role ?? 'Cliente',
        'estado' => 'activo',
    ]);

    return redirect()
        ->back()
        ->with('success', 'Usuario registrado correctamente. Ya puedes iniciar sesión.');
}
```

**SECCIÓN 4 - Líneas 92-96:**

**CÓDIGO ACTUAL:**
```php
public function show(string $id)
{
    $usuario = Usuario::findOrFail($id);
    return view('usuarios.show', compact('usuario'));
}
```

**CÓDIGO NUEVO:**
```php
public function show(string $id)
{
    $usuario = User::findOrFail($id);
    return view('usuarios.show', compact('usuario'));
}
```

**SECCIÓN 5 - Líneas 98-138:**

**CÓDIGO ACTUAL:**
```php
public function consultar(Request $request)
{
    $search = $request->input('search');

    $usuarios = Usuario::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('usuarios.nombre_completo', 'like', "%{$search}%")
                    ->orWhere('usuarios.email', 'like', "%{$search}%")
                    ->orWhere('usuarios.dni', 'like', "%{$search}%");
            });
        })
        ->join('users', 'usuarios.email', '=', 'users.email')
        ->select(
            'usuarios.*',
            'users.role as rol',
            'users.estado'
        );

    // ... resto del código

    return view('usuarios.consultar', compact('usuarios'));
}
```

**CÓDIGO NUEVO:**
```php
public function consultar(Request $request)
{
    $search = $request->input('search');

    $usuarios = User::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        });

    // Filtro por estado
    if ($request->filled('estado')) {
        $usuarios->where('estado', $request->estado);
    }

    // Filtro por fecha de registro
    if ($request->filled('fecha_registro')) {
        $usuarios->whereDate('created_at', $request->fecha_registro);
    }

    $usuarios = $usuarios->paginate(10)->appends($request->all());

    return view('usuarios.consultar', compact('usuarios'));
}
```

**SECCIÓN 6 - Líneas 141-213:**

**CÓDIGO ACTUAL:**
```php
public function edit(string $id)
{
    $usuario = Usuario::findOrFail($id);
    return view('usuarios.Editar', compact('usuario'));
}

public function update(Request $request, $id)
{
    $usuario = Usuario::findOrFail($id);
    $originalEmail = $usuario->email;

    $request->validate([
        'nombre_completo' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
        'dni' => ['required', 'numeric', 'digits:13', Rule::unique('usuarios', 'dni')->ignore($usuario->id)],
        'email' => ['required', 'email', Rule::unique('usuarios', 'email')->ignore($usuario->id)],
        'telefono' => 'required|numeric|digits:8',
        'password' => 'nullable|string|min:8|confirmed',
        'estado' => 'required|in:activo,inactivo',
    ], [
        // ... mensajes
    ]);

    $usuario->nombre_completo = $request->nombre_completo;
    $usuario->dni = $request->dni;
    $usuario->email = $request->email;
    $usuario->telefono = $request->telefono;

    if ($request->filled('password')) {
        $usuario->password = $request->password;
    }

    $usuario->save();

    // Actualizar tabla users
    $user = User::where('email', $originalEmail)->first();
    if ($user) {
        $user->name = $usuario->nombre_completo;
        $user->email = $usuario->email;
        $user->estado = $request->estado;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
    }

    return redirect()->route('usuarios.consultar')
        ->with('success', 'Usuario actualizado exitosamente.');
}
```

**CÓDIGO NUEVO:**
```php
public function edit(string $id)
{
    $usuario = User::findOrFail($id);
    return view('usuarios.Editar', compact('usuario'));
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
        'dni' => ['required', 'numeric', 'digits:13', Rule::unique('users', 'dni')->ignore($user->id)],
        'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        'telefono' => 'required|numeric|digits:8',
        'password' => 'nullable|string|min:8|confirmed',
        'estado' => 'required|in:activo,inactivo',
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.regex' => 'El nombre solo puede contener letras y espacios.',
        'dni.required' => 'El DNI es obligatorio.',
        'dni.numeric' => 'El DNI debe ser numérico.',
        'dni.digits' => 'El DNI debe tener 13 dígitos.',
        'dni.unique' => 'El DNI ya está registrado.',
        'email.required' => 'El email es obligatorio.',
        'email.email' => 'El email debe ser válido.',
        'email.unique' => 'El email ya está registrado.',
        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.numeric' => 'El teléfono debe ser numérico.',
        'telefono.digits' => 'El teléfono debe tener 8 dígitos.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'La confirmación no coincide.',
        'estado.required' => 'El estado es obligatorio.',
        'estado.in' => 'El estado seleccionado no es válido.',
    ]);

    $user->name = $request->name;
    $user->dni = $request->dni;
    $user->email = $request->email;
    $user->telefono = $request->telefono;
    $user->estado = $request->estado;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('usuarios.consultar')
        ->with('success', 'Usuario actualizado exitosamente.');
}
```

---

### ARCHIVO 6: `/workspace/app/Http/Controllers/CalificacionChoferController.php`

**Cambio:** Actualizar referencia de `Usuario` a `User`

**Buscar línea:**
```php
$usuario = \App\Models\Usuario::where('email', $user->email)->first();
```

**Reemplazar con:**
```php
$usuario = User::where('email', $user->email)->first();
```

---

### ARCHIVO 7: `/workspace/app/Http/Controllers/RegistroRentaController.php`

**Cambio:** Actualizar referencia de `Usuario` a `User`

**Buscar línea similar a:**
```php
$cliente = Usuario::firstOrCreate(
```

**Reemplazar con:**
```php
$cliente = User::firstOrCreate(
```

---

## PARTE 3 - PRUEBAS NECESARIAS

### ✅ Pruebas de Base de Datos

```sql
-- 1. Verificar que no existe plain_password
SELECT COLUMN_NAME FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'bustrak_db' AND TABLE_NAME = 'users' AND COLUMN_NAME = 'plain_password';
-- Resultado esperado: 0 filas

-- 2. Verificar que no existe tabla lugars
SHOW TABLES LIKE 'lugars';
-- Resultado esperado: Empty set

-- 3. Verificar que no existe tabla usuarios
SHOW TABLES LIKE 'usuarios';
-- Resultado esperado: Empty set

-- 4. Verificar que existe calificaciones (con 'e')
SHOW TABLES LIKE 'calificaciones';
-- Resultado esperado: 1 fila

-- 5. Verificar FK de buses a empresa_buses
SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
WHERE TABLE_SCHEMA = 'bustrak_db' AND TABLE_NAME = 'buses' AND CONSTRAINT_NAME = 'buses_empresa_id_foreign';
-- Resultado esperado: 1 fila con 'buses_empresa_id_foreign'
```

### ✅ Pruebas Funcionales

1. **Login de usuario**
   - Iniciar sesión con email/password válidos
   - Verificar que redirige según el rol
   - Verificar que NO se guarda plain_password

2. **Registro de usuario**
   - Crear nuevo usuario desde formulario
   - Verificar que solo se guarda en tabla `users`
   - Verificar que puede hacer login

3. **Consulta de usuarios**
   - Listar todos los usuarios
   - Buscar usuario por nombre/email/DNI
   - Verificar que muestra datos correctos

4. **Edición de usuario**
   - Editar usuario existente
   - Cambiar estado activo/inactivo
   - Cambiar contraseña
   - Verificar cambios en BD

5. **Calificaciones**
   - Crear nueva calificación
   - Verificar que se guarda en `calificaciones` (no `calificacions`)
   - Verificar FK a users y reservas

6. **Buses**
   - Crear/editar bus con empresa_id
   - Verificar FK a empresa_buses
   - Verificar que no permite empresa_id inválido

---

## 📊 Resumen para el Equipo

### Incidencias Resueltas:
- ✅ **#37** - Eliminación de `plain_password` (CRÍTICA - Seguridad)
- ✅ **#3, #9, #35** - Unificación `lugars` → `lugares`
- ✅ **#11, #32, #36** - Unificación `usuarios` → `users`
- ✅ **#34** - Renombrado `calificacions` → `calificaciones`
- ✅ **#10, #18** - FK `buses.empresa_id` → `empresa_buses`

### Total: 5 incidencias técnicas resueltas (que agrupan 13 IDs de prueba)

### Pendientes (requieren acción manual/servidor):
- ⚠️ **#2** - Configurar SSL en MySQL
- ⚠️ **#1** - Crear usuarios específicos por rol

---

**Documentación elaborada para implementación en producción**
**Equipo MR. ROBOTS - Julio 2026**