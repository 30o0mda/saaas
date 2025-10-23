<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stage extends Model
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
        return $this->hasMany(stage::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(stage::class, 'parent_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function education_type()
    {
        return $this->belongsTo(EducationType::class);
    }
}
