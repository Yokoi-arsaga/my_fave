<?php

namespace App\Repositories\Interfaces;

use App\Models\Thumbnail;

/**
 * interface AdminRepository 管理者ユーザー関連処理
 * @package App\Repositories\Admin
 */
interface ThumbnailRepository
{
    /**
     * サムネイルを作成。
     * @param  string $fileString
     * @param  string $fullFileName
     * @param  int $userId
     * @return Thumbnail
     */
    public function createThumbnail(string $fileString, string $fullFileName, int $userId): Thumbnail;
}
