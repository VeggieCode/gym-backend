<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejercicio extends Model
{
    protected $fillable = ['rutina_id', 'nombre', 'series', 'repeticiones'];

    public function rutina(): BelongsTo
    {
        return $this->belongsTo(Rutina::class);
    }
}
