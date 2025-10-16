<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegistroUsuarioController;

Route::get('/registro', function () {
    return view('Vista_registro.create');
});
Route::post('/registro', [RegistroUsuarioController::class, 'store']);











