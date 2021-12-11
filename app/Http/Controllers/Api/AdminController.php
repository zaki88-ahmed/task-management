<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Http\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
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

    public function activation(Request $request)
    {
        $user = Auth::user();
//        if ($user->hasRole('Admin')) {
        if ($user)
        {
            $org =  Organization::find($request->id);
            if (!empty($org)) {
                $org->update([$org->status = 'Active']);
                return $this->sendJson(new OrganizationResource($org));
            } else {
                throw new \App\Exceptions\NotFoundException;
            }
        } else {
            throw new \App\Exceptions\DontHavePermission();
        }
    }
}
