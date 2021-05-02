<?php

namespace App\Http\Requests\Message;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SendMessageRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'text'=>["nullable"],
            'receiver_id'=>["nullable","exists:users,_id","min:2","max:25","not_in:".$request->user->_id],
            'group_id'=>["nullable","exists:groups,_id","min:2","max:25"],
        ];
    }

    public function withValidator($validator)
    {
        if(!$validator->fails()){
            $validator->after(function ($validator) {
                if((isset($this->receiver_id) && !empty($this->receiver_id)) && (isset($this->receiver_id) && !empty($this->group_id)))
                    $validator->errors()->add('error','Invalid request');
            });
        }

    }
}
