<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteVideoRequest;
use App\Http\Requests\ParentFolderRequest;
use App\Models\FavoriteVideo;
use App\Models\ParentFolder;
use App\Modules\ApplicationLogger;
use App\Repositories\ParentFolder\ParentFolderRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * お気に入り動画に関するコントローラー
 *
 */
class ParentFolderController extends Controller
{
    private ParentFolderRepositoryInterface $parentFolderRepository;

    /**
     * @param ParentFolderRepositoryInterface $parentFolderRepository
     */
    public function __construct(
        ParentFolderRepositoryInterface $parentFolderRepository
    )
    {
        $this->parentFolderRepository = $parentFolderRepository;
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * お気に入り動画の登録
     *
     * @param ParentFolderRequest $request
     * @return ParentFolder
     */
    public function store(ParentFolderRequest $request): ParentFolder
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('親フォルダーの登録処理開始');
        $favoriteVideo = $this->parentFolderRepository->storeParentFolder($request);

        $logger->success();
        return $favoriteVideo;
    }
}
