<?php

namespace App\Jobs;

use App\Integrations\Github\Exceptions\{RateLimitedExceededException};
use App\Integrations\Github\GithubIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class GithubCheckDeveloperHasActivitiesOnLastYearJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $username,
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        try {
            $hasActivitiesOnLastYear = (new GithubIntegration())->checkIfUserHasActivitiesInTheLastYear($this->username);

            if ($hasActivitiesOnLastYear) {
                GithubCheckDeveloperHasAtLeast4RepositoriesPhpLanguageJob::dispatch($this->username);
            }

        } catch (RateLimitedExceededException $e) {
            $this->release($e->getRetryAfter());
        }
    }

    public function tries(): int
    {
        return 5;
    }
}
