<?php

namespace Tests\Feature;

use App\Models\FavoriteVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UpdateFavoriteVideosTest extends TestCase
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
     * お気に入り動画の情報更新に成功したテスト
     *
     * @return void
     */
    public function test_update_favorite_video_success()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $updateFavoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=DmdrWQXtL-U',
            'video_name' => 'サンプル2',
        ];

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$favoriteVideo->id", $updateFavoriteVideoInfo);

        $response->assertStatus(200);

        $updateFavoriteVideo = FavoriteVideo::find($favoriteVideo->id);

        $this->assertEquals($updateFavoriteVideo->video_url, $response['video_url']);
    }

    /**
     * 動画URLが空欄だった場合にお気に入り動画の情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_favorite_video_failure_by_url_empty()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $updateFavoriteVideoInfo = [
            'video_url' => '',
            'video_name' => 'サンプル2',
        ];

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$favoriteVideo->id", $updateFavoriteVideoInfo);

        $response->assertRedirect('/');

        $updateFavoriteVideo = FavoriteVideo::find($favoriteVideo->id);

        $this->assertEquals($updateFavoriteVideo->video_url, $favoriteVideoInfo['video_url']);
    }

    /**
     * 動画名が空欄だった場合にお気に入り動画の情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_favorite_video_failure_by_name_empty()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $updateFavoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=DmdrWQXtL-U',
            'video_name' => '',
        ];

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$favoriteVideo->id", $updateFavoriteVideoInfo);

        $response->assertRedirect('/');

        $updateFavoriteVideo = FavoriteVideo::find($favoriteVideo->id);

        $this->assertEquals($updateFavoriteVideo->video_url, $favoriteVideoInfo['video_url']);
    }

    /**
     * 動画名が空欄だった場合にお気に入り動画の情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_favorite_video_failure_by_format_different()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $updateFavoriteVideoInfo = [
            'video_url' => 'https://www.arsaga.jp/',
            'video_name' => 'サンプル2',
        ];

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$favoriteVideo->id", $updateFavoriteVideoInfo);

        $response->assertRedirect('/');

        $updateFavoriteVideo = FavoriteVideo::find($favoriteVideo->id);

        $this->assertEquals($updateFavoriteVideo->video_url, $favoriteVideoInfo['video_url']);
    }
}
