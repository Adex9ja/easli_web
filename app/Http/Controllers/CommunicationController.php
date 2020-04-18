<?php

namespace App\Http\Controllers;

use App\Model\TableEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function messageList(Request $request, $arg = null){
        if($arg != null)
            $messageDetail = $this->mproxy->getMessageDetail($arg);
        $messages = $this->mproxy->getMessageList();
        return view('message_list', ['messages' => $messages, 'messageDetail' => $messageDetail ?? null]);
    }

    public function pushNotifications(Request $request, $arg = null){
        $pushList = $this->mproxy->getPushNotifications();
        if($arg != null){
            $this->mproxy->resendPushNotification($arg);
            return view('push_notification_list', ['pushList' => $pushList, 'msg' => $this->prepareMessage(true,'Message Sent Successfully!')]);
        }
        return view('push_notification_list', ['pushList' => $pushList]);
    }

    public function newPushNotifications(){
        return view('push_notification_add');
    }

    public function sendPushNotifications(Request $request){
        return $this->mproxy->publishNewPushNotification($request->input());
    }

    public function bulkSms(){
        $userList = $this->mproxy->getUsersList();
        $balance = $this->mproxy->getBulkSmsBalance();
        $smsStat = $this->mproxy->getBulkSmsStat();
        return view('bulk_sms', ['userList' => $userList, 'balance' => $balance, 'smsStat' => $smsStat]);
    }

    public function sendBulkSms(Request $request){
        $res = $this->mproxy->sendBulkSms($request->input());
        $isSuccessful = ($res == 146);
        return back()->with('msg', $this->prepareMessage($isSuccessful, $isSuccessful ? "Message Sent Successfully" : "Message could not be sent...Please check your balance" ));
    }


}
