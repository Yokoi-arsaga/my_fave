<?php

namespace App\Repositories\ParentFolder;

use App\Http\Requests\ChangeRegistrationFavoriteVideoRequest;
use App\Http\Requests\DetachRegistrationFavoriteVideoRequest;
use App\Http\Requests\MultiRegisterFavoriteVideosRequest;
use App\Models\ChildFolder;
use App\Models\GrandchildFolder;
use App\Models\ParentFolder;
use App\Http\Requests\ParentFolderRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function registerFavoriteVideo(int $parentFolderId, int $favoriteVideoId): ParentFolder
    {
        $parentFolder = ParentFolder::find($parentFolderId);
        $parentFolder->favoriteVideos()->syncWithoutDetaching($favoriteVideoId);
        return $parentFolder;
    }

    /**
     * @inheritDoc
     */
    public function multiRegisterFavoriteVideos(MultiRegisterFavoriteVideosRequest $request, int $parentFolderId): ParentFolder
    {
        $parentFolder = ParentFolder::find($parentFolderId);
        $parentFolder->favoriteVideos()->syncWithoutDetaching($request->getFavoriteVideoIds());
        return $parentFolder;
    }

    /**
     * @inheritDoc
     */
    public function changeRegistration(ChangeRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): ParentFolder
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
            $destinationFolder = ParentFolder::find($request->getDestinationFolderId());
            $destinationFolder->favoriteVideos()->syncWithoutDetaching($favoriteVideoId);

            return $destinationFolder;
        });
    }

    /**
     * @inheritDoc
     */
    public function detachRegistration(DetachRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): void
    {
        $parentFolder = ParentFolder::find($request->getFolderId());
        $parentFolder->favoriteVideos()->detach($favoriteVideoId);
    }
}
