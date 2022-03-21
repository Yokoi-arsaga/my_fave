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

    /**
     * 詳細取得
     *
     * @param int $requestId
     * @return FriendRequest
     */
    public function getFriendRequest(int $requestId): FriendRequest;

    /**
     * 削除処理
     *
     * @param int $requestId
     * @return void
     */
    public function deleteFriendRequest(int $requestId): void;
}
