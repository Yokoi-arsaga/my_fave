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
     * 通知が送られているかもテスト
     *
     * @return void
     */
    public function test_store_friend_request_success()
    {
        $friendRequestInfo = [
            'destination_id' => $this->users[2]->id,
            'message' => 'よろしくお願いいたします。',
        ];

        $response = $this->actingAs($this->users[1])->post('/friend/request/store', $friendRequestInfo);

        $response->assertStatus(201);
        $this->assertEquals($response['destination_id'], $friendRequestInfo['destination_id']);

        $user = User::find($this->users[2]->id);
        $this->assertCount(1, $user->notifications);
    }

    /**
     * メッセージが空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_friend_request_failure_by_message_empty()
    {
        $friendRequestInfo = [
            'destination_id' => $this->users[2]->id,
            'message' => '',
        ];

        $response = $this->actingAs($this->users[1])->post('/friend/request/store', $friendRequestInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FriendRequest::all());

        $user = User::find($this->users[2]->id);
        $this->assertEmpty($user->notifications);
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

        $response = $this->actingAs($this->users[1])->post('/friend/request/store', $friendRequestInfo);

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

        $response = $this->actingAs($this->users[1])->post('/friend/request/store', $friendRequestInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(FriendRequest::all());

        $user = User::find($this->users[2]->id);
        $this->assertEmpty($user->notifications);
    }
}
