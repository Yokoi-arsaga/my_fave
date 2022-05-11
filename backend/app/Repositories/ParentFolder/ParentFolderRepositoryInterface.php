<?php

namespace App\Repositories\ParentFolder;

use App\Http\Requests\ChangeRegistrationFavoriteVideoRequest;
use App\Http\Requests\DetachRegistrationFavoriteVideoRequest;
use App\Http\Requests\MultiRegisterFavoriteVideosRequest;
use App\Http\Requests\ParentFolderRequest;
use App\Models\ParentFolder;
use Illuminate\Support\Collection;

interface ParentFolderRepositoryInterface
{
    /**
     * インサート
     *
     * @param ParentFolderRequest $request
     * @return ParentFolder
     */
    public function storeParentFolder(ParentFolderRequest $request): ParentFolder;

    /**
     * ユーザーに紐づく親フォルダー一覧取得
     *
     * @return Collection
     */
    public function fetchParentFolders(): Collection;

    /**
     * 更新
     *
     * @param ParentFolderRequest $request
     * @param int $id
     * @return ParentFolder
     */
    public function updateParentFolder(ParentFolderRequest $request, int $id): ParentFolder;

    /**
     * 削除
     *
     * @param int $id
     * @return void
     */
    public function deleteParentFolder(int $id): void;

    /**
     * 公開範囲の変更
     *
     * @param int $disclosureRangeId
     * @param int $parentFolderId
     * @return ParentFolder
     */
    public function changeDisclosureRange(int $disclosureRangeId, int $parentFolderId): ParentFolder;

    /**
     * お気に入り動画を親フォルダーに登録
     *
     * @param int $parentFolderId
     * @param int $favoriteVideoId
     * @return ParentFolder
     */
    public function registerFavoriteVideo(int $parentFolderId, int $favoriteVideoId): ParentFolder;

    /**
     * 複数のお気に入り動画を親フォルダーに登録
     *
     * @param MultiRegisterFavoriteVideosRequest $request
     * @param int $parentFolderId
     * @return ParentFolder
     */
    public function multiRegisterFavoriteVideos(MultiRegisterFavoriteVideosRequest $request, int $parentFolderId): ParentFolder;

    /**
     * お気に入り動画の格納先を変更
     *
     * @param ChangeRegistrationFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return ParentFolder
     */
    public function changeRegistration(ChangeRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): ParentFolder;

    /**
     * お気に入り動画と親フォルダーの連携解除
     *
     * @param DetachRegistrationFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return void
     */
    public function detachRegistration(DetachRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): void;
}
