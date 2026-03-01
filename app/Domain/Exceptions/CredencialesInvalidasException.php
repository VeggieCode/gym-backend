<?php

namespace App\Domain\Exceptions;

class CredencialesInvalidasException extends DomainException
{
    public function __construct()
    {
        parent::__construct("El correo o la contraseña son incorrectos.", 401);
    }
}
