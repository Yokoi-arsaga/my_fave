<?php

namespace App\Repositories\FavoriteVideo;

use App\Models\FavoriteVideo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class FavoriteVideoRepository implements FavoriteVideoRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function storeFavoriteVideo(string $videoUrl, string $videoName): FavoriteVideo
    {
        return FavoriteVideo::create([
            'user_id' => Auth::id(),
            'video_url' => $videoUrl,
            'video_name' => $videoName
        ]);
    }

    /**
     * @inheritDoc
     */
    public function fetchFavoriteVideos(): Collection
    {
        return FavoriteVideo::where('user_id', Auth::id())->get();
    }
}
