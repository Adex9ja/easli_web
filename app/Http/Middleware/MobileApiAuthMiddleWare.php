<?php

namespace App\Http\Middleware;

use Closure;

class MobileApiAuthMiddleWare
{
    public function handle($request, Closure $next)
    {
        if($request->header('authorization') != env('API_KEY'))
            redirect()->action('MobileApiErrorController@permissionDenied')->send();
        return $next($request);
    }
}
