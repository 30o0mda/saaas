<?php

namespace App\Models;

use App\Http\Enum\UserEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'organization_id',
        'parent_name',
        'parent_phone',
        'verify_code',
        'is_verified',
        'email_verified_at',
        'phone',
        'country_code',
        'image',
        'stage_id',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function stage(){
        return $this->belongsTo(Stage::class);
    }

     protected $casts = [
        'type' => UserEnum::class,
    ];
}
