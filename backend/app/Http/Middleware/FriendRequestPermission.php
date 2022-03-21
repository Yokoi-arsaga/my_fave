<?php

namespace App\Http\Middleware;

use App\Models\FriendRequest;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendRequestPermission
{
    /**
     * フレンド申請が存在しなかった場合にリダイレクト
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (is_null(FriendRequest::find($request->input('request_id')))) {
            return back();
        } else {
            return $next($request);
        }
    }
}
