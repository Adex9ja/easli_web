<?php

namespace App\Http\Controllers;

use App\Model\TableEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OthersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function bannerList(Request $request, $arg = null){
        $bannerList = $this->mproxy->getBannerList();
        $banner = $this->mproxy->getBannerById($arg);
        return view('banner_list', ['bannerList' => $bannerList, 'banner' => $banner ?? null]);
    }

    public function updateBanner(Request $request, $arg = null){
        return $this->mproxy->updateBanner($request->input(), $request->file('fileUpload'), $arg);
    }

    public function deactivateBanner(Request $request, $arg = null){
        return $this->mproxy->deActivateBanner($arg);

    }

    public function bannerAdd(){
        return view('banner_add');
    }

    public function saveNewBanner(Request $request){
        return $this->mproxy->saveNewBanner($request->input(), $request->file('imageUpload'));
    }

    public function faqList(Request $request, $arg = 0){
        $faqList = $this->mproxy->getFaqList(true);
        $faq = $this->mproxy->getFaqById($arg);
        return view('faq_list', ['faqList' => $faqList, 'faq' => $faq]);
    }

    public function deactivateFaq(Request $request, $arg = null){
        return $this->mproxy->deactivateFaq($arg);
    }

    public function updateFaq(Request $request, $arg = null){
        return $this->mproxy->updateFaq($request->input(), $arg);
    }

    public function addFaq(){
        return view('faq_add');
    }

    public function saveFaq(Request $request){
        return $this->mproxy->saveFaq($request->input());
    }

}
