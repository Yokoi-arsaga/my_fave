<?php

namespace Tests\Feature;

use App\Models\FriendRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class StoreFriendRequestTest extends TestCase
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
     * フレンド申請投稿に成功したテスト
     *
     * @return void
     */
    public function test_store_friend_request_success()
    {
        $friendRequestInfo = [
            'destination_id' => 2,
            'message' => 'よろしくお願いいたします。',
        ];

        $response = $this->actingAs($this->users[1])->post('/friend/request/store', $friendRequestInfo);

        $response->assertStatus(201);
        $this->assertEquals($response['destination_id'], $friendRequestInfo['destination_id']);
    }

    /**
     * メッセージが空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_friend_request_failure_by_message_empty()
    {
        $friendRequestInfo = [
            'destination_id' => 2,
            'message' => '',
        ];

        $response = $this->actingAs($this->users[1])->post('/friend/request/store', $friendRequestInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FriendRequest::all());
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

        $response = $this->actingAs($this->users[1])->post('/friend/request/store', $friendRequestInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FriendRequest::all());
    }

    /**
     * 送信先IDが空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_friend_request_failure_by_destination_string()
    {
        $friendRequestInfo = [
            'destination_id' => 1,
            'message' => 'よろしくお願いいたします。',
        ];

        $response = $this->actingAs($this->users[1])->post('/friend/request/store', $friendRequestInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FriendRequest::all());
    }
}
