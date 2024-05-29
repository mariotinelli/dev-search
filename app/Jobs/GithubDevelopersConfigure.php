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
    ) {
    }

    public function handle(): void
    {
        $developers = collect();

        foreach ($this->responseDevelopers as $developer) {
            if (!empty($developer['node']) && !is_null($developer['node']['name'])) {
                $developers->push(Developer::createFromApiWithGraphQL($developer['node']));
            }
        }

        foreach ($developers as $developer) {
            /** @var Developer $developer */
            if ($developer->hasAtLeast4RepositoriesInLanguages(['php', 'laravel', 'blade'])) {
                GithubDeveloperCheckHasActivitiesOnLastYearJob::dispatch($developer);
            }
        }
    }

    public function tries(): int
    {
        return 5;
    }
}
