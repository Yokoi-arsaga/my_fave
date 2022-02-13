<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static YOUTUBE()
 * @method static static TWITTER()
 * @method static static FACEBOOK()
 */
final class MediaType extends Enum
{
    const YOUTUBE = 1;
    const TWITTER = 2;
    const FACEBOOK = 3;
}
