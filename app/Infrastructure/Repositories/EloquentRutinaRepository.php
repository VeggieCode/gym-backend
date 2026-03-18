<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Rutina as DomainRutina;
use App\Domain\Entities\Ejercicio as DomainEjercicio;
use App\Domain\Enums\TipoRegistroEjercicio;
use App\Domain\Repositories\RutinaRepositoryInterface;
use App\Domain\ValueObjects\SerieObjetivo;
use App\Infrastructure\Mappers\RutinaMapper;
use App\Models\Rutina as EloquentRutina;
use App\Models\Ejercicio as EloquentEjercicio;
use Illuminate\Support\Facades\DB;

class EloquentRutinaRepository implements RutinaRepositoryInterface
{
    public function guardar(DomainRutina $rutina): DomainRutina
    {
        // DB::transaction asegura que si falla el ejercicio 5, se deshace la creación de la rutina
        return DB::transaction(function () use ($rutina) {

            // 1. Guardamos la raíz
            $modeloRutina = EloquentRutina::create([
                'nombre' => $rutina->nombre,
                'dias_asignados' => $rutina->diasAsignados
            ]);

            // 1. Guardar o actualizar la Rutina (La Raíz)
            $modeloRutina = EloquentRutina::updateOrCreate(
                ['id' => $rutina->id],
                [
                    'user_id' => $rutina->usuarioId,
                    'nombre' => $rutina->nombre,
                    'dias_asignados' => $rutina->diasAsignados,
                ]
            );


            // 2. Preparamos los datos para la Tabla Pivote (M:N)
            $pivotData = [];

            foreach ($rutina->ejercicios as $index => $ejercicio) {
                // LÓGICA DE CATÁLOGO:
                // Si el ejercicio no tiene ID, o si queremos asegurar que no se duplique por nombre
                $modeloEjercicio = EloquentEjercicio::firstOrCreate(
                    // Busca por nombre (para no duplicar el "Press de Banca")
                    ['nombre' => $ejercicio->nombre],
                    [
                        // Si no existe, lo crea con estos datos
                        'grupo_muscular' => $ejercicio->grupoMuscular,
                        'tipo_registro' => $ejercicio->tipoRegistro->value,
                    ]
                );

                $seriesArray = array_map(function(SerieObjetivo $serie) {
                    return [
                        'peso' => $serie->peso,
                        'repeticiones' => $serie->repeticiones,
                        'tiempo_segundos' => $serie->tiempoSegundos,
                        'distancia_metros' => $serie->distanciaMetros
                    ];
                }, $ejercicio->seriesObjetivo);

                // Usamos el ID del catálogo (sea viejo o recién creado)
                $pivotData[$modeloEjercicio->id] = [
                  'orden' => $index,
                    'series_objetivo' => json_encode($seriesArray),
                ];
            }

            // 3. Sincronizar (La magia de Eloquent)
            $modeloRutina->ejercicios()->sync($pivotData);

            // 4. Actualizamos la entidad de dominio con su nuevo ID y la devolvemos
            $rutina->id = $modeloRutina->id;
            return $rutina;
        });
    }

    public function obtenerTodas(): array
    {
        // Usamos eager loading ('with') para traer los ejercicios y evitar el problema de N+1
        $modelos = EloquentRutina::with('ejercicios')->get();

        return RutinaMapper::toDomain($modelos);
    }

    public function buscarPorId(int $id): ?DomainRutina
    {
        $rutinaModel = EloquentRutina::with('ejercicios')->find($id);
        // Mapeamos el modelo principal a la entidad de Dominio Rutina
        // Aseguramos que dias_asignados sea un array
        $diasAsignados = is_string($rutinaModel->dias_asignados)
            ? json_decode($rutinaModel->dias_asignados, true)
            : $rutinaModel->dias_asignados;


        if (!$rutinaModel) return null;

        $rutinaDomain = new DomainRutina(
            id: $rutinaModel->id,
            nombre: $rutinaModel->nombre,
            diasAsignados: $diasAsignados ?? [],
            usuarioId: $rutinaModel->user_id
        );

        foreach ($rutinaModel->ejercicios as $ejercicioModel) {
            $seriesRaw = json_decode($ejercicioModel->pivot->series_objetivo, true) ?? [];

            $seriesObjetivo = [];
            foreach ($seriesRaw as $serieData) {
                $seriesObjetivo[] = new SerieObjetivo(
                    peso: $serieData['peso'] ?? null,
                    repeticiones: $serieData['repeticiones'] ?? null,
                    tiempoSegundos: $serieData['tiempo_segundos'] ?? null,
                    distanciaMetros: $serieData['distancia_metros'] ?? null
                );
            }

            $rutinaDomain->agregarEjercicio(new DomainEjercicio(
                id: $ejercicioModel->id,
                nombre: $ejercicioModel->nombre,
                grupoMuscular: $ejercicioModel->grupo_muscular,
                tipoRegistro: $ejercicioModel->tipo_registro,
                seriesObjetivo: $seriesObjetivo
            ));
        }

        return $rutinaDomain;
    }
}
