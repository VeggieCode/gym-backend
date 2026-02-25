<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Domain\Exceptions\DomainException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // INTERCEPTOR GLOBAL DE EXCEPCIONES DE DOMINIO
        $exceptions->render(function (DomainException $e, Request $request) {
            // Si la petición espera un JSON (como nuestra API de React/Kotlin)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error_type' => class_basename($e), // Ej: "PrecioInvalidoException"
                    'message' => $e->getMessage()
                ], 400); // 400 Bad Request
            }
        });
    })->create();
