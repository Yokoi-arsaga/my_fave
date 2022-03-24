<?php

namespace Tests\Feature;

use App\Models\FavoriteVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class StoreFavoriteVideoTest extends TestCase
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
     * お気に入り動画の投稿に成功したテスト
     *
     * @return void
     */
    public function test_store_favorite_video_success()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $response = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $response->assertStatus(201);
        $this->assertEquals($response['video_url'], $favoriteVideoInfo['video_url']);
    }

    /**
     * 動画URLが空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_favorite_video_failure_by_url_empty()
    {
        $favoriteVideoInfo = [
            'video_url' => '',
            'video_name' => 'サンプル',
        ];

        $response = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FavoriteVideo::all());
    }

    /**
     * 動画名が空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_favorite_video_failure_by_name_empty()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => '',
        ];

        $response = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FavoriteVideo::all());
    }

    /**
     * 動画URLがyoutube動画の形式でない場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_favorite_video_failure_by_format_different()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.arsaga.jp/',
            'video_name' => 'サンプル',
        ];

        $response = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FavoriteVideo::all());
    }
}
