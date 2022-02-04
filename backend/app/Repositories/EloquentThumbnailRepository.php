<?php

namespace App\Repositories;

use App\Models\Thumbnail;
use App\Repositories\Interfaces\ThumbnailRepositoryInterface;

/**
 * class AdminRepositoryImpl 管理者ユーザー関連処理
 * @package App\Repositories\Admin\Concrete
 */
class EloquentThumbnailRepository implements ThumbnailRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function createThumbnail(string $fileString, string $fullFileName, int $userId): Thumbnail
    {
        return Thumbnail::create([
            'file_string' => $fileString,
            'full_file_name' => $fullFileName,
            'user_id' => $userId
        ]);
    }
}
