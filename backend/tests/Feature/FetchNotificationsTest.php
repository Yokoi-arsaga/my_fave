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
     * 通知一覧の取得に成功したテスト
     *
     * @return void
     */
    public function test_fetch_notifications_success()
    {
        $friendRequestInfo = [
            'destination_id' => $this->users[1]->id,
            'message' => 'よろしくお願いいたします。',
        ];

        $this->actingAs($this->users[2])->post('/api/friend/request/store', $friendRequestInfo);
        $this->actingAs($this->users[3])->post('/api/friend/request/store', $friendRequestInfo);

        $response = $this->actingAs($this->users[1])->get('/api/notifications');

        $response->assertStatus(200);

        $this->assertCount(2, $response->notifications);
    }
}
