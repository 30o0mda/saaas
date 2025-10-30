<?php

namespace App\Models;

use App\Http\Enum\QuestionsEnum\MediaTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations\MediaType;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'question_bank_id',
        'media',
        'degree',
        'media_type',
        'question_type',
        'difficulty',
        'is_active',
    ];

    public function questionBank()
    {
        return $this->belongsTo(QuestionBank::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    protected $casts = [
        'media_type' => MediaTypeEnum::class,
        'question_type' => MediaTypeEnum::class,
        'difficulty' => MediaTypeEnum::class
    ];

}
