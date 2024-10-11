<?php

namespace App\Models;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = [
        'address',
        'education',
        'gender',
        'date_of_birth',
        'user_id',
        'organization_id',
    ];

    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'employee_id', 'id');
    }

}






















