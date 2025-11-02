<?php

namespace App\Http\Enum\QuestionBank;

enum QuestionBankStatus: int
{
    case PENDING = 1;
    case ACCEPT = 2;
case REJECT = 3;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'قيد الانتظار',
            self::ACCEPT => 'قبول',
            self::REJECT => 'رفض',
        };
    }
}
