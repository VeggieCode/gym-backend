<?php

namespace App\Infrastructure\Mappers;

use App\Domain\Entities\Rutina as DomainRutina;
use App\Domain\Entities\Ejercicio as DomainEjercicio;
use App\Models\Rutina as EloquentRutina;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RutinaMapper
{
    public static function toDomain(Model|Collection|EloquentRutina $modelo)
    {
        if ($modelo instanceof Collection) {
            return $modelo->map(fn($m) => self::toDomain($m))->toArray();
        }

        // Mapeamos los modelos de Eloquent de los ejercicios a entidades de dominio
        $ejerciciosDominio = $modelo->ejercicios->map(function ($ejModelo) {
            return new DomainEjercicio(
                $ejModelo->id,
                nombre: $ejModelo->nombre, grupoMuscular: $ejModelo->grupo_muscular, tipoRegistro: $ejModelo->tipo_registro
            );
        })->toArray();

        // Mapeamos el modelo principal a la entidad de Dominio Rutina
        // Aseguramos que dias_asignados sea un array
        $diasAsignados = is_string($modelo->dias_asignados)
            ? json_decode($modelo->dias_asignados, true)
            : $modelo->dias_asignados;

        return new DomainRutina(
            $modelo->id,
            nombre: $modelo->nombre,
            diasAsignados: $diasAsignados,
            usuarioId: $modelo->user_id,
            ejercicios: $ejerciciosDominio
        );
    }
}
