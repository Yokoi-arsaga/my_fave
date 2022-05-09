<?php

namespace Tests\Feature\FavoriteVideo\ChildFolder;

use App\Models\ChildFolder;
use App\Models\FavoriteVideo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class MultiRegisterByChildFolderTest extends TestCase
{
    use RefreshDatabase;

    // 一度に登録できる最大数（この値はお気に入り動画のページネーションの値と一致する）
    private int $maxRegistrationNumber = 15;

    protected function setUp(): void
    {
        parent::setUp();

        $arr = [1, 2];
        foreach ($arr as $value) {
            $this->users[$value] = User::factory()->create();
        }
    }

    /**
     * お気に入り動画複数を子フォルダーに登録することに成功したテスト
     *
     * @return void
     */
    public function test_multi_register_by_child_folder_success()
    {
        $registrationNumber = 5;
        [$favoriteVideoIds, $childFolderId] = $this->common_preparation($registrationNumber);

        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/child/multi/register/$childFolderId", $favoriteVideoIds);

        $response->assertStatus(200);

        $response->assertJsonCount($registrationNumber);
    }

    /**
     * お気に入り動画が一件だけだったとしても子フォルダーに登録することに成功したテスト
     *
     * @return void
     */
    public function test_multi_register_by_child_folder_success_even_when_only_one()
    {
        $registrationNumber = 1;
        [$favoriteVideoIds, $childFolderId] = $this->common_preparation($registrationNumber);

        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/child/multi/register/$childFolderId", $favoriteVideoIds);

        $response->assertStatus(200);

        $response->assertJsonCount($registrationNumber);
    }

    /**
     * 一度に登録できる最大数でのお気に入り動画の子フォルダーへの登録に成功したテスト
     *
     * @return void
     */
    public function test_multi_register_by_child_folder_success_even_when_max_num()
    {
        [$favoriteVideoIds, $childFolderId] = $this->common_preparation($this->maxRegistrationNumber);

        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/child/multi/register/$childFolderId", $favoriteVideoIds);

        $response->assertStatus(200);

        $response->assertJsonCount($this->maxRegistrationNumber);
    }

    /**
     * 登録するお気に入り動画が一件も存在しない場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_multi_register_by_child_folder_failure_by_empty_registration()
    {
        $registrationNumber = 0;
        [$favoriteVideoIds, $childFolderId] = $this->common_preparation($registrationNumber);
        $this->common_validation_logic($favoriteVideoIds, $childFolderId);
    }

    /**
     * 登録するお気に入り動画が最大数を超えていた場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_multi_register_by_child_folder_failure_by_exceeding_tolerance()
    {
        $registrationNumber = $this->maxRegistrationNumber + 1;
        [$favoriteVideoIds, $childFolderId] = $this->common_preparation($registrationNumber);
        $this->common_validation_logic($favoriteVideoIds, $childFolderId);
    }

    /**
     * お気に入り動画IDの配列内に数値以外の値が含まれていた場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_child_folder_failure_by_wrong_ids_type()
    {
        $registrationNumber = 2;
        [$favoriteVideoIds, $childFolderId] = $this->common_preparation($registrationNumber);
        $wrongFavoriteVideoIds = [1,'a'];
        $this->common_validation_logic($wrongFavoriteVideoIds, $childFolderId);
    }

    /**
     * 存在しないお気に入り動画を指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_child_folder_failure_by_wrong_favorite_video()
    {
        $registrationNumber = 2;
        [$favoriteVideoIds, $childFolderId] = $this->common_preparation($registrationNumber);
        $wrongFavoriteVideoIds = [1,3];
        $this->common_validation_logic($wrongFavoriteVideoIds, $childFolderId);
    }

    /**
     * 存在しない子フォルダーを指定した場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_child_folder_failure_by_wrong_parent_folder()
    {
        $registrationNumber = 2;
        [$favoriteVideoIds, $childFolderId] = $this->common_preparation($registrationNumber);
        $wrongChildFolderId = 2;
        $this->common_validation_logic($favoriteVideoIds, $wrongChildFolderId);
    }

    /**
     * 指定したお気に入り動画が自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_child_folder_failure_by_have_not_favorite_video()
    {
        [$favoriteVideoIds, $childFolderId] = $this->common_preparation(true, false);
        $this->common_validation_logic($favoriteVideoIds, $childFolderId);
    }

    /**
     * 指定した子フォルダーが自身のものでなかった場合にリダイレクトされることを確認
     *
     * @return void
     */
    public function test_register_by_child_folder_failure_by_have_not_parent_folder()
    {
        [$favoriteVideoIds, $childFolderId] = $this->common_preparation(false, true);
        $this->common_validation_logic($favoriteVideoIds, $childFolderId);
    }

    /**
     * テスト実行前の準備
     *
     * @param int $registrationNumber
     * @param bool|null $isFavoriteVideoWrongUser
     * @param bool|null $isChildFolderWrongUser
     * @return array
     */
    private function common_preparation(int $registrationNumber, ?bool $isFavoriteVideoWrongUser = false, ?bool $isChildFolderWrongUser = false): array
    {
        $returnFavoriteVideoIds = $this->make_favorite_video_ids($registrationNumber, $isFavoriteVideoWrongUser);

        // 子フォルダー作成には親フォルダーが必要
        $parentFolder = $this->insert_folder($isChildFolderWrongUser, 'サンプル1', 'parent');
        $childFolder = $this->insert_folder($isChildFolderWrongUser, 'サンプル1', 'child', $parentFolder['id']);

        $childFolderId = $childFolder['id'];
        return [$returnFavoriteVideoIds, $childFolderId];
    }

    /**
     * バリデーション関連のテストの共通ロジック
     *
     * @param array $favoriteVideoIds
     * @param int $childFolderId
     * @return void
     */
    private function common_validation_logic(array $favoriteVideoIds, int $childFolderId)
    {
        $response = $this->actingAs($this->users[1])->post("/api/favorite/folder/child/multi/register/$childFolderId", $favoriteVideoIds);

        $response->assertRedirect('/');

        $childFolder = ChildFolder::where('user_id', Auth::id())->first();
        $favoriteVideo = FavoriteVideo::where('user_id', Auth::id())->first();

        if (isset($childFolder)){
            $this->assertEmpty($childFolder->favoriteVideos);
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
