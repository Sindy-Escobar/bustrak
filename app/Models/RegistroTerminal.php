<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroTerminal extends Model
{

    protected $table = 'registro_terminal';

    protected $fillable = [
        'nombre',
        'codigo',
        'direccion',
        'departamento',
        'municipio',
        'telefono',
        'correo',
        'horario_apertura',
        'horario_cierre',
        'descripcion',
    ];


}
