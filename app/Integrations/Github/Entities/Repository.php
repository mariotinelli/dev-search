<?php

namespace App\Integrations\Github\Entities;

readonly class Repository
{
    public function __construct(
        public int    $stargazers_count,
        public string $primaryLanguageName,
    )
    {
    }

    public static function createFromGraphQL(array $data): self
    {
        return new self(
            stargazers_count: $data['stargazerCount'],
            primaryLanguageName: $data['primaryLanguage']['name'],
        );
    }
}
