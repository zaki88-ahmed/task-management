<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function create_task(){
//        dd('here');
//        $admin = User::where('user_type', 1)->first();
        $user = auth('sanctum')->user();
        if($user->hasPermissionTo('create_task')) return true;
        return false;
    }

    public function edit_task(){
        $user = auth('sanctum')->user();
        if($user->hasPermissionTo('view_specific_task')) return true;
        return false;
    }


    public function view_specific_task(){
        $user = auth('sanctum')->user();
        if($user->hasPermissionTo('view_specific_task')) return true;
        return false;
    }

    public function view_all_tasks(){
        $user = auth('sanctum')->user();
        if($user->hasPermissionTo('view_all_tasks')) return true;
        return false;
    }

    public function delete_task(){
        $user = auth('sanctum')->user();
        if($user->hasPermissionTo('delete_task')) return true;
        return false;
    }

    public function restore_task(){
        $user = auth('sanctum')->user();
        if($user->hasPermissionTo('restore_task')) return true;
        return false;
    }

    public function assign_task(){
        $user = auth('sanctum')->user();
        if($user->hasPermissionTo('assign_task')) return true;
        return false;
    }
    public function changeTaskStatus(){
        $user = auth('sanctum')->user();
        if($user->hasPermissionTo('change_task_status')) return true;
        return false;
    }

}
