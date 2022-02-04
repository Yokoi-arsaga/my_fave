<?php
namespace App\Services\Thumbnail;

use App\Models\Thumbnail;
use Illuminate\Http\UploadedFile;

interface ThumbnailServiceInterface
{
    public function storeThumbnail(UploadedFile $file, string $fileString, string $fullFileName, int $userId): Thumbnail;
}
