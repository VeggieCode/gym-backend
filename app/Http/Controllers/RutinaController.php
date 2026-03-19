<?php

namespace App\Http\Controllers;

use App\Application\UseCases\CrearRutinaUseCase;
use App\Application\UseCases\ObtenerRutinasUseCase;
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
            'ejercicios.*.grupo_muscular' => 'required|string',
            'ejercicios.*.tipo_registro' => 'string',

            'ejercicios.*.series_objetivo' => 'present|array',
            'ejercicios.*.series_objetivo.*.peso' => 'nullable|numeric',
            'ejercicios.*.series_objetivo.*.repeticiones' => 'nullable|integer',
            'ejercicios.*.series_objetivo.*.tiempo_segundos' => 'nullable|integer',
            'ejercicios.*.series_objetivo.*.distancia_metros' => 'nullable|numeric'
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

    public function index(ObtenerRutinasUseCase $useCase): JsonResponse
    {
        $rutinas = $useCase->ejecutar();

        // Convertimos cada entidad de dominio a un array usando su método toArray()
        $data = array_map(function ($rutina) {
            return $rutina->toArray();
        }, $rutinas);

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }
}
