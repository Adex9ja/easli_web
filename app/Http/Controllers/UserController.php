<?php

namespace App\Http\Controllers;

use App\Model\TableEntity;
use App\UserEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{


    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'authorization'])->except('index', 'validateLogin','logout');
        parent::__construct();
    }
    public function index()
    {
        if(Auth::check())
            return redirect()->intended('dashboard');
        else
            return view('index');
    }
    public function validateLogin(Request $request)
    {
        $user = $this->mproxy->validateUser($request->input());
        if($user == null)
            return redirect()->action('UserController@index')->with('msg', $this->prepareMessage(false, 'Invalid email / password'));
        else{
            Auth::login($user);
            return redirect()->intended('dashboard');
        }
    }
    public function dashboard(){
        return view('dashboard');
    }
    public function profile (){

        return view('profile');
    }
    public function updateProfile (Request $request){
        return $this->mproxy->updateUser($request->input(), $request->input('email'));
    }
    public function logout(){
        Auth::logout();
        return redirect()->action('UserController@index');
    }
    public function userList(){
        $users = $this->mproxy->getUsersList();
        return view('user_list', ['data' => $users ?? null]);
    }
    public function deactivateUser(Request $request, $args){
        return $this->mproxy->deactivateUser(base64_decode($args));
    }
    public function activateUser(Request $request, $arg){
        return $this->mproxy->activateUser(base64_decode($arg));
    }
    public function userRoles(){
        $data = $this->mproxy->getUserRoles();
        return view('user_role_list', ['data' => $data ]);
    }
    public function userRolesPages(Request $request, $args){
        $user_roles = $this->mproxy->getUserRoles();
        $user_role = base64_decode($args);
        if($request->has('privileges')){
            $this->mproxy->deleteUserPrivilege($user_role);
            $inputs = $request->input('privileges');
            $this->mproxy->addUserPrivilege($inputs, $user_role);
        }

        $privileges = $this->mproxy->getPrivileges($user_role);

        return view('user_role_list', ['data' => $user_roles, 'privileges' => $privileges ?? null]);
    }
    public function addUser(){
        $user_roles = $this->mproxy->getUserRoles();
        return view('user_add', ['user_roles' => $user_roles] );
    }
    public function viewUser(Request $request, $args){
        $user_roles = $this->mproxy->getUserRoles();
        $user = $this->mproxy->getUserByEmail(base64_decode($args));
        return view('user_add', ['user_roles' => $user_roles, 'user' => $user]);
    }
    public function saveUser(Request $request, $args = null){
        if($args != null)
            return $this->mproxy->updateUser($request->input(), $args);
        else
            return $this->mproxy->saveNewUser($request->input());
    }
    public function userDetail(Request $request, $arg = null){
        $email = base64_decode($arg);
        $transaction = $this->mproxy->getProductTransHistory($email);
        $userDetail = $this->mproxy->getUserByEmail($email);
        $walletList = $this->mproxy->getWalletTransHistory($email);
        return view('user_detail', ['transaction' => $transaction, 'userDetail' => $userDetail, 'walletList' => $walletList->toArray()]);
    }

}
