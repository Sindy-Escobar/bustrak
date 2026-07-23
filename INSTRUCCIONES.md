# Guía de Ejecución Rápida - Bustrak 🚌

Esta guía detalla los 3 sencillos pasos para poner en marcha el sistema en tu computadora usando XAMPP y nuestro script automático.

---

## 🛠️ Paso 1: Preparar la Base de Datos

1. Abre el panel de control de **XAMPP** y enciende **Apache** y **MySQL** dándoles a **Start**.
2. Abre [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/) en tu navegador.
3. Haz clic en **Nueva** (en la barra lateral izquierda) y crea una base de datos con el nombre **`bustrak_db`**.
4. Haz clic en la nueva base de datos `bustrak_db` para entrar en ella.
5. Ve a la pestaña **Importar** de la barra superior.
6. Haz clic en **Seleccionar archivo**, elige tu archivo `.sql` de la base de datos de BusTrak y presiona el botón **Importar** (abajo de todo). 

*(Tu base de datos ahora está lista).*

---

## 🚀 Paso 2: Ejecutar el Script de Inicialización Automática

Hemos preparado un archivo automático que se encarga de crear tu entorno, instalar las dependencias necesarias y arrancar el servidor.

1. Abre la carpeta raíz del proyecto BusTrak desde tu explorador de archivos de Windows.
2. Busca el archivo llamado **`iniciar_proyecto.bat`** y dale **doble clic** para ejecutarlo. (Si Windows te pide permisos, dale a "Ejecutar de todas formas").

**¿Qué hace este archivo internamente por ti?**
* Clona el archivo de configuración `.env.example` a `.env` si no lo tienes.
* Genera la App Key (llave de seguridad).
* Descarga e instala las dependencias de backend usando Composer (ignorando problemas de versión de PHP).
* Descarga e instala las dependencias de frontend usando NPM.
* Enciende los servidores internos (servidor web, colas de trabajo y Vite) automáticamente.

---

## 🌐 Paso 3: Entrar al Sistema

Cuando el script de la terminal diga que Vite y el servidor interno están corriendo (y dejen de salir códigos de carga), **no cierres la ventana negra**. Simplemente abre tu navegador y visita la siguiente dirección:

👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

Para detener el servidor en cualquier momento, basta con ir a la ventana negra y presionar `Ctrl + C`, o simplemente cerrar la ventana.
