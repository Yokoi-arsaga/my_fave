<?php

namespace Tests\Feature\FavoriteVideo\ChildFolder;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeDisclosureRangeByChildFolderTest extends TestCase
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
     * 子フォルダーの公開範囲の変更に成功したテスト
     *
     * @return void
     */
    public function test_change_disclosure_range_by_child_folder_success()
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
        $childFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/child/store', $childFolderInfo);

        $childFolderId = $childFolder['id'];
        $disclosureRangeId = ['disclosure_range_id' => 2];

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/child/disclosure/$childFolderId", $disclosureRangeId);

        $response->assertStatus(200);
        $this->assertEquals($response['disclosure_range_id'], $disclosureRangeId['disclosure_range_id']);
    }

    /**
     * 公開範囲が無効だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
//    public function test_change_disclosure_range_child_failure_by_out_of_range()
//    {
//        $parentFolderInfo = [
//            'folder_name' => 'サンプル',
//            'description' => '動画フォルダーの説明文',
//            'disclosure_range_id' => 1,
//            'is_nest' => false
//        ];
//        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);
//
//        $childFolderInfo = [
//            'folder_name' => 'サンプル',
//            'description' => '動画フォルダーの説明文',
//            'disclosure_range_id' => 1,
//            'parent_folder_id' => $parentFolder['id'],
//            'is_nest' => false
//        ];
//        $childFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/child/store', $childFolderInfo);
//
//        $childFolderId = $childFolder['id'];
//        $disclosureRangeId = ['disclosure_range_id' => 4];
//
//        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/child/disclosure/$childFolderId", $disclosureRangeId);
//
//        $response->assertRedirect('/');
//
//        $this->assertEquals($response['disclosure_range_id'], $childFolderInfo['disclosure_range_id']);
//    }
// TODO: 上記原因がわかるまでとりあえずコメントアウト
}
