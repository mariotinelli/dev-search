<?php

namespace App\Jobs;

use App\Integrations\Github\Exceptions\RateLimitedExceededException;
use App\Integrations\Github\GithubIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Collection;

class GithubDeveloperRepositoriesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $username,
        private readonly int    $page = 1,
        private readonly int    $perPage = 100,
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        try {
            $repositories = (new GithubIntegration())->getAllUserRepositories($this->username);

            $stars = $this->calculeDeveloperStars($repositories);

            GithubDeveloperUpdateJob::dispatch($this->username, sizeof($repositories), $stars);

            if (sizeof($repositories) > 99) {
                GithubDeveloperRepositoriesJob::dispatch($this->username, $this->page + 1);
            }

        } catch (RateLimitedExceededException $e) {
            $this->release($e->getRetryAfter());
        }
    }

    private function calculeDeveloperStars(Collection $repositories): int
    {
        $stars = 0;

        foreach ($repositories as $repository) {
            $stars += $repository->stargazers_count;
        }

        return $stars;
    }
}
