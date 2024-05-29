<?php

use App\Console\Commands\RetryJobsMaxAttemptsExceptionCommand;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(RetryJobsMaxAttemptsExceptionCommand::class)->everyMinute();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['role' => RoleMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
