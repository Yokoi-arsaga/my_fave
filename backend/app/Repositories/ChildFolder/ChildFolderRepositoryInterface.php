<?php

namespace App\Repositories\ChildFolder;

use App\Http\Requests\ChildFolderRequest;
use App\Models\ChildFolder;

interface ChildFolderRepositoryInterface
{
    /**
     * インサート
     *
     * @param ChildFolderRequest $request
     * @param int $parentFolderId
     * @return ChildFolder
     */
    public function storeChildFolder(ChildFolderRequest $request, int $parentFolderId): ChildFolder;
}
