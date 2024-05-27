<?php

namespace App\Integrations\Github\Entities;

readonly class User
{
    public function __construct(
        public string  $login,
        public string  $avatarUrl,
        public string  $htmlUrl,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $bio = null,
        public ?string $location = null,
        public ?int    $followers = null,
        public ?int    $stars = null,
    )
    {
    }

    public static function createFromApi(array $data): self
    {
        return new self(
            login: $data['login'],
            avatarUrl: $data['avatar_url'],
            htmlUrl: $data['html_url'],
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            bio: $data['bio'] ?? null,
            location: $data['location'] ?? null,
            followers: $data['followers'] ?? null,
            stars: $data['stars'] ?? null,
        );
    }
}
