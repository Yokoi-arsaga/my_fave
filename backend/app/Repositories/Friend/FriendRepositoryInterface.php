<?php

namespace App\Repositories\Friend;

use App\Models\Friend;

/**
 * interface SnsAccountRepository SNSアカウントの処理
 * @package App\Repositories\SnsAccountRepository
 */
interface FriendRepositoryInterface
{
    /**
     * インサート
     *
     * @param int $applicantId
     * @return Friend
     */
    public function storeFriend(int $applicantId, int $authorizerId): Friend;
}
