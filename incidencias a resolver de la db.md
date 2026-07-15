# Plan de Resolución de Incidencias Técnicas – Base de Datos BusTrack

**Auditoría realizada:** Junio 2026  
**Equipo evaluado:** MR. ROBOTS  
**Total de pruebas:** 45  
**Incidencias fallidas:** 43  
**Incidencias exitosas:** 2 (pruebas 5 y 6)

El presente documento enumera y clasifica todas las incidencias técnicas detectadas durante la auditoría, con el objetivo de priorizar y guiar su corrección. Se han agrupado por naturaleza para facilitar su resolución.

---

## 1. Incidencias Críticas de Seguridad

| ID Prueba | Descripción | Acción requerida |
|-----------|-------------|------------------|
| 2 | Conexiones sin cifrado SSL (require_secure_transport = OFF) | Activar SSL/TLS forzado en el servidor MySQL/MariaDB. Configurar `require_secure_transport = ON` y proporcionar certificados válidos. |
| 37 | Campo `plain_password` almacena contraseñas en texto plano | **Eliminar inmediatamente** el campo `plain_password` de la tabla `users`. Usar solo hash con bcrypt o Argon2. Nunca almacenar contraseñas en claro. |
| 1 | Único usuario `root` con ALL PRIVILEGES | Crear usuarios específicos por rol (administrador, operador, consulta, etc.) con privilegios mínimos necesarios (principio de menor privilegio). |

---

## 2. Incidencias de Integridad Referencial y Estructura

### 2.1 Tablas duplicadas o redundantes

| ID Prueba | Descripción | Acción requerida |
|-----------|-------------|------------------|
| 3, 9, 35 | Tablas `lugars` (duplicado de `lugares`) y posible duplicación lógica en otras tablas. | Unificar en una sola tabla `lugares`. Migrar datos (`INSERT IGNORE ...`) y eliminar `lugars`. Actualizar todas las referencias en modelos, migraciones y diagrama. |
| 11, 32, 36 | Coexistencia de `users` (Laravel) y `usuarios` (personalizada), con FKs apuntando a ambas. | Unificar en una sola tabla `users`. Migrar datos de `usuarios` a `users` (resolviendo conflictos). Eliminar `usuarios` y actualizar **todas** las FKs que apuntaban a `usuarios.id` para que apunten a `users.id`. |
| 34 | Tabla `calificaciones` (sin "e") vs. `calificaciones` (ortografía correcta). | Renombrar la tabla a `calificaciones` (con "e") y actualizar todos los artefactos (modelos, controladores, vistas, rutas). |

### 2.2 FKs incorrectas, huérfanas o ausentes

| ID Prueba | Descripción | Acción requerida |
|-----------|-------------|------------------|
| 10, 14, 15, 16, 18 | FKs que no existen o no están activas (viajes, lugares, calificaciones, buses, consultas). | Crear o verificar las siguientes restricciones: <br> - `lugares.departamento_id` → `departamentos.id` <br> - `viajes.bus_id` → `buses.id` <br> - `viajes.empleado_id` → `empleados.id` <br> - `calificacions.reserva_id` → `reservas.id` <br> - `buses.empresa_id` → `empresa_buses.id` <br> - `consultas.user_id` → `users.id` (permitiendo NULL para anónimos) |
| 13, 18 | Permitir inserciones con `usuario_id` inexistente (sin FK) o con FK desactivada. | Activar las restricciones y verificar que rechacen valores inválidos. |
| 33 | Tablas `calificaciones`, `canjes_realizados` sin FK explícita a `reservas` o `users`. | Agregar `calificaciones.reserva_id` → `reservas.id` y reconectar `usuario_id` a `users.id`. |
| 7, 8 | Tabla `asientos` contiene campos de abordaje (reserva_id, fecha_hora_abordaje) en lugar de campos de asiento. | Redefinir `asientos` con: `id`, `viaje_id` (FK), `numero_asiento`, `fila`, `columna`, `estado` ENUM('disponible','ocupado','reservado'). Eliminar columnas de abordaje. |
| 31 | El diccionario documenta `asientos` erróneamente como si fuera `abordajes`. | Corregir el diccionario de datos con la estructura correcta de `asientos`. |

### 2.3 Comportamiento ON DELETE no definido

| ID Prueba | Descripción | Acción requerida |
|-----------|-------------|------------------|
| 20 | Eliminar empresa con buses asignados no tiene regla ON DELETE clara. | Definir explícitamente `ON DELETE RESTRICT` o `SET NULL` según la lógica de negocio. Documentar y aplicar la regla en la FK. |

---

## 3. Incidencias de Normalización y Calidad de Datos

| ID Prueba | Descripción | Acción requerida |
|-----------|-------------|------------------|
| 4 | No existen datos de prueba (viajes, reservas, usuarios, pagos) | Crear un entorno de pruebas con datos sintéticos que permitan validar el sistema sin afectar producción. |
| 38 | Campos `estado` usan VARCHAR o ENUM sin valores permitidos documentados. | Definir ENUM o CHECK constraints con los valores posibles (ej. `pendiente`, `confirmada`, `cancelada`, `completada`). Documentar las transiciones de estado. |
| 42, 43 | Relaciones N:M sin tabla pivote documentada en el diccionario (ej. viajes↔conductores). | Documentar todas las tablas pivote con sus campos (FKs, atributos propios) y actualizar el diagrama. |
| 40 | Longitudes de campos genéricas (`VARCHAR(255)`) en email, teléfono, DNI. | Ajustar a longitudes reales: email `VARCHAR(254)`, teléfono `VARCHAR(20)`, DNI `VARCHAR(20)`. Agregar CHECK para formato cuando sea posible. |
| 39 | Inconsistencia entre `TIMESTAMP` y `DATETIME` en campos de auditoría. | Estandarizar a `TIMESTAMP WITH TIME ZONE` (o `DATETIME` con zona horaria documentada). Definir zona horaria UTC para todos los servidores. |

---

## 4. Incidencias de Arquitectura y Configuración

| ID Prueba | Descripción | Acción requerida |
|-----------|-------------|------------------|
| 5 (exitosa) | Integridad referencial general funcionando (51 relaciones) | Mantener y no romper. |
| 6 (exitosa) | Proyección de crecimiento correcta (2.48 MB → <55 MB en 5 años) | Aprovisionar 20 GB SSD y 8 vCPU según recomendación. |
| 24 | Tablas de infraestructura Laravel (`cache`, `sessions`, `jobs`, etc.) con FKs hacia tablas de negocio (incorrecto). | Verificar que no existan FKs cruzadas. Si existen, eliminarlas y mantenerlas independientes. |
| 26 | `home_configs` sin FKs y accesible sin JOIN (correcto). | Asegurar que se mantenga así. |

---

## 5. Incidencias de Documentación y Nomenclatura

| ID Prueba | Descripción | Acción requerida |
|-----------|-------------|------------------|
| 31, 44 | Diccionario de datos con descripciones vacías o repetidas, mezcla de idiomas (inglés/español) sin estándar. | Completar todas las descripciones funcionales. Definir un estándar de nomenclatura (ej. campos de auditoría en inglés, negocio en español) y documentarlo en el manual técnico. |
| 41 | Diagrama ER sin cardinalidades explícitas (1:1, 1:N, N:M). | Revisar y anotar todas las relaciones con notación crow's foot o UML, especialmente en `users↔reservas`, `reservas↔pagos`, `viajes↔conductores`. |
| 45 | Índice del diccionario no agrupado por módulos funcionales. | Reorganizar el diccionario por módulos (Usuarios, Viajes, Reservas, Pagos, Fidelización, etc.) con introducción por módulo. |

---

## 6. Incidencias de Flujos Funcionales (Regresión)

Las siguientes pruebas fallidas corresponden a flujos completos que no se pudieron ejecutar correctamente debido a las incidencias estructurales anteriores. Su resolución depende de corregir primero los puntos 1 a 5.

| ID Prueba | Descripción | Acción requerida |
|-----------|-------------|------------------|
| 27 | Flujo completo: usuario → viaje → asiento → reserva → pago | Una vez corregidas las FKs y la tabla `asientos`, probar nuevamente el flujo. |
| 28 | Registro de abordaje para reserva existente | Verificar FKs y estados. |
| 29 | Registro de puntos y canje de beneficios | Unificar tabla de usuarios y FKs. |
| 30 | Registro de documentos de bus e historial | Asegurar FKs correctas. |

---

## Plan de Acción Priorizado

1. **Seguridad (inmediato)**
   - Eliminar `plain_password`.
   - Configurar SSL y usuario root.
2. **Unificación de tablas duplicadas**
   - Unificar `users`/`usuarios`.
   - Unificar `lugares`/`lugars`.
   - Renombrar `calificaciones` a `calificaciones`.
3. **Corrección de FKs y estructura**
   - Crear/activar todas las FKs faltantes.
   - Redefinir tabla `asientos`.
4. **Normalización y tipos de datos**
   - Ajustar longitudes, estandarizar fechas y estados.
5. **Documentación**
   - Actualizar diccionario, diagrama ER, manual técnico.
6. **Pruebas funcionales**
   - Ejecutar nuevamente todas las pruebas (especialmente las de flujo) para validar la corrección.

---

**Elaborado por:** Equipo Evaluador  
**Fecha:** Julio 2026