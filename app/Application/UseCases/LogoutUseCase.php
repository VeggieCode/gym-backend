<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\AuthRepositoryInterface;

class LogoutUseCase
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function ejecutar(): void
    {
        $this->authRepository->logout();
    }
}
