<?php

namespace App\Http\Enum\QuestionsEnum;

enum MediaTypeEnum: int
{
    case PICTURE = 1;
    case AUDIO = 2;
    case VIDEO = 3;

    public function label(): string
    {
        return match ($this) {
            self::PICTURE => ' صورة',
            self::AUDIO => ' صوت',
            self::VIDEO => ' فيديو',
        };
    }
}
