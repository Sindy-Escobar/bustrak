<?php

use Illuminate\Support\Facades\Route;

// Declaraciones 'use' combinadas
use Inertia\Inertia;
use App\Http\Controllers\RegistroUsuarioController;


// Ruta de registro
Route::get('/registro', function () {
    return view('Vista_registro.create');
});
Route::post('/registro', [RegistroUsuarioController::class, 'store'])->name('registro.store');

Route::get('/usuarios', [RegistroUsuarioController::class, 'index'])->name('usuarios.index');

Route::get('/usuarios/{id}', [RegistroUsuarioController::class, 'show'])->name('usuarios.show');
