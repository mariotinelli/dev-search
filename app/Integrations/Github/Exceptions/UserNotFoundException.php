<?php

namespace App\Integrations\Github\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    protected $message = 'Usuário não encontrado.';
}
