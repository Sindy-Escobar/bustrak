<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = ['nombre', 'descripcion', 'terminal_id', 'icono', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function terminal()
    {
        return $this->belongsTo(RegistroTerminal::class, 'terminal_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorTerminal($query, $terminalId)
    {
        return $query->where('terminal_id', $terminalId);
    }

    public function scopeOrdenadosPorNombre($query)
    {
        return $query->orderBy('nombre');
    }
}
