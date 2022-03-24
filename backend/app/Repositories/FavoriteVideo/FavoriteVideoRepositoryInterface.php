<?php

namespace App\Repositories\FavoriteVideo;

use App\Models\FavoriteVideo;

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
}
