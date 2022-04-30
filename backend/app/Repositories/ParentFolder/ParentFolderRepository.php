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

    /**
     * @inheritDoc
     */
    public function updateParentFolder(ParentFolderRequest $request, int $id): ParentFolder
    {
        $parentFolder = ParentFolder::find($id);
        $parentFolder->folder_name = $request->getFolderName();
        $parentFolder->description = $request->getDescription();
        $parentFolder->disclosure_range_id = $request->getDisclosureRangeId();
        $parentFolder->is_nest = $request->getIsNest();
        $parentFolder->save();
        return $parentFolder;
    }

    /**
     * @inheritDoc
     */
    public function deleteParentFolder(int $id): void
    {
        ParentFolder::destroy($id);
    }

    /**
     * @inheritDoc
     */
    public function changeDisclosureRange(int $disclosureRangeId, int $parentFolderId): ParentFolder
    {
        $parentFolder = ParentFolder::find($parentFolderId);
        $parentFolder->disclosure_range_id = $disclosureRangeId;
        $parentFolder->save();
        return $parentFolder;
    }

    /**
     * @inheritDoc
     */
    public function registerFavoriteVideo(int $parentFolderId, int $favoriteVideoId): Collection
    {
        $parentFolder = ParentFolder::find($parentFolderId);
        $parentFolder->favoriteVideos()->syncWithoutDetaching($favoriteVideoId);
        return $parentFolder->favoriteVideos();
    }
}
