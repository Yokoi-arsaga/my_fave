<?php

namespace Tests\Feature\FavoriteVideo\ChildFolder;

use App\Models\ChildFolder;
use App\Models\FavoriteVideo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DetachRegistrationByChildFolderTest extends TestCase
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
     * お気に入り動画と子フォルダーの連携解除に成功したテスト
     *
     * @return void
     */
    public function test_detach_registration_to_child_folder_success()
    {
        [$favoriteVideoId, $registerFolderId] = $this->common_preparation();

        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/child/detach/$favoriteVideoId", $registerFolderId);

        $response->assertStatus(200);

        $favoriteVideo = FavoriteVideo::find($favoriteVideoId);
        $registrationFolders = $favoriteVideo->parentFolders();

        $this->assertEmpty($registrationFolders);
    }

    /**
     * 存在しないお気に入り動画を指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_detach_registration_to_child_folder_failure_by_wrong_favorite_video()
    {
        [$favoriteVideoId, $registerFolderId] = $this->common_preparation();
        $wrongFavoriteVideoId = 2;
        $this->common_validation_logic($wrongFavoriteVideoId, $registerFolderId);
    }

    /**
     * 存在しない子フォルダーを指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_detach_registration_to_child_folder_failure_by_wrong_child_folder()
    {
        [$favoriteVideoId, $registerFolderId] = $this->common_preparation();
        $wrongRegisterFolderId = ['folder_id' => 2];
        $this->common_validation_logic($favoriteVideoId, $wrongRegisterFolderId);
    }

    /**
     * 指定したお気に入り動画が自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_detach_registration_to_child_folder_failure_by_have_not_favorite_video()
    {
        [$favoriteVideoId, $registerFolderId] = $this->common_preparation(true, false);
        $this->common_validation_logic($favoriteVideoId, $registerFolderId);
    }

    /**
     * 指定した親フォルダーが自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_detach_registration_to_child_folder_failure_by_have_not_child_folder()
    {
        [$favoriteVideoId, $registerFolderId] = $this->common_preparation(false, true);
        $this->common_validation_logic($favoriteVideoId, $registerFolderId);
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

        $parentFolder = $this->insert_folder($isParentFolderWrongUser, 'サンプル1', 'parent');
        $registerFolder = $this->insert_folder($isParentFolderWrongUser, 'サンプル1', 'child', $parentFolder['id']);

        $registerFolderId = ['folder_id' => $registerFolder['id']];
        $this->actingAs($this->users[1])->post("/api/favorite/folder/child/register/$favoriteVideoId", $registerFolderId);

        return [$favoriteVideoId, $registerFolderId];
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
        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/child/detach/$favoriteVideoId", $parentFolderId);

        $response->assertRedirect('/');

        $childFolder = ChildFolder::where('user_id', Auth::id())->first();
        $favoriteVideo = FavoriteVideo::where('user_id', Auth::id())->first();

        if (isset($childFolder)){
            $this->assertCount(1, $childFolder->favoriteVideos);
        }
        if (isset($favoriteVideo)){
            $this->assertCount(1, $favoriteVideo->childFolders);
        }
    }

    /**
     * 各フォルダーの登録処理
     *
     * @param bool $isParentFolderWrongUser
     * @param string $folderName
     * @param string $folderType
     * @param int|null $parentFolderId
     * @return TestResponse
     */
    private function insert_folder(bool $isParentFolderWrongUser, string $folderName, string $folderType, int $parentFolderId = null): TestResponse
    {
        $folderInfo = [
            'folder_name' => $folderName,
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => false
        ];
        if ($folderType === 'child'){
            $folderInfo = [...$folderInfo,'parent_folder_id' => $parentFolderId];
        }


        if ($isParentFolderWrongUser){
            $folder = $this->actingAs($this->users[2])->post("/api/favorite/folder/$folderType/store", $folderInfo);
        }else{
            $folder = $this->actingAs($this->users[1])->post("/api/favorite/folder/$folderType/store", $folderInfo);
        }

        return $folder;
    }
}
