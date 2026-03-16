<?php

namespace App\Models;

use App\Domain\Enums\TipoRegistroEjercicio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EjercicioEntrenado extends Model
{
    protected $table = 'ejercicios_entrenados';
    protected $fillable = ['entrenamiento_id', 'ejercicio_original_id', 'nombre_snapshot', 'tipo_registro', 'orden', 'created_at', 'updated_at'];

    public function casts(): array
    {
        return [
            'tipo_registro' => TipoRegistroEjercicio::class,
        ];
    }

    public function entrenamiento(): BelongsTo
    {
        return $this->belongsTo(Entrenamiento::class);
    }

    public function series(): HasMany
    {
        return $this->hasMany(SerieRealizada::class);
    }
}
