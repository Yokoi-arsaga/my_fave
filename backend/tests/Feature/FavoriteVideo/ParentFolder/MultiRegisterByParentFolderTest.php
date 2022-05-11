<?php

namespace Tests\Feature\FavoriteVideo\ParentFolder;

use App\Models\FavoriteVideo;
use App\Models\ParentFolder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MultiRegisterByParentFolderTest extends TestCase
{
    use RefreshDatabase;

    // 一度に登録できる最大数（この値はお気に入り動画のページネーションの値と一致する）
    private const MAX_REGISTRATION_NUM = 15;

    protected function setUp(): void
    {
        parent::setUp();

        $arr = [1, 2];
        foreach ($arr as $value) {
            $this->users[$value] = User::factory()->create();
        }
    }

    /**
     * お気に入り動画複数を親フォルダーに登録することに成功したテスト
     *
     * @return void
     */
    public function test_multi_register_by_parent_folder_success()
    {
        $registrationNumber = 5;
        [$favoriteVideoIds, $parentFolderId] = $this->common_preparation($registrationNumber);

        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/parent/multi/register/$parentFolderId", $favoriteVideoIds);

        $response->assertStatus(200);
        $response->assertJsonCount($registrationNumber);
    }

    /**
     * お気に入り動画が一件だけだったとしても親フォルダーに登録することに成功したテスト
     *
     * @return void
     */
    public function test_multi_register_by_parent_folder_success_even_when_only_one()
    {
        $registrationNumber = 1;
        [$favoriteVideoIds, $parentFolderId] = $this->common_preparation($registrationNumber);

        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/parent/multi/register/$parentFolderId", $favoriteVideoIds);

        $response->assertStatus(200);
        $response->assertJsonCount($registrationNumber);
    }

    /**
     * 一度に登録できる最大数でのお気に入り動画の親フォルダーへの登録に成功したテスト
     *
     * @return void
     */
    public function test_multi_register_by_parent_folder_success_even_when_max_num()
    {
        [$favoriteVideoIds, $parentFolderId] = $this->common_preparation(self::MAX_REGISTRATION_NUM);

        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/parent/multi/register/$parentFolderId", $favoriteVideoIds);

        $response->assertStatus(200);
        $response->assertJsonCount(self::MAX_REGISTRATION_NUM);
    }

    /**
     * 登録するお気に入り動画が一件も存在しない場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_empty_registration()
    {
        $registrationNumber = 0;
        [$favoriteVideoIds, $parentFolderId] = $this->common_preparation($registrationNumber);
        $this->common_validation_logic($favoriteVideoIds, $parentFolderId);
    }

    /**
     * 登録するお気に入り動画が最大数を超えていた場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_exceeding_tolerance()
    {
        $excessRegistrationNumber = self::MAX_REGISTRATION_NUM + 1;
        [$favoriteVideoIds, $parentFolderId] = $this->common_preparation($excessRegistrationNumber);
        $this->common_validation_logic($favoriteVideoIds, $parentFolderId);
    }

    /**
     * お気に入り動画IDの配列内に数値以外の値が含まれていた場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_wrong_ids_type()
    {
        $registrationNumber = 2;
        [$favoriteVideoIds, $parentFolderId] = $this->common_preparation($registrationNumber);
        $wrongFavoriteVideoIds = [1,'a'];
        $this->common_validation_logic($wrongFavoriteVideoIds, $parentFolderId);
    }

    /**
     * 存在しないお気に入り動画を指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_wrong_favorite_video()
    {
        $registrationNumber = 2;
        [$favoriteVideoIds, $parentFolderId] = $this->common_preparation($registrationNumber);
        $wrongFavoriteVideoIds = [1,3];
        $this->common_validation_logic($wrongFavoriteVideoIds, $parentFolderId);
    }

    /**
     * 存在しない親フォルダーを指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_wrong_parent_folder()
    {
        $registrationNumber = 2;
        [$favoriteVideoIds, $parentFolderId] = $this->common_preparation($registrationNumber);
        $wrongParentFolderId = 2;
        $this->common_validation_logic($favoriteVideoIds, $wrongParentFolderId);
    }

    /**
     * 指定したお気に入り動画が自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_have_not_favorite_video()
    {
        $registrationNumber = 2;
        [$favoriteVideoIds, $parentFolderId] = $this->common_preparation($registrationNumber, true, false);
        $this->common_validation_logic($favoriteVideoIds, $parentFolderId);
    }

    /**
     * 指定した親フォルダーが自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_parent_folder_failure_by_have_not_parent_folder()
    {
        $registrationNumber = 2;
        [$favoriteVideoIds, $parentFolderId] = $this->common_preparation($registrationNumber, false, true);
        $this->common_validation_logic($favoriteVideoIds, $parentFolderId);
    }

    /**
     * テスト実行前の準備
     *
     * @param int $registrationNumber
     * @param bool|null $isFavoriteVideoWrongUser
     * @param bool|null $isParentFolderWrongUser
     * @return array
     */
    private function common_preparation(int $registrationNumber, ?bool $isFavoriteVideoWrongUser = false, ?bool $isParentFolderWrongUser = false): array
    {
        $returnFavoriteVideoIds = $this->make_favorite_video_ids($registrationNumber, $isFavoriteVideoWrongUser);

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
        $parentFolderId = $parentFolder['id'];
        return [$returnFavoriteVideoIds, $parentFolderId];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param array $favoriteVideoIds
     * @param int $parentFolderId
     * @return void
     */
    private function common_validation_logic(array $favoriteVideoIds, int $parentFolderId)
    {
        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/parent/multi/register/$parentFolderId", $favoriteVideoIds);

        $response->assertRedirect('/');

        $parentFolder = ParentFolder::where('user_id', Auth::id())->first();
        $favoriteVideo = FavoriteVideo::where('user_id', Auth::id())->first();

        if (isset($parentFolder)){
            $this->assertEmpty($parentFolder->favoriteVideos);
        }
        if (isset($favoriteVideo)){
            $this->assertEmpty($favoriteVideo->parentFolders);
        }
    }

    /**
     * 登録する複数のお気に入り動画IDリストの作成
     *
     * @param int $registrationNumber
     * @param bool|null $isFavoriteVideoWrongUser
     * @return array[]
     */
    private function make_favorite_video_ids(int $registrationNumber, ?bool $isFavoriteVideoWrongUser = false): array
    {
        $favoriteVideoIds = [];
        for ($i = 1; $i <= $registrationNumber; $i++){
            $favoriteVideoInfo = [
                'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
                'video_name' => 'サンプル',
            ];

            if ($isFavoriteVideoWrongUser){
                $favoriteVideo = $this->actingAs($this->users[2])->post('/api/favorite/videos/store', $favoriteVideoInfo);
            }else{
                $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);
            }
            $favoriteVideoIds[] = $favoriteVideo['id'];
        }
        return [
            'favorite_video_ids' => $favoriteVideoIds
        ];
    }
}
