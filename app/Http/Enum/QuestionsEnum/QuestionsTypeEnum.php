<?php

namespace App\Http\Enum\QuestionsEnum;

enum QuestionsTypeEnum: int
{
    case MCQ = 1;
    case TRUE_FALSE = 2;

    case ARTICLE = 3;

    public function label(): string
    {
        return match ($this) {
            self::MCQ => ' اختياري',
            self::TRUE_FALSE => ' صحيح / خاطئ',
            self::ARTICLE => ' مقال',
        };
    }
}
