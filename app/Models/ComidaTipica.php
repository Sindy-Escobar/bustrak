<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComidaTipica extends Model
{
    protected $table = 'comidas_tipicas';
    protected $fillable = ['departamento_id', 'nombre', 'imagen'];
    public $timestamps = false;

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
