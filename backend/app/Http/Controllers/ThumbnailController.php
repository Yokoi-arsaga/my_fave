<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThumbnailRequest;
use App\Models\Thumbnail;
use App\Modules\GenerateFileName;
use App\Services\Thumbnail\ThumbnailServiceInterface;
use Illuminate\Support\Facades\Auth;

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
        $generateFileName = new GenerateFileName;
        $fileString = $generateFileName->fileString;

        $extension = $request->thumbnail->extension();
        $fullFileName = $fileString.'.'.$extension;

        return $this->thumbnailService->storeThumbnail($request->thumbnail, $fileString, $fullFileName, Auth::id());
    }
}
