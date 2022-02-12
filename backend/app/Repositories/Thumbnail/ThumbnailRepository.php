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

    /**
     * @inheritDoc
     */
    public function updateThumbnail(string $fullFileName, int $userId): Thumbnail
    {
        $thumbnail = Thumbnail::where('user_id', $userId)->first();
        $thumbnail->full_file_name = $fullFileName;
        $thumbnail->save();
        return $thumbnail;
    }

    /**
     * @inheritDoc
     */
    public function deleteThumbnail(string $fullFileName): bool
    {
        $thumbnail = Thumbnail::where('full_file_name', $fullFileName)->first();
        return $thumbnail->delete();
    }
}
