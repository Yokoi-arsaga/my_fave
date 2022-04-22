<?php

namespace App\Repositories\GrandchildFolder;

use App\Http\Requests\GrandchildFolderRequest;
use App\Models\GrandchildFolder;

interface GrandchildFolderRepositoryInterface
{
    /**
     * インサート
     *
     * @param GrandchildFolderRequest $request
     * @return GrandchildFolder
     */
    public function storeGrandchildFolder(GrandchildFolderRequest $request): GrandchildFolder;
}
