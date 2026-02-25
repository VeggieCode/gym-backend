<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Plan as DomainPlan;
use App\Domain\Exceptions\PlanNoEncontradoException;
use App\Domain\Repositories\PlanRepositoryInterface;

class MockPlanRepository implements PlanRepositoryInterface
{
    private array $planes = [];

    public function __construct()
    {
        $this->planes = [
            new DomainPlan(1, 'Plan Básico', 'Principiante', 29.99, true),
            new DomainPlan(3, 'Plan Élite', 'Intermedio', 39.99, false),
            new DomainPlan(2, 'Plan Espartano', 'Avanzado', 50.00, true),
        ];
    }

    public function obtenerActivos(): array
    {
        return array_values(array_filter($this->planes, fn (DomainPlan $plan) => $plan->activo));
    }

    public function guardar(DomainPlan $plan): DomainPlan
    {
        $this->planes[] = $plan;
        return $plan;
    }

    public function buscarPorId(int $id): DomainPlan
    {
        $modelo = $this->planes[$id] ?? null;

        if (!$modelo) {
            throw new PlanNoEncontradoException("El plan con ID $id no existe en el sistema.");
        }

        return new DomainPlan(
            id: $modelo->id,
            nombre: $modelo->nombre,
            nivel: $modelo->nivel,
            precio: (float)$modelo->precio,
            activo: (bool)$modelo->activo
        );
    }

    public function actualizar(DomainPlan $plan): void
    {
        $this->planes[$plan->id] = $plan;
    }
}
