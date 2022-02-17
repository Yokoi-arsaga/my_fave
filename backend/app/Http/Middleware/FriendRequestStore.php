<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendRequestStore
{
    /**
     * ユーザーのIDと申請先のIDが一致する場合リダイレクト
     * 申請先のIDが存在しない場合リダイレクト
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->input('destination_id') === Auth::id() || is_null(User::find($request->input('destination_id')))) {
            return back();
        } else {
            return $next($request);
        }
    }
}
