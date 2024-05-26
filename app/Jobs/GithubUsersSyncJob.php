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
use Illuminate\Support\Collection;

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
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $expression = "{$this->locationExpr}+type:User+created:{$this->createdStart}..{$this->createdEnd}";

        try {
            $users = (new GithubIntegration())->searchUsers($expression, $this->page);

            $this->dispatchGithubUserSaveJob($users);

            $this->checkToDispatchGithubUsersSyncJob($users);

        } catch (RateLimitedExceededException $e) {
            $this->release($e->getRetryAfter());
        }
    }

    /**
     * @throws RateLimitedExceededException
     * @throws ConnectionException
     */
    private function dispatchGithubUserSaveJob(Collection $users): void
    {
        foreach ($users as $user) {
            $hasActivitiesOnLastYear = (new GithubIntegration())->checkIfUserHasActivitiesInTheLastYear($user->login);

            if ($hasActivitiesOnLastYear) {
                GithubUserSaveJob::dispatch($user->login);
            }
        }
    }

    private function checkToDispatchGithubUsersSyncJob(Collection $users): void
    {
        if (sizeof($users) > 99) {
            GithubUsersSyncJob::dispatch($this->page + 1);

            return;
        }

        $newCreatedStart = Carbon::parse($this->createdEnd)->addDay()->format('Y-m-d');
        $newCreatedEnd = Carbon::parse($newCreatedStart)->addWeek()->format('Y-m-d');

        if ($newCreatedStart < Carbon::now()->format('Y-m-d')) {
            GithubUsersSyncJob::dispatch(createdStart: $newCreatedStart, createdEnd: $newCreatedEnd);
        }
    }
}
