<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Modules\ApplicationLogger;
use App\ViewModel\UserViewModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

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
     * @return Application|Factory|View
     */
    public function edit()
    {
        return view('Profile.edit');
    }

    /**
     * プロフィールの更新
     *
     * @param ProfileRequest $request
     * @return JsonResponse
     */
    public function store(ProfileRequest $request): JsonResponse
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('プロフィールの更新処理を開始');
        $editProfile = $this->userRepository->editProfile($request);

        $logger->success();
        return response()->json($editProfile);
    }

    /**
     * 通知一覧の取得
     *
     * @return JsonResponse
     */
    public function notifications(): JsonResponse
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('通知一覧の取得処理を開始');
        $notifications = $this->userRepository->fetchNotifications();

        $logger->success();
        return response()->json($notifications);
    }
}
