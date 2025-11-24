<?php

use App\Http\Controllers\RegistroRentaController;
use App\Http\Controllers\RegistroTeminalController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controladores
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\ValidarEmpresaController2;
use App\Http\Controllers\EmpresaHU11Controller;
use App\Http\Controllers\EmpleadoHU5Controller;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpresaBusController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\RegistroUsuarioController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ConsultaParadaController;
use App\Http\Controllers\AbordajeController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\HistorialReservasController;
use App\Http\Controllers\ItinerarioController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CheckinController;


// Toggle activar/inactivar
Route::patch('/admin/usuarios/{id}/cambiar', [AdminController::class, 'cambiarEstado'])->name('admin.cambiarEstado');

// Validar usuario (PATCH) - si usas este método
Route::patch('/admin/usuarios/{id}/validar', [AdminController::class, 'validar'])->name('admin.validar');


// ======================================================
// RUTAS VALIDAR EMPRESAS
// ======================================================
Route::get('/validar-empresas', [ValidarEmpresaController2::class, 'index'])
    ->name('empresas.validar');

//visualizacion de terminales
Route::get('/ver_terminales', [RegistroTeminalController::class, 'ver_terminales'])->name('terminales.ver_terminales');



// RUTA VALIDACIÓN DE EMPLEADOS
Route::get('/validacion-empleados', function () {
    return view('validacion-empleados.index');
})->name('validacion-empleados.index');


//consulta-paradas
Route::get('consulta-paradas', [ConsultaParadaController::class, 'index'])->name('consulta-paradas.index');

// ======================================================
// RUTA PRINCIPAL
// ======================================================
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// ======================================================
// CONSULTA DE EMPRESAS
// ======================================================
Route::get('empresas', [EmpresaController::class, 'index'])->name('empresas.index');

// ======================================================
// REGISTRO DE EMPRESAS DE BUSES
// ======================================================
Route::match(['get', 'post'], '/empresa', [EmpresaBusController::class, 'form'])->name('empresa.form');

// ======================================================
// RECURSO EMPLEADOS
// ======================================================
Route::resource('empleados', EmpleadoController::class);

// Rutas adicionales para activar/desactivar empleados
Route::get('/empleados/{id}/desactivar', [EmpleadoController::class, 'formDesactivar'])->name('empleados.formDesactivar');
Route::put('/empleados/{id}/desactivar', [EmpleadoController::class, 'guardarDesactivacion'])->name('empleados.desactivar');
Route::put('/empleados/{id}/activar', [EmpleadoController::class, 'activar'])->name('empleados.activar');

// ======================================================
// RUTAS DE AUTENTICACIÓN
// ======================================================

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ======================================================
// SOLO USAMOS /registro PARA REGISTRO DE USUARIOS
// ======================================================
Route::get('/registro', [RegistroUsuarioController::class, 'create'])->name('registro');
Route::post('/registro', [RegistroUsuarioController::class, 'store']);

// ======================================================
// CONSULTAR USUARIOS
// ======================================================
Route::get('/usuarios/consultar', [RegistroUsuarioController::class, 'consultar'])->name('usuarios.consultar');

// Recurso usuarios
Route::resource('usuarios', RegistroUsuarioController::class);

// ======================================================
// PASSWORD RESET
// ======================================================
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ======================================================
// RUTAS PROTEGIDAS PARA CLIENTES
// ======================================================
Route::middleware(['auth', 'user.active'])->prefix('cliente')->group(function () {
    Route::get('/perfil', [ClienteController::class, 'perfil'])->name('cliente.perfil');
    Route::get('/reservas', [ClienteController::class, 'reservas'])->name('cliente.reservas');
    Route::get('/perfil/editar', [ClienteController::class, 'edit'])->name('cliente.edit');
    Route::put('/perfil', [ClienteController::class, 'update'])->name('cliente.update');
});

// ======================================================
// RUTAS PROTEGIDAS PARA ADMIN
// ======================================================
Route::middleware(['auth', 'user.active'])->prefix('admin')->group(function () {
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::post('/usuarios/{id}/cambiar-estado', [AdminController::class, 'cambiarEstado'])->name('admin.cambiarEstado');
});

Route::middleware('auth')->get('/admin/pagina', function () {
    return view('interfaces.admin');
})->name('admin.dashboard');

// ======================================================
// RUTAS EMPLEADO-HU5
// ======================================================
Route::get('/empleados-hu5', [EmpleadoHU5Controller::class, 'index'])->name('empleados.hu5');

// ======================================================
// RUTAS EMPRESAS HU11 (Editar / Actualizar)
// ======================================================
Route::get('/empresa-hu11/{id}/editar', [EmpresaHU11Controller::class, 'edit'])->name('empresa.edit.hu11');
Route::put('/empresa-hu11/{id}', [EmpresaHU11Controller::class, 'update'])->name('empresa.update.hu11');

// ======================================================
// RUTAS TERMINALES
// ======================================================
Route::resource('terminales', RegistroTeminalController::class)->parameters([
    'terminales' => 'terminal',
]);
// ======================================================
// RUTA HU10 - VISUALIZAR EMPRESAS DE BUSES
// ======================================================
Route::get('/hu10/empresas-buses', [EmpresaBusController::class, 'index'])
    ->name('hu10.empresas.buses');

Route::get('/principal', function () {
    return view('interfaces.principal');})->name('interfaces.principal');

Route::middleware('auth')->prefix('abordajes')->name('abordajes.')->controller(AbordajeController::class)->group(function () {
    Route::get('escanear', 'mostrarEscaner')->name('escanear');
    Route::post('validar', 'validarCodigoQR')->name('validar');
    Route::post('confirmar', 'confirmarAbordaje')->name('confirmar');
    Route::get('historial', 'historial')->name('historial');
});

// ======================================================
// RUTAS NOTIFICACIONES
// ======================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('usuario.notificaciones');
    Route::get('/notificaciones/leida/{id}', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leida');
    Route::delete('/notificaciones/eliminar/{id}', [NotificacionController::class, 'eliminar'])->name('notificaciones.eliminar');
});

// ======================================================
// RUTAS Historial de viajes
// ======================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/cliente/historial', [HistorialReservasController::class, 'index'])
        ->name('cliente.historial');
});
Route::middleware('auth')->prefix('itinerario')->name('itinerario.')->controller(ItinerarioController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/pdf', 'descargarPDF')->name('pdf');
    Route::get('/compartir/{id}', 'compartir')->name('compartir');
    Route::get('/compartir/{id}/pdf', 'descargarCompartido')->name('pdf.compartido');
    Route::post('/actualizar', 'actualizarItinerario')->name('actualizar');
    Route::get('/historial', 'historialVisualizaciones')->name('historial');
});


// ======================================================
// RUTA HU17 - Catalogo
// ======================================================
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');

// ======================================================
// RUTAS DE CONSULTAS/SOPORTE
// ======================================================
Route::get('/ayuda-soporte', [ConsultaController::class, 'index'])->name('consulta.formulario');
Route::post('/ayuda-soporte', [ConsultaController::class, 'store'])->name('soporte.enviar');

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

     Route::prefix('usuario')->middleware(['auth', 'user.active'])->group(function() {
    Route::get('/dashboard', [EmpleadoController::class, 'dashboard'])->name('usuario.dashboard');
});

Route::prefix('usuario')->middleware(['auth', 'user.active'])->name('usuario.')->group(function() {
    Route::get('/dashboard', function () {
        return view('usuarios.dashboard');
    })->name('dashboard');

    Route::get('/viajes', [EmpleadoController::class, 'viajes'])->name('viajes');
    Route::get('/pasajeros', [EmpleadoController::class, 'pasajeros'])->name('pasajeros');
    Route::get('/confirmar', [EmpleadoController::class, 'confirmar'])->name('confirmar');
    Route::get('/qr', [EmpleadoController::class, 'qr'])->name('qr');

    Route::get('/perfil', [EmpleadoController::class, 'perfil'])->name('perfil');
});


//Empleados Fransis

Route::get('/empleados-hu5', [EmpleadoController::class, 'index'])->name('empleados.hu5');
Route::put('/empleados-hu5/{id}', [EmpleadoController::class, 'update'])->name('empleados.hu5.update');
Route::put('/empleados-hu5/{id}/activar', [EmpleadoController::class, 'activar'])->name('empleados.hu5.activar');
Route::put('/empleados-hu5/{id}/desactivar', [EmpleadoController::class, 'guardarDesactivacion'])->name('empleados.hu5.desactivar');

//Estadisticas
Route::get('/estadisticahu46', [EstadisticasController::class, 'index'])
    ->name('estadistica');
Route::get('/admin/estadisticas', [EstadisticasController::class, 'mostrar'])
    ->name('admin.estadisticas');

//Ruta para visualizar y actualizar las empresas---Anahi_cabrera
Route::put('empresas/{id}', [EmpresaController::class, 'update'])->name('empresas.update');

Route::resource('rentas', RegistroRentaController::class);

// ======================================================
// RUTAS PARA RESERVAS
// ======================================================
Route::middleware(['auth'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('reserva/create', [ReservaController::class, 'create'])->name('reserva.create');
    Route::post('reserva/buscar', [ReservaController::class, 'buscar'])->name('reserva.buscar');
    Route::get('reserva/{viaje_id}/asientos', [ReservaController::class, 'seleccionarAsiento'])->name('reserva.asientos');
    Route::post('reserva/store', [ReservaController::class, 'store'])->name('reserva.store');

});

Route::put('/reserva/{reserva}/update', [ReservaController::class, 'update'])->name('reserva.update');


//usuario pre-determinado para admin, Roberto
Route::get('/admin/dashboard', function () {
    if (Auth::check() && Auth::user()->role === 'Administrador') {
        return view('admin.dashboard');
    }
    abort(403, 'Acceso denegado');
})->middleware('auth')->name('admin.dashboard');


// Página temporal "Próximamente"
Route::get('/proximamente', function () {
    return view('Empresa.proximamente');
})->name('proximamente');

// Chec-kin
Route::post('/abordajes/validar', [CheckinController::class, 'validarCodigo'])->name('abordajes.validar');
Route::post('/abordajes/confirmar', [CheckinController::class, 'confirmarAbordaje'])->name('abordajes.confirmar');
Route::get('/abordajes/historial', [CheckinController::class, 'historial'])->name('abordajes.historial');
