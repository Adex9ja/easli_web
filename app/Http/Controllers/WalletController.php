<?php

namespace App\Http\Controllers;

use App\Model\Repository;
use App\Model\TableEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function payoutList(){
        $payoutList = $this->mproxy->getPayoutRequests();
        return view('wallet_payout_list', ['payoutList' => $payoutList]);
    }

    public function approvePayout(Request $request, $arg){
        return $this->mproxy->updatePayout($arg, 1);
    }

    public function declinePayout(Request $request, $arg){
        return $this->mproxy->updatePayout($arg, -1);
    }

    public function walletTransactions(){
        $walletTransList = $this->mproxy->getWalletTrans();
        return view('wallet_trans_list', ['walletTransList' => $walletTransList]);
    }
}
