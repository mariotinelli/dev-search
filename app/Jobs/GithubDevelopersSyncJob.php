<?php

namespace App\Jobs;

use App\Integrations\Github\Exceptions\ErrorException;
use App\Integrations\Github\Exceptions\RateLimitedExceededException;
use App\Integrations\Github\GithubIntegration;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GithubDevelopersSyncJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string  $createdStart = '2008-01-01',
        private readonly string  $createdEnd = '2008-01-08',
        private readonly ?string $after = null,
    )
    {
        $this->onQueue('developers-sync');
    }

    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        try {
            $response = (new GithubIntegration())->searchUsers("{$this->createdStart}..{$this->createdEnd}", $this->after);

            GithubDevelopersConfigure::dispatch($response['data']['search']['edges']);

            $this->checkToDispatchGithubDevelopersSyncJob($response['data']['search']['pageInfo']);

        } catch (RateLimitedExceededException $e) {
            Log::error("RateLimitedExceededException ## Failed to sync developers");

            $this->release($e->getRetryAfter());
        } catch (ErrorException $e) {
            Log::error("GithubErrorException ## Failed to sync developers", [
                'errors' => $e->getErrors(),
                'response' => $e->getResponse(),
            ]);
        }
    }

    private function checkToDispatchGithubDevelopersSyncJob(array $pageInfo): void
    {
        if ($pageInfo['hasNextPage']) {
            GithubDevelopersSyncJob::dispatch($this->createdStart, $this->createdEnd, $pageInfo['endCursor']);
            return;
        }

        $newCreatedStart = Carbon::parse($this->createdEnd)->addDay()->format('Y-m-d');
        $newCreatedEnd = Carbon::parse($newCreatedStart)->addWeek()->format('Y-m-d');

        if ($newCreatedStart < Carbon::now()->format('Y-m-d')) {
            GithubDevelopersSyncJob::dispatch($newCreatedStart, $newCreatedEnd);
        }
    }

    public function tries(): int
    {
        return 5;
    }
}
