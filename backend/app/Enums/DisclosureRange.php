<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DisclosureRange extends Enum
{
    const PUBLIC = 1;
    const FRIEND = 2;
    const PRIVATE = 3;

    /**
     * 公開範囲名を取得
     *
     * @param $value
     * @return string
     */
    public static function getDescription($value): string
    {
        if ($value === self::PUBLIC) {
            return '全体公開';
        }
        if ($value === self::FRIEND) {
            return 'フレンドに公開';
        }
        if ($value === self::PRIVATE) {
            return '非公開';
        }

        return parent::getDescription($value);
    }

    /**
     * 公開範囲IDを取得
     *
     * @param string $key
     * @return int|mixed
     */
    public static function getValue(string $key)
    {
        if ($key === '全体公開'){
            return self::PUBLIC;
        }
        if ($key === 'フレンドに公開'){
            return self::FRIEND;
        }
        if ($key === '非公開'){
            return self::PRIVATE;
        }
        return parent::getValue($key);
    }
}
