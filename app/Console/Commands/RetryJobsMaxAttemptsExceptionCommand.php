<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{Artisan, DB};

class RetryJobsMaxAttemptsExceptionCommand extends Command
{
    protected $signature = 'retry:jobs-max-attempts-exception';

    protected $description = 'Retry jobs that have reached the maximum number of attempts.';

    public function handle(): void
    {
        $failedJobs = DB::table('failed_jobs')
            ->where('exception', 'like', '%MaxAttemptsExceededException%')
            ->get();

        $failedJobs->each(function ($failedJob) {
            Artisan::call("queue:retry {$failedJob->uuid}");
        });
    }
}
