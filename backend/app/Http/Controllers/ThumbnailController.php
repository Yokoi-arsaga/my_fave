<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThumbnailRequest;
use Illuminate\Support\Facades\Auth;

class ThumbnailController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    public function store(ThumbnailRequest $request)
    {
        $extension = $request->thumbnail->extension();
    }

    public function getFileString()
    {
        $date = date('YmdHis');
        $id = Auth::id();
        $randomString = $this->getRandomString();

        return $date. '-' .$id. '-' .$randomString;
    }

    public function getRandomString()
    {
        $stringLength = 8;

        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < $stringLength; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }
}
