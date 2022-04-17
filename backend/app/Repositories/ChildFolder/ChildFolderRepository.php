<?php

namespace App\Repositories\ChildFolder;

use App\Http\Requests\ChildFolderRequest;
use App\Models\ChildFolder;
use Illuminate\Support\Facades\Auth;

class ChildFolderRepository implements ChildFolderRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function storeChildFolder(ChildFolderRequest $request, int $parentFolderId): ChildFolder
    {
        return ChildFolder::create([
            'folder_name' => $request->getFolderName(),
            'description' => $request->getDescription(),
            'disclosure_range_id' => $request->getDisclosureRangeId(),
            'is_nest' => $request->getIsNest(),
            'user_id' => Auth::id(),
            'parent_folder_id' => $parentFolderId
        ]);
    }
}
