<?php

namespace App\Jobs;

use App\Integrations\Github\Entities\Developer as GithubDeveloper;
use App\Integrations\Github\Exceptions\{DeveloperNotFoundException, ErrorException, RateLimitedExceededException};
use App\Integrations\Github\GithubIntegration;
use App\Models\Developer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GithubDeveloperSaveJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly GithubDeveloper $githubDeveloper,
        public readonly int             $commitsInLastYear,
    ) {
    }

    /**
     * @throws ConnectionException
     */
    public function handle(): void
    {
        try {
            $developerEmail = (new GithubIntegration())->getDeveloperEmail($this->githubDeveloper->login);

            $this->saveDeveloper($developerEmail);

        } catch (DeveloperNotFoundException) {

            $this->saveDeveloper($this->githubDeveloper->email);

        } catch (RateLimitedExceededException $e) {

            $this->release($e->getRetryAfter());

        } catch (ErrorException $errorException) {
            Log::error("GithubErrorException ## Failed to check if developer has activities on last year: {$this->githubDeveloper->login}", [
                'errors'   => $errorException->getErrors(),
                'response' => $errorException->getResponse(),
            ]);
        }
    }

    private function saveDeveloper(?string $developerEmail = null): void
    {
        $stars = $this->githubDeveloper->calculateStars();

        Developer::query()
            ->updateOrCreate(['login' => $this->githubDeveloper->login], [
                'name'                => $this->githubDeveloper->name,
                'email'               => !empty($developerEmail) || !is_null($developerEmail) ? $developerEmail : "Não informado",
                'avatar_url'          => $this->githubDeveloper->avatarUrl,
                'url'                 => $this->githubDeveloper->url,
                'location'            => $this->githubDeveloper->location,
                'followers'           => $this->githubDeveloper->followers,
                'repos'               => $this->githubDeveloper->repositories->count(),
                'stars'               => $stars,
                'commits'             => $this->commitsInLastYear,
                'repos_contributions' => $this->githubDeveloper->reposContributions,
                'bio'                 => $this->githubDeveloper->bio ?? null,
                'score'               => $this->githubDeveloper->calculateScore($stars, $this->commitsInLastYear),
            ]);
    }
}
