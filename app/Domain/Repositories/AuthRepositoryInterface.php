<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Usuario;
use App\Domain\Exceptions\CredencialesInvalidasException;

interface AuthRepositoryInterface
{
    /**
     * Intenta autenticar al usuario y devuelve un token de acceso.
     * @throws CredencialesInvalidasException
     */
    public function login(string $email, string $password): string;

    /**
     * Cierra la sesión del usuario actual (revoca el token).
     */
    public function logout(): void;

    /**
     * Obtiene el usuario autenticado actualmente.
     */
    public function obtenerUsuarioActual(): ?Usuario;
}
