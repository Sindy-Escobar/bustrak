<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AutorizacionMenor extends Model
{
    protected $table = 'autorizaciones_menores';

    protected $fillable = [
        'menor_dni',
        'menor_fecha_nacimiento',
        'tutor_nombre',
        'tutor_dni',
        'tutor_email',
        'parentesco',
        'codigo_autorizacion',
        'autorizado',
        'reserva_id',
    ];

    protected $casts = [
        'menor_fecha_nacimiento' => 'date',
        'autorizado' => 'boolean',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    // Validar si es menor de edad
    public static function esMenor($fechaNacimiento)
    {
        return Carbon::parse($fechaNacimiento)->age < 18;
    }

    // Generar código QR
    public static function generarCodigo()
    {
        return 'AUT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}
