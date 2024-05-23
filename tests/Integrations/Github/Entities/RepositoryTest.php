<?php

use App\Integrations\Github\Entities\Repository;
use Illuminate\Support\Facades\File;

it('should create an instance of Repository with data from api', function () {
    $data = File::json('tests/fixtures/github/repository.json');

    $repository = Repository::createFromApi($data);

    expect($repository)->toBeInstanceOf(Repository::class);
});
