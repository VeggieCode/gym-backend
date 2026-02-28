<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Rutina as DomainRutina;

interface RutinaRepositoryInterface {
    public function guardar(DomainRutina $rutina): DomainRutina;

    public function obtenerTodas(): array;
}
