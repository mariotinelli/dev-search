<?php

namespace App\Jobs;

use App\Integrations\Github\Exceptions\{RateLimitedExceededException};
use App\Integrations\Github\GithubIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, MaxAttemptsExceededException, SerializesModels};

class GithubCheckDeveloperHasAtLeast4RepositoriesPhpLanguageJob implements ShouldQueue
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
            $hasAtLeast4RepositoriesPhpLanguage = (new GithubIntegration())->checkIfUserHasAtLeast4RepositoriesPhpLanguage($this->username);

            if ($hasAtLeast4RepositoriesPhpLanguage) {
                GithubDeveloperSaveJob::dispatch($this->username);
            }

        } catch (RateLimitedExceededException $e) {
            $this->release($e->getRetryAfter());
        } catch (MaxAttemptsExceededException $e) {
            $this->release(60);
        }
    }

    public function tries(): int
    {
        return 5;
    }
}
