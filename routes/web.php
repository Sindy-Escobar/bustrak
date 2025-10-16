<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClienteController;

// Ruta principal
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación (HU1)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para CLIENTES (protegidas y solo para usuarios activos)
Route::middleware(['auth', 'user.active'])->prefix('cliente')->group(function () {
    Route::get('/perfil', [ClienteController::class, 'perfil'])->name('cliente.perfil');
    Route::get('/reservas', [ClienteController::class, 'reservas'])->name('cliente.reservas');
});

// Rutas para ADMIN (HU24 - Validación de usuarios)
Route::middleware(['auth', 'user.active'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::post('/usuarios/{id}/cambiar-estado', [AdminController::class, 'cambiarEstado'])->name('admin.cambiarEstado');
});
