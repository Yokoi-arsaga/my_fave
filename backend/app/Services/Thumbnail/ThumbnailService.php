<?php
namespace App\Services\Thumbnail;

use App\Models\Thumbnail;
use App\Repositories\Interfaces\ThumbnailRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

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
        DB::transaction(function () use ($file, $fileString, $fullFileName, $userId){
            Storage::disk('s3')->putFileAs('', $file, $fullFileName, 'public');
            $this->thumbnailRepository->createThumbnail($fileString, $fullFileName, $userId);
        });

        return response();
    }
}
