<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    // AGREGADO: Forzar el nombre correcto de la tabla
    protected $table = 'lugares';

    protected $fillable = ['departamento_id','nombre','imagen'];

    public function departamento() {
        return $this->belongsTo(Departamento::class);
    }
}
