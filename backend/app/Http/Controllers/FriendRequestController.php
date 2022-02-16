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
    }

    /**
     * フレンド申請の登録画面
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view();
    }

    /**
     * フレンド申請の登録
     *
     * @param FriendRequestRequest $request
     * @param int $destinationId
     * @return FriendRequest
     */
    public function store(FriendRequestRequest $request, int $destinationId): FriendRequest
    {
        return $this->friendRequestRepository->storeFriendRequest($request, $destinationId);
    }
}
