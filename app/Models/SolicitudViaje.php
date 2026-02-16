<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SolicitudViaje extends Model
{
    protected $table = 'solicitudes_viaje';

    protected $fillable = [
        'tipo_servicio_id', 'user_id', 'codigo_reserva', 'origen', 'destino',
        'fecha_viaje', 'hora_salida', 'num_pasajeros', 'precio_base',
        'descuento', 'precio_total', 'estado', 'metodo_pago', 'notas_especiales'
    ];

    protected $casts = [
        'fecha_viaje' => 'date',
        'precio_base' => 'decimal:2',
        'descuento' => 'decimal:2',
        'precio_total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($solicitud) {
            if (empty($solicitud->codigo_reserva)) {
                $solicitud->codigo_reserva = 'BT' . date('ymd') . strtoupper(Str::random(4));
            }
            if (empty($solicitud->precio_total)) {
                $solicitud->precio_total = $solicitud->precio_base - $solicitud->descuento;
            }
        });
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class, 'tipo_servicio_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
