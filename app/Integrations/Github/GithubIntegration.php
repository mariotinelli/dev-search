<?php

namespace App\Integrations\Github;

use App\Integrations\Github\Entities\{Repository, User};
use App\Integrations\Github\Exceptions\GithubUserNotFoundException;
use Illuminate\Http\Client\{ConnectionException, PendingRequest};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GithubIntegration
{
    private PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::baseUrl('https://api.github.com/');
    }

    /**
     * @throws GithubUserNotFoundException
     * @throws ConnectionException
     */
    public function getUser(string $username): User
    {
        $response = $this->api->get("users/{$username}");

        if ($response->status() === 404) {
            throw new GithubUserNotFoundException();
        }

        return User::createFromApi($response->json());
    }

    /**
     * @throws ConnectionException
     */
    public function getAllUsers(): Collection
    {
        $response = $this->api->get('users');

        return collect($response->json())
            ->map(fn ($user) => User::createFromApi($user));
    }

    /**
     * @throws ConnectionException
     */
    public function getAllUserRepositories(string $username): Collection
    {
        $response = $this->api->get("users/{$username}/repos");

        return collect($response->json())
            ->map(fn ($repository) => Repository::createFromApi($repository));
    }
}
