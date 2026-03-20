<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    use HasFactory;

    protected $table = 'autorizaciones';

    protected $fillable = [
        'reserva_id',
        'user_id',
        'estado',
        'tipo_autorizacion',
        'observaciones',
        'archivo_path',
        'fecha_solicitud',
        'fecha_aprobacion',
        'aprobado_por',
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_aprobacion' => 'datetime',
    ];

    /**
     * Relación con la reserva
     */
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    /**
     * Relación con el usuario que solicitó
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con el usuario que aprobó
     */
    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}
