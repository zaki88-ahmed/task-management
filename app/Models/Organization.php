<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\This;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'status', 'manager_id'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

//    public function manager()
//    {
//        return $this->belongsTo(Manager::class, 'id', 'manager_id');
//    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'manager_id', 'id');
    }

//    public function employee()
//    {
//        return $this->hasMany(Employee::class,'organization_id', 'id');
//    }
//
//    public function task()
//    {
//        return $this->belongsTo(Employee::class, 'organization_id', 'id');
//    }

    public function images(){
        return $this->morphToMany(Image::class, 'imageable');
    }
}
