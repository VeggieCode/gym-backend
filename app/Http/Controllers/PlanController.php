<?php

namespace App\Http\Controllers;

use App\Application\UseCases\ArchivarPlanUseCase;
use App\Application\UseCases\CrearPlanUseCase;
use App\Application\UseCases\ObtenerPlanesActivosUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    private ObtenerPlanesActivosUseCase $useCase;

    public function __construct(ObtenerPlanesActivosUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function index(): JsonResponse
    {
        $planes = $this->useCase->ejecutar();

        // Convertimos las Entidades Puras a un formato de array para el JSON
        $planesArray = array_map(function($plan) {
            return $plan->toArray();
        }, $planes);

        return response()->json([
            'success' => true,
            'data' => $planesArray
        ], 200);
    }

    public function store(Request $request, CrearPlanUseCase $useCase): JsonResponse
    {
        // 1. Validamos formato (Infraestructura HTTP)
        $request->validate([
            'nombre' => 'required|string',
            'nivel' => 'required|string',
            'precio' => 'required|numeric'
        ]);

        // 2. Ejecutamos el caso de uso.
        // Si la Entidad falla, lanzará la DomainException.
        // Laravel la atrapará en bootstrap/app.php automáticamente.
        $nuevoPlan = $useCase->ejecutar(
            $request->input('nombre'),
            $request->input('nivel'),
            $request->input('precio')
        );

        // 3. Si todo salió bien, devolvemos el éxito.
        return response()->json([
            'success' => true,
            'message' => 'Plan creado exitosamente',
            'data' => $nuevoPlan->toArray()
        ], 201);
    }

    public function archivar(int $id, ArchivarPlanUseCase $useCase): JsonResponse
    {
        $useCase->ejecutar($id);

        return response()->json([
            'success' => true,
            'message' => "El plan ha sido archivado correctamente."
        ], 200);
    }
}
