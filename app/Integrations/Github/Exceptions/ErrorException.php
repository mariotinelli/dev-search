<?php

namespace App\Integrations\Github\Exceptions;

use Exception;
use Throwable;

class ErrorException extends Exception
{
    protected $message = 'Erro ao processar a requisição.';

    protected array $errors = [];

    protected ?array $response = null;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, array $errors = [], ?array $response = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
        $this->response = $response;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }
}
