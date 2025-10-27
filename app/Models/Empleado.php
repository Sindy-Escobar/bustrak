<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'cargo',
        'fecha_ingreso',
        'rol',
        'estado',
        'foto',
        'email',             // Incluido
        'password_initial',  // Incluido
        'motivo_baja',       // <--- agregado
        'fecha_desactivacion', // <--- agregado también para permitir update
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',          // opcional, útil para formatear
        'fecha_desactivacion' => 'datetime', // ya está
    ];

    public $timestamps = true;
}
