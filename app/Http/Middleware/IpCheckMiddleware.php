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
        return $next($request);
    }
}
