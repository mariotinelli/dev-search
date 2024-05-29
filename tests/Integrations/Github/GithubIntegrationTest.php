<?php

use App\Integrations\Github\GithubIntegration;

it('should be get developer email from api', function () {
    $email = (new GithubIntegration())->getDeveloperEmail('mariotinelli');

    expect($email)
        ->toBeString()
        ->and($email)
        ->toBe('maario_tinelli@hotmail.com');
});

it('should be get developer from api with exception', function () {
    (new GithubIntegration())->getDeveloperEmail('mariotinelli123');
})->throws(\App\Integrations\Github\Exceptions\DeveloperNotFoundException::class);

it('should be get all developers from search with graphql', function () {
    $response = (new GithubIntegration())->searchDevelopers("2024-01-01..2024-01-25");

    expect($response['data']['search']['userCount'])
        ->toBeGreaterThan(0);
});

it('should be get all commits on the last year by username with graphql', function () {
    $response = (new GithubIntegration())->getAllDeveloperCommitsOnTheLastYear('mariotinelli');

    expect($response['data']['user']['login'])
        ->toBe('mariotinelli')
        ->and($response['data']['user']['repositories']['totalCount'])
        ->toBeGreaterThan(0);
});
