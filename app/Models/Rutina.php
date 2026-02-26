<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rutina extends Model
{
    protected $fillable = ['nombre', 'dias_asignados'];

    // Le decimos a Laravel que maneje este campo automáticamente como un array
    protected $casts = [
        'dias_asignados' => 'array'
    ];

    public function ejercicios(): HasMany
    {
        return $this->hasMany(Ejercicio::class);
    }
}
