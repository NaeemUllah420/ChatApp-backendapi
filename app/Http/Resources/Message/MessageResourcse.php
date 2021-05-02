<?php

namespace App\Http\Resources\Message;

use App\Http\Resources\Attachments\AttachmentResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResourcse extends JsonResource
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
            'text'=>$this->text,
            'sender'=>$this->when(!empty($this->sender),new UserResource($this->sender)),
            'receiver'=>$this->when(!empty($this->receiver),new UserResource($this->receiver)),
            // 'group'=>$this->when(!empty($this->group),new UserResource($this->group)),
            'attachments'=>$this->when(count($this->attachmentable),AttachmentResource::collection($this->attachmentable)),

        ];
    }
}
