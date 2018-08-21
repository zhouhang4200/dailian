<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class WxApi
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
        $user = Auth::user();
        $sign = request()->header('Authorization');
        dd($sign);
        return $next($request);
    }
}
