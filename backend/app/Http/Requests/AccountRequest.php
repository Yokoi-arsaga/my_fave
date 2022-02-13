<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'media_id' => ['required', 'numeric', 'between:1,3'],
            'account_url' => ['required', 'string', 'max:500'],
        ];
    }

    /**
     * メディアID
     *
     * @return int
     */
    public function getMediaId(): int
    {
        return $this->input('media_id');
    }

    /**
     * アカウントURL
     *
     * @return string
     */
    public function getAccountUrl(): string
    {
        return $this->input('account_url');
    }
}
