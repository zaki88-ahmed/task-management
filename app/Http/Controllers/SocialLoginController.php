<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiDesignTrait;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    use ApiDesignTrait;
    //
    public function redirectToSocial($driver){

        return Socialite::driver($driver)->redirect();
    }

    public function handleSocialCallback($driver){
        try {
            $user = Socialite::driver($driver)->user();
//            dd($user);
            $this->registerOrLoginUser($user);
//            return redirect()->route('');
        }catch (Exception $e){
            dd($e->getMessage());
        }
    }

    protected function registerOrLoginUser($request){
//        dd($request);
        $user = User::where('email', '=', $request->email)->first();
//        dd($user);
        if(! $user){
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('123456'),
                'gender' => 'male',
                'Oauth_token' => $request->id,
                'user_type' => 3,
                'phone_no' => 0100000,
            ]);
        }
//            dd('buy');
            Auth::login($user);
            $user = Auth::user();
//            dd($user);
//            $response = [
//                'token' => $user->createToken('token-name')->plainTextToken
//            ];
//        $response = array('token' => $user->createToken('token-name')->plainTextToken);
        $response = $user->createToken('token-name')->plainTextToken;
        dd($response);

//            return $user->createToken('token-name')->plainTextToken;
            return $this->ApiResponse(200, 'Access Token', $response);
    }
}
