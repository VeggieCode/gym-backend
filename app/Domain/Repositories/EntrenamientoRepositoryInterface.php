<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Entrenamiento;

interface EntrenamientoRepositoryInterface
{
    // Guarda el agregado completo (Entrenamiento + Ejercicios + Series)
    public function guardar(Entrenamiento $entrenamiento): Entrenamiento;

    // Nos permite saber si el usuario dejó un entrenamiento a medias
    public function obtenerActivoPorUsuario(int $usuarioId): ?Entrenamiento;

}
