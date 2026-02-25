<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Plan;

interface PlanRepositoryInterface
{
    public function obtenerActivos(): array;
    public function guardar(Plan $plan): Plan;
    public function buscarPorId(int $id): Plan;
    public function actualizar(Plan $plan): void;
}
