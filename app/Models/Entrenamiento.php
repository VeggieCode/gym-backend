<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrenamiento extends Model
{
    protected $fillable = [
        'usuario_id',
        'rutina_base_id',
        'fecha_inicio',
        'fecha_fin',
        'created_at',
        'updated_at',
        'nombre',
        'id'
    ];

    public function ejercicios()
    {
        return $this->hasMany(EjercicioEntrenado::class, 'entrenamiento_id');
    }

}
