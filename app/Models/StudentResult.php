<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    use HasFactory;

    protected $table = 'student_results';

    protected $fillable = [
        'user_id',
        'question_bank_id',
        'organization_id',
        'is_finished',
        'is_cancel',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questionBank()
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function studentResultAnswers()
    {
        return $this->hasMany(StudentResultAnswer::class);
    }
}
