<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;
    protected $table = 'user_types';
    protected $fillable = [
        'type',
    ];

    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function Users()
    {
        return $this->hasMany(User::class,'user_type','id');
    }
}
