<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteVideoRequest;
use App\Models\FavoriteVideo;
use App\Modules\ApplicationLogger;
use App\Repositories\FavoriteVideo\FavoriteVideoRepositoryInterface;
use Illuminate\Support\Collection;

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
     * お気に入り動画の登録
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

    /**
     * ユーザーに紐づくお気に入り動画一覧の取得
     *
     * @return Collection
     */
    public function fetch(): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画の一覧取得処理開始');
        $favoriteVideos = $this->favoriteVideoRepository->fetchFavoriteVideos();

        $logger->success();
        return $favoriteVideos;
    }

    /**
     * お気に入り動画情報の更新
     *
     * @param FavoriteVideoRequest $request
     * @param int $id
     * @return FavoriteVideo
     */
    public function update(FavoriteVideoRequest $request, int $id): FavoriteVideo
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画の更新処理開始');
        $favoriteVideo = $this->favoriteVideoRepository->updateFavoriteVideo($id, $request->getVideoUrl(), $request->getVideoName());

        $logger->success();
        return $favoriteVideo;
    }

    /**
     * 御気に入り動画の削除
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画の削除処理開始');
        $this->favoriteVideoRepository->deleteFavoriteVideo($id);

        $logger->success();
    }
}
