<?php

namespace App\Models;

use App\Http\Enums\OrganizationEnum;
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

    public function organization_employe()
    {
        return $this->hasMany(organization_employe::class);
    }

       protected $casts = [
        'type' => OrganizationEnum::class,
    ];



}
