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
    public function storeFriendRequest(FriendRequestRequest $request, int $destinationId): FriendRequest
    {
        return FriendRequest::create([
            'applicant_id' => Auth::id(),
            'destination_id' => $destinationId,
            'message' => $request->getMessage()
        ]);
    }
}
