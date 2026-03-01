<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\RutinaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// ==========================================
// RUTAS PÚBLICAS
// ==========================================
Route::post('/login', [AuthController::class, 'login']);


// ==========================================
// RUTAS PROTEGIDAS (Requieren Token Sanctum)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutinas
    Route::post('/rutinas', [RutinaController::class, 'store']);
    Route::get('/rutinas', [RutinaController::class, 'index']);

    // Planes
    Route::get('/planes', [PlanController::class, 'index']);
    Route::post('/planes', [PlanController::class, 'store']);
    Route::patch('/planes/{id}/archivar', [PlanController::class, 'archivar']);
});
