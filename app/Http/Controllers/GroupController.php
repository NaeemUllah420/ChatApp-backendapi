<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Resources\Group\GroupResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function create(Request $default_request, CreateGroupRequest $request)
    {
        $members = User::whereIn('email', $request->members)->pluck('_id');
        $group = Group::create([
            'name' => $request->name,
            'owner_id' => $default_request->user->_id,
            'type' => request('type', 'private'),
        ]);
        $group->users()->attach(array_merge([$default_request->user->_id], $members->toArray()));
        if ($group) {
            $response = response()->created("Group account created successfully", new GroupResource($group->load("users")));
        } else {
            $response = response()->failed("Failed to create the group");
        }
        return $response;
    }
}
