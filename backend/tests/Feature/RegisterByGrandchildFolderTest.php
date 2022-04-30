<?php

namespace Tests\Feature;

use App\Models\FavoriteVideo;
use App\Models\GrandchildFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterByGrandchildFolderTest extends TestCase
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
     * お気に入り動画を孫フォルダーに登録することに成功したテスト
     *
     * @return void
     */
    public function test_register_by_grandchild_folder_success()
    {
        [$favoriteVideoId, $grandchildFolderId] = $this->common_preparation();

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/grandchild/register/$favoriteVideoId", $grandchildFolderId);

        $response->assertStatus(200);
        $this->assertEquals($response['grandchild_folder_id'], $grandchildFolderId);
        $this->assertEquals($response['favorite_video_id'], $favoriteVideoId);
    }

    /**
     * 存在しないお気に入り動画を指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_grandchild_folder_failure_by_wrong_favorite_video()
    {
        [$favoriteVideoId, $grandchildFolderId] = $this->common_preparation();
        $wrongFavoriteVideoId = 2;
        $this->common_validation_logic($wrongFavoriteVideoId, $grandchildFolderId);
    }

    /**
     * 存在しない孫フォルダーを指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_grandchild_folder_failure_by_wrong_parent_folder()
    {
        [$favoriteVideoId, $grandchildFolderId] = $this->common_preparation();
        $wrongGrandchildFolderId = ['folder_id' => 2];
        $this->common_validation_logic($favoriteVideoId, $wrongGrandchildFolderId);
    }

    /**
     * 指定したお気に入り動画が自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_grandchild_folder_failure_by_have_not_favorite_video()
    {
        [$favoriteVideoId, $grandchildFolderId] = $this->common_preparation(true, false);
        $wrongFavoriteVideoId = $favoriteVideoId;
        $this->common_validation_logic($wrongFavoriteVideoId, $grandchildFolderId);
    }

    /**
     * 指定した孫フォルダーが自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_grandchild_folder_failure_by_have_not_grandchild_folder()
    {
        [$favoriteVideoId, $grandchildFolderId] = $this->common_preparation(false, true);
        $this->common_validation_logic($favoriteVideoId, $grandchildFolderId);
    }

    /**
     * テスト実行前の準備
     *
     * @param bool|null $isFavoriteVideoWrongUser
     * @param bool|null $isGrandchildFolderWrongUser
     * @return array
     */
    private function common_preparation(?bool $isFavoriteVideoWrongUser = false, ?bool $isGrandchildFolderWrongUser = false): array
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        if ($isFavoriteVideoWrongUser){
            $favoriteVideo = $this->actingAs($this->users[2])->post('/api/favorite/videos/store', $favoriteVideoInfo);
        }else{
            $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);
        }
        $favoriteVideoId = $favoriteVideo['id'];

        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => false
        ];

        if ($isGrandchildFolderWrongUser){
            $parentFolder = $this->actingAs($this->users[2])->post('/api/favorite/folder/parent/store', $parentFolderInfo);
        }else{
            $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);
        }

        $childFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'parent_folder_id' => $parentFolder['id'],
            'is_nest' => false
        ];

        if ($isGrandchildFolderWrongUser){
            $childFolder = $this->actingAs($this->users[2])->post("/api/favorite/folder/child/store", $childFolderInfo);
        }else{
            $childFolder = $this->actingAs($this->users[1])->post("/api/favorite/folder/child/store", $childFolderInfo);
        }

        $grandchildFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'child_folder_id' => $childFolder['id'],
            'is_nest' => false
        ];

        if ($isGrandchildFolderWrongUser){
            $grandchildFolder = $this->actingAs($this->users[2])->post("/api/favorite/folder/grandchild/store", $grandchildFolderInfo);
        }else{
            $grandchildFolder = $this->actingAs($this->users[1])->post("/api/favorite/folder/grandchild/store", $grandchildFolderInfo);
        }

        $grandchildFolderId = ['folder_id' => $grandchildFolder['id']];
        return [$favoriteVideoId, $grandchildFolderId];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param int $favoriteVideoId
     * @param array $grandchildFolderId
     * @return void
     */
    private function common_validation_logic(int $favoriteVideoId, array $grandchildFolderId)
    {
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/grandchild/register/$favoriteVideoId", $grandchildFolderId);

        $response->assertRedirect('/');

        $grandchildFolder = GrandchildFolder::find($grandchildFolderId['folder_id']);
        $favoriteVideo = FavoriteVideo::find($favoriteVideoId);

        $this->assertEmpty($grandchildFolder->favoriteVideos);
        $this->assertEmpty($favoriteVideo->grandchildFolders);
    }
}
