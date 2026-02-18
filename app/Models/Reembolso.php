<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reembolso extends Model
{
    protected $table = 'reembolsos';

    protected $fillable = [
        'reserva_id',
        'user_id',
        'codigo_reembolso',
        'codigo_cancelacion',
        'monto_original',
        'monto_reembolso',
        'metodo_pago',
        'numero_cuenta',
        'banco',
        'titular_cuenta',
        'numero_cheque',
        'notas',
        'estado',
        'fecha_procesamiento',
        'fecha_entrega',
        'procesado_por',
        'entregado_por'
    ];

    protected $casts = [
        'fecha_procesamiento' => 'datetime',
        'fecha_entrega' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con Reserva
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }

    // Relación con Usuario (cliente)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con Usuario (quien procesó)
    public function procesadoPor()
    {
        return $this->belongsTo(User::class, 'procesado_por');
    }

    // Relación con Usuario (quien entregó)
    public function entregadoPor()
    {
        return $this->belongsTo(User::class, 'entregado_por');
    }

    // Scopes para filtrar
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeProcesados($query)
    {
        return $query->where('estado', 'procesado');
    }

    public function scopeEntregados($query)
    {
        return $query->where('estado', 'entregado');
    }

    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopeFiltroEstado($query, $estado)
    {
        if ($estado) {
            return $query->where('estado', $estado);
        }
        return $query;
    }

    // Método para generar código
    public static function generarCodigoReembolso()
    {
        return 'RBL' . date('ymd') . strtoupper(\Illuminate\Support\Str::random(4));
    }
}
