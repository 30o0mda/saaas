<?php

namespace App\Http\Enum\QuestionsEnum;

enum DifficultyQuestionEnum: int
{
    case EASY = 1;
    case MEDIUM = 2;
    case HARD = 3;

    public function label(): string
    {
        return match ($this) {
            self::EASY => ' سهل',
            self::MEDIUM => ' متوسط',
            self::HARD => ' صعب',
        };
    }
}
