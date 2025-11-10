<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'viaje_id',
        'codigo_reserva',
        'fecha_reserva',
        'estado',
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con viaje
    public function viaje()
    {
        return $this->belongsTo(Viaje::class, 'viaje_id');
    }
}

