<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\SnsAccount;
use App\Modules\ApplicationLogger;
use App\Repositories\SnsAccount\SnsAccountRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * SNSアカウントに関するコントローラー
 *
 */
class SnsAccountController extends Controller
{
    private SnsAccountRepositoryInterface $snsAccountRepository;

    /**
     * @param SnsAccountRepositoryInterface $snsAccountRepository
     */
    public function __construct(SnsAccountRepositoryInterface $snsAccountRepository)
    {
        $this->snsAccountRepository = $snsAccountRepository;
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * SNSアカウント登録ページ
     *
     * @return Application|Factory|View
     */
    public function edit()
    {
        return view('Account.edit');
    }


    /**
     * SNSアカウント登録
     *
     * @param AccountRequest $request
     * @return SnsAccount
     */
    public function store(AccountRequest $request)
    {
        $logger = new ApplicationLogger(__METHOD__);

        $logger->write('SNSアカウント情報の登録処理を開始');
        $response = $this->snsAccountRepository->storeAccount($request);

        $logger->success();
        return $response;
    }
}
