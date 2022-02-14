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

        $response = $this->actingAs($this->user)->post('/account/store', $accountInfo);

        $account = SnsAccount::first();

        $response->assertStatus(201);
        $this->assertEquals($response['account_url'], $account['account_url']);
    }
}
