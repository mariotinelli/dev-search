<?php

todo('should be get all users and dispatch PullGithubUserJob for each user', function () {
    $users = [
        (object) ['login' => 'mariotinelli'],
        (object) ['login' => 'laravel'],
    ];

    $githubIntegration = Mockery::mock('overload:App\Integrations\Github\GithubIntegration');
    $githubIntegration->shouldReceive('getAllUsers')->andReturn(collect($users));

    $pullGithubUserJob = Mockery::mock('overload:App\Jobs\PullGithubUserJob');
    $pullGithubUserJob->shouldReceive('dispatch')->with('mariotinelli');
    $pullGithubUserJob->shouldReceive('dispatch')->with('laravel');

    (new \App\Jobs\PullGithubUsersJob())->handle();

});
