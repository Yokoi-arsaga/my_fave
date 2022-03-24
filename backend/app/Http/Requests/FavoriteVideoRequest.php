<?php

namespace App\Http\Requests;

use App\Rules\VideoUrl;
use Illuminate\Foundation\Http\FormRequest;

class FavoriteVideoRequest extends FormRequest
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
            'video_url' => ['required', 'string', new VideoUrl()],
            'video_name' => ['required', 'string', 'max:100']
        ];
    }

    /**
     * 動画URL
     *
     * @return string
     */
    public function getVideoUrl(): string
    {
        return $this->input('video_url');
    }

    /**
     * 動画名
     *
     * @return string
     */
    public function getVideoName(): string
    {
        return $this->input('video_name');
    }
}
