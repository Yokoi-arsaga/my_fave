<?php

namespace App\Http\Middleware;

use App\Models\ChildFolder;
use App\Models\GrandchildFolder;
use App\Models\ParentFolder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeRegistrationToParentFolder
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
        $minCount = 1; // 変更元と変更先の合計2つ必要
        $parentFolderCount = ParentFolder::where('user_id', Auth::id())->count();
        $destinationFolder = ParentFolder::where('user_id', Auth::id())->get();

        if($request->source_folder_type === 'parent'){
            $sourceFolder = ParentFolder::where('user_id', Auth::id())->get();
        }else if ($request->source_folder_type === 'child'){
            $sourceFolder = ChildFolder::where('user_id', Auth::id())->get();
        }else{
            $sourceFolder = GrandchildFolder::where('user_id', Auth::id())->get();
        }

        if ($parentFolderCount > $minCount && $destinationFolder->contains($request->destination_folder_id) && $sourceFolder->contains($request->source_folder_id)){
            return $next($request);
        }else{
            return back();
        }
    }
}
