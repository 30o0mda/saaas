<?php

namespace App\Models;

use App\Http\Enum\SessionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions';

    protected $fillable = [
        'name',
        'description',
        'course_id',
        'parent_id',
        'file',
        'order',
        'is_active',
        'type',
        'link',
        'organization_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function parent()
    {
        return $this->belongsTo(Session::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Session::class, 'parent_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

           protected $casts = [
        'type' => SessionEnum::class,
        'is_active' => 'boolean',
    ];

}
