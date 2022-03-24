<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class FetchFavoriteVideosTest extends TestCase
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
     * お気に入り動画一覧の取得に成功したテスト
     *
     * @return void
     */
    public function test_fetch_favorite_videos_success()
    {
        $favoriteVideoInfos = [
            [
                'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
                'video_name' => 'サンプル1',
            ],
            [
                'video_url' => 'https://www.youtube.com/watch?v=oq20w95OlSY',
                'video_name' => 'サンプル2',
            ],
            [
                'video_url' => 'https://www.youtube.com/watch?v=AElYX7XQ-7s',
                'video_name' => 'サンプル3',
            ],
        ];

        foreach ($favoriteVideoInfos as $videoInfo){
            $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $videoInfo);
        }

        $response = $this->actingAs($this->users[1])->get('/api/favorite/videos/fetch');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * 認証されておらずリダイレクトされることを確認するテスト
     *
     * @return void
     */
    public function test_fetch_favorite_videos_failure_by_not_auth()
    {
        $response = $this->get('/api/favorite/videos/fetch');

        $response->assertRedirect('/login');
    }
}
