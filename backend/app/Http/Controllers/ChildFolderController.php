<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeDisclosureRequest;
use App\Http\Requests\ChangeRegistrationFavoriteVideoRequest;
use App\Http\Requests\ChildFolderRequest;
use App\Http\Requests\DetachRegistrationFavoriteVideoRequest;
use App\Http\Requests\MultiRegisterFavoriteVideosRequest;
use App\Http\Requests\RegisterFavoriteVideoRequest;
use App\Models\ChildFolder;
use App\Modules\ApplicationLogger;
use App\Repositories\ChildFolder\ChildFolderRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * 子フォルダーに関するコントローラー
 *
 */
class ChildFolderController extends Controller
{
    private ChildFolderRepositoryInterface $childFolderRepository;

    /**
     * @param ChildFolderRepositoryInterface $childFolderRepository
     */
    public function __construct(
        ChildFolderRepositoryInterface $childFolderRepository
    )
    {
        $this->childFolderRepository = $childFolderRepository;
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     *  子フォルダーの登録
     *
     * @param ChildFolderRequest $request
     * @return ChildFolder
     */
    public function store(ChildFolderRequest $request): ChildFolder
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('子フォルダーの登録処理開始');
        $childFolder = $this->childFolderRepository->storeChildFolder($request);

        $logger->success();
        return $childFolder;
    }

    /**
     * 親フォルダーに紐づく子フォルダーの全件取得
     *
     * @param int $parentFolderId
     * @return Collection
     */
    public function fetch(int $parentFolderId): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('親フォルダーに紐づく子フォルダーの一覧取得処理開始');
        $childFolders = $this->childFolderRepository->fetchChildFolders($parentFolderId);

        $logger->success();
        return $childFolders;
    }

    /**
     * 子フォルダー情報の更新処理
     *
     * @param ChildFolderRequest $request
     * @param int $id
     * @return ChildFolder
     */
    public function update(ChildFolderRequest $request, int $id): ChildFolder
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('子フォルダー情報の更新処理開始');
        $childFolder = $this->childFolderRepository->updateChildFolder($request, $id);

        $logger->success();
        return $childFolder;
    }

    /**
     * 子フォルダーの削除
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('子フォルダーの削除処理開始');
        $this->childFolderRepository->deleteChildFolder($id);

        $logger->success();
    }

    /**
     * 子フォルダー公開範囲の変更
     *
     * @param ChangeDisclosureRequest $request
     * @param int $id
     * @return ChildFolder
     */
    public function changeDisclosure(ChangeDisclosureRequest $request, int $id): ChildFolder
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('子フォルダー公開範囲の変更処理開始');
        $childFolder = $this->childFolderRepository->changeDisclosureRange($request->getDisclosureRangeId(), $id);

        $logger->success();
        return $childFolder;
    }

    /**
     * お気に入り動画を子フォルダーに登録
     *
     * @param RegisterFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return Collection
     */
    public function registerFavoriteVideo(RegisterFavoriteVideoRequest $request, int $favoriteVideoId): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画を子フォルダーに登録処理開始');
        $childFolder = $this->childFolderRepository->registerFavoriteVideo($request->getFolderId(), $favoriteVideoId);

        $logger->success();
        return $childFolder->favoriteVideos;
    }

    /**
     * 複数のお気に入り動画を子フォルダーに登録
     *
     * @param MultiRegisterFavoriteVideosRequest $request
     * @param int $childFolderId
     * @return Collection
     */
    public function multiRegisterFavoriteVideos(MultiRegisterFavoriteVideosRequest $request, int $childFolderId): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('複数のお気に入り動画を子フォルダーに登録処理開始');
        $childFolder = $this->childFolderRepository->multiRegisterFavoriteVideos($request, $childFolderId);

        $logger->success();
        return $childFolder->favoriteVideos;
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

        $logger->write('お気に入り動画の格納先を子フォルダーに変更処理開始');
        $parentFolder = $this->childFolderRepository->changeRegistration($request, $favoriteVideoId);

        $logger->success();
        return $parentFolder->favoriteVideos;
    }

    /**
     * お気に入り動画と子フォルダーとの連携を解除
     *
     * @param DetachRegistrationFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return void
     */
    public function detachRegistration(DetachRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): void
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画と子フォルダーとの連携解除処理開始');
        $this->childFolderRepository->detachRegistration($request, $favoriteVideoId);

        $logger->success();
    }
}
