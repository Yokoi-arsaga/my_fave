<?php

namespace App\Repositories\Friend;

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
