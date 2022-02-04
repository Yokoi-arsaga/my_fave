<?php
namespace App\Services\Thumbnail;

use App\Models\Thumbnail;
use App\Repositories\Interfaces\ThumbnailRepositoryInterface;
use App\Services\Thumbnail\ThumbnailServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class ThumbnailService implements ThumbnailServiceInterface
{
    private ThumbnailRepositoryInterface $thumbnailRepository;

    public function __construct(ThumbnailRepositoryInterface $thumbnailRepository)
    {
        $this->thumbnailRepository = $thumbnailRepository;
    }

    public function storeThumbnail(UploadedFile $file, string $fileString, string $fullFileName, int $userId): Thumbnail
    {
        Storage::disk('s3')->putFileAs('', $file, $fullFileName, 'public');

        DB::beginTransaction();

        try {
            $thumbnail = $this->thumbnailRepository->createThumbnail($fileString, $fullFileName, $userId);
            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            Storage::disk('s3')->delete($fullFileName);

            throw $exception;
        }

        return $thumbnail;
    }
}
