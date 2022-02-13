<?php

namespace App\Repositories\User;

use App\Http\Requests\ProfileRequest;
use App\Models\User;

/**
 * interface UserRepository ユーザーのサムネイル処理
 * @package App\Repositories\UserRepository
 */
interface UserRepositoryInterface
{
    /**
     * プロフィールの更新
     *
     * @param ProfileRequest $request
     * @return User
     */
    public function editProfile(ProfileRequest $request): User;
}
