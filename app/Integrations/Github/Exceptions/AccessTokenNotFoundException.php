<?php

namespace App\Integrations\Github\Exceptions;

use Exception;

class AccessTokenNotFoundException extends Exception
{
    protected $message = 'Github token não encontrado.';
}
