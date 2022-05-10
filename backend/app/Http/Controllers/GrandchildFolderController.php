<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeDisclosureRequest;
use App\Http\Requests\ChangeRegistrationFavoriteVideoRequest;
use App\Http\Requests\DetachRegistrationFavoriteVideoRequest;
use App\Http\Requests\GrandchildFolderRequest;
use App\Http\Requests\MultiRegisterFavoriteVideosRequest;
use App\Http\Requests\RegisterFavoriteVideoRequest;
use App\Models\GrandchildFolder;
use App\Modules\ApplicationLogger;
use App\Repositories\GrandchildFolder\GrandchildFolderRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * 孫フォルダーに関するコントローラー
 *
 */
class GrandchildFolderController extends Controller
{
    private GrandchildFolderRepositoryInterface $grandchildFolderRepository;

    /**
     * @param GrandchildFolderRepositoryInterface $grandchildFolderRepository
     */
    public function __construct(
        GrandchildFolderRepositoryInterface $grandchildFolderRepository
    )
    {
        $this->grandchildFolderRepository = $grandchildFolderRepository;
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     *  孫フォルダーの登録
     *
     * @param GrandchildFolderRequest $request
     * @return GrandchildFolder
     */
    public function store(GrandchildFolderRequest $request): GrandchildFolder
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('孫フォルダーの登録処理開始');
        $grandchildFolder = $this->grandchildFolderRepository->storeGrandchildFolder($request);

        $logger->success();
        return $grandchildFolder;
    }

    /**
     * 子フォルダーに紐づく孫フォルダーの全件取得
     *
     * @param int $childFolderId
     * @return Collection
     */
    public function fetch(int $childFolderId): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('親フォルダーに紐づく子フォルダーの一覧取得処理開始');
        $grandchildFolders = $this->grandchildFolderRepository->fetchGrandchildFolder($childFolderId);

        $logger->success();
        return $grandchildFolders;
    }

    /**
     * 孫フォルダー情報の更新処理
     *
     * @param GrandchildFolderRequest $request
     * @param int $id
     * @return GrandchildFolder
     */
    public function update(GrandchildFolderRequest $request, int $id): GrandchildFolder
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('孫フォルダー情報の更新処理開始');
        $grandchildFolder = $this->grandchildFolderRepository->updateGrandchildFolder($request, $id);

        $logger->success();
        return $grandchildFolder;
    }

    /**
     * 孫フォルダーの削除
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('孫フォルダーの削除処理開始');
        $this->grandchildFolderRepository->deleteGrandchildFolder($id);

        $logger->success();
    }

    /**
     * 孫フォルダー公開範囲の変更
     *
     * @param ChangeDisclosureRequest $request
     * @param int $id
     * @return GrandchildFolder
     */
    public function changeDisclosure(ChangeDisclosureRequest $request, int $id): GrandchildFolder
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('孫フォルダー公開範囲の変更処理開始');
        $grandchildFolder = $this->grandchildFolderRepository->changeDisclosureRange($request, $id);

        $logger->success();
        return $grandchildFolder;
    }

    /**
     * お気に入り動画を孫フォルダーに登録
     *
     * @param RegisterFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return Collection
     */
    public function registerFavoriteVideo(RegisterFavoriteVideoRequest $request, int $favoriteVideoId): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画を孫フォルダーに登録処理開始');
        $grandchildFolder = $this->grandchildFolderRepository->registerFavoriteVideo($request->getFolderId(), $favoriteVideoId);

        $logger->success();
        return $grandchildFolder->favoriteVideos;
    }

    /**
     * 複数のお気に入り動画を孫フォルダーに登録
     *
     * @param MultiRegisterFavoriteVideosRequest $request
     * @param int $grandchildFolderId
     * @return Collection
     */
    public function multiRegisterFavoriteVideo(MultiRegisterFavoriteVideosRequest $request, int $grandchildFolderId): Collection
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('複数のお気に入り動画を孫フォルダーに登録処理開始');
        $grandchildFolder = $this->grandchildFolderRepository->multiRegisterFavoriteVideo($request, $grandchildFolderId);

        $logger->success();
        return $grandchildFolder->favoriteVideos;
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

        $logger->write('お気に入り動画の格納先を孫フォルダーに変更処理開始');
        $parentFolder = $this->grandchildFolderRepository->changeRegistration($request, $favoriteVideoId);

        $logger->success();
        return $parentFolder->favoriteVideos;
    }

    /**
     * お気に入り動画と孫フォルダーとの連携を解除
     *
     * @param DetachRegistrationFavoriteVideoRequest $request
     * @param int $favoriteVideoId
     * @return void
     */
    public function detachRegistration(DetachRegistrationFavoriteVideoRequest $request, int $favoriteVideoId): void
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('お気に入り動画と孫フォルダーとの連携解除処理開始');
        $this->grandchildFolderRepository->detachRegistration($request, $favoriteVideoId);

        $logger->success();
    }
}
