<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// Controladores
use App\Http\Controllers\EmpleadoHU5Controller;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\RegistroUsuarioController;
use App\Http\Controllers\EmpleadoController;

// ======================================================
// RUTA PRINCIPAL
// ======================================================
Route::get('/', function () {
    return redirect()->route('login'); // mantenemos la redirección a login
})->name('home');

// ======================================================
// CONSULTA DE EMPRESAS
// ======================================================
Route::get('empresas', [EmpresaController::class, 'index'])->name('empresas.index');

// ======================================================
// RECURSO EMPLEADOS
// ======================================================
Route::resource('empleados', EmpleadoController::class);
Route::put('empleados/{id}/toggle', [EmpleadoController::class, 'toggleEstado'])->name('empleados.toggle');

// ======================================================
// RUTAS DE AUTENTICACIÓN
// ======================================================

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Ruta alternativa de registro (opcional)
Route::get('/registro', function () {
    return view('Vista_registro.create');
})->name('registro');

// Password Reset
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
});

// ======================================================
// RUTAS PROTEGIDAS PARA ADMIN
// ======================================================
Route::middleware(['auth', 'user.active'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::post('/usuarios/{id}/cambiar-estado', [AdminController::class, 'cambiarEstado'])->name('admin.cambiarEstado');
});

// ======================================================
// RUTAS DE INERTIA / FRANCIS_5
// ======================================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('/empleados-hu5', [EmpleadoHU5Controller::class, 'index'])->name('empleados.hu5');
});
