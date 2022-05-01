<?php

namespace App\Http\Middleware;

use App\Models\ChildFolder;
use App\Models\FavoriteVideo;
use App\Models\ParentFolder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterToParentFolder
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
        $parentFolderCount = ParentFolder::where('user_id', Auth::id())->count();
        $parentFolder = ParentFolder::where('user_id', Auth::id())->get();

        if ($parentFolderCount > 0 && $parentFolder->contains($request->folder_id)){
            return $next($request);
        }else{
            return back();
        }
    }
}
