<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'viaje_id',
        'asiento_id',
        'codigo_reserva',
        'fecha_reserva',
        'estado',
        'fecha_nacimiento_pasajero',
        'es_menor',
        'abordado',
        'fecha_abordaje',
    ];

    protected $casts = [
        'fecha_reserva' => 'datetime',
        'fecha_nacimiento_pasajero' => 'date',
        'es_menor' => 'boolean',
        'abordado' => 'boolean',
        'fecha_abordaje' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function viaje()
    {
        return $this->belongsTo(Viaje::class, 'viaje_id');
    }

    public function asiento()
    {
        return $this->belongsTo(Asiento::class);
    }

    // Relación con autorización de menor
    public function autorizacionMenor()
    {
        return $this->hasOne(AutorizacionMenor::class);
    }

    public function reembolsos()
    {
        return $this->hasMany(\App\Models\Reembolso::class, 'reserva_id');
    }
}
