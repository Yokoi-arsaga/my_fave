<?php

namespace App\Repositories\GrandchildFolder;

use App\Http\Requests\ChangeDisclosureRequest;
use App\Http\Requests\GrandchildFolderRequest;
use App\Models\GrandchildFolder;
use Illuminate\Support\Collection;

interface GrandchildFolderRepositoryInterface
{
    /**
     * インサート
     *
     * @param GrandchildFolderRequest $request
     * @return GrandchildFolder
     */
    public function storeGrandchildFolder(GrandchildFolderRequest $request): GrandchildFolder;

    /**
     * 子フォルダーに紐づく孫フォルダーの全件取得
     *
     * @param int $childFolderId
     * @return Collection
     */
    public function fetchGrandchildFolder(int $childFolderId): Collection;

    /**
     * 更新
     *
     * @param GrandchildFolderRequest $request
     * @param int $grandchildFolderId
     * @return GrandchildFolder
     */
    public function updateGrandchildFolder(GrandchildFolderRequest $request, int $grandchildFolderId): GrandchildFolder;

    /**
     * 削除
     *
     * @param int $grandchildFolderId
     * @return void
     */
    public function deleteGrandchildFolder(int $grandchildFolderId): void;

    /**
     * 公開範囲の変更
     *
     * @param ChangeDisclosureRequest $request
     * @param int $grandchildFolderId
     * @return GrandchildFolder
     */
    public function changeDisclosureRange(ChangeDisclosureRequest $request, int $grandchildFolderId): GrandchildFolder;
}