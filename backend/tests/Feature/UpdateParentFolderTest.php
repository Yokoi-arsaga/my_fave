<?php

namespace Tests\Feature;

use App\Models\ParentFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UpdateParentFolderTest extends TestCase
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
     * 親フォルダーの情報更新に成功したテスト
     *
     * @return void
     */
    public function test_update_parent_folder_success()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => true
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $updateParentFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'is_nest' => false
        ];

        $parentFolderId = $parentFolder['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/parent/$parentFolderId", $updateParentFolderInfo);

        $response->assertStatus(200);

        $updateParentFolder = ParentFolder::find($parentFolderId);

        $this->assertEquals($updateParentFolder->folder_name, $response['folder_name']);
    }

    /**
     * フォルダ名が空欄だった場合に親フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_parent_folder_failure_by_folder_name_empty()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => true
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $updateParentFolderInfo = [
            'folder_name' => '',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'is_nest' => true
        ];

        $parentFolderId = $parentFolder['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$parentFolderId", $updateParentFolderInfo);

        $response->assertRedirect('/');

        $updateParentFolder = ParentFolder::find($parentFolderId);

        $this->assertEquals($updateParentFolder->folder_name, $parentFolderInfo['folder_name']);
    }

    /**
     * 説明文が空欄だった場合に親フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_parent_folder_failure_by_description_empty()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => true
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $updateParentFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '',
            'disclosure_range_id' => 2,
            'is_nest' => true
        ];

        $parentFolderId = $parentFolder['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$parentFolderId", $updateParentFolderInfo);

        $response->assertRedirect('/');

        $updateParentFolder = ParentFolder::find($parentFolderId);

        $this->assertEquals($updateParentFolder->folder_name, $parentFolderInfo['folder_name']);
    }

    /**
     * 公開範囲が無効な値だった場合に親フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_parent_folder_failure_by_disclosure_out_of_range()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => true
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $updateParentFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 4,
            'is_nest' => true
        ];

        $parentFolderId = $parentFolder['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$parentFolderId", $updateParentFolderInfo);

        $response->assertRedirect('/');

        $updateParentFolder = ParentFolder::find($parentFolderId);

        $this->assertEquals($updateParentFolder->folder_name, $parentFolderInfo['folder_name']);
    }

    /**
     * ネストフラグが無効な値だった場合に親フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_parent_folder_failure_by_nest_flag_invalid()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => true
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $updateParentFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'is_nest' => null
        ];

        $parentFolderId = $parentFolder['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$parentFolderId", $updateParentFolderInfo);

        $response->assertRedirect('/');

        $updateParentFolder = ParentFolder::find($parentFolderId);

        $this->assertEquals($updateParentFolder->folder_name, $parentFolderInfo['folder_name']);
    }
}
