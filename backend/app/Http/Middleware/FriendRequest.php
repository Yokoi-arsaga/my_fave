<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendRequest
{
    /**
     * ユーザーのIDと申請先のIDが一致する場合リダイレクト
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->input('destination_id') === Auth::id()){
            return back();
        }else{
            return $next($request);
        }
    }
}
