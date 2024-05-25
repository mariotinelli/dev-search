<?php

namespace App\Jobs;

use App\Integrations\Github\Exceptions\RateLimitedExceededException;
use App\Integrations\Github\GithubIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class GithubUserStarsUpdateJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $username
    ) {
    }

    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        try {
            $repositories = (new GithubIntegration())->getAllUserRepositories($this->username);

            $stars = 0;

            foreach ($repositories as $repository) {
                $stars += $repository->stargazers_count;
            }

            // TODO: Update user stars in database

        } catch (RateLimitedExceededException $e) {
            $this->release($e->getRetryAfter());
        }
    }
}
