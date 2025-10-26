<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;
    protected $table = 'courses';

    protected $fillable = [
        'name',
        'description',
        'stage_and_subject_id',
        'organization_id',
        'price',
        'order',
        'is_active',
        'is_free',
        'organization_employees_id',
    ];

    public function stageAndSubject()
    {
        return $this->belongsTo(StageAndSubject::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function organizationEmployee()
    {
        return $this->belongsTo(OrganizationEmployee::class);
    }

    
}
