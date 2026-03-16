<?php

namespace App\Domain\Exceptions;

class EntrenamientoActivoExistenteException extends DomainException
{
    public function __construct(string $mensaje = "Ya tienes un entrenamiento en curso. Finalízalo o descártalo antes de iniciar uno nuevo.")
    {
        parent::__construct($mensaje, 409);
    }
}
