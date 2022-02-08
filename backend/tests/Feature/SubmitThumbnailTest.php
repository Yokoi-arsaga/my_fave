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

class SubmitThumbnailTest extends TestCase
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
     * サムネイル投稿に成功したテスト
     *
     * @return void
     */
    public function test_submit_thumbnail_success()
    {
        Storage::fake('s3');

        $response = $this->actingAs($this->users[1])->post('/thumbnail',[
            'thumbnail' => UploadedFile::fake()->image('photo.jpg')
        ]);

        $response->assertStatus(201);

        $thumbnail = Thumbnail::first();

        Storage::disk('s3')->assertExists($thumbnail->full_file_name);
    }

    /**
     * DB保存に失敗した場合の挙動のテスト
     *
     * @return void
     */
    public function test_submit_thumbnail_failure_by_db_error()
    {
        Schema::table('thumbnails', function (Blueprint $table) {
            $table->dropColumn('file_string');
        });

        Storage::fake('s3');

        $response = $this->actingAs($this->users[1])->post('/thumbnail',[
            'thumbnail' => UploadedFile::fake()->image('photo.jpeg')
        ]);

        $response->assertStatus(500);

        $this->assertCount(0, Storage::disk('s3')->files());
    }

    /**
     * ストレージ保存に失敗した場合の挙動のテスト
     *
     * @return void
     */
    public function test_submit_thumbnail_failure_by_storage_error()
    {
        Storage::shouldReceive('disk')
            ->once()
            ->andReturnNull();

        $response = $this->actingAs($this->users[1])->post('/thumbnail',[
            'thumbnail' => UploadedFile::fake()->image('photo.jpeg')
        ]);

        $response->assertStatus(500);

        $this->assertEmpty(Thumbnail::all());
    }

    /**
     * ユニークなファイル名が保存されているかのテスト
     *
     * @return void
     */
    public function test_filename_unique()
    {
        Storage::fake('s3');

        $arr = [1, 2, 3, 4, 5];
        foreach ($arr as $value){
            $this->actingAs($this->users[$value])->post('/thumbnail',[
                'thumbnail' => UploadedFile::fake()->image('photo.jpg')
            ]);
        }

        $thumbnails = Thumbnail::get(['full_file_name']);

        $keyRecord = $thumbnails->get(0);
        $thumbnails->shift();

        $this->assertNotContains($keyRecord, $thumbnails);
    }

    /**
     * バリデーションエラーテスト（画像がない）
     *
     * @return void
     */
    public function test_validation_by_image_less()
    {
        Storage::fake('s3');

        $response = $this->actingAs($this->users[1])->post('/thumbnail');

        $response->assertStatus(302);

        $this->assertEmpty(Thumbnail::all());
    }

    /**
     * バリデーションエラーテスト（拡張子が正しくない）
     *
     * @return void
     */
    public function test_validation_by_no_suitable_image()
    {
        Storage::fake('s3');

        $response = $this->actingAs($this->users[1])->post('/thumbnail',[
            'thumbnail' => UploadedFile::fake()->image('photo.tiff')
        ]);

        $response->assertStatus(302);

        $this->assertEmpty(Thumbnail::all());
    }
}
