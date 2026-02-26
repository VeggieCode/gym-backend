<?php

namespace App\Http\Controllers;

use App\Application\UseCases\CrearRutinaUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RutinaController extends Controller
{
    public function store(Request $request, CrearRutinaUseCase $useCase): JsonResponse
    {
        // Validamos la estructura del JSON que nos envía el cliente
        $request->validate([
            'nombre' => 'required|string',
            'dias_asignados' => 'required|array',
            'dias_asignados.*' => 'string', // Cada elemento debe ser string
            'ejercicios' => 'required|array',
            'ejercicios.*.nombre' => 'required|string',
            'ejercicios.*.series' => 'required|integer',
            'ejercicios.*.repeticiones' => 'required|integer',
        ]);

        $rutina = $useCase->ejecutar(
            $request->input('nombre'),
            $request->input('dias_asignados'),
            $request->input('ejercicios')
        );

        return response()->json([
            'success' => true,
            'message' => 'Rutina creada exitosamente',
            'data' => $rutina->toArray()
        ], 201);
    }
}
