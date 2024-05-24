<?php

namespace App\Enums;

enum RoleEnum: int
{

    case ADMIN = 1;

    case CTO = 2;

    case ASSISTANT = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::CTO => 'CTO',
            self::ASSISTANT => 'Assistente',
        };
    }
}
