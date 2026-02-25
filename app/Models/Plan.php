<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    // Forzamos el nombre en español para que no busque "plans"
    protected $table = 'planes';

    protected $fillable = [
        'nombre',
        'nivel',
        'precio',
        'activo'
    ];
}
