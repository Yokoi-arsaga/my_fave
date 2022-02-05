<?php
namespace App\Services\Thumbnail;

use App\Models\Thumbnail;
use Illuminate\Http\UploadedFile;

interface ThumbnailServiceInterface
{
    /**
     * サムネイルの登録処理の整合性をとる
     *
     * @param UploadedFile $file
     * @param string $fileString
     * @param string $fullFileName
     * @param int $userId
     * @return Thumbnail
     */
    public function storeThumbnail(UploadedFile $file, string $fileString, string $fullFileName, int $userId): Thumbnail;
}
