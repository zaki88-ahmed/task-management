<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'notifications';

    protected $fillable = ['destination', 'text', 'seen', 'url', 'type'];
    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];
}
