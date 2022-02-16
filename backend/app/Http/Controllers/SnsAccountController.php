<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Repositories\SnsAccount\SnsAccountRepositoryInterface;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(){
        return view('Account.edit');
    }


    public function store(AccountRequest $request)
    {
        return $this->snsAccountRepository->storeAccount($request);
    }
}
