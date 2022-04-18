<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChildFolderRequest;
use App\Http\Requests\ParentFolderRequest;
use App\Models\ChildFolder;
use App\Models\ParentFolder;
use App\Modules\ApplicationLogger;
use App\Repositories\ParentFolder\ParentFolderRepositoryInterface;
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
}
