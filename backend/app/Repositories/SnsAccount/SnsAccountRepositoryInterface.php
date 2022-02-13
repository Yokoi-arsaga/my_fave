<?php

namespace App\Repositories\SnsAccount;

use App\Http\Requests\AccountRequest;
use App\Models\SnsAccount;

/**
 * interface SnsAccountRepository SNSアカウントの処理
 * @package App\Repositories\SnsAccountRepository
 */
interface SnsAccountRepositoryInterface
{
    /**
     * SNSアカウント情報のインサート
     *
     * @param AccountRequest $request
     * @return SnsAccount
     */
    public function storeAccount(AccountRequest $request): SnsAccount;
}
