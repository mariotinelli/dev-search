<?php

namespace App\Jobs;

use App\Integrations\Github\Exceptions\GithubUserNotFoundException;
use App\Integrations\Github\GithubIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class PullGithubUserJob implements ShouldQueue
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
     * @throws GithubUserNotFoundException
     */
    public function handle(): void
    {
        $user = (new GithubIntegration())->getUser($this->username);

        PullGithubUserStarsJob::dispatch($user->login);
    }
}
