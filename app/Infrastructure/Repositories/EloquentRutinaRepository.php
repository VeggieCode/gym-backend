<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Rutina as DomainRutina;
use App\Domain\Entities\Ejercicio as DomainEjercicio;
use App\Domain\Repositories\RutinaRepositoryInterface;
use App\Models\Rutina as EloquentRutina;
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

            // 2. Preparamos los hijos con el ID recién creado
            $ejerciciosParaInsertar = [];
            foreach ($rutina->ejercicios as $ejercicio) {
                $ejerciciosParaInsertar[] = [
                    'rutina_id' => $modeloRutina->id,
                    'nombre' => $ejercicio->nombre,
                    'series' => $ejercicio->series,
                    'repeticiones' => $ejercicio->repeticiones,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // 3. Insertamos todos los hijos de un solo golpe (Bulk Insert)
            $modeloRutina->ejercicios()->insert($ejerciciosParaInsertar);

            /*
            Si observas con lupa tu respuesta, notarás que los ejercicios regresaron con "id": null. Esto es un comportamiento clásico de Laravel: al usar insert() masivo (bulk insert) para optimizar el rendimiento, la base de datos no le devuelve a Eloquent los IDs autoincrementales de los hijos generados.
            Para el flujo de creación actual está perfecto, pero si en el futuro necesitas devolver los IDs reales de los ejercicios de inmediato, bastaría con hacer un $modeloRutina->load('ejercicios'); al final de tu repositorio y actualizar la entidad de dominio antes de retornarla.
            */

            // 4. Actualizamos la entidad de dominio con su nuevo ID y la devolvemos
            $rutina->id = $modeloRutina->id;
            return $rutina;
        });
    }
}
