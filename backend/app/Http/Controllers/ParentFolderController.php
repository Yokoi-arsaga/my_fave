<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentFolderRequest;
use App\Models\ParentFolder;
use App\Modules\ApplicationLogger;
use App\Repositories\ParentFolder\ParentFolderRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * お気に入り動画に関するコントローラー
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
}
