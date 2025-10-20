<?php

namespace App\Models;

use App\Http\Enum\AdminEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'image',
        'phone',
    ];

    public function organization()
    {
        return $this->hasMany(Organization::class);
    }
    protected $casts = [
        'type' => AdminEnum::class,
    ];
}
