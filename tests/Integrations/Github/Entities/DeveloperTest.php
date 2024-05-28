<?php

use App\Integrations\Github\Entities\Developer;
use Illuminate\Support\Facades\File;

it('should create an instance of User with data from api', function () {
    $data = File::json('tests/fixtures/github/developer.json');

    $user = Developer::createFromGraphQL($data);

    expect($user)->toBeInstanceOf(Developer::class);
});
