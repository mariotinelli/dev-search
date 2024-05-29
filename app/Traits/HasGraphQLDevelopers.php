<?php

namespace App\Traits;

trait HasGraphQLDevelopers
{
    private function mountQueryForSearchDevelopers(): string
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

                                {$this->mountQueryFollowersForSearchDevelopers()}
                                {$this->mountQueryRepositoriesForSearchDevelopers()}
                                {$this->mountQueryRepositoriesContributedTosForSearchDevelopers()}
                            }
                        }
                    }
                }
            }
        GRAPHQL;
    }

    private function mountQueryFollowersForSearchDevelopers(): string
    {
        return "
            followers {
                totalCount
            }
        ";
    }

    private function mountQueryRepositoriesForSearchDevelopers(): string
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

    private function mountQueryRepositoriesContributedTosForSearchDevelopers(): string
    {
        return "
            repositoriesContributedTo {
                totalCount
            }
        ";
    }
}
