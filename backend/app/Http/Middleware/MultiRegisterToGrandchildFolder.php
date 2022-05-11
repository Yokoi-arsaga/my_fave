<?php

namespace App\Http\Middleware;

use App\Models\GrandchildFolder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultiRegisterToGrandchildFolder
{
    /**
     * ユーザーが孫フォルダーを持っていない場合にリダイレクト
     * 指定されたIDの孫フォルダーを持っていない場合にリダイレクト
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Closure  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $grandchildFolderCount = GrandchildFolder::where('user_id', Auth::id())->count();
        $grandchildFolder = GrandchildFolder::where('user_id', Auth::id())->get();

        if ($grandchildFolderCount > 0 && $grandchildFolder->contains($request->grandchildFolderId)){
            return $next($request);
        }else{
            return back();
        }
    }
}
