<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TaskResource;
use App\Http\Traits\ApiDesignTrait;
use App\Models\Image;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    use ApiResponse;
    use ApiDesignTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
//        if ($user->can('show')) {
        if ($user)
        {
            $org = Organization::get();
            if ($org) {
                return $this->sendJson(OrganizationResource::collection($org));
            } else {
                throw new \App\Exceptions\NotFoundException;
            }
        } else {
            throw new \App\Exceptions\DontHavePermission();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationRequest $request)
    {
        $user = Auth::user();

//        if ($user->can('create')) {
        if ($user)
        {
//            $org = new Organization;
//            $org->name = $request->name;
//            $org->description = $request->description;
//            $org->status = 'InActive';
//            $org->manager_id = Auth::id();
//            $org->save();

//            $org = Organization::create($request->only([
//                'name', 'description', 'manager_id'
//            ]));

            $org = Organization::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'InActive',
                'manager_id' => Auth::id()
            ]);

            if($request->image){
                $imageUrl = Storage::disk('public')->put('/organization_images', $request->image);
                $image = new Image();
                $image->type = 'post';
                $image->url = $imageUrl;
//                $image->organization_id = $org->id;
                $image->save();
                $org->images()->attach($image->id, ['type'=>'post']);
            }

            return $this->apiResponse(200, 'Organizaton created successfully', null, new OrganizationResource($org));

//            return $this->sendJson(new OrganizationResource($org));
        } else {
            throw new \App\Exceptions\DontHavePermission();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = Auth::user();

//        if ($user->can('show')) {
        if ($user)
        {
            $org = Organization::find($request->id);
            if ($org) {
                return $this->sendJson(new OrganizationResource($org));
            }
            throw new \App\Exceptions\NotFoundException;
        } else {
            throw new \App\Exceptions\DontHavePermission();
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrganizationRequest $request)
    {
        $user = Auth::user();

//        if ($user->can('update')) {
        if ($user)
        {
            $org = Organization::find($request->id);
            if (!empty($org)) {
                $org->update($request->all());
                return $this->sendJson(new OrganizationResource($org));
            }
            throw new \App\Exceptions\NotFoundException;
        } else {
            throw new \App\Exceptions\DontHavePermission();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

//        if ($user->can('delete')) {
        if ($user)
        {
            $org = Organization::find($request->id);
            if (!empty($org)) {
                $org->delete();
                return 'Organization deleted';
            }
            throw new \App\Exceptions\NotFoundException;
        } else {
            throw new \App\Exceptions\DontHavePermission();
        }
    }
}
