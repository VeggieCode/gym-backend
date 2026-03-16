<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Application\UseCases\IniciarEntrenamientoUseCase;
use Illuminate\Http\JsonResponse;
use Exception;

class EntrenamientoController extends Controller
{
    public function iniciar(Request $request, IniciarEntrenamientoUseCase $useCase): JsonResponse
    {
        // 1. Validación de entrada (Infraestructura HTTP)
        // Aseguramos que nos envíen un ID y que la rutina exista en la BD.
        $request->validate([
            'rutina_id' => 'required|integer|exists:rutinas,id'
        ]);

        try {
            // 2. Ejecutar el Caso de Uso
            $entrenamiento = $useCase->ejecutar($request->input('rutina_id'));

            // 3. Mapear la respuesta (Formateamos el Agregado de Dominio a un Array simple para el Frontend)
            $ejerciciosFormateados = array_map(function ($ejercicio) {
                return [
                    'id' => $ejercicio->id,
                    'ejercicio_original_id' => $ejercicio->ejercicioOriginalId,
                    'nombre' => $ejercicio->nombreSnapshot,
                    'tipo_registro' => $ejercicio->tipoRegistro->value,
                    'series' => [] // Al iniciar, las series están vacías esperando ser llenadas en vivo
                ];
            }, $entrenamiento->ejercicios);

            return response()->json([
                'success' => true,
                'message' => 'Entrenamiento iniciado correctamente.',
                'data' => [
                    'entrenamiento' => [
                        'id' => $entrenamiento->id,
                        'nombre' => $entrenamiento->nombre,
                        'fecha_inicio' => $entrenamiento->periodo->inicio->format('Y-m-d H:i:s'),
                        'ejercicios' => $ejerciciosFormateados
                    ]
                ]
            ], 201);

        } catch (Exception $e) {
            // Atrapamos EntrenamientoActivoExistenteException, RutinaNoEncontradaException, etc.
            $codigo = $e->getCode() !== 0 ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $codigo);
        }
    }
}
