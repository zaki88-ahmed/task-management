<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_no',
        'gender',
        'fb_id',
        'google_id',
        'user_type',
        'device_token',
        'email_verified_at',
        'current_team_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function User_type()
    {
        return $this->belongsTo(UserType::class,'user_type','id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'user_id', 'id');
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'id', 'destination');
    }
}
