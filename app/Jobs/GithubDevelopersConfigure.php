<?php

namespace App\Jobs;

use App\Integrations\Github\Entities\Developer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class GithubDevelopersConfigure implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly array $responseDevelopers
    )
    {
    }

    public function handle(): void
    {
        foreach ($this->responseDevelopers as $developer) {
            if (!empty($developer['node']) && !is_null($developer['node']['name'])) {
                GithubDeveloperCheckHasActivitiesOnLastYearJob::dispatch(Developer::createFromApiWithGraphQL($developer['node']));
            }
        }
    }

    public function tries(): int
    {
        return 5;
    }
}
