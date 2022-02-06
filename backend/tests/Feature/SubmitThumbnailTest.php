<?php

namespace Tests\Feature;

use App\Models\Thumbnail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
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
}
