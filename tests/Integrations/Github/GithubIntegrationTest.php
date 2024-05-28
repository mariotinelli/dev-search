<?php

use App\Integrations\Github\GithubIntegration;

it('should be get all users from search with graphql', function () {
    $response = (new GithubIntegration())->searchUsers("2024-01-01..2024-01-25");

    expect($response['data']['search']['userCount'])
        ->toBeGreaterThan(0);
});

it('should be get all commits on the last year by username with graphql', function () {
    $response = (new GithubIntegration())->getAllUserCommitsOnTheLastYear('mariotinelli');

    expect($response['data']['user']['login'])
        ->toBe('mariotinelli')
        ->and($response['data']['user']['repositories']['totalCount'])
        ->toBeGreaterThan(0);
});
