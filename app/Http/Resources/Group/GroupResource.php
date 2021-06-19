<?php

namespace App\Http\Resources\Group;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->_id,
            'name' => $this->name,
            'type' => $this->type,
            'users' => $this->when(count($this->users), UserResource::collection($this->users)),
        ];
    }
}
