<?php

use App\Enums\RoleEnum;

function redirectByLoggedUser(): string
{
    if (!auth()->check()) {
        return 'login';
    }

    return auth()->user()->role_id === RoleEnum::CTO
        ? 'assistants.index'
        : 'dashboard';
}
