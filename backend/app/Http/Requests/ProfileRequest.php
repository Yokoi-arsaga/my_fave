<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:60'],
            'description' => ['required', 'string', 'max:500'],
            'location' => ['required', 'string', 'max:100']
        ];
    }

    /**
     * 名前
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->input('name');
    }

    /**
     * 紹介文
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->input('description');
    }

    /**
     * 位置情報
     *
     * @return string
     */
    public function getLocation(): string
    {
        return $this->input('location');
    }
}
