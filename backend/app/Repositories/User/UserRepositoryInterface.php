<?php

namespace App\Repositories\User;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Notification;

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

    /**
     * サムネイル設定フラグの変更
     *
     * @param bool $flag
     * @return void
     */
    public function toggleThumbnailFlag(bool $flag): void;

    /**
     * 通知一覧の取得
     *
     * @return mixed
     */
    public function fetchNotifications(): mixed;
}
