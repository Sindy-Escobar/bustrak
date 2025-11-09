<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    use HasFactory;

    protected $fillable =[
        'nombre_completo',
        'dni',
        'email', // Necesitas este campo para que la actualización masiva funcione
        'telefono',
        'password',
        // 'estado' Retirado, pertenece al modelo User
    ];

    /**
     * Define la relación inversa con el modelo User (uno a uno).
     */
    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    /**
     * Mutator para hashear la contraseña automáticamente si se asigna.
     */
    public function setPasswordAttribute($value)
    {
        // Solo hashea si el valor no está vacío (para manejar la actualización opcional)
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
