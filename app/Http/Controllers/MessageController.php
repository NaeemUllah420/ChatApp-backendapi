<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\DeleteMessageRequest;
use App\Http\Requests\Message\GetMessagesRequest;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Requests\Message\UpdateMessageRequest;
use App\Http\Resources\Message\GroupMessageResource;
use App\Http\Resources\Message\MessageResourcse;
use App\Http\Resources\Message\OneToOneMessageResource;
use App\Models\Attachment;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function sendMessage(Request $default_request, SendMessageRequest $request)
    {
        $group = app(Request::class)->user->groups()->where("_id", $request->group_id)->first();
        $message_data = request(['text', 'receiver_id', 'group_id']);
        $message_data = array_merge($message_data, ['sender_id' => $default_request->user->_id]);
        $message = Message::create($message_data);
        empty($request->attachments) ?: Attachment::formDataFile($request, $message);
        return response()->created("message sent successfully", new MessageResourcse($message->load(['sender', 'receiver', 'group', 'attachmentable'])));
    }

    public function updateMessage(Request $default_request, UpdateMessageRequest $request)
    {
        $message = $request->message_detail;
        $message->update([
            'text' => $request->get('text', $message->text),
        ]);
        empty($request->attachments) ?: Attachment::formDataFile($request, $message);
        return response()->created("message updated successfully", new MessageResourcse($message->load(['sender', 'receiver', 'group', 'attachmentable'])));
    }

    public function deleteMessage(Request $default_request, DeleteMessageRequest $request)
    {
        $message = $request->message_detail;
        $attachments = $message->attachmentable();
        $attachments = (request('complete', false)) ? $attachments : $attachments->whereIn("_id", $request->get("attachments", []));
        Attachment::deleteAttachments($attachments->get());
        $attachments_deleted = $attachments->delete();
        $response = response()->failed("Failed to delete the message");
        if (request('complete', false) && $message->delete()) {
            $response = response()->success("Message deleted successfully");
        } else if ($attachments_deleted) {
            $response = response()->success("Attachments deleted successfully");
        }
        return $response;
    }

    public function getMessages(Request $default_request, GetMessagesRequest $request)
    {
        if ($request->get('user_id')) {
            $messages=Message::where(function($query) use ($default_request, $request){
                return $query->where([['sender_id','=',$default_request->user->_id],['receiver_id','=',$request->user_id]])
                ->orWhere([['sender_id','=',$request->user_id],['receiver_id','=',$default_request->user->_id]]);
            })
            ->get();
            $messages = count($messages) ? OneToOneMessageResource::collection($messages) : $messages;
            $response = response()->success("chat fetched successfully", $messages);

        } else if ($request->get('group_id')) {
            $messages = Message::query()->where('group_id', $request->group_id)
                ->with('sender', 'attachmentable')
                ->get();
            $messages = count($messages) ? GroupMessageResource::collection($messages) : $messages;
            $response = response()->success("chat fetched successfully", $messages);
        }
        return $response;
    }

    public function getPrivateFiles($receiver_or_group, $random, $file, $extension)
    {
        $path = "$receiver_or_group/$random/$file.$extension";
        if (Storage::disk('private')->exists($path)) {
            return response()->download(storage_path('app/private') . "/$path"); // return Storage::disk('private')->download($path);
        }
    }

    public function getPublicFiles($receiver_or_group, $random, $file_with_extension)
    {
        $path = storage_path('app/public') . "/$receiver_or_group/$random/$file_with_extension";
        if (File::exists($path)) {
            $response = response()->make(File::get($path), HttpResponse::HTTP_OK, ["content-type" => File::mimeType($path)]);
        } else {
            $response = response()->failed("Invalid data");
        }
        return $response;
    }

}
