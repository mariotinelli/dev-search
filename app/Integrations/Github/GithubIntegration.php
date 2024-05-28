<?php

namespace App\Integrations\Github;

use App\Integrations\Github\Exceptions\{AccessTokenNotFoundException, ErrorException, RateLimitedExceededException};
use Illuminate\Http\Client\{ConnectionException, PendingRequest};
use Illuminate\Support\Facades\{Http, Log};

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
                'Accept' => 'application/vnd.github.v3+json',
                'Authorization' => 'Bearer ' . config('services.github.token'),
            ]);
    }

    /**
     * @throws ConnectionException
     * @throws RateLimitedExceededException
     * @throws ErrorException
     */
    public function searchUsers(string $created, string $after = null): array
    {
        $response = $this->api->post('graphql', [
            'query' => $this->mountQueryForSearchUsers(),
            'variables' => [
                'after' => $after,
                'query' => "location:Brazil location:Brasil language:php language:blade language:laravel created:$created",
            ]
        ]);

        if ($response->status() === 403 && ($response->header('X-RateLimit-Remaining') == 0 || $response->header('Retry-After') !== null)) {
            throw new RateLimitedExceededException();
        }

        if ($response->status() === 502) {
            throw new ErrorException(errors: $response->json()['errors'], response: $response->json());
        }

        if ($response->status() === 200 && !isset($response->json()['data'])) {
            throw new ErrorException(response: $response->json());
        }

        $responseSearch = $response->json()['data']['search'];

        if ($responseSearch['userCount'] > 1000) Log::info('Alert ## The search returned more than 1000 results');

        return $response->json();

//        $users = collect();
//        foreach ($responseSearch['edges'] as $user) {
//            if (!empty($user['node']) && !is_null($user['node']['name'])) {
//                $users->push(User::createFromGraphQL($user['node']));
//            }
//        }
//
//        return collect([
//            'users' => $users,
//            'pageInfo' => $responseSearch['pageInfo'],
//        ]);
    }

    /**
     * @throws RateLimitedExceededException
     * @throws ConnectionException
     * @throws ErrorException
     */
    public function getAllUserCommitsOnTheLastYear(string $username): array
    {
        $query = <<<GRAPHQL
            query(\$login: String!, \$since: GitTimestamp!) {
                user(login: \$login) {
                    login
                    repositories(first: 100) {
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
            'query' => $query,
            'variables' => [
                'login' => $username,
                'since' => now()->subYear()->format('Y-m-d\TH:i:s'),
            ]
        ]);

        if ($response->status() === 403 && ($response->header('X-RateLimit-Remaining') == 0 || $response->header('Retry-After') !== null)) {
            throw new RateLimitedExceededException();
        }

        if ($response->status() === 502) {
            throw new ErrorException(errors: $response->json()['errors'], response: $response->json());
        }

        if ($response->status() === 200 && !isset($response->json()['data'])) {
            throw new ErrorException(response: $response->json());
        }

        return $response->json();
    }


    /* ############  Mount Query  ############ */

    private function mountQueryForSearchUsers(): string
    {
        return <<<GRAPHQL
            query(\$query: String!, \$after: String) {
                search(query: \$query, type: USER, after: \$after, first: 100) {
                    userCount
                    pageInfo {
                        endCursor
                        hasNextPage
                    }
                    edges {
                        node {
                            ... on User {
                                login
                                name
                                email
                                avatarUrl
                                url
                                bio
                                location

                                {$this->mountQueryFollowersForSearchUsers()}
                                {$this->mountQueryRepositoriesForSearchUsers()}
                                {$this->mountQueryRepositoriesContributedTosForSearchUsers()}
                            }
                        }
                    }
                }
            }
        GRAPHQL;
    }

    private function mountQueryFollowersForSearchUsers(): string
    {
        return "
            followers {
                totalCount
            }
        ";
    }

    private function mountQueryRepositoriesForSearchUsers(): string
    {
        return "
            repositories (first: 100, isFork: false) {
                totalCount
                nodes {
                    stargazerCount

                    primaryLanguage {
                        name
                    }
                }
            }
        ";
    }

    private function mountQueryRepositoriesContributedTosForSearchUsers(): string
    {
        return "
            repositoriesContributedTo {
                totalCount
            }
        ";
    }
}
