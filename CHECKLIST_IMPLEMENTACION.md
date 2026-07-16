## 📋 CHECKLIST DE IMPLEMENTACIÓN BUSTRAK DB

---

### FASE 1: PREPARACIÓN ✅

- [ ] Descargar archivos SQL generados
  - [ ] `SQL_PHPMYADMIN_PASO_A_PASO.sql`
  - [ ] `SQL_CORRECTIONS_FINAL.sql`
  - [ ] `SQL_CORRECCIONES_DE_ERRORES.sql`

- [ ] Revisar documentación
  - [ ] `RESUMEN_CORRECCIONES_COMPLETO.md`
  - [ ] `README_CORRECCIONES.md`

- [ ] Hacer backup de la BD actual
  ```sql
  CREATE TABLE `usuarios_backup_final` AS SELECT * FROM `usuarios`;
  CREATE TABLE `asientos_backup_final` AS SELECT * FROM `asientos`;
  ```

---

### FASE 2: EJECUTAR CONSULTAS SQL EN PHP ADMIN

**Archivo a usar:** `SQL_PHPMYADMIN_PASO_A_PASO.sql`

#### Bloque 1: Verificación Inicial
- [ ] Copiar CONSULTA 1: Verificar estado actual
- [ ] Ejecutar y verificar: Deben existir `asientos`, `historial_abordajes`, `calificaciones`

#### Bloque 2: Renombrar Calificaciones (DE: calificacions → A: calificaciones)
- [ ] Copiar CONSULTA 2: Renombrar tabla
- [ ] Ejecutar
- [ ] Verificar CONSULTA 3: Nueva tabla existe

#### Bloque 3: Migrar Usuarios (DE: usuarios → A: users)
- [ ] Copiar CONSULTA 4: Migrar usuarios a users
- [ ] Ejecutar
- [ ] Copiar CONSULTA 5: Crear tabla de mapeo
- [ ] Ejecutar
- [ ] Copiar CONSULTA 6: Llenar mapeo
- [ ] Ejecutar
- [ ] Copiar CONSULTA 7: Verificar mapeo
- [ ] Ejecutar (resultado debe mostrar N registros)

#### Bloque 4: Actualizar Foreign Keys
- [ ] Copiar CONSULTA 8: Actualizar comentario_conductores
- [ ] Ejecutar
- [ ] Copiar CONSULTA 9: Actualizar registro_rentas
- [ ] Ejecutar

#### Bloque 5: Recrear FKs hacia users
- [ ] Copiar CONSULTA 10: Verificar FKs antiguas
- [ ] Ejecutar (ver si hay resultados)
- [ ] Si hay resultados, copiar CONSULTA 11: Eliminar FKs antiguas
- [ ] Ejecutar
- [ ] Copiar CONSULTA 12: Crear nuevas FKs
- [ ] Ejecutar (sin errores)

#### Bloque 6: Conteo Final
- [ ] Copiar CONSULTA 13: Conteo final
- [ ] Ejecutar (verificar números están correctos)

#### Bloque 7: PUNTO DE NO RETORNO - Eliminar tabla usuarios
- [ ] Copiar CONSULTA 14: Eliminar tabla usuarios
- [ ] ⚠️ CONFIRMAR antes de ejecutar
- [ ] Ejecutar
- [ ] Copiar CONSULTA 15: Eliminar mapeo temporal
- [ ] Ejecutar

#### Bloque 8: Verificación Final
- [ ] Copiar CONSULTA 16: Describir asientos y historial_abordajes
- [ ] Ejecutar (verificar estructura)
- [ ] Copiar CONSULTA 17: Validar integridad
- [ ] Ejecutar (todos deben retornar 0)
- [ ] Copiar CONSULTA 18: Verificar FKs finales
- [ ] Ejecutar (verificar todas apunten a `users`, no a `usuarios`)

---

### FASE 3: SINCRONIZAR CÓDIGO LARAVEL

#### Verificación de Modelos
- [ ] Existe: `app/Models/HistorialAbordaje.php`
- [ ] Existe: `app/Models/Calificacion.php`
- [ ] Actualizado: `app/Models/Asiento.php` (fillable correcto)
- [ ] Actualizado: `app/Models/Bus.php` (empresa_id agregada)
- [ ] Actualizado: `app/Models/RegistroRenta.php` (User::class)
- [ ] Actualizado: `app/Models/Lugar.php` (protected $table = 'lugares')

#### Limpiar Caché de Laravel
```bash
cd c:\Users\Administrador\Desktop\UNIVERSITY\bustrak

php artisan cache:clear
php artisan config:clear
composer dump-autoload
```

- [ ] Comando ejecutado sin errores

---

### FASE 4: PROBAR EN EL NAVEGADOR

#### Rutas a verificar
- [ ] GET `/asientos` → Debe retornar lista de asientos (sin reserva_id)
- [ ] GET `/calificaciones` → Debe retornar calificaciones
- [ ] GET `/comentarios` → Debe funcionar sin errores de Usuario
- [ ] GET `/registro-rentas` → Debe mostrar rentas con usuario
- [ ] POST `/asientos` → Crear asiento nuevo con campos: fila, columna, tipo, estado

#### Búsqueda de Errores en Logs
```bash
tail -f storage/logs/laravel.log
```
- [ ] No hay errores de clase "Usuario" no encontrada
- [ ] No hay errores de columna "reserva_id" no existe
- [ ] No hay errores de método "usuarios()" no existe

---

### FASE 5: VALIDACIÓN FINAL

#### En PHP Admin
```sql
-- Ejecutar estas queries de validación final:

-- ✅ Verificar conteo final
SELECT 
    'asientos' AS tabla, COUNT(*) AS cantidad FROM asientos
UNION ALL
SELECT 'historial_abordajes', COUNT(*) FROM historial_abordajes
UNION ALL
SELECT 'users', COUNT(*) FROM users
UNION ALL
SELECT 'calificaciones', COUNT(*) FROM calificaciones;

-- ✅ Verificar no hay datos huérfanos
SELECT COUNT(*) AS huerfanos FROM historial_abordajes
WHERE asiento_id NOT IN (SELECT id FROM asientos);
-- Debe retornar: 0

-- ✅ Verificar FKs correctas
SELECT REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_NAME IN ('comentario_conductores', 'registro_rentas')
AND TABLE_SCHEMA = 'bustrak_db';
-- Debe retornar: users, users (no usuarios)
```

- [ ] Todos los conteos correctos
- [ ] Sin datos huérfanos
- [ ] FKs apuntan a `users`, no a `usuarios`

#### En Laravel
```bash
# Ejecutar artisan tinker para verificar relaciones
php artisan tinker

// Probar:
$asiento = \App\Models\Asiento::first();
$asiento->historialAbordajes;  // Debe funcionar
$asiento->viaje;               // Debe funcionar

$renta = \App\Models\RegistroRenta::first();
$renta->usuario;               // Debe funcionar (singular)

$cal = \App\Models\Calificacion::first();
$cal->usuario;                 // Debe funcionar
```

- [ ] Todas las relaciones funcionan sin errores
- [ ] No hay "Method not found" errors

---

### ✅ CHECKLISTA FINAL - VALIDACIÓN TOTAL

#### Base de Datos
- [ ] Tabla `usuarios` fue eliminada o está vacía
- [ ] Tabla `users` contiene todos los usuarios
- [ ] Tabla `asientos` tiene estructura: fila, columna, tipo, estado
- [ ] Tabla `historial_abordajes` contiene datos de reservas
- [ ] Tabla `calificaciones` existe (no `calificacions`)
- [ ] Tabla `lugars` fue eliminada
- [ ] 0 datos perdidos

#### Modelos Laravel
- [ ] `Asiento::class` tiene relación `historialAbordajes`
- [ ] `HistorialAbordaje::class` existe y funciona
- [ ] `Calificacion::class` existe y funciona
- [ ] `Bus::class` tiene relación `empresa`
- [ ] `RegistroRenta::class` tiene método `usuario()` (singular)
- [ ] `User::class` es la única tabla de usuarios

#### Funcionalidad
- [ ] Crear asiento nuevo - ✅
- [ ] Asignar reserva a asiento (via historial) - ✅
- [ ] Ver calificaciones - ✅
- [ ] Ver comentarios conductores - ✅
- [ ] Ver registro de rentas - ✅
- [ ] Ningún error 500 - ✅

---

## 🎯 ESTADO FINAL

Cuando TODAS las casillas estén marcadas:

✅ **BASE DE DATOS:** 100% funcional y consistente
✅ **CÓDIGO LARAVEL:** 100% sincronizado
✅ **DATOS:** 100% íntegros (0 perdidos)
✅ **PRUEBAS:** 100% exitosas

**Status:** READY FOR PRODUCTION ✅

---

## 🆘 ROLLBACK DE EMERGENCIA

Si algo sale mal y necesitas revertir:

```sql
-- Restaurar desde backups
CREATE TABLE `usuarios` LIKE `usuarios_backup_final`;
INSERT INTO `usuarios` SELECT * FROM `usuarios_backup_final`;

CREATE TABLE `asientos` LIKE `asientos_backup_final`;
INSERT INTO `asientos` SELECT * FROM `asientos_backup_final`;
```

Luego restaurar modelos Laravel desde version anterior.

---

**Fecha de inicio:** 2026-07-16  
**Fecha de finalización:** [Completar]  
**Responsable:** [Nombre]  
**Validado por:** [QA/Tests]

Imprimir y mantener como referencia durante la implementación.
