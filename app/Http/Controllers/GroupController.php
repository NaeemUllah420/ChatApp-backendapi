<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group\CreateGroupRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function create(Request $default_request, CreateGroupRequest $request)
    {
        $owner_id = $default_request->user->_id;
        // return $owner_id;
        $group = Group::where('_id', '608e8c9a07530000360052fc')->first();
        return $group->users()->attach($default_request->user);
        // return $default_request->user->groups()->get();
        // return request('type', 'private');
        // $members = User::whereIn('email', $request->members)->pluck('_id');
        // return $members;
        // $group = Group::create([
        //     'name' => $request->name,
        //     'owner_id' => $default_request->user->_id,
        //     'type' => request('type', 'private'),
        // ]);
        // $group->users()->attach([$default_request->user->_id], ['role_id', Role::where('name', 'user')->value('_id')]);
        // if (count($members)) {
        //     $group->users()->attach($members, ['role_id' => Role::where('name', 'user')->value('_id')]);
        // }
        // return $group;
    }
}
