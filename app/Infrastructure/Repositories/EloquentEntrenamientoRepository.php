<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Entrenamiento as EntrenamientoDomain;
use App\Domain\Repositories\EntrenamientoRepositoryInterface;
use App\Infrastructure\Mappers\EntrenamientoMapper;
use App\Models\Entrenamiento as EntrenamientoModel;
use Illuminate\Support\Facades\DB;

class EloquentEntrenamientoRepository implements EntrenamientoRepositoryInterface
{

    public function obtenerActivoPorUsuario(int $usuarioId): ?EntrenamientoDomain
    {
        // Buscamos un entrenamiento de este usuario que NO tenga fecha_fin (Está en curso)
        $entrenamientoModel = EntrenamientoModel::with(['ejercicios.series'])
            ->where('usuario_id', $usuarioId)
            ->whereNull('fecha_fin')
            ->first();

        if (!$entrenamientoModel) {
            return null;
        }

        return EntrenamientoMapper::toDomain($entrenamientoModel);
    }

    public function guardar(EntrenamientoDomain $entrenamiento): EntrenamientoDomain
    {
        // Usamos una transacción de base de datos
        $entrenamientoModel = DB::transaction(function () use ($entrenamiento) {

            // 1. Guardar o Actualizar la cabecera del Entrenamiento
            $entrenamientoModel = EntrenamientoModel::updateOrCreate(
                ['id' => $entrenamiento->id],
                [
                    'usuario_id' => $entrenamiento->usuarioId,
                    'rutina_base_id' => $entrenamiento->rutinaBaseId,
                    'nombre' => $entrenamiento->nombre,
                    'fecha_inicio' => $entrenamiento->periodo->inicio->format('Y-m-d H:i:s'),
                    'fecha_fin' => $entrenamiento->periodo->fin?->format('Y-m-d H:i:s'),
                ]
            );

            // 2. Sincronizar Ejercicios
            // Para simplificar: borramos todo lo viejo y reinsertamos
            $entrenamientoModel->ejercicios()->delete();

            foreach ($entrenamiento->ejercicios as $indexEjercicio => $ejercicio) {
                $ejercicioModel = $entrenamientoModel->ejercicios()->create([
                    'ejercicio_original_id' => $ejercicio->ejercicioOriginalId,
                    'nombre_snapshot' => $ejercicio->nombreSnapshot,
                    'tipo_registro' => $ejercicio->tipoRegistro->value,
                    'orden' => $indexEjercicio
                ]);

                // 3. Insertar las Series de este ejercicio
                $seriesData = [];
                foreach ($ejercicio->series as $indexSerie => $serie) {
                    $seriesData[] = [
                        'peso' => $serie->peso,
                        'repeticiones' => $serie->repeticiones,
                        'tiempo_segundos' => $serie->tiempoSegundos,
                        'distancia_metros' => $serie->distanciaMetros,
                        'completada' => $serie->completada,
                        'orden' => $indexSerie,
                    ];
                }

                if (!empty($seriesData)) {
                    $ejercicioModel->series()->createMany($seriesData);
                }
            }

            // Recargamos el modelo con sus relaciones frescas de la BD
            return $entrenamientoModel->load('ejercicios.series');
        });

        return EntrenamientoMapper::toDomain($entrenamientoModel);
    }
}
