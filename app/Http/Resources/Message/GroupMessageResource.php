<?php

namespace App\Http\Resources\Message;

use App\Http\Resources\Attachments\AttachmentResource;
use App\Http\Resources\PartialUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupMessageResource extends JsonResource
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
            'text' => $this->text,
            'attachments' => $this->when(count($this->attachmentable), AttachmentResource::collection($this->attachmentable)),
            'sender' => new PartialUserResource($this->sender),
        ];
    }
}
