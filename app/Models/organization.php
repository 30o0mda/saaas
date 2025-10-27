<?php

namespace App\Models;

use App\Http\Enum\OrganizationEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'type',
        'image',
        'max_teachers',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function organization_employee()
    {
        return $this->hasMany(OrganizationEmployee::class);
    }

    public function education_type(){
        return $this->hasMany(EducationType::class);
    }

    public function stage(){
        return $this->hasMany(Stage::class);
    }

    public function subject(){
        return $this->hasMany(Subject::class);
    }

    public function course(){
        return $this->hasMany(Course::class);
    }

    public function session(){
        return $this->hasMany(Session::class);
    }

       protected $casts = [
        'type' => OrganizationEnum::class,
    ];



}
