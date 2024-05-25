<?php

namespace App\Console\Commands;

use App\Jobs\GithubUsersSyncJob;
use Illuminate\Console\Command;

class GithubUsersSyncCommand extends Command
{
    protected $signature = 'github-users-sync';

    protected $description = 'Sync users from Github API and store in database.';

    public function handle(): void
    {
        GithubUsersSyncJob::dispatch();
    }
}
