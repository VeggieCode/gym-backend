<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Entities\Usuario;
use App\Domain\Exceptions\CredencialesInvalidasException;
use App\Models\User as EloquentUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EloquentAuthRepository implements AuthRepositoryInterface
{
    /**
     * @throws CredencialesInvalidasException
     */
    public function login(string $email, string $password): string
    {
        $user = EloquentUser::where('email', $email)->first();

        // Verificamos si el usuario existe y la contraseña es correcta
        if (!$user || !Hash::check($password, $user->password)) {
            throw new CredencialesInvalidasException();
        }

        // Revocamos tokens anteriores por seguridad (Opcional, pero buena práctica)
        $user->tokens()->delete();

        //Al ser una petición sin estado debemos indicarle a Laravel que el usuario ya está autenticado
        Auth::setUser($user);

        // Generamos un nuevo token de Sanctum
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function logout(): void
    {
        // Laravel Sanctum guarda el token en el request autenticado
        $user = Auth::user();
        if ($user) {
            $user->currentAccessToken()->delete();
        }
    }

    public function obtenerUsuarioActual(): ?Usuario
    {
        $user = Auth::user();
        if (!$user) return null;

        return new Usuario(
            $user->id,
            $user->name,
            $user->email,
            $user->role
        );
    }
}
