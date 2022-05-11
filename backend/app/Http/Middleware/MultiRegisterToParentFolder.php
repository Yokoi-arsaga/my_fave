<?php

namespace App\Http\Middleware;

use App\Models\ParentFolder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultiRegisterToParentFolder
{
    /**
     * ユーザーが親フォルダーを持っていない場合にリダイレクト
     * 指定されたIDの親フォルダーを持っていない場合にリダイレクト
     *
     * @param  Request  $request
     * @param Closure  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $parentFolderCount = ParentFolder::where('user_id', Auth::id())->count();
        $parentFolder = ParentFolder::where('user_id', Auth::id())->get();

        if ($parentFolderCount > 0 && $parentFolder->contains($request->parentFolderId)){
            return $next($request);
        }else{
            return back();
        }
    }
}
