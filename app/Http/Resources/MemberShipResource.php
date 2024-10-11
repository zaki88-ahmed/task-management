<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberShipResource extends JsonResource
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
            'Price' => $this->price,
            'Description' => $this->description,
            'duration' => $this->duration,
            // 'status' => $this->status,
            'status' => $this->when($this->status == 0,"De Active", $this->when($this->status == 1,"Active")),
        ];
    }
}
