<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageAndSubject extends Model
{
    use HasFactory;

    protected $table = 'stage_and_subjects';
    protected $fillable = [
        'stage_id',
        'subject_id',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    
}
