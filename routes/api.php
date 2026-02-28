<?php

use App\Http\Controllers\PlanController;
use App\Http\Controllers\RutinaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/planes', [PlanController::class, 'index']);

Route::post('/planes', [PlanController::class, 'store']);

Route::patch('/planes/{id}/archivar', [PlanController::class, 'archivar']);

Route::post('/rutinas', [RutinaController::class, 'store']);
Route::get('/rutinas', [RutinaController::class, 'index']); // <- Nueva ruta
