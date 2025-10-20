<?php

namespace App\Http\Enums;

enum OrganizationEnum: int
{
    case Teacher = 1;
    case center = 2;

    public function label(): string
    {
        return match ($this) {
            self::Teacher => ' مدرس',
            self::center => ' مركز',
        };
    }
}
