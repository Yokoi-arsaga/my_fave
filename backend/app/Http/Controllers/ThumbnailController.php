<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThumbnailRequest;
use App\Models\Thumbnail;
use App\Modules\GenerateFileName;
use App\Services\Thumbnail\ThumbnailServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Modules\ApplicationLogger;

/**
 * ユーザーのサムネイルに関するコントローラー
 *
 */
class ThumbnailController extends Controller
{
    private ThumbnailServiceInterface $thumbnailService;

    /**
     * @param ThumbnailServiceInterface $thumbnailService
     */
    public function __construct(ThumbnailServiceInterface $thumbnailService)
    {
        $this->thumbnailService = $thumbnailService;
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * サムネイルの登録
     *
     * @param ThumbnailRequest $request
     * @return Thumbnail $response
     */
    public function store(ThumbnailRequest $request)
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('ストレージ保存の際につけるファイル名の生成');
        $generateFileName = new GenerateFileName;
        $fileString = $generateFileName->fileString;
        $extension = $request->thumbnail->extension();
        $fullFileName = $fileString.'.'.$extension;

        $logger->write('サムネイルの投稿処理開始');
        $thumbnail = $this->thumbnailService->storeThumbnail($request->thumbnail, $fileString, $fullFileName, Auth::id());

        $logger->success();
        return $thumbnail;
    }

    /**
     * サムネイルの変更
     *
     * @param ThumbnailRequest $request
     * @return Thumbnail $response
     */
    public function change(ThumbnailRequest $request)
    {
        $logger = new ApplicationLogger(__METHOD__);

        $thumbnail = Thumbnail::where('user_id', Auth::id())->first();

        $extension = $request->thumbnail->extension();
        $fullFileName = $thumbnail->file_string.'.'.$extension;

        $logger->write('サムネイルの変更処理開始');
        $response = $this->thumbnailService->changeThumbnail($request->thumbnail, $thumbnail->file_string, $fullFileName, $thumbnail->full_file_name, Auth::id());

        $logger->success();
        return $response;
    }

    /**
     * サムネイルの削除
     *
     * @return mixed
     */
    public function delete()
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('サムネイルの削除処理開始');
        $thumbnail = Thumbnail::where('user_id', Auth::id())->first();

        $response = $this->thumbnailService->deleteThumbnail($thumbnail->full_file_name);

        $logger->success();
        return $response;
    }
}
