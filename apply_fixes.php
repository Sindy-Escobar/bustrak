<?php
// Temporary script to execute database changes and verify the state

$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


echo "========================================\n";
echo "BUSTRAK DB - FINAL CORRECTIONS SCRIPT\n";
echo "==========================================\n\n";

try {
    // 1. Get current table list
    echo "1. VERIFICANDO TABLAS EXISTENTES:\n";
    $tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA='bustrak_db' ORDER BY TABLE_NAME");
    foreach ($tables as $t) {
        if (in_array($t->TABLE_NAME, ['usuarios', 'users', 'calificaciones', 'calificacions', 'mapeo_usuarios', 'mapeo_usuarios_temporal', 'asientos', 'historial_abordajes'])) {
            echo "   ✓ " . $t->TABLE_NAME . "\n";
        }
    }

    // 2. Count users
    echo "\n2. CONTEO DE USUARIOS:\n";
    $count_usuarios = DB::table('usuarios')->count();
    $count_users = DB::table('users')->count();
    echo "   usuarios: $count_usuarios\n";
    echo "   users: $count_users\n";

    // 3. Check if calificacions exists
    echo "\n3. VERIFICANDO TABLA CALIFICACIONES:\n";
    $calificacions_exists = Schema::hasTable('calificacions');
    $calificaciones_exists = Schema::hasTable('calificaciones');
    echo "   calificacions exists: " . ($calificacions_exists ? 'YES' : 'NO') . "\n";
    echo "   calificaciones exists: " . ($calificaciones_exists ? 'YES' : 'NO') . "\n";

    // 4. Check asientos and historial_abordajes
    echo "\n4. VERIFICANDO TABLAS CORREGIDAS:\n";
    echo "   asientos count: " . DB::table('asientos')->count() . "\n";
    echo "   historial_abordajes count: " . DB::table('historial_abordajes')->count() . "\n";

    // 5. If everything is ready, apply remaining corrections
    echo "\n5. APLICANDO CORRECCIONES FALTANTES:\n";

    // Check and rename calificacions if exists
    if (Schema::hasTable('calificacions') && !Schema::hasTable('calificaciones')) {
        echo "   Renombrando calificacions → calificaciones...\n";
        DB::statement('RENAME TABLE `calificacions` TO `calificaciones`');
        echo "   ✓ Tabla renombrada\n";
    } elseif (Schema::hasTable('calificaciones')) {
        echo "   ✓ calificaciones ya existe\n";
    }

    // Migrate usuarios to users
    echo "\n   Migrando usuarios → users...\n";
    $usuarios_no_migrados = DB::table('usuarios')
        ->whereNotIn('email', DB::table('users')->pluck('email'))
        ->get();
    
    if (count($usuarios_no_migrados) > 0) {
        foreach ($usuarios_no_migrados as $u) {
            DB::table('users')->insertOrIgnore([
                'name' => $u->nombre_completo ?? $u->email,
                'email' => $u->email,
                'password' => $u->password ?? '',
                'role' => 'Cliente',
                'estado' => 'activo',
                'created_at' => $u->created_at ?? now(),
                'updated_at' => $u->updated_at ?? now(),
            ]);
        }
        echo "   ✓ Migrados " . count($usuarios_no_migrados) . " usuarios\n";
    } else {
        echo "   ✓ Sin usuarios nuevos para migrar\n";
    }

    // Create mapping table
    echo "\n   Creando tabla de mapeo...\n";
    if (!Schema::hasTable('mapeo_usuarios_temporal')) {
        DB::statement('
            CREATE TABLE IF NOT EXISTS `mapeo_usuarios_temporal` (
              `usuario_id_antiguo` bigint unsigned NOT NULL,
              `user_id_nuevo` bigint unsigned NOT NULL,
              `email` varchar(255) NOT NULL,
              PRIMARY KEY (`usuario_id_antiguo`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');
        echo "   ✓ Tabla de mapeo creada\n";
    }

    // Fill mapping
    DB::statement('
        INSERT IGNORE INTO mapeo_usuarios_temporal (usuario_id_antiguo, user_id_nuevo, email)
        SELECT u_old.id, u_new.id, u_old.email
        FROM usuarios u_old
        INNER JOIN users u_new ON u_old.email = u_new.email
    ');
    $mapeo_count = DB::table('mapeo_usuarios_temporal')->count();
    echo "   ✓ Mapeo registrado: $mapeo_count registros\n";

    // Update FKs
    echo "\n   Actualizando Foreign Keys...\n";
    DB::statement('
        UPDATE comentario_conductores cc
        JOIN mapeo_usuarios_temporal mt ON cc.usuario_id = mt.usuario_id_antiguo
        SET cc.usuario_id = mt.user_id_nuevo
    ');
    echo "   ✓ comentario_conductores actualizado\n";

    DB::statement('
        UPDATE registro_rentas rr
        JOIN mapeo_usuarios_temporal mt ON rr.usuario_id = mt.usuario_id_antiguo
        SET rr.usuario_id = mt.user_id_nuevo
    ');
    echo "   ✓ registro_rentas actualizado\n";

    // Verify no broken FKs
    echo "\n6. VALIDANDO INTEGRIDAD:\n";
    $broken_abordajes = DB::table('historial_abordajes')
        ->whereNotIn('asiento_id', DB::table('asientos')->pluck('id'))
        ->count();
    echo "   historial_abordajes huérfanos: $broken_abordajes\n";

    $broken_reservas = DB::table('historial_abordajes')
        ->whereNotNull('reserva_id')
        ->whereNotIn('reserva_id', DB::table('reservas')->pluck('id'))
        ->count();
    echo "   historial_abordajes sin reserva: $broken_reservas\n";

    // Final cleanup
    echo "\n7. LIMPIEZA (BORRAR TABLAS TEMPORALES):\n";
    if (Schema::hasTable('mapeo_usuarios_temporal')) {
        DB::statement('DROP TABLE IF EXISTS `mapeo_usuarios_temporal`');
        echo "   ✓ mapeo_usuarios_temporal eliminada\n";
    }

    if (Schema::hasTable('mapeo_usuarios')) {
        DB::statement('DROP TABLE IF EXISTS `mapeo_usuarios`');
        echo "   ✓ mapeo_usuarios eliminada\n";
    }

    // Option: Drop usuarios table if all data migrated
    echo "\n8. OPCIÓN: ELIMINAR TABLA USUARIOS ANTIGUA:\n";
    $usuarios_count = DB::table('usuarios')->count();
    echo "   Registros en usuarios: $usuarios_count\n";
    
    if ($usuarios_count > 0) {
        echo "   ⚠  Aún hay datos. Mantener tabla por seguridad.\n";
        echo "   Cuando todo esté funcionando, ejecutar: DROP TABLE usuarios;\n";
    } else {
        echo "   ✓ Tabla usuarios vacía - puede eliminarse\n";
        // Uncomment to delete: DB::statement('DROP TABLE IF EXISTS `usuarios`');
    }

    echo "\n==========================================\n";
    echo "✅ CORRECCIONES COMPLETADAS\n";
    echo "==========================================\n\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
