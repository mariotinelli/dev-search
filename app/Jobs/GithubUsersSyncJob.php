<?php

namespace App\Jobs;

use App\Integrations\Github\Exceptions\RateLimitedExceededException;
use App\Integrations\Github\GithubIntegration;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class GithubUsersSyncJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly int    $page = 1,
        private readonly string $locationExpr = 'location:Brazil+location:Brasil',
        private readonly string $createdStart = '2008-01-01',
        private readonly string $createdEnd = '2008-01-08',
    ) {
    }

    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $expression = "{$this->locationExpr}+type:User+created:{$this->createdStart}..{$this->createdEnd}";

        try {
            $users = (new GithubIntegration())->searchUsers($expression, $this->page);

            foreach ($users as $user) {
                GithubUserSaveJob::dispatch($user->login);
            }

            if (sizeof($users) > 99) {
                GithubUsersSyncJob::dispatch($this->page + 1);
            } else {

                $newCreatedStart = Carbon::parse($this->createdEnd)->addDay()->format('Y-m-d');
                $newCreatedEnd   = Carbon::parse($newCreatedStart)->addWeek()->format('Y-m-d');

                if ($newCreatedStart < Carbon::now()->format('Y-m-d')) {
                    GithubUsersSyncJob::dispatch(createdStart: $newCreatedStart, createdEnd: $newCreatedEnd);
                }

            }

        } catch (RateLimitedExceededException $e) {
            $this->release($e->getRetryAfter());
        }
    }
}
