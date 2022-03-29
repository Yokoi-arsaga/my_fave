<?php

namespace App\Repositories\ParentFolder;

use App\Models\ParentFolder;

class ParentFolderRepository implements ParentFolderRepositoryInterface
{
    public function storeParentFolder(ParentFolderRequest $request): ParentFolder
    {
        return ParentFolder::create([
            'folder_name' =>
        ])
    }
}
