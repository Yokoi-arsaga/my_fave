<?php

namespace Tests\Feature;

use App\Models\ChildFolder;
use App\Models\FavoriteVideo;
use App\Models\ParentFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\User;

class RegisterByParentFolderTest extends TestCase
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
     * お気に入り動画を親フォルダーに登録することに成功したテスト
     *
     * @return void
     */
    public function test_register_by_parent_folder_success()
    {
        [$favoriteVideoId, $parentFolderId] = $this->common_preparation();

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/parent/register/$favoriteVideoId", $parentFolderId);

        $response->assertStatus(200);
        $this->assertEquals($response['parent_folder_id'], $parentFolderId);
        $this->assertEquals($response['favorite_video_id'], $favoriteVideoId);
    }

    /**
     * 存在しないお気に入り動画を指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_wrong_favorite_video()
    {
        [$favoriteVideoId, $parentFolderId] = $this->common_preparation();
        $wrongFavoriteVideoId = 2;
        $this->common_validation_logic($wrongFavoriteVideoId, $parentFolderId);
    }

    /**
     * 存在しない親フォルダーを指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_wrong_parent_folder()
    {
        [$favoriteVideoId, $parentFolderId] = $this->common_preparation();
        $wrongParentFolderId = ['parent_folder_id' => 2];
        $this->common_validation_logic($favoriteVideoId, $wrongParentFolderId);
    }

    /**
     * 指定したお気に入り動画が自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_have_not_favorite_video()
    {
        [$favoriteVideoId, $parentFolderId] = $this->common_preparation(true, false);
        $wrongFavoriteVideoId = $favoriteVideoId;
        $this->common_validation_logic($wrongFavoriteVideoId, $parentFolderId);
    }

    /**
     * 指定した親フォルダーが自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_have_not_parent_folder()
    {
        [$favoriteVideoId, $parentFolderId] = $this->common_preparation(false, true);
        $this->common_validation_logic($favoriteVideoId, $parentFolderId);
    }

    /**
     * テスト実行前の準備
     *
     * @param bool|null $isFavoriteVideoWrongUser
     * @param bool|null $isParentFolderWrongUser
     * @return array
     */
    private function common_preparation(?bool $isFavoriteVideoWrongUser = false, ?bool $isParentFolderWrongUser = false): array
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

        if ($isParentFolderWrongUser){
            $parentFolder = $this->actingAs($this->users[2])->post('/api/favorite/folder/parent/store', $parentFolderInfo);
        }else{
            $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);
        }
        $parentFolderId = ['parent_folder_id' => $parentFolder['id']];
        return [$favoriteVideoId, $parentFolderId];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param int $favoriteVideoId
     * @param array $parentFolderId
     * @return void
     */
    private function common_validation_logic(int $favoriteVideoId, array $parentFolderId)
    {
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/parent/register/$favoriteVideoId", $parentFolderId);

        $response->assertRedirect('/');

        $parentFolder = ParentFolder::find($parentFolderId['parent_folder_id']);
        $favoriteVideo = FavoriteVideo::find($favoriteVideoId);

        $this->assertEmpty($parentFolder->favoriteVideos);
        $this->assertEmpty($favoriteVideo->parentFolders);
    }
}
