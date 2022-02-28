<?php

namespace Tests\Feature;

use App\Models\FriendRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class FetchNotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $arr = [1, 2, 3, 4, 5];
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
            'destination_id' => $this->users[1]->id,
            'message' => 'よろしくお願いいたします。',
        ];

        $response = $this->actingAs($this->users[2])->post('/api/friend/request/store', $friendRequestInfo);

        $response->assertStatus(201);
        $this->assertEquals($response['destination_id'], $friendRequestInfo['destination_id']);

        $user = User::find($this->users[2]->id);
        $this->assertCount(1, $user->notifications);
    }
}
