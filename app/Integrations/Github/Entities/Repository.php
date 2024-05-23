<?php

namespace App\Integrations\Github\Entities;

readonly class Repository
{
    public function __construct(
        public int     $stargazers_count,
        public ?string $language = null,
    )
    {
    }

    public static function createFromApi(array $data): self
    {
        return new self(
            stargazers_count: $data['stargazers_count'],
            language: $data['language'],
        );
    }
}
