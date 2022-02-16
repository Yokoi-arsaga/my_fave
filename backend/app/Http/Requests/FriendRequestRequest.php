<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FriendRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => ['required', 'string', 'max:100']
        ];
    }

    /**
     * 友達申請のメッセージ
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->input('message');
    }
}
