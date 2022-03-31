<?php

namespace Tests\Feature;

use App\Models\FavoriteVideo;
use App\Models\ParentFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UpdateParentFolderTest extends TestCase
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
     * 親フォルダーの情報更新に成功したテスト
     *
     * @return void
     */
    public function test_update_parent_folder_success()
    {
        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => null
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);

        $updateParentFolderInfo = [
            'folder_name' => 'サンプル2',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 2,
            'is_nest' => null
        ];

        $parentFolderId = $parentFolder['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/parent/$parentFolderId", $updateParentFolderInfo);

        $response->assertStatus(200);

        $updateParentFolder = ParentFolder::find($parentFolderId);

        $this->assertEquals($updateParentFolder->folder_name, $response['folder_name']);
    }

    /**
     * 動画URLが空欄だった場合にお気に入り動画の情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_favorite_video_failure_by_url_empty()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $updateFavoriteVideoInfo = [
            'video_url' => '',
            'video_name' => 'サンプル2',
        ];

        $favoriteVideoId = $favoriteVideo['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$favoriteVideoId", $updateFavoriteVideoInfo);

        $response->assertRedirect('/');

        $updateFavoriteVideo = FavoriteVideo::find($favoriteVideoId);

        $this->assertEquals($updateFavoriteVideo->video_url, $favoriteVideoInfo['video_url']);
    }

    /**
     * 動画名が空欄だった場合にお気に入り動画の情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_favorite_video_failure_by_name_empty()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $updateFavoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=DmdrWQXtL-U',
            'video_name' => '',
        ];

        $favoriteVideoId = $favoriteVideo['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$favoriteVideoId", $updateFavoriteVideoInfo);

        $response->assertRedirect('/');

        $updateFavoriteVideo = FavoriteVideo::find($favoriteVideoId);

        $this->assertEquals($updateFavoriteVideo->video_url, $favoriteVideoInfo['video_url']);
    }

    /**
     * 動画名が空欄だった場合にお気に入り動画の情報更新に失敗するテスト
     *
     * @return void
     */
    public function test_update_favorite_video_failure_by_format_different()
    {
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);

        $updateFavoriteVideoInfo = [
            'video_url' => 'https://www.arsaga.jp/',
            'video_name' => 'サンプル2',
        ];

        $favoriteVideoId = $favoriteVideo['id'];
        $response = $this->actingAs($this->users[1])->patch("/api/favorite/videos/$favoriteVideoId", $updateFavoriteVideoInfo);

        $response->assertRedirect('/');

        $updateFavoriteVideo = FavoriteVideo::find($favoriteVideoId);

        $this->assertEquals($updateFavoriteVideo->video_url, $favoriteVideoInfo['video_url']);
    }
}
