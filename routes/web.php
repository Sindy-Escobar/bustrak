<?php

use App\Http\Controllers\RegistroTeminalController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\User;

// Controladores
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
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\AbordajeController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ConsultaController;


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
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendReset'])->name('password.email');
// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ======================================================
// RUTAS PROTEGIDAS PARA CLIENTES
// ======================================================
Route::middleware(['auth', 'user.active'])->prefix('cliente')->group(function () {
    Route::get('/perfil', [ClienteController::class, 'perfil'])->name('cliente.perfil');
    Route::get('/reservas', [ClienteController::class, 'reservas'])->name('cliente.reservas');
});

// ======================================================
// RUTAS PROTEGIDAS PARA ADMIN
// ======================================================
Route::middleware(['auth', 'user.active'])->prefix('admin')->group(function () {
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::post('/usuarios/{id}/cambiar-estado', [AdminController::class, 'cambiarEstado'])->name('admin.cambiarEstado');
});

Route::middleware('auth')->get('/admin/pagina', function () {
    // Totales empleados
    $total_activos = Empleado::where('estado', 'Activo')->count();
    $total_inactivos = Empleado::where('estado', 'Inactivo')->count();
    $total_empleados = Empleado::count();

    // Totales usuarios
    $totalUsuarios = User::count();
    $usuariosActivos = User::where('estado', 'activo')->count();
    $usuariosInactivos = User::where('estado', 'inactivo')->count();

    return view('interfaces.admin', compact(
        'total_activos', 'total_inactivos', 'total_empleados',
        'totalUsuarios', 'usuariosActivos', 'usuariosInactivos'
    ));
})->name('admin.dashboard');

// ======================================================
// RUTAS EMPLEADO-HU5
// ======================================================
Route::get('/empleados-hu5', [EmpleadoController::class, 'index'])->name('empleados.hu5');

// ======================================================
// RUTAS EMPRESAS HU11 (Editar / Actualizar)
// ======================================================
Route::get('/empresa-hu11/{id}/editar', [EmpresaHU11Controller::class, 'edit'])->name('empresa.edit.hu11');
Route::put('/empresa-hu11/{id}', [EmpresaHU11Controller::class, 'update'])->name('empresa.update.hu11');

// ======================================================
// RUTAS TERMINALES
// ======================================================
Route::resource('terminales', RegistroTeminalController::class);

// ======================================================
// RUTA HU10 - VISUALIZAR EMPRESAS DE BUSES
// ======================================================
Route::get('/hu10/empresas-buses', [EmpresaBusController::class, 'index'])
    ->name('hu10.empresas.buses');

// ======================================================
// RUTA PRINCIPAL
// ======================================================
Route::get('/principal', function () {
    return view('interfaces.principal');
});

// ======================================================
// DEMO DASHBOARD
// ======================================================
Route::get('/demo-dashboard', function () {
    // Totales de empleados
    $total_activos = Empleado::where('estado', 'Activo')->count();
    $total_inactivos = Empleado::where('estado', 'Inactivo')->count();
    $total_empleados = Empleado::count();

    // Totales de usuarios
    $totalUsuarios = User::count();
    $usuariosActivos = User::where('estado', 'activo')->count();
    $usuariosInactivos = User::where('estado', 'inactivo')->count();

    // Retorna la vista
    return view('admin.dashboard', compact(
        'total_activos', 'total_inactivos', 'total_empleados',
        'totalUsuarios', 'usuariosActivos', 'usuariosInactivos'
    ));
});

// ======================================================
// RUTA HU17 - Catalogo (tu ruta)
// ======================================================
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');

// ======================================================
// RUTAS ABORDAJES (del main)
// ======================================================
Route::middleware('auth')->prefix('abordajes')->name('abordajes.')->controller(AbordajeController::class)->group(function () {
    Route::get('escanear', 'mostrarEscaner')->name('escanear');
    Route::post('validar', 'validarCodigoQR')->name('validar');
    Route::post('confirmar', 'confirmarAbordaje')->name('confirmar');
    Route::get('historial', 'historial')->name('historial');
});

// ======================================================
// RUTAS DE CONSULTAS/SOPORTE (Públicas)
// ======================================================
Route::get('/ayuda-soporte', [ConsultaController::class, 'index'])->name('consulta.formulario');
Route::post('/ayuda-soporte', [ConsultaController::class, 'store'])->name('consulta.store');

