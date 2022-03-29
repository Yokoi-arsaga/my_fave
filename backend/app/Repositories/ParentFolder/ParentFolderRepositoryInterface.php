<?php

namespace App\Repositories\ParentFolder;

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
