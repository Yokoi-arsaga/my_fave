<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class FetchChildFoldersTest extends TestCase
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
     * 親フォルダに紐づく子フォルダー一覧の取得に成功したテスト
     *
     * @return void
     */
    public function test_fetch_child_folders_success()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル1',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => true
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $childFoldersInfo = [
            [
                'folder_name' => 'サンプル1',
                'description' => '動画フォルダーの説明文',
                'disclosure_range_id' => 1,
                'parent_folder_id' => $parentFolder->id,
                'is_nest' => true
            ],
            [
                'folder_name' => 'サンプル2',
                'description' => '動画フォルダーの説明文',
                'disclosure_range_id' => 1,
                'parent_folder_id' => $parentFolder->id,
                'is_nest' => true
            ],
            [
                'folder_name' => 'サンプル3',
                'description' => '動画フォルダーの説明文',
                'disclosure_range_id' => 1,
                'parent_folder_id' => $parentFolder->id,
                'is_nest' => true
            ],
        ];

        foreach ($childFoldersInfo as $childFolderInfo){
            $this->actingAs($this->users[1])->post("/api/favorite/folder/child/store", $childFolderInfo);
        }

        $response = $this->actingAs($this->users[1])->get('/api/favorite/folder/child/fetch');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * 認証されておらずリダイレクトされることを確認するテスト
     *
     * @return void
     */
    public function test_fetch_child_folders_failure_by_not_auth()
    {
        $response = $this->get('/api/favorite/folder/parent/fetch');

        $response->assertRedirect('/login');
    }
}
