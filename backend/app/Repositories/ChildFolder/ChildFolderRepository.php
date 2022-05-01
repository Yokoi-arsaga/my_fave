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
        $childFolder = ChildFolder::find($childFolderId);
        $childFolder->folder_name = $request->getFolderName();
        $childFolder->description = $request->getDescription();
        $childFolder->disclosure_range_id = $request->getDisclosureRangeId();
        $childFolder->is_nest = $request->getIsNest();
        $childFolder->parent_folder_id = $request->getParentFolderId();
        $childFolder->save();
        return $childFolder;
    }

    /**
     * @inheritDoc
     */
    public function deleteChildFolder(int $childFolderId): void
    {
        ChildFolder::destroy($childFolderId);
    }

    /**
     * @inheritDoc
     */
    public function changeDisclosureRange(int $disclosureRangeId, int $childFolderId): ChildFolder
    {
        $childFolder = ChildFolder::find($childFolderId);
        $childFolder->disclosure_range_id = $disclosureRangeId;
        $childFolder->save();
        return $childFolder;
    }

    /**
     * @inheritDoc
     */
    public function registerFavoriteVideo(int $childFolderId, int $favoriteVideoId): ChildFolder
    {
        $childFolder = ChildFolder::find($childFolderId);
        $childFolder->favoriteVideos()->syncWithoutDetaching($favoriteVideoId);
        return $childFolder;
    }
}
