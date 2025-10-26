<?php

namespace App\Models;

use App\Http\Enum\OrganizationEmployeEnum;
use App\Http\Enum\Organization_EmployeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class OrganizationEmployee extends Authenticatable
{

    use HasFactory;
        use HasApiTokens, Notifiable;

protected $table = 'organization_employees';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image',
        'organization_id',
        'admin_id',
        'type',
        'is_master',
        'parent_id',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function parent()
    {
        return $this->belongsTo(OrganizationEmployee::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(OrganizationEmployee::class, 'parent_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

       protected $casts = [
        'type' => OrganizationEmployeEnum::class,
        'is_master' => 'boolean',
    ];
}
