<?php

namespace Tests\Feature\FavoriteVideo\GrandchildFolder;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchGrandchildFoldersTest extends TestCase
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
     * 子フォルダに紐づく孫フォルダー一覧の取得に成功したテスト
     *
     * @return void
     */
    public function test_fetch_grandchild_folders_success()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル1',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => true
        ];
        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $childFolderInfo = [
            'folder_name' => 'サンプル1',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'parent_folder_id' => $parentFolder['id'],
            'is_nest' => true
        ];
        $childFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/child/store', $childFolderInfo);

        $grandchildFoldersInfo = [
            [
                'folder_name' => 'サンプル1',
                'description' => '動画フォルダーの説明文',
                'disclosure_range_id' => 1,
                'child_folder_id' => $childFolder['id'],
                'is_nest' => true
            ],
            [
                'folder_name' => 'サンプル2',
                'description' => '動画フォルダーの説明文',
                'disclosure_range_id' => 1,
                'child_folder_id' => $childFolder['id'],
                'is_nest' => true
            ],
            [
                'folder_name' => 'サンプル3',
                'description' => '動画フォルダーの説明文',
                'disclosure_range_id' => 1,
                'child_folder_id' => $childFolder['id'],
                'is_nest' => true
            ],
        ];

        foreach ($grandchildFoldersInfo as $grandchildFolderInfo){
            $this->actingAs($this->users[1])->post("/api/favorite/folder/grandchild/store", $grandchildFolderInfo);
        }

        $response = $this->actingAs($this->users[1])->get("/api/favorite/folder/grandchild/fetch/{$childFolder['id']}");

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * 認証されておらずリダイレクトされることを確認するテスト
     *
     * @return void
     */
    public function test_fetch_grandchild_folders_failure_by_not_auth()
    {
        $response = $this->get("/api/favorite/folder/grandchild/fetch/1");

        $response->assertRedirect('/login');
    }
}
