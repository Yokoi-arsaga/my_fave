<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class VideoUrl implements Rule
{
    const YOUTUBE_PREFIX = 'https://www.youtube.com/watch?v=';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $urlCount = strlen(self::YOUTUBE_PREFIX);
        return substr($value, 0, $urlCount) === self::YOUTUBE_PREFIX;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'youtubeの動画URLとして正しくありません。';
    }
}
