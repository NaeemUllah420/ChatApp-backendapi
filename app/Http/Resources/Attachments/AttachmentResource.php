<?php

namespace App\Http\Resources\Attachments;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
            'type'=>$this->type,
            'path'=>$this->path,
        ];
    }
}
