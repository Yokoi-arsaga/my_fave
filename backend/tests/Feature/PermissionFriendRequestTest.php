<?php

namespace Tests\Feature;

use App\Models\FriendRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class PermissionFriendRequestTest extends TestCase
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
     * フレンド申請許可に成功したテスト
     *
     * @return void
     */
    public function test_permission_friend_request_success()
    {
        $friendRequestInfo = [
            'destination_id' => $this->users[2]->id,
            'message' => 'よろしくお願いいたします。',
        ];

        $friendRequest = $this->actingAs($this->users[1])->post('/api/friend/request/store', $friendRequestInfo);

        $response = $this->actingAs($this->users[2])->post("api/friend/permission/$friendRequest->id");

        $response->assertCreated();
        // ユーザー2のフレンドが追加されているか
        $user = User::find($this->users[2]->id);
        $this->assertCount(1, $user->friends);
        // ユーザー1のフレンドが追加されているか
        $user = User::find($this->users[1]->id);
        $this->assertCount(1, $user->friends);
        // フレンド申請が削除されているか
        $friendRequests = FriendRequest::all();
        $this->assertEmpty($friendRequests);
    }

    /**
     * フレンド申請していない場合に申請許可が失敗することを確認
     *
     * @return void
     */
    public function test_permission_friend_request_failure_by_request_empty()
    {
        $response = $this->actingAs($this->users[2])->post("api/friend/permission/1");

        $response->assertRedirect('/');
        $this->assertEmpty(Friend::all());

        $user = User::find($this->users[1]->id);
        $this->assertEmpty($user->friends);
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
