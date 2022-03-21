<?php

namespace App\Repositories\Friend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Friend;

class FriendRepository implements FriendRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function storeFriend(int $applicantId, int $authorizerId): Friend
    {
        return Friend::create([
            'applicant_id' => $applicantId,
            'authorizer_id' => $authorizerId,
        ]);
    }
}
