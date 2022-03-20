<?php

namespace App\Http\Controllers;

use App\Http\Requests\FriendRequestRequest;
use Illuminate\Http\Request;
use App\Models\FriendRequest;
use App\Models\User;
use App\Modules\ApplicationLogger;
use App\Notifications\FriendRequestNotification;
use App\Repositories\FriendRequest\FriendRequestRepositoryInterface;
use App\Repositories\Friend\FriendRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * フレンド申請に関するコントローラー
 *
 */
class FriendRequestController extends Controller
{
    private FriendRequestRepositoryInterface $friendRequestRepository;
    private FriendRepositoryInterface $friendRepository;

    /**
     * @param FriendRequestRepositoryInterface $friendRequestRepository
     * @param FriendRepositoryInterface $friendRepository
     */
    public function __construct(
        FriendRequestRepositoryInterface $friendRequestRepository,
        FriendRepositoryInterface $friendRepository
    )
    {
        $this->friendRequestRepository = $friendRequestRepository;
        $this->friendRepository = $friendRepository;
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
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('フレンド申請の登録処理開始');
        $friendRequest = $this->friendRequestRepository->storeFriendRequest($request);

        $applicant = User::find(Auth::id());
        $destination = User::find($request->getDestinationId());

        $logger->write('通知処理開始');
        $destination->notify(
            new FriendRequestNotification($friendRequest, $applicant)
        );

        $logger->success();
        return $friendRequest;
    }

    /**
     * フレンド申請の許可
     *
     * @param Request $request
     * @return void
     */
    public function permission(Request $request): void
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('フレンド申請の取得開始');
        $friendRequest = $this->friendRequestRepository->getFriendRequest($request->request_id);

        $logger->write('フレンドの登録処理開始');
        $friend = $this->friendRepository->storeFriend($friendRequest->applicant_id);
    }
}
