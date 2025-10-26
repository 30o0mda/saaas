<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function stage_and_subjects()
    {
        return $this->hasMany(StageAndSubject::class);
    }

    public function stages()
    {
        return $this->belongsToMany(Stage::class, 'stage_and_subjects', 'subject_id', 'stage_id');
    }

    


}
