<?php

namespace App\Console\Commands;

use App\Jobs\GithubDevelopersSyncJob;
use Illuminate\Console\Command;

class GithubDevelopersSyncCommand extends Command
{
    protected $signature = 'fetch:developers';

    protected $description = 'Fetch developers from Github API and store in database.';

    public function handle(): void
    {
        GithubDevelopersSyncJob::dispatch();
    }
}
