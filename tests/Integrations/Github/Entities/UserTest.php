<?php

use App\Integrations\Github\Entities\User;
use Illuminate\Support\Facades\File;

it('should create an instance of User with data from api', function () {
    $data = File::json('tests/fixtures/github/user.json');

    $user = User::createFromApi($data);

    expect($user)->toBeInstanceOf(User::class);
});
