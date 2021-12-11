<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        return [
            'title' => $this->title,
            'description' => $this->description,
            'employee_id' => $this->employee_id,
            'image' => $this->image,
            'manager_id' => $this->manager_id,
            'organization_name' => $this->when($this->employee()->exists(), $this->employee->organization->name),
            'status_id' => $this->when($this->status()->exists(), $this->status->name),
            'parent_id' => $this->parent_id,
        ];
    }
}
