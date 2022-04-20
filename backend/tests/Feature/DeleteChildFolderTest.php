<?php

namespace Tests\Feature;

use App\Models\ChildFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DeleteChildFolderTest extends TestCase
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
     * 子フォルダーの削除に成功したテスト
     *
     * @return void
     */
    public function test_delete_child_folder_success()
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

        $childFolderId = $childFolder['id'];
        $response = $this->actingAs($this->users[1])->delete("/api/favorite/folder/child/$childFolderId");

        $response->assertStatus(200);

        $childFolders = ChildFolder::all();

        $this->assertEmpty($childFolders);
    }

    // TODO:リダイレクトテストはfactory作ってから
}
