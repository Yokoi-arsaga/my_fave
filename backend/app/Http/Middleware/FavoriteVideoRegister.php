<?php

namespace App\Http\Middleware;

use App\Models\FavoriteVideo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteVideoRegister
{
    /**
     * ユーザーがお気に入り動画を持っていない場合にリダイレクト
     * 指定されたIDの子フォルダーを持っていない場合にリダイレクト
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Closure  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $favoriteVideoCount = FavoriteVideo::where('user_id', Auth::id())->count();
        $favoriteVideo = FavoriteVideo::where('user_id', Auth::id())->get();

        if ($favoriteVideoCount > 0 && $favoriteVideo->contains($request->favoriteVideoId)){
            return $next($request);
        }else{
            return back();
        }
    }
}
