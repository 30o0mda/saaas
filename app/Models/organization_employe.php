<?php

namespace App\Models;

use App\Http\Enums\Organization_EmployeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class organization_employe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image',
        'organization_id',
        'admin_id',
        'type',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

       protected $casts = [
        // 'type' => Organization_EmployeEnum::class,
    ];}
