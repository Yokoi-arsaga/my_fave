<?php
namespace App\Services\Thumbnail;

use App\Models\Thumbnail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ThumbnailService
{
    public function storeThumbnail(string $fileString, string $fullFileName, int $userId)
    {


        DB::beginTransaction();


    }
}
