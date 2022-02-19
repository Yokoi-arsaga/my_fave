<?php

namespace App\ViewModel;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class FriendRequestViewModel
{
    /**
     * フレンド申請のデータをjsonで返却
     *
     * @param Collection $friendRequest
     * @return JsonResponse
     */
    public function toJson(Collection $friendRequest): JsonResponse
    {
        return response()->json($friendRequest);
    }
}
