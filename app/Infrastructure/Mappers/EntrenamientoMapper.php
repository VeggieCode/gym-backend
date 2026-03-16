<?php

namespace App\Infrastructure\Mappers;

use App\Domain\Entities\Entrenamiento as EntrenamientoDomain;
use App\Domain\Entities\EjercicioEntrenado as EjercicioEntrenadoDomain;
use App\Domain\Entities\SerieRealizada as SerieRealizadaDomain;
use App\Domain\ValueObjects\Periodo;
use App\Domain\Enums\TipoRegistroEjercicio;
use App\Models\Entrenamiento as EntrenamientoModel;
use DateTimeImmutable;

class EntrenamientoMapper
{
    /**
     * Convierte un Modelo Eloquent (con sus relaciones cargadas) a una Entidad de Dominio Pura.
     */
    public static function toDomain(EntrenamientoModel $model): EntrenamientoDomain
    {
        $fechaInicio = new DateTimeImmutable($model->fecha_inicio);
        $fechaFin = $model->fecha_fin ? new DateTimeImmutable($model->fecha_fin) : null;

        $entrenamiento = new EntrenamientoDomain(
            id: $model->id,
            usuarioId: $model->usuario_id,
            nombre: $model->nombre,
            periodo: new Periodo($fechaInicio, $fechaFin),
            rutinaBaseId: $model->rutina_base_id
        );

        if ($model->relationLoaded('ejercicios')) {
            foreach ($model->ejercicios as $ejercicioModel) {
                $ejercicioDomain = new EjercicioEntrenadoDomain(
                    id: $ejercicioModel->id,
                    ejercicioOriginalId: $ejercicioModel->ejercicio_original_id,
                    nombreSnapshot: $ejercicioModel->nombre_snapshot,
                    // Si Eloquent ya lo casteó a Enum, lo usamos directo, si no, lo convertimos:
                    tipoRegistro: $ejercicioModel->tipo_registro instanceof TipoRegistroEjercicio
                        ? $ejercicioModel->tipo_registro
                        : TipoRegistroEjercicio::from($ejercicioModel->tipo_registro)
                );

                if ($ejercicioModel->relationLoaded('series')) {
                    foreach ($ejercicioModel->series as $serieModel) {
                        $serieDomain = new SerieRealizadaDomain(
                            id: $serieModel->id,
                            ejercicioEntrenadoId: $ejercicioModel->id,
                            tipoRegistro: $ejercicioDomain->tipoRegistro,
                            peso: $serieModel->peso !== null ? (float)$serieModel->peso : null,
                            repeticiones: $serieModel->repeticiones,
                            tiempoSegundos: $serieModel->tiempo_segundos,
                            distanciaMetros: $serieModel->distancia_metros !== null ? (float)$serieModel->distancia_metros : null,
                            completada: (bool)$serieModel->completada
                        );

                        $ejercicioDomain->agregarSerie($serieDomain);
                    }
                }

                $entrenamiento->agregarEjercicio($ejercicioDomain);
            }
        }

        return $entrenamiento;
    }
}
