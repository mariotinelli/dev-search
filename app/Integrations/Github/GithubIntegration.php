<?php

namespace App\Integrations\Github;

use App\Integrations\Github\Exceptions\{AccessTokenNotFoundException,
    DeveloperNotFoundException,
    ErrorException,
    RateLimitedExceededException};
use App\Traits\HasGraphQLDevelopers;
use Illuminate\Http\Client\{ConnectionException, PendingRequest};
use Illuminate\Support\Facades\{Http};

class GithubIntegration
{
    use HasGraphQLDevelopers;

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
     * @throws RateLimitedExceededException
     * @throws DeveloperNotFoundException
     * @throws ErrorException
     * @throws ConnectionException
     */
    public function getDeveloperEmail(string $username): string
    {
        $response = $this->api->get("users/$username");

        if ($response->status() === 404) {
            throw new DeveloperNotFoundException();
        }

        $this->handleErrors($response);

        return $response->json()['email'];
    }

    /**
     * @throws ConnectionException
     * @throws RateLimitedExceededException
     * @throws ErrorException
     */
    public function searchDevelopers(string $created, string $after = null): array
    {
        $response = $this->api->post('graphql', [
            'query'     => $this->mountQueryForSearchDevelopers(),
            'variables' => [
                'after' => $after,
                'query' => "location:Brazil location:Brasil language:php language:blade language:laravel created:$created",
            ],
        ]);

        $this->handleErrors($response);

        return $response->json();
    }

    /**
     * @throws RateLimitedExceededException
     * @throws ConnectionException
     * @throws ErrorException
     */
    public function getAllDeveloperCommitsOnTheLastYear(string $username): array
    {
        $query = <<<GRAPHQL
            query(\$login: String!, \$since: GitTimestamp!) {
                user(login: \$login) {
                    login
                    email
                    repositories(first: 100, orderBy: {field: STARGAZERS, direction: DESC}) {
                        totalCount
                        nodes {
                            name
                            defaultBranchRef {
                                target {
                                    ... on Commit {
                                        history(since: \$since) {
                                            totalCount
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        GRAPHQL;

        $response = $this->api->post('graphql', [
            'query'     => $query,
            'variables' => [
                'login' => $username,
                'since' => now()->subYear()->format('Y-m-d\TH:i:s'),
            ],
        ]);

        $this->handleErrors($response);

        return $response->json();
    }

    /**
     * @throws RateLimitedExceededException
     * @throws ErrorException
     */
    private function handleErrors($response): void
    {
        if ($response->status() === 403 && ($response->header('X-RateLimit-Remaining') == 0 || $response->header('Retry-After') !== null)) {
            throw new RateLimitedExceededException();
        }

        if ($response->status() === 502) {
            throw new ErrorException(errors: $response->json()['errors'], response: $response->json());
        }

        //        if ($response->status() === 200 && !isset($response->json()['data'])) {
        //            throw new ErrorException(response: $response->json());
        //        }
    }
}
