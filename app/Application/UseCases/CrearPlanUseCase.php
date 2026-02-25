<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Plan;
use App\Domain\Repositories\PlanRepositoryInterface;

class CrearPlanUseCase
{
    private PlanRepositoryInterface $repository;

    public function __construct(PlanRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function ejecutar(string $nombre, string $nivel, float $precio): Plan
    {
        // 1. Creamos la entidad en memoria (El ID es null porque es nuevo).
        // Si el precio es negativo, aquí saltará la excepción y se detendrá el proceso.
        $plan = new Plan(null, $nombre, $nivel, $precio);

        // 2. Le decimos a la infraestructura que lo guarde físicamente.
        return $this->repository->guardar($plan);
    }
}
