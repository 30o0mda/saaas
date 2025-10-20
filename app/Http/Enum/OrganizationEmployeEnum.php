<?php

namespace App\Http\Enum;

enum OrganizationEmployeEnum: int
{
    case TEACHER = 1;
    case ASSISTANT = 2;

    public function label(): string
    {
        return match ($this) {
            self::TEACHER => ' مدرس',
            self::ASSISTANT => ' مساعد',
        };
    }
}
