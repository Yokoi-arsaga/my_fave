<?php

namespace App\Repositories\FavoriteVideo;

use App\Models\FavoriteVideo;
use Illuminate\Support\Collection;

interface FavoriteVideoRepositoryInterface
{
    /**
     * インサート
     *
     * @param string $videoUrl
     * @param string $videoName
     * @return FavoriteVideo
     */
    public function storeFavoriteVideo(string $videoUrl, string $videoName): FavoriteVideo;

    /**
     * ユーザーに紐づくお気に入り動画一覧取得
     *
     * @return Collection
     */
    public function fetchFavoriteVideos(): Collection;
}
