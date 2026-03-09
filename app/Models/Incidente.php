<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reserva_id',
        'viaje_id',
        'numero_bus',
        'ruta',
        'descripcion',
        'tipo_incidente',
        'fecha_hora_incidente',
    ];

    protected $casts = [
        'fecha_hora_incidente' => 'datetime',
    ];

    // Relación con usuario (pasajero)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con reserva
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    // Relación con viaje
    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }
}
