<?php

namespace App\Integrations\Github\Exceptions;

use Exception;
use Throwable;

class RateLimitedExceededException extends Exception
{
    protected $message = 'Limite de requisições excedido.';

    protected int $retryAfter = 60;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, int $retryAfter = 60)
    {
        parent::__construct($message, $code, $previous);
        $this->retryAfter = $retryAfter;
    }

    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}
