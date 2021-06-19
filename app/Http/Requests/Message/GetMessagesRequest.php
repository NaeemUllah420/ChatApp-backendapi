<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GetMessagesRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
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
    public function rules()
    {
        return [
            'user_id' => 'nullable|exists:users,_id',
            'group_id' => 'nullable',
        ];
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                $group = app(Request::class)->user->groups()->where("_id", $this->group_id)->first();
                if (isset($this->group_id) && empty($group)) {
                    $validator->errors()->add('error', 'Invalid group id given');
                }
                if ((isset($this->user_id) && !empty($this->user_id)) && (isset($this->group_id) && !empty($this->group_id))) {
                    $validator->errors()->add('error', 'Invalid request');
                }
                if (empty($this->user_id) && empty($this->group_id)) {
                    $validator->errors()->add('error', 'receiver_id or group_id required');
                }
                $this->merge(['group_detail' => $group]);

            });
        }
    }

}
