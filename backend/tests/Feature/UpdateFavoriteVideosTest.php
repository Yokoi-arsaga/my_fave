<?php

namespace Tests\Feature;

use App\Models\FavoriteVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
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
        [$favoriteVideoInfo, $favoriteVideo] = $this->common_preparation();

        $updateFavoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=DmdrWQXtL-U',
            'video_name' => 'サンプル2',
        ];

        $favoriteVideoId = $favoriteVideo['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$favoriteVideoId", $updateFavoriteVideoInfo);

        $response->assertStatus(200);

        $updateFavoriteVideo = FavoriteVideo::find($favoriteVideoId);

        $this->assertEquals($updateFavoriteVideo->video_url, $response['video_url']);
    }

    /**
     * 動画URLが空欄だった場合にお気に入り動画の情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_favorite_video_failure_by_url_empty()
    {
        [$favoriteVideoInfo, $favoriteVideo] = $this->common_preparation();

        $updateFavoriteVideoInfo = [
            'video_url' => '',
            'video_name' => 'サンプル2',
        ];

        $this->common_validation_logic($favoriteVideo, $updateFavoriteVideoInfo, $favoriteVideoInfo);
    }

    /**
     * 動画名が空欄だった場合にお気に入り動画の情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_favorite_video_failure_by_name_empty()
    {
        [$favoriteVideoInfo, $favoriteVideo] = $this->common_preparation();

        $updateFavoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=DmdrWQXtL-U',
            'video_name' => '',
        ];

        $this->common_validation_logic($favoriteVideo, $updateFavoriteVideoInfo, $favoriteVideoInfo);
    }

    /**
     * 動画名が空欄だった場合にお気に入り動画の情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_favorite_video_failure_by_format_different()
    {
        [$favoriteVideoInfo, $favoriteVideo] = $this->common_preparation();

        $updateFavoriteVideoInfo = [
            'video_url' => 'https://www.arsaga.jp/',
            'video_name' => 'サンプル2',
        ];

        $this->common_validation_logic($favoriteVideo, $updateFavoriteVideoInfo, $favoriteVideoInfo);
    }

    /**
     * テスト実行前の準備
     *
     * @return array
     */
    private function common_preparation(): array
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);
        return [$favoriteVideoInfo, $favoriteVideo];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param TestResponse $favoriteVideo
     * @param array $updateFavoriteVideoInfo
     * @param array $favoriteVideoInfo
     * @return void
     */
    private function common_validation_logic(TestResponse $favoriteVideo, array $updateFavoriteVideoInfo, array $favoriteVideoInfo)
    {
        $favoriteVideoId = $favoriteVideo['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$favoriteVideoId", $updateFavoriteVideoInfo);

        $response->assertRedirect('/');

        $updateFavoriteVideo = FavoriteVideo::find($favoriteVideoId);

        $this->assertEquals($updateFavoriteVideo->video_url, $favoriteVideoInfo['video_url']);
    }
}
