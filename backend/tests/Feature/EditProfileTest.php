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

        $response = $this->actingAs($this->user)->post('/api/profile/store', $profileInfo);

        $user = User::first();

        $response->assertStatus(200);
        $this->assertEquals($response['name'], $user['name']);
    }

    /**
     * 空文字列を送るとバリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_edit_profile_failure_by_empty()
    {
        $profileInfo = [
            'name' => '',
            'description' => 'よろしくお願いいたします',
            'location' => '東京都'
        ];

        $this->common_validation_logic($profileInfo);
    }

    /**
     * 名前が25文字を超えていた場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_edit_profile_failure_by_name_too_long()
    {
        $profileInfo = [
            'name' => 'ああああああああああああああああああああああああああ',
            'description' => 'よろしくお願いいたします',
            'location' => '東京都'
        ];

        $this->common_validation_logic($profileInfo);
    }

    /**
     * 説明文が500文字を超えていた場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_edit_profile_failure_by_description_too_long()
    {
        $profileInfo = [
            'name' => 'テスト太郎',
            'description' => 'あああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ',
            'location' => '東京都'
        ];

        $this->common_validation_logic($profileInfo);
    }

    /**
     * 位置情報が500文字を超えていた場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_edit_profile_failure_by_location_too_long()
    {
        $profileInfo = [
            'name' => 'テスト太郎',
            'description' => 'よろしくお願いいたします',
            'location' => 'あああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ'
        ];

        $this->common_validation_logic($profileInfo);
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param array $profileInfo
     * @return void
     */
    private function common_validation_logic(array $profileInfo)
    {
        $response = $this->actingAs($this->user)->post('/api/profile/store', $profileInfo);

        $user = User::first();

        $response->assertRedirect('/');
        $this->assertNull($user['description']);
    }
}
