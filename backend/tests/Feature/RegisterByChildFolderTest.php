<?php

namespace Tests\Feature;

use App\Models\ChildFolder;
use App\Models\FavoriteVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterByChildFolderTest extends TestCase
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
     * お気に入り動画を子フォルダーに登録することに成功したテスト
     *
     * @return void
     */
    public function test_register_by_child_folder_success()
    {
        [$favoriteVideoId, $childFolderId] = $this->common_preparation();

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/child/register/$favoriteVideoId", $childFolderId);

        $response->assertStatus(200);
        $this->assertEquals($response['child_folder_id'], $childFolderId);
        $this->assertEquals($response['favorite_video_id'], $favoriteVideoId);
    }

    /**
     * 存在しないお気に入り動画を指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_child_folder_failure_by_wrong_favorite_video()
    {
        [$favoriteVideoId, $childFolderId] = $this->common_preparation();
        $wrongFavoriteVideoId = 2;
        $this->common_validation_logic($wrongFavoriteVideoId, $childFolderId);
    }

    /**
     * 存在しない子フォルダーを指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_child_folder_failure_by_wrong_parent_folder()
    {
        [$favoriteVideoId, $childFolderId] = $this->common_preparation();
        $wrongChildFolderId = ['child_folder_id' => 2];
        $this->common_validation_logic($favoriteVideoId, $wrongChildFolderId);
    }

    /**
     * 指定したお気に入り動画が自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_child_folder_failure_by_have_not_favorite_video()
    {
        [$favoriteVideoId, $childFolderId] = $this->common_preparation(true, false);
        $wrongFavoriteVideoId = $favoriteVideoId;
        $this->common_validation_logic($wrongFavoriteVideoId, $childFolderId);
    }

    /**
     * 指定した子フォルダーが自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_child_folder_failure_by_have_not_child_folder()
    {
        [$favoriteVideoId, $childFolderId] = $this->common_preparation(false, true);
        $this->common_validation_logic($favoriteVideoId, $childFolderId);
    }

    /**
     * テスト実行前の準備
     *
     * @param bool|null $isFavoriteVideoWrongUser
     * @param bool|null $isChildFolderWrongUser
     * @return array
     */
    private function common_preparation(?bool $isFavoriteVideoWrongUser = false, ?bool $isChildFolderWrongUser = false): array
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

        if ($isChildFolderWrongUser){
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

        if ($isChildFolderWrongUser){
            $childFolder = $this->actingAs($this->users[2])->post("/api/favorite/folder/child/store", $childFolderInfo);
        }else{
            $childFolder = $this->actingAs($this->users[1])->post("/api/favorite/folder/child/store", $childFolderInfo);
        }
        $childFolderId = ['child_folder_id' => $childFolder['id']];
        return [$favoriteVideoId, $childFolderId];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param int $favoriteVideoId
     * @param array $childFolderId
     * @return void
     */
    private function common_validation_logic(int $favoriteVideoId, array $childFolderId)
    {
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/child/register/$favoriteVideoId", $childFolderId);

        $response->assertRedirect('/');

        $childFolder = ChildFolder::find($childFolderId['child_folder_id']);
        $favoriteVideo = FavoriteVideo::find($favoriteVideoId);

        $this->assertEmpty($childFolder->favoriteVideos);
        $this->assertEmpty($favoriteVideo->childFolders);
    }
}
