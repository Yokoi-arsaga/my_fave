<?php

namespace App\Http\Middleware;

use App\Models\ParentFolder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildFolderStore
{
    /**
     * ユーザーが親フォルダーを持っていない場合にリダイレクト
     * 指定されたIDの親フォルダーを持っていない場合にリダイレクト
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Closure  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $count = ParentFolder::where('user_id', Auth::id())->count();
        $parentFolder = ParentFolder::where('user_id', Auth::id())->get();
        if ($count > 0 && $parentFolder->contains($request->parent_folder_id)){
            return $next($request);
        }else{
            return back();
        }
    }
}
