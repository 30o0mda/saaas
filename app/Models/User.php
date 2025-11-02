<?php

namespace App\Models;

use App\Http\Enum\QuestionBank\QuestionBankStatus as QuestionBankQuestionBankStatus;
use App\Http\Enum\QuestionBank\QuestionBankStatus;
use App\Http\Enum\UserEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'organization_id',
        'parent_name',
        'parent_phone',
        'verify_code',
        'is_verified',
        'email_verified_at',
        'phone',
        'country_code',
        'image',
        'stage_id',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function stage(){
        return $this->belongsTo(Stage::class);
    }

    public function courses(){
        return $this->belongsToMany(Course::class, 'join_courses', 'user_id', 'course_id');
    }

    public function courseUsers(){
        return $this->hasMany(CourseUser::class);
    }

    public function userCourses(){
        return $this->hasManyThrough(course::class , CourseUser::class , 'user_id', 'id', 'id', 'course_id');
    }

    public function questionBank(){
        return $this->hasMany(QuestionBank::class , 'user_id');
    }

    // User.php
public function questionBanks()
{
    return $this->belongsToMany(QuestionBank::class, 'user_question_bank', 'user_id', 'question_bank_id')->withPivot('status');
}

public function studentResults()
{
    return $this->hasMany(StudentResult::class);
}


     protected $casts = [
        'type' => UserEnum::class,

    ];
}
