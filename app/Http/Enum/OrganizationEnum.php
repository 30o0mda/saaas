<?php

namespace App\Http\Enum;

enum OrganizationEnum: int
{
    case Organization = 1;
    case Teacher = 2;
    case center = 3;


    public function label(): string
    {
        return match ($this) {
            self::Organization => ' منظمة',
            self::Teacher => ' مدرس',
            self::center => ' مركز',
        };
    }
}
