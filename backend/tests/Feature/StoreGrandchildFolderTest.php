<?php

namespace Tests\Feature;

use App\Models\GrandChildFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class StoreGrandchildFolderTest extends TestCase
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
     * 孫フォルダーの投稿に成功したテスト
     *
     * @return void
     */
    public function test_store_grandchild_folder_success()
    {
        $childFolderId = $this->common_preparation();

        $grandchildFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'child_folder_id' => $childFolderId,
            'is_nest' => false
        ];

        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/grandchild/store", $grandchildFolderInfo);

        $response->assertStatus(201);
        $this->assertEquals($response['folder_name'], $grandchildFolderInfo['folder_name']);
        $this->assertEquals($response['child_folder_id'], $childFolderId);
    }

    /**
     * フォルダ名が空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_grandchild_folder_failure_by_name_empty()
    {
        $childFolderId = $this->common_preparation();

        $grandchildFolderInfo = [
            'folder_name' => '',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'child_folder_id' => $childFolderId,
            'is_nest' => false
        ];

        $this->common_validation_logic($grandchildFolderInfo);
    }

    /**
     * 公開範囲が無効だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_grandchild_folder_failure_by_disclosure_out_of_range()
    {
        $childFolderId = $this->common_preparation();

        $grandchildFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 4,
            'child_folder_id' => $childFolderId,
            'is_nest' => false
        ];

        $this->common_validation_logic($grandchildFolderInfo);
    }

    /**
     * ネストフラグの値が無効なものだった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_grandchild_folder_failure_by_nest_flag_invalid()
    {
        $childFolderId = $this->common_preparation();

        $grandchildFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'child_folder_id' => $childFolderId,
            'is_nest' => null
        ];

        $this->common_validation_logic($grandchildFolderInfo);
    }

    /**
     * 子フォルダーIDがnullだった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_grandchild_folder_failure_by_child_folder_id_empty()
    {
        $grandchildFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => false
        ];

        $this->common_validation_logic($grandchildFolderInfo);
    }

    /**
     * 子フォルダーそのものが存在しなかった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_grandchild_folder_failure_by_child_folder_empty()
    {
        $grandchildFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'child_folder_id' => 1,
            'is_nest' => false
        ];

        $this->common_validation_logic($grandchildFolderInfo);
    }

    /**
     * 指定した子フォルダーIDが自分のものでない場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_grandchild_folder_failure_by_parent_folder_not_mine()
    {
        $this->common_preparation();

        $grandchildFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'child_folder_id' => 2,
            'is_nest' => false
        ];

        $this->common_validation_logic($grandchildFolderInfo);
    }

    // TODO: 認証されていない場合のテストの実装

    /**
     * テスト実行前の準備
     * 親フォルダーを登録してそのIDを返却
     *
     * @return int
     */
    private function common_preparation(): int
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => false
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $childFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'parent_folder_id' => $parentFolder['id'],
            'is_nest' => false
        ];

        $childFolder = $this->actingAs($this->users[1])->post("/api/favorite/folder/grandchild/store", $childFolderInfo);

        return $childFolder['id'];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param array $grandchildFolderInfo
     * @param string|null $path
     * @return void
     */
    private function common_validation_logic(array $grandchildFolderInfo, ?string $path=null)
    {
        if (is_null($path)){
            $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/grandchild/store", $grandchildFolderInfo);
        }else{
            $response = $this->post("/api/favorite/folder/grandchild/store", $grandchildFolderInfo);
        }

        $response->assertRedirect("/$path");
        $this->assertEmpty(GrandchildFolder::all());
    }
}
