<?php

namespace App\Http\Enum;

enum UserEnum: int
{
    case USER = 1;
    case PARENT = 2;

    public function label(): string
    {
        return match ($this) {
            self::USER => 'طالب',
            self::PARENT => ' ولي أمر',
        };
    }
}
