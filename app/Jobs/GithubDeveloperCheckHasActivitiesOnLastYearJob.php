<?php

namespace App\Jobs;

use App\Integrations\Github\Entities\Developer;
use App\Integrations\Github\Exceptions\{ErrorException, RateLimitedExceededException};
use App\Integrations\Github\GithubIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, MaxAttemptsExceededException, SerializesModels};
use Illuminate\Support\Facades\Log;
use Throwable;

class GithubDeveloperCheckHasActivitiesOnLastYearJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Developer $developer,
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {

        try {
            $response = (new GithubIntegration())->getAllDeveloperCommitsOnTheLastYear($this->developer->login);

            $responseDeveloper = $response['data']['user'];

            if ($responseDeveloper['repositories']['totalCount'] > 0) {
                $commitsInLastYear = array_sum(array_map(function ($repo) {
                    if (!isset($repo['defaultBranchRef']['target']['history']['totalCount'])) {
                        return 0;
                    }

                    return $repo['defaultBranchRef']['target']['history']['totalCount'];
                }, $responseDeveloper['repositories']['nodes']));

                if ($commitsInLastYear > 0) {
                    GithubDeveloperSaveJob::dispatch($this->developer, $commitsInLastYear);
                }
            }

        } catch (RateLimitedExceededException $e) {
            Log::error("RateLimitedExceededException ## Failed to check if developer has activities on last year: {$this->developer->login}");

            $this->release($e->getRetryAfter());
        } catch (ErrorException $e) {
            Log::error("GithubErrorException ## Failed to check if developer has activities on last year: {$this->developer->login}", [
                'errors' => $e->getErrors(),
                'response' => $e->getResponse(),
            ]);
        }
    }

    public function tries(): int
    {
        return 5;
    }

    public function failed(?Throwable $exception): void
    {
        if ($exception instanceof MaxAttemptsExceededException) {
            Log::error("MaxAttemptsExceededException ## Failed to check if developer has activities on last year: {$this->developer->login}");
        }

    }
}
