<?php

namespace App\Http\Controllers;

use App\Model\JsonResponse;
use App\Model\RequestStatus;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MobileApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['mobile_auth']);
        parent::__construct();
    }
    public function validateLogin(Request $request)
    {
        $user = $this->mproxy->validateUser($request->input());
        $code = $user == null ? "-01" : "00";
        $msg = $user == null ? "Invalid Username/Password!" : "Validation Successful";
        return json_encode(new JsonResponse($code, $msg, $user));
    }
    public function registerUser(Request $request)
    {
        $inputs = $request->input();
        $user = $this->mproxy->getUserByEmail($inputs['email']);
        if($user == null){
            $this->mproxy->saveNewUser($inputs);
            $user = $this->mproxy->getUserByEmail($inputs['email']);
            $code = $user == null ? "-01" : "00";
            $msg = $user == null ? "Phone Number Already Exists" : "Registration Successful";
            return json_encode(new JsonResponse($code, $msg, $user));
        }
        else
            return json_encode(new JsonResponse("-01", "Email Already Exists", null));

    }
    public function updateUserToken(Request $request)
    {
        $inputs = $request->input();
        $this->mproxy->updateUserToken($inputs);
        $user = $this->mproxy->getUserByEmail($inputs['email']);
        $code = $user == null ? "-01" : "00";
        $msg = $user == null ? "User does not exist" : "Profile Details";
        return json_encode(new JsonResponse($code, $msg, $user));
    }
    public function updateProfile (Request $request){
        $inputs = $request->input();
        $this->mproxy->updateUser($inputs, $request->input('email'));
        return json_encode(new JsonResponse("00", "Profile Updated Successfully!", $inputs));
    }
    public function updateProfileImage (Request $request){
        $email = $request->input('email');
        $this->mproxy->updateUserImage($request->file('fileToUpload'), $email );
        $user = $this->mproxy->getUserByEmail($email);
        return json_encode(new JsonResponse("00", "Uploaded Successfully!", $user));
    }
    public function updateProfileDocument (Request $request){
        $email = $request->input('email');
        $doc_type = $request->input('doc_type');
        $this->mproxy->updateProfileDocument($request->file('fileToUpload'), $email, $doc_type );
        $user = $this->mproxy->getUserByEmail($email);
        return json_encode(new JsonResponse("00", "Uploaded Successfully!", $user));
    }
    public function updateProfileBvn (Request $request){
        $inputs = $request->input();
        if($this->mproxy->verifyBvn($inputs)) {
            $this->mproxy->updateProfileBvn($inputs);
            $user = $this->mproxy->getUserByEmail($inputs['email']);
            return json_encode(new JsonResponse("00", "Uploaded Successfully!", $user));
        }
        else
            return json_encode(new JsonResponse("-01", "BVN Verification Failed", null));

    }
    public function referralCode(Request $request, $arg = null){
        $referral = $this->mproxy->getReferralByEmail($arg);
        return json_encode(new JsonResponse("00", null, $referral));
    }
    public function payMaintenance(Request $request){
        $inputs = $request->input();
        $tranx = $this->mproxy->verifyPaymentReference($inputs['reference']);
        if(isset($tranx->status) && $tranx->status && $tranx->data->status == 'success'){
            $inputs['amount'] = $tranx->data->amount / 100;
            $inputs['ref_code'] = $this->mproxy->genReferralCode($inputs['email'], $inputs['reference']);
            $this->mproxy->saveReferralCode($inputs);
            $referral = $this->mproxy->getReferralByEmail($inputs['email']);
            $code = $referral == null ? "-01" : "00";
            $msg = $referral == null ? "Error Occurs" : "Posted Successfully!";
        }
        else{
            $code = "-01";
            $msg = "Invalid Transaction!";
        }
        return json_encode(new JsonResponse($code, $msg, $referral ?? null));
    }
    public function sendContactUsMsg (Request $request){
        $this->mproxy->sendContactUsMsg($request->input());
        return json_encode(new JsonResponse("00", "Message Submitted!"));
    }
    public function bankList(){
        $bankList = $this->mproxy->getBankList();
        return json_encode(new JsonResponse("00", null, $bankList));
    }
    public function postTransaction(Request $request){
        $inputs = $request->input();
        $this->mproxy->postTransaction($inputs);
        $transaction = $this->mproxy->getTransactionDetailById($inputs['ref']);
        if($transaction != null){
            $this->mproxy->sendPostedTransNotifications($transaction);
            return json_encode(new JsonResponse("00", "Posted Successfully!", $transaction));
        }
        else
            return json_encode(new JsonResponse("-01", "Error Occurs!", $transaction));

    }
    public function postWalletTransaction(Request $request){
        $inputs = $request->input();
        $tranx = $this->mproxy->verifyPaymentReference($inputs['payment_ref']);
        if(isset($tranx->status) && $tranx->status && $tranx->data->status == 'success'){
            $inputs['amount'] = $tranx->data->amount / 100;
            $this->mproxy->postWalletTransaction($inputs);
            $transaction = $this->mproxy->getWalletTransByPayRef($inputs['payment_ref']);
            $this->mproxy->sendWalletTransMail($transaction);
            $code = $transaction == null ? "-01" : "00";
            $msg = $transaction == null ? "Error Occurs" : "Posted Successfully!";
        }
        else{
            $code = "-01";
            $msg = "Invalid Transaction!";
        }
        return json_encode(new JsonResponse($code, $msg, $transaction ?? null));
    }
    public function productList(Request $request, $arg){
        $productList = $this->mproxy->getProductsByServiceId($arg);
        return json_encode(new JsonResponse("00", null, $productList));
    }
    public function getBannerList(){
        $bannersList = $this->mproxy->getBannerList(false);
        return json_encode(new JsonResponse("00", null, $bannersList));
    }
    public function walletTransList(Request $request, $arg){
        $walletList = $this->mproxy->getWalletTransHistory($arg);
        return json_encode(new JsonResponse("00", null, $walletList));
    }
    public function dataBalList(){
        $dataBalCodeList = $this->mproxy->getDataBalanceCodeList(false);
        return json_encode(new JsonResponse("00", null, $dataBalCodeList));
    }
    public function subProductList(Request $request, $arg){
        $subProdList = $this->mproxy->getSubProductsByProdId($arg);
        return json_encode(new JsonResponse("00", null, $subProdList));
    }
    public function productTransactionList(Request $request, $arg){
        $transList = $this->mproxy->getProductTransHistory($arg);
        return json_encode(new JsonResponse("00", null, $transList));
    }
    public function paymentAcctList(){
        $acctList = $this->mproxy->getAccountList();
        return json_encode(new JsonResponse("00", null, $acctList));
    }
    public function cancelTransaction(Request $request, $arg){
        $this->mproxy->updateTransactionStatus($arg, RequestStatus::Cancelled);
        $trans = $this->mproxy->getTransactionDetailById($arg);
        return json_encode(new JsonResponse("00", "Cancelled Successfully!", $trans));
    }
    public function faqList(){
        $faqList = $this->mproxy->getFaqList();
        return json_encode(new JsonResponse("00", null, $faqList));
    }
    public function requestPayout(Request $request){
        $inputs = $request->input();
        $pendingPayout = $this->mproxy->getPendingPayout($inputs['email']);
        if($pendingPayout == null){
            $accountBal = $this->mproxy->getWalletBalance($inputs['email']);
            if($accountBal > $inputs['amount']){
                $this->mproxy->savePayoutRequest($inputs);
                $payoutRequest = $this->mproxy->getPayoutRequestById($inputs['payout_id']);
                if($payoutRequest != null){
                    $this->mproxy->sendPayoutRequestNotification($payoutRequest);
                    return json_encode(new JsonResponse("00", "Request Successfully!", $payoutRequest));
                }
                else
                    return json_encode(new JsonResponse("-01", "payout Request Fail...Try Again!", null));
            }
            else
                return json_encode(new JsonResponse('-01', 'Insufficient Balance', null));
        }
        else
            return json_encode(new JsonResponse('-01', 'You have a pending payout request', null));
    }
    public function receiptTest(){
        $trans = $this->mproxy->getWalletTransByPayRef('20200214075305610');
        $user = $this->mproxy->getUserByEmail($trans->email);
        return view('emails.fund_wallet_receipt', ['trans' => $trans, 'fullname' => $user->fullname]);
//       $res = $this->mproxy->verifyBvn(['bvn_number'=> '22181301057', 'bvn_phone' => '081667672', 'bvn_dob' => '']);
//       var_dump($res);
    }
}
