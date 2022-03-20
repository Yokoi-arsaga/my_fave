<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\FriendRequest;
use App\Models\Friend;

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
}
