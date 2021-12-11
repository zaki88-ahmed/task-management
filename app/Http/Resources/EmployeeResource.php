<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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

            // Relation Employee with User
            'Name' => $this->when($this->User()->exists(), $this->User->name),
            'email' => $this->when($this->User()->exists(),$this->User->email),
            'gender' => $this->when($this->User()->exists(),$this->User->gender),
            'user_type' => $this->when($this->User()->exists(),$this->User->user_type),
            'profile_photo_path' => $this->when($this->User()->exists(),$this->User->profile_photo_path),
            'current_team_id' => $this->when($this->User()->exists(),$this->User->current_team_id),
            // Relation Employee with Organization
            'Organization_name'=>$this->when($this->Organization()->exists(), $this->Organization->name),
            'Organization_description' => $this->when($this->Organization()->exists(),$this->Organization->description),
            'Organization_status' => $this->when($this->Organization()->exists(),$this->Organization->status),
            // Employee
            'address' => $this->address,
            'Education' => $this->education,
            'phone_no' => $this->phone_no,
            'date_of_birth'=>$this->date_of_birth,
        ];
    }
}
