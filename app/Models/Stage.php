<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organization_id',
        'parent_id',
        'education_type_id',
    ];

    public function children()
    {
        return $this->hasMany(Stage::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Stage::class, 'parent_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function education_type()
    {
        return $this->belongsTo(EducationType::class);
    }

    public function stage_and_subjects()
    {
        return $this->hasMany(StageAndSubject::class);
    }
public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'stage_and_subjects', 'stage_id', 'subject_id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function user(){
        return $this->hasMany(User::class);
    }
}
