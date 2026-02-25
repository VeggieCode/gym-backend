<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Exceptions\PlanNoEncontradoException;
use App\Domain\Repositories\PlanRepositoryInterface;
// Importamos el modelo de la BD dándole un alias para que no choque con nuestra Entidad
use App\Models\Plan as EloquentPlan;
// Importamos nuestra Entidad Pura
use App\Domain\Entities\Plan as DomainPlan;

class EloquentPlanRepository implements PlanRepositoryInterface
{
    public function obtenerActivos(): array
    {
        // CORRECCIÓN: Filtramos directamente en MySQL antes de traer los datos
        $planesEloquent = EloquentPlan::where('activo', true)->get();

        return $planesEloquent->map(function (EloquentPlan $modelo) {
            return new DomainPlan(
                id: $modelo->id,
                nombre: $modelo->nombre,
                nivel: $modelo->nivel,
                precio: (float) $modelo->precio,
                activo: (bool) $modelo->activo
            );
        })->all();
    }

    public function guardar(DomainPlan $plan): DomainPlan
    {
        // Creamos un nuevo modelo de Laravel y le pasamos los datos del Dominio
        $modelo = new EloquentPlan();
        $modelo->nombre = $plan->nombre;
        $modelo->nivel = $plan->nivel;
        $modelo->precio = $plan->precio;

        // Guardamos en MySQL (aquí es donde se genera el ID autoincremental)
        $modelo->save();

        // Le asignamos el nuevo ID a nuestra entidad pura y la devolvemos
        $plan->id = $modelo->id;

        return $plan;
    }

    public function buscarPorId(int $id): DomainPlan
    {
        $modelo = EloquentPlan::find($id);

        if (!$modelo) {
            throw new PlanNoEncontradoException("El plan con ID $id no existe en el sistema.");
        }

        return new DomainPlan(
            id: $modelo->id,
            nombre: $modelo->nombre,
            nivel: $modelo->nivel,
            precio: (float) $modelo->precio,
            activo: (bool) $modelo->activo
        );
    }

    public function actualizar(DomainPlan $plan): void
    {
        $modelo = EloquentPlan::find($plan->id);

        $modelo->activo = $plan->activo;

        $modelo->save();
    }
}
