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


    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * プロフィール編集ページ
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(){
        return view('Profile.edit');
    }

    /**
     * プロフィールの更新
     *
     * @param ProfileRequest $request
     * @return \App\Models\User
     */
    public function store(ProfileRequest $request)
    {
        return $this->userRepository->editProfile($request);
    }
}
