<?php

namespace Tests\Feature;

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
        foreach ($arr as $value){
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

        $response = $this->actingAs($this->users[1])->post('/friend/request/store',$friendRequestInfo);

        $response->assertStatus(201);
        $this->assertEquals($response['destination_id'], $friendRequestInfo['destination_id']);
    }
}
