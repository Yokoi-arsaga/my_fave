<?php

namespace App\Repositories\ParentFolder;

use App\Models\ParentFolder;
use App\Http\Requests\ParentFolderRequest;
use Illuminate\Support\Facades\Auth;

class ParentFolderRepository implements ParentFolderRepositoryInterface
{
    public function storeParentFolder(ParentFolderRequest $request): ParentFolder
    {
        return ParentFolder::create([
            'folder_name' => $request->getFolderName(),
            'description' => $request->getDescription(),
            'disclosure_range_id' => $request->getDisclosureRangeId(),
            'is_nest' => $request->getIsNest(),
            'user_id' => Auth::id()
        ]);
    }
}
