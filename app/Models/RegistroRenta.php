<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroRenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'tipo_evento',
        'destino',
        'punto_partida',
        'fecha_inicio',
        'fecha_fin',
        'num_pasajeros_confirmados',
        'num_pasajeros_estimados',
        'tarifa',
        'descuento',
        'total_tarifa',
        'codigo_renta',
        'estado',
        'anticipo',
        'hora_salida',
        'hora_retorno',
        'penalizacion',
    ];

    public function usuarios()
    {
        return $this->belongsTo(Usuario::class);
    }
}
