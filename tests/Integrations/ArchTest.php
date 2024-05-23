<?php

it('Entities should be readonly')
    ->expect("App\Integrations\Github\Entities")
    ->toBeReadonly();

it('Exceptions should extends laravel Exception class')
    ->expect("App\Integrations\Github\Exceptions")
    ->toExtend("Exception");
