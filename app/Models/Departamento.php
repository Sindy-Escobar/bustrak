<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $fillable = ['nombre', 'imagen'];
    public $timestamps = false;

    public function lugaresTuristicos()
    {
        return $this->hasMany(LugarTuristico::class, 'departamento_id');
    }

    public function comidasTipicas()
    {
        return $this->hasMany(ComidaTipica::class, 'departamento_id');
    }
}
