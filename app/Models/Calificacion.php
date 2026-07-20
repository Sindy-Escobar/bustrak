<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    // IMPORTANTE: Forzar el nombre correcto de la tabla
    protected $table = 'calificaciones';

    protected $fillable = [
        'reserva_id',
        'usuario_id',
        'estrellas',
        'comentario',
    ];

    protected $casts = [
        'estrellas' => 'integer',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
