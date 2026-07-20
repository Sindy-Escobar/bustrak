<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    use HasFactory;

    // ACTUALIZADO: Solo campos físicos del asiento (sin reserva_id ni disponible)
    protected $fillable = [
        'viaje_id',
        'numero_asiento',
        'fila',
        'columna',
        'tipo',
        'estado'
    ];

    protected $casts = [
        'fila' => 'integer',
        'numero_asiento' => 'integer',
    ];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }

    // NUEVA: Relación con historial de abordajes (reemplaza la relación a reserva)
    public function historialAbordajes()
    {
        return $this->hasMany(HistorialAbordaje::class);
    }

    // Método auxiliar: obtener la reserva actual confirmada
    public function reservaConfirmada()
    {
        return $this->historialAbordajes()
            ->where('estado_abordaje', 'confirmado')
            ->first()
            ?->reserva;
    }

    // Método auxiliar: verificar si está ocupado
    public function estaOcupado()
    {
        return $this->historialAbordajes()
            ->where('estado_abordaje', 'confirmado')
            ->exists();
    }
}
