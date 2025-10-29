<?php

namespace App\Models;

use App\Http\Enum\JoinCourseEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinCourseRequest extends Model
{
    use HasFactory;

    protected $table = 'join_course_requests';

    protected $fillable = [
        'user_id',
        'course_id',
        'organization_id',
        'image',
        'account_number',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

       protected $casts = [
        'status' => JoinCourseEnum::class,
    ];


}
