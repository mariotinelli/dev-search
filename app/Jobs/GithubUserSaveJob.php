<?php

namespace App\Jobs;

use App\Integrations\Github\Exceptions\{RateLimitedExceededException, UserNotFoundException};
use App\Integrations\Github\GithubIntegration;
use App\Models\Developer;
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

            Developer::updateOrCreate(
                ['login' => $user->login],
                [
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'location'   => $user->location,
                    'bio'        => $user->bio,
                    'avatar_url' => $user->avatarUrl,
                    'html_url'   => $user->htmlUrl,
                    'followers'  => $user->followers,
                ]
            );

            GithubDeveloperRepositoriesJob::dispatch($user->login);

        } catch (RateLimitedExceededException $e) {
            $this->release($e->getRetryAfter());
        }
    }
}
