<?php

namespace App\Jobs;

use App\Models\Developer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class GithubDeveloperUpdateJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $username,
        private readonly int    $repos,
        private readonly int    $stars,
    )
    {
    }

    public function handle(): void
    {
        $developer = Developer::query()
            ->select(['id', 'login', 'repos', 'stars', 'followers', 'score'])
            ->where('login', $this->username)
            ->first();

        $stars = $developer->stars + $this->stars;
        $repos = $developer->repos + $this->repos;

        $developer->update([
            'repos' => $repos,
            'stars' => $stars,
            'score' => $this->calculeDeveloperScore($developer->followers, $stars, $repos),
        ]);
    }

    private function calculeDeveloperScore(int $followers, int $stars, int $repos): int
    {
        return ($stars * 1.5) + ($followers * 3) + ($repos * 1.3);
    }

    public function tries(): int
    {
        return 5;
    }
}
