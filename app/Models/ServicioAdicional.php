<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioAdicional extends Model
{
    use HasFactory;

    protected $table = 'servicios_adicionales';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'icono',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'precio' => 'decimal:2',
    ];

    // Relación con reservas
    public function reservas()
    {
        return $this->belongsToMany(Reserva::class, 'reserva_servicio_adicional')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }
}
