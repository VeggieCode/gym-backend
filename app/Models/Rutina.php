<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rutina extends Model
{
    protected $fillable = [
        'nombre',
        'dias_asignados',
        'user_id',
        'id'
    ];

    // Le decimos a Laravel que maneje este campo automáticamente como un array
    protected $casts = [
        'dias_asignados' => 'array'
    ];

    /**
     * @return BelongsToMany
     */
    public function ejercicios(): BelongsToMany
    {
        // belongsToMany para indicar la relación M:N
        // withPivot('orden') nos trae ese dato extra de la tabla intermedia
        return $this->belongsToMany(Ejercicio::class, 'ejercicio_rutina')
            ->withPivot('orden', 'series_objetivo')
            ->orderBy('pivot_orden', 'asc');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entrenamientos(): HasMany
    {
        return $this->hasMany(Entrenamiento::class, 'rutina_base_id');
    }
}
