<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiDesignTrait;
use App\Http\Traits\BlockTrait;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    use ApiDesignTrait;
    use BlockTrait;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'gender'                => 'required',
            'phone_no'                => 'required|min:11|numeric',
            'date_of_birth'                  => 'required|date',
            'address'                 => 'required|string',
            'education'                 => 'required|string',
            'organization_id'                => 'required|exists:organizations,id',
        ]);
        if($validator->fails()) {
            return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
        }

//        dd('user');
        $userType = UserType::where('id', 3)->first();
        $userTypeId = $userType->id;
        $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'gender' => $request->gender,
                    'user_type' => $userTypeId,
                    'phone_no' => $request->phone_no,
                ]);


//        if($user->user_type == 1){
//            $user->assignRole('admin');
//            $userRole = 'Admin';
//        }
//
//
//        else if($user->user_type == 2){
//            $user->assignRole('manager');
//            $userRole = 'Manager';
////            $validator = Validator::make($request->all(), [
////                'section'                  => 'required|string',
////                'join_date'                 => 'required|date',
//////                'user_id'                => 'required|exists:users,id',
////                'membership_id'                => 'required|exists:memberships,id',
////            ]);
////            if($validator->fails()) {
////                return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
////            }
////            $manager = Manager::create([
////                'section' => $request->section,
////                'join_date' => $request->join_date,
////                'user_id' => $user->id,
////                'membership_id' => $request->membership_id,
////            ]);
//
//        }
//
//
//        else if($user->user_type == 3){
//            $user->assignRole('employee');
//            $userRole = 'Employee';
////            $validator = Validator::make($request->all(), [
////                'date_of_birth'                  => 'required|date',
////                'address'                 => 'required|text',
////                'education'                 => 'required|text',
//////                'user_id'                => 'required|exists:users,id',
////                'organization_id'                => 'required|exists:organizations,id',
////            ]);
////            if($validator->fails()) {
////                return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
////            }
//            $employee = Employee::create([
//                'date_of_birth' => $request->date_of_birth,
//                'address' => $request->address,
//                'education' => $request->education,
//                'user_id' => $user->id,
//                'organization_id' => $request->organization_id,
//            ]);
//        }


        $user->assignRole('employee');
        $userRole = 'Employee';

        $employee = Employee::create([
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'education' => $request->education,
            'user_id' => $user->id,
            'organization_id' => $request->organization_id,
        ]);

        $this->sendSMS($request, $userRole);
        return $this->ApiResponse(200, 'You have signed-in', null, $user);
    }




    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
        }

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password) ){
            return $this->ApiResponse(401, 'Bad credentials');
        }
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $response = [
                'token' => $user->createToken('token-name')->plainTextToken
            ];
            return $this->ApiResponse(200, 'Done', null, $response);
        }
    }


    public function userPromotion(Request $request){

        $this->authorize('userPromotion', User::class);

        $validator = Validator::make($request->all(), [
            'user_id'                => 'required|exists:users,id',
            'section'                  => 'required|string',
            'join_date'                 => 'required|date',
            'membership_id'                => 'required|exists:memberships,id',
        ]);
        if($validator->fails()) {
            return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
        }
        $user = User::with(['employee', 'manager'])->find($request->user_id);
        $userTypeRequested = $user->user_type;
        $userTypeRequested -= 1;
        $userType = UserType::find($userTypeRequested);

        if(!is_null($userType)){
//            dd($user);
            $userTypeName = $userType->type;
            $user->update([
                'user_type' => $userTypeRequested,
            ]);

            $employee = Employee::where('user_id', $user->id)->first();
            $manager = Manager::where('user_id', $user->id)->first();
//            $admin = User::where('user_id', $user->id)->first();

            if($employee){
                $employee->delete();
                $manager = Manager::create([
                    'section' => $request->section,
                    'join_date' => $request->join_date,
                    'user_id' => $user->id,
                    'membership_id' => $request->membership_id,
                ]);
            }
            elseif($manager) $manager->delete();

            return $this->ApiResponse(200, "User Promoted", null, $manager);
        }
        return $this->ApiResponse(200, "User Can't be Promoted");

    }




    public function userDemotion(Request $request){

        $this->authorize('userDemotion', User::class);
        $validator = Validator::make($request->all(), [
            'user_id'                => 'required|exists:users,id',
        ]);
        if($validator->fails()) {
            return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
        }
//        dd('zzz');
        $user = User::with(['employee', 'manager'])->find($request->user_id);
        $userTypeRequested = $user->user_type;
        $userTypeRequested += 1;
        $userType = UserType::find($userTypeRequested);

        if(!is_null($userType)){
            $userTypeName = $userType->type;
            $user->update([
                'user_type' => $userTypeRequested,
            ]);
            $employee = Employee::where('user_id', $user->id)->first();
            $manager = Manager::where('user_id', $user->id)->first();
            $admin = User::where('id', $user->id)->first();

            if($employee) return $this->ApiResponse(200, "User Can't be Demoted");
            if($manager) {
                $manager->delete();
                $validator = Validator::make($request->all(), [
                    'date_of_birth'                  => 'required|date',
                    'address'                 => 'required|string',
                    'education'                 => 'required|string',
                    'organization_id'                => 'required|exists:organizations,id',
                ]);
                $employee = Employee::create([
                    'date_of_birth' => $request->date_of_birth,
                    'address' => $request->address,
                    'education' => $request->education,
                    'user_id' => $user->id,
                    'organization_id' => $request->organization_id,
                ]);
                return $this->ApiResponse(200, "User Demoted", null, $employee);
            }

            elseif($admin){
                $validator = Validator::make($request->all(), [
                    'section'                  => 'required|string',
                    'join_date'                 => 'required|date',
                    'membership_id'                => 'required|exists:memberships,id',
                ]);
                if($validator->fails()) {
                    return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
                }
                $manager = Manager::create([
                    'section' => $request->section,
                    'join_date' => $request->join_date,
                    'user_id' => $user->id,
                    'membership_id' => $request->membership_id,
                ]);
                return $this->ApiResponse(200, "User Demoted", null, $manager);
            }
        }
        return $this->ApiResponse(200, "User Can't Be Demoted");

    }
}
