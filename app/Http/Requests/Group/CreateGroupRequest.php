<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules()
    {
        return [
            'name'=>'required',
            'type'=>'nullable|in:private,public',
            'members'=>'array|nullable',
            'members.*'=>['exists:users,email','distinct']
        ];
    }

    public function withValidator($validator)
    {
        if(!$validator->fails()){
            $validator->after(function ($validator) {

                // if((isset($this->receiver_id) && !empty($this->receiver_id)) && (isset($this->receiver_id) && !empty($this->group_id)))
                    // $validator->errors()->add('error','Invalid request');
            });
        }

    }
}
