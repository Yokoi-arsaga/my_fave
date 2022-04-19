<?php

namespace App\Repositories\ChildFolder;

use App\Http\Requests\ChildFolderRequest;
use App\Models\ChildFolder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ChildFolderRepository implements ChildFolderRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function storeChildFolder(ChildFolderRequest $request): ChildFolder
    {
        return ChildFolder::create([
            'folder_name' => $request->getFolderName(),
            'description' => $request->getDescription(),
            'disclosure_range_id' => $request->getDisclosureRangeId(),
            'is_nest' => $request->getIsNest(),
            'user_id' => Auth::id(),
            'parent_folder_id' => $request->getParentFolderId()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function fetchChildFolders(int $parentFolderId): Collection
    {
        return ChildFolder::where('parent_folder_id', $parentFolderId)->get();
    }

    /**
     * @inheritDoc
     */
    public function updateChildFolder(ChildFolderRequest $request, int $childFolderId): ChildFolder
    {
        // TODO: Implement updateChildFolder() method.
    }
}
