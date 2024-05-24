<?php

use App\Integrations\Github\Entities\Repository;
use App\Integrations\Github\Entities\User;
use App\Integrations\Github\Exceptions\GithubUserNotFoundException;
use App\Integrations\Github\GithubIntegration;
use Illuminate\Support\Collection;

it('should get an user by a username', function () {
    $user = (new GithubIntegration())->getUser('mariotinelli');

    expect($user)->toBeInstanceOf(User::class);
});

it('should throw an exception if user does not exist', function () {
    (new GithubIntegration())->getUser('n0n-3x1st1ng-user');
})->throws(GithubUserNotFoundException::class, 'Usuário não encontrado.');

it('should be get all users from api', function () {
    $users = (new GithubIntegration())->getAllUsers();

    expect($users)->toBeInstanceOf(Collection::class)
        ->and($users)->not->toBeEmpty()
        ->and($users->get(0))->toBeInstanceOf(User::class);
});

it('should be get all repositories from an user', function () {
    $repositories = (new GithubIntegration())->getAllUserRepositories('mariotinelli');

    expect($repositories)->toBeInstanceOf(Collection::class)
        ->and($repositories)->not->toBeEmpty()
        ->and($repositories->get(0))->toBeInstanceOf(Repository::class);
});