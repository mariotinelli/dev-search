<?php

namespace App\Console\Commands;

use App\Jobs\GithubDevelopersSyncJob;
use Illuminate\Console\Command;

class GithubDevelopersSyncCommand extends Command
{
    protected $signature = 'github-developers-sync';

    protected $description = 'Sync users from Github API and store in database.';

    public function handle(): void
    {
        GithubDevelopersSyncJob::dispatch();
    }
}
