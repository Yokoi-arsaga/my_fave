<?php

namespace App\Repositories\GrandchildFolder;

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
}
