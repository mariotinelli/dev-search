<?php

namespace App\Jobs;

use App\Integrations\Github\Entities\Developer as GithubDeveloper;
use App\Models\Developer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class GithubDeveloperSaveJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly GithubDeveloper $githubDeveloper,
        public readonly int             $commitsInLastYear,
    )
    {
    }

    public function handle(): void
    {
        $stars = $this->githubDeveloper->calculateStars();

        Developer::query()
            ->updateOrCreate(['login' => $this->githubDeveloper->login], [
                'name' => $this->githubDeveloper->name,
                'avatar_url' => $this->githubDeveloper->avatarUrl,
                'url' => $this->githubDeveloper->url,
                'location' => $this->githubDeveloper->location,
                'followers' => $this->githubDeveloper->followers,
                'repos' => $this->githubDeveloper->repositories->count(),
                'stars' => $stars,
                'commits' => $this->commitsInLastYear,
                'repos_contributions' => $this->githubDeveloper->reposContributions,
                'email' => $this->githubDeveloper->email ?? null,
                'bio' => $this->githubDeveloper->bio ?? null,
                'score' => $this->githubDeveloper->calculateScore($stars, $this->commitsInLastYear)
            ]);
    }
}
