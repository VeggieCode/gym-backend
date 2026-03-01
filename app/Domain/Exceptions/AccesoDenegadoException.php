<?php

namespace App\Domain\Exceptions;

class AccesoDenegadoException extends DomainException
{
    public function __construct(string $mensaje = "No tienes permisos para realizar esta acción. Solo el dueño del gimnasio puede hacerlo.")
    {
        parent::__construct($mensaje, 403);
    }
}
