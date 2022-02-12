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

class DeleteThumbnailTest extends TestCase
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
     * サムネイル削除に成功したテスト
     *
     * @return void
     */
    public function test_change_thumbnail_success()
    {
        Storage::fake('s3');

        $this->actingAs($this->users[1])->post('/thumbnail',[
            'thumbnail' => UploadedFile::fake()->image('photo.jpg')
        ]);

        $response = $this->actingAs($this->users[1])->delete('/thumbnail');

        $response->assertStatus(200);

        $this->assertCount(0, Storage::disk('s3')->files());
        $this->assertEmpty(Thumbnail::all());
    }
}
