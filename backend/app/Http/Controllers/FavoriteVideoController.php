<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteVideoRequest;
use App\Models\FavoriteVideo;
use App\Modules\ApplicationLogger;
use App\Repositories\FavoriteVideo\FavoriteVideoRepositoryInterface;
use App\Repositories\FriendRequest\FriendRequestRepositoryInterface;
use App\Repositories\Friend\FriendRepositoryInterface;

/**
 * お気に入り動画に関するコントローラー
 *
 */
class FavoriteVideoController extends Controller
{
    private FavoriteVideoRepositoryInterface $favoriteVideoRepository;

    /**
     * @param FavoriteVideoRepositoryInterface $favoriteVideoRepository
     */
    public function __construct(
        FavoriteVideoRepositoryInterface $favoriteVideoRepository
    )
    {
        $this->favoriteVideoRepository = $favoriteVideoRepository;
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * フレンド申請の登録
     *
     * @param FavoriteVideoRequest $request
     * @return FavoriteVideo
     */
    public function store(FavoriteVideoRequest $request): FavoriteVideo
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画の登録処理開始');
        $favoriteVideo = $this->favoriteVideoRepository->storeFavoriteVideo($request->getVideoUrl(), $request->getVideoName());

        $logger->success();
        return $favoriteVideo;
    }
}
