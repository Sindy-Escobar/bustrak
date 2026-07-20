# Guía Definitiva de Instalación y Ejecución - Bustrak 🚌

Esta guía detalla los pasos exactos para instalar las herramientas necesarias y poner en marcha el sistema en cualquier computadora con **Windows**.

---

## 🛠️ Paso 1: Descargar e Instalar las Herramientas Básicas

Debes descargar e instalar estos 3 programas con su configuración por defecto:

1. **XAMPP (con PHP 8.2 o Superior):**
   * Descarga la última versión de XAMPP para Windows.
   * [Link de Descarga de XAMPP](https://www.apachefriends.org/es/index.html)
2. **Composer (Composer-Setup.exe):**
   * Es el gestor de dependencias de PHP. Durante la instalación, detectará automáticamente la ruta de tu PHP en XAMPP (`C:\xampp\php\php.exe`). Asegúrate de marcar la casilla para agregarlo al PATH.
   * [Link de Descarga de Composer](https://getcomposer.org/Composer-Setup.exe)
3. **Node.js (Versión LTS, recomendada v20):**
   * Necesario para ejecutar el compilador del frontend (Vite y React).
   * [Link de Descarga de Node.js](https://nodejs.org/)

---

## ⚙️ Paso 2: Activar Extensiones de PHP en XAMPP

Por defecto, XAMPP viene con algunas extensiones de gráficos y descompresión desactivadas. Debes activarlas:

1. Abre el panel de control de **XAMPP** y dale a **Stop** (detener) al servicio de **Apache** si está encendido.
2. Abre el explorador de archivos y dirígete a: `C:\xampp\php\`
3. Busca el archivo llamado **`php.ini`** y ábrelo con el Bloc de notas o VS Code.
4. Presiona `Ctrl + F` (o `Ctrl + B` para buscar) y localiza las siguientes líneas:
   * `;extension=gd`
   * `;extension=zip`
5. **Elimina el punto y coma (`;`) al inicio** de ambas líneas para que queden así:
   ```ini
   extension=gd
   extension=zip
   ```
6. Guarda el archivo y cierra el editor.
7. En el panel de **XAMPP**, inicia de nuevo **Apache** y **MySQL** dándoles a **Start**.

---

## 📂 Paso 3: Configurar el Archivo de Entorno de la App

1. Entra a la carpeta del proyecto en tu computadora.
2. Busca el archivo llamado **`.env.example`** y duplícalo.
3. Cambia el nombre de la copia a **`.env`** (asegúrate de que no termine en `.txt`).
4. Abre el archivo `.env` y busca la sección de base de datos (`DB_*`). Modifícala para que coincida con tu XAMPP:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bustrak_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Nota: Por defecto en XAMPP de Windows, el usuario es `root` y la contraseña se deja completamente vacía).*

---

## 📥 Paso 4: Instalar las Dependencias del Proyecto

Abre una terminal (PowerShell o CMD) dentro de la carpeta del proyecto y ejecuta los siguientes comandos en orden:

```bash
# 1. Instalar las dependencias de PHP (omitimos desarrollo para ser compatibles con PHP 8.2)
composer install --no-dev

# 2. Generar la clave de seguridad del proyecto
php artisan key:generate

# 3. Instalar los paquetes de Javascript (React, TypeScript, Vite)
npm install
```

---

## 🗄️ Paso 5: Importar la Base de Datos

### Opción A: Copia rápida desde código (Laravel Seeders)
Si no tienes el archivo `.sql` a mano y quieres una base de datos de prueba funcional creada por código:
1. Crea una base de datos vacía en phpMyAdmin llamada `bustrak_db`.
2. Ejecuta en la terminal:
   ```bash
   php artisan migrate --seed
   ```

### Opción B: Si tienes el archivo `bustrak_db_completo.sql`
1. Abre [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/) en tu navegador.
2. Haz clic en **Nueva** en la barra lateral izquierda y crea una base de datos con el nombre **`bustrak_db`**.
3. Selecciona la nueva base de datos `bustrak_db`.
4. Ve a la pestaña **Importar** de la barra superior.
5. Selecciona el archivo `.sql` que te compartieron y haz clic en **Importar** abajo.
   * *Aviso:* Si te aparece un error que dice *"La base de datos ya existe (Error 1007)"*, no te preocupes, es porque el archivo venía con la instrucción `CREATE DATABASE`. Las tablas se habrán importado correctamente de todas formas.

---

## 🚀 Paso 6: Ejecutar el Proyecto

Una vez completado todo lo anterior, ejecuta el servidor:
```bash
composer run dev
```

Este comando activará el backend y el frontend. Deja la terminal abierta y accede al sistema desde tu navegador en la dirección:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

### 🔑 Cuentas de Acceso de Prueba (Si usaste la Opción A de Base de Datos):
* **Administrador:** `admin@bustrak.com` | Clave: `admin123`
* **Cliente:** `jose@hn.com` | Clave: `12345678`
* **Conductor/Empleado:** `empleado@bustrak.com` | Clave: `12345678`
