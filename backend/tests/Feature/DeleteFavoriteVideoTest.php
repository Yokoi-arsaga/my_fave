<?php

namespace Tests\Feature;

use App\Models\FavoriteVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DeleteFavoriteVideoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $arr = [1, 2];
        foreach ($arr as $value) {
            $this->users[$value] = User::factory()->create();
        }
    }

    /**
     * お気に入り動画の削除に成功したテスト
     *
     * @return void
     */
    public function test_delete_favorite_video_success()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $favoriteVideoId = $favoriteVideo['id'];
        $response = $this->actingAs($this->users[1])->delete("/api/favorite/videos/$favoriteVideoId");

        $response->assertStatus(200);

        $favoriteVideos = FavoriteVideo::all();

        $this->assertEmpty($favoriteVideos);
    }

    // TODO:リダイレクトテストはfactory作ってから
}
