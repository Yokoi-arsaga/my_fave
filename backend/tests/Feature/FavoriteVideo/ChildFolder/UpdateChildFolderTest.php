<?php

namespace Tests\Feature\FavoriteVideo\ChildFolder;

use App\Models\ChildFolder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateChildFolderTest extends TestCase
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
     * 子フォルダーの情報更新に成功したテスト
     *
     * @return void
     */
    public function test_update_child_folder_success()
    {
        [$childFolderInfo, $childFolder, $parentFolderId] = $this->common_preparation();

        $updateChildFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'parent_folder_id' => $parentFolderId,
            'is_nest' => false
        ];

        $childFolderId = $childFolder['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/child/$childFolderId", $updateChildFolderInfo);

        $response->assertStatus(200);

        $updateChildFolder = ChildFolder::find($childFolderId);

        $this->assertEquals($updateChildFolder->folder_name, $response['folder_name']);
    }

    /**
     * フォルダ名が空欄だった場合に子フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_child_folder_failure_by_folder_name_empty()
    {
        [$childFolderInfo, $childFolder, $parentFolderId] = $this->common_preparation();

        $updateChildFolderInfo = [
            'folder_name' => '',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'parent_folder_id' => $parentFolderId,
            'is_nest' => false
        ];

        $this->common_validation_logic($childFolder, $updateChildFolderInfo, $childFolderInfo);
    }

    /**
     * 公開範囲が無効な値だった場合に子フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_child_folder_failure_by_disclosure_out_of_range()
    {
        [$childFolderInfo, $childFolder, $parentFolderId] = $this->common_preparation();

        $updateChildFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 4,
            'parent_folder_id' => $parentFolderId,
            'is_nest' => false
        ];

        $this->common_validation_logic($childFolder, $updateChildFolderInfo, $childFolderInfo);
    }

    /**
     * ネストフラグが無効な値だった場合に子フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_child_folder_failure_by_nest_flag_invalid()
    {
        [$childFolderInfo, $childFolder, $parentFolderId] = $this->common_preparation();

        $updateChildFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'parent_folder_id' => $parentFolderId,
            'is_nest' => null
        ];

        $this->common_validation_logic($childFolder, $updateChildFolderInfo, $childFolderInfo);
    }

    /**
     * 親フォルダーIDが無効な値だった場合に子フォルダの情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_child_folder_failure_by_parent_folder_id_invalid()
    {
        [$childFolderInfo, $childFolder, $parentFolderId] = $this->common_preparation();

        $updateChildFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'parent_folder_id' => 2,
            'is_nest' => null
        ];

        $this->common_validation_logic($childFolder, $updateChildFolderInfo, $childFolderInfo);
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
        return [$childFolderInfo, $childFolder, $parentFolder['id']];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param TestResponse $childFolder
     * @param array $updateChildFolderInfo
     * @param array $childFolderInfo
     * @return void
     */
    private function common_validation_logic(TestResponse $childFolder, array $updateChildFolderInfo, array $childFolderInfo)
    {
        $childFolderId = $childFolder['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/child/$childFolderId", $updateChildFolderInfo);

        $response->assertRedirect('/');

        $updateChildFolder = ChildFolder::find($childFolderId);

        $this->assertEquals($updateChildFolder->folder_name, $childFolderInfo['folder_name']);
    }
}
