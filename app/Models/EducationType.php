<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'organization_id',
        'is_active',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function stage()
    {
        return $this->hasMany(Stage::class);
    }

    
}
