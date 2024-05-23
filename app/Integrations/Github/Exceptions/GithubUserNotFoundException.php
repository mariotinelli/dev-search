<?php

namespace App\Integrations\Github\Exceptions;

use Exception;

class GithubUserNotFoundException extends Exception
{
    protected $message = 'Usuário não encontrado.';
}
