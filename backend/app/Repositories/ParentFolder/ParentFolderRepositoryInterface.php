<?php

namespace App\Repositories\ParentFolder;
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

}
