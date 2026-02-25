<?php

// app/Domain/Exceptions/DomainException.php
namespace App\Domain\Exceptions;

use Exception;

abstract class DomainException extends Exception
{
    // Esta clase base nos servirá más adelante para atrapar
    // TODOS los errores de negocio de un solo golpe.
}
