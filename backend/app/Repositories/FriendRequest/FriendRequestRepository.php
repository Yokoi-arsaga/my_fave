<?php

namespace App\Repositories\FriendRequest;

use App\Http\Requests\FriendRequestRequest;
use App\Models\FriendRequest;
use Illuminate\Support\Facades\Auth;

class FriendRequestRepository implements FriendRequestRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function storeFriendRequest(FriendRequestRequest $request): FriendRequest
    {
        return FriendRequest::create([
            'applicant_id' => Auth::id(),
            'destination_id' => $request->getDestinationId(),
            'message' => $request->getMessage()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getFriendRequest(int $requestId): FriendRequest
    {
        return FriendRequest::find($requestId);
    }
}
