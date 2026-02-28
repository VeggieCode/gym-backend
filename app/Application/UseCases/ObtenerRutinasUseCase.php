<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\RutinaRepositoryInterface;

class ObtenerRutinasUseCase
{
    private $rutinaRepository;

    public function __construct(RutinaRepositoryInterface $rutinaRepository) {
        $this->rutinaRepository = $rutinaRepository;
    }

    public function ejecutar(): array {
        return $this->rutinaRepository->obtenerTodas();

    }

}
