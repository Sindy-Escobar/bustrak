<?php

use Illuminate\Support\Facades\Route;
use App\Models\{Empleado, User};
use App\Http\Controllers\{
    RegistroTerminalController,
    ValidarEmpresaController2,
    EmpresaHU11Controller,
    EmpresaController,
    EmpresaBusController,
    AuthController,
    AdminController,
    ClienteController,
    RegistroUsuarioController,
    EmpleadoController,
    EmpleadoHU5Controller,
    ConsultaParadaController,
    AbordajeController,
    NotificacionController,
    HistorialReservasController
};

// ======================================================
// RUTA PRINCIPAL
// ======================================================
Route::get('/', fn() => redirect()->route('login'))->name('home');

// ======================================================
// AUTENTICACIÓN
// ======================================================
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');

    // Reset de contraseña
    Route::get('/forgot-password', 'showForgotPassword')->name('password.request');
    Route::post('/forgot-password', 'sendResetLink')->name('password.email');
    Route::get('/reset-password/{token}', 'showResetPassword')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
});

// ======================================================
// REGISTRO DE USUARIOS
// ======================================================
Route::controller(RegistroUsuarioController::class)->group(function () {
    Route::get('/registro', 'create')->name('registro');
    Route::post('/registro', 'store');
    Route::get('/usuarios/consultar', 'consultar')->name('usuarios.consultar');
});
Route::resource('usuarios', RegistroUsuarioController::class);

// ======================================================
// ADMINISTRADOR
// ======================================================
Route::middleware(['auth', 'user.active'])->prefix('admin')->group(function () {
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::patch('/usuarios/{id}/cambiar', [AdminController::class, 'cambiarEstado'])->name('admin.cambiarEstado');
    Route::patch('/usuarios/{id}/validar', [AdminController::class, 'validar'])->name('admin.validar');

    Route::get('/pagina', function () {
        $total_activos = Empleado::where('estado', 'Activo')->count();
        $total_inactivos = Empleado::where('estado', 'Inactivo')->count();
        $total_empleados = Empleado::count();
        $totalUsuarios = User::count();
        $usuariosActivos = User::where('estado', 'activo')->count();
        $usuariosInactivos = User::where('estado', 'inactivo')->count();

        return view('interfaces.admin', compact(
            'total_activos', 'total_inactivos', 'total_empleados',
            'totalUsuarios', 'usuariosActivos', 'usuariosInactivos'
        ));
    })->name('admin.dashboard');
});

// ======================================================
// EMPLEADOS
// ======================================================
Route::resource('empleados', EmpleadoController::class);
Route::get('/empleados/{id}/desactivar', [EmpleadoController::class, 'formDesactivar'])->name('empleados.formDesactivar');
Route::put('/empleados/{id}/desactivar', [EmpleadoController::class, 'guardarDesactivacion'])->name('empleados.desactivar');
Route::put('/empleados/{id}/activar', [EmpleadoController::class, 'activar'])->name('empleados.activar');

// HU5
Route::get('/empleados-hu5', [EmpleadoHU5Controller::class, 'index'])->name('empleados.hu5');
Route::put('/empleados-hu5/{empleado}', [EmpleadoHU5Controller::class, 'update'])->name('empleados.hu5.update');

Route::put('empresas/{id}', [EmpresaController::class, 'update'])->name('empresas.update');
// ======================================================
// EMPRESAS
// ======================================================
Route::get('empresas', [EmpresaController::class, 'index'])->name('empresas.index');
Route::get('validar-empresas', [ValidarEmpresaController2::class, 'index'])->name('empresas.validar');
Route::match(['get', 'post'], 'empresa', [EmpresaBusController::class, 'form'])->name('empresa.form');
Route::get('empresa-hu11/{id}/editar', [EmpresaHU11Controller::class, 'edit'])->name('empresa.edit.hu11');
Route::put('empresa-hu11/{id}', [EmpresaHU11Controller::class, 'update'])->name('empresa.update.hu11');

// ======================================================
// TERMINALES
// ======================================================
Route::resource('terminales', RegistroTerminalController::class);
Route::get('ver_terminales', [RegistroTerminalController::class, 'ver_terminales'])->name('terminales.ver_terminales');

// ======================================================
// CONSULTA DE PARADAS
// ======================================================
Route::get('consulta-paradas', [ConsultaParadaController::class, 'index'])->name('consulta-paradas.index');

// ======================================================
// CLIENTE (USUARIO)
// ======================================================
Route::middleware(['auth', 'user.active'])->prefix('usuario')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('usuario.dashboard');
    Route::get('/viajes', [ClienteController::class, 'viajes'])->name('usuario.viajes');
    Route::get('/pasajeros', [ClienteController::class, 'pasajeros'])->name('usuario.pasajeros');
    Route::get('/confirmar', [ClienteController::class, 'confirmar'])->name('usuario.confirmar');
    Route::get('/qr', [ClienteController::class, 'qr'])->name('usuario.qr');
});

// ======================================================
// ABORDAJE
// ======================================================
Route::middleware('auth')
    ->prefix('abordajes')
    ->name('abordajes.')
    ->controller(AbordajeController::class)
    ->group(function () {
        Route::get('escanear', 'mostrarEscaner')->name('escanear');
        Route::post('validar', 'validarCodigoQR')->name('validar');
        Route::post('confirmar', 'confirmarAbordaje')->name('confirmar');
        Route::get('historial', 'historial')->name('historial');
    });

// ======================================================
// NOTIFICACIONES
// ======================================================
Route::middleware('auth')->group(function () {
    Route::get('notificaciones', [NotificacionController::class, 'index'])->name('usuario.notificaciones');
    Route::get('notificaciones/leida/{id}', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leida');
    Route::delete('notificaciones/eliminar/{id}', [NotificacionController::class, 'eliminar'])->name('notificaciones.eliminar');
});

// ======================================================
// OTRAS RUTAS
// ======================================================
Route::view('principal', 'interfaces.principal')->name('principal');

// ======================================================
// RUTAS DE EMPLEADOS INTERFAZ
// ======================================================
Route::prefix('empleado')->middleware(['auth', 'user.active'])->group(function() {

    Route::get('/dashboard', [EmpleadoController::class, 'dashboard'])->name('empleado.dashboard');
    Route::get('/viajes', [EmpleadoController::class, 'viajes'])->name('empleado.viajes');
    Route::get('/pasajeros', [EmpleadoController::class, 'pasajeros'])->name('empleado.pasajeros');
    Route::get('/confirmar', [EmpleadoController::class, 'confirmar'])->name('empleado.confirmar');
    Route::get('/qr', [EmpleadoController::class, 'qr'])->name('empleado.qr');

    // Reservas
    Route::get('/reservas/create', [EmpleadoController::class, 'crearReserva'])->name('empleado.reservas.create');
    Route::get('/reservas', [EmpleadoController::class, 'consultarReservas'])->name('empleado.reservas');
    Route::get('/asientos', [EmpleadoController::class, 'asignarAsientos'])->name('empleado.asientos');
    Route::get('/boletos', [EmpleadoController::class, 'boletos'])->name('empleado.boletos');

    // Itinerarios
    Route::get('/itinerarios', [EmpleadoController::class, 'itinerarios'])->name('empleado.itinerarios');

    // Perfil
    Route::get('/perfil', [EmpleadoController::class, 'perfil'])->name('empleado.perfil');
});
