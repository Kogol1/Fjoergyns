<?php

namespace App\Http\Middleware;

use App\AccessLog;
use App\Admin;
use Closure;

class IpCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $log = new AccessLog(['ip' => $request->ip(), 'page' => $request->fullUrl()]);
        $log->save();
        if (!in_array($request->ip(), Admin::getIpAdressessToArray())){
            return 'Přístup odepřen. Nepovolená ip';
        }
        return $next($request);
    }
}
