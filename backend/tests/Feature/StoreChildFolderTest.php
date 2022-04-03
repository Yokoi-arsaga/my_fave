<?php

namespace Tests\Feature;

use App\Models\ParentFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class StoreChildFolderTest extends TestCase
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
     * 子フォルダーの投稿に成功したテスト
     *
     * @return void
     */
    public function test_store_child_folder_success()
    {
        $parentFolderId = $this->common_preparation();

        $childFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'parent_folder_id' => $parentFolderId,
            'is_nest' => false
        ];

        $response = $this->actingAs($this->users[1])->post('/api/favorite/folder/child/store', $childFolderInfo);

        $response->assertStatus(201);
        $this->assertEquals($response['folder_name'], $childFolderInfo['folder_name']);
    }

    /**
     * フォルダ名が空欄だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_child_folder_failure_by_name_empty()
    {
        $parentFolderId = $this->common_preparation();

        $childFolderInfo = [
            'folder_name' => '',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'parent_folder_id' => $parentFolderId,
            'is_nest' => false
        ];

        $this->common_validation_logic($childFolderInfo);
    }

    /**
     * 公開範囲が無効だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_child_folder_failure_by_disclosure_out_of_range()
    {
        $parentFolderId = $this->common_preparation();

        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 4,
            'parent_folder_id' => $parentFolderId,
            'is_nest' => false
        ];

        $this->common_validation_logic($parentFolderInfo);
    }

    /**
     * ネストフラグの値が無効なものだった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_child_folder_failure_by_nest_flag_invalid()
    {
        $parentFolderId = $this->common_preparation();

        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'parent_folder_id' => $parentFolderId,
            'is_nest' => null
        ];

        $this->common_validation_logic($parentFolderInfo);
    }

    /**
     * 親フォルダーIDがnullだった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_child_folder_failure_by_parent_folder_id_empty()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'parent_folder_id' => null,
            'is_nest' => false
        ];

        $this->common_validation_logic($parentFolderInfo);
    }

    /**
     * 親フォルダーそのものが存在しなかった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_store_child_folder_failure_by_parent_folder_empty()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'parent_folder_id' => 1,
            'is_nest' => false
        ];

        $this->common_validation_logic($parentFolderInfo);
    }

    /**
     * 認証されていない場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_store_child_folder_failure_by_not_auth()
    {
        $parentFolderId = $this->common_preparation();

        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'parent_folder_id' => $parentFolderId,
            'is_nest' => true
        ];

        $this->common_validation_logic($parentFolderInfo, 'login');
    }

    /**
     * テスト実行前の準備
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

        return $parentFolder['id'];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param array $childFolderInfo
     * @param string|null $path
     * @return void
     */
    private function common_validation_logic(array $childFolderInfo, ?string $path=null)
    {
        if (is_null($path)){
            $response = $this->actingAs($this->users[1])->post('/api/favorite/folder/child/store', $childFolderInfo);
        }else{
            $response = $this->post('/api/favorite/folder/child/store', $childFolderInfo);
        }

        $response->assertRedirect("/$path");
        $this->assertEmpty(ChildFolder::all());
    }
}
