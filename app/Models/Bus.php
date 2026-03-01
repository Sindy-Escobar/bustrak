<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $table = 'buses';

    protected $fillable = [
        'numero_bus',
        'placa',
        'modelo',
        'capacidad_asientos',
        'estado',
        'tipo_servicio_id'
    ];
    public function tipoServicio()
    {
        // Relación: Un Bus pertenece a un Tipo de Servicio
        return $this->belongsTo(TipoServicio::class, 'tipo_servicio_id');
    }
    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'bus_id');
    }
    // Accessor para usar 'capacidad' en lugar de 'capacidad_asientos'
    public function getCapacidadAttribute()
    {
        return $this->capacidad_asientos;
        return $this->numero_bus;
    }
    public function asientos()
    {
        return $this->hasMany(Asiento::class, 'viaje_id');
    }

    // Relación con documentos
    public function documentos()
    {
        return $this->hasMany(DocumentoBus::class, 'bus_id');
    }
}
