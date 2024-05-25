<?php

namespace App\Jobs;

use App\Integrations\Github\Exceptions\GithubUserNotFoundException;
use App\Integrations\Github\GithubIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class PullGithubUsersJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
    }

    /**
     * @throws ConnectionException
     * @throws GithubUserNotFoundException
     */
    public function handle(): void
    {
        $users = (new GithubIntegration())->getAllUsers();

        foreach ($users as $user) {
            PullGithubUserJob::dispatch($user->login);
        }
    }
}
