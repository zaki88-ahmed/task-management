<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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




    public function userPromotion(){
        $user = auth('sanctum')->user();
        if($user->hasPermissionTo('user_promotion')) return true;
        return false;
    }


    public function userDemotion(){
        $user = auth('sanctum')->user();
        if($user->hasPermissionTo('user_demotion')) return true;
        return false;
    }



}
