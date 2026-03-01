<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Application\UseCases\LoginUseCase;
use App\Application\UseCases\LogoutUseCase;
use App\Domain\Exceptions\CredencialesInvalidasException;

class AuthController extends Controller
{
    public function login(Request $request, LoginUseCase $loginUseCase): JsonResponse
    {
        // 1. Validamos la entrada HTTP básica
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            // 2. Ejecutamos nuestra regla de negocio
            $resultado = $loginUseCase->ejecutar($request->email, $request->password);

            // 3. Formateamos la salida exitosa
            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso.',
                'data' => [
                    'token' => $resultado['token'],
                    'usuario' => [
                        'id' => $resultado['usuario']->id,
                        'nombre' => $resultado['usuario']->nombre,
                        'email' => $resultado['usuario']->email,
                        'rol' => $resultado['usuario']->rol,
                    ]
                ]
            ], 200);

        } catch (CredencialesInvalidasException $e) {
            // 4. Transformamos la excepción pura en una respuesta HTTP
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode()); // Devuelve 401
        }
    }

    public function logout(LogoutUseCase $logoutUseCase): JsonResponse
    {
        $logoutUseCase->ejecutar();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.'
        ], 200);
    }
}
