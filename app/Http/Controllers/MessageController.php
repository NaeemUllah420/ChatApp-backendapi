<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Resources\Message\MessageResourcse;
use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function sendMessage(Request $default_request,SendMessageRequest $request)
    {
        $message_data=request(['text','receiver_id','group_id']);
        $message_data=array_merge($message_data,['sender_id'=>$default_request->user->_id]);
        $message=Message::create($message_data);
        Attachment::formDateFile($request,$message);
        return response()->created("message sent successfully",new MessageResourcse($message->load(['sender','receiver','group','attachmentable'])));
    }


    public function getFiles($receiver_or_group,$random,$file,$extension)
    {
        $path="$receiver_or_group/$random/$file.$extension";
        if(Storage::disk('private')->exists($path))
        {
            return response()->download(storage_path('app/private')."/$path");  // return Storage::disk('private')->download($path);
        }
    }

}
