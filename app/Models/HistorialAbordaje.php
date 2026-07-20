<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialAbordaje extends Model
{
    use HasFactory;

    protected $table = 'historial_abordajes';

    protected $fillable = [
        'asiento_id',
        'reserva_id',
        'fecha_abordaje',
        'estado_abordaje',
    ];

    protected $casts = [
        'fecha_abordaje' => 'datetime',
    ];

    public function asiento()
    {
        return $this->belongsTo(Asiento::class);
    }

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }
}
