<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\PlanRepositoryInterface;

class ObtenerPlanesActivosUseCase
{
    private PlanRepositoryInterface $repository;

    public function __construct(PlanRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function ejecutar(): array
    {
        return $this->repository->obtenerActivos();
    }
}
