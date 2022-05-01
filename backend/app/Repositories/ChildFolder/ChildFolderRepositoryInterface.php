<?php

namespace App\Repositories\ChildFolder;

use App\Http\Requests\ChildFolderRequest;
use App\Models\ChildFolder;
use Illuminate\Support\Collection;

interface ChildFolderRepositoryInterface
{
    /**
     * インサート
     *
     * @param ChildFolderRequest $request
     * @return ChildFolder
     */
    public function storeChildFolder(ChildFolderRequest $request): ChildFolder;

    /**
     * 親フォルダーに紐づく子フォルダーの全件取得
     *
     * @param int $parentFolderId
     * @return Collection
     */
    public function fetchChildFolders(int $parentFolderId): Collection;

    /**
     * 更新
     *
     * @param ChildFolderRequest $request
     * @param int $childFolderId
     * @return ChildFolder
     */
    public function updateChildFolder(ChildFolderRequest $request, int $childFolderId): ChildFolder;

    /**
     * 削除
     *
     * @param int $childFolderId
     * @return void
     */
    public function deleteChildFolder(int $childFolderId): void;

    /**
     * 公開範囲の変更
     *
     * @param int $disclosureRangeId
     * @param int $childFolderId
     * @return ChildFolder
     */
    public function changeDisclosureRange(int $disclosureRangeId, int $childFolderId): ChildFolder;

    /**
     * お気に入り動画を子フォルダーに登録
     *
     * @param int $childFolderId
     * @param int $favoriteVideoId
     * @return ChildFolder
     */
    public function registerFavoriteVideo(int $childFolderId, int $favoriteVideoId): ChildFolder;
}
