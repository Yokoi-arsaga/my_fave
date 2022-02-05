<?php

namespace App\Repositories\Interfaces;

use App\Models\Thumbnail;

/**
 * interface ThumbnailRepository ユーザーのサムネイル処理
 * @package App\Repositories\EloquentThumbnailRepository
 */
interface ThumbnailRepositoryInterface
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
