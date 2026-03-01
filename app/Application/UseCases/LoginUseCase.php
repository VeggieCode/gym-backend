<?php

namespace App\Application\UseCases;

use App\Domain\Exceptions\CredencialesInvalidasException;
use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Entities\Usuario;

class LoginUseCase
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @return array{token: string, usuario: Usuario}
     * @throws CredencialesInvalidasException
     */
    public function ejecutar(string $email, string $password): array
    {
        // 1. Delegamos la autenticación a la infraestructura (Lanzará excepción si falla)
        $token = $this->authRepository->login($email, $password);

        // 2. Obtenemos la entidad pura del usuario recién logueado
        $usuario = $this->authRepository->obtenerUsuarioActual();

        // 3. Devolvemos el "Payload" que el controlador enviará a React/Android
        return [
            'token' => $token,
            'usuario' => $usuario
        ];
    }
}
