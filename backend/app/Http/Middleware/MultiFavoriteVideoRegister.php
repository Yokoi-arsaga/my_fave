<?php

namespace App\Http\Middleware;

use App\Models\FavoriteVideo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultiFavoriteVideoRegister
{
    // まとめて登録できるお気に入り動画の最大登録数
    private const MAX_REGISTRATION_NUM = 15;

    /**
     * ユーザーがお気に入り動画を持っていない場合にリダイレクト
     * 指定されたIDの子フォルダーを持っていない場合にリダイレクト
     * 指定されたIDの個数が15件を超えた場合にリダイレクト
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Closure  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $favoriteVideoCount = FavoriteVideo::where('user_id', Auth::id())->count();
        $favoriteVideo = FavoriteVideo::where('user_id', Auth::id())->get();

        $haveFavoriteVideo = true;
        if (isset($request->favorite_video_ids)){
            $registrationCount = count($request->favorite_video_ids);
            foreach ($request->favorite_video_ids as $favoriteVideoId){
                if (!$favoriteVideo->contains($favoriteVideoId)){
                    $haveFavoriteVideo = false;
                }
            }
        }else{
            $registrationCount = 0;
        }
        if ($favoriteVideoCount > 0 && $haveFavoriteVideo && $registrationCount <= self::MAX_REGISTRATION_NUM){
            return $next($request);
        }else{
            return back();
        }
    }
}
