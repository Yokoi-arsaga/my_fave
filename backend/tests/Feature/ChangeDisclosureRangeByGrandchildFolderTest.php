<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ChangeDisclosureRangeByGrandchildFolderTest extends TestCase
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
     * 孫フォルダーの公開範囲の変更に成功したテスト
     *
     * @return void
     */
    public function test_change_disclosure_range_by_grandchild_folder_success()
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

        $grandchildFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'child_folder_id' => $childFolder['id'],
            'is_nest' => false
        ];
        $grandchildFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/grandchild/store', $grandchildFolderInfo);

        $grandchildFolderId = $grandchildFolder['id'];
        $disclosureRangeId = ['disclosure_range_id' => 2];

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/grandchild/disclosure/$grandchildFolderId", $disclosureRangeId);

        $response->assertStatus(200);
        $this->assertEquals($response['disclosure_range_id'], $disclosureRangeId);
    }

    /**
     * 公開範囲が無効だった場合バリデーションで弾かれることを確認
     *
     * @return void
     */
    public function test_change_disclosure_range_grandchild_failure_by_out_of_range()
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

        $grandchildFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'child_folder_id' => $childFolder['id'],
            'is_nest' => false
        ];
        $grandchildFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/grandchild/store', $grandchildFolderInfo);

        $grandchildFolderId = $grandchildFolder['id'];
        $disclosureRangeId = ['disclosure_range_id' => 2];

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/grandchild/disclosure/$grandchildFolderId", $disclosureRangeId);

        $response->assertRedirect('/');

        $this->assertEquals($response['disclosure_range_id'], $grandchildFolderInfo['disclosure_range_id']);
    }
}
