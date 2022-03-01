<?php

namespace App\Repositories\User;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function editProfile(ProfileRequest $request): User
    {
        $user = User::find(Auth::id());
        $user->name = $request->getName();
        $user->description = $request->getDescription();
        $user->location = $request->getLocation();
        $user->save();
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function toggleThumbnailFlag(bool $flag): void
    {
        $user = User::find(Auth::id());
        $user->have_thumbnail = $flag;
        $user->save();
    }

    /**
     * @inheritDoc
     */
    public function fetchNotifications(): mixed
    {
        $user = User::find(Auth::id());
        return $user->notifications;
    }
}
