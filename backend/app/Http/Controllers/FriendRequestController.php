<?php

namespace App\Http\Controllers;

use App\Http\Requests\FriendRequestRequest;
use App\Models\FriendRequest;
use App\Repositories\FriendRequest\FriendRequestRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * フレンド申請に関するコントローラー
 *
 */
class FriendRequestController extends Controller
{
    private FriendRequestRepositoryInterface $friendRequestRepository;

    /**
     * @param FriendRequestRepositoryInterface $friendRequestRepository
     */
    public function __construct(FriendRequestRepositoryInterface $friendRequestRepository)
    {
        $this->friendRequestRepository = $friendRequestRepository;
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * フレンド申請の登録画面
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('FriendRequest.create');
    }

    /**
     * フレンド申請の登録
     *
     * @param FriendRequestRequest $request
     * @return FriendRequest
     */
    public function store(FriendRequestRequest $request): FriendRequest
    {
        
        return $this->friendRequestRepository->storeFriendRequest($request);
    }
}
