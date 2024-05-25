<?php

namespace App\Jobs;

use App\Integrations\Github\Exceptions\{RateLimitedExceededException, UserNotFoundException};
use App\Integrations\Github\GithubIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class GithubUserSaveJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $username,
    ) {
    }

    /**
     * @throws ConnectionException
     * @throws UserNotFoundException
     */
    public function handle(): void
    {
        try {

            $user = (new GithubIntegration())->getUser($this->username);

            // TODO: Save user to database

            GithubUserStarsUpdateJob::dispatch($user->login);

        } catch (RateLimitedExceededException $e) {
            $this->release($e->getRetryAfter());
        }
    }
}
