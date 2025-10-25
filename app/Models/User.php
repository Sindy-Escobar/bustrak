<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Verificar si es admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Verificar si es cliente
    public function isCliente()
    {
        return $this->role === 'cliente';
    }

    // Verificar si está activo
    public function isActive()
    {
        return $this->estado === 'activo';
    }

    // Verificar si está inactivo
    public function isInactive()
    {
        return $this->estado === 'inactivo';
    }
}
