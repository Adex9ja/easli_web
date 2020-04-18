<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ 'as' => 'login', 'uses' => 'UserController@index']);
Route::post('/', 'UserController@validateLogin');
Route::get('/dashboard', 'UserController@dashboard');
Route::get('/logout', [ 'as' => 'logout', 'uses' => 'UserController@logout']);
Route::get('/profile', 'UserController@profile');
Route::post('/profile', 'UserController@updateProfile');
Route::get('/error/denied', 'ErrorController@permissionDenied');


//Users Management
Route::get('/users/list', 'UserController@userList');
Route::get('/users/add', 'UserController@addUser');
Route::post('/users/add', 'UserController@saveUser');
Route::get('/users/update/{user_role}', 'UserController@viewUser');
Route::post('/users/update/{user_role}', 'UserController@saveUser');
Route::get('/users/deactivate/{email}', 'UserController@deactivateUser');
Route::get('/users/activate/{email}', 'UserController@activateUser');
Route::get('/users/privileges', 'UserController@userRoles');
Route::get('/users/privileges/{user_role}', 'UserController@userRolesPages');
Route::post('/users/privileges/{user_role}', 'UserController@userRolesPages');
Route::get('/users/list/detail/{email}', 'UserController@userDetail');

//Transactions
Route::get('/transaction/list', 'TransactionController@transactionList');
Route::get('/transaction/approve/{ref}', 'TransactionController@approveTransaction');
Route::get('/transaction/decline/{ref}', 'TransactionController@declineTransaction');
Route::get('/transaction/stat', 'TransactionController@transactionStat');
Route::get('/transaction/list/view/{ref}', 'TransactionController@viewTransaction');
Route::get('/transaction/history/{email}', 'TransactionController@userHistory');

//Product
Route::get('/product/data/list', 'ProductController@dataList');
Route::get('/product/data/list/{product_id}', 'ProductController@dataList');
Route::get('/product/data/list/{product_id}/{sub_prod_id}', 'ProductController@dataList');
Route::post('/product/data/list/{product_id}/{sub_prod_id}', 'ProductController@updateSubProduct');
Route::get('/product/sub-prod/delete/{sub_prod_id}', 'ProductController@deleteSubProduct');
Route::get('/product/recharge/list', 'ProductController@rechargeList');
Route::get('/product/recharge/list/{product_id}', 'ProductController@rechargeList');
Route::get('/product/recharge/list/{product_id}/{sub_prod_id}', 'ProductController@rechargeList');
Route::post('/product/recharge/list/{product_id}/{sub_prod_id}', 'ProductController@updateSubProduct');

//Setup
Route::get('/setup/payment/account', 'SetupController@paymentAccountList');
Route::get('/setup/payment/account/{acc_no}', 'SetupController@paymentAccountList');
Route::post('/setup/payment/account/{acc_no}', 'SetupController@updatePaymentAccount');
Route::get('/setup/payment/account/add/new', 'SetupController@paymentAccountAdd');
Route::post('/setup/payment/account/add/new', 'SetupController@savePaymentAccount');
Route::get('/setup/payment/account/deactivate/{acc_no}', 'SetupController@deactivatePaymentAcct');
Route::get('/setup/charges/rate', 'SetupController@chargeRateList');
Route::get('/setup/charges/rate/{conversion_id}', 'SetupController@chargeRateList');
Route::post('/setup/charges/rate/{conversion_id}', 'SetupController@updateChargeRateList');
Route::get('/setup/charges/rate/add/new', 'SetupController@addChargesRate');
Route::post('/setup/charges/rate/add/new', 'SetupController@saveChargesRate');
Route::get('/setup/charges/rate/deactivate/{conversion_id}', 'SetupController@deactivateChargesRate');
Route::get('/setup/data/balance', 'SetupController@dataBalanceList');
Route::get('/setup/data/balance/{net_code}', 'SetupController@dataBalanceList');
Route::post('/setup/data/balance/{net_code}', 'SetupController@updateDataBalanceList');
Route::get('/setup/data/balance/add/new', 'SetupController@dataBalanceAdd');
Route::post('/setup/data/balance/add/new', 'SetupController@saveDataBalance');
Route::get('/setup/data/balance/deactivate/{net_code}', 'SetupController@deactivateDataBalance');

//Communications
Route::get('/messages/list', 'CommunicationController@messageList');
Route::get('/messages/list/{contact_id}', 'CommunicationController@messageList');
Route::get("/push/notification/send", 'CommunicationController@pushNotifications');
Route::get("/push/notification/re-send/{id}", 'CommunicationController@pushNotifications');
Route::get("/push/notification/send/new", 'CommunicationController@newPushNotifications');
Route::post("/push/notification/send/new", 'CommunicationController@sendPushNotifications');
Route::get("/bulk-sms/list", 'CommunicationController@bulkSms');
Route::post("/bulk-sms/list", 'CommunicationController@sendBulkSms');


//Others
Route::get('/others/banner', 'OthersController@bannerList');
Route::get('/others/banner/{banner_id}', 'OthersController@bannerList');
Route::post('/others/banner/{banner_id}', 'OthersController@updateBanner');
Route::get('/others/banner/deactivate/{banner_id}', 'OthersController@deactivateBanner');
Route::get('/others/banner/add/new', 'OthersController@bannerAdd');
Route::post('/others/banner/add/new', 'OthersController@saveNewBanner');
Route::get("/others/faq/list", 'OthersController@faqList');
Route::get("/others/faq/deactivate/{id}", 'OthersController@deactivateFaq');
Route::get("/others/faq/list/{id}", 'OthersController@faqList');
Route::post("/others/faq/list/{id}", 'OthersController@updateFaq');
Route::get("/others/faq/add/new", 'OthersController@addFaq');
Route::post("/others/faq/add/new", 'OthersController@saveFaq');



//Promo & Earnings
Route::get("/promo/code/list", 'PromoEarningController@promoCodeList');
Route::get("/promo/code/list/{discount_code}", 'PromoEarningController@promoCodeList');
Route::post("/promo/code/list/{discount_code}", 'PromoEarningController@updatePromoCode');
Route::get("/promo/code/add/new", 'PromoEarningController@addPromoCode');
Route::post("/promo/code/add/new", 'PromoEarningController@savePromoCode');
Route::get("/promo/code/deactivate/{discount_code}", 'PromoEarningController@deactivatePromoCode');
Route::get("/referral/earnings/list", 'PromoEarningController@referralList');
Route::get("/referral/earnings/list/{ref_code}", 'PromoEarningController@referralList');



//Wallet
Route::get("/wallet/withdrawal/request/list", 'WalletController@payoutList');
Route::get("/wallet/withdrawal/approve/{payout_id}", 'WalletController@approvePayout');
Route::get("/wallet/withdrawal/decline/{payout_id}", 'WalletController@declinePayout');
Route::get("/wallet/transaction/list", 'WalletController@walletTransactions');


