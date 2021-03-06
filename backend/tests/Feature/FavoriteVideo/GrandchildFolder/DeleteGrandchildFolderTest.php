<?php

namespace Tests\Feature\FavoriteVideo\GrandchildFolder;

use App\Models\GrandchildFolder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteGrandchildFolderTest extends TestCase
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
     * 孫フォルダーの削除に成功したテスト
     *
     * @return void
     */
    public function test_delete_grandchild_folder_success()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => true
        ];
        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $childFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'parent_folder_id' => $parentFolder['id'],
            'is_nest' => false
        ];
        $childFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/child/store', $childFolderInfo);

        $grandchildFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'child_folder_id' => $childFolder['id'],
            'is_nest' => false
        ];
        $grandchildFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/grandchild/store', $grandchildFolderInfo);

        $grandchildFolderId = $grandchildFolder['id'];
        $response = $this->actingAs($this->users[1])->delete("/api/favorite/folder/grandchild/$grandchildFolderId");

        $response->assertStatus(200);

        $grandchildFolders = GrandchildFolder::all();

        $this->assertEmpty($grandchildFolders);
    }

    // TODO:リダイレクトテストはfactory作ってから
}
