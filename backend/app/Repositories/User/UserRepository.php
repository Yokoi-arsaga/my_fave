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
        return $user;
    }
}
