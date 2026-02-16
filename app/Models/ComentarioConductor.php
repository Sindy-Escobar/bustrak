<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComentarioConductor extends Model
{
    use HasFactory;

    protected $table = 'comentario_conductores';

    // ACTUALIZADO: Agregamos todos los campos del formulario
    protected $fillable = [
        'usuario_id',
        'empleado_id',
        'calificacion',
        'comentario',
        'mejoras',
        'positivo',
        'comportamientos',
        'velocidad',
        'seguridad',
    ];

    protected $casts = [
        'calificacion' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el modelo Usuario
     * Asegúrate de que el modelo sea 'User' o 'Usuario' según lo tengas en tu App
     */
    public function usuario(): BelongsTo
    {
        // Si tu modelo de autenticación de Laravel es User, cámbialo aquí
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con el modelo Empleado (Conductor)
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    /**
     * Scopes de ordenamiento
     */
    public function scopeReciente($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeAntiguo($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    public function scopeMejor($query)
    {
        return $query->orderBy('calificacion', 'desc')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope para filtrar por conductor
     */
    public function scopeDelConductor($query, $empleadoId)
    {
        return $query->where('empleado_id', $empleadoId);
    }
}
