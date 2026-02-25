<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\PlanRepositoryInterface;

class ArchivarPlanUseCase
{
    private PlanRepositoryInterface $repository;

    public function __construct(PlanRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function ejecutar(int $planId): void
    {
        // 1. Obtenemos la Entidad Pura
        $plan = $this->repository->buscarPorId($planId);

        // 2. Ejecutamos el comportamiento (Regla de negocio)
        // Si el plan ya estaba inactivo, lanzará PlanYaInactivoException y se detendrá.
        $plan->archivar();

        // 3. Persistimos el nuevo estado
        $this->repository->actualizar($plan);
    }
}
