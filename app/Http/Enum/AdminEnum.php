<?php

namespace App\Http\Enum;

enum AdminEnum: int
{
    case Admin = 1;
    case Super_Admin = 2;

    public function label(): string
    {
        return match ($this) {
            self::Admin => ' مدير',
            self::Super_Admin => ' مدير عام',

        };
    }
}
