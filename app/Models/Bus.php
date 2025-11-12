<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $table = 'buses';
    use HasFactory;

    protected $fillable = ['placa', 'modelo', 'capacidad_asientos', 'estado'];
}
