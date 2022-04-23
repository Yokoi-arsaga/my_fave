<?php

namespace Tests\Feature;

use App\Models\ChildFolder;
use App\Models\GrandchildFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\User;

class UpdateGrandchildFolderTest extends TestCase
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
     * 孫フォルダーの情報更新に成功したテスト
     *
     * @return void
     */
    public function test_update_grandchild_folder_success()
    {
        [$grandchildFolderInfo, $grandchildFolder, $childFolderId] = $this->common_preparation();

        $updateGrandchildFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'child_folder_id' => $childFolderId,
            'is_nest' => false
        ];

        $grandchildFolderId = $grandchildFolder['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/grandchild/$grandchildFolderId", $updateGrandchildFolderInfo);

        $response->assertStatus(200);

        $updateGrandchildFolder = GrandchildFolder::find($grandchildFolderId);

        $this->assertEquals($updateGrandchildFolder->folder_name, $response['folder_name']);
    }

    /**
     * フォルダ名が空欄だった場合に孫フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_grandchild_folder_failure_by_folder_name_empty()
    {
        [$grandchildFolderInfo, $grandchildFolder, $childFolderId] = $this->common_preparation();

        $updateGrandchildFolderInfo = [
            'folder_name' => '',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'child_folder_id' => $childFolderId,
            'is_nest' => false
        ];

        $this->common_validation_logic($grandchildFolder, $updateGrandchildFolderInfo, $grandchildFolderInfo);
    }

    /**
     * 公開範囲が無効な値だった場合に孫フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_grandchild_folder_failure_by_disclosure_out_of_range()
    {
        [$grandchildFolderInfo, $grandchildFolder, $childFolderId] = $this->common_preparation();

        $updateGrandchildFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 4,
            'child_folder_id' => $childFolderId,
            'is_nest' => false
        ];

        $this->common_validation_logic($grandchildFolder, $updateGrandchildFolderInfo, $grandchildFolderInfo);
    }

    /**
     * ネストフラグが無効な値だった場合に孫フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_grandchild_folder_failure_by_nest_flag_invalid()
    {
        [$grandchildFolderInfo, $grandchildFolder, $childFolderId] = $this->common_preparation();

        $updateGrandchildFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'child_folder_id' => $childFolderId,
            'is_nest' => null
        ];

        $this->common_validation_logic($grandchildFolder, $updateGrandchildFolderInfo, $grandchildFolderInfo);
    }

    /**
     * 親フォルダーIDが無効な値だった場合に孫フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_grandchild_folder_failure_by_parent_folder_id_invalid()
    {
        [$grandchildFolderInfo, $grandchildFolder, $childFolderId] = $this->common_preparation();

        $updateGrandchildFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'child_folder_id' => 2,
            'is_nest' => null
        ];

        $this->common_validation_logic($grandchildFolder, $updateGrandchildFolderInfo, $grandchildFolderInfo);
    }

    /**
     * テスト実行前の準備
     *
     * @return array
     */
    private function common_preparation(): array
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

        return [$grandchildFolderInfo, $grandchildFolder, $childFolder['id']];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param TestResponse $grandchildFolder
     * @param array $updateGrandchildFolderInfo
     * @param array $grandchildFolderInfo
     * @return void
     */
    private function common_validation_logic(TestResponse $grandchildFolder, array $updateGrandchildFolderInfo, array $grandchildFolderInfo)
    {
        $grandchildFolderId = $grandchildFolder['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/grandchild/$grandchildFolderId", $updateGrandchildFolderInfo);

        $response->assertRedirect('/');

        $updateGrandchildFolder = GrandchildFolder::find($grandchildFolderId);

        $this->assertEquals($updateGrandchildFolder->folder_name, $grandchildFolderInfo['folder_name']);
    }
}
