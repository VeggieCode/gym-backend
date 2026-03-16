<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SerieRealizada extends Model
{
    protected $table = 'series_realizadas';

    protected $fillable = [
        'ejercicio_entrenado_id',
        'peso',
        'repeticiones',
        'tiempo_segundos',
        'distancia_metros',
        'completada',
        'orden',
        'created_at',
        'updated_at'
    ];

    public function casts(): array
    {
        return [
            'peso' => 'float',
            'repeticiones' => 'integer',
            'tiempo_segundos' => 'integer',
            'distancia_metros' => 'float',
            'completada' => 'boolean',
        ];
    }

    public function ejercicioEntrenado(): BelongsTo
    {
        return $this->belongsTo(EjercicioEntrenado::class);
    }
}

