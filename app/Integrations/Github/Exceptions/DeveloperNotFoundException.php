<?php

namespace App\Integrations\Github\Exceptions;

use Exception;

class DeveloperNotFoundException extends Exception
{
    protected $message = 'Desenvolvedor não encontrado.';
}
