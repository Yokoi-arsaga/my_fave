<?php

namespace App\Http\Middleware;

use App\Models\ChildFolder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterToChildFolder
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
        $childFolderCount = ChildFolder::where('user_id', Auth::id())->count();
        $childFolder = ChildFolder::where('user_id', Auth::id())->get();

        if ($childFolderCount > 0 && $childFolder->contains($request->folder_id)){
            return $next($request);
        }else{
            return back();
        }
    }
}
