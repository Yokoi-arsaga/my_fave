<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThumbnailRequest;
use App\Modules\GenerateFileName;
use App\Services\Thumbnail\ThumbnailService;
use Illuminate\Support\Facades\Auth;

class ThumbnailController extends Controller
{
    private ThumbnailService $thumbnailService;

    public function __construct(ThumbnailService $thumbnailService)
    {
        // 認証が必要
        $this->middleware('auth');
        $this->thumbnailService = $thumbnailService;
    }

    public function store(ThumbnailRequest $request)
    {
        $generateFileName = new GenerateFileName;
        $fileString = $generateFileName->fileString;

        $extension = $request->thumbnail->extension();
        $fullFileName = $fileString.$extension;

        return $this->thumbnailService->storeThumbnail($request->thumbnail, $fileString, $fullFileName, Auth::id());
    }
}
