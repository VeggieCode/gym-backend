<?php

namespace App\Domain\Exceptions;

class SerieInvalidaException extends DomainException
{
    public function __construct(string $mensaje)
    {
        parent::__construct($mensaje, 400); // 400 Bad Request
    }

}
