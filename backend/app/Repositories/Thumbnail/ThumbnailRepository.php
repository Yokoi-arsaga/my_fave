<?php

namespace App\Repositories\Thumbnail;

use App\Models\Thumbnail;

/**
 * class AdminRepositoryImpl 管理者ユーザー関連処理
 * @package App\Repositories\Admin\Concrete
 */
class ThumbnailRepository implements ThumbnailRepositoryInterface
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
