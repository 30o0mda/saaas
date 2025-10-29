<?php

namespace App\Http\Enum;

enum JoinCourseEnum: int
{
    case PENDING = 0;
    case ACCEPTED = 1;
    case REJECTED = 2;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => ' معلق ',
            self::ACCEPTED => ' مقبول ',
            self::REJECTED => ' مرفوض ',
        };
    }
}
