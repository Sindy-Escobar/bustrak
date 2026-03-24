<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanjeRealizado extends Model
{
    use HasFactory;

    protected $table = 'canjes_realizados';

    protected $fillable = [
        'usuario_id',
        'beneficio_id',
        'puntos_usados',
        'estado',
        'saldo_tras_canje',
        'reserva_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'usuario_id');
    }

    public function beneficio()
    {
        return $this->belongsTo(CanjeBeneficio::class, 'beneficio_id');
    }

    public function reserva()
    {
        return $this->belongsTo(\App\Models\Reserva::class, 'reserva_id');
    }
}
