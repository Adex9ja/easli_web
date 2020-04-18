<?php

namespace App\Http\Controllers;

use App\Model\JsonResponse;
use App\Model\TableEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MobileApiErrorController extends Controller
{

    public function permissionDenied (){
        return json_encode(new JsonResponse("-01", "Authentication Fail", null));
    }

}
