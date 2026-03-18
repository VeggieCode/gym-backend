<?php

namespace App\Domain\Exceptions;

class RutinaNoEncontradaException extends DomainException
{
    public function __construct(string $mensaje = "La rutina solicitada no existe.")
    {
        parent::__construct($mensaje, 404);
    }
}