<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'organization_id',
        'stage_and_subject_id',
        'order',
        'is_active',
    ];

    public function stageAndSubject()
    {
        return $this->belongsTo(StageAndSubject::class,'stage_and_subject_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class,'organization_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class,'question_bank_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function users()
{
    return $this->belongsToMany(User::class, 'user_question_bank', 'question_bank_id', 'user_id');
}

public function studentResults()
{
    return $this->hasMany(StudentResult::class);
}

}
