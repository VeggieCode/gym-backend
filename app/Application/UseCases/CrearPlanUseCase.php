<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Plan;
use App\Domain\Exceptions\AccesoDenegadoException;
use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Repositories\PlanRepositoryInterface;

class CrearPlanUseCase
{
    public function __construct(private PlanRepositoryInterface $planRepository,
                                private AuthRepositoryInterface $authRepository
    ){}

    public function ejecutar(string $nombre, float $precio, int $duracionMeses, string $nivel): Plan
    {
        // 1. Regla de Autorización: Obtenemos al usuario actual
        $usuario = $this->authRepository->obtenerUsuarioActual();

        // 2. Si no hay usuario, o su rol no es 'dueño', lanzamos excepción
        if (!$usuario || $usuario->rol !== 'dueño') {
            throw new AccesoDenegadoException();
        }

        // 3. Lógica normal del caso de uso...
        $plan = new Plan(null, $nombre, $nivel, $precio );
        return $this->planRepository->guardar($plan);
    }
}
