<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'employee_id',
        'manager_id',
        'organization_id',
        'status_id',
        'parent_id',
    ];

    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'manager_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

}
