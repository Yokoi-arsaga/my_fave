<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $favoriteVideoInfo = [
            'video_url' => 'https://www.youtube.com/watch?v=NwOvu-j_WjY',
            'video_name' => 'サンプル',
        ];

        $favoriteVideo = $this->actingAs($this->users[1])->post('/api/favorite/videos/store', $favoriteVideoInfo);
        $favoriteVideoId = $favoriteVideo['id'];

        $parentFolderInfo = [
            'folder_name' => 'サンプル',
            'description' => '動画フォルダーの説明文',
            'disclosure_range_id' => 1,
            'is_nest' => false
        ];

        $parentFolder = $this->actingAs($this->users[1])->post('/api/favorite/folder/parent/store', $parentFolderInfo);
        $parentFolderId = $parentFolder['id'];

        $response = $this->actingAs($this->users[1])->patch("/api/favorite/folder/parent/register/$favoriteVideoId", $parentFolderId);

        $response->assertStatus(200);
        $this->assertEquals($response['parent_folder_id'], $parentFolderId);
        $this->assertEquals($response['favorite_video_id'], $favoriteVideoId);
    }
}
