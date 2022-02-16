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
            'message' => ['required', 'string', 'max:100'],
            'destination_id' => ['required', 'numeric']
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

    /**
     * 申請先のユーザーID
     *
     * @return int
     */
    public function getDestinationId(): int
    {
        return $this->input('destination_id');
    }
}
