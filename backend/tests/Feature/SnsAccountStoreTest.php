<?php

namespace Tests\Feature;

use App\Models\SnsAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SnsAccountStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * SNSアカウント情報の登録が上手くいくことを確認
     *
     * @return void
     */
    public function test_store_account_success()
    {
        $accountInfo = [
            'media_id' => 1,
            'account_url' => 'https://www.youtube.com/'
        ];

        $response = $this->actingAs($this->user)->post('/api/account/store', $accountInfo);

        $account = SnsAccount::first();

        $response->assertStatus(201);
        $this->assertEquals($response['account_url'], $account['account_url']);
    }

    /**
     * メディアIDが空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_account_failure_by_media_empty()
    {
        $accountInfo = [
            'media_id' => '',
            'account_url' => 'https://www.youtube.com/'
        ];

        $response = $this->actingAs($this->user)->post('/api/account/store', $accountInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(SnsAccount::all());
    }

    /**
     * アカウントURLが空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_account_failure_by_account_url_empty()
    {
        $accountInfo = [
            'media_id' => 1,
            'account_url' => ''
        ];

        $response = $this->actingAs($this->user)->post('/api/account/store', $accountInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(SnsAccount::all());
    }

    /**
     * メディアIDが1~3以外の数字だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_account_failure_by_incorrect_number()
    {
        $accountInfo = [
            'media_id' => 4,
            'account_url' => 'https://www.youtube.com/'
        ];

        $response = $this->actingAs($this->user)->post('/api/account/store', $accountInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(SnsAccount::all());
    }
}
