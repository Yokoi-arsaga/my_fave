<?php

namespace Tests\Feature;

use App\Models\Thumbnail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChangeThumbnailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $arr = [1, 2, 3, 4, 5];
        foreach ($arr as $value){
            $this->users[$value] = User::factory()->create();
        }
    }

    /**
     * サムネイル変更に成功したテスト
     *
     * @return void
     */
    public function test_change_thumbnail_success()
    {
        Storage::fake('s3');

        $current = $this->actingAs($this->users[1])->post('/api/thumbnail',[
            'thumbnail' => UploadedFile::fake()->image('photo.jpg')
        ]);

        $response = $this->actingAs($this->users[1])->patch('/api/thumbnail/change',[
            'thumbnail' => UploadedFile::fake()->image('photo.png')
        ]);

        $response->assertStatus(200);

        $this->assertCount(1, Storage::disk('s3')->files());

        $this->assertNotEquals($current['full_file_name'], $response['full_file_name']);
        $this->assertEquals($current['file_string'], $response['file_string']);
    }

    /**
     * サムネイルを設定していない場合のテスト
     *
     * @return void
     */
    public function test_change_thumbnail_failure_by_have_not_thumbnail()
    {
        Storage::fake('s3');

        $response = $this->actingAs($this->users[1])->patch('/api/thumbnail/change',[
            'thumbnail' => UploadedFile::fake()->image('photo.png')
        ]);

        $response->assertRedirect('/');

        $this->assertCount(0, Storage::disk('s3')->files());
    }

    /**
     * DB保存に失敗した場合の挙動のテスト
     *
     * @return void
     */
    public function test_submit_thumbnail_failure_by_db_error()
    {
        Storage::fake('s3');

        $current = $this->actingAs($this->users[1])->post('/api/thumbnail',[
            'thumbnail' => UploadedFile::fake()->image('photo.jpg')
        ]);

        Schema::table('thumbnails', function (Blueprint $table) {
            $table->dropColumn('file_string');
        });

        $response = $this->actingAs($this->users[1])->patch('/api/thumbnail/change',[
            'thumbnail' => UploadedFile::fake()->image('photo.png')
        ]);

        $response->assertStatus(500);

        $thumbnail = Thumbnail::first();

        $this->assertEquals($current['full_file_name'], $thumbnail['full_file_name']);
    }
}
