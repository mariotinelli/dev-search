<?php

namespace App\Console\Commands;

use App\Jobs\GithubDevelopersSyncJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class GithubDevelopersSyncCommand extends Command
{
    protected $signature = 'github-developers-sync';

    protected $description = 'Sync users from Github API and store in database.';

    public function handle(): void
    {
        Redis::command('flushdb');
        GithubDevelopersSyncJob::dispatch();
    }
}
