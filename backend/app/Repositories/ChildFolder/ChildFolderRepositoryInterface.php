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
}
