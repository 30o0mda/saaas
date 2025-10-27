<?php

namespace App\Http\Enum;

enum SessionEnum: int
{
    case VIDEO = 1;
    case AUDIO = 2;
    case PDF = 3;

    public function label(): string
    {
        return match ($this) {
            self::VIDEO => ' فيديو',
            self::AUDIO => ' صوت',
            self::PDF => ' ملف نصي',
        };
    }
}
