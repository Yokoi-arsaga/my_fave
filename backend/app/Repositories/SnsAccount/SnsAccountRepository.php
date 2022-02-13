<?php

namespace App\Repositories\SnsAccount;

use App\Http\Requests\AccountRequest;
use App\Models\SnsAccount;
use Illuminate\Support\Facades\Auth;

class SnsAccountRepository implements SnsAccountRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function storeAccount(AccountRequest $request): SnsAccount
    {
        return SnsAccount::create([
            'user_id' => Auth::id(),
            'media_id' => $request->getMediaId(),
            'account_url' => $request->getAccountUrl()
        ]);
    }
}
