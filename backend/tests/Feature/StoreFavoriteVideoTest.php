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

        $response = $this->actingAs($this->users[1])->post('/api/favorite-video/store', $favoriteVideoInfo);

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

        $response = $this->actingAs($this->users[1])->post('/api/favorite-video/store', $favoriteVideoInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FavoriteVideo::all());
    }

    /**
     * 送信先IDが空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_friend_request_failure_by_destination_empty()
    {
        $friendRequestInfo = [
            'destination_id' => '',
            'message' => 'よろしくお願いいたします。',
        ];

        $response = $this->actingAs($this->users[1])->post('/api/friend/request/store', $friendRequestInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FriendRequest::all());

        $user = User::find($this->users[2]->id);
        $this->assertEmpty($user->notifications);
    }

    /**
     * 送信先IDが自身だった場合リダイレクトされることを確認
     *
     * @return void
     */
    public function test_store_friend_request_failure_by_destination_mine()
    {
        $friendRequestInfo = [
            'destination_id' => $this->users[1]->id,
            'message' => 'よろしくお願いいたします。',
        ];

        $response = $this->actingAs($this->users[1])->post('/api/friend/request/store', $friendRequestInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FriendRequest::all());

        $user = User::find($this->users[2]->id);
        $this->assertEmpty($user->notifications);
    }

    /**
     * 送信先IDが存在しない場合リダイレクトされることを確認
     *
     * @return void
     */
    public function test_store_friend_request_failure_by_destination_not_exist()
    {
        $friendRequestInfo = [
            'destination_id' => 3,
            'message' => 'よろしくお願いいたします。',
        ];

        $response = $this->actingAs($this->users[1])->post('/api/friend/request/store', $friendRequestInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FriendRequest::all());

        $user = User::find($this->users[2]->id);
        $this->assertEmpty($user->notifications);
    }
}
