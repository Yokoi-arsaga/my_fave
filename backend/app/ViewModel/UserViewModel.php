<?php

namespace App\ViewModel;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class UserViewModel
{
    /**
     * ユーザーのデータをjsonで返却
     *
     * @param Collection $user
     * @return JsonResponse
     */
    public function toJson(Collection $user): JsonResponse
    {
        return response()->json($user);
    }
}
