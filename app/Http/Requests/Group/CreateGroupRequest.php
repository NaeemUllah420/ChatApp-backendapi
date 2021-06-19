<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'name' => 'required',
            'type' => 'nullable|in:private,public',
            'members' => 'array|nullable',
            'members.*' => ['exists:users,email', 'distinct', 'not_in:' . $request->user->email],
        ];
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                if (app(Request::class)->user->groups()->where("name", $this->name)->get()->count()) {
                    $validator->errors()->add('error', 'Group name already exists');
                }
            });
        }

    }
}
