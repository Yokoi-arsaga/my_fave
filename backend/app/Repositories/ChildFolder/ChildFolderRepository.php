<?php

namespace App\Repositories\ChildFolder;

use App\Http\Requests\ChangeRegistrationFavoriteVideoRequest;
use App\Http\Requests\ChildFolderRequest;
use App\Http\Requests\DetachRegistrationFavoriteVideoRequest;
use App\Http\Requests\MultiRegisterFavoriteVideosRequest;
use App\Models\ChildFolder;
use App\Models\GrandchildFolder;
use App\Models\ParentFolder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
     * @inheritDoc
     */
    public function multiRegisterFavoriteVideos(MultiRegisterFavoriteVideosRequest $request, int $childFolderId): ChildFolder
    {
        $childFolder = ChildFolder::find($childFolderId);
        $childFolder->favoriteVideos()->syncWithoutDetaching($request->getFavoriteVideoIds());
        return $childFolder;
    }

    /**
     * @inheritDoc
     */
    public function changeRegistration(ChangeRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): ChildFolder
    {
        return DB::transaction(function () use ($request, $favoriteVideoId){
            // 既存の中間テーブルの削除処理
            $sourceType = $request->getSourceFolderType();
            if ($sourceType === 'parent'){
                $sourceFolder = ParentFolder::find($request->getSourceFolderId());
            }else if($sourceType === 'child'){
                $sourceFolder = ChildFolder::find($request->getSourceFolderId());
            }else {
                $sourceFolder = GrandchildFolder::find($request->getSourceFolderId());
            }
            $sourceFolder->favoriteVideos()->detach($favoriteVideoId);

            // 格納先の中間テーブルの作成
            $destinationFolder = ChildFolder::find($request->getDestinationFolderId());
            $destinationFolder->favoriteVideos()->syncWithoutDetaching($favoriteVideoId);

            return $destinationFolder;
        });
    }

    /**
     * @inheritDoc
     */
    public function detachRegistration(DetachRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): void
    {
        $childFolder = ChildFolder::find($request->getFolderId());
        $childFolder->favoriteVideos()->detach($favoriteVideoId);
    }
}
