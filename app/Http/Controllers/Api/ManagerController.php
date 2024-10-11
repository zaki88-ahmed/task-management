<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DontHavePermissionException;
use App\Http\Resources\ManagerResource;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Manager;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\Organization;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use GuzzleHttp\Client;
use Infobip\Api\SendSmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;


class ManagerController extends Controller
{
    use ApiResponse ;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ApiResponse ;

    public function index()
    {
        //
    }

    public function list_manager(Request $request)
    {

        $user= User::find(Auth::user()->id);
//        if($user->can('list_managers')){
        if ($user)
        {
           $manager = Manager::get();
           return $this->sendJson(ManagerResource::collection($manager));
        }else {
           throw new DontHavePermissionException();
        }
    }






    public function addManager_permission(Request $request)
    {

        // $user =User::find($request->id);

        $user =auth()->user();

//        if ($user->can('Add_manager'))
//        {
        if ($user)
        {
            $request->validate([
                'section' => 'required',
                'join_date' => 'required',
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'gender' => 'required',
            ]);



                        $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => $request->password,
                        'gender' => $request->gender,
                        ]);

                        $manager = Manager::create([
                        'section' => $request->section,
                        'join_date' => $request->join_date,
                        'user_id' => $user->id,
                        ]);

                        $user->assignRole('Manager');

                        $managerResource = new ManagerResource($manager);

                        if ($manager) {
                            return $this->sendJson($managerResource);
                        }
                        throw new \App\Exceptions\NotFoundException;
        }else{
            throw new \App\Exceptions\NotAuthorizeException;
             }

    }


    public function assign_manager_to_organization(Request $request)
    {

                // $user =User::find($request->id);
                $user =auth()->user();


//                if ($user->can('Add_manager')) {
        if ($user)
        {
                    $organization = Organization::find($request->organization_id);
                    if ($organization) {




                        $organization->manager_id = $request->manager_id;
                        $organization->save();

                    //dd($organization_id);


                        return $this->sendJson($organization);;
                    } else {
                        throw new \App\Exceptions\NotFoundOrganization;

                    }
                }else{
                    throw new \App\Exceptions\NotAuthorizeException;

                     }

    }



}
