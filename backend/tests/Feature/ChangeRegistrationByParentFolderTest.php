<?php

namespace Tests\Feature;

use App\Models\FavoriteVideo;
use App\Models\ParentFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChangeRegistrationByParentFolderTest extends TestCase
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
     * お気に入り動画の登録先を変更（親フォルダー）することに成功したテスト
     *
     * @return void
     */
    public function test_change_registration_to_parent_folder_success()
    {
        [$favoriteVideoId, $request] = $this->common_preparation();

        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/parent/change/$favoriteVideoId", $request);

        $response->assertStatus(200);

        $this->assertEquals($response[0]['pivot']['parent_folder_id'], $request['destination_folder_id']);
    }

    /**
     * 存在しないお気に入り動画を指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_change_registration_to_parent_folder_failure_by_wrong_favorite_video()
    {
        [$favoriteVideoId, $request] = $this->common_preparation();
        $wrongFavoriteVideoId = 2;
        $this->common_validation_logic($wrongFavoriteVideoId, $request);
    }

    /**
     * 存在しない親フォルダーを指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_change_registration_to_parent_folder_failure_by_wrong_parent_folder()
    {
        [$favoriteVideoId, $request] = $this->common_preparation();
        $wrongRequest = [
            'source_folder_type' => 'parent',
            'source_folder_id' => $request['source_folder_id'],
            'destination_folder_id' => 3
        ];
        $this->common_validation_logic($favoriteVideoId, $wrongRequest);
    }

    /**
     * 指定したお気に入り動画が自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_change_registration_to_parent_folder_failure_by_have_not_favorite_video()
    {
        [$favoriteVideoId, $request] = $this->common_preparation(true, false);
        $this->common_validation_logic($favoriteVideoId, $request);
    }

    /**
     * 指定した親フォルダーが自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_have_not_parent_folder()
    {
        [$favoriteVideoId, $request] = $this->common_preparation(false, true);
        $this->common_validation_logic($favoriteVideoId, $request);
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

        $sourceFolder = $this->insert_folder($isParentFolderWrongUser, 'サンプル1');
        $sourceFolderId = ['folder_id' => $sourceFolder['id']];
        $this->actingAs($this->users[1])->post("/api/favorite/folder/parent/register/$favoriteVideoId", $sourceFolderId);
        $destinationFolder = $this->insert_folder($isParentFolderWrongUser, 'サンプル2');

        $request = [
            'source_folder_type' => 'parent',
            'source_folder_id' => $sourceFolder['id'],
            'destination_folder_id' => $destinationFolder['id']
        ];

        return [$favoriteVideoId, $request];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param int $favoriteVideoId
     * @param array $request
     * @return void
     */
    private function common_validation_logic(int $favoriteVideoId, array $request)
    {
        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/parent/change/$favoriteVideoId", $request);

        $response->assertRedirect('/');
    }

    /**
     * 親フォルダーの登録処理
     *
     * @param bool $isParentFolderWrongUser
     * @param string $folderName
     * @return TestResponse
     */
    private function insert_folder(bool $isParentFolderWrongUser, string $folderName): TestResponse
    {
        $parentFolderInfo = [
            'folder_name' => $folderName,
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => false
        ];

        if ($isParentFolderWrongUser){
            $parentFolder = $this->actingAs($this->users[2])->post('/api/favorite/folder/parent/store', $parentFolderInfo);
        }else{
            $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);
        }

        return $parentFolder;
    }
}
