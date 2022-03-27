<?php

namespace Tests\Feature;

use App\Models\FavoriteVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class StoreParentFolderTest extends TestCase
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
     * 親フォルダーの投稿に成功したテスト
     *
     * @return void
     */
    public function test_store_parent_folder_success()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => false
        ];

        $response = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $response->assertStatus(201);
        $this->assertEquals($response['folder_name'], $parentFolderInfo['folder_name']);
    }

    /**
     * フォルダ名が空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_parent_folder_failure_by_name_empty()
    {
        $parentFolderInfo = [
            'folder_name' => '',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => false
        ];

        $response = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(ParentFolder::all());
    }

    /**
     * 公開範囲が無効だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_parent_folder_failure_by_disclosure_out_of_range()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 4,
            'is_nest' => false
        ];

        $response = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(ParentFolder::all());
    }

    /**
     * 公開範囲が無効だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_parent_folder_failure_by_nest_flag_invalid()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => null
        ];

        $response = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $response->assertRedirect('/');
        $this->assertEmpty(ParentFolder::all());
    }

    /**
     * 認証されていない場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_store_parent_folder_failure_by_not_auth()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => true
        ];

        $response = $this->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $response->assertRedirect('/login');
        $this->assertEmpty(ParentFolder::all());
    }
}
