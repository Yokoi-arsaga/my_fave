<?php

namespace App\Repositories\GrandchildFolder;

use App\Http\Requests\GrandchildFolderRequest;
use App\Models\GrandchildFolder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class GrandchildFolderRepository implements GrandchildFolderRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function storeGrandchildFolder(GrandchildFolderRequest $request): GrandchildFolder
    {
        return GrandchildFolder::create([
            'folder_name' => $request->getFolderName(),
            'description' => $request->getDescription(),
            'disclosure_range_id' => $request->getDisclosureRangeId(),
            'is_nest' => $request->getIsNest(),
            'user_id' => Auth::id(),
            'child_folder_id' => $request->getChildFolderId()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function fetchGrandchildFolder(int $childFolderId): Collection
    {
        return GrandchildFolder::where('child_folder_id', $childFolderId)->get();
    }
}
