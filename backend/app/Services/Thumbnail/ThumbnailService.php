<?php
namespace App\Services\Thumbnail;

use App\Repositories\Thumbnail\ThumbnailRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ThumbnailService implements ThumbnailServiceInterface
{
    private ThumbnailRepositoryInterface $thumbnailRepository;

    /**
     * @param ThumbnailRepositoryInterface $thumbnailRepository
     */
    public function __construct(ThumbnailRepositoryInterface $thumbnailRepository)
    {
        $this->thumbnailRepository = $thumbnailRepository;
    }

    /**
     * @inerhitDoc
     */
    public function storeThumbnail(UploadedFile $file, string $fileString, string $fullFileName, int $userId)
    {
        $thumbnail = DB::transaction(function () use ($file, $fileString, $fullFileName, $userId){
            Storage::disk('s3')->putFileAs('', $file, $fullFileName, 'public');
            return $this->thumbnailRepository->createThumbnail($fileString, $fullFileName, $userId);
        });

        return response($thumbnail, 201);
    }
}
