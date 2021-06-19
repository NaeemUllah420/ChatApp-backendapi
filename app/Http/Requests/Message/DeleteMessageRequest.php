<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class DeleteMessageRequest extends FormRequest
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
            'message_id' => 'required',
            'attachments' => 'nullable|array',
            'attachments.*' => 'distinct',
            'complete' => 'required|boolean',
        ];
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                $message = app(Request::class)->user->send_messages()->where('_id', $this->message_id)->first();
                if (empty($message)) {
                    $validator->errors()->add('error', 'Invalid request');
                }
                $this->merge([
                    'message_detail' => $message,
                ]);
            });
        }

    }
}
