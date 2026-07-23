@echo off
echo ==============================================
echo   Bienvenido a BusTrak
echo ==============================================
echo.

:: Verificar si el archivo .env existe
if not exist ".env" (
    echo Creando archivo .env...
    copy .env.example .env
    echo Generando key de aplicacion...
    call php artisan key:generate
    echo Por favor configura tus variables en el archivo .env
    echo y vuelve a ejecutar este script si es la primera vez.
    echo.
)

echo [1/3] Instalando dependencias de PHP (Composer)...
call composer install --ignore-platform-reqs
echo.

echo [2/3] Instalando dependencias de Node.js (NPM)...
call npm install
echo.

echo [3/3] Ejecutando el servidor de desarrollo...
echo Presiona Ctrl+C en cualquier momento para detener los servidores.
echo.
call composer run dev
