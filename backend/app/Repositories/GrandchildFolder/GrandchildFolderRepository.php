<?php

namespace App\Repositories\GrandchildFolder;

use App\Http\Requests\ChangeDisclosureRequest;
use App\Http\Requests\ChangeRegistrationFavoriteVideoRequest;
use App\Http\Requests\GrandchildFolderRequest;
use App\Models\ChildFolder;
use App\Models\GrandchildFolder;
use App\Models\ParentFolder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
     * @inheritDoc
     */
    public function updateGrandchildFolder(GrandchildFolderRequest $request, int $grandchildFolderId): GrandchildFolder
    {
        $grandchildFolder = GrandchildFolder::find($grandchildFolderId);
        $grandchildFolder->folder_name = $request->getFolderName();
        $grandchildFolder->description = $request->getDescription();
        $grandchildFolder->disclosure_range_id = $request->getDisclosureRangeId();
        $grandchildFolder->is_nest = $request->getIsNest();
        $grandchildFolder->child_folder_id = $request->getChildFolderId();
        $grandchildFolder->save();
        return $grandchildFolder;
    }

    /**
     * @inheritDoc
     */
    public function deleteGrandchildFolder(int $grandchildFolderId): void
    {
        GrandchildFolder::destroy($grandchildFolderId);
    }

    /**
     * @inheritDoc
     */
    public function changeDisclosureRange(ChangeDisclosureRequest $request, int $grandchildFolderId): GrandchildFolder
    {
        $grandchildFolder = GrandchildFolder::find($grandchildFolderId);
        $grandchildFolder->disclosure_range_id = $request->getDisclosureRangeId();
        $grandchildFolder->save();
        return $grandchildFolder;
    }

    /**
     * @inheritDoc
     */
    public function registerFavoriteVideo(int $grandchildFolderId, int $favoriteVideoId): GrandchildFolder
    {
        $grandchildFolder = GrandchildFolder::find($grandchildFolderId);
        $grandchildFolder->favoriteVideos()->syncWithoutDetaching($favoriteVideoId);
        return $grandchildFolder;
    }

    /**
     * @inheritDoc
     */
    public function changeRegistration(ChangeRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): GrandchildFolder
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
            $destinationFolder = GrandchildFolder::find($request->getDestinationFolderId());
            $destinationFolder->favoriteVideos()->syncWithoutDetaching($favoriteVideoId);

            return $destinationFolder;
        });
    }
}
