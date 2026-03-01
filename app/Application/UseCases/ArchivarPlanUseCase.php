<?php

namespace App\Application\UseCases;

use App\Domain\Exceptions\AccesoDenegadoException;
use App\Domain\Exceptions\PlanYaInactivoException;
use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Repositories\PlanRepositoryInterface;

class ArchivarPlanUseCase
{
    private PlanRepositoryInterface $repository;

    private AuthRepositoryInterface $authRepository;

    public function __construct(PlanRepositoryInterface $repository, AuthRepositoryInterface $authRepository)
    {
        $this->repository = $repository;
        $this->authRepository = $authRepository;
    }

    /**
     * @throws AccesoDenegadoException
     */
    public function ejecutar(int $planId): void
    {
        // 1. Regla de Autorización: Obtenemos el usuario actual
        $usuario = $this->authRepository->obtenerUsuarioActual();

        // 2. Si no hay usuario, o su rol no es 'dueño', lanzamos excepción
        if (!$usuario || $usuario->rol !== 'dueño') {
            throw new AccesoDenegadoException();
        }

        // 1. Obtenemos la Entidad Pura
        $plan = $this->repository->buscarPorId($planId);

        // 2. Ejecutamos el comportamiento (Regla de negocio)
        // Si el plan ya estaba inactivo, lanzará PlanYaInactivoException y se detendrá.
        $plan->archivar();

        // 3. Persistimos el nuevo estado
        $this->repository->actualizar($plan);
    }
}
