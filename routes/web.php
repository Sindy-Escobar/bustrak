<?php

use Illuminate\Support\Facades\Route;

// Declaraciones 'use' combinadas
use Inertia\Inertia;
use App\Http\Controllers\RegistroUsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClienteController;


// Ruta de registro (De tu versión)
Route::get('/registro', function () {
    return view('Vista_registro.create');
});

// Ruta principal (De la versión 'main')
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación (HU1)
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');

