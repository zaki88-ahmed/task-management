<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        return [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'images' => $this->images,
            'manager_id' => $this->manager ? $this->manager->User->name : '' ,
        ];
    }
}
