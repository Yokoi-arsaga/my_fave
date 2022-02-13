<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Repositories\User\UserRepositoryInterface;

/**
 * ユーザーに関するコントローラー
 *
 */
class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;


    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        // 認証が必要
        $this->middleware('auth');
    }


    public function store(ProfileRequest $request)
    {
        return $this->userRepository->editProfile($request);
    }
}
