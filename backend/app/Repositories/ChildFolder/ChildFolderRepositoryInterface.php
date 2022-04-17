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
     * @return ChildFolder
     */
    public function storeChildFolder(ChildFolderRequest $request): ChildFolder;
}
