<?php

namespace App\Modules;

use Illuminate\Support\Facades\Auth;

class GenerateFileName{
    public $fileString;

    public function __construct()
    {
        $date = date('YmdHis');
        $id = Auth::id();
        $randomString = $this->getRandomString();

        $this->fileString = $date. '-' .$id. '-' .$randomString;
    }

    private function getRandomString()
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
