<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeDisclosureRequest;
use App\Http\Requests\ChangeRegistrationFavoriteVideoRequest;
use App\Http\Requests\DetachRegistrationFavoriteVideoRequest;
use App\Http\Requests\MultiRegisterFavoriteVideosRequest;
use App\Http\Requests\ParentFolderRequest;
use App\Http\Requests\RegisterFavoriteVideoRequest;
use App\Models\ParentFolder;
use App\Modules\ApplicationLogger;
use App\Repositories\ParentFolder\ParentFolderRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * 親フォルダーに関するコントローラー
 *
 */
class ParentFolderController extends Controller
{
    private ParentFolderRepositoryInterface $parentFolderRepository;

    /**
     * @param ParentFolderRepositoryInterface $parentFolderRepository
     */
    public function __construct(
        ParentFolderRepositoryInterface $parentFolderRepository
    )
    {
        $this->parentFolderRepository = $parentFolderRepository;
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * 親フォルダーの登録
     *
     * @param ParentFolderRequest $request
     * @return ParentFolder
     */
    public function store(ParentFolderRequest $request): ParentFolder
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('親フォルダーの登録処理開始');
        $favoriteVideo = $this->parentFolderRepository->storeParentFolder($request);

        $logger->success();
        return $favoriteVideo;
    }

    /**
     * ユーザーに紐づく親フォルダー一覧の取得
     *
     * @return Collection
     */
    public function fetch(): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('親フォルダーの一覧取得処理開始');
        $favoriteVideos = $this->parentFolderRepository->fetchParentFolders();

        $logger->success();
        return $favoriteVideos;
    }

    /**
     * 親フォルダー情報の更新
     *
     * @param ParentFolderRequest $request
     * @param int $id
     * @return ParentFolder
     */
    public function update(ParentFolderRequest $request, int $id): ParentFolder
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('親フォルダー情報の更新処理開始');
        $parentFolder = $this->parentFolderRepository->updateParentFolder($request, $id);

        $logger->success();
        return $parentFolder;
    }

    /**
     * 親フォルダーの削除
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('親フォルダーの削除処理開始');
        $this->parentFolderRepository->deleteParentFolder($id);

        $logger->success();
    }

    /**
     * 親フォルダー公開範囲の変更
     *
     * @param ChangeDisclosureRequest $request
     * @param int $id
     * @return ParentFolder
     */
    public function changeDisclosure(ChangeDisclosureRequest $request, int $id): ParentFolder
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('親フォルダー公開範囲の変更処理開始');
        $parentFolder = $this->parentFolderRepository->changeDisclosureRange($request->getDisclosureRangeId(), $id);

        $logger->success();
        return $parentFolder;
    }

    /**
     * お気に入り動画を親フォルダーに登録
     *
     * @param RegisterFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return Collection
     */
    public function registerFavoriteVideo(RegisterFavoriteVideoRequest $request, int $favoriteVideoId): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画を親フォルダーに登録処理開始');
        $parentFolder = $this->parentFolderRepository->registerFavoriteVideo($request->getFolderId(), $favoriteVideoId);

        $logger->success();
        return $parentFolder->favoriteVideos;
    }

    /**
     * 複数のお気に入り動画を親フォルダーに登録
     *
     * @param MultiRegisterFavoriteVideosRequest $request
     * @param int $parentFolderId
     * @return Collection
     */
    public function multiRegisterFavoriteVideo(MultiRegisterFavoriteVideosRequest $request, int $parentFolderId): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('複数のお気に入り動画を親フォルダーに登録処理開始');
        $parentFolder = $this->parentFolderRepository->multiRegisterFavoriteVideo($request, $parentFolderId);

        $logger->success();
        return $parentFolder->favoriteVideos;
    }

    /**
     * お気に入り動画の格納先の変更
     *
     * @param ChangeRegistrationFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return Collection
     */
    public function changeRegistration(ChangeRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画の格納先を親フォルダーに変更処理開始');
        $parentFolder = $this->parentFolderRepository->changeRegistration($request, $favoriteVideoId);

        $logger->success();
        return $parentFolder->favoriteVideos;
    }

    /**
     * お気に入り動画と親フォルダーとの連携を解除
     *
     * @param DetachRegistrationFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return void
     */
    public function detachRegistration(DetachRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): void
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画と親フォルダーとの連携解除処理開始');
        $this->parentFolderRepository->detachRegistration($request, $favoriteVideoId);

        $logger->success();
    }
}
