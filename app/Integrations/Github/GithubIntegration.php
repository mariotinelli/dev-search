<?php

namespace App\Integrations\Github;

use App\Integrations\Github\Entities\{Repository, User};
use App\Integrations\Github\Exceptions\{AccessTokenNotFoundException, RateLimitedExceededException, UserNotFoundException};
use Illuminate\Http\Client\{ConnectionException, PendingRequest};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GithubIntegration
{
    private PendingRequest $api;

    /**
     * @throws AccessTokenNotFoundException
     */
    public function __construct()
    {
        if (!config('services.github.token')) {
            throw new AccessTokenNotFoundException();
        }

        $this->api = Http::baseUrl('https://api.github.com/')
            ->withHeaders([
                'Accept'        => 'application/vnd.github.v3+json',
                'Authorization' => 'Bearer ' . config('services.github.token'),
            ]);
    }

    /**
     * @throws UserNotFoundException
     * @throws ConnectionException
     * @throws RateLimitedExceededException
     */
    public function getUser(string $username): User
    {
        $response = $this->api->get("users/{$username}");

        if ($response->status() === 404) {
            throw new UserNotFoundException();
        }

        if ($response->status() === 403 && $response->header('X-RateLimit-Remaining') == 0) {
            throw new RateLimitedExceededException(retryAfter: $response->header('Retry-After'));
        }

        return User::createFromApi($response->json());
    }

    /**
     * @throws ConnectionException
     * @throws RateLimitedExceededException
     */
    public function searchUsers(
        string $expression = null,
        int    $page = 1,
        int    $perPage = 100
    ): Collection {
        $defaultExpression = "type:User+location:Brazil+location:Brasil";

        if ($expression) {
            $defaultExpression .= "+{$expression}";
        }

        $response = $this->api->get("search/users?q={$defaultExpression}&page={$page}&per_page={$perPage}");

        if ($response->status() === 403 && $response->header('X-RateLimit-Remaining') == 0) {
            throw new RateLimitedExceededException();
        }

        return collect($response->json()['items'])
            ->map(fn ($user) => User::createFromApi($user));
    }

    /**
     * @throws ConnectionException
     * @throws RateLimitedExceededException
     */
    public function getAllUserRepositories(string $username): Collection
    {
        $response = $this->api->get("users/{$username}/repos");

        if ($response->status() === 403 && $response->header('X-RateLimit-Remaining') === 0) {
            throw new RateLimitedExceededException();
        }

        return collect($response->json())
            ->map(fn ($repository) => Repository::createFromApi($repository));
    }

    /**
     * @throws ConnectionException
     * @throws RateLimitedExceededException
     */
    public function getUserStars(User $user): int
    {
        $repositories = $this->getAllUserRepositories($user->login);

        $stars = 0;

        foreach ($repositories as $repository) {
            $stars += $repository->stargazers_count;
        }

        return $stars;
    }
}
