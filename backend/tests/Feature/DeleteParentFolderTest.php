<?php

namespace Tests\Feature;

use App\Models\ParentFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DeleteParentFolderTest extends TestCase
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
     * 親フォルダーの削除に成功したテスト
     *
     * @return void
     */
    public function test_delete_parent_folder_success()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => false
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $parentFolderId = $parentFolder['id'];
        $response = $this->actingAs($this->users[1])->delete("/api/favorite/folder/parent/$parentFolderId");

        $response->assertStatus(200);

        $parentFolders = ParentFolder::all();

        $this->assertEmpty($parentFolders);
    }

    // TODO:リダイレクトテストはfactory作ってから
}
