<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'=>$this->_id,
            'name'=>$this->name,
            'email'=>$this->email,
            'token'=>$this->when(!empty($this->token),$this->token)
        ];
    }
}
