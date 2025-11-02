<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResultAnswer extends Model
{
    use HasFactory;
    protected $table = 'student_result_answers';
    protected $fillable = [
        'student_result_id',
        'question_id',
        'answer_id',
        'is_correct',
    ];

    public function studentResult()
    {
        return $this->belongsTo(StudentResult::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    
}
