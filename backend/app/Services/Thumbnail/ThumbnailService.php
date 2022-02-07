<?php
namespace App\Services\Thumbnail;

use App\Repositories\Thumbnail\ThumbnailRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        Storage::disk('s3')->putFileAs('', $file, $fullFileName, 'public');

        // FIXME:ファイルアップロード処理があるためbeginTransactionを使用しているが何かいい方法があれば書き換えたい
        DB::beginTransaction();
        try {
            $response = $this->thumbnailRepository->createThumbnail($fileString, $fullFileName, $userId);
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();

            Storage::disk('s3')->delete($fullFileName);
            throw $e;
        }

        return $response;
    }
}