<?php

namespace App\Http\Controllers;

use App\Model\TableEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    private $DATA_SERVICE = 'DATA_PUR_01';
    private $RECHARGE_SERVICE = 'RE_SA_01';
    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function dataList(Request $request, $arg = null, $arg2 = null){

        $data = $this->mproxy->getProductsByServiceId($this->DATA_SERVICE);
        if($arg != null)
            $subProdList = $this->mproxy->getSubProductsByProdId($arg, true);
        if($arg2 != null)
            $subProdDetail = $this->mproxy->getSubProductDetail($arg2);

        return view('data_list', ['dataList' => $data, 'subProdList' => $subProdList ?? null, 'subProdDetail' => $subProdDetail ?? null]);
    }

    public function deleteSubProduct(Request $request, $arg){
        DB::update("update sub_product_entity set active = 0 where sub_prod_id = '$arg'");
        return back()->with('msg', parent::prepareMessage(true, "De-activated Successfully!"));
    }

    public function updateSubProduct(Request $request, $arg = null, $arg2 = null){
        $table = new TableEntity();
        $table->setTablePrimary('sub_product_entity', 'sub_prod_id');
        $inputs = $request->input();
        unset($inputs['_token']);
        $table->where('sub_prod_id', '=', $arg2)->update($inputs);
        return back()->with('msg', parent::prepareMessage(true, "Updated Successfully!"));
    }

    public function rechargeList(Request $request, $arg = null, $arg2 = null){
        $table = new TableEntity();
        $table->setTablePrimary('product_entity', 'product_id');
        $data = $table->where([['service_id', '=', $this->RECHARGE_SERVICE]])->get();
        if($arg != null){
            $table->setTablePrimary('sub_product_entity', 'sub_prod_id');
            $subProdList = $table->where([['product_id', '=', $arg], ['active', '=', 1]])->get();
        }

        if($arg2 != null){
            $subProdDetail = $table->where([['sub_prod_id', '=', $arg2],  ['active', '=', 1]])->first();
        }
        return view('data_list', ['dataList' => $data, 'subProdList' => $subProdList ?? null, 'subProdDetail' => $subProdDetail ?? null]);
    }
}
