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
        'cantidad_asientos',
        'codigo_reserva',
        'fecha_reserva',
        'estado',
        'fecha_nacimiento_pasajero',
        'es_menor',
        'abordado',
        'fecha_abordaje',
        'tipo_servicio_id',
        // ── HU14: Reservar para tercero ──
        'para_tercero',
        'tercero_nombre',
        'tercero_pais',
        'tercero_tipo_doc',
        'tercero_num_doc',
        'tercero_telefono',
        'tercero_email',
        'tercero_entrega',
    ];

    protected $casts = [
        'fecha_reserva' => 'datetime',
        'fecha_nacimiento_pasajero' => 'date',
        'es_menor' => 'boolean',
        'abordado' => 'boolean',
        'fecha_abordaje' => 'datetime',
    ];

    // ═══════════════════════════════════════════════════════
    // RELACIONES EXISTENTES
    // ═══════════════════════════════════════════════════════

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

    public function reembolsos()
    {
        return $this->hasMany(\App\Models\Reembolso::class, 'reserva_id');
    }

    public function tipoServicio()
    {
        return $this->belongsTo(\App\Models\TipoServicio::class, 'tipo_servicio_id');
    }

    public function serviciosAdicionales()
    {
        return $this->belongsToMany(ServicioAdicional::class, 'reserva_servicio_adicional')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }

    /**
     * Relación con autorizaciones
     */
    public function autorizaciones()
    {
        return $this->hasMany(Autorizacion::class);
    }

    /**
     * Obtener la autorización activa
     */
    public function autorizacionActiva()
    {
        return $this->hasOne(Autorizacion::class)->latest();
    }

    // ═══════════════════════════════════════════════════════
    // ✅ NUEVAS RELACIONES Y MÉTODOS PARA PAGOS
    // ═══════════════════════════════════════════════════════

    /**
     * Relación con pagos
     */
    public function pagos()
    {
        return $this->hasMany(\App\Models\Pago::class);
    }

    /**
     * Obtener el pago aprobado
     */
    public function pagoAprobado()
    {
        return $this->hasOne(\App\Models\Pago::class)->where('estado', 'aprobado');
    }

    /**
     * Verificar si la reserva está pagada
     */
    public function estaPagada()
    {
        return $this->pagos()->where('estado', 'aprobado')->exists();
    }

    /**
     * Obtener el total a pagar (accessor)
     */
    public function getTotalAPagarAttribute()
    {
        $tarifaBase = $this->tipoServicio->tarifa_base ?? 0;
        $subtotalAsientos = $tarifaBase * ($this->cantidad_asientos ?? 1);

        $totalServicios = $this->serviciosAdicionales->sum(function($servicio) {
            return $servicio->pivot->precio_unitario * $servicio->pivot->cantidad;
        });

        return $subtotalAsientos + $totalServicios;
    }
}
