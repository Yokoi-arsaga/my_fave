<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MultiRegisterFavoriteVideosRequest extends FormRequest
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
            'favorite_video_ids' => ['required', 'array']
        ];
    }

    /**
     * お気に入り動画のID配列
     *
     * @return array
     */
    public function getFavoriteVideoIds(): array
    {
        return $this->input('favorite_video_ids');
    }
}
