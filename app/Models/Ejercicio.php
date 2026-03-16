<?php

namespace App\Models;

use App\Domain\Enums\TipoRegistroEjercicio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejercicio extends Model
{
    protected $fillable = [
        'nombre',
        'tipo_registro',
        'grupo_muscular'
    ];

    public function rutinas()
    {
        return $this->belongsToMany(Rutina::class, 'ejercicio_rutina');
    }


    public function casts(): array
    {
        return [
            'tipo_registro' => TipoRegistroEjercicio::class,
        ];
    }
}
