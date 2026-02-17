<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    protected $table = 'tipos_servicio';

    protected $fillable = ['nombre', 'descripcion', 'tarifa_base', 'icono', 'color', 'activo'];

    protected $casts = [
        'tarifa_base' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function solicitudesViaje()
    {
        return $this->hasMany(SolicitudViaje::class, 'tipo_servicio_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenadosPorNombre($query)
    {
        return $query->orderBy('nombre');
    }
}
