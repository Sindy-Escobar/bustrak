<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserva_id',
        'user_id',
        'monto',
        'metodo_pago',
        'estado',
        'codigo_transaccion',
        'referencia_bancaria',
        'numero_tarjeta_ultimos4',
        'banco',
        'fecha_pago',
        'fecha_aprobacion',
        'fecha_rechazo',
        'observaciones',
        'comprobante_path',
        'aprobado_por',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'fecha_aprobacion' => 'datetime',
        'fecha_rechazo' => 'datetime',
    ];

    // Relaciones
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    // Métodos de utilidad
    public static function generarCodigoTransaccion()
    {
        return 'PAG-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
    }

    public function aprobar($admin_id = null)
    {
        $this->update([
            'estado' => 'aprobado',
            'fecha_aprobacion' => now(),
            'aprobado_por' => $admin_id,
        ]);

        // Actualizar estado de la reserva
        $this->reserva->update(['estado' => 'confirmada']);
    }

    public function rechazar($observaciones = null)
    {
        $this->update([
            'estado' => 'rechazado',
            'fecha_rechazo' => now(),
            'observaciones' => $observaciones,
        ]);
    }

    public function reembolsar($observaciones = null)
    {
        $this->update([
            'estado' => 'reembolsado',
            'observaciones' => $observaciones,
        ]);
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopeMetodo($query, $metodo)
    {
        return $query->where('metodo_pago', $metodo);
    }

    // Accessors
    public function getMetodoPagoFormateadoAttribute()
    {
        $metodos = [
            'efectivo' => 'Efectivo',
            'tarjeta_credito' => 'Tarjeta de Crédito',
            'tarjeta_debito' => 'Tarjeta de Débito',
            'transferencia' => 'Transferencia Bancaria',
            'terminal' => 'Pago en Terminal',
        ];

        return $metodos[$this->metodo_pago] ?? $this->metodo_pago;
    }

    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            'reembolsado' => 'Reembolsado',
        ];

        return $estados[$this->estado] ?? $this->estado;
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'warning',
            'aprobado' => 'success',
            'rechazado' => 'danger',
            'reembolsado' => 'info',
        ];

        return $badges[$this->estado] ?? 'secondary';
    }
}
