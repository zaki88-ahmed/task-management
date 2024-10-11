<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';

    protected $fillable = [
        'price',
        'description',
        'duration',
        'status'
    ];

    public function Managers()
    {
        return $this->hasMany(Manager::class,'membership_id','id');
    }}
