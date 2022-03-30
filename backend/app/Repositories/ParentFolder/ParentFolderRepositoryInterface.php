<?php

namespace App\Repositories\ParentFolder;
use App\Http\Requests\ParentFolderRequest;
use App\Models\ParentFolder;

interface ParentFolderRepositoryInterface
{
    /**
     * インサート
     *
     * @param ParentFolderRequest $request
     * @return ParentFolder
     */
    public function storeParentFolder(ParentFolderRequest $request): ParentFolder;
}
