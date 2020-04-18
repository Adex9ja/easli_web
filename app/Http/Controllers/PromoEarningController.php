<?php

namespace App\Http\Controllers;

use App\Model\Repository;
use App\Model\TableEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoEarningController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function promoCodeList(Request $request, $arg = null){
        $promoCodeList = $this->mproxy->getPromoCodes();
        $promoCode = $this->mproxy->getPromoCodeByCode($arg);
        return view('promo_code_list', ['promoCodeList' => $promoCodeList, 'promoCode' => $promoCode]);
    }
    public function addPromoCode (){
        $code = $this->mproxy->genPromoCode(6);
        return view('promo_code_add', ['code' => $code]);
    }
    public function savePromoCode(Request $request){
        return $this->mproxy->savePromoCode($request->input());
    }
    public function deactivatePromoCode(Request $request, $arg){
        return $this->mproxy->deactivatePromoCode($arg);
    }
    public function updatePromoCode (Request $request, $arg){
        return $this->mproxy->updatePromoCode($request->input(), $arg);
    }

    public function referralList(Request $request, $arg =null){
        $referralList = $this->mproxy->getReferralList();
        $earningList = $this->mproxy->getReferralEarnings($arg);
        return view('referral_list', ['referralList' => $referralList, 'earningList' => $earningList]);
    }

}
