<?php

function redirectByLoggedUser(): string
{
    if (!auth()->check()) {
        return 'login';
    }

    return auth()->user()->isCto()
        ? 'assistants.index'
        : 'assistants.index';
}
