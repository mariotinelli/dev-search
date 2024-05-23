<?php

namespace App\Jobs;

use App\Integrations\Github\GithubIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PullGithubUserStarsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $username
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $repositories = (new GithubIntegration())->getAllUserRepositories($this->username);

        foreach ($repositories as $repository) {
            // Do something with the repository
        }
    }
}
