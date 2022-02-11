<?php

namespace App\Http\Middleware;

use App\Models\Thumbnail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HaveThumbnail
{
    /**
     * ユーザーがサムネイルを設定していない場合リダイレクト
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Closure  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $count = Thumbnail::where('user_id', Auth::id())->count();
        if ($count > 0){
            return $next($request);
        }else{
            return back();
        }
    }
}
