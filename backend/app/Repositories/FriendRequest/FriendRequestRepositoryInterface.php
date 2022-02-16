<?php

namespace App\Repositories\FriendRequest;

use App\Http\Requests\FriendRequestRequest;
use App\Models\FriendRequest;

/**
 * interface SnsAccountRepository SNSアカウントの処理
 * @package App\Repositories\SnsAccountRepository
 */
interface FriendRequestRepositoryInterface
{
    /**
     * インサート
     *
     * @param FriendRequestRequest $request
     * @return FriendRequest
     */
    public function storeFriendRequest(FriendRequestRequest $request): FriendRequest;
}
