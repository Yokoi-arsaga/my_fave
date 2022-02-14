<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * プロフィール編集が上手く行えていることを確認
     *
     * @return void
     */
    public function test_edit_profile_success()
    {
        $profileInfo = [
            'name' => 'テスト太郎',
            'description' => 'よろしくお願いいたします',
            'location' => '東京都'
        ];

        $response = $this->actingAs($this->user)->post('/profile/store', $profileInfo);

        $user = User::first();

        $response->assertStatus(200);
        $this->assertEquals($response['name'], $user['name']);
    }
}
