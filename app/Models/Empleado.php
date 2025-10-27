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
        'estado',
        'rol',
        'foto',
        'motivo_baja',
        'fecha_desactivacion',
    ];

    protected $casts = [
        'fecha_desactivacion' => 'datetime',
    ];

    public $timestamps = true;
}
