<?php

namespace App\Repositories\ParentFolder;

use App\Models\ParentFolder;
use App\Http\Requests\ParentFolderRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ParentFolderRepository implements ParentFolderRepositoryInterface
{
    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
    public function fetchParentFolders(): Collection
    {
        return ParentFolder::where('user_id', Auth::id())->get();
    }
}
