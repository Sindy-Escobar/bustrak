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
        'email',             // <--- asegúrate de incluir esto
        'password_initial',  // <--- y esto si quieres mostrar la contraseña
    ];


    protected $casts = [
        'fecha_desactivacion' => 'datetime',
    ];

    public $timestamps = true;
}
