<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroContable extends Model
{
    protected $table = 'registros_contables';

    protected $fillable = [
        'reembolso_id',
        'tipo_transaccion',
        'descripcion',
        'monto',
        'cuenta_origen',
        'cuenta_destino',
        'estado',
        'observaciones',
        'registrado_por',
        'fecha_transaccion'
    ];

    protected $casts = [
        'fecha_transaccion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con Reembolso
    public function reembolso()
    {
        return $this->belongsTo(Reembolso::class, 'reembolso_id');
    }

    // Relación con Usuario
    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    // Scopes
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_transaccion', $tipo);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    // Método para registrar un reembolso automáticamente
    public static function registrarReembolso($reembolso)
    {
        return self::create([
            'reembolso_id' => $reembolso->id,
            'tipo_transaccion' => 'reembolso',
            'descripcion' => "Reembolso de reserva #{$reembolso->codigo_cancelacion} - {$reembolso->usuario->name}",
            'monto' => $reembolso->monto_reembolso,
            'cuenta_origen' => 'Caja Principal',
            'cuenta_destino' => $reembolso->metodo_pago === 'transferencia' ? "Transferencia a {$reembolso->banco}" : $reembolso->metodo_pago,
            'estado' => 'procesado',
            'observaciones' => "Procesado por " . auth()->user()->name . " - {$reembolso->metodo_pago}",
            'registrado_por' => auth()->id(),
            'fecha_transaccion' => now(),
        ]);
    }
}
