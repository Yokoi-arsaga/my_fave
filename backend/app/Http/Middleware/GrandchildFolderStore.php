<?php

namespace App\Http\Middleware;

use App\Models\ChildFolder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrandchildFolderStore
{
    /**
     * ユーザーが子フォルダーを持っていない場合にリダイレクト
     * 指定されたIDの子フォルダーを持っていない場合にリダイレクト
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Closure  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $count = ChildFolder::where('user_id', Auth::id())->count();
        $childFolder = ChildFolder::where('user_id', Auth::id())->get();
        if ($count > 0 && $childFolder->contains($request->child_folder_id)){
            return $next($request);
        }else{
            return back();
        }
    }
}
