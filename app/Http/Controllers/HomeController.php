<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest\SearchUserRequest;
use App\Http\Resources\ChatedUserResource;
use App\Http\Resources\GroupListingResource;
use App\Http\Resources\GroupUserListingResource;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $user=$request->user;
        $groups=$user->load('groups')->groups;
        $chated_users=User::whereHas("send_messages",function($query) use($user){
            return $query->where("receiver_id",$user->_id);
        })->orWhereHas('received_messages',function($query) use($user){
            return $query->where("sender_id",$user->_id);
        })->get();
        return response()->success("Users and Groups fetched successfully",["users"=>ChatedUserResource::collection($chated_users),"groups"=>GroupListingResource::collection($groups)]);
    }
    public function searchUser(SearchUserRequest $request)
    {
        $auth_user=app(Request::class)->user;
        if($request->get('name_or_email') && !empty($request->name_or_email))
        {
            $name_or_email=$request->name_or_email;
            $users=User::where("_id","!=",$auth_user->_id)->where(function($query) use($name_or_email){
                return $query->where('name','like',"%".Str::lower($name_or_email)."%")->orWhere('email','like',"%".Str::lower($name_or_email)."%")->get();
            })->paginate(request('per_page',8));
        }
        else{
            $users=User::where("_id","!=",$auth_user->_id)->paginate(request('per_page',8));
        }
        return response()->success("Users and Groups fetched successfully",["pagination"=>paginator($users->toArray()),"users"=>ChatedUserResource::collection($users)]);
    }
}
