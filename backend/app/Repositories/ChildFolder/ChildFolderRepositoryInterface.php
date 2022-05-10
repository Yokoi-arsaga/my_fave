<?php

namespace App\Repositories\ChildFolder;

use App\Http\Requests\ChangeRegistrationFavoriteVideoRequest;
use App\Http\Requests\ChildFolderRequest;
use App\Http\Requests\DetachRegistrationFavoriteVideoRequest;
use App\Http\Requests\MultiRegisterFavoriteVideosRequest;
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

    /**
     * 複数のお気に入り動画を子フォルダーに登録
     *
     * @param MultiRegisterFavoriteVideosRequest $request
     * @param int $childFolderId
     * @return Collection
     */
    public function multiRegisterFavoriteVideo(MultiRegisterFavoriteVideosRequest $request, int $childFolderId): Collection;

    /**
     * お気に入り動画の格納先を変更
     *
     * @param ChangeRegistrationFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return ChildFolder
     */
    public function changeRegistration(ChangeRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): ChildFolder;

    /**
     * お気に入り動画と子フォルダーとの連携解除
     *
     * @param DetachRegistrationFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return void
     */
    public function detachRegistration(DetachRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): void;
}
