<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DontHavePermissionException;
use App\Exceptions\NotFoundMemberShipException;
use App\Http\Resources\MemberShipResource;
use App\Http\Resources\ManagerResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\UserType;
use App\Models\Manager;
use App\Models\User;
use GuzzleHttp\Client;

class MembershipController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test_api()
    {
        $clint = new Client([
            'base_uri' => 'http://localhost:8000/api/list-fantasy'
        ]);
        $response = $clint->request('GET' , 'https://fakestoreapi.com/products');

        $data = json_decode($response->getBody()) ;

        return $data;
    }
    public function list_membership()
    {
        // Done For only Managers
        $user = User::find(Auth::user()->id);
        $manager_type = UserType::where('type' , 'Manager')->first();
        $admin_type = UserType::where('type' , 'Admin')->first();

       if($user->can('list_membership') && $user->user_type == $manager_type->id ||  $user->user_type == $admin_type->id ){
            $membership = Membership::get();
            if($membership){
                return $this->sendJson(MemberShipResource::collection($membership));
            }else{
                throw new NotFoundMemberShipException;
            }

       }else{
        throw new DontHavePermissionException();
       }
    }

    public function subscribe_membership(Request $request)
    {
            if(UserType::find(Auth::user()->user_type)->type == 'admin'){
            $request->validate([
                'membership_id' => 'required',
                    'manager_id' => 'required',
            ]);
        }else {
            $request->validate([
                'membership_id' => 'required',
            ]);
        }

        // use admin manager //
        $manager = Manager::find(Auth::user()->id);
        $user = User::find($manager->user_id);
//        if($user->hasRole('Manager') ){
        if ($user)
        {
            $membership = Membership::find($request->membership_id);
            if($membership){
                $subscribe_membership = Manager::find(Auth::user()->id);
                $subscribe_membership->membership_id = $request->membership_id;
                $subscribe_membership->save();
                //  return $subscribe_membership;
                return $this->sendJson(new ManagerResource($subscribe_membership));
            }else{
                throw new NotFoundMemberShipException;
            }
        }else{
            throw new DontHavePermissionException();
        }
    }


    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
