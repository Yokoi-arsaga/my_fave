<?php

namespace App\Repositories\Thumbnail;

use App\Models\Thumbnail;

/**
 * interface ThumbnailRepository ユーザーのサムネイル処理
 * @package App\Repositories\ThumbnailRepository
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

    /**
     * サムネイルを更新。
     * @param  string $fullFileName
     * @param  int $userId
     * @return Thumbnail
     */
    public function updateThumbnail(string $fullFileName, int $userId): Thumbnail;
}
