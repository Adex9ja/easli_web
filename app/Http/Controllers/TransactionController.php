<?php

namespace App\Http\Controllers;

use App\Model\RequestStatus;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }
    public function transactionList(){
        $data = $this->mproxy->getTransactionList();
        return view('transaction_list', ['data' => $data]);
    }
    public function approveTransaction(Request $request, $arg){
        $this->mproxy->updateTransactionStatus($arg, RequestStatus::Approved);
        return back()->with('msg', $this->prepareMessage(true, "Transaction ". RequestStatus::getReqTitle($arg)));
    }
    public function declineTransaction(Request $request, $arg){
        $this->mproxy->updateTransactionStatus($arg, RequestStatus::Declined);
        return back()->with('msg', $this->prepareMessage(true, "Transaction ". RequestStatus::getReqTitle($arg)));
    }
    public function transactionStat(){
        $transTrend = $this->mproxy->getMonthlyTransactionGraphData();
        $approvalStatus = $this->mproxy->getTransApprovalStatusGraphData();
        $topSelling = $this->mproxy->getTransTopSellingGraphData();
        $topBuyers = $this->mproxy->getTransTopBuyerGraphData();
        $channels = $this->mproxy->getTransPaymentChannelGraphData();
        return view('transaction_stat', ['transTrend' => $transTrend, 'approvalStatus' => $approvalStatus, 'topSelling' => $topSelling, 'topBuyers' => $topBuyers, 'channels' => $channels]);
    }
    public function viewTransaction(Request $request, $arg){
        $transaction = $this->mproxy->getTransactionDetailById($arg);
        return view('transaction_view', ['transaction' => $transaction]);
    }
    public function userHistory(Request $request, $arg){
        $transList = $this->mproxy->getProductTransHistory(base64_decode($arg));
        return view('transaction_history', ['transList' => $transList]);
    }
}
