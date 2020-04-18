<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2/8/2020
 * Time: 2:06 PM
 */

namespace App\Model;


use App\UserEntity;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class Repository
{
    private $table;
    private $general_push_topic;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->table = new TableEntity();
        $this->general_push_topic = "/topics/general";
    }

    public static function getAccountName($bank_code)
    {
        $bank = DB::selectOne("select * from bank_entity where bank_code = '$bank_code'");
        return $bank->bank_name;
    }

    private function getRef(){
        return Date("Ymdhis");
    }


    public function getAccountList(){
        return DB::select("SELECT P.*, B.bank_name FROM payment_account_entity as P INNER JOIN bank_entity as B on P.bank_code = B.bank_code");
    }

    public function getBannerList($onlyActive = true)
    {
        return $this->table->getItemList('banner_entity', 'banner_id', $onlyActive);
    }

    public function getBannerById($searchValue)
    {
        return $this->table->getSingleItem('banner_entity', 'banner_id', $searchValue);
    }

    public function saveNewBanner( $inputs, $file)
    {
        return $this->table->insertNewEntry('banner_entity', 'banner_id', $inputs , $file, $this->getRef());
    }

    public function getBankList()
    {
        return $this->table->getItemList('bank_entity', 'bank_code', false);
    }

    public function getBankAcctInfo($arg)
    {
        return $this->table->getSingleItem('payment_account_entity', 'acc_no', $arg);
    }

    public function savePaymentAcct($inputs)
    {
        return $this->table->insertNewEntry('payment_account_entity', 'acc_no', $inputs);
    }

    public function getDataBalanceCodeList($onlyActive = true)
    {
        return $this->table->getItemList('data_balance_entity', 'net_code', $onlyActive);
    }

    public function getDataCode($arg)
    {
        return $this->table->getSingleItem('data_balance_entity', 'net_code', $arg);
    }

    public function updateDataBalanceCode($input, $arg)
    {
        return $this->table->updateTable('data_balance_entity', 'net_code', $arg, $input );
    }

    public function updateBanner($inputs, $file,  $arg)
    {
        return $this->table->updateTable('banner_entity', 'banner_id', $arg, $inputs, $file, $this->getRef());
    }

    public function getNetworkList()
    {
        return $this->table->getItemList('network_entity', 'net_name');
    }

    public function saveNewDataBalCode($inputs)
    {
        return $this->table->insertNewEntry('data_balance_entity', 'net_code', $inputs);
    }

    public function deActivatePaymentAcct($arg)
    {
        return $this->table->deactivate('payment_account_entity', 'acc_no', $arg);
    }

    public function updatePaymentAcct($input, $arg)
    {
        return $this->table->updateTable('payment_account_entity', 'acc_no', $arg, $input);
    }

    public function deActivateBanner($arg)
    {
        return $this->table->deactivate('banner_entity', 'banner_id', $arg);
    }

    public function getChargesList()
    {
        return $this->table->getItemList('conversion_rate_entity', 'conversion_id', true);
    }

    public function getChargeRate($arg)
    {
        return $this->table->getSingleItem('conversion_rate_entity', 'conversion_id', $arg);
    }

    public function updateChargesRate($input, $arg)
    {
        return $this->table->updateTable('conversion_rate_entity', 'conversion_id', $arg, $input);
    }

    public function deactivateDataBal($arg)
    {
        return $this->table->deactivate('data_balance_entity', 'net_code', $arg);
    }

    public function deActivateChargeRate($arg)
    {
        return $this->table->deactivate('conversion_rate_entity', 'conversion_id', $arg);
    }

    public function saveNewChargeRate($input)
    {
        $input['conversion_id'] = $this->getRef();
        return $this->table->insertNewEntry('conversion_rate_entity', 'conversion_id', $input);
    }

    public function getUsersList()
    {
        return DB::select("SELECT * from user_entity as U inner join user_role_entity as UR on U.user_role = UR.user_role");
    }

    public function deactivateUser($arg)
    {
        return $this->table->deactivate('user_entity', 'email', $arg);
    }

    public function activateUser($arg)
    {
        return $this->table->activate('user_entity', 'email', $arg);
    }

    public function getUserRoles()
    {
        return $this->table->getItemList('user_role_entity', 'user_role');
    }

    public function deleteUserPrivilege($user_role)
    {
        DB::delete("delete from menu_privilege_entity where user_role = '$user_role'");
    }

    public function addUserPrivilege($inputs, $user_role)
    {
        foreach ($inputs as $input){
            $arr = ['user_role' => $user_role, 'link' => $input];
            $this->table = new TableEntity();
            $this->table->insertNewEntry('menu_privilege_entity', 'id', $arr, null, null, false);
        }
    }

    public function getPrivileges($user_role)
    {
        return DB::select("SELECT ML.title, ML.link, MP.link as privilege from menu_link_entity as ML LEFT join menu_privilege_entity as MP on (ML.link = MP.link and MP.user_role = '$user_role')");
    }

    public function getUserByEmail($email)
    {
        return $this->table->getSingleItem('user_entity', 'email', $email);
    }

    public function saveNewUser($input)
    {
        $input['password'] = Hash::make($input['password']);
        $input['active'] = 1;
        return $this->table->insertNewEntry('user_entity', 'email', $input);
    }

    public function updateUser($input, $args)
    {
        return $this->table->updateTable('user_entity', 'email', $args, $input);
    }

    public function getMonthlyTransactionGraphData()
    {
        return DB::select("SELECT year(A.transDate) as years, monthname(A.transDate) as months, count(A.transDate) as value from voucher_entity as A  GROUP by concat(year(A.transDate), monthname(A.transDate)) order by A.transDate desc LIMIT 12");
    }

    public function getDashBoardReportData()
    {
        return DB::selectOne("SELECT sum(approvalStatus) as approved, sum( case when approvalStatus = 0 then 1 else 0 end) as pending, (select count(*) from user_entity) as users, (select count(*) from sub_product_entity where active = 1) as products, (select count(*) from contact_us_entity) as feedbacks FROM `voucher_entity`");
    }

    public function validateUser($inputs)
    {
        $email = $inputs['email'];
        $password = Hash::make($inputs['password']);
        $user = new UserEntity();
        $user = $user->where('email', '=', $email)->first();
        return Hash::check($inputs['password'], $password) ? $user : null;
    }

    public function getTransactionList()
    {
        return DB::select('SELECT V.*, U.fullname, S.sub_name, (V.amount + ifnull(D.amount, 0)) as original_amount FROM voucher_entity as V LEFT join discount_entity as D on V.discount_code = D.discount_code INNER JOIN user_entity as U on V.email = U.email INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id order by V.transDate desc');
    }

    public function getTransApprovalStatusGraphData()
    {
        return DB::select("SELECT (case WHEN approvalStatus = 0 THEN 'Pending' else 'Approved' end) as label, count(approvalStatus) as value from voucher_entity GROUP by approvalStatus");
    }

    public function getTransTopSellingGraphData()
    {
        return DB::select("SELECT count(V.sub_prod_id) as value, S.sub_name as label from voucher_entity as V INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id group by V.sub_prod_id order by  count(V.sub_prod_id) desc limit 12");
    }

    public function getTransTopBuyerGraphData()
    {
        return DB::select("SELECT COUNT(V.email) as value, U.fullname as label from voucher_entity as V INNER join user_entity as U on V.email = U.email GROUP by V.email ORDER by  COUNT(V.email) desc LIMIT 12");
    }

    public function getTransPaymentChannelGraphData()
    {
        return DB::select("SELECT count(channel_name) as value, channel_name as label from voucher_entity GROUP by channel_name");
    }

    public function updateTransactionStatus($arg, $status)
    {
        DB::update("update voucher_entity set approvalStatus = $status where ref = '$arg'");
        $transaction = $this->getTransactionDetailById($arg);
        $user = $this->getUserByEmail($transaction->email);
        $tokens[] = $user->token;
        $this->sendPushNotification('01', json_encode($transaction), $tokens);
        $this->sendReceiptByMail($transaction, RequestStatus::getReqTitle($status). ' Transaction', array($transaction->email));
    }

    public function getPageInfo($user_role, $uri)
    {
        return DB::selectOne("SELECT ML.title, ML.menu_cat from menu_privilege_entity as MP INNER join menu_link_entity as ML on MP.link = ML.link where MP.user_role = '$user_role' and ML.link = '/$uri'");
    }

    public function getPrivilegeMenu($user_role)
    {
        return DB::select("select M.*, mce.cat_icon, mce.cat_link, mle.menu_cat, mle.title from menu_category_entity as mce INNER  join menu_link_entity as mle on mce.menu_cat = mle.menu_cat INNER join menu_item_entity as M on mle.link = M.link INNER JOIN menu_privilege_entity as mpe on M.link = mpe.link  where mce.active = 1 and M.active = 1 and mpe.user_role = '$user_role' GROUP by mle.title  order by  mce.order_id,  M.menu_order");
    }

    public function getUnreadMessages($limit)
    {
        return DB::select("SELECT U.fullname, C.message, C.created_at, C.contact_id from contact_us_entity as C INNER join user_entity as U on C.email = U.email where status = 0 ORDER by C.created_at desc LIMIT $limit");
    }

    public function getPendingTransactions($limit)
    {
        return DB::select("SELECT U.fullname, S.sub_name, V.amount, V.transDate, V.ref from voucher_entity as V INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id INNER join user_entity as U on V.email = U.email WHERE V.approvalStatus = 0 order by V.transDate desc LIMIT $limit");
    }

    public function getTransactionDetailById($arg)
    {
        return DB::selectOne("SELECT U.fullname, V.*, S.sub_name, P.product_name, P.product_icon, P.product_description from voucher_entity as V INNER join user_entity as U on V.email = U.email INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id INNER join product_entity as P on S.product_id = P.product_id where V.ref = '$arg'");
    }

    public function getMessageList()
    {
        return DB::select("SELECT C.*, U.fullname FROM contact_us_entity as C INNER join user_entity as U ON C.email = U.email ORDER by created_at DESC");
    }

    public function getMessageDetail($arg)
    {
        DB::update("update contact_us_entity set status = 1 where contact_id = '$arg'");
        return DB::selectOne("SELECT C.*, U.fullname, U.phoneno, U.user_role FROM contact_us_entity as C INNER join user_entity as U ON C.email = U.email where C.contact_id = '$arg'");
    }

    public function getProductTransHistory($arg)
    {
            return DB::select("SELECT V.*, S.sub_name, P.product_name, P.product_icon, P.product_description, (V.amount + ifnull(D.amount, 0)) as original_amount FROM voucher_entity as V LEFT join discount_entity as D on V.discount_code = D.discount_code  INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id INNER join product_entity as P on S.product_id = P.product_id where V.email = '$arg' order by V.transDate desc");
    }

    public function updateUserToken($inputs)
    {
        $email = $inputs['email'];
        $token = $inputs['token'];
        $this->table->updateTable('user_entity', 'email', $email, ["token" => $token]);
    }

    public function sendContactUsMsg($inputs)
    {
        $this->table->insertNewEntry('contact_us_entity', 'contact_id', $inputs, null, null, false);
    }

    public function postTransaction($inputs)
    {
        $inputs['transDate'] = date("Y-m-d H:i:s");
        unset($inputs['product_name']);
        unset($inputs['sub_name']);
        unset($inputs['product_description']);
        $this->table->insertNewEntry('voucher_entity', 'ref', $inputs, null, null, false);
    }

    public function getProductsByServiceId($DATA_SERVICE)
    {
        $where = [['service_id', '=', $DATA_SERVICE]];
        return $this->table->getItemListWithWhere('product_entity', 'product_id', $where);
    }

    public function getSubProductsByProdId($arg, $onlyActive = false)
    {
        if($onlyActive)
            return DB::select("SELECT S.*, C.per_charges from sub_product_entity as S INNER join conversion_rate_entity as C on S.conversion_id = C.conversion_id where S.product_id = '$arg' and S.active = 1");
        else
            return DB::select("SELECT S.*, C.per_charges from sub_product_entity as S INNER join conversion_rate_entity as C on S.conversion_id = C.conversion_id where S.product_id = '$arg'");
    }

    public function getSubProductDetail($arg2)
    {
        return $this->table->getSingleItem('sub_product_entity', 'sub_prod_id',  $arg2);
    }

    public function getWalletTransHistory($arg)
    {
        $where = [['email' , '=', $arg]];
        return $this->table->getItemListWithWhere('wallet_entity', 'id', $where);
    }

    public function postWalletTransaction($input)
    {
        $this->table->insertNewEntry('wallet_entity', 'id', $input, null, null, false);
    }

    public function getWalletTransByPayRef($payment_ref)
    {
        $where = [['payment_ref' , '=', $payment_ref]];
        return $this->table->getSingleItemWithWhere('wallet_entity', 'id', $where);
    }

    public function updateUserImage($file, $email)
    {
        $this->table->updateTable('user_entity', 'email', $email, [], $file, $this->getRef());
    }

    public function sendPushNotification(string $title, string $message, array $registrationIDs = null, string $topic = null)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $notification = array('title' =>$title , 'body' =>  $message, 'sound' => 'default', 'badge' => '1');
        $arrayToSend = array('to' => $topic, 'registration_ids' => $registrationIDs, 'data' => $notification, 'priority'=>'high');
        $client = new Client();
        $result = $client->post( $url, [
            'json'    =>  $arrayToSend,
            'headers' => [  'Authorization' => 'key='.env('FCM_SERVER_KEY'),  'Content-Type'  => 'application/json' ],
        ] );
        return json_decode( $result->getBody(), true );
    }

    public function sendPostedTransNotifications($transaction)
    {
        $tokens = $this->getAdminTokens();
        $emails = $this->getAdminEmails();
        $emails[] = $transaction->email;
        $this->sendPushNotification("03", json_encode($transaction), $tokens);
        $this->sendReceiptByMail($transaction, "Transaction Receipt", $emails);
    }

    private function sendReceiptByMail($transaction, $subject = 'Transaction Receipt', array $emails = [])
    {
        Mail::send('emails.transaction_receipt', ['trans' => $transaction], function ($message) use ($emails, $subject) {
            $message->to($emails)->subject($subject);
            $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
        });
    }

    private function getAdminEmails()
    {
        $registrationIDs = DB::select("SELECT email from user_entity where userRole = 'Admin' and token <> '' ");
        $arr = [];
        foreach ($registrationIDs as $reg)
            $arr[] = $reg->email;
        return $arr;
    }

    private function getAdminTokens()
    {
        $registrationIDs = DB::select("SELECT token from user_entity where userRole = 'Admin' and token <> '' ");
        $arr = [];
        foreach ($registrationIDs as $reg)
            $arr[] = $reg->token;
        return $arr;
    }

    public function sendWalletTransMail($transaction)
    {
        $fullname = $this->getUserByEmail($transaction['email'])['fullname'];
        Mail::send('emails.fund_wallet_receipt', ['trans' => $transaction, 'fullname' => $fullname], function ($message) use ($fullname, $transaction) {
            $message->to($transaction['email'], $fullname)->subject('Fund Wallet');
            $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
        });
    }

    public function getPushNotifications()
    {
        return $this->table->getItemList('notification_entity');
    }

    public function publishNewPushNotification($inputs)
    {
        $inputs['email'] = Auth::user()->email;
        $this->sendPushNotification("04", json_encode($inputs), null, $this->general_push_topic);
        return $this->table->insertNewEntry('notification_entity', 'id', $inputs);
    }

    public function resendPushNotification($arg)
    {
        $notification = $this->table->getSingleItem('notification_entity', 'id', $arg);
        $this->sendPushNotification("04", json_encode($notification), null, $this->general_push_topic);
    }

    public function updateProfileDocument($file, $email, $doc_type)
    {
        $this->table->updateTable('user_entity', 'email', $email, ['doc_type' => $doc_type], $file, $this->getRef(), 'doc_url');
    }

    public function verifyBvn($inputs)
    {
        $phone = $inputs['bvn_phone'];
        $dob  =  $inputs['bvn_dob'];
        $bvn_number =  $inputs['bvn_number'];
        $bvn = $this->table->getSingleItemWithWhere('bvn_entity', 'bvn_number', [['bvn_number', '=', $bvn_number]]);
        if($bvn != null)
            return $bvn->bvn_phone == $phone && $bvn->bvn_dob == $dob;
        else{
            $url = "https://api.paystack.co/bank/resolve_bvn/".$bvn_number;
            $client = new Client();
            $result = $client->get( $url, ['headers' => [ 'Content-Type' => 'application/json', 'Authorization' => 'Bearer '.env('PAYSTACK_SEC_KEY')]]);
            $bvn = json_decode( $result->getBody());
            if($bvn->status){
                $bvnInput['bvn_phone'] = $bvn->data->mobile;
                $bvnInput['bvn_dob'] = $bvn->data->formatted_dob;
                $bvnInput['bvn_number'] = $bvn_number;
                $this->table->insertNewEntry('bvn_entity', 'bvn_number', $bvnInput);
                return $bvnInput['bvn_phone'] == $phone && $bvnInput['bvn_dob'] == $dob;
            }
            return false;
        }
    }

    public function updateProfileBvn($inputs)
    {
        $this->table->updateTable('user_entity', 'email', $inputs['email'], $inputs);
    }

    public function sendBulkSms($input)
    {
        $response = $this->sendSMS( $input['message'], $input['phone']);
        $input['response'] = $response;
        $input['email'] = Auth::user()->email;
        $this->table->insertNewEntry('sms_entity', 'id', $input);
        return $response;
    }

    private function sendSMS($message, $phone)
    {
        $url = "http://www.daftsms.com/sms_api.php?username=". env('SMS_USERNAME')."&password=". env('SMS_PASSWORD'). "&sender=AirtimeData&dest=". $phone ."&msg=".$message;
        $client = new Client();
        $request = $client->get($url);
        return $request->getBody()->getContents();
    }

    public function getBulkSmsBalance()
    {
        $url = "http://www.daftsms.com/sms_api.php?meg_report=balance&username=". env('SMS_USERNAME') ."&password=". env('SMS_PASSWORD');
        $client = new Client();
        $request = $client->get($url);
        return $request->getBody()->getContents();
    }

    public function getBulkSmsStat()
    {
        return DB::selectOne("SELECT (SELECT COUNT(response) from sms_entity where response = '146') as success, (SELECT COUNT(response) from sms_entity where response <> '146') as fail");
    }

    public function getFaqList($onlyActive = false)
    {
        return $this->table->getItemList('faq_entity', 'id', $onlyActive);
    }

    public function getFaqById($arg)
    {
        return $this->table->getSingleItem('faq_entity', 'id', $arg);
    }

    public function deactivateFaq($arg)
    {
        return $this->table->deactivate('faq_entity', 'id', $arg);
    }

    public function updateFaq($inputs, $arg)
    {
        return $this->table->updateTable('faq_entity', 'id', $arg, $inputs);
    }

    public function saveFaq($input)
    {
        return $this->table->insertNewEntry('faq_entity', 'id', $input);
    }

    public function getReferralByEmail($arg)
    {
        return $this->table->getSingleItem('referral_entity', 'email', $arg);
    }

    public function verifyPaymentReference($reference)
    {
        $url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);
        $client = new Client();
        $result = $client->get( $url, ['headers' => [ 'Content-Type' => 'application/json', 'Authorization' => 'Bearer '.env('PAYSTACK_SEC_KEY')]]);
        return json_decode( $result->getBody());
    }

    public function saveReferralCode($inputs)
    {
        return $this->table->insertNewEntry('referral_entity', 'ref_code', $inputs);
    }

    public function genReferralCode($email, $reference)
    {
        $len = strlen($reference);
        return substr($email, 0, 3). substr($reference, ($len - 4), ($len -1));
    }

    public function getPromoCodes()
    {
        return $this->table->getItemList('discount_entity', 'discount_code', true);
    }

    public function genPromoCode($len)
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < $len; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
        }
        return $res;
    }

    public function savePromoCode($input)
    {
        return $this->table->insertNewEntry('discount_entity', 'discount_code', $input);
    }

    public function getPromoCodeByCode($arg)
    {
        return $this->table->getSingleItem('discount_entity', 'discount_code', $arg);
    }

    public function deactivatePromoCode($arg)
    {
        return $this->table->deactivate('discount_entity', 'discount_code', $arg);
    }

    public function updatePromoCode($input, $arg)
    {
        return $this->table->updateTable('discount_entity', 'discount_code', $arg, $input);
    }

    public function getReferralList()
    {
        return $this->table->getItemList('referral_entity', 'ref_code', true);
    }

    public function getReferralEarnings($arg)
    {
        return DB::select("SELECT W.amount, W.narration, W.payment_ref, U.fullname, W.created_at from wallet_entity as W INNER join referral_entity as R on W.payment_ref = R.reference INNER JOIN user_entity as U on R.email = U.email where U.ref_code = '$arg'");
    }

    public function getPendingPayout($email)
    {
        $where = [['email', '=', $email], ['status', '=', 0]];
        return $this->table->getSingleItemWithWhere('payout_entity', 'payout_id', $where);
    }

    public function getWalletBalance($email)
    {
        return DB::select('select  GetWalletBalance(?)', [$email]);
    }

    public function savePayoutRequest($inputs)
    {
        $inputs['created_at'] = date("Y-m-d H:i:s");
        $this->table->insertNewEntry('payout_entity', 'payout_id', $inputs);
    }

    public function getPayoutRequestById($payout_id)
    {
        return $this->table->getSingleItemWithWhere('payout_entity', 'payout_id', [['payout_id', '=', $payout_id]]);
    }

    public function sendPayoutRequestNotification($payoutRequest, $subject = 'Payout Request')
    {
        $emails = $this->getAdminEmails();
        $tokens = $this->getAdminTokens();
        $user = $this->getUserByEmail($payoutRequest['email']);
        $emails[] = $user['email'];
        $tokens[] = $user['token'];
        $this->sendPushNotification("05", json_encode($payoutRequest), $tokens);
        $this->sendPayoutRequestMail($payoutRequest, $user, $subject, $emails);
    }

    private function sendPayoutRequestMail($payoutRequest, $user, $subject, array $emails = [])
    {
        Mail::send('emails.withdraw_wallet_receipt', ['payoutRequest' => $payoutRequest, 'user' => $user], function ($message) use ($emails, $subject) {
            $message->to($emails)->subject($subject);
            $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
        });
    }

    public function getPayoutRequests()
    {
        return $this->table->getItemList('payout_entity', 'payout_id', false);
    }

    public function updatePayout($arg, $value)
    {
        $pendingRequest = $this->getPayoutRequestById($arg);
        $pendingRequest['status'] = $value;
        $this->sendPayoutRequestNotification($pendingRequest, RequestStatus::getReqTitle($value). ' Payout Request');
        return $this->table->updateTable('payout_entity', 'payout_id', $arg, ['status' => $value]);
    }

    public function getWalletTrans()
    {
        return $this->table->getItemList('wallet_entity', 'wallet_id', false);
    }

    public function saveContactUs($inputs)
    {
        $this->table->insertNewEntry('contact_us_entity', 'contact_id', $inputs);
    }


}
