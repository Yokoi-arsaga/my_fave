<?php
namespace App\Services\Thumbnail;

use App\Repositories\Thumbnail\ThumbnailRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ThumbnailService implements ThumbnailServiceInterface
{
    private ThumbnailRepositoryInterface $thumbnailRepository;
    private UserRepositoryInterface $userRepository;

    /**
     * @param ThumbnailRepositoryInterface $thumbnailRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(ThumbnailRepositoryInterface $thumbnailRepository, UserRepositoryInterface $userRepository)
    {
        $this->thumbnailRepository = $thumbnailRepository;
        $this->userRepository = $userRepository;
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
            $this->userRepository->toggleThumbnailFlag(true);
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();

            Storage::disk('s3')->delete($fullFileName);
            throw $e;
        }

        return $response;
    }

    /**
     * @inerhitDoc
     */
    public function changeThumbnail(UploadedFile $file, string $fileString, string $fullFileName, string $currentFileName, int $userId)
    {
        // rollback時元に戻すため
        $currentFile = Storage::disk('s3')->get($currentFileName);
        Storage::disk('s3')->delete($currentFileName);

        Storage::disk('s3')->putFileAs('', $file, $fullFileName, 'public');

        // FIXME:ファイルアップロード処理があるためbeginTransactionを使用しているが何かいい方法があれば書き換えたい
        DB::beginTransaction();
        try {
            $response = $this->thumbnailRepository->updateThumbnail($fullFileName, $userId);
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();

            Storage::disk('s3')->delete($fullFileName);
            Storage::disk('s3')->putFileAs('', $currentFile, $currentFileName, 'public');
            throw $e;
        }

        return $response;
    }

    /**
     * @inerhitDoc
     */
    public function deleteThumbnail(string $currentFileName)
    {
        // rollback時元に戻すため
        $rollBackFile = Storage::disk('s3')->get($currentFileName);
        Storage::disk('s3')->delete($currentFileName);

        // FIXME:ファイルアップロード処理があるためbeginTransactionを使用しているが何かいい方法があれば書き換えたい
        DB::beginTransaction();
        try {
            $response = $this->thumbnailRepository->deleteThumbnail($currentFileName);
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();

            Storage::disk('s3')->putFileAs('', $rollBackFile, $currentFileName, 'public');
            throw $e;
        }

        return $response;
    }
}
