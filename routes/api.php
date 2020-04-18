<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get("/test/receipt", 'MobileApiController@receiptTest');

//User Profile
Route::post("/user/login", 'MobileApiController@validateLogin');
Route::post("/user/register", 'MobileApiController@registerUser');
Route::post("/user/profile/update/token", 'MobileApiController@updateUserToken');
Route::put("/user/profile/update", 'MobileApiController@updateProfile');
Route::post("/user/profile/update/image", 'MobileApiController@updateProfileImage');
Route::post("/user/profile/identity/document", 'MobileApiController@updateProfileDocument');
Route::put("/user/profile/identity/bvn", 'MobileApiController@updateProfileBvn');
Route::get("/user/referral/code/{email}", 'MobileApiController@referralCode');
Route::post("/user/maintenance/fee/pay", 'MobileApiController@payMaintenance');


//Others
Route::post("/contact/message/add", 'MobileApiController@sendContactUsMsg');
Route::get("/bank/list", 'MobileApiController@bankList');
Route::get("/product/list/{service_id}", 'MobileApiController@productList');
Route::post("/transaction/post/new", 'MobileApiController@postTransaction');
Route::get("/banners/list", 'MobileApiController@getBannerList');
Route::get("/data/balance/code/list", 'MobileApiController@dataBalList');
Route::get("/product/sub-product/list/{product_id}", 'MobileApiController@subProductList');
Route::get("/product/transaction/list/{email}", 'MobileApiController@productTransactionList');
Route::get("/payment/account/list", 'MobileApiController@paymentAcctList');
Route::put("/product/transaction/request/cancel/{ref}", 'MobileApiController@cancelTransaction');
Route::get("/faq/list", 'MobileApiController@faqList');


//Wallet
Route::get("/wallet/transaction/list/{email}", 'MobileApiController@walletTransList');
Route::post("/wallet/fund/account", 'MobileApiController@postWalletTransaction');
Route::post("/wallet/withdrawal/request", 'MobileApiController@requestPayout');






Route::get('/error/denied', 'MobileApiErrorController@permissionDenied');

