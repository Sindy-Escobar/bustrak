<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LugarTuristico extends Model
{
    protected $table = 'lugares_turisticos';
    protected $fillable = ['departamento_id', 'nombre', 'imagen'];
    public $timestamps = false;

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
