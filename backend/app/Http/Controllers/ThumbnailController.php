<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThumbnailRequest;
use App\Modules\GenerateFileName;

class ThumbnailController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    public function store(ThumbnailRequest $request)
    {
        $generateFileName = new GenerateFileName;
        return $generateFileName->getFileString();
    }

    public function test()
    {
        $generateFileName = new GenerateFileName;
        return $generateFileName->getFileString();
    }
}
