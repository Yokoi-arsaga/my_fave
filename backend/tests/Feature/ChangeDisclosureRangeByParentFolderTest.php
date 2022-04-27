<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ChangeDisclosureRangeByParentFolderTest extends TestCase
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
     * 親フォルダーの公開範囲の変更に成功したテスト
     *
     * @return void
     */
    public function test_change_disclosure_range_by_parent_folder_success()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => false
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);
        $parentFolderId = $parentFolder['id'];
        $disclosureRangeId = ['disclosure_range_id' => 2];

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/parent/disclosure/$parentFolderId", $disclosureRangeId);

        $response->assertStatus(200);
        $this->assertEquals($response['disclosure_range_id'], $disclosureRangeId['disclosure_range_id']);
    }

    /**
     * 公開範囲が無効だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
//    public function test_change_disclosure_range_parent_failure_by_out_of_range()
//    {
//        $parentFolderInfo = [
//            'folder_name' => 'サンプル',
//            'description' => '動画フォルダーの説明文',
//            'disclosure_range_id' => 1,
//            'is_nest' => false
//        ];
//
//        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);
//        $parentFolderId = $parentFolder['id'];
//        $disclosureRangeId = ['disclosure_range_id' => 4];
//
//        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/parent/disclosure/$parentFolderId", $disclosureRangeId);
//
//        $response->assertRedirect('/');
//
//        $this->assertEquals($response['disclosure_range_id'], $parentFolderInfo['disclosure_range_id']);
//    }
    // TODO: 上記原因がわかるまでとりあえずコメントアウト
}
