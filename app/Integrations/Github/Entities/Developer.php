<?php

namespace App\Integrations\Github\Entities;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

readonly class Developer
{
    public function __construct(
        public string     $login,
        public string     $name,
        public string     $avatarUrl,
        public string     $url,
        public ?string    $location,
        public int        $followers,
        public Collection $repositories,
        public int        $reposContributions,
        public ?string    $email = null,
        public ?string    $bio = null
    )
    {
    }

    public static function createFromGraphQL(array $data): self
    {
        return new self(
            login: $data['login'],
            name: $data['name'],
            avatarUrl: $data['avatarUrl'],
            url: $data['url'],
            location: $data['location'],
            followers: $data['followers']['totalCount'],
            repositories: self::getRepositories($data['repositories']['nodes']),
            reposContributions: $data['repositoriesContributedTo']['totalCount'],
            email: $data['email'] ?? null,
            bio: $data['bio'] ?? null,
        );
    }

    private static function getRepositories(array $repositories): Collection
    {
        if (sizeof($repositories) === 0) return collect();

        $collectionRepositories = collect();
        foreach ($repositories as $repo) {
            if (!is_null($repo['primaryLanguage'])) {
                $collectionRepositories->push(Repository::createFromGraphQL($repo));
            }
        }

        return $collectionRepositories;
    }

    public function hasAtLeast4RepositoriesInLanguages(array $languages): bool
    {
        if (sizeof($this->repositories) < 4) return false;

        if (sizeof($this->repositories) > 100) {
            Log::info('#### Developer has more than 100 repositories ####');
        }

        return collect($this->repositories)
                ->filter(fn(Repository $repo) => in_array(strtolower($repo->primaryLanguageName), $languages))
                ->count() >= 4;
    }

    public function calculateStars(): int
    {
        return $this->repositories->reduce(fn($carry, Repository $repo) => $carry + $repo->stargazers_count, 0);
    }

    public function calculateScore(int $stars, int $commitsInLastYear): int
    {
        return $this->followers * 0.4 +
            $this->repositories->count() * 0.3 +
            $stars * 0.5 +
            $commitsInLastYear * 0.4 +
            $this->reposContributions * 0.4;
    }
}